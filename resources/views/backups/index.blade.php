@extends('layouts.master',[
    'datatable' => true,
    'title' => __('backups.list'),
])
@section('content')
@component('components.widget')
    @slot('title')
        <span>@lang('backups.list')</span>
    @endslot
    @slot('extra')
        <form action="" method="GET" class="form-inline guide-advanced-search">
            @csrf
            <div class="form-group mr-2">
                <i class="fa fa-cogs"></i>
                <span>@lang('global.search_advanced')</span>
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
        
    @endslot
    @slot('body')
        <table id="backups-table" class="datatable table table-bordered table-condensed table-striped table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th style="width: 130px;">@lang('global.date')</th>
                    <th>@lang('global.user')</th>
                    <th style="width: 130px;">@lang('global.options')</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($backups as $backup)
                    <tr>
                        <td>{{ $loop->index + 1 }}</td>
                        <td>{{ str_replace(['am', 'pm'], [__('global.time_modes.am'), __('global.time_modes.pm')], $backup->created_at->format('Y/m/d h:i a')) }}</td>
                        <td>{{ $backup->user->name }}</td>
                        <td>
                            <div class="btn-group">
                                @permission('backups-read')
                                    <a href="{{ route('backups.show', $backup) }}" class="btn btn-primary">
                                        <i class="fa fa-download"></i>
                                        <span class="d-none d-lg-inline">@lang('global.download')</span>
                                    </a>
                                @endpermission
                                @permission('backups-delete')
                                    <button type="button" class="btn btn-danger"
                                        data-toggle="confirm"
                                        data-title="@lang('global.confirm_delete_title')"
                                        data-text="@lang('global.confirm_delete_text')"
                                        data-form="#deleteLogForm-{{ $backup->id }}"
                                        >
                                        <i class="fa fa-trash"></i>
                                        <span class="d-none d-lg-inline">@lang('global.delete')</span>
                                    </button>
                                @endpermission
                            </div>
                            
                            @permission('backups-delete')
                                <form id="deleteLogForm-{{ $backup->id }}" action="{{ route('backups.destroy', $backup) }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            @endpermission
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endslot    
@endcomponent
@endsection
