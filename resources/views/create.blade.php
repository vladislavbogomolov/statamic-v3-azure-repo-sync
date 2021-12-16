@extends('statamic::layout')
@section('title', Statamic::crumb(__('Updater!'), __('Utilities')))
@section('wrapper_class', 'max-w-full')

@section('content')

    @if(isset($system_errors))
        <div class="flex items-center mb-3">
            <h1 class="flex-1">{{ __('WebApps') }}</h1>
        </div>

        <div class="max-w-lg mt-2 mx-auto">
            <div class="rounded p-3 lg:px-7 lg:py-5 shadow bg-white">
                <header class="text-center mb-6">
                    <h1 class="mb-3">{{ __('Add new WebApp') }}</h1>
                </header>
                <div class="mb-2 text-center">
                    Set UPDATER_WEBAPP_ORG & UPDATER_WEBAPP_TOKEN
                </div>
            </div>
        </div>
    @else
        <div class="flex items-center mb-3">
            <h1 class="flex-1">{{ __('WebApps') }}</h1>
            <a href="{{ cp_route('globals.create') }}" class="btn-primary">{{ __('Add') }}</a>
        </div>

        <div class="max-w-lg mt-2 mx-auto">
            <form action="{{ cp_route('utilities.reposyncazure.create')  }}" method="post">
                @csrf
                <div class="rounded p-3 lg:px-7 lg:py-5 shadow bg-white">
                    <header class="text-center mb-6">
                        <h1 class="mb-3">{{ __('Add new WebApp') }}</h1>
                    </header>
                    <div class="mb-2">
                        <label class="font-bold text-base mb-sm" for="name">{{ __('Project title') }}*</label>
                        <input required name="title" type="text" class="input-text" autofocus tabindex="1">
                        @if($errors->has('title'))
                            <div class="text-2xs text-grey-60 mt-1 flex items-center">
                                {{ $errors->first('title') }}
                            </div>
                        @endif
                    </div>
                    <div class="mb-2">
                        <label class="font-bold text-base mb-sm" for="name">{{ __('Repository name') }}*</label>

                        <select name="name" id="name">
                            @foreach ($repos as $repo)
                                <option value="{{$repo['name']}}">
                                    {{$repo['name']}}
                                </option>
                            @endforeach
                        </select>

                        @if($errors->has('name'))
                            <div class="text-2xs text-grey-60 mt-1 flex items-center">
                                {{ $errors->first('name') }}
                            </div>
                        @endif
                    </div>
                </div>

                <div class="flex justify-center mt-4">
                    <button tabindex="4" class="btn-primary mx-auto btn-lg">
                        {{ __('Add')}}
                    </button>
                </div>
            </form>
        </div>
    @endif
@endsection

