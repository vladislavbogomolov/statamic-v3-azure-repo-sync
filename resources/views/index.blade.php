@extends('statamic::layout')
@section('title', Statamic::crumb(__('Updater!'), __('Utilities')))
@section('wrapper_class', 'max-w-full')

@section('content')
    <div class="flex items-center mb-3">
        <h1 class="flex-1">{{ __('WebApps') }}</h1>

        <a href="{{ cp_route('utilities.reposyncazure.create') }}" class="btn-primary">{{ __('Add') }}</a>
    </div>

    <div class="card p-0">
        <table class="data-table">
            <thead>
            <tr><!---->
                <th class="current-column"><span>Title</span></th>
                <th class="current-column"><span>Repository</span></th>
                <th class=""><span>Build</span></th>
                <th class=""><span>Last update UTC</span></th>
                <th class="actions-column"><!----></th>
            </tr>
            </thead>
            <tbody tabindex="0">
            @foreach ($config['projects'] as $key => $project)
                <tr class="sortable-row outline-none" tabindex="0"><!----> <!---->
                    <td>{{ $project['title'] }}</td>
                    <td><a href="https://dev.azure.com/{{ $organization }}/{{ $project['name'] }}" target="_blank">{{ $project['name'] }}</a></td>
                    <td>
                        @if(!is_null($project['build']))
                            <a href="https://dev.azure.com/{{ $organization }}/{{ $project['name'] }}/_build/results?buildId={{ $project['build'] }}&view=results" target="_blank">{{ $project['build'] }}</a>
                        @endif
                    </td>
                    <td><span class="font-mono text-2xs">{{ $project['updated_at'] }}</span></td>
                    <td class="actions-column">
                        <div class="popover-container dropdown-list">
                            <div aria-haspopup="true">
                                <button aria-label="Open Dropdown" class="rotating-dots-button">
                                    <svg width="12" viewBox="0 0 24 24" class="rotating-dots fill-current">
                                        <circle cx="3" cy="12" r="3"></circle>
                                        <circle cx="12" cy="12" r="3"></circle>
                                        <circle cx="21" cy="12" r="3"></circle>
                                    </svg>
                                </button>
                            </div>
                            <div class="popover" data-popper-placement="bottom-end"
                                 style="position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate(-138px, 34px);">
                                <div class="popover-content bg-white shadow-popover rounded-md">
                                    <button onclick="window.location.href='./reposyncazure/{{$key}}'">Edit</button>
                                    @if(!is_null($project['build']))
                                    <form action="{{ cp_route('utilities.reposyncazure.download', $key)  }}" method="post">
                                        @csrf
                                        <button>Update</button>
                                    </form>
                                    @endif
                                    <form action="{{ cp_route('utilities.reposyncazure.update', $key)  }}" method="post">
                                        @method('DELETE')
                                        @csrf
                                        <button class="warning">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach

            </tbody>
        </table>
    </div>
    <script>
        window.onload = function() {
            $( ".rotating-dots-button" ).on( "click", function() {
                // console.log( $( this ).parent('.popover-container.dropdown-list').text() );
                $( this ).parents('.popover-container.dropdown-list').toggleClass('popover-open')
            });

        };
    </script>
@endsection

