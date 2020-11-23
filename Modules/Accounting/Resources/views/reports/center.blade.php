@extends('layouts.print', [
    'title' => 'حركة ' . $center->displayName(),
    'heading' => 'حركة ' . $center->displayName() . '<br>' . date('Y/m/d', strtotime($from_date)) . ' - ' . date('Y/m/d', strtotime($to_date)),
    'auto_print' => false,
])
@push('content')
    <table id="entries-table" class="table table-bordered table-condensed table-striped datatable">
        <thead>
        <tr>
            <th>@lang('accounting::global.date')</th>
            <th>@lang('accounting::entries.details')</th>
            <th>@lang('accounting::global.account')</th>
            @if($center->isCost())
                <th>الحسابات المدينة</th>
            @else
                <th>الحسابات الدائنة</th>
            @endif
            <th>@lang('accounting::global.amount')</th>
            <th>@lang('accounting::global.total')</th>
        </tr>
        </thead>
        <tbody>
            @php
                $total = 0;
            @endphp
            @foreach($center->accounts as $account)
                @php
                    $account_entries = $entries->where('pivot.account_id', $account->id);
                @endphp
                @foreach ($account_entries as $entry)
                    @php $total += $entry->pivot->amount; @endphp
                    <tr>
                        <td>{{ $entry->getDate() }}</td>
                        <td>{{ $entry->details}}</td>
                        <td>
                            <div>{{ $account->name}}</div>
                        </td>
                        <td>
                            @if($center->isProfit())
                                @if(count($entry->debts()) == 1)
                                    @lang('accounting::entries.from_account') {{ $entry->debts()->first()->name }}
                                @else
                                    <div>@lang('accounting::entries.from_accounts')</div>
                                    @foreach($entry->debts() as $amount)
                                        <div>@lang('accounting::global.account') {{ $amount->name}}</div>
                                    @endforeach
                                @endif
                            @else
                                @if(count($entry->credits()) == 1)
                                    @lang('accounting::entries.to_account') {{ $entry->credits()->first()->name }}
                                @else
                                    <div>@lang('accounting::entries.to_accounts')</div>
                                    @foreach($entry->credits() as $amount)
                                        <div class="entry_to">@lang('accounting::global.account') {{ $amount->name}}</div>
                                    @endforeach
                                @endif
                            @endif
                        </td>
                        <td>{{ number_format($entry->pivot->amount, 2) }}</td>
                        <td>{{ number_format($total, 2) }}</td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
        <tfoot>
        <th colspan="4">@lang('accounting::global.total')</th>
        <th>{{ number_format($entries->sum('pivot.amount'), 2) }}</th>
        <td>{{ number_format($total, 2) }}</td>
        </tfoot>
    </table>
@endpush
