@extends('layouts.print', [
    'title' => __('accounting::global.expenses'),
    'heading' => __('accounting::global.expenses') . '<br>' . date('Y/m/d', strtotime($from_date)) . ' - ' . date('Y/m/d', strtotime($to_date)),
    'auto_print' => false,
])
@push('content')
    <table class="table table-bordered datatable text-center">
        <thead>
        <tr>
            <th>@lang('accounting::global.id')</th>
            <th>@lang('accounting::global.date')</th>
            <th>@lang('accounting::global.amount')</th>
            <th>@lang('accounting::entries.details')</th>
            <th>@lang('accounting::global.safe')</th>
            <th>@lang('accounting::global.account')</th>
{{--            <th>@lang('accounting::global.status')</th>--}}
            <th>@lang('accounting::global.user')</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($expenses as $expense)
            <tr>
                <td>{{ $expense->id }}</td>
                <td>{{ $expense->created_at->format('Y/m/d') }}</td>
                <td>{{ $expense->money() }}</td>
                <td>{{ $expense->details }}</td>
                <td>{{ $expense->safe->name }}</td>
                <td>{{ $expense->account->display() }}</td>
{{--                <td>{{ $expense->displayStatus() }}</td>--}}
                <td>{{ $expense->auth()->name }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endpush
