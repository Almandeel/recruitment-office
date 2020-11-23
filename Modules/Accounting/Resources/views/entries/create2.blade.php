@extends('accounting::layouts.master',[
    'title' => __('accounting::entries.create'),
    'datatable' => true,
    'accounting_modals' => [
        'center'
    ],
    'crumbs' => [
        [route('entries.index'), __('accounting::global.entries')],
        ['#', __('accounting::entries.create')],
    ],
])
@push('content')
<form action="{{ route('entries.store') }}" method="POST">
@component('accounting::components.widget')
    @slot('id')
        
    @endslot
    @slot('title')
        <i class="fa fa-plus"></i>
        <span>@lang('accounting::entries.create')</span>
    @endslot
    @slot('body')
        @csrf
        <fieldset>
            <div class="form-group row">
                <div class="col col-xs-12">
                    <label class="col-sm-4 col-md-3 control-label" for="entry_date">
                        @lang('accounting::global.entry_date')
                    </label>
                    <input id="entry_date" name="entry_date" type="date" class="form-control input-transparent date" value="{{ date('Y-m-d') }}" required />
                </div>
                <div class="col col-xs-12">
                    <label class="col-sm-4 col-md-3 control-label" for="entry_type">
                        @lang('accounting::global.entry_type')
                    </label>
                    <select id="entry_type" name="type" class="form-control input-transparent date">
                        @foreach ($types as $type)
                            <option value="{{ $type }}">@lang('accounting::entries.types.' . $type)</option>
                        @endforeach
                    </select>
                </div>
                <div class="col col-xs-12">
                    <label class="col-sm-4 col-md-3 control-label" for="amount">
                        @lang('accounting::global.amount')
                    </label>
                    <input id="amount" name="amount" type="number" class="form-control input-transparent" value="0" />
                </div>
            </div>
            <div class="form-group row">
                <div class="col col-xs-12">
                    <label class="col-sm-4 col-md-1 control-label" for="details">
                        @lang('accounting::global.details')
                    </label>
                    <textarea id="details" name="details" rows="5" class="form-control input-transparent"></textarea>
                </div>
            </div>
            <div class="form-group row">
                <div class="col col-xs-12">
                    <table id="debts-table" class="table table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th colspan="2" class="text-center">@lang('accounting::global.debts')</th>
                            </tr>
                            <tr>
                                <th>@lang('accounting::global.amount')</th>
                                <th>@lang('accounting::global.debt')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $select_ids = '';
                            @endphp
                            @for($i = 1; $i <= 10; $i++)
                                @php
                                    $prefix = $i == 1 ? '' : ', ';
                                    $select_ids .= $prefix . '#debt_account_' . $i . ', #credit_account_' . $i;
                                @endphp
                                @component('accounting::entries._accounts_row')
                                    @slot('id', $i)
                                    @slot('roots', $roots)
                                    @slot('name', 'debt')
                                @endcomponent
                            @endfor
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2" class="text-center">
                                    <strong id="total_debts">0</strong>
                                    <input type="hidden" name="total_debts">
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="col col-xs-12">
                    <table id="debts-table" class="table table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th colspan="2" class="text-center">@lang('accounting::global.credits')</th>
                            </tr>
                            <tr>
                                <th>@lang('accounting::global.amount')</th>
                                <th>@lang('accounting::global.credit')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @for($i = 1; $i <= 10; $i++)
                                @component('accounting::entries._accounts_row')
                                    @slot('id', $i)
                                    @slot('roots', $roots)
                                    @slot('name', 'credit')
                                @endcomponent
                            @endfor
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2" class="text-center">
                                    <strong id="total_credits">0</strong>
                                    <input type="hidden" name="total_credits">
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </fieldset>
    @endslot
    @slot('footer')
        <legend>@lang('accounting::global.options')</legend>
        <hr>
        <div class="form-group row">
            <div class="col">
                <input type="radio" name="next" id="save_show" value="save_show" checked>
                <label for="save_show" class="text-default">
                    @lang('accounting::entries.save_show')
                </label>
            </div>
            <div class="col">
                <input type="radio" name="next" id="save_new" value="save_new">
                <label for="save_new" class="text-default">
                    @lang('accounting::entries.save_new')
                </label>
            </div>
            <div class="col">
                <input type="radio" name="next" id="save_list" value="save_list">
                <label for="save_list" class="text-default">
                    @lang('accounting::entries.save_list')
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
<script src="{{ asset('lib/moment/moment.js') }}"></script>
<script src="{{ asset('lib/moment/locale/' . app()->getLocale(). '.js') }}"></script>
<script src="{{ asset('lib/bootstrap-select/dist/js/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('lib/select2/select2.min.js') }}"></script>
<script src="{{ asset('lib/select2/select2_locale_'.app()->getLocale().'.js') }}"></script>
<script src="{{ asset('lib/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js') }}"></script>
<script src="{{ asset('lib/jquery.maskedinput/dist/jquery.maskedinput.min.js') }}"></script>

<script>
    function calc(){
        var debts = 0;
        var credits = 0;

        for (let index = 1; index <= 10; index++) {
            var debt_account = Number($('select[name=debt_account_' + index + ']').val());
            var credit_account = Number($('select[name=credit_account_' + index + ']').val());
            if(debt_account !== 0) {
                debts += Number($('input[name=debt_value_' + index + ']').val());
            }
            if(credit_account !== 0) {
                credits += Number($('input[name=credit_value_' + index + ']').val());
            }
        }

        $('#total_debts').text(debts);
        $('#total_credits').text(credits);
        $('input[name=total_debts]').val(debts);
        $('input[name=total_credits]').val(credits);
    }
    $(document).ready(function(){
        $('#entry_date').datetimepicker({
            format: 'YYYY/MM/DD',
            locale: '{{ app()->getLocale() }}'
        });
        // $("#opened_at").mask("99-99-9999");
        $("input.value-number").on("click", function () {
            $(this).select();
        });

        $("select.debt_account, select.credit_account").change(function(){
            // var selected_value = Number($(this).children("option:selected").val());
            // alert("You have selected the value - " + selected_value);
            // alert("You have selected the value - " + $(this).val());
            // if(selected_value == 0) calc();
            calc();
        });

        $("input.value-number").keyup(function(){
            calc();
        });

        $("input.value-number").change(function(){
            calc();
        });


        $('form button.btn-submit').click(function(e){
            e.preventDefault();
            
            if(Number($('input[name=total_debts]').val()) == 0){
                Messenger().post({
                    message: '@lang("accounting::entries.amount_invalid")',
                    type: 'error',
                    showCloseButton: true
                });
            }
            else{
                if(Number($('input[name=total_debts]').val()) == Number($('input[name=total_credits]').val())){
                    if(Number($('input[name=total_debts]').val()) == Number($('input[name=amount]').val())){
                        $(this).closest('form').submit();
                    }else{
                        Messenger().post({
                            message: '@lang("accounting::entries.debt_credit_mismatch")',
                            type: 'error',
                            showCloseButton: true
                        });
                    }
                }
                else{
                    Messenger().post({
                        message: '@lang("accounting::entries.amount_mismatch")',
                        type: 'error',
                        showCloseButton: true
                    });
                }
            }
        });

    })
</script>
@endpush