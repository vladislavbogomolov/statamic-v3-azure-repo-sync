<?php

namespace VladislavBogomolov\StatamicRepoSyncAzure;

use Statamic\Modifiers\Modifier;

class StatamicRepoModifier extends Modifier
{
    protected static $aliases = ['version_web_app'];
    public function index($value)
    {
        return (new StatamicRepoSyncAzureController())->getLastUpdatedTime($value);
    }
}
