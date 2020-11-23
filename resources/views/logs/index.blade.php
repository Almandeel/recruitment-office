@extends('layouts.master',[
    'datatable' => true,
    'title' => __('logs.list'),
])
@section('content')
    @if ($operation == 'all')
        @component('components.tabs')
            @slot('sticky', true)
            @slot('header')
                <form action="" method="GET" class="form-inline guide-advanced-search">
                    @csrf
                    <div class="form-group mr-2">
                        <i class="fa fa-cogs"></i>
                        <span>@lang('global.search_advanced')</span>
                    </div>
                    <div class="form-group mr-2">
                        <label for="user_id">@lang('global.users')</label>
                        <select name="user_id" id="user_id" class="form-control select2">
                            <option value="all" {{ ($user_id == 'all') ? 'selected' : '' }}>@lang('global.all')</option>
                            @foreach ($users as $usr)
                            <option value="{{ $usr->id }}" {{ ($user_id == $usr->id) ? 'selected' : '' }}>
                                {{ $usr->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mr-2">
                        <label for="type">@lang('global.operations')</label>
                        <select class="form-control operation" name="operation" id="operation">
                            <option value="all" {{ ($operation == 'all') ? 'selected' : '' }}>@lang('global.all')</option>
                            @foreach ($oprs as $opr)
                            <option value="{{ $opr }}" {{ ($opr == $operation) ? 'selected' : '' }}>@lang('logs.operations.' . $opr)
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mr-2">
                        <label for="from-date">@lang('global.from')</label>
                        <input type="date" name="from_date" id="from-date" value="{{ $from_date }}" class="form-control">
                    </div>
                    <div class="form-group mr-2">
                        <label for="to-date">@lang('global.to')</label>
                        <input type="date" name="to_date" id="to-date" value="{{ $to_date }}" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <span>@lang('global.search')</span>
                        <i class="fa fa-search"></i>
                    </button>
                </form>
            @endslot
            @slot('tools')
                @permission('logs-delete')
                    <button type="button" class="btn btn-default" data-toggle="confirm" data-title="@lang('global.confirm_reset_title')"
                        data-text="@lang('global.confirm_reset_text')" data-form="#resetLogForm">
                        <i class="fa fa-sync"></i>
                        <span class="d-none d-lg-inline">@lang('global.reset')</span>
                    </button>
                    <form id="resetLogForm" action="{{ route('logs.store', ['reset' => true]) }}" method="post">
                        @csrf
                    </form>
                @endpermission
            @endslot
            @slot('items')
                @foreach ($tabs as $tab)
                    @component('components.tab-item')
                        @slot('id', $tab)
                        @if ($loop->index == 0)
                            @slot('active', true)
                        @endif
                        @slot('title', __('logs.operations.' . $tab))
                    @endcomponent
                @endforeach
            @endslot
            @slot('contents')
                @foreach ($tabs as $tab)
                    @component('components.tab-content')
                        @slot('id', $tab)
                        @if ($loop->index == 0)
                            @slot('active', true)
                        @endif
                        @slot('content')
                            <table id="logs-table" class="datatable table table-bordered table-condensed table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>@lang('global.user')</th>
                                        <th>@lang('global.model')</th>
                                        <th>@lang('global.title')</th>
                                        <th style="width: 130px;">@lang('global.date')</th>
                                        <th style="width: 130px;">@lang('global.options')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (array_key_exists($tab, $logs->toArray()))
                                        @foreach ($logs[$tab]->sortByDesc('created_at') as $log)
                                            <tr>
                                                <td>{{ $loop->index + 1 }}</td>
                                                <td>{{ $log->user->name }}</td>
                                                <td>{{ $log->getModel() }}</td>
                                                <td>{{ $log->getTitle() }}</td>
                                                <td>{{ str_replace(['am', 'pm'], [__('global.time_modes.am'), __('global.time_modes.pm')], $log->created_at->format('Y/m/d h:i a')) }}</td>
                                                <td>
                                                    <div class="btn-group">
                                                        @permission('logs-read')
                                                            <a href="{{ route('logs.show', $log) }}" class="btn btn-info">
                                                                <i class="fa fa-eye"></i>
                                                                <span class="d-none d-lg-inline">@lang('global.show')</span>
                                                            </a>
                                                        @endpermission
                                                        @if(auth()->user()->isAbleTo('logs-update') && $log->isRestoreable())
                                                            <button type="button" class="btn btn-warning"
                                                                data-toggle="confirm"
                                                                data-title="@lang('global.confirm_restore_title')"
                                                                data-text="@lang('global.confirm_restore_text')"
                                                                data-form="#restoreLogForm"
                                                                data-action="{{ route('logs.update', ['log' => $log, 'restore' => true]) }}"
                                                                data-method="PUT"
                                                                >
                                                                <i class="fa fa-reply"></i>
                                                                <span class="d-none d-lg-inline">@lang('global.restore')</span>
                                                            </button>
                                                        @endif
                                                        @permission('logs-delete')
                                                            <button type="button" class="btn btn-danger"
                                                                data-toggle="confirm"
                                                                data-title="@lang('global.confirm_delete_title')"
                                                                data-text="@lang('global.confirm_delete_text')"
                                                                data-form="#deleteLogForm-{{ $log->id }}"
                                                                >
                                                                <i class="fa fa-trash"></i>
                                                                <span class="d-none d-lg-inline">@lang('global.delete')</span>
                                                            </button>
                                                        @endpermission
                                                    </div>
                                                    
                                                    @permission('logs-delete')
                                                        <form id="deleteLogForm-{{ $log->id }}" action="{{ route('logs.destroy', $log) }}" method="post">
                                                            @csrf
                                                            @method('DELETE')
                                                        </form>
                                                    @endpermission
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        @endslot
                    @endcomponent
                @endforeach
            @endslot
        @endcomponent
    @else
        @component('components.widget')
            @slot('sticky', true)
            @slot('noPadding', true)
            @slot('tools')
                @permission('logs-delete')
                    <button type="button" class="btn btn-default" data-toggle="confirm" data-title="@lang('global.confirm_reset_title')"
                        data-text="@lang('global.confirm_reset_text')" data-form="#resetLogForm">
                        <i class="fa fa-sync"></i>
                        <span class="d-none d-lg-inline">@lang('global.reset')</span>
                    </button>
                    <form id="resetLogForm" action="{{ route('logs.store', ['reset' => true]) }}" method="post">
                        @csrf
                    </form>
                @endpermission
            @endslot
            @slot('title')
                <i class="fa fa-list"></i>
                <span>@lang('global.operation'): @lang('logs.operations.' . $operation)</span>
            @endslot
            @slot('extra')
                <form action="" method="GET" class="form-inline guide-advanced-search">
                    @csrf
                    <div class="form-group mr-2">
                        <i class="fa fa-cogs"></i>
                        <span>@lang('global.search_advanced')</span>
                    </div>
                    <div class="form-group mr-2">
                        <label for="user_id">@lang('global.users')</label>
                        <select name="user_id" id="user_id" class="form-control select2">
                            <option value="all" {{ ($user_id == 'all') ? 'selected' : '' }}>@lang('global.all')</option>
                            @foreach ($users as $usr)
                            <option value="{{ $usr->id }}" {{ ($user_id == $usr->id) ? 'selected' : '' }}>
                                {{ $usr->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mr-2">
                        <label for="type">@lang('global.operations')</label>
                        <select class="form-control operation" name="operation" id="operation">
                            <option value="all" {{ ($operation == 'all') ? 'selected' : '' }}>@lang('global.all')</option>
                            @foreach ($oprs as $opr)
                            <option value="{{ $opr }}" {{ ($opr == $operation) ? 'selected' : '' }}>@lang('logs.operations.' . $opr)
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mr-2">
                        <label for="from-date">@lang('global.from')</label>
                        <input type="date" name="from_date" id="from-date" value="{{ $from_date }}" class="form-control">
                    </div>
                    <div class="form-group mr-2">
                        <label for="to-date">@lang('global.to')</label>
                        <input type="date" name="to_date" id="to-date" value="{{ $to_date }}" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <span>@lang('global.search')</span>
                        <i class="fa fa-search"></i>
                    </button>
                </form>
            @endslot
            @slot('body')
            <div class="table-wrapper">
                <table id="logs-table" class="datatable table table-bordered table-condensed table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>@lang('global.user')</th>
                            <th>@lang('global.model')</th>
                            <th>@lang('global.title')</th>
                            <th style="width: 130px;">@lang('global.date')</th>
                            <th style="width: 130px;">@lang('global.options')</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($logs as $log)
                        <tr>
                            <td>{{ $loop->index + 1 }}</td>
                            <td>{{ $log->user->name }}</td>
                            <td>{{ $log->getModel() }}</td>
                            <td>{{ $log->getTitle() }}</td>
                            <td>{{ str_replace(['am', 'pm'], [__('global.time_modes.am'), __('global.time_modes.pm')], $log->created_at->format('Y/m/d h:i a')) }}</td>
                            <td>
                                <div class="btn-group">
                                    @permission('logs-read')
                                        <a href="{{ route('logs.show', $log) }}" class="btn btn-info">
                                            <i class="fa fa-eye"></i>
                                            <span class="d-none d-lg-inline">@lang('global.show')</span>
                                        </a>
                                    @endpermission
                                    @if(auth()->user()->isAbleTo('logs-update') && $log->isRestoreable())
                                        <button type="button" class="btn btn-warning"
                                            data-toggle="confirm"
                                            data-title="@lang('global.confirm_restore_title')"
                                            data-text="@lang('global.confirm_restore_text')"
                                            data-form="#restoreLogForm"
                                            data-action="{{ route('logs.update', ['log' => $log, 'restore' => true]) }}"
                                            data-method="PUT"
                                            >
                                            <i class="fa fa-reply"></i>
                                            <span class="d-none d-lg-inline">@lang('global.restore')</span>
                                        </button>
                                    @endif
                                    @permission('logs-delete')
                                        <button type="button" class="btn btn-danger"
                                            data-toggle="confirm"
                                            data-title="@lang('global.confirm_delete_title')"
                                            data-text="@lang('global.confirm_delete_text')"
                                            data-form="#deleteLogForm-{{ $log->id }}"
                                            >
                                            <i class="fa fa-trash"></i>
                                            <span class="d-none d-lg-inline">@lang('global.delete')</span>
                                        </button>
                                    @endpermission
                                </div>
                                @permission('logs-delete')
                                    <form id="deleteLogForm-{{ $log->id }}" action="{{ route('logs.destroy', $log) }}" method="post">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                @endpermission
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            @endslot
        @endcomponent
    @endif
    @if(auth()->user()->isAbleTo('logs-update'))
        <form id="restoreLogForm" method="post">
            @csrf
            @method('PUT')
        </form>
    @endif
@endsection
