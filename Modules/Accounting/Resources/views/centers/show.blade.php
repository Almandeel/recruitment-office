@extends('accounting::layouts.master',[
    'title' => $center->name,
    'datatable' => true,
    'accounting_modals' => [
        'center', 'account'
    ],
    'crumbs' => [
        [route('centers.index'), __('accounting::global.centers')],
        ['#', $center->name],
    ],
])

@push('content')
    @component('accounting::components.tabs')
        @slot('items')
            @component('accounting::components.tab-item')
                @slot('id', 'details')
                @slot('active', true)
                @slot('title', __('accounting::global.details'))
            @endcomponent
            @component('accounting::components.tab-item')
                @slot('id', 'entries')
                @slot('title', __('accounting::global.entries'))
            @endcomponent
            @component('accounting::components.tab-item')
                @slot('id', 'accounts')
                @slot('title', __('accounting::global.accounts'))
            @endcomponent
            @permission('centers-update')
            @component('accounting::components.tab-item')
                @slot('id', 'account-add')
                @slot('title', __('accounting::accounts.create'))
            @endcomponent
            @endpermission
        @endslot
        @slot('contents')
            @component('accounting::components.tab-content')
                @slot('id', 'details')
                @slot('active', true)
                @slot('content')
                    <table class="table table-striped">
                        <tr>
                            <th style="width: 20%;">المعرف</th>
                            <td>{{ $center->id }}</td>
                        </tr>
                        <tr>
                            <th>النوع</th>
                            <td>{{ $center->getType() }}</td>
                        </tr>
                        {{--  <tr>
                            <th>الرصيد</th>
                            <td>{{ $center->balance(true) }}</td>
                        </tr>  --}}
                        <tr>
                            <th>الخيارات</th>
                            <td class="btn-group">
                                @permission('centers-update')
                                {!! centerButton($center, 'update') !!}
                                @endpermission
                                @permission('centers-delete')
                                {!! centerButton($center, 'delete') !!}
                                @endpermission
                            </td>
                        </tr>
                    </table>
                @endslot
            @endcomponent
            @component('accounting::components.tab-content')
                @slot('id', 'entries')
                @slot('content')
                    <form action="" method="GET" class="form-inline guide-advanced-search">
                        @csrf
                        <div class="form-group mr-2">
                            <label>
                                <i class="fa fa-cogs"></i>
                                <span>@lang('accounting::global.search_advanced')</span>
                            </label>
                        </div>
                        <div class="form-group mr-2">
                            <label for="from-date">@lang('accounting::global.from')</label>
                            <input type="date" name="from_date" id="from-date" value="{{ $from_date }}" class="form-control">
                        </div>
                        <div class="form-group mr-2">
                            <label for="to-date">@lang('accounting::global.to')</label>
                            <input type="date" name="to_date" id="to-date" value="{{ $to_date }}" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <span>@lang('accounting::global.search')</span>
                            <i class="fa fa-search"></i>
                        </button>
                    </form>
                    <div class="table-wrapper">
                        <table id="entries-table" class="table table-bordered table-condensed table-striped datatable">
                            <thead>
                                <tr>
                                    <th>@lang('accounting::global.date')</th>
                                    <th>@lang('accounting::entries.details')</th>
                                    <th>@lang('accounting::global.from')</th>
                                    <th>@lang('accounting::global.to')</th>
                                    <th>@lang('accounting::global.amount')</th>
                                    <th>@lang('accounting::global.total')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $total = 0;
                                @endphp
                                @foreach ($entries as $entry)
                                @php $total += $entry->pivot->amount; @endphp
                                <tr>
                                    <td>{{ $entry->getDate() }}</td>
                                    <td>{{ $entry->details}}</td>
                                    <td>
                                        @if(count($entry->debts()) == 1)
                                            @lang('accounting::entries.from_account') {{ $entry->debts()->first()->name }}
                                        @else
                                            <div>@lang('accounting::entries.from_accounts')</div>
                                            @foreach($entry->debts() as $amount)
                                                <div>@lang('accounting::global.account') {{ $amount->name}}</div>
                                            @endforeach
                                        @endif
                                    </td>
                                    <td>
                                        @if(count($entry->credits()) == 1)
                                            @lang('accounting::entries.to_account') {{ $entry->credits()->first()->name }}
                                        @else
                                            <div>@lang('accounting::entries.to_accounts')</div>
                                            @foreach($entry->credits() as $amount)
                                                <div class="entry_to">@lang('accounting::global.account') {{ $amount->name}}</div>
                                            @endforeach
                                        @endif
                                    </td>
                                    <td>{{ number_format($entry->pivot->amount, 2) }}</td>
                                    <td>{{ number_format($total, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <th colspan="4">@lang('accounting::global.total')</th>
                                <th>{{ number_format($entries->sum('pivot.amount'), 2) }}</th>
                                <td>{{ number_format($total, 2) }}</td>
                            </tfoot>
                        </table>
                    </div>
                @endslot
            @endcomponent
            @component('accounting::components.tab-content')
                @slot('id', 'accounts')
                @slot('content')
                    <table class="table table-bordered table-striped datatable">
                        <thead>
                            <th>الرقم</th>
                            <th>الاسم</th>
                            <th>الرصيد</th>
                            <th>الخيارات</th>
                        </thead>
                        <tbody>
                            @foreach ($center->accounts as $account)
                                <tr>
                                    <td>{{ $account->number }}</td>
                                    <td>{{ $account->name }}</td>
                                    <td>{{ $account->balance(true) }}</td>
                                    <td>
                                        <div class="btn-group">
                                            @permission('accounts-read')
                                                <a href="{{ route('accounts.show', $account) }}" class="btn btn-primary">
                                                    <i class="fa fa-eye"></i>
                                                    <span class="d-sm-none d-lg-inline">عرض</span>
                                                </a>
                                            @endpermission
                                            @permission('centers-update')
                                                <button type="submit" class="btn btn-danger" data-confirm="true" data-form="#removeAccountForm{{ $account->id }}">
                                                    <i class="fa fa-times"></i>
                                                    <span class="d-xs-none d-lg-inline">@lang('accounting::global.delete')</span>
                                                </button>
                                            @endpermission
                                        </div>
                                        @permission('centers-update')
                                        <form id="removeAccountForm{{ $account->id }}" class="accountForm" action="{{ route('centers.update', $center) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="account_id" value="{{ $account->id }}" />
                                            <input type="hidden" name="operation" value="remove" />
                                            
                                        </form>
                                        @endpermission
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endslot
            @endcomponent
            @permission('centers-update')
            @component('accounting::components.tab-content')
                @slot('id', 'account-add')
                @slot('content')
                    <table class="table table-striped table-condonsed datatable">
                        <thead>
                            <tr>
                                <th>@lang('accounting::global.number')</th>
                                <th>@lang('accounting::global.account')</th>
                                <th>@lang('accounting::global.options')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($accounts as $account)
                                @component('accounting::centers._account-row')
                                    @slot('center', $center)
                                    @slot('account', $account)
                                @endcomponent
                            @endforeach
                        </tbody>
                    </table>
                @endslot
            @endcomponent
            @endpermission
        @endslot
    @endcomponent
@endpush