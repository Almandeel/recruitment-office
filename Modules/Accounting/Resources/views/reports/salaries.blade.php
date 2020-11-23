@extends('layouts.print', [
    'title' => __('accounting::global.salaries'),
    'heading' => __('accounting::global.salaries') . '<br>' . date('Y/m/d', strtotime($from_date)) . ' - ' . date('Y/m/d', strtotime($to_date)),
    'auto_print' => false,
])
@push('content')
    <table id="datatable" class="datatable table table-bordered table-striped">
        <thead>
        <tr>
            <th>@lang('accounting::global.id')</th>
            <th>@lang('accounting::global.month')</th>
            <th>@lang('accounting::global.employee')</th>
            <th>@lang('accounting::global.salary')</th>
            <th>@lang('accounting::salaries.bonus')</th>
            <th>@lang('accounting::salaries.debts')</th>
            <th>@lang('accounting::salaries.deducations')</th>
            <th>@lang('accounting::salaries.net')</th>
            <th>@lang('accounting::salaries.total')</th>
            <th>@lang('accounting::global.status')</th>
            <th>@lang('accounting::global.user')</th>
            <th>@lang('accounting::global.date')</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($salaries as $salary)
            <tr>
                <td>{{ $salary->id }}</td>
                <td>{{ $salary->month }}</td>
                <td>{{ $salary->employee->name }}</td>
                <td>{{ $salary->displayAmount() }}</td>
                <td>{{ $salary->money('bonus') }}</td>
                <td>{{ $salary->money('debts') }}</td>
                <td>{{ $salary->money('deducations') }}</td>
                <td>{{ $salary->money('net') }}</td>
                <td>{{ $salary->money('total') }}</td>
                <td>{{ $salary->displayStatus() }}</td>
                <td>{{ $salary->auth()->name }}</td>
                <td>{{ $salary->created_at->format('Y/m/d') }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endpush
