<?php

namespace VladislavBogomolov\StatamicRepoSyncAzure;


use Illuminate\Console\Command;


class StatamicRepoCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'webapp:download';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Downloads webapps';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        (new StatamicRepoSyncAzureController())->downloadAllProjects();
    }
}
