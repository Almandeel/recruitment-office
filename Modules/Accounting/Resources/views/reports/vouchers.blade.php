@extends('layouts.print', [
    'title' => __('accounting::global.vouchers'),
    'heading' => __('accounting::global.vouchers') . '<br>' . date('Y/m/d', strtotime($from_date)) . ' - ' . date('Y/m/d', strtotime($to_date)),
    'auto_print' => false,
])
@push('content')
    <table class="table table-bordered datatable text-center">
        <thead>
        <tr>
            {{-- <th>@lang('accounting::global.id')</th>  --}}
            <th>@lang('accounting::global.number')</th>
            <th>@lang('accounting::global.type')</th>
            <th>@lang('accounting::global.benefit')</th>
            <th>@lang('accounting::global.amount')</th>
            <th>@lang('accounting::global.details')</th>
            <th>@lang('accounting::global.date')</th>
            <th>@lang('accounting::global.status')</th>
            <th>@lang('accounting::global.user')</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($vouchers as $voucher)
            <tr>
                {{-- <td>{{ $voucher->id }}</td> --}}
                <td>{{ $voucher->number ? $voucher->number : '...' }}</td>
                <td>{{ $voucher->displayType() }}</td>
                <td>{{ $voucher->getBenefit() }}</td>
                <td>{{ $voucher->displayAmount() }}</td>
                <td>{{ $voucher->details }}</td>
                <td>{{ $voucher->voucher_date ? date('Y/m/d', strtotime($voucher->voucher_date)) : '...' }}</td>
                <td>{{ $voucher->displayStatus() }}</td>
                <td>{{ $voucher->auth()->name }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endpush
