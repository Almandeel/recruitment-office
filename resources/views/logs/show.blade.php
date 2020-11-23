@extends('layouts.master',[
    'datatable' => true,
    'title' => __('logs.list') . ': ' . $log->getOperation() . ": " . $log->getModel(),
    'crumbs' => [
        [route('logs.index'), __('logs.list')],
        ['#', $log->getOperation() . ": " . $log->getModel()]
    ]
])
@section('content')
        @component('components.widget')
            @slot('sticky', true)
            @slot('noPadding', true)
            @slot('tools')
                <div class="btn-group">
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
                    @permission('logs-delete')
                    <form id="deleteLogForm-{{ $log->id }}" action="{{ route('logs.destroy', $log) }}" method="post">
                        @csrf
                        @method('DELETE')
                    </form>
                    @endpermission
                    @if(auth()->user()->isAbleTo('logs-update') && $log->isRestoreable())
                    <form id="restoreLogForm" method="post">
                        @csrf
                        @method('PUT')
                    </form>
                    @endif
                </div>
            @endslot
            @slot('title')
                <i class="fa fa-list"></i>
                <span>@lang('global.operation'): {{$log->getOperation()}}</span>
            @endslot
            @slot('body')
            <div class="table-wrapper">
                <table id="logs-table" class="datatable table table-bordered table-condensed table-striped table-hover">
                    <tbody>
                        <tr>
                            <th style="width: 130px;">@lang('global.id')</th>
                            <td>{{ $log->id }}</td>
                        </tr>
                        <tr>
                            <th>@lang('global.model')</th>
                            <td>{{ $log->getModel() }}</td>
                        </tr>
                        <tr>
                            <th>@lang('global.title')</th>
                            <td>{{ $log->getTitle() }}</td>
                        </tr>
                        <tr>
                            <th>@lang('global.details')</th>
                            <td>{!! nl2br(e($log->details)) !!}</td>
                        </tr>
                        <tr>
                            <th>@lang('global.user')</th>
                            <td>{{ $log->user->name }}</td>
                        </tr>
                        <tr>
                            <th>@lang('global.date')</th>
                            <td>{{ str_replace(['am', 'pm'], [__('global.time_modes.am'), __('global.time_modes.pm')], $log->created_at->format('Y/m/d h:i a')) }}</td>
                        </tr>
                        @php
                            $columns = __('logs.' . $log->logable()->getRelated()->getTable() . '.columns');
                            $data = $log->getModelData();
                        @endphp
                        @if ($log->isModel() && is_array($columns))
                            <tr>
                                <th>@lang('global.data')</th>
                                <td>
                                    <div>
                                        <strong>@lang('global.model'):</strong>
                                        <span>{{ $log->getModel() }}</span>
                                    </div>
                                    @foreach ($columns as $column => $title)
                                        @if (array_key_exists($column, $data))
                                            <div>
                                                <strong>@lang($title): </strong>
                                                <span>{{ $data[$column] }}</span>
                                            </div>
                                        @endif
                                    @endforeach
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            @endslot
        @endcomponent
@endsection
