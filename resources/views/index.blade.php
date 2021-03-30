show.blade.php@extends('statamic::layout')
@section('title', Statamic::crumb(__('Updater!'), __('Utilities')))
@section('wrapper_class', 'max-w-full')

@section('content')

    @unless(false)

        <div class="flex items-center mb-3">
            <h1 class="flex-1">{{ __('WebApps') }}</h1>
            <a href="{{ cp_route('globals.create') }}" class="btn-primary">{{ __('Add new') }}</a>
            <a href="{{ cp_route('utilities.reposyncazure.settings') }}" class="btn ml-5">{{ __('Settings') }}</a>
        </div>

        <global-listing :globals="{{ json_encode(false) }}"></global-listing>

    @else

        @include('statamic::partials.empty-state', [
            'title' => __('Globals'),
            'description' => __('statamic::messages.global_set_config_intro'),
            'svg' => 'empty/content',
            'button_url' => cp_route('globals.create'),
            'button_text' => __('Create Global Set'),
            'can' => $user->can('create', 'Statamic\Contracts\Globals\GlobalSet')
        ])

    @endunless
    

@endsection

