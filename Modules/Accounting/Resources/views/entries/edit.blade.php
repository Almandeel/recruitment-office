@extends('layouts/accounting')
@section('title', __('accounting::years.edit'))
@section('page_title', __('accounting::global.years'))
@push('content')
@include('partials._errors')
@component('accounting::components.widget')
    @slot('id')
        
    @endslot
    @slot('title')
        <h4>
            <i class="fa fa-pencil"></i>
            <span>@lang('accounting::years.edit')</span>
        </h4>
    @endslot
    @slot('content')
        <form action="{{ route('years.update', $year) }}" method="post" data-parsley-validate class="form-horizontal">
            @csrf
            @method('PUT')
            <fieldset>
                <legend>@lang('accounting::years.details')</legend>
                <div class="form-group">
                    <div class="col-sm-12 col-md-4 reset">
                        <label class="col-sm-4 col-md-2 control-label" for="opened_at">
                            @lang('accounting::global.opened_at')
                        </label>
                        <div class="col-sm-8 col-md-10">
                            <label class="input-group" for="opened_at">
                            <input id="opened_at" name="opened_at" type="text" class="form-control input-transparent date disabled" value="{{ $year->opened_at }}" disabled />
                                <span class="input-group-addon btn btn-success disabled">
                                    <span class="fa fa-calendar"></span>                    
                                </span>
                            </label>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-4 reset">
                        <label class="col-sm-4 col-md-2 control-label" for="currency_id">
                            @lang('accounting::global.currency')
                        </label>
                        <div class="col-sm-8 col-md-10">
                            <select name="currency_id" id="currency_id" class="form-control">
                                @foreach ($currencies as $currency)
                                <option value="{{ $currency->id }}" {{ $currency->id == $year->currency_id ? 'selected' : '' }}>{{ $currency->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-4 reset">
                        <label class="col-sm-4 col-md-2 control-label" for="taxes">
                            @lang('accounting::global.taxes')
                        </label>
                        <div class="col-sm-8 col-md-10">
                            <div class="input-group">
                                <input id="taxes" name="taxes" type="number" class="form-control input-transparent" value="{{ $year->taxes }}" />
                                <span class="input-group-addon">
                                    %
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>
            <fieldset>
                <legend>@lang('accounting::years.settings')</legend>
                <div class="form-group">
                    <div class="col-sm-12 col-md-6 reset">
                        <label class="col-sm-4 col-md-3 control-label" for="safe">
                            @lang('accounting::years.accounts.safe')
                        </label>
                        <div class="col-sm-8 col-md-9">
                            <select id="safe" data-placeholder="@lang('accounting::global.assets')" class="select2 form-control input-transparent" name="safe">
                                <optgroup label="@lang('accounting::global.assets')">
                                    @component('accounting::years._options')
                                        @slot('id', $year->safe)
                                        @slot('group', branch()->assets())
                                    @endcomponent
                                </optgroup>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6 reset">
                        <label class="col-sm-4 col-md-3 control-label" for="bank">
                            @lang('accounting::years.accounts.bank')
                        </label>
                        <div class="col-sm-8 col-md-9">
                            <select id="bank" data-placeholder="@lang('accounting::global.assets')" class="select2 form-control input-transparent" name="bank">
                                <optgroup label="@lang('accounting::global.assets')">
                                    @component('accounting::years._options')
                                        @slot('id', $year->bank)
                                        @slot('group', branch()->assets())
                                    @endcomponent
                                </optgroup>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-12 col-md-6 reset">
                        <label class="col-sm-4 col-md-3 control-label" for="purchases">
                            @lang('accounting::years.accounts.purchases')
                        </label>
                        <div class="col-sm-8 col-md-9">
                            <select id="purchases" data-placeholder="@lang('accounting::global.expenses')" class="select2 form-control input-transparent" name="purchases">
                                <optgroup label="@lang('accounting::global.expenses')">
                                    @component('accounting::years._options')
                                        @slot('id', $year->purchases)
                                        @slot('group', branch()->expenses())
                                    @endcomponent
                                </optgroup>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6 reset">
                        <label class="col-sm-4 col-md-3 control-label" for="purchases_returns">
                            @lang('accounting::years.accounts.purchases_returns')
                        </label>
                        <div class="col-sm-8 col-md-9">
                            <select id="purchases_returns" data-placeholder="@lang('accounting::global.expenses')" class="select2 form-control input-transparent" name="purchases_returns">
                                <optgroup label="@lang('accounting::global.expenses')">
                                    @component('accounting::years._options')
                                        @slot('id', $year->purchases_returns)
                                        @slot('group', branch()->expenses())
                                    @endcomponent
                                </optgroup>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-12 col-md-6 reset">
                        <label class="col-sm-4 col-md-3 control-label" for="sales">
                            @lang('accounting::years.accounts.sales')
                        </label>
                        <div class="col-sm-8 col-md-9">
                            <select id="sales" data-placeholder="@lang('accounting::global.revenues')" class="select2 form-control input-transparent" name="sales">
                                <optgroup label="@lang('accounting::global.revenues')">
                                    @component('accounting::years._options')
                                        @slot('id', $year->sales)
                                        @slot('group', branch()->revenues())
                                    @endcomponent
                                </optgroup>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6 reset">
                        <label class="col-sm-4 col-md-3 control-label" for="sales_returns">
                            @lang('accounting::years.accounts.sales_returns')
                        </label>
                        <div class="col-sm-8 col-md-9">
                            <select id="sales_returns" data-placeholder="@lang('accounting::global.revenues')" class="select2 form-control input-transparent" name="sales_returns">
                                <optgroup label="@lang('accounting::global.revenues')">
                                    @component('accounting::years._options')
                                        @slot('id', $year->sales_returns)
                                        @slot('group', branch()->revenues())
                                    @endcomponent
                                </optgroup>
                            </select>
                        </div>
                    </div>
                </div>
            </fieldset>
            <fieldset>
                <legend>@lang('accounting::global.options')</legend>
                <div class="form-group">
                    <div class="col-sm-12 col-md-12">
                        <div class="radio radio-default">
                            <input type="radio" name="next" id="save_show" value="save_show" checked>
                            <label for="save_show" class="text-default">
                                @lang('accounting::global.save_show')
                            </label>
                        </div>
                        <div class="radio radio-default">
                            <input type="radio" name="next" id="save_new" value="save_new">
                            <label for="save_new" class="text-default">
                                @lang('accounting::global.save_new')
                            </label>
                        </div>
                        <div class="radio radio-default">
                            <input type="radio" name="next" id="save_list" value="save_list">
                            <label for="save_list" class="text-default">
                                @lang('accounting::global.save_list')
                            </label>
                        </div>
                        <button class="btn btn-success">
                            <i class="fa fa-save"></i>
                            <span>@lang('accounting::global.save_changes')</span>
                        </button>
                    </div>
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
        // $("#opened_at").mask("99-99-9999");

        $("#safe, #bank, #purchases_returns, #purchases, #sales_returns, #sales, #currency_id").select2({
            dir: "{{ direction() }}"
        });
    })
</script>
@endpush