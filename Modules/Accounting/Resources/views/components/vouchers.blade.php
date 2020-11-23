@php
    $view = isset($view) ?: 'viewer';
    $voucherable = isset($voucherable) ? $voucherable : null;
    $type = isset($type) ? $type : 'all';
    $vouchers = isset($vouchers) ? $vouchers : null; 
    $vouchers = isset($voucherable) && is_null($vouchers) ? $voucherable->vouchers : $vouchers;
    // dd($voucherable->vouchers);
    $vouchers_type = request()->has('vouchers_type') ? request('vouchers_type') : 'all';
    $vouchers_status = request()->has('vouchers_status') ? request('vouchers_status') : 'all';
    $vouchers_from_date = request()->has('vouchers_from_date') ? request('vouchers_from_date') : date('Y-m-d');
    if (!(request()->has('vouchers_from_date')) && $vouchers->count()) {
        $vouchers_from_date = $vouchers->first()->created_at->format('Y-m-d');
    }
    $vouchers_to_date = request()->has('vouchers_to_date') ? request('vouchers_to_date') : date('Y-m-d');
    $read_only = isset($read_only) ? $read_only : false;
    $amount = isset($amount) ? $amount : 0;
    $vouchers_from_date_time = $vouchers_from_date . ' 00:00:00';
    $vouchers_to_date_time = $vouchers_to_date . ' 23:59:59';
    $vouchers = $vouchers->whereBetween('created_at',[$vouchers_from_date_time, $vouchers_to_date_time]);
    if($vouchers_type != 'all'){
        $vouchers = $vouchers->where('type', $vouchers_type);
    }
    if($vouchers_status != 'all'){
        $vouchers = $vouchers->filter(function($voucher) use ($vouchers_status){ return $voucher->getStatus() == $vouchers_status; });
    }

    $secondary_accounts = Modules\Accounting\Models\Account::secondaryAccounts();
@endphp
<div class="vouchers-view">
    <div class="vouchers-view-header clearfix">
        <h3 class="vouchers-title float-left">
            <i class="fa fa-list"></i>
            <span>@lang('accounting::vouchers.list')</span>
        </h3>
        @if (!$read_only)
            @permission('vouchers-create')
            <button class="btn btn-primary btn-add-voucher float-right">
                <i class="fa fa-plus"></i>
                <span>@lang('accounting::vouchers.add')</span>
            </button>
            @endpermission
        @endif
    </div>
    <div class="vouchers-view-body">
        <div class="mb-2">
            <div class="form-group">
                <label>
                    <i class="fa fa-cogs"></i>
                    <span>@lang('accounting::global.search_advanced')</span>
                </label>
            </div>
            <form action="" method="GET" class="form-inline guide-advanced-search">
                @csrf
                <div class="form-group mr-2">
                    <label for="vouchers-type">@lang('accounting::global.type')</label>
                    <select name="vouchers_type" id="vouchers-type" class="form-control" required>
                        <option value="all" {{ ($vouchers_type == 'all') ? 'selected' : '' }}>@lang('accounting::global.all')
                        @foreach (Modules\Accounting\Models\Voucher::TYPES as $t)
                        <option value="{{ $t }}" {{ $t == $vouchers_type ? 'selected' : '' }}>
                            {{ Modules\Accounting\Models\Voucher::getStaticType($t) }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group mr-2">
                    <label for="vouchers-status">@lang('accounting::global.status')</label>
                    <select class="form-control type" name="vouchers_status" id="vouchers-status">
                        <option value="all" {{ ($vouchers_status == 'all') ? 'selected' : '' }}>@lang('accounting::global.all')</option>
                        @foreach (App\Traits\Statusable::$STATUSES as $value => $status)
                        <option value="{{ $status }}" {{ $status == $vouchers_status ? 'selected' : '' }}>
                            @lang('global.statuses.' . $status)
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group mr-2">
                    <label for="vouchers-from-date">@lang('accounting::global.from')</label>
                    <input type="date" name="vouchers_from_date" id="vouchers-from-date" value="{{ $vouchers_from_date }}" class="form-control">
                </div>
                <div class="form-group mr-2">
                    <label for="vouchers-to-date">@lang('accounting::global.to')</label>
                    <input type="date" name="vouchers_to_date" id="vouchers-to-date" value="{{ $vouchers_to_date }}" class="form-control">
                </div>
                <button type="submit" class="btn btn-primary">
                    <span>@lang('accounting::global.search')</span>
                    <i class="fa fa-search"></i>
                </button>
            </form>
        </div>
        <table class="table table-bordered datatable text-center">
            <thead>
                <tr>
                    <th>@lang('accounting::global.id')</th>
                    <th>@lang('accounting::global.type')</th>
                    {{--  <th>@lang('accounting::global.number')</th>  --}}
                    <th>@lang('accounting::global.amount')</th>
                    <th>@lang('accounting::global.date')</th>
                    <th>@lang('accounting::global.status')</th>
                    <th>@lang('accounting::global.user')</th>
                    <th>@lang('accounting::global.options')</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($vouchers as $voucher)
                <tr>
                    <td>{{ $voucher->id }}</td>
                    <td>{{ $voucher->displayType() }}</td>
                    {{--  <td>{{ $voucher->number ? $voucher->number : '...' }}</td>  --}}
                    <td>
                        @if (isset($currency))
                           @if ($currency == 'ريال')
                                @if ($voucher->entry)
                                    {{ $voucher->entry->money('amount', false) }}
                                @else
                                    {{ $voucher->money('amount', false) }}
                                @endif
                           @else
                                {{ $voucher->money('amount', false) }}
                           @endif 
                        @else
                            {{ $voucher->money('amount', false) }}
                        @endif
                    </td>
                    <td>{{ $voucher->voucher_date ? date('Y/m/d', strtotime($voucher->voucher_date)) : '...' }}</td>
                    <td>{{ $voucher->displayStatus() }}
                        {{--  @if ($voucher->entry)
                        <span>
                            <i class="fa fa-check"></i>
                            <span>@lang('accounting::global.confirmed')</span>
                        </span>
                        @else
                        <span>
                            <i class="fa fa-time"></i>
                            <span>@lang('accounting::global.confirming')</span>
                        </span>
                        @endif  --}}
                    </td>
                    <td>{{ is_object($voucher->auth()) ? $voucher->auth()->name : '...' }}</td>
                    <td>
                        <div class="btn-group">
                            @permission('vouchers-read')
                            <a href="{{ route('home.voucher', ['voucher' => $voucher, 'layout' => 'print']) }}" class="btn btn-default">
                                <i class="fa fa-print"></i>
                                <span>طباعة</span>
                            </a></li>
                            @endpermission
                            {{--  @if(auth()->user()->isAbleTo('vouchers-update') && $voucher->statusIsWaiting())
                            <button type="button" class="btn btn-success" data-toggle="status" data-id="{{ $voucher->id }}"
                                data-type="{{ get_class($voucher) }}" data-status="approve">
                                <i class="fa fa-check"></i>
                                <span>@lang('global.approve')</span>
                            </button>
                            <button type="button" class="btn btn-danger" data-toggle="status" data-id="{{ $voucher->id }}"
                                data-type="{{ get_class($voucher) }}" data-status="reject">
                                <i class="fa fa-times"></i>
                                <span>@lang('global.reject')</span>
                            </button>
                            @endif  --}}
                            @permission('vouchers-read')
                            <a href="{{ route('home.voucher', $voucher) }}" class="btn btn-info">
                                <i class="fa fa-eye"></i>
                                <span>@lang('accounting::global.show')</span>
                            </a>
                            @endpermission
                            @if ($voucher->isEditable())
                                @permission('vouchers-delete')
                                <a href="#" class="btn btn-danger delete" data-form="#deleteForm-{{ $voucher->id }}">
                                    <i class="fa fa-trash"></i>
                                    <span>@lang('accounting::global.delete')</span>
                                </a>
                                @endpermission
                            @endif
                        </div>
                        @if ($voucher->isEditable())
                            @permission('vouchers-delete')
                            <form id="deleteForm-{{ $voucher->id }}" style="display:none;"
                                action="{{ route('vouchers.destroy', $voucher->id) }}" method="post">
                                @csrf
                                @method('DELETE')
                            </form>
                        @endif
                        @endpermission
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="vouchers-view-footer clearfix">
        <div class="float-left">

        </div>
        @if (!$read_only)
            @permission('vouchers-create')
            <button class="btn btn-primary btn-add-voucher float-right">
                <i class="fa fa-plus"></i>
                <span>@lang('accounting::vouchers.add')</span>
            </button>
            @endpermission
        @endif
    </div>
    @if ($view == 'viewer')
    
    @elseif ($view == 'form')
    
    @endif
</div>
@if (!$read_only)
    @permission('vouchers-create')
    <form class="vouchers-form" method="POST" action="{{ route('vouchers.store') }}">
        @csrf
        @method('POST')
        <input type="hidden" name="voucherable_id" value="{{ $voucherable->id }}"/>
        <input type="hidden" name="voucherable_type" value="{{ get_class($voucherable) }}"/>
        <fieldset>
            <legend>
                <i class="fa fa-plus"></i>
                <span>@lang('accounting::vouchers.add')</span>
            </legend>
            {{--  <div class="form-group row">
                <div class="col">
                    <label for="debt_account">الحساب المدين</label>
                    <select name="debt_account" id="debt_account" class="form-control" {{ isset($debt_account_read_only) ? 'readonly' : '' }} required>
                        @foreach ($secondary_accounts as $account)
                        <option value="{{ $account->id }}" {{ isset($debt_account) ? ($debt_account->id == $account->id ? 'selected' : '') : '' }}>{{ $account->display() }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col">
                    <label for="credit_account">الحساب الدائن</label>
                    <select name="credit_account" id="credit_account" class="form-control" {{ isset($credit_account_read_only) ? 'readonly' : '' }} required>
                        @foreach ($secondary_accounts as $account)
                        <option value="{{ $account->id }}" {{ isset($credit_account) ? ($credit_account->id == $account->id ? 'selected' : '') : '' }}>{{ $account->display() }}</option>
                        @endforeach
                    </select>
                </div>
            </div>  --}}
            <div class="form-group row">
                @if ($type == 'all')
                    <div class="col col-xs-12">
                        <label for="type">@lang('accounting::global.type')</label>
                        <select name="type" id="type" class="form-control" required>
                            <option value="">@lang('accounting::vouchers.choose_type')</option>
                            @foreach (Modules\Accounting\Models\Voucher::TYPES as $type)
                                <option value="{{ $type }}">
                                    {{ Modules\Accounting\Models\Voucher::getStaticType($type) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @else
                    <input type="hidden" name="type" value="{{ $type == 'payment' ? Modules\Accounting\Models\Voucher::TYPE_PAYMENT : Modules\Accounting\Models\Voucher::TYPE_RECEIPT }}">
                @endif
                <div class="col col-xs-12">
                    <label for="amount">@lang('accounting::global.amount')</label>
                    <div class="input-group">
                        <input type="number" id="amount" name="amount" value="{{ $amount }}" min="1" class="form-control" @isset($max_amount) max="{{ $max_amount }}" @endisset required>
                        @if (isset($currency))
                            <input type="hidden" name="currency" value="{{ $currency }}">
                        @else
                            <select name="currency" id="currency" class="form-control" required>
                                <option value="@lang('accounting::global.riyal')">@lang('accounting::global.riyal')</option>
                                <option value="@lang('accounting::global.dollar')">@lang('accounting::global.dollar')</option>
                            </select>
                        @endif
                    </div>
                </div>
                {{--  <div class="col col-xs-12">
                    <label for="currency">@lang('accounting::global.currency')</label>
                    <select name="currency" id="currency" class="form-control" required>
                        <option value="@lang('accounting::global.riyal')">@lang('accounting::global.riyal')</option>
                        <option value="@lang('accounting::global.dollar')">@lang('accounting::global.dollar')</option>
                    </select>
                </div>  --}}
            </div>
            <div class="form-group">
                <label for="details">@lang('accounting::accounting.details')</label>
                <textarea name="details" id="details" rows="5" class="form-control"></textarea>
            </div>
            {{--  <div class="form-group">
                @component('accounting::components.entry-accounts')
                    @slot('class', 'accounts')
                    @slot('amount_class', 'amount')
                    @slot('btn', '#btn-submit')
                    @isset($debts)
                        @slot('debts', $debts)
                    @endisset
                    @isset($credits)
                        @slot('credits', $credits)
                    @endisset
                @endcomponent
            </div>  --}}
            <div class="form-group">
                <label>
                    <i class="fas fa-paperclip"></i>
                    <span>@lang('accounting::global.attachments')</span>
                </label>
                @component('accounting::components.attachments-uploader')
                    @slot('prefix', '-vouchers')
                @endcomponent
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">@lang('accounting::global.submit')</button>
                <button type="button" class="btn btn-secondary btn-cancel-voucher">@lang('accounting::global.cancel')</button>
            </div>
        </fieldset>
    </form>
    @endpermission
@endif
@push('head')
    <style>
        .vouchers-view .vouchers-view-header{
            border-bottom: 1px solid #ddd;
        }
        .vouchers-view .vouchers-view-body{}
        .vouchers-view .vouchers-view-footer{
            border-top: 1px solid #ddd;
        }
        .vouchers-view .vouchers-view-header,
        .vouchers-view .vouchers-view-body,
        .vouchers-view .vouchers-view-footer{
            padding: 15px;
        }
        .vouchers-form{ 
            display: none;
            padding: 15px;
            border: 1px solid #ddd;
        }
    </style>
@endpush
@push('foot')
    <script>
        $(function(){
            $('.vouchers-view .btn-add-voucher').click(function(){
                $('.vouchers-view').fadeOut()
                $('.vouchers-form').fadeIn()
            })
            $('.vouchers-form .btn-cancel-voucher').click(function(){
                $('.table-attachments-vouchers tbody').html('')
                $('.vouchers-form input[name=amount]').val('')
                $('.vouchers-form select[name=type]').val($('.vouchers-form select[name=type] option:first-child').val())
                $('.vouchers-form select[name=currency]').val($('.vouchers-form select[name=currency] option:first-child').val())
                $('.vouchers-view').fadeIn()
                $('.vouchers-form').fadeOut()
            })
        })
    </script>
@endpush