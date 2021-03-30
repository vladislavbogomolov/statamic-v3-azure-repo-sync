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


        /*Statamic::booted(function () {
            $this->registerCpRoutes(function () {
                Route::get('/xxx', function () {
                    dd('ok!');
                })->name('show1');
            });

            $this->registerWebRoutes(function () {
                // Route::get(...);
            });

            $this->registerActionRoutes(function () {
                // Route::get(...);
            });
        });*/

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'reposyncazure');

        Utility::make('reposyncazure')
            ->title(__('Updater WebApp'))
            ->icon('addons')
            ->description(__('Sync web apps stored on Azure with Statamic V3'))
            ->routes(function (Router $router) {
                $router->get('/', [StatamicRepoSyncAzureController::class, 'show'])->name('show');
                $router->get('/settings', [StatamicRepoSyncAzureController::class, 'show'])->name('show');
                // $router->delete('/delete', [StatamicRepoSyncAzureController::class, 'destroy'])->name('destroy');
            })
            ->register();
    }
}
