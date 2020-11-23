@extends('layouts.print', [
    'title' => __('accounting::global.transfers'),
    'heading' => __('accounting::global.transfers') . '<br>' . date('Y/m/d', strtotime($from_date)) . ' - ' . date('Y/m/d', strtotime($to_date)),
    'auto_print' => false,
])
@push('content')
    <table id="transfers-table" class="table table-striped datatable">
        <thead>
        <tr>
            <th rowspan="2">@lang('accounting::global.date')</th>
            <th rowspan="2">@lang('accounting::global.amount')</th>
            <th rowspan="2">@lang('accounting::entries.details')</th>
            <th colspan="2">@lang('accounting::global.accounts')</th>
            <th rowspan="2">@lang('accounting::global.user')</th>
        </tr>
        <tr>
            <th>@lang('accounting::global.from')</th>
            <th>@lang('accounting::global.to')</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($transfers as $transfer)
            <tr class="text-center">
                <td>{{ $transfer->created_at->format('Y/m/d') }}</td>
                <td>{{ $transfer->money() }}</td>
                <td>{{ $transfer->details  }}</td>
                <td>{{ $transfer->to->name }}</td>
                <td>{{ $transfer->from->name }}</td>
                <td>{{ $transfer->auth()->name }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endpush
