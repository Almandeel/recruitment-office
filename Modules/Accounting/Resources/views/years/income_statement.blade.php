@extends('layouts.print', [
    'title' => __('accounting::lists.income_statement') . ': ' . $year->id,
    'heading' => __('accounting::lists.income_statement') . ': ' . $year->id,
    'snippts' => true,
])
@push('content')
    <h1>
        <i class="fa fa-list"></i>
        <span>@lang('accounting::lists.income_statement'): {{ $year->id }}</span>
    </h1>
    <div class="table-wrapper">
        <table class="table table-bordered table-condensed table-striped">
            <tbody>
                <tr>
                    <td class="col-md-2">
                        <div>-</div>
                        @component('accounting::years.income_amounts')
                            @slot('year', $year)
                            @slot('type', 'amounts')
                            @slot('account', revenuesAccount())
                            @slot('side', 'revenues')
                        @endcomponent
                        <div><strong style="">{{ number_format($revenues_amount, 2) }}</strong></div>
                    </td>
                    <td class="col-md-2">-</td>
                    <td class="col-md-8">
                        <div><strong style="text-decoration: underline">{{ revenuesAccount()->name }}</strong></div>
                        @component('accounting::years.income_amounts')
                            @slot('year', $year)
                            @slot('type', 'names')
                            @slot('account', revenuesAccount())
                            @slot('side', 'revenues')
                        @endcomponent
                        <div><strong style="">@lang('accounting::global.total_revenues')</strong></div>

                    </td>
                </tr>
                
                <tr>
                    <td>-</td>
                    <td>
                        <div>-</div>
                        @component('accounting::years.income_amounts')
                            @slot('year', $year)
                            @slot('type', 'amounts')
                            @slot('account', expensesAccount())
                            @slot('side', 'expenses')
                        @endcomponent
                        <div><strong style="">{{ number_format($expenses_amount, 2) }}</strong></div>
                    </td>
                    <td>
                        <div><strong style="text-decoration: underline">{{ expensesAccount()->name }}</strong></div>
                        @component('accounting::years.income_amounts')
                            @slot('year', $year)
                            @slot('type', 'names')
                            @slot('account', expensesAccount())
                            @slot('side', 'expenses')
                        @endcomponent
                        <div><strong style="">@lang('accounting::global.total_expenses')</strong></div>
                    </td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <th class="revenues">
                        {{--  @if($revenues_amount > $expenses_amount)
                            <span class="success">{{ number_format(($revenues_amount - $expenses_amount), 2) }}</span>
                        @else
                            -
                        @endif  --}}
                    </th>
                    <th class="expenses">
                        {{--  @if($expenses_amount > $revenues_amount)
                            <span class="error">{{ number_format(($expenses_amount - $revenues_amount), 2) }}</span>
                        @else
                            -
                        @endif  --}}
                    </th>
                    <th class="title">
                        {{--  @if($revenues_amount > $expenses_amount)
                            <strong style="text-decoration: underline" class="success">@lang('accounting::global.net_profit')</strong>
                        @elseif($expenses_amount > $revenues_amount)
                            <strong style="text-decoration: underline" class="error">@lang('accounting::global.net_lost')</strong>
                        @else
                            لا يوجد ربح او خسارة
                        @endif  --}}
                    </th>
                </tr>
            </tfoot>
        </table>
    </div>
@endpush
@push('scripts')
<script>
    $(document).ready(function(){
        let revenues_account = @json(revenuesAccount());
        let expenses_account = @json(expensesAccount());
        let children = [];
        children[revenues_account.id] = @json(revenuesAccount()->children(true));
        children[expenses_account.id] = @json(expensesAccount()->children(true));
        let revenues_accounts = $('.revenues-account-balance');
        let expenses_accounts = $('.expenses-account-balance');
        let revenues = 0;
        let expenses = 0;
        let net = {
            'title' : 'لا يوجد ربح او خسارة',
            'amount' : 0,
            'side': '',
            'class': ''
        };

        for(let index = 0; index < revenues_accounts.length; index++)
        {
            revenues += number_filter($(revenues_accounts[index]).text());
        }
        
        for(let index = 0; index < expenses_accounts.length; index++)
        {
            expenses += number_filter($(expenses_accounts[index]).text());
        }

        if(revenues > expenses){
            net.title = `@lang('accounting::global.net_profit')`;
            net.amount = revenues - expenses;
            net.class = 'success';
            net.side = 'revenues';
        }
        else if(expenses > revenues){
            net.title = `@lang('accounting::global.net_lost')`;
            net.amount = expenses - revenues;
            net.class = 'error';
            net.side = 'expenses';
        }
        
        if(net.side == 'revenues')
        {
            $('tfoot tr th.revenues').text(number_format(net.amount))
            $('tfoot tr th.expenses').text('-')
        }
        
        else if(net.side == 'expenses')
        {
            $('tfoot tr th.expenses').text(number_format(net.amount))
            $('tfoot tr th.revenues').text('-')
        }
        else{
            $('tfoot tr th.revenues').text(0.00)
            $('tfoot tr th.expenses').text(0.00)
        }
        
        $('tfoot tr th.title').html(`<strong style="text-decoration: underline" class="` + net.class + `">` + net.title + `</strong>`)
    });

    function setAccounts(account, type = 'names', side = 'revenues')
    {
        let html = ``;
        if (account.isSecondary()){
            html += `<div class="` + $side + `-account-balance">` + account.balance(true, {{ $year->id }}) + `</div>`;
        }else{
            html += `<div class="transparent">-</div>`;
        }

        return html;
    }
</script>
@endpush