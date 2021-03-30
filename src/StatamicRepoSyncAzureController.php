<?php

namespace VladislavBogomolov\StatamicRepoSyncAzure;

use Illuminate\Http\Request;
use Statamic\Http\Controllers\Controller;

class StatamicRepoSyncAzureController extends Controller
{

    public function index(Request $request)
    {
        return view('reposyncazure::index', []);
    }
}
