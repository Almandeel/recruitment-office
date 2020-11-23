@extends('accounting::layouts.master',[
    'title' => __('accounting::years.update'),
    'datatable' => true,
    'accounting_modals' => [
        'account'
    ],
    'crumbs' => [
        [route('years.index'), __('accounting::global.years')],
        [route('years.show', $year), __('accounting::years.show') . ': ' . $year->id],
        ['#', __('accounting::years.update')],
    ],
])
@push('content')
    <form action="{{ route('years.update', $year) }}" method="post">
        @csrf
        @method('PUT')
        @component('accounting::components.widget')
            @slot('title')
                <i class="fa fa-plus"></i>
                <span>@lang('accounting::years.create')</span>
            @endslot
            @slot('body')
                <div class="form-group row">
                    <div class="col col-xs-12">
                        <label for="last_year">
                            @lang('accounting::years.last')
                        </label>
                        <select id="last_year" class="select2 form-control" readonly name="last_year">
                            <option value="{{ null }}" {{ !$year->lastYear ? 'selected' : '' }}>لا يوجد</option>
                            @foreach ($years as $y)
                                <option value="{{ $y->id }}" {{ $y->id == $year->last_year ? 'selected' : '' }}>{{ $y->id }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col col-xs-12">
                        <label for="opened_at">
                            @lang('accounting::global.opened_at')
                        </label>
                        <input id="opened_at" readonly name="opened_at" value="{{ $year->opened_at }}" type="date" class="form-control" required/>
                    </div>
                    <div class="col col-xs-12">
                        <label for="taxes">
                            @lang('accounting::global.taxes')
                        </label>
                        <input id="taxes" name="taxes" value="{{ $year->taxes }}" type="number" class="form-control" value="0" maxlength="100" max="100"/>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col col-xs-12">
                        <label for="cash">
                            @lang('accounting::years.accounts.cash')
                        </label>
                        <select id="cash" data-placeholder="@lang('accounting::accounting.assets')" class="select2 form-control" name="default_cash">
                            <option value="{{ null }}">لا يوجد</option>
                            @component('accounting::components.account_options')
                                @slot('account', cashAccount())
                            @endcomponent
                        </select>
                    </div>
                    <div class="col col-xs-12">
                        <label for="bank">
                            @lang('accounting::years.accounts.bank')
                        </label>
                        <select id="bank" data-placeholder="@lang('accounting::accounting.assets')" class="select2 form-control" name="default_bank">
                            <option value="{{ null }}">لا يوجد</option>
                            @component('accounting::components.account_options')
                                @slot('account', bankAccount())
                            @endcomponent
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col col-xs-12">
                        <label for="expenses">
                            @lang('accounting::years.accounts.expenses')
                        </label>
                        <select id="expenses" data-placeholder="@lang('accounting::accounting.expenses')" class="select2 form-control" name="default_expenses">
                            <option value="{{ null }}">لا يوجد</option>
                            @component('accounting::components.account_options')
                                @slot('account', expensesAccount())
                            @endcomponent
                        </select>
                    </div>
                    <div class="col col-xs-12">
                        <label for="revenues">
                            @lang('accounting::years.accounts.revenues')
                        </label>
                        <select id="revenues" data-placeholder="@lang('accounting::accounting.revenues')" class="select2 form-control" name="default_revenues">
                            <option value="{{ null }}">لا يوجد</option>
                            @component('accounting::components.account_options')
                                @slot('account', revenuesAccount())
                            @endcomponent
                        </select>
                    </div>
                </div>
            @endslot
            @slot('footer')
                <legend>@lang('accounting::global.options')</legend>
                <hr>
                <div class="form-group row">
                    <div class="col">
                        <input type="radio" name="next" id="save_show" value="save_show" checked>
                        <label for="save_show" class="text-default">
                            @lang('accounting::global.save_show')
                        </label>
                    </div>
                    <div class="col">
                        <input type="radio" name="next" id="save_new" value="save_new">
                        <label for="save_new" class="text-default">
                            @lang('accounting::global.save_new')
                        </label>
                    </div>
                    <div class="col">
                        <input type="radio" name="next" id="save_list" value="save_list">
                        <label for="save_list" class="text-default">
                            @lang('accounting::global.save_list')
                        </label>
                    </div>
                    <div class="col">
                        <button class="btn btn-primary">
                            <i class="fa fa-check"></i>
                            <span>@lang('accounting::global.submit')</span>
                        </button>
                    </div>
                </div>
            @endslot
        @endcomponent
    </form>
@endpush
@push('foot')

<script>
    $(document).ready(function(){
    })
</script>
@endpush