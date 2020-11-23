@extends('accounting::layouts.master' ,[
    'title' => __('accounting::expenses.create'),
    'accounting_modals' => ['transfer', 'safe'], 
    'datatable' => true,
    'crumbs' => [
        [route('expenses.index'), __('accounting::global.expenses')],
        ['#', __('accounting::expenses.create')],
    ],
])
@push('content')
    <form class="expenseForm" action="{{ route('expenses.store') }}" method="POST">
        @csrf
        @component('accounting::components.widget')
            @slot('title', __('accounting::expenses.details'))
            @slot('body')
                <div class="form-group">
                    <label>@lang('accounting::global.safe')</label>
                    <select id="safeId" name="safe_id" class="form-control" required>
                        @foreach (safes() as $safe)
                            <option value="{{ $safe->id }}">{{ $safe->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>@lang('accounting::global.account')</label>
                    <select id="accountId" name="account_id" class="form-control" required>
                        @foreach (expensesAccount()->children(true) as $account)
                            @if ($account->children->count() <= 0)
                            <option value="{{ $account->id }}">{{ $account->number . '-' . $account->name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>@lang('accounting::global.amount')</label>
                    <input id="amount"  class="form-control amount" autocomplete="off" type="number" min="1" name="amount" placeholder="@lang('accounting::global.amount')">
                </div>
                <div class="form-group">
                    <label>@lang('accounting::global.details')</label>
                    <textarea id="details"  class="form-control details" autocomplete="off" type="text" name="details" placeholder="@lang('accounting::global.details')"></textarea>
                </div>    
            @endslot
            @slot('footer')
                <button type="submit" class="btn btn-primary">@lang('accounting::global.save')</button>
                {!! cancelButton() !!}
            @endslot
        @endcomponent
    </form>
@endpush

@push('foot')
@endpush