<?php

namespace VladislavBogomolov\StatamicRepoSyncAzure;

use Statamic\Facades\Utility;
use Illuminate\Routing\Router;
# use Illuminate\Support\Facades\Route;
use Statamic\Providers\AddonServiceProvider;
use Statamic\Statamic;

class StatamicRepoSyncAzureServiceProvider extends AddonServiceProvider
{
    /*protected $scripts = [
        __DIR__.'/../resources/js/logbook.js'
    ];*/

    protected $modifiers = [
        'VladislavBogomolov\StatamicRepoSyncAzure\StatamicRepoModifier'
    ];

    protected $commands = [
        'VladislavBogomolov\StatamicRepoSyncAzure\StatamicRepoCommand'
    ];

    public function boot()
    {
        parent::boot();


        $this->publishes([
            __DIR__.'/../config/repo-sync-azure.php' => config_path('repo-sync-azure.php')
        ], 'config');

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'reposyncazure');

        Utility::make('reposyncazure')
            ->title(__('Updater WebApp'))
            ->icon('addons')
            ->description(__('Sync web apps stored on Azure with Statamic V3'))
            ->routes(function (Router $router) {
                $router->get('/', [StatamicRepoSyncAzureController::class, 'index'])->name('index');


                $router->get('/add', [StatamicRepoSyncAzureController::class, 'create'])->name('create');
                $router->post('/add', [StatamicRepoSyncAzureController::class, 'store'])->name('store');
                $router->post('/{index}/download', [StatamicRepoSyncAzureController::class, 'download'])->name('download');

                $router->get('/{index}', [StatamicRepoSyncAzureController::class, 'show'])->name('show');

                $router->put('/{index}', [StatamicRepoSyncAzureController::class, 'update'])->name('update');
                $router->delete('/{index}', [StatamicRepoSyncAzureController::class, 'delete'])->name('delete');
            })
            ->register();

        Statamic::afterInstalled(function ($command) {
            (new StatamicRepoSyncAzureController)->createConfigFile();
            // $command->call('some:command');
        });
    }
}
