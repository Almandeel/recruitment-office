@extends('layouts/accounting')
@section('title', __('accounting::years.close'))
@section('page_title', __('accounting::global.years'))
@push('content')
@include('partials._errors')
@component('accounting::components.widget')
    @slot('id')
        
    @endslot
    @slot('title')
        <h4>
            <i class="fa fa-power-off"></i>
            <span>@lang('accounting::years.close')</span>
        </h4>
    @endslot
    @slot('content')
        {{-- <div class="alert alert-warning">
            <h3><i class="fa fa-exclamation-triangle"></i></h3>
            <ol>
                <li>ترحيل جميع أرصدة حسابات الإيرادات إلى حساب ملخص الدخل من خلال جعل حسابات الإيرادات مدينة وجعل حساب ملخص الدخل دائناً بإجمالي مبلغ الإيرادات.</li>
                <li>ترحيل جميع أرصدة حسابات المصروفات إلى حساب ملخص الدخل من خلال جعل حساب ملخص الدخل مديناً بمبلغ إجمالي المصاريف وجعل حسابات المصروفات دائنة.</li>
                <li> ترحيل رصيد حساب ملخص الدخل في حالة الربح بجعله مدينا وجعل حساب رأس المال دائناً ، وفي حالة الخسارة نقوم بترحيل رصيد حساب ملخص الدخل بجعله دائناً ونجعل حساب رأس المال مديناً بنفس المبلغ.</li>
                <li> رصيد حساب المسحوبات الشخصية بجعله دائناً وجعل حساب رأس المال مديناً بنفس المبلغ.</li>            
            </ol>
        </div> --}}
        <form action="{{ route('years.close', $year) }}" method="post" data-parsley-validate>
            @csrf
            <fieldset>
                <legend>@lang('accounting::settings.income_summary')</legend>
                <div class="form-group">
                    <div class="row">
                        <div class="col-xs-12 col-md-6">
                            <label class="col-sm-4 col-md-4 control-label" for="income_summary">
                                @lang('accounting::accounts.income_summary')
                            </label>
                            <div class="col-sm-8 col-md-8 reset">
                                <div class="input-group">
                                    @component('accounting::years._select_all_accounts') @slot('id', 'income_summary') @slot('name', 'income_summary') @endcomponent
                                    <span class="input-group-addon">
                                        <i class="fa fa-bars"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-6">
                            <label class="col-sm-4 col-md-4 control-label" for="capital">
                                @lang('accounting::accounts.capital')
                            </label>
                            <div class="col-sm-8 col-md-8 reset">
                                <div class="input-group">
                                    @component('accounting::years._select_all_accounts') @slot('id', 'capital') @slot('name', 'capital') @endcomponent
                                    <span class="input-group-addon">
                                        <i class="fa fa-bars"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-xs-12 col-md-6">
                        <table id="debts-table" class="table table-bordered table-hover table-striped">
                            <thead>
                                <tr>
                                    <th colspan="2" class="text-{{ side() }}">@lang('accounting::accounts.debts')</th>
                                </tr>
                                <tr>
                                    <th>@lang('accounting::global.account')</th>
                                    <th style="width: 50px; text-align: center;">#</th>
                                </tr>
                            </thead>
                            <tbody>
                                @component('accounting::years._accounts_rows')
                                    @slot('group', branch()->revenues())
                                @endcomponent
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td>
                                        @component('accounting::years._select_all_accounts') @slot('id', 'debts_accounts') @slot('name', '') @endcomponent
                                    </td>
                                    <td><button type="button" class="btn btn-success btn-add-row"><i class="fa fa-plus"></i></button></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="col-xs-12 col-md-6">
                        <table id="credits-table" class="table table-bordered table-hover table-striped">
                            <thead>
                                <tr>
                                    <th colspan="2" class="text-{{ side() }}">@lang('accounting::accounts.credits')</th>
                                </tr>
                                <tr>
                                    <th>@lang('accounting::global.account')</th>
                                    <th style="width: 50px; text-align: center;">#</th>
                                </tr>
                            </thead>
                            <tbody>
                                @component('accounting::years._accounts_rows')
                                    @slot('name', 'credits')
                                    @slot('group', branch()->expenses())
                                @endcomponent
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td>
                                        @component('accounting::years._select_all_accounts') @slot('id', 'credits_accounts') @slot('name', '') @endcomponent
                                    </td>
                                    <td><button type="button" class="btn btn-success btn-add-row"><i class="fa fa-plus"></i></button></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </fieldset>
            <fieldset>
                <legend>@lang('accounting::settings.capital')</legend>
                <div class="form-group">
                    <div class="col-xs-12 col-md-12">
                        <table id="debts-table" class="table table-bordered table-hover table-striped">
                            <thead>
                                {{-- <tr>
                                    <th colspan="2" class="text-{{ side() }}">@lang('accounting::accounts.debts')</th>
                                </tr> --}}
                                <tr>
                                    <th>@lang('accounting::global.capital')</th>
                                    <th>@lang('accounting::global.debts')</th>
                                    <th style="width: 50px; text-align: cener;">#</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td>
                                        <select id="capitals_accounts" data-placeholder="@lang('accounting::global.capital')" class="select2 form-control input-transparent">
                                            <optgroup label="@lang('accounting::global.capital')">
                                                <option value="0" selected>@lang('accounting::accounts.choose')</option>
                                                @component('accounting::years._options')
                                                    @slot('name', 'owners')
                                                    @slot('group', branch()->owners())
                                                @endcomponent
                                            </optgroup>
                                        </select>
                                    </td>
                                    <td>
                                        <select id="capitalz_accounts" data-placeholder="@lang('accounting::global.debts')" class="select2 form-control input-transparent">
                                            <optgroup label="@lang('accounting::global.debts')">
                                                <option value="0" selected>@lang('accounting::accounts.choose')</option>
                                                @component('accounting::years._options')
                                                    @slot('name', 'owners')
                                                    @slot('group', branch()->debtsGroup())
                                                @endcomponent
                                            </optgroup>
                                        </select>
                                    </td>
                                    <td><button type="button" class="btn btn-success btn-add-capital"><i class="fa fa-plus"></i></button></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </fieldset>
            <fieldset>
                <legend></legend>
                <div class="form-group text-center">
                    <input type="hidden" name="operation" value="preview" />
                    <a href="{{ route('years.show', $year) }}" class="btn btn-danger">
                        <i class="fa fa-times"></i>
                        <span>@lang('accounting::global.cancel')</span>
                    </a>
                    <span style="display: inline-block; margin: 0px 15px;"></span>
                    <button type="button" class="btn btn-success btn-submit">
                        <span>@lang('accounting::global.next')</span>
                        <i class="fa fa-chevron-{{ side() }}"></i>
                    </button>
                </div>
            </fieldset>
        </form>
    @endslot
@endcomponent
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
    $(document).ready(function(){
        $('#opened_at').datetimepicker({
            format: 'YYYY/MM/DD',
            locale: '{{ app()->getLocale() }}'
        });
        $('.btn-add-row').click(function(){
            var table = $(this).closest('table');
            var select = table.find('select');
            var option = select.find(':selected');
            var name = option.data('name');
            var account_id = option.data('account-id');
            var account_name = option.data('account-name');
            var exists = false;
            if(account_id){
                table.find('tbody  tr > td input').each(function(index, input){
                    exists = input.value == account_id;
                    return !exists;
                });
                if(!exists){
                    var row = `
                    <tr>
                        <td>` + account_id + ` - ` + account_name + ` <input type="hidden" name="` + name + `" value="` + account_id + `"></td>
                        <td><button type="button" class="btn btn-danger btn-xs btn-remove-row"><i class="fa fa-trash"></i></button></td>
                    </tr>
                    `; 
                    select.find(':first > option:first').prop('selected', true);
                    select.val(0);
                    table.find('tbody').append(row);
                    Messenger().post({
                        message: '@lang("accounting::accounts.add_success")',
                        type: 'success',
                        showCloseButton: true
                    });
                }else{
                    Messenger().post({
                        message: '@lang("accounting::accounts.exists")',
                        type: 'error',
                        showCloseButton: true
                    });
                }
            }else{
                Messenger().post({
                    message: '@lang("accounting::accounts.choose")',
                    type: 'error',
                    showCloseButton: true
                });
            }
        });
        $('.btn-add-capital').click(function(){
            var table = $(this).closest('table');
            var select_capitals = table.find('select#capitals_accounts');
            var option_capitals = select_capitals.find(':selected');
            var name_capitals = option_capitals.data('name');
            var account_id_capitals = option_capitals.data('account-id');
            var account_name_capitals = option_capitals.data('account-name');
            
            var select_capitalz = table.find('select#capitalz_accounts');
            var option_capitalz = select_capitalz.find(':selected');
            var name_capitalz = option_capitalz.data('name');
            var account_id_capitalz = option_capitalz.data('account-id');
            var account_name_capitalz = option_capitalz.data('account-name');
            var exists = false;
            if(account_id_capitals && account_id_capitalz){
                table.find('tbody  tr > td input').each(function(index, input){
                    exists = input.value == account_id_capitals;
                    return !exists;
                });
                if(!exists){
                    var row = `
                    <tr>
                        <td>` + account_id_capitals + ` - ` + account_name_capitals + ` <input type="hidden" name="caps_credit[]" value="` + account_id_capitals + `"></td>
                        <td>` + account_id_capitalz + ` - ` + account_name_capitalz + ` <input type="hidden" name="caps_debt[]" value="` + account_id_capitalz + `"></td>
                        <td><button type="button" class="btn btn-danger btn-xs btn-remove-row"><i class="fa fa-trash"></i></button></td>
                    </tr>
                    `; 
                    table.find('tbody').append(row);
                    Messenger().post({
                        message: '@lang("accounting::accounts.add_success")',
                        type: 'success',
                        showCloseButton: true
                    });
                }else{
                    Messenger().post({
                        message: '@lang("accounting::accounts.exists")',
                        type: 'error',
                        showCloseButton: true
                    });
                }
            }else{
                Messenger().post({
                    message: '@lang("accounting::accounts.choose")',
                    type: 'error',
                    showCloseButton: true
                });
            }
        });
        $(document).on('click', '.btn-remove-row', function(){
            var that = $(this)
            msg = Messenger().post({
                message: "@lang('accounting::global.delete_confirm')",
                type: 'error',
                showCloseButton: true,
                actions: {
                    delete: {
                    label: "@lang('accounting::global.delete')",
                    action: function(){
                        that.parent().parent().remove();
                        msg.hide()
                    }
                    },

                    cancel: {
                        label: "@lang('accounting::global.cancel')",
                        action: function(){
                            msg.hide()
                        }
                    }
                }
            });
        });

        $('form button.btn-submit').click(function(e){
            e.preventDefault();
            
            if(Number($('#income_summary').find(':selected').val()) == 0){
                Messenger().post({
                    message: '@lang("accounting::years.errors.income_summary")',
                    type: 'error',
                    showCloseButton: true
                });
            }
            else{
                if(Number($('#capital').find(':selected').val() == 0)){
                    Messenger().post({
                        message: '@lang("accounting::years.errors.capital")',
                        type: 'error',
                        showCloseButton: true
                    });
                }
                else{
                    $(this).closest('form').submit();
                }
            }
        });

        $("#debts_accounts, #credits_accounts, #capitals_accounts, #capitalz_accounts, #income_summary, #capital").select2({
            dir: "{{ direction() }}"
        });
    })
</script>
@endpush