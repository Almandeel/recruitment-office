@extends('accounting::layouts.master',[
    'title' => __('accounting::global.year') . ': ' . $year->id,
    'datatable' => true,
    'accounting_modals' => [
        // 'account'
    ],
    'crumbs' => [
        [route('years.index'), __('accounting::global.years')],
        ['#', __('accounting::years.show') . ': ' . $year->id],
    ],
])
@push('content')
    @component('accounting::components.widget')
        @slot('title')
            <span>@lang('accounting::global.year'): {{ $year->id }}</span>
        @endslot
        @slot('body')
            <div class="table-wrapper">
                <table class="table table-bordered table-condensed table-striped">
                    <tr>
                        <th colspan="1">@lang('accounting::global.id')</th>
                        <td colspan="1">{{ $year->id }}</td>
                    
                        <th colspan="1">@lang('accounting::global.opened_at')</th>
                        <td colspan="3">{{ $year->opened_at }}</td>
                    
                        <th colspan="1">@lang('accounting::global.closed_at')</th>
                        <td colspan="3"{{ $year->closed_at }}</td>
                    </tr>
                    <tr>
                        <th colspan="1">@lang('accounting::global.status')</th>
                        <td colspan="4"><span class="text-{{ $year->getStatusClass() }}">{{ $year->displayStatus() }}</span></td>
                        
                        <th colspan="1">@lang('accounting::global.taxes')</th>
                        <td colspan="4"{{ $year->taxes }}</td>
                    </tr>
                    <tr>
                        <th colspan="1">@lang('accounting::years.accounts.cash')</th>
                        <td colspan="4">
                            @if ($year->cashAccount)
                                @permission('accounts-read')
                                <a href="{{ route('accounts.show', $year->cashAccount) }}" class="btn btn-primary">
                                    <i class="fa fa-eye"></i>
                                    <span class="d-sm-none d-lg-inline">عرض</span>
                                </a>
                                @endpermission
                            @else
                                <span>لا يوجد</span>
                            @endif
                        </td>
                    
                        <th colspan="1">@lang('accounting::years.accounts.bank')</th>
                        <td colspan="4">
                            @if ($year->bankAccount)
                                @permission('accounts-read')
                                <a href="{{ route('accounts.show', $year->bankAccount) }}" class="btn btn-primary">
                                    <i class="fa fa-eye"></i>
                                    <span class="d-sm-none d-lg-inline">عرض</span>
                                </a>
                                @endpermission
                            @else
                                <span>لا يوجد</span>
                            @endif
                        </td>
                        {{--  <td><a href="{{ route('accounts.show', $year->bankAccount->id) }}"> {{  $year->bankAccount->name()  }}</a></td>  --}}
                    </tr>
                    <tr>
                        <th colspan="1">@lang('accounting::years.accounts.expenses')</th>
                        <td colspan="4">
                            @if ($year->expensesAccount)
                                @permission('accounts-read')
                                <a href="{{ route('accounts.show', $year->expensesAccount) }}" class="btn btn-primary">
                                    <i class="fa fa-eye"></i>
                                    <span class="d-sm-none d-lg-inline">عرض</span>
                                </a>
                                @endpermission
                            @else
                                <span>لا يوجد</span>
                            @endif
                        </td>
                    
                        <th colspan="1">@lang('accounting::years.accounts.revenues')</th>
                        <td colspan="4">
                            @if ($year->revenuesAccount)
                                @permission('accounts-read')
                                <a href="{{ route('accounts.show', $year->revenuesAccount) }}" class="btn btn-primary">
                                    <i class="fa fa-eye"></i>
                                    <span class="d-sm-none d-lg-inline">عرض</span>
                                </a>
                                @endpermission
                            @else
                                <span>لا يوجد</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th colspan="1">@lang('accounting::global.options')</th>
                        <td colspan="9">
                            @permission('years-update')
                                <form action="{{ route('years.update', $year) }}" method="post" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="active" value="{{ $year->isActive() ? 0 : 1 }}" />
                                    <button class="btn btn-{{ $year->isActive() ? 'warning' : 'success' }} confirm" 
                                        data-title="@lang('accounting::global.confirm_'.($year->isActive() ? 'deactivate' : 'activate'). '_title')"
                                        data-text="@lang('accounting::global.confirm_'.($year->isActive() ? 'deactivate' : 'activate'). '_text')"
                                        data-icon="{{ $year->isActive() ? 'warning' : 'success' }}"
                                        >
                                        <i class="fa fa-{{ $year->isActive() ? 'lock' : 'check' }}"></i>
                                        <span>{{ $year->isActive() ? __('accounting::global.deactivate') : __('accounting::global.activate') }}</span>
                                    </button>
                                </form>
                                <a href="{{ route('years.edit', $year->id) }}" class="btn btn-warning" data-toggle="tooltip" data-placement="top" title="" data-original-title="@lang('accounting::global.edit')">
                                    <i class="fa fa-edit"></i> 
                                    <span class="table-text">@lang('accounting::global.edit')</span>
                                </a>
                                @if (!$year->isClosed())
                                <a href="{{ route('years.closing', $year->id) }}" class="btn btn-warning">
                                    <i class="fa fa-ban"></i> 
                                    <span class="table-text">@lang('accounting::global.close')</span>
                                </a>
                                @endif
                            @endpermission
                            @permission('years-delete')
                                <form action="{{ route('years.destroy', $year) }}" method="post" style="display: inline;">
                                    @method('DELETE')
                                    @csrf
                                    <button type="submit" class="btn btn-danger delete" data-toggle="tooltip" data-placement="top" title="" data-original-title="@lang('accounting::global.edit')">
                                        <i class="fa fa-trash"></i> 
                                        <span class="table-text">@lang('accounting::global.delete')</span>
                                    </button>
                                </form>
                            @endpermission
                        </td>
                    </tr>
                </table>
            </div>
        @endslot
    @endcomponent
@endpush