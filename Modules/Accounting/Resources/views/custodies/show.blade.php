@extends('layouts.master', [
    'modals' => ['employee', 'attachment'],
    'datatable' => true, 
    'lightbox' => true, 
    'confirm_status' => true, 
    'title' => 'عهدة رقم: ' . $custody->id,
    'crumbs' => [
        [route('accounting.custodies.index'), 'العهد'],
        ['#', 'عهدة رقم: ' . $custody->id],
    ]
])
@section('content')
    @component('components.tabs')
        @slot('items')
            @component('components.tab-item')
                @slot('id', 'details')
                @slot('active', true)
                @slot('title', __('accounting::global.details'))
            @endcomponent
            @component('components.tab-item')
                @slot('id', 'vouchers')
                @slot('title', __('accounting::global.vouchers'))
            @endcomponent
            @if (!$custody->isPayed())
                @component('components.tab-item')
                    @slot('id', 'pay')
                    @slot('title', 'تخليص العهدة')
                @endcomponent
            @endif
            @component('components.tab-item')
                @slot('id', 'attachments')
                @slot('title', __('accounting::global.attachments'))
            @endcomponent
            @permission('custodies-update|custodies-delete')
                @component('components.tab-dropdown')
                    @slot('title', __('accounting::global.options'))
                    @slot('items')
                        @permission('custodies-update')
                            @component('components.tab-dropdown-item')
                                @slot('href', route('accounting.custodies.edit', $custody))
                                @slot('content')
                                    <i class="fa fa-edit"></i>
                                    <span>تعديل</span>
                                @endslot
                            @endcomponent
                        @endpermission
                        @permission('custodies-delete')
                            @component('components.tab-dropdown-item')
                                @slot('href', "#")
                                @slot('content')
                                    <i class="fa fa-trash"></i>
                                    <span>حذف</span>
                                @endslot
                                @slot('attributes')
                                    data-toggle="confirm"
                                    data-form="#deleteForm-{{ $custody->id }}"
                                @endslot
                                @slot('extra')
                                    <form id="deleteForm-{{ $custody->id }}" style="display:none;"
                                        action="{{ route('accounting.custodies.destroy', $custody->id) }}" method="post">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                @endslot
                            @endcomponent
                        @endpermission
                    @endslot
                @endcomponent
            @endpermission
        @endslot
        @slot('contents')
            @component('components.tab-content')
                @slot('id', 'details')
                @slot('active', true)
                @slot('content')
                    <table class="datatable table table-bordered table-hover text-center">
                        <thead>
                            <tr>
                                <th>المعرف</th>
                                <th>المدين</th>
                                <th>الدائن</th>
                                <th>المبلغ</th>
                                <th>المدفوع</th>
                                <th>المتبقي</th>
                                <th>الحالة</th>
                                <th>المسؤول</th>
                                <th>التاريخ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $custody->id }}</td>
                                <td>
                                    @if (auth()->user()->isAbleTo('accounts-read'))
                                        <a href="{{ route('accounts.show', $custody->debt_account) }}">{{ $custody->debt_account->name }}</a>
                                    @else
                                        {{ $custody->debt_account->name }}
                                    @endif
                                </td>
                                <td>
                                    @if (auth()->user()->isAbleTo('accounts-read'))
                                        <a href="{{ route('accounts.show', $custody->credit_account) }}">{{ $custody->credit_account->name }}</a>
                                    @else
                                        {{ $custody->credit_account->name }}
                                    @endif
                                </td>
                                <td>{{ $custody->formated_amount }}</td>
                                <td>{{ number_format($custody->payed(), 2) }}</td>
                                <td>{{ number_format($custody->remain(), 2) }}</td>
                                <td>{{ $custody->displayStatus() }}</td>
                                <td>{{ $custody->user->name }}</td>
                                <td>{{ $custody->created_at->format('Y/m/d') }}</td>
                            </tr>
                        </tbody>
                    </table>
                @endslot
            @endcomponent
            @component('components.tab-content')
                @slot('id', 'vouchers')
                @slot('content')
                    @component('accounting::components.vouchers')
                        @slot('voucherable', $custody)
                        @slot('vouchers', $custody->vouchers)
                        @slot('type', 'receipt')
                        @slot('read_only', true)
                        @slot('max_amount', $custody->remain())
                        @slot('amount', $custody->remain())
                        @slot('credit_account', $custody->debt_account)
                        @slot('credit_account_read_only', true)
                        @slot('currency', 'ريال')
                        @slot('credits', [['id' => $debt_account->id, 'name' => $debt_account->display(), 'amount' => $custody->remain()]])
                    @endcomponent
                @endslot
            @endcomponent
            @if (!$custody->isPayed())
                @component('components.tab-content')
                    @slot('id', 'pay')
                    @slot('content')
                        <form action="{{ route('accounting.custodies.pay', $custody) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <table id="vouchers-table" class="table table-bordered table-striped text-center">
                                <thead>
                                    <th>#</th>
                                    <th><i class="fa fa-file"></i></th>
                                    <th><i class="fa fa-cog"></i></th>
                                </thead>
                                <tbody></tbody>
                                <tfoot>
                                    <tr>
                                        <th>#</th>
                                        <th><i class="fa fa-file"></i></th>
                                        <th><i class="fa fa-cog"></i></th>
                                    </tr>
                                </tfoot>
                            </table>
                            <button type="submit" class="btn btn-primary float-right">
                                <i class="fa fa-check"></i>
                                <span>إكمال العملية</span>
                            </button>
                            <button type="button" class="btn btn-success btn-add-voucher float-right mr-3">
                                <i class="fa fa-plus"></i>
                                <span>إضافة فاتورة</span>
                            </button>
                        </form>
                    @endslot
                @endcomponent
            @endif
            @component('components.tab-content')
                @slot('id', 'attachments')
                @slot('content')
                    @component('components.attachments-viewer')
                        @slot('attachable', $custody)
                        @slot('view', 'timeline')
                        @slot('canAdd', true)
                    @endcomponent
                @endslot
            @endcomponent
        @endslot
    @endcomponent
@endsection

@push('foot')
    <script>
        let accounts_options = ``;
        // accounts_options += `<option value="">@lang("accounting::accounts.choose")</option>`;
        @foreach (secondaryAccounts() as $account)
        accounts_options += `<option value="{{ $account->id }}">{{ $account->number  . '-' . $account->name }}</option>`;
        @endforeach
        let vouchers_table = $('table#vouchers-table')
        let vouchers_table_body = $('table#vouchers-table tbody')
        $(function(){
            $(document).on('change, keyup', 'input.amount[data-side=debts]', function(e){
                e.preventDefault();
                let value = $(this).val();

                let voucher = $(this).closest('tr.voucher')
                voucher.find('input.amount[data-side=credits]').val($(this).val());
            })
            $(document).on('click', '.btn-add-voucher', function(e){
                e.preventDefault();
                addVoucher();
            })
            $(document).on('click', '.btn-remove-voucher', function(e){
                let voucher = $(this).closest('tr.voucher')
                voucher.remove()
                setVoucherCounter()
            })
            @if (!$custody->isPayed())
                addVoucher()
            @endif
        })
        function addVoucher()
        {
            let voucher_number = validateVoucher();
            let vouchers_amounts = vouchersAmounts();
            let debts = vouchers_amounts.debts;
            let credits = vouchers_amounts.credits;
            let remain = credits - debts;
            if(remain > 0){
                if(voucher_number == 0 || true){
                    let index = vouchers_table_body.children().length;
                    let counter = (index + 1);
                    let key = generateVoucherNumber();
                    let _td_voucher = `
                        <tr class="voucher">
                            <input type="hidden" name="vouchers[]" value="` + key + `" class="voucher-key" />
                            <td><span class="counter">` + counter + `</span></td>
                            <td>
                                <div class="row accounts">
                                    <div class="col col-debts">
                                        <h4>مدين</h4>
                                        <div class="side debts" data-side="debts" data-voucher-index="` + index + `">
                                            <div class="account-group input-group mb-3">
                                                <input type="number" name="debts_amounts` + key + `[]" data-side="debts" value="` + remain + `" min="1" max="` + remain + `" class="amount form-control"
                                                    style="max-width: 120px;" />
                                                <select name="debts_accounts` + key + `[]" data-side="debts" class="form-control account" required>` + accounts_options + `</select>
                                                <div class="input-group-append">
                                                    <button type="button" class="btn btn-danger btn-remove-account" data-side="debts">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col col-credits">
                                        <h4>دائن</h4>
                                        <div class="side credits" data-side="credits" data-voucher-index="` + index + `">
                                            <div class="account-group input-group mb-3">
                                                <input type="number" name="credits_amounts` + key + `[]" data-side="credits" value="` + remain + `" minn="` + remain + `" maxx="` + remain + `" class="amount form-control" readonly style="max-width: 120px;" />
                                                <input type="hidden" name="credits_accounts` + key + `[]" data-side="credits" class="form-control account" value="{{ $custody->account->id }}" required>
                                                <input class="form-control" value="{{ $custody->account->display() }}" readonly>
                                                <div class="input-group-append">
                                                    <button type="button" class="btn btn-danger btn-remove-account" data-side="credits">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group">
                                    <textarea name="details[]" rows="5" class="form-control" placeholder="بيان الفاتورة" required></textarea>
                                </div>
                                <div class="form-group row">
                                    <div class="col col-lg-1">
                                        <label>المرفق</label>
                                    </div>
                                    <div class="col">
                                        <input type="text" class="form-control attachment-name" placeholder="@lang('accounting::global.name')" name="attachments_names[]" required>
                                    </div>
                                    <div class="col">
                                        <input type="file" class="form-control attachment-file" placeholder="Name" name="attachments_file`+ key +`" required>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger btn-remove-voucher" data-voucher-index="` + index + `">
                                    <i class="fa fa-trash"></i>
                                    <span>حذف الفاتورة</span>
                                </button>
                            </td>
                        </tr>
                    `;
                    vouchers_table_body.append(_td_voucher)
                }else{
                    sweet('سند غير مستوفى', 'مجموع الحسابات المدينة والدئنة غير متساوية او تساوي صفر في الفاتورة رقم: ' + voucher_number, 'error')
                }
            }else{
                sweet('قيمة الفاتورة غير صحيحة', 'لا توجد قيمة كافية لعمل الفاتورة', 'error');
            }
        }
        function vouchersAmounts(side = 'all')
        {
            let amounts = {debts: 0, credits: 0};
            let debts_amounts = $('input.amount[data-side=debts]')
            let credits_amounts = $('input.amount[data-side=credits]')
            let debts = 0, credits = 0;

            for(let index = 0; index < debts_amounts.length; index++)
            {
                let debt_amount = $(debts_amounts[index])
                let amount = Number(debt_amount.val())
                debts += amount;
            }

            if($('tr.voucher').length < 0)
            {
                for(let index = 0; index < credits_amounts.length; index++)
                {
                    let credit_amount = $(credits_amounts[index])
                    let amount = Number(credit_amount.val())
                    credits += amount;
                }
            }else{
                credits = {{ $custody->remain() }};
            }
            amounts.debts = debts;
            amounts.credits = credits;
            return (side == 'all') ? amounts : amounts[side]; 
        }
        function generateVoucherNumber(size = 20000){
            let key = Math.floor((Math.random() * size) + 1);
            if($('.voucher input.voucher-key[value=' + key + ']').length){
                return generateVoucherNumber(size);
            }
            return key;
        }
        function setVoucherCounter(voucher = null){
            if(voucher){
                let counters = voucher.find('.counter');
                for(let i = 0; i < counters.length; i++){
                    counter = $(counters[i]);
                    counter.text((voucher.index() + 1));
                }
                /*
                let accounts = voucher.find('select.account');
                for(let i = 0; i < accounts.length; i++){
                    account = $(accounts[i])
                    account.attr('name', accounts.data('side') + '_accounts' + (voucher.index() + 1) + '[]')
                }
                let amounts = voucher.find('input.amount');
                for(let i = 0; i < amounts.length; i++){
                    amount = $(amounts[i])
                    amount.attr('name', amounts.data('side') + '_amounts' + (voucher.index() + 1) + '[]')
                }
                */
            }else{
                let vouchers = vouchers_table_body.children();
                for(let index = 0; index < vouchers.length; index++){
                    voucher = $(vouchers[index])
                    setVoucherCounter($(voucher))
                }
            }

        }

        function validateVoucher(voucher = null){
            if(voucher){
                let total_debts = number_filter($(voucher.find('.voucher-side-total[data-side=debts]')).text())
                let total_credits = number_filter($(voucher.find('.voucher-side-total[data-side=credits]')).text())
                return total_debts && (total_debts == total_credits) ? 0 : (voucher.index() + 1);
            }else{
                let voucher_number = 0;
                let vouchers = vouchers_table_body.children();
                for(let index = 0; index < vouchers.length; index++){
                    voucher = $(vouchers[index])
                    voucher_number = validateVoucher($(voucher))
                    // if(!voucher_number) return;
                    if(!voucher_number) break;
                }

                return voucher_number;
            }

        }

        function submitForm(){
            let voucher_number = validateVoucher();
                if(voucher_number == 0){
                    $('form#closing-form').submit()
                }else{
                    sweet('قيد غير مستوفى', 'مجموع الحسابات المدينة والدئنة غير متساوية او تساوي صفر في القيد رقم: ' + voucher_number, 'error')
                }
        }
    </script>
@endpush