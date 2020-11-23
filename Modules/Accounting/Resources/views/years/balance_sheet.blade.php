@extends('layouts.print', [
    'title' => __('accounting::lists.balance_sheet') . ': ' . $year->id,
    'heading' => '<strong>' . __('accounting::lists.balance_sheet') . '</strong><br>' . $year->id,
    'models_js' => ['Account', 'Accounts', 'Entry', 'Entries'],
    'year' => $year,
])
@push('content')
    {{--  <h3>
        <i class="fa fa-list"></i>
        <span>@lang('accounting::lists.balance_sheet'): </span>
        <span>{{ $year->id }}</span>
    </h3>  --}}
    {{--  <div id="table-wrapper" class="table-wrapper">
        <table class="table table-bordered table-condensed table-v-align-top">
            <thead>
                <tr>
                    <th class="col-md-2">@lang('accounting::global.amount')</th>
                    <th class="col-md-4">@lang('accounting::global.assets')</th>
                    <th class="col-md-2">@lang('accounting::global.amount')</th>
                    <th class="col-md-4">@lang('accounting::global.liabilities_owners')</th>
                </tr>
            </thead>
            <tbody>
                @if ($type == 'accounts')
                <tr>
                    <td style="vertical-align: top;" class="debts_amounts">
                        @foreach (assetsAccount()->children as $child)
                            @component('accounting::years._accounts_children')
                                @slot('year', $year)
                                @slot('side', 'amounts')
                                @slot('account', $child)
                            @endcomponent
                        @endforeach
                    </td>
                    <td style="vertical-align: top;">
                        @foreach (assetsAccount()->children as $child)
                            @component('accounting::years._accounts_children')
                                @slot('year', $year)
                                @slot('side', 'names')
                                @slot('account', $child)
                            @endcomponent
                        @endforeach
                    </td>
                    <td style="vertical-align: top;" class="credits_amounts">
                        @foreach ([liabilitiesAccount(), ownersAccount()] as $account)
                            @component('accounting::years._accounts_children')
                                @slot('year', $year)
                                @slot('side', 'amounts')
                                @slot('account', $account)
                            @endcomponent
                        @endforeach
                    </td>
                    <td style="vertical-align: top;">
                        @foreach ([liabilitiesAccount(), ownersAccount()] as $account)
                            @component('accounting::years._accounts_children')
                                @slot('year', $year)
                                @slot('side', 'names')
                                @slot('account', $account)
                            @endcomponent
                        @endforeach
                    </td>
                </tr>
                @else
                <div class="alert alert-danger">{{ $type }}</div>
                @endif
            </tbody>
            <tfoot>
                <tr>
                    <th  class="total_debts">0</th>
                    <th>@lang('accounting::global.total_assets')</th>
                    <th  class="total_credits">0</th>
                    <th>@lang('accounting::global.total_liabilities_owners')</th>
                </tr>
            </tfoot>
        </table>
    </div>  --}}
    <div id="table-wrapper" class="table-wrapper">
        <table class="table table-bordered table-condensed table-v-align-top">
            <thead>
                <tr>
                    <th class="col-md-2">@lang('accounting::global.amount')</th>
                    <th class="col-md-4">@lang('accounting::global.assets')</th>
                    <th class="col-md-2">@lang('accounting::global.amount')</th>
                    <th class="col-md-4">@lang('accounting::global.liabilities_owners')</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="vertical-align: top;" class="debts_amounts sides" data-sides="debts" data-side="amounts">
                        {{--  @foreach (assetsAccount()->children as $child)
                            @component('accounting::years._accounts_children')
                                @slot('year', $year)
                                @slot('side', 'amounts')
                                @slot('account', $child)
                            @endcomponent
                        @endforeach  --}}
                    </td>
                    <td style="vertical-align: top;" class="debts_names sides" data-sides="debts" data-side="names">
                        {{--  @foreach (assetsAccount()->children as $child)
                            @component('accounting::years._accounts_children')
                                @slot('year', $year)
                                @slot('side', 'names')
                                @slot('account', $child)
                            @endcomponent
                        @endforeach  --}}
                    </td>
                    <td style="vertical-align: top;" class="credits_amounts sides" data-sides="credits" data-side="amounts">
                        {{--  @foreach ([liabilitiesAccount(), ownersAccount()] as $account)
                            @component('accounting::years._accounts_children')
                                @slot('year', $year)
                                @slot('side', 'amounts')
                                @slot('account', $account)
                            @endcomponent
                        @endforeach  --}}
                    </td>
                    <td style="vertical-align: top;" class="credits_names sides" data-sides="credits" data-side="names">
                        {{--  @foreach ([liabilitiesAccount(), ownersAccount()] as $account)
                            @component('accounting::years._accounts_children')
                                @slot('year', $year)
                                @slot('side', 'names')
                                @slot('account', $account)
                            @endcomponent
                        @endforeach  --}}
                    </td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <th  class="total_debts">0</th>
                    <th>@lang('accounting::global.total_assets')</th>
                    <th  class="total_credits">0</th>
                    <th>@lang('accounting::global.total_liabilities_owners')</th>
                </tr>
            </tfoot>
        </table>
    </div>
@endpush
@push('scripts')
    <script>
        $(document).ready(function(){
            let tds = $('td.sides');
            let _total_debts = 0;
            let _total_credits = 0;
            for(let index = 0; index < tds.length; index++){
                let _td = $(tds[index]);
                let _sides = _td.data('sides');
                let _side = _td.data('side');
                let _selector = '.' + _sides +'_' + _side + '.sides';
                let _accounts = [];
                if(_sides == 'debts'){
                    _accounts.push(accounts.assets());
                }
                else if(_sides == 'credits'){
                    _accounts.push(accounts.owners());
                    _accounts.push(accounts.liabilities());
                }

                let _html = ``;
                _accounts.forEach(function(account){
                    if(_sides == 'debts' && _side == 'amounts'){
                        _total_debts += account.balances(false, {{ $year->id }})
                    }
                    else if(_sides == 'credits' && _side == 'amounts'){
                        _total_credits += account.balances(false, {{ $year->id }})
                    }
                    
                    _html += _accounts_children(account, _side);
                })
                $(_selector).html(_html);
            }

            $('.total_debts').text(number_format(_total_debts));
            $('.total_credits').text(number_format(_total_credits));
        });

        function _accounts_children(account, side = 'names'){
            let _builder = ``;
            if(account.balances(false, {{ $year->id }})){
                _builder = `<div>`;
                    if (side == 'amounts'){
                        if (account.balance(false, {{ $year->id }})){
                            _builder += `<div class="amount">` + account.balance(true, {{ $year->id }}) + `</div>`;
                        }else{
                            _builder += `<div style="color: transparent">.</div>`;
                        }
                    }else{
                        let _styles = account.isPrimary() ? 'text-decoration: underline; font-weight: bold;' : '';
                        _builder += `<div class="">`;
                            _builder += `<span class="show-print" style="` + _styles + `">`;
                                for(i = 0; i < account.parents().length; i++){
                                    _builder += `<span>_</span>`;
                                    // _builder += `<span>-</span>`;
                                }
                                _builder += `<span>` + account.getName() + `</span>`;
                            _builder += `</span>`;
                        _builder += `</div>`;
                    }
                _builder += `</div>`;
                account.children().forEach(function(child){
                    _builder += _accounts_children(child, side)
                })
            }
            return _builder;
        }
    </script>
@endpush