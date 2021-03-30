<?php

namespace VladislavBogomolov\StatamicRepoSyncAzure;

use Statamic\Facades\Utility;
use Illuminate\Routing\Router;
use Illuminate\Routing\Route;
use Statamic\Providers\AddonServiceProvider;

class StatamicRepoSyncAzureServiceProvider extends AddonServiceProvider
{
    /*protected $scripts = [
        __DIR__.'/../resources/js/logbook.js'
    ];*/

    public function boot()
    {
        parent::boot();

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'repo_sync_azure');

        Utility::make('repo_sync_azure')
            ->title(__('Updater!'))
            ->icon('book-pages')
            ->description(__('Sync web apps stored on Azure with Statamic V3'))
            ->routes(function (Router $router) {
                $router->get('/', [StatamicRepoSyncAzureController::class, 'show'])->name('show');
                // $router->delete('/delete', [StatamicRepoSyncAzureController::class, 'destroy'])->name('destroy');
            })
            ->register();

        $this->registerCpRoutes(function () {
            Route::get('/vlad', [StatamicRepoSyncAzureController::class, 'show']);
        });
    }
}
