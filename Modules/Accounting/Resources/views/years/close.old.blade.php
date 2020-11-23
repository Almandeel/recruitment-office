@extends('layouts.accounting')
@section('title', __('accounting::years.close'))
@section('page_title')
    <i class="icon-expenses"></i>
    <span>@lang('accounting::global.years')</span>
@endpush
@push('content')
    @component('accounting::components.widget')
        @slot('id', '')
        @slot('title')
            <i class="fa fa-eye"></i>
            <span>@lang('accounting::years.close'): {{ $year->id }}</span>
        @endslot
        @slot('content')
        {{-- <form action="{{ route('years.close', $year) }}" method="post" data-parsley-validate>
            @csrf --}}
        <div class="table-wrapper">
            <table class="table table-bordered table-condensed table-striped table-hover">
                <thead>
                    <tr>
                        <th>@lang('accounting::global.from')</th>
                        <th>@lang('accounting::global.to')</th>
                        <th>@lang('accounting::global.details')</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div>-</div>
                            @php
                                $income_debt = \App\Entry::create([
                                    'type' => \App\Entry::TYPE_CLOSE,
                                    'amount' => $debts_total,
                                    'entry_date' => date('Y/m/d'),
                                    'details' => __('accounting::years.entries_details.income_summary_debt')
                                ]);
                            @endphp
                            @foreach ($debts_accounts as $account)
                                @php
                                    if($account->balance()->getAmount()){
                                        \App\AccountEntry::create([
                                            'type' => \App\AccountEntry::TYPE_DEBT,
                                            'amount' => $account->balance()->getAmount(),
                                            'account_id' => $account->id,
                                            'entry_id' => $income_debt->id,
                                        ]);
                                    }
                                @endphp
                                <div>{{ money($account->balance()->getAmount()) }}</div>
                                <input type="hidden" name="debts_accounts[]" value="{{ $account->id }}">
                            @endforeach
                        </td>
                        <td>
                            <div>-</div>
                            @foreach ($debts_accounts as $account)
                                <div>-</div>
                            @endforeach
                            <div>{{ money($debts_total) }}</div>
                            @php
                                \App\AccountEntry::create([
                                    'type' => \App\AccountEntry::TYPE_CREDIT,
                                    'amount' => $debts_total,
                                    'account_id' => $income_summary->id,
                                    'entry_id' => $income_debt->id,
                                ]);
                            @endphp
                        </td>
                        <td>
                            <div>@lang('accounting::entries.from_accounts')</div>
                            @foreach ($debts_accounts as $account)
                                <div>@lang('accounting::entries.account_prefix') {{ $account->name() }}</div>
                            @endforeach
                            <div style="margin-{{ side() }}: 30px;">@lang('accounting::entries.to_account') {{ $income_summary->name() }}</div>
                            {{-- <div class="text-center">عبارة عن قيد اقفال لجعل حسابات الإيرادات مدينة وجعل حساب ملخص الدخل دائناً بإجمالي مبلغ الإيرادات</div> --}}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            @php
                                $income_credit = \App\Entry::create([
                                    'type' => \App\Entry::TYPE_CLOSE,
                                    'amount' => $credits_total,
                                    'entry_date' => date('Y/m/d'),
                                    'details' => __('accounting::years.entries_details.income_summary_credit'),
                                ]);
                                \App\AccountEntry::create([
                                    'type' => \App\AccountEntry::TYPE_DEBT,
                                    'amount' => $credits_total,
                                    'account_id' => $income_summary->id,
                                    'entry_id' => $income_credit->id,
                                ]);
                            @endphp
                            <div>{{ $credits_total }}</div>
                        </td>
                        <td>
                            <div>-</div>
                            <div>-</div>
                            @foreach ($credits_accounts as $account)
                                <div>{{ money($account->balance()->getAmount()) }}</div>
                                <input type="hidden" name="credits_accounts[]" value="{{ $account->id }}">
                                @php
                                    if($account->balance()->getAmount()){
                                        \App\AccountEntry::create([
                                            'type' => \App\AccountEntry::TYPE_CREDIT,
                                            'amount' => $account->balance()->getAmount(),
                                            'account_id' => $account->id,
                                            'entry_id' => $income_credit->id,
                                        ]);
                                    }
                                @endphp
                            @endforeach
                        </td>
                        <td>
                            <div>@lang('accounting::entries.from_account') {{ $income_summary->name() }}</div>
                            <div>@lang('accounting::entries.to_accounts')</div>
                            @foreach ($credits_accounts as $account)
                                <div style="margin-{{ side() }}: 30px;">@lang('accounting::entries.account_prefix') {{ $account->name() }}</div>
                            @endforeach
                            {{-- <div class="text-center">عبارة عن قيد اقفال لجعل حسابات الإيرادات مدينة وجعل حساب ملخص الدخل دائناً بإجمالي مبلغ الإيرادات</div> --}}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            @php
                                $entry = null;
                                if($income_summary->balance()->getAmount()){
                                    $entry = \App\Entry::create([
                                        'type' => \App\Entry::TYPE_CLOSE,
                                        'amount' => $income_summary->balance()->getAmount(),
                                        'entry_date' => date('Y/m/d'),
                                        'details' => $income_summary->balance()->getType() == \App\Balance::TYPE_DEBT ? __('accounting::years.entries_details.profit') : __('accounting::years.entries_details.lost')
                                    ]);
                                }
                                $debt_account = null;
                                $credit_account = null;
                            @endphp
                            <div>{{ money(abs($debts_total - $credits_total)) }}</div>
                        </td>
                        <td>
                            <div>-</div>
                            <div>{{ money(abs($debts_total - $credits_total)) }}</div>
                        </td>
                        <td>
                            <div>@lang('accounting::entries.from_account') 
                                @if($credits_total > $debts_total)
                                {{ $capital->name() }}
                                @php $debt_account = $capital; @endphp
                                @else
                                {{ $income_summary->name() }}
                                @php $debt_account = $income_summary; @endphp
                            @endif</div>
                            <div style="margin-{{ side() }}: 30px;">@lang('accounting::entries.to_account') 
                                @if($credits_total > $debts_total)
                                {{ $income_summary->name() }}
                                @php $credit_account = $income_summary; @endphp
                                @else
                                {{ $capital->name() }}
                                @php $credit_account = $capital; @endphp
                            @endif</div>
                            @php
                                if($income_summary->balance()->getAmount()){
                                    $debt_ae = \App\AccountEntry::create([
                                        'type' => \App\AccountEntry::TYPE_DEBT,
                                        'amount' => $income_summary->balance()->getAmount(),
                                        'account_id' => $debt_account->id,
                                        'entry_id' => $entry->id,
                                    ]);
                                    $credit_ae = \App\AccountEntry::create([
                                        'type' => \App\AccountEntry::TYPE_CREDIT,
                                        'amount' => $income_summary->balance()->getAmount(),
                                        'account_id' => $credit_account->id,
                                        'entry_id' => $entry->id,
                                    ]);
                                }
                            @endphp
                        </td>
                    </tr>
                    @for ($i = 0; $i < count($caps_debt); $i++)
                    @php
                        $cap_debt = $caps_debt[$i];
                        $cap_credit = $caps_credit[$i];
                        if($cap_debt->balance()->getAmount() > 0){
                            $entry = \App\Entry::create([
                                'type' => \App\Entry::TYPE_CLOSE,
                                'amount' => $cap_debt->balance()->getAmount(),
                                'entry_date' => date('Y/m/d'),
                                'details' => __('accounting::years.entries_details.capitals')
                            ]);
                            $debt_ae = \App\AccountEntry::create([
                                'type' => \App\AccountEntry::TYPE_DEBT,
                                'amount' => $cap_debt->balance()->getAmount(),
                                'account_id' => $cap_credit->id,
                                'entry_id' => $entry->id,
                            ]);
                            $credit_ae = \App\AccountEntry::create([
                                'type' => \App\AccountEntry::TYPE_CREDIT,
                                'amount' => $cap_debt->balance()->getAmount(),
                                'account_id' => $cap_debt->id,
                                'entry_id' => $entry->id,
                            ]);
                        }
                    @endphp
                    <tr>
                        <td>
                            <div>{{ money($cap_debt->balance()->getAmount()) }}</div>
                            <input type="hidden" name="caps_debt[]" value="{{ $cap_debt->id }}">
                            <input type="hidden" name="caps_credit[]" value="{{ $cap_credit->id }}">
                        </td>
                        <td>
                            <div>-</div>
                            <div>{{ money($cap_debt->balance()->getAmount()) }}</div>
                        </td>
                        <td>
                            <div>@lang('accounting::entries.from_account') {{ $cap_debt->name() }}</div>
                            <div style="margin-{{ side() }}: 30px;">@lang('accounting::entries.to_account') {{ $cap_credit->name() }}</div>
                        </td>
                    </tr>
                    @endfor

                </tbody>
            </table>
        </div>
        <div class="form-group text-center">
            <input type="hidden" name="operation" value="close" />
            <input type="hidden" name="capital" value="{{ $capital->id }}" />
            <input type="hidden" name="debts_total" value="{{ $debts_total }}" />
            <input type="hidden" name="credits_total" value="{{ $credits_total }}" />
            <input type="hidden" name="income_summary" value="{{ $income_summary->id }}" />
            {{-- <a href="{{ route('years.closing', $year)}}?operation=cancel&year_id= yearId()  }}" class="btn btn-danger">
                <i class="fa fa-times"></i>
                <span>@lang('accounting::global.cancel')</span>
            </a> --}}
            <span style="display: inline-block; margin: 0px 15px;"></span>
            {{-- <button type="button" class="btn btn-success btn-submit form_confirm" data-message="@lang('accounting::years.confirm_close')">
                <span>@lang('accounting::global.close')</span>
                <i class="fa fa-power-off"></i>
            </button> --}}
        </div>
        {{-- </form> --}}
        @endslot
    @endcomponent
@endpush