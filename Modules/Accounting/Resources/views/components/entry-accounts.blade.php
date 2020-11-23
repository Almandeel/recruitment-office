@php
    $accounts = isset($accounts) ? $accounts : Modules\Accounting\Models\Account::secondaryAccounts();
@endphp
<div class="row {{ $class ?? 'entry-accounts' }}" id="guide-accounts">
    <div class="col p-0">
        <table class="debts-table table table-bordered table-hover table-striped guide-debts">
            <thead>
                <tr>
                    <th colspan="3">@lang('accounting::accounting.debt')</th>
                </tr>
                <tr>
                    <th style="width: 80px; text-align: center;">#</th>
                    <th>@lang('accounting::global.account')</th>
                    <th>@lang('accounting::global.amount')</th>
                </tr>
            </thead>
            <tbody>
                @isset($debts)
                    @foreach ($debts as $account)
                        <tr>
                            <td class="text-center">
                                <button type="button" class="btn btn-danger btn-remove" data-side="debts" data-text="{{ $account['name'] }}" data-value="{{ $account['id'] }}"><i class="fa fa-trash"></i></button>
                            </td>
                            <td>{{ $account['name'] }}</td>
                            <td>
                                <input type="number" class="form-control {{ $amount_class }}" data-side="debts" name="debts_amounts[]" min="0" value="{{ $account['amount'] }}" required>
                                <input type="hidden" name="debts_accounts[]" value="{{ $account['id'] }}">
                            </td>
                        </tr>
                    @endforeach
                @endisset
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="2">الاجمالي</th>
                    <th>
                        <strong id="total_debts">{{ isset($debts) ? array_sum(array_column($debts, 'amount')) : 0 }}</strong>
                        <input type="hidden" name="total_debts" value="{{ isset($debts) ? array_sum(array_column($debts, 'amount')) : 0 }}">
                    </th>
                </tr>
                <tr>
                    <th class="text-center">
                        <button type="button" class="btn btn-primary btn-add guide-debts-add" data-side="debts">
                            <i class="fa fa-plus"></i>
                            {{--  <span>@lang('accounting::global.add')</span>  --}}
                        </button>
                    </th>
                    <th colspan="2">
                        <select class="form-control select2 accounts-options" data-side="debts"></select>
                    </th>
                </tr>
            </tfoot>
        </table>
    </div>
    <div class="col p-0">
        <table class="credits-table table table-bordered table-hover table-striped guide-credits">
            <thead>
                <tr>
                    <th colspan="3">@lang('accounting::accounting.credit')</th>
                </tr>
                <tr>
                    <th>@lang('accounting::global.amount')</th>
                    <th>@lang('accounting::global.account')</th>
                    <th style="width: 80px; text-align: center;">#</th>
                </tr>
            </thead>
            <tbody>
                
                @isset($credits)
                    @foreach ($credits as $account)
                        <tr>
                            <td>
                                <input type="number" class="form-control {{ $amount_class }}" data-side="credits" name="credits_amounts[]" min="0" value="{{ $account['amount'] }}" required>
                                <input type="hidden" name="credits_accounts[]" value="{{ $account['id'] }}">
                            </td>
                            <td>{{ $account['name'] }}</td>
                            <td class="text-center">
                                <button type="button" class="btn btn-danger btn-remove" data-side="credits" data-text="{{ $account['name'] }}" data-value="{{ $account['id'] }}"><i class="fa fa-trash"></i></button>
                            </td>
                        </tr>
                    @endforeach
                @endisset
            </tbody>
            <tfoot>
                <tr>
                    <th>
                        <strong id="total_credits">{{ isset($credits) ? array_sum(array_column($credits, 'amount')) : 0 }}</strong>
                        <input type="hidden" name="total_credits" value="{{ isset($credits) ? array_sum(array_column($credits, 'amount')) : 0 }}">
                    </th>
                    <th colspan="2">الاجمالي</th>
                </tr>
                <tr>
                    <th colspan="2">
                        <select class="form-control select2 accounts-options" data-side="credits"></select>
                    </th>
                    <th class="text-center">
                        <button type="button" class="btn btn-primary btn-add guide-credits-add" data-side="credits">
                            <i class="fa fa-plus"></i>
                            {{--  <span>@lang('accounting::global.add')</span>  --}}
                        </button>
                    </th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

@push('foot')
    <script>
        let accounts = {!! $accounts->toJson() !!};
        
        $(function(){
            let options = '';
            for(let i = 0; i < accounts.length; i++){
                let account = accounts[i];
                let option =`
                    <option value="`+account.id+`">`+account.number+`-`+account.name+`</option>
                `;

                options += option;
            }
            let btn = $("{{ $btn ?? '#btn-submit' }}");
            let amount_class = "{{ $amount_class ?? 'amount' }}";
            let amount_field = $("input#{{ $amount ?? 'amount' }}");
            let wrapper = $(".{{ $class ?? 'entry-accounts' }}");
            let debtsTable = $(".{{ $class ?? 'entry-accounts' }} .debts-table");
            let creditsTable = $(".{{ $class ?? 'entry-accounts' }} .credits-table");
            wrapper.find('select.accounts-options').html(options);
            $(document).on('click', ".{{ $class ?? 'entry-accounts' }} .btn-add", function(){
                let table = $(this).closest('table');
                let tbody = table.children('tbody');
                let select = table.find("select.accounts-options");
                let selected = select.find('option:selected');
                let amount_field_value = Number(amount_field.val());
                let side_amount = Number($('input[name=total_' + $(this).data('side') + ']').val());
                let remain = amount_field_value - side_amount;
                /*
                if(amount_field_value > side_amount){
                }
                */
                if(amount_field_value){
                    if(remain > 0){
                        $('input[name=total_' + $(this).data('side') + ']').val(side_amount + remain)
                        $('#total_' + $(this).data('side')).text(side_amount + remain)
                        let row = ``;
                        if($(this).data('side') == 'debts'){
                            row = `
                                <tr>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-danger btn-remove" data-side="debts" data-text="`+ selected.text() +`" data-value="`+ selected.val() +`"><i class="fa fa-trash"></i></button>
                                    </td>
                                    <td>`+ selected.text() +`</td>
                                    <td>
                                        <input type="number" class="form-control `+ amount_class +`" data-side="debts" name="debts_amounts[]" min="0" value="`+ remain +`" required>
                                        <input type="hidden" name="debts_accounts[]" value="`+ selected.val() +`">
                                    </td>
                                </tr>
                            `;
                        }else{
                            row = `
                                <tr>
                                    <td>
                                        <input type="number" class="form-control `+ amount_class +`" data-side="credits" name="credits_amounts[]" min="0" value="`+ remain +`" required>
                                        <input type="hidden" name="credits_accounts[]" value="`+ selected.val() +`">
                                    </td>
                                    <td>`+ selected.text() +`</td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-danger btn-remove" data-side="credits" data-text="`+ selected.text() +`" data-value="`+ selected.val() +`"><i class="fa fa-trash"></i></button>
                                    </td>
                                </tr>
                            `;
                        }
                        tbody.append(row)
                        selected.remove()
                    }else{
                        // sweet('@lang("accounting::global.error")', '@lang("accounting::entries.amount_full")', 'error')
                        sweet('@lang("accounting::global.error")', 'تم استيفاء قيمة القيد او قيمة القيد اصغر من قيمة المدين او الدائن', 'error')
                    }
                }else{
                    sweet('@lang("accounting::global.error")', '@lang("accounting::entries.amount_invalid")', 'error')
                    amount_field.focus()
                }
            })
            $(document).on('click', ".{{ $class ?? 'entry-accounts' }} .btn-remove", function(){
                $(this).attr('disbaled', true);
                let table = $(this).closest('table');
                let select = table.find("select.accounts-options");
                let option = $('<option value="'+$(this).data('value')+'">'+$(this).data('text')+'</option>');
                $(this).parent().parent().remove()
                select.append(option)

                let options = '';
                for(let i = 0; i < accounts.length; i++){
                    let account = accounts[i];
                    if(select.find('option[value=' +account.id +']').length){
                        let opt =`
                            <option value="`+account.id+`">`+account.number+`-`+account.name+`</option>
                        `;
    
                        options += opt;
                    }
                }

                select.html(options)

                side = $(this).data('side');
                let side_amount = 0;
                let side_inputs = $(".{{ $class ?? 'entry-accounts' }} input."+ amount_class + "[data-side="+side+"]")
                for(let i = 0; i < side_inputs.length; i++){
                    let input = $(side_inputs[i]);
                    side_amount += Number(input.val());
                }
                $('input[name=total_' + side + ']').val(side_amount)
                $('#total_' + side).text(side_amount)
            })

            $(document).on('change, keyup', '.' + amount_class, function(){
                let amount_field_value = amount_field.val();
                let side = 'debts';
                let other_side = 'credits';
                if($(this).data('side')){
                    side = $(this).data('side');
                    other_side = side == 'debts' ? 'credits' : 'debts';
                }
                let side_amount = 0;
                let other_side_amount = 0;
                let side_inputs = $(".{{ $class ?? 'entry-accounts' }} input."+ amount_class + "[data-side="+side+"]")
                let other_side_inputs = $(".{{ $class ?? 'entry-accounts' }} input."+ amount_class + "[data-side="+other_side+"]")
                for(let i = 0; i < side_inputs.length; i++){
                    let input = $(side_inputs[i]);
                    side_amount += Number(input.val());
                }
                for(let i = 0; i < other_side_inputs.length; i++){
                    let input = $(other_side_inputs[i]);
                    other_side_amount += Number(input.val());
                }
                $('input[name=total_' + side + ']').val(side_amount)
                $('input[name=total_' + other_side + ']').val(other_side_amount)
                $('#total_' + side).text(side_amount)
                $('#total_' + other_side).text(other_side_amount)
            })
            btn.on('click', function(){
                let amount_field_value = amount_field.val();
                let side = 'debts';
                let other_side = 'credits';
                if($(this).data('side')){
                    side = $(this).data('side');
                    other_side = side == 'debts' ? 'credits' : 'debts';
                }
                let side_amount = 0;
                let other_side_amount = 0;
                let side_inputs = $(".{{ $class ?? 'entry-accounts' }} input."+ amount_class + "[data-side="+side+"]")
                let other_side_inputs = $(".{{ $class ?? 'entry-accounts' }} input."+ amount_class + "[data-side="+other_side+"]")
                for(let i = 0; i < side_inputs.length; i++){
                    let input = $(side_inputs[i]);
                    side_amount += Number(input.val());
                }
                for(let i = 0; i < other_side_inputs.length; i++){
                    let input = $(other_side_inputs[i]);
                    other_side_amount += Number(input.val());
                }
                if(amount_field_value == 0){
                    sweet('@lang("accounting::global.error")', '@lang("accounting::entries.amount_invalid")', 'error')
                    amount_field.focus()
                }else{
                    if(side_amount == 0 || other_side_amount == 0){
                        sweet('@lang("accounting::global.error")', '@lang("accounting::entries.sides_invalid")', 'error')
                    }
                    else{
                        if(side_amount == other_side_amount){
                            if(side_amount == amount_field_value){
                                $(this).closest('form').submit();
                            }else{
                                sweet('@lang("accounting::global.error")','@lang("accounting::entries.amount_mismatch")', 'error');
                            }
                        }
                        else{
                            sweet('@lang("accounting::global.error")', '@lang("accounting::entries.debt_credit_mismatch")', 'error');
                        }
                    }
                }
            })
        })
    </script>
@endpush