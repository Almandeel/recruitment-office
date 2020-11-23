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
    /*
    'guides' => [
        [
            'element' => '#guide-entry-date',
            'title' =>  __('accounting::entries.guides.create.guide_entry_date.title'),
            'description' => __('accounting::entries.guides.create.guide_entry_date.description'),
            'position' => 'top',
        ],
        [
            'element' => '#guide-entry-type',
            'title' => __('accounting::entries.guides.create.guide_entry_type.title'),
            'description' => __('accounting::entries.guides.create.guide_entry_type.description'),
            'position' => 'top',
        ],
        [
            'element' => '#guide-amount',
            'title' => __('accounting::entries.guides.create.guide_amount.title'),
            'description' => __('accounting::entries.guides.create.guide_amount.description'),
            'position' => 'top',
        ],
        [
            'element' => '#guide-details',
            'title' => __('accounting::entries.guides.create.guide_details.title'),
            'description' => __('accounting::entries.guides.create.guide_details.description'),
            'position' => 'top',
        ],
        [
            'element' => '.guide-debts',
            'title' => __('accounting::entries.guides.create.guide_debts.title'),
            'description' => __('accounting::entries.guides.create.guide_debts.description'),
            'position' => 'top',
        ],
        [
            'element' => '.guide-debts-add',
            'title' => __('accounting::entries.guides.create.guide_debts_add.title'),
            'description' => __('accounting::entries.guides.create.guide_debts_add.description'),
            'position' => 'top',
        ],
        [
            'element' => '.guide-credits',
            'title' => __('accounting::entries.guides.create.guide_credits.title'),
            'description' => __('accounting::entries.guides.create.guide_credits.description'),
            'position' => 'top',
        ],
        [
            'element' => '.guide-credits-add',
            'title' => __('accounting::entries.guides.create.guide_credits_add.title'),
            'description' => __('accounting::entries.guides.create.guide_credits_add.description'),
            'position' => 'top',
        ],
        [
            'element' => '.guide-submit',
            'title' => __('accounting::entries.guides.create.guide_submit.title'),
            'description' => __('accounting::entries.guides.create.guide_submit.description'),
            'position' => 'top',
        ],
    ],
    */
])
@push('content')
<form action="{{ route('entries.store') }}" method="POST">
    @csrf
@component('accounting::components.widget')
    @slot('widgets', ['collapse'])
    @slot('title')
        <i class="fa fa-list"></i>
        <span>@lang('accounting::global.details')</span>
    @endslot
    @slot('body')
        <fieldset>
            <div class="form-group row">
                <div class="col col-xs-12" id="guide-entry-date">
                    <label class="col-sm-4 col-md-3 control-label" for="entry_date">
                        @lang('accounting::global.entry_date')
                    </label>
                    <input id="entry_date" name="entry_date" type="date" class="form-control input-transparent date" value="{{ date('Y-m-d') }}" required />
                </div>
                <div class="col col-xs-12" id="guide-entry-type">
                    <label class="col-sm-4 col-md-3 control-label" for="entry_type">
                        @lang('accounting::global.entry_type')
                    </label>
                    <select id="entry_type" name="type" class="form-control input-transparent date">
                        @foreach ($types as $type)
                            <option value="{{ $type }}">@lang('accounting::entries.types.' . $type)</option>
                        @endforeach
                    </select>
                </div>
                <div class="col col-xs-12" id="guide-amount">
                    <label class="col-sm-4 col-md-3 control-label" for="amount">
                        @lang('accounting::global.amount')
                    </label>
                    <input id="amount" name="amount" type="number" class="form-control amount" value="0" />
                </div>
            </div>
            <div class="form-group row">
                <div class="col col-xs-12" id="guide-details">
                    <label class="col-sm-4 col-md-1 control-label" for="details">
                        @lang('accounting::entries.details')
                    </label>
                    <textarea id="details" name="details" rows="5" class="form-control input-transparent"></textarea>
                </div>
            </div>
        </fieldset>
    @endslot
@endcomponent
@component('accounting::components.widget')
    @slot('widgets', ['collapse'])
    @slot('title')
        <i class="fa fa-list"></i>
        <span>@lang('accounting::global.accounts')</span>
    @endslot
    @slot('body')
        @component('accounting::components.entry-accounts')
            @slot('class', 'accounts')
            @slot('amount_class', 'amount')
            @slot('btn', '#btn-submit')
            @slot('accounts', $roots)
        @endcomponent
        {{--  <div class="form-group row">
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
        </div>  --}}
    @endslot
    @slot('footer')
        <legend>@lang('accounting::global.options')</legend>
        <hr>
        <div class="form-group row" id="guide-options">
            <div class="col" id="guide-option-save-show">
                <input type="radio" name="next" id="save_show" value="save_show" checked>
                <label for="save_show" class="text-default">
                    @lang('accounting::entries.save_show')
                </label>
            </div>
            <div class="col" id="guide-option-save-new">
                <input type="radio" name="next" id="save_new" value="save_new">
                <label for="save_new" class="text-default">
                    @lang('accounting::entries.save_new')
                </label>
            </div>
            <div class="col" id="guide-option-save-list">
                <input type="radio" name="next" id="save_list" value="save_list">
                <label for="save_list" class="text-default">
                    @lang('accounting::entries.save_list')
                </label>
            </div>
            <div class="col">
                <button id="btn-submit" type="button" class="btn btn-primary guide-submit">
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
<script>
</script>
@endpush