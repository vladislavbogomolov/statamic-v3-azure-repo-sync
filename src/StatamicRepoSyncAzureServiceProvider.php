<?php

namespace VladislavBogomolov\StatamicRepoSyncAzure;

use Statamic\Facades\Utility;
use Illuminate\Routing\Router;
use Statamic\Providers\AddonServiceProvider;

class StatamicRepoSyncAzureServiceProvider extends AddonServiceProvider
{
    /*protected $scripts = [
        __DIR__.'/../resources/js/logbook.js'
    ];*/

    public function boot()
    {
        parent::boot();

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'logbook');

        Utility::make('updater')
            ->title(__('Updater!'))
            ->icon('book-pages')
            ->description(__('Sync web apps stored on Azure with Statamic V3'))
            ->routes(function (Router $router) {
                $router->get('/', [StatamicRepoSyncAzureController::class, 'show'])->name('show');
                // $router->delete('/delete', [StatamicRepoSyncAzureController::class, 'destroy'])->name('destroy');
            })
            ->register();
    }
}
