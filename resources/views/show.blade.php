@extends('statamic::layout')
@section('title', Statamic::crumb(__('Updater!'), __('Utilities')))
@section('wrapper_class', 'max-w-full')

@section('content')
        <div class="flex items-center mb-3">
            <h1 class="flex-1">{{ __('WebApps') }}</h1>
        </div>

        <div class="max-w-lg mt-2 mx-auto">
            <form action="{{ cp_route('utilities.reposyncazure.update', $index)  }}" method="post">
                @method('PUT')
                @csrf
                <div class="rounded p-3 lg:px-7 lg:py-5 shadow bg-white">
                    <header class="text-center mb-6">
                        <h1 class="mb-3">{{ __('Add new WebApp') }}</h1>
                    </header>
                    <div class="mb-2">
                        <label class="font-bold text-base mb-sm" for="name">{{ __('Project title') }}*</label>
                        <input required name="title" value="{{ $project['title'] }}" type="text" class="input-text" autofocus tabindex="1">
                        @if($errors->has('title'))
                            <div class="text-2xs text-grey-60 mt-1 flex items-center">
                                {{ $errors->first('title') }}
                            </div>
                        @endif
                    </div>
                    <div class="mb-2">
                        <label class="font-bold text-base mb-sm" for="name">{{ __('Repository name') }}*</label>
                        <input required name="name" readonly  value="{{ $project['name'] }}" type="text" class="input-text" autofocus tabindex="1">
                        @if($errors->has('name'))
                            <div class="text-2xs text-grey-60 mt-1 flex items-center">
                                {{ $errors->first('name') }}
                            </div>
                        @endif
                    </div>

                    <div class="mb-2">
                        <label class="font-bold text-base mb-sm" for="name">{{ __('Repository build') }}</label>
                        <select name="build" id="">
                            @if (is_null($project['build'])) <option value=""></option>@endif

                            @foreach ($project['builds']->value as $build)
                            <option
                                value="{{$build->id}}"
                                @if ($build->status !== 'completed' || $build->result !== 'succeeded' ) disabled @endif value="{{$build->id}}"
                                @if ($build->id === $project['build'] ) selected @endif value="{{$build->id}}"
                            >
                                {{ $build->id }}
                                {{ $build->status }}
                                @if (isset($build->result)) {{ $build->result }} @endif
                                {{ $build->buildNumber }}

                            </option>
                            @endforeach
                        </select>

                        @if($errors->has('build'))
                            <div class="text-2xs text-grey-60 mt-1 flex items-center">
                                {{ $errors->first('build') }}
                            </div>
                        @endif
                    </div>
                </div>

                <div class="flex justify-center mt-4">
                    <button tabindex="4" class="btn-primary mx-auto btn-lg">
                        {{ __('Update')}}
                    </button>
                </div>
            </form>
        </div>
@endsection

