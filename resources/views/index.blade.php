@extends('statamic::layout')
@section('title', Statamic::crumb(__('Updater!'), __('Utilities')))
@section('wrapper_class', 'max-w-full')

@section('content')
        <div class="flex items-center mb-3">
            <h1 class="flex-1">{{ __('WebApps') }}</h1>
            <a href="{{ cp_route('globals.create') }}" class="btn-primary">{{ __('Add new') }}</a>
        </div>
@endsection

