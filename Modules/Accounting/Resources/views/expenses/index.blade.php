@extends('accounting::layouts.master' ,[
    'title' => __('accounting::global.expenses'),
    'accounting_modals' => [
        //'transfer', 
    'safe'], 
    'datatable' => true,
    'crumbs' => [
        ['#', __('accounting::global.expenses')],
    ],
])
@push('content')
@component('accounting::components.widget')
    @slot('tools')
        @permission('expenses-create')
        <a href="{{ route('expenses.create') }}" class="btn btn-primary">
            <i class="fa fa-plus"></i>
            <span>@lang('accounting::expenses.add')</span>
        </a>
        @endpermission
    @endslot
    @slot('title', __('accounting::expenses.list'))
    @slot('extra')
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
    @endslot
    @slot('body')
        <table id="expenses-table" class="table table-striped datatable">
            <thead>
                <tr>
                    <th>@lang('accounting::global.safe')</th>
                    <th>@lang('accounting::global.account')</th>
                    <th>@lang('accounting::global.amount')</th>
                    {{--  <th>@lang('accounting::global.details')</th>  --}}
                    <th>@lang('accounting::global.user')</th>
                    <th>@lang('accounting::global.create_date')</th>
                    <th>@lang('accounting::global.options')</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($expenses as $expense)
                    <tr class="text-center">
                        <td>{{ $expense->safe->name }}</td>
                        <td>{{ $expense->account->display() }}</td>
                        <td>{{ number_format($expense->amount, 2) }}</td>
                        {{--  <td>{{ $expense['details']  }}</td>  --}}
                        <td>{{ $expense->auth()->name }}</td>
                        <td>{{ $expense->created_at->format('Y-m-d') }}</td>
                        <td>
                            <div class="btn-group">
                                @permission('expenses-read')
                                {!! expenseButton($expense, 'preview') !!}
                                @endpermission
                                @permission('expenses-update')
                                {!! expenseButton($expense, 'update') !!}
                                @endpermission
                                @permission('expenses-delete')
                                {!! expenseButton($expense, 'delete') !!}
                                @endpermission
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endslot
@endcomponent
@endpush
@push('foot')
@endpush