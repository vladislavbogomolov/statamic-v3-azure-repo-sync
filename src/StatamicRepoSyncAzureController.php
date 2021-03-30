<?php

namespace VladislavBogomolov\StatamicRepoSyncAzure;

use Illuminate\Http\Request;
use Statamic\Http\Controllers\Controller;

class StatamicRepoSyncAzureController extends Controller
{
    public function show(Request $request)
    {
        /*if ($file = $request->log) {
            $logviewer->setFile(urldecode($file));
        }

        if ($request->has('download')) {
            return response()->download($logviewer->pathToLogFile(urldecode($file)));
        }*/

        return view('reposyncazure::show', [
            'logs' => [],
            'files' => [],
            'currentFile' => [],
        ]);
    }
}
