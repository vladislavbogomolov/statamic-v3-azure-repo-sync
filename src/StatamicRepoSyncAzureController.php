<?php

namespace VladislavBogomolov\StatamicRepoSyncAzure;

use Illuminate\Http\Request;
use phpDocumentor\Reflection\Project;
use Statamic\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use Symfony\Component\Yaml\Yaml;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Validation\Rule;


class StatamicRepoSyncAzureController extends Controller
{

    protected $_config_file = 'reposyncazure.yaml';
    protected $_config_path = 'config/';
    protected $_config_organization;
    protected $_config_token;
    private $_config;
    private $_client;
    private $_webappsDir;
    private $_headers;

    public function __construct()
    {
        $this->_config = Yaml::parseFile( resource_path($this->_config_path . $this->_config_file));
        $this->_config_organization = config('repo-sync-azure.organization');
        $this->_config_token = config('repo-sync-azure.token');
        $this->_headers = [
            'Content-Type:application/json',
            'Authorization: Basic ' . base64_encode(":" . $this->_config_token)
        ];
    }

    private function saveConfig()
    {
        $yaml = Yaml::dump($this->_config);
        file_put_contents(resource_path($this->_config_path . $this->_config_file), $yaml);
    }

    public function isExists($repoName)
    {
        foreach ($this->_config['projects'] as &$project)
        {
            if ($project['name'] === $repoName) {
                return true;
            }
        }

        return false;
    }


    public function getBuilds($repo)
    {
        $url = 'https://dev.azure.com/' . $this->_config_organization . '/' . $repo . '/_apis/build/builds/?api-version=5.1';
        $builds = $this->request($url);
        return $builds;
    }

    public function getRepos()
    {
        $url = 'https://dev.azure.com/'.$this->_config_organization.'/_apis/projects?api-version=6.0';

        return \Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Basic'.base64_encode(":" . $this->_config_token)
        ])->get($url)->json();
    }

    private function request($url, $encode = true)
    {
        $url = str_replace(" ", "%20", $url);
        if (is_null($this->_client)) {
            $headers = array(
                'Content-Type:application/json',
                'Authorization: Basic ' . base64_encode(":" . $this->_config_token)
            );
            $this->_client = curl_init();
            curl_setopt($this->_client, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($this->_client, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($this->_client, CURLOPT_TIMEOUT, 30);
        }
        curl_setopt($this->_client, CURLOPT_URL, $url);

        $result = curl_exec($this->_client);

        if ($encode == false) {
            return $result;
        }

        $json = json_decode($result);
        return $json;
    }

    public function index(Request $request)
    {

        return view('reposyncazure::index', [
            'config' => $this->_config,
            'organization' => $this->_config_organization
        ]);
    }

    public function create(Request $request)
    {

        return view('reposyncazure::create', [
            'repos' => $this->getRepos()['value']
        ]);
    }

    public function update(Request $request, $index)
    {

        $validated = $request->validate([
            'name' => ['required', 'string', new isValidRepoRule],
            'title' => 'required',
            'build' => 'nullable'
        ]);

        $validated['updated_at'] = null;
        $validated['id'] = $this->getBuilds($validated['name'])->value[0]->project->id;

        $this->_config['projects'][$index] = $validated;
        $this->saveConfig();

        return redirect()->route('statamic.cp.utilities.reposyncazure.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', new isValidRepoRule, new isUniqueRepoRule],
            'title' => 'required',
        ]);

        $validated['updated_at'] = null;
        $validated['build'] = null;
        $validated['id'] = $this->getBuilds($validated['name'])->value[0]->project->id;

        $this->_config['projects'][] = $validated;
        $this->saveConfig();

        return redirect()->route('statamic.cp.utilities.reposyncazure.index');

    }

    public function show($index)
    {
        $this->_config['projects'][$index]['builds'] = $this->getBuilds($this->_config['projects'][$index]['name']);
        return view('reposyncazure::show', [
            'project' => $this->_config['projects'][$index],
            'index' => $index
        ]);
    }

    public function delete($index) {
        $projects = $this->_config['projects'];
        unset($projects[$index]);
        $this->_config['projects'] = array_values($projects);
        $this->saveConfig();
        return redirect()->route('statamic.cp.utilities.reposyncazure.index');
    }

    public function download($index) {


        $project = $this->_config['projects'][$index];
        $this->_createDirectory($project);

        $response = \Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Basic'.base64_encode(":" . $this->_config_token)
        ])->get('https://dev.azure.com/HumanCompany/'.$project['id'].'/_apis/build/Builds/'.$project['build'].'/artifacts');

        if (!$response->successful()) {
            dd('ko');
        }

        $response = $response->json();
        $urlArtifact = $response['value'][0]['resource']['downloadUrl'];


        $drop = \Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Basic'.base64_encode(":" . $this->_config_token)
        ])->get($urlArtifact);

        $saveArtifactZip = public_path($this->_webappsDir.$project['name'].'/artifact.zip');
        $distDir = public_path($this->_webappsDir.$project['name'].'/dist_addon');
        file_put_contents( $saveArtifactZip, $drop );

        $zip = new \ZipArchive();

        if (!$zip->open($saveArtifactZip)) {
            dd('ko');
        }

        \File::deleteDirectory($distDir);
        \File::makeDirectory($distDir, 0755, true, true);
        $zip->extractTo($distDir);
        $zip->close();

        \File::delete($saveArtifactZip);

        if (!$zip->open($distDir.'/drop/'.$project['build'].'.zip')) {
            dd('ko');
        }
        $zip->extractTo(public_path($this->_webappsDir.$project['name']));
        $zip->close();
        \File::deleteDirectory($distDir);

        $project['updated_at'] = date("Y-m-d H:i:s");
        $this->_config['projects'][$index] = $project;
        $this->saveConfig();


        return redirect()->route('statamic.cp.utilities.reposyncazure.index');
    }

    private function _createDirectory($project)
    {

        $this->_webappsDir = 'webapps/';
        $projectDir = $this->_webappsDir.$project['name'].'/';

        if (!\File::isDirectory(public_path($this->_webappsDir))) {
            \File::makeDirectory(public_path($this->_webappsDir), 0755, true, true);
        }

        if (!\File::isDirectory(public_path($projectDir))) {
            \File::makeDirectory(public_path($projectDir), 0755, true, true);
        }
    }


    public function createConfigFile() {


        if (!\File::exists(resource_path($this->_config_path . $this->_config_file))) {

            if (!\File::isDirectory(resource_path($this->_config_path))) {
                \File::makeDirectory(resource_path($this->_config_path), 0755, true, true);
            }

            $yaml = Yaml::dump([
                        'projects' => []
                    ]);
            file_put_contents(resource_path($this->_config_path . $this->_config_file), $yaml);
        }
    }
}

class isValidRepoRule implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */

    public $msg;

    public function passes($attribute, $value)
    {
        $repos = (new StatamicRepoSyncAzureController)->getBuilds($value);
        if (isset($repos->message)) {
            $this->msg = $repos->message;
        }
        return !isset($repos->message);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->msg;
    }
}

class isUniqueRepoRule implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */


    public function passes($attribute, $value)
    {
        return !(new StatamicRepoSyncAzureController)->isExists($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Should be unique';
    }
}
