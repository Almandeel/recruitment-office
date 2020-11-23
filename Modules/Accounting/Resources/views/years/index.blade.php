@extends('accounting::layouts.master',[
    'title' => __('accounting::global.years'),
    'datatable' => true,
    'accounting_modals' => [
        
    ],
    'crumbs' => [
        ['#', __('accounting::global.years')],
    ],
    /*
    'guides' => [
        [
            'element' => '.guide-create-btn',
            'title' =>  __('accounting::years.guides.index.guide_create_btn.title'),
            'description' => __('accounting::years.guides.index.guide_create_btn.description'),
            'position' => 'right',
        ],
    ],
    */
])
@push('content')
@component('accounting::components.widget')
    @slot('title')
        <i class="fa fa-file"></i>
        <span>@lang('accounting::global.years')</span>
    @endslot
    @slot('tools')
        @permission('years-create')
            <a href="{{ route('years.create') }}" class="btn btn-primary guide-create-btn">
                <i class="fa fa-plus"></i>
                <span>اضافة</span>
            </a>
        @endpermission
    @endslot
    @slot('body')
    <div class="table-wrapper">
        <table id="years-table" class="datatable table table-bordered table-hover table-striped">
            <thead>
                <tr>
                    <th>@lang('accounting::global.id')</th>
                    <th>@lang('accounting::global.opened_at')</th>
                    <th>@lang('accounting::global.closed_at')</th>
                    <th>@lang('accounting::global.status')</th>
                    <th>@lang('accounting::global.options')</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($years as $year)
                    <tr>
                        <td>{{ $year->id }}</td>
                        <td>{{ $year->opened_at }}</td>
                        <td>{{ $year->closed_at }}</td>
                        <td><span class="text-{{ $year->getStatusClass() }}">{{ $year->displayStatus() }}</span></td>
                        <td>
                            @permission('years-update')
                                <form id="toggle-active-form-{{ $year->id }}" action="{{ route('years.update', $year) }}" method="post" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="active" value="{{ $year->isActive() ? 0 : 1 }}" />
                                </form>
                            @endpermission
                            <div class="btn-group">
                                @permission('years-update')
                                    <button class="btn btn-{{ $year->isActive() ? 'warning' : 'success' }}"
                                        data-toggle="confirm" data-form="#toggle-active-form-{{ $year->id }}" 
                                        data-title="@lang('accounting::global.confirm_'.($year->isActive() ? 'deactivate' : 'activate'). '_title')"
                                        data-text="@lang('accounting::global.confirm_'.($year->isActive() ? 'deactivate' : 'activate'). '_text')"
                                        data-icon="{{ $year->isActive() ? 'warning' : 'success' }}"
                                        >
                                        <i class="fa fa-{{ $year->isActive() ? 'lock' : 'check' }}"></i>
                                        <span>{{ $year->isActive() ? __('accounting::global.deactivate') : __('accounting::global.activate') }}</span>
                                    </button>
                                @endpermission
                                @permission('years-read')
                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-list"></i>
                                        <span>@lang('accounting::lists.list')</span>
                                    </button>
                                    <div class="dropdown-menu">
                                        <div class="dropdown-item"><a href="{{ route('years.income_statement', $year) }}">@lang('accounting::lists.income_statement')</a></div>
                                        <div class="dropdown-item"><a href="{{ route('years.trial_balance', ['year' => $year, 'by' => 'totals']) }}">@lang('accounting::lists.trial_balance_by_totals')</a></div>
                                        <div class="dropdown-item"><a href="{{ route('years.trial_balance', ['year' => $year, 'by' => 'balances']) }}">@lang('accounting::lists.trial_balance_by_balances')</a></div>
                                        <div class="dropdown-divider"></div>
                                        <div class="dropdown-item"><a href="{{ route('years.balance_sheet', $year) }}">@lang('accounting::lists.balance_sheet')</a></div>
                                    </div>
                                @endpermission
                                @permission('years-read')
                                    <a href="{{ route('years.show', $year->id) }}" class="btn btn-info" data-toggle="tooltip" data-placement="top" title="" data-original-title="@lang('accounting::global.edit')">
                                        <i class="fa fa-eye"></i> 
                                        <span class="table-text">@lang('accounting::global.show')</span>
                                    </a>
                                @endpermission
                                {{--  @permission('years-update')
                                    @php
                                        $activeYearId = year() ? yearId() : 0;
                                    @endphp
                                    @if($year->id == $activeYearId)
                                        <button class="btn btn-{{ $year->isActive() ? 'warning' : 'success' }}"
                                            data-confirm="true" data-form="#activateForm-{{ $year->id }}" 
                                            data-title="@lang('accounting::global.confirm_'.($year->isActive() ? 'deactivate' : 'activate'). '_title')"
                                            data-text="@lang('accounting::global.confirm_'.($year->isActive() ? 'deactivate' : 'activate'). '_text')"
                                            data-icon="{{ $year->isActive() ? 'warning' : 'success' }}"
                                            >
                                            <i class="fa fa-{{ $year->isActive() ? 'lock' : 'check' }}"></i>
                                            <span>{{ $year->isActive() ? __('accounting::global.deactivate') : __('accounting::global.activate') }}</span>
                                        </button>
                                    @endif
                                @endpermission  --}}
                                @permission('years-update')
                                    <a href="{{ route('years.edit', $year->id) }}" class="btn btn-warning" data-toggle="tooltip" data-placement="top" title="" data-original-title="@lang('accounting::global.edit')">
                                        <i class="fa fa-edit"></i> 
                                        <span class="table-text">@lang('accounting::global.edit')</span>
                                    </a>
                                @endpermission
                                @permission('years-update')
                                @if (!$year->isClosed())
                                    <a href="{{ route('years.closing', ['year' => $year, 'step' => 'use_entries']) }}" class="btn btn-warning">
                                        <i class="fa fa-ban"></i> 
                                        <span class="table-text">@lang('accounting::global.close')</span>
                                    </a>
                                @endif
                                @endpermission
                                @permission('years-delete')
                                    <button type="button" class="btn btn-danger btn-xs" 
                                        data-confirm="true" data-form="#deleteForm-{{ $year->id }}" 
                                        data-toggle="tooltip" data-placement="top" title="" data-original-title="@lang('accounting::global.delete')">
                                        <i class="fa fa-trash"></i> 
                                        <span class="table-text">@lang('accounting::global.delete')</span>
                                    </button>
                                @endpermission
                            </div>
                            {{--  @permission('years-update')
                                @if($year->id == $activeYearId)
                                <form id="activateForm-{{ $year->id }}" action="{{ route('years.update', $year) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="active" value="{{ $year->isActive() ? 0 : 1 }}" />
                                </form>
                                @endif
                            @endpermission  --}}
                            @permission('years-delete')
                                <form id="deleteForm-{{ $year->id }}" action="{{ route('years.destroy', $year) }}" method="post" style="display: inline;">
                                    @method('DELETE')
                                    @csrf
                                </form>
                            @endpermission
                            @permission('years-update')
                            @endpermission
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endslot
@endcomponent
@endpush
@push('foot')
    <script>
    </script>
@endpush