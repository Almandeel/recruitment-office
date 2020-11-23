@extends('accounting::layouts.master',[
    'title' => 'إقفال السنة المالية: ' . $year->id,
    'datatable' => true,
    'accounting_modals' => [
        'account'
    ],
    'crumbs' => [
        ['#', __('accounting::global.years')],
    ],
])
@push('content')
    <form id="closing-form" action="{{ route('years.close', $year) }}" method="post">
        @csrf
        @component('accounting::components.widget')
            @slot('title')
                <i class="fa fa-book"></i>
                <span>قيود الاقفال</span>
            @endslot
            @slot('tools')
                <button type="button" class="btn btn-primary btn-add-entry">
                    <i class="fa fa-plus"></i>
                    <span>إضافة قيد</span>
                </button>
            @endslot
            @slot('body')
                <table id="entries-table" class="table table-bordered table-striped text-center">
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
            @endslot
            @slot('footer')
                <div class="clearfix">
                    <div class="form-inline float-left">
                        <div class="form-group mr-2">
                            <label for="closed_at">تاريخ الإقفال</label>
                            <input type="date" name="closed_at" id="closed_at" class="form-control" value="{{ date('Y-m-d') }}">
                        </div>
                        <button type="button" class="btn btn-warning"
                            data-toggle="confirm" data-text="@lang('accounting::years.confirm_close_text')"
                            data-title="@lang('accounting::years.confirm_close_title')"
                            data-callback="submitForm"
                        >
                            <i class="fa fa-power-off"></i>
                            <span>إقفال</span>
                        </button>
                    </div>
                    <button type="button" class="btn btn-primary btn-add-entry float-right">
                        <i class="fa fa-plus"></i>
                        <span>إضافة قيد</span>
                    </button>
                </div>
            @endslot
        @endcomponent
    </form>
@endpush
@push('foot')
    <script>
        let accounts_options = ``;
        // accounts_options += `<option value="">@lang("accounting::accounts.choose")</option>`;
        @foreach (accounts(true, true) as $account)
        accounts_options += `<option value="{{ $account->id }}">{{ $account->number  . '-' . $account->name }}</option>`;
        @endforeach
        let entries_table = $('table#entries-table')
        let entries_table_body = $('table#entries-table tbody')
        $(function(){
            $(document).on('click', '.btn-add-entry', function(e){
                e.preventDefault()
                let entry_number = validateEntry();
                if(entry_number == 0 || true){
                    let index = entries_table_body.children().length;
                    let counter = (index + 1);
                    let key = generateEntryNumber();
                    let _td_entry = `
                        <tr class="entry">
                            <input type="hidden" name="entries[]" value="` + key + `" class="entry-key" />
                            <td><span class="counter">` + counter + `</span></td>
                            <td>
                                <div class="row accounts">
                                    <div class="col col-debts">
                                        <h4>مدين</h4>
                                        <div class="side debts" data-side="debts" data-entry-index="` + index + `">
                                            <div class="account-group input-group mb-3">
                                                <input type="number" name="debts_amounts` + key + `[]" data-side="debts" value="0" min="1" class="amount form-control"
                                                    style="max-width: 120px;" />
                                                <select name="debts_accounts` + key + `[]" data-side="debts" class="form-control account" required>` + accounts_options + `</select>
                                                <div class="input-group-append">
                                                    <button type="button" class="btn btn-danger btn-remove-account" data-side="debts">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="clearfix">
                                            <div class="float-left">
                                                <span>الإجمالي</span>
                                                (<strong class="entry-side-total" data-side="debts">0</strong>)
                                            </div>
                                            <div class="float-right">
                                                <button type="button" class="btn btn-primary btn-add-account" data-side="debts">
                                                    <i class="fa fa-plus"></i>
                                                    <span>إضافة حساب</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col col-credits">
                                        <h4>دائن</h4>
                                        <div class="side credits" data-side="credits" data-entry-index="` + index + `">
                                            <div class="account-group input-group mb-3">
                                                <input type="number" name="credits_amounts` + key + `[]" data-side="credits" value="0" min="1" class="amount form-control"
                                                    style="max-width: 120px;" />
                                                <select name="credits_accounts` + key + `[]" data-side="credits" class="form-control account" required>` + accounts_options + `</select>
                                                <div class="input-group-append">
                                                    <button type="button" class="btn btn-danger btn-remove-account" data-side="credits">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="clearfix">
                                            <div class="float-left">
                                                <span>الإجمالي</span>
                                                (<strong class="entry-side-total" data-side="credits">0</strong>)
                                            </div>
                                            <div class="float-right">
                                                <button type="button" class="btn btn-primary btn-add-account" data-side="credits">
                                                    <i class="fa fa-plus"></i>
                                                    <span>إضافة حساب</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group">
                                    <textarea name="details[]" rows="5" class="form-control" placeholder="بيان القيد" required></textarea>
                                </div>
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger btn-remove-entry" data-entry-index="` + index + `">
                                    <i class="fa fa-trash"></i>
                                    <span>حذف القيد</span>
                                </button>
                            </td>
                        </tr>
                    `;
                    entries_table_body.append(_td_entry)
                }else{
                    sweet('قيد غير مستوفى', 'مجموع الحسابات المدينة والدئنة غير متساوية او تساوي صفر في القيد رقم: ' + entry_number, 'error')
                }
            })
            $(document).on('click', '.btn-remove-entry', function(e){
                let entry = $(this).closest('tr.entry')
                entry.remove()
                setEntryCounter()
                /* let index = $(this).data('entry-index')
                if(index){
                    let entry = $(entries_table_body.children()[index]);
                    entry.remove()
                    setEntryCounter()
                }*/
            })
            $(document).on('click', '.btn-add-account', function(e){
                e.preventDefault()
                let data_side = $(this).data('side')
                let col_side = $(this).closest('.col-' + data_side)
                let side = col_side.find('.side')
                let entry = side.closest('tr.entry')
                let entry_key = $(entry.find('.entry-key')).val()
                let account = `
                    <div class="account-group input-group mb-3">
                        <input type="number" name="` + side.data('side') + `_amounts` + entry_key + `[]" data-side="` + side.data('side') + `" value="0" min="1"
                            class="amount form-control" style="max-width: 120px;" />
                        <select name="` + side.data('side') + `_accounts` + entry_key + `[]" data-side="` + side.data('side') + `" class="form-control account" required>` +
                            accounts_options + `</select>
                        <div class="input-group-append">
                            <button type="button" class="btn btn-danger btn-remove-account" data-side="` + side.data('side') + `">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>
                    </div>
                `;
                side.append(account)
                // setEntryCounter(entry)
            })
            $(document).on('click', '.btn-remove-account', function(e){
                e.preventDefault()
                let account_group = $(this).closest('.account-group')
                let side = $(this).closest('.side')
                let accounts_groups = side.children('.account-group')
                let entry = $(this).closest('tr.entry')
                if(accounts_groups.length > 1){
                    account_group.remove()
                }else{
                    alert('يجب ان يكون هناك على الاقل حساب واحد')
                }
                {{--  let side = $(this).parent().children('div.side')
                setEntryCounter(entry)  --}}
            })
            $(document).on('change, keyup', '.account-group input.amount', function(e){
                let side = $(this).data('side')
                let column = $(this).closest('.col-' + side)
                let total_display = $(column.find('.entry-side-total'))
                let amounts = column.find('.account-group input.amount')
                let total = 0;
                for(let i = 0; i < amounts.length; i++){
                    let amount = $(amounts[i])
                    total += Number(amount.val())
                }

                total_display.text(total)
            })
        })
        function generateEntryNumber(size = 20000){
            let key = Math.floor((Math.random() * size) + 1);
            if($('.entry input.entry-key[value=' + key + ']').length){
                return generateEntryNumber(size);
            }
            return key;
        }
        function setEntryCounter(entry = null){
            if(entry){
                let counters = entry.find('.counter');
                for(let i = 0; i < counters.length; i++){
                    counter = $(counters[i]);
                    counter.text((entry.index() + 1));
                }
                /*
                let accounts = entry.find('select.account');
                for(let i = 0; i < accounts.length; i++){
                    account = $(accounts[i])
                    account.attr('name', accounts.data('side') + '_accounts' + (entry.index() + 1) + '[]')
                }
                let amounts = entry.find('input.amount');
                for(let i = 0; i < amounts.length; i++){
                    amount = $(amounts[i])
                    amount.attr('name', amounts.data('side') + '_amounts' + (entry.index() + 1) + '[]')
                }
                */
            }else{
                let entries = entries_table_body.children();
                for(let index = 0; index < entries.length; index++){
                    entry = $(entries[index])
                    setEntryCounter($(entry))
                }
            }

        }

        function validateEntry(entry = null){
            if(entry){
                let total_debts = number_filter($(entry.find('.entry-side-total[data-side=debts]')).text())
                let total_credits = number_filter($(entry.find('.entry-side-total[data-side=credits]')).text())
                return total_debts && (total_debts == total_credits) ? 0 : (entry.index() + 1);
            }else{
                let entry_number = 0;
                let entries = entries_table_body.children();
                for(let index = 0; index < entries.length; index++){
                    entry = $(entries[index])
                    entry_number = validateEntry($(entry))
                    // if(!entry_number) return;
                    if(!entry_number) break;
                }

                return entry_number;
            }

        }

        function submitForm(){
            let entry_number = validateEntry();
                if(entry_number == 0){
                    $('form#closing-form').submit()
                }else{
                    sweet('قيد غير مستوفى', 'مجموع الحسابات المدينة والدئنة غير متساوية او تساوي صفر في القيد رقم: ' + entry_number, 'error')
                }
        }
    </script>
@endpush