@extends('layouts.print', [
    'title' => __('accounting::global.transactions'),
    'heading' => __('accounting::global.transactions') . '<br>' . date('Y/m/d', strtotime($from_date)) . ' - ' . date('Y/m/d', strtotime($to_date)),
    'auto_print' => false,
])
@push('content')
    <table id="datatable" class="datatable table table-bordered table-striped">
        <thead>
        <tr>
            <th>@lang('accounting::global.id')</th>
            <th>@lang('accounting::global.type')</th>
            <th>@lang('accounting::global.employee')</th>
            <th>@lang('accounting::global.amount')</th>
            <th>@lang('accounting::global.details')</th>
            <th>@lang('accounting::global.status')</th>
            <th>@lang('accounting::global.user')</th>
            <th>@lang('accounting::global.date')</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($transactions as $transaction)
            <tr>
                <td>{{ $transaction->id }}</td>
                <td>{{ $transaction->displayType() }}</td>
                <td>{{ $transaction->employee->name }}</td>
                <td>{{ $transaction->money() }}</td>
                <td>{{ $transaction->details }}</td>
                <td>{{ $transaction->displayStatus() }}</td>
                <td>{{ $transaction->auth()->name }}</td>
                <td>{{ $transaction->created_at->format('Y/m/d') }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endpush
