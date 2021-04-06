<?php

namespace VladislavBogomolov\StatamicRepoSyncAzure;

use Statamic\Modifiers\Modifier;

class Repeat extends Modifier
{
    public function index($value, $params, $context)
    {
        // Repeat twice by default
        $repeat = 2;

        // Get the parameter, if there is one
        if ($param = array_get($params, 0)) {
            // Either get the variable from the context, or if it doesn't exist,
            // use the parameter itself - we'll assume its a number.
            $repeat = array_get($context, $param, $param);
        }

        // Repeat!
        return str_repeat($value, $repeat);
    }
}
