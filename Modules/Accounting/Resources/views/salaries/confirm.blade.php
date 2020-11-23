@extends('layouts.master', [
    'datatable' => true, 
    'datatable' => true,
    'summernote' => true,
    'title' => __('accounting::salaries.check'),
    'crumbs' => [
        [route('accounting.salaries'), __('accounting::global.salaries')],
        ['#', __('accounting::salaries.check')],
    ]
])

@push('head')
    
@endpush

@section('content')
    <section class="content">
        <form class="form" action="{{ route('accounting.salaries.confirming', $salary) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @component('components.widget')
                @slot('title', __('accounting::salaries.check'))
                @slot('body')
                    <fieldset>
                        <legend>@lang('accounting::global.employee')</legend>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>@lang('accounting::global.id')</th>
                                    <th>@lang('accounting::global.name')</th>
                                    <th>@lang('accounting::global.department')</th>
                                    <th>@lang('accounting::global.position')</th>
                                    <th>@lang('accounting::global.salary')</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ $salary->employee->id }}</td>
                                    <td>{{ $salary->employee->name }}</td>
                                    <td>{{ $salary->employee->department->title }}</td>
                                    <td>{{ $salary->employee->position->title }}</td>
                                    <td>{{ $salary->displayAmount() }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </fieldset>
                    @if ($remain_transactions->count())
                        <fieldset>
                            <legend>@lang('accounting::transactions.remain')</legend>
                            <table id="transactions-remain-table" class="table table-bordered table-striped table-hover text-center">
                                <thead>
                                    <tr>
                                        <th>@lang('accounting::global.id')</th>
                                        {{--  <th>@lang('accounting::global.user')</th>  --}}
                                        <th>@lang('accounting::global.type')</th>
                                        <th>@lang('accounting::global.amount')</th>
                                        <th>@lang('accounting::global.safe')</th>
                                        <th>@lang('accounting::global.account')</th>
                                        <th>@lang('accounting::global.delete')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($remain_transactions as $remain_transaction)
                                    <tr class="transaction-row" data-toggle="tooltip" data-placement="top" title="Tooltip on top">
                                        <td>{{ $remain_transaction->id }}</td>
                                        {{--  <td>{{ $remain_transaction->auth()->name }}</td>  --}}
                                        <td>{{ $remain_transaction->displayType() }}</td>
                                        <td><strong class="remain-amount" data-amount="{{ $remain_transaction->amount }}" data-type="{{ $remain_transaction->type }}">{{ $remain_transaction->money() }}</strong></td>
                                        <td>
                                            <input type="hidden" name="remain_ids[]" value="{{ $remain_transaction->id }}">
                                            <select name="remain_safes[]" class="form-control select2 remain_safes safes" required></select>
                                        </td>
                                        <td>
                                            <select name="remain_accounts[]" class="form-control select2 remain_accounts accounts" required></select>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-danger btn-transaction-remove">
                                                <i class="fa fa-trash"></i>
                                                <span>@lang('accounting::global.delete')</span>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </fieldset>
                    @endif
                    <fieldset>
                        <legend>
                            <i class="fas fa-list"></i>
                            <span>التفاصيل</span>
                        </legend>
                        <div class="form-group row">
                            <div class="col">
                                <label>السلفيات</label>
                                <input class="form-control" readonly type="number" name="debts" value="{{ $salary->debts }}"
                                    placeholder="السلفيات">
                            </div>
                            <div class="col">
                                <label>الخصومات</label>
                                <input class="form-control" readonly type="number" name="deducations" value="{{ $salary->deducations }}"
                                    placeholder="الخصومات">
                            </div>
                            <div class="col">
                                <label>العلاوات</label>
                                <input class="form-control" readonly type="number" name="bonus" value="{{ $salary->bonus }}"
                                    placeholder="العلاوات">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col">
                                <label>الاجمالي</label>
                                <input class="form-control" required autocomplete="off" readonly type="number" name="total" value="{{ $salary->total }}"
                                    placeholder="الاجمالي">
                            </div>
                            <div class="col">
                                <label>المرتب</label>
                                <input class="form-control" required autocomplete="off" readonly type="number" name="salary"
                                    value="{{ $salary->amount() }}" placeholder="المرتب">
                            </div>
                            <div class="col">
                                <label>الصافي</label>
                                <input class="form-control" required autocomplete="off" readonly type="number" name="net" value="{{ $salary->net }}"
                                    placeholder="الصافي">
                            </div>
                        </div>
                    </fieldset>
                @endslot
                @slot('footer')
                    <div class="form-inline">
                        <div class="form-group mr-2">
                            <label>المتبقي</label>
                            <input class="form-control" required autocomplete="off" readonly type="number" name="net" value="{{ $salary->net }}"
                        </div>
                        <div class="form-group mr-2">
                            <label>الخزنة</label>
                            <select name="safe_id" class="form-control safes" required></select>
                        </div>
                        <div class="form-group mr-2">
                            <label>الحساب</label>
                            <select name="account_id" class="form-control accounts" required></select>
                        </div>
                        <div class="form-group mr-2">
                            {{--  <input type="hidden" name="employee_id" value="{{ $salary->employee_id }}">
                            <input type="hidden" name="year" value="{{ $salary->split_month(0) }}">
                            <input type="hidden" name="month" value="{{ $salary->split_month(1) }}">  --}}
                            <button type="button" class="btn btn-primary" 
                                data-toggle="confirm" data-title="@lang('accounting::global.confirm_salary_title')"
                                data-text="@lang('accounting::global.confirm_salary_text')">اكمال العملية</button>
                        </div>
                    </div>
                @endslot
            @endcomponent
        </form>
    </section>
@endsection

@push('foot')
<script>
    let field_debts = $('input[name=debts]');
    let field_deducations = $('input[name=deducations]');
    let field_bonus = $('input[name=bonus]');
    let field_total = $('input[name=total]');
    let field_salary = $('input[name=salary]');
    let field_net = $('input[name=net]');

    function calculateSalary(){
        let type_debt = {{ Modules\Accounting\Models\Transaction::TYPE_DEBT }};
        let type_deducation = {{ Modules\Accounting\Models\Transaction::TYPE_DEDUCATION }};
        let type_bonus = {{ Modules\Accounting\Models\Transaction::TYPE_BONUS }};

        // let payed_rows = $('#transactions-payed-table tbody tr');
        let remain_amounts = $('#transactions-remain-table .remain-amount');

        let payed_debts = {{ $payed_debts->sum('amount') }};
        let payed_deducations = {{ $payed_deducations->sum('amount') }};
        let payed_bonuses = {{ $payed_bonuses->sum('amount') }};

        let remain_debts = 0;
        let remain_deducations = 0;
        let remain_bonuses = 0;

        for(let i = 0; i < remain_amounts.length; i++){
            let remain_amount = $(remain_amounts[i])
            let data = remain_amount.data()
            if(data){
                let amount = data.amount
                let type = data.type
                switch(type){
                    case type_debt: 
                        remain_debts += amount;
                        break;
                    case type_deducation: 
                        remain_deducations += amount;
                        break;
                    case type_bonus: 
                        remain_bonuses += amount;
                        break;
                }
            }
        }
        
        let debts = payed_debts + remain_debts;
        let deducations = payed_deducations + remain_deducations;
        let bonuses = payed_bonuses + remain_bonuses;
        

        let total = {{ $salary->amount() }};
        total += bonuses;
        let net = total;
        
        net -= debts;
        net -= deducations;
        
        field_net.val(net)
        field_total.val(total)
        field_debts.val(debts);
        field_deducations.val(deducations);
        field_bonus.val(bonuses);
        console.log(debts, deducations, bonuses)
    }

    $(function () {
        let _accounts_options = ``;
        let _accounts = @json($accounts);
        for(let index = 0; index < _accounts.length; index++)
        {
            let _account = _accounts[index];
            _accounts_options += `
                <option value="` + _account.id + `">` + _account.number + `-` + _account.name + `</option>
            `;
        }
        $('select.accounts').append(_accounts_options)

        let _safes_options = ``;
        let _safes = @json($safes);
        for(let index = 0; index < _safes.length; index++)
        {
            let _safe = _safes[index];
            _safes_options += `
                <option value="` + _safe.id + `">` + _safe.name + `</option>
            `;
        }
        $('select.safes').append(_safes_options)

        calculateSalary()
        $(document).on('click', '.btn-transaction-remove', function(e){
            let confirmed = confirm('سوف يتم حذف المعاملة هل انت متأكد؟')
            if(confirmed){
                $(this).parent().parent().remove()
                calculateSalary()
            }
        })
        $(document).on('click', '*[data-toggle="steps"]', function(e){
            e.preventDefault()
            $('.form-wizard .step').fadeOut()
            $('#' + $(this).data('step')).fadeIn()
        })
        /*
        let safes_options = ``;
        @foreach (safes() as $safe)
            safes_options += `<option value="{{ $safe->id }}">{{ $safe->account->number  . '-' . $safe->name }}</option>`;
        @endforeach
        $('select.safes').html(safes_options)
        
        let accounts_options = ``;
        accounts_options += `<option value="">@lang("accounting::accounts.choose")</option>`;
        @foreach (accounts(true, true) as $account)
            accounts_options += `<option value="{{ $account->id }}">{{ $account->number  . '-' . $account->name }}</option>`;
        @endforeach
        $('select.accounts').html(accounts_options)
        */
    })
</script>
@endpush