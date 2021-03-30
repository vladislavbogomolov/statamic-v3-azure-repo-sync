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

    public function boot()
    {
        parent::boot();


        $this->publishes([
            __DIR__.'/../config/updater_webapp.php' => config_path('updater_webapp.php')
        ], 'config');

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'reposyncazure');

        Utility::make('reposyncazure')
            ->title(__('Updater WebApp'))
            ->icon('addons')
            ->description(__('Sync web apps stored on Azure with Statamic V3'))
            ->routes(function (Router $router) {
                $router->get('/', [StatamicRepoSyncAzureController::class, 'index'])->name('show');
            })
            ->register();
    }
}
