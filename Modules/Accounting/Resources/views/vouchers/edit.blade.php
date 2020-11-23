@extends('accounting::layouts.master', [
    'datatable' => true, 
    'title' => $title,
    'datatable' => true,
    'modals' => ['attachment'],
    'summernote' => true,
    'crumbs' => [
        [route('vouchers.index'), __('accounting::global.vouchers')],
        [route('vouchers.show', $voucher), $voucher->getType() . ': ' . $voucher->id],
        ['#', $title],
    ]
])
@push('content')
    <section class="content">
        @if (request()->has('check'))
            <form class="form" action="{{ route('vouchers.update', $voucher) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="check" value="true">
                <div class="row">
                    <div class="col">
                        @component('components.widget')
                            @slot('title')
                                <i class="fas fa-list"></i>
                                <span>@lang('accounting::global.details')</span>
                            @endslot
                            @slot('body')
                                <div class="form-group row">
                                    @if (!$voucher->benefitIsModel() && is_null($voucher->bill()) && is_null($voucher->advance))
                                    <div class="col">
                                        <label>@lang('accounting::global.benefit')</label>
                                        <input class="form-control" autocomplete="off" type="text" name="voucherable_type" value="{{ $voucher->voucherable_type }}" placeholder="@lang('accounting::global.benefit')" />
                                    </div>
                                    @endif
                                    {{--  <div class="col">
                                        <label>@lang('accounting::global.number')</label>
                                        <input class="form-control" autocomplete="off" type="number" name="number" value="{{ $voucher->number ? $voucher->number : $voucher->id }}" placeholder="@lang('accounting::global.number')" />
                                    </div>  --}}
                                    @if (!$voucher->benefitIsModel() && is_null($voucher->bill()) && is_null($voucher->advance))
                                        <div class="col">
                                            <label>@lang('accounting::global.type')</label>
                                            <select class="form-control type" name="type" id="type" required>
                                                @foreach (\Modules\Accounting\Models\Voucher::TYPES as $type)
                                                <option value="{{ $type }}" {{ $type == $voucher->type ? 'selected' : '' }}>@lang('accounting::vouchers.types.' . $type)</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif
                                </div>
                                <div class="form-group row">
                                    <div class="col">
                                        <label>@lang('accounting::global.amount')</label>
                                        <div class="input-group">
                                            <input type="number" id="amount" name="amount" class="form-control" value="{{ $voucher->amount }}" required>
                                            <select name="currency" id="currency" class="form-control" required>
                                                <option value="ريال" {{ $voucher->currency == 'ريال' ? 'selected' : ''}}>ريال</option>
                                                <option value="دولار" {{ $voucher->currency == 'دولار' ? 'selected' : ''}}>دولار</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <label>@lang('accounting::global.date')</label>
                                        <input class="form-control" autocomplete="off" type="date" name="voucher_date" value="{{ $voucher->voucher_date ? $voucher->voucher_date : date('Y-m-d') }}" placeholder="@lang('accounting::global.date')" required />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>@lang('accounting::global.details')</label>
                                    <textarea class="form-control" name="details" placeholder="@lang('accounting::global.details')">{{ $voucher->details }}</textarea>
                                </div>
                                @php
                                    $contract = $voucher->contract;
                                @endphp
                                @if ($voucher->cv)
                                    @php
                                        $cv = $voucher->cv;
                                        $bill_cv = $cv->billPivot($voucher->voucherable_id);
                                        $bill = $bill_cv ? (get_class($bill_cv->bill) == $voucher->voucherable_type ? $bill_cv->bill : null) : null;
                                            
                                        $office = $cv->office;
                                        $contract = $cv->contract;
                                    @endphp
                                    <div class="form-group">
                                        <label>بيانات المكتب الخارجي</label>
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>المكتب</th>
                                                    <th>الدولة</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>{{ $office->name }}</td>
                                                    <td>{{ $office->country->name }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="form-group">
                                        <label>بيانات العاملة</label>
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>الإسم</th>
                                                    <th>رقم الجواز</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>{{ $cv->name }}</td>
                                                    <td>{{ $cv->passport }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                                @if ($contract)
                                    <div class="form-group">
                                        <label>بيانات العقد</label>
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>العميل</th>
                                                    <th>رقم التأشيرة</th>
                                                    <th>المسوق</th>
                                                    <th>نسبة المسوق</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>{{ $contract->customer->name }}</td>
                                                    <td>{{ $contract->visa }}</td>
                                                    <td>{{ $contract->marketer->name }}</td>
                                                    <td>{{ $contract->money('marketing_ratio') }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                                <label>بيانات القيد</label>
                                <div class="form-group row">
                                    <div class="col">
                                        <label for="debt_id">@lang('accounting::accounting.debt')</label>
                                        @if ($voucher->cv)
                                            <input type="text" class="form-control" value="{{ $voucher->cv->office->account->display() }}" disabled readonly>
                                            <input type="hidden" name="debt_id" value="{{ $voucher->cv->office->account->id }}">
                                        @else
                                            <select id="debt_id" name="debt_id" class="form-control" required>
                                                <option value="">إختر الحساب</option>
                                                @foreach ($accounts as $account)
                                                    <option value="{{ $account->id }}">{{ $account->display() }}</option>
                                                @endforeach
                                            </select>
                                        @endif
                                    </div>
                                    <div class="col">
                                        <label for="credit_id">@lang('accounting::accounting.credit')</label>
                                        <select id="credit_id" name="credit_id" class="form-control" required>
                                            <option value="">إختر الحساب</option>
                                            @foreach ($accounts as $account)
                                                <option value="{{ $account->id }}">{{ $account->display() }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col">
                                        <label>@lang('accounting::global.amount')</label>
                                        <div class="input-group">
                                            <input type="number" id="amount" name="entry_amount" class="form-control" value="{{ $entry_amount }}" required>
                                            <label class="form-control">ريال</label>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <label>@lang('accounting::global.date')</label>
                                        <input class="form-control" autocomplete="off" type="date" name="entry_date" value="{{ $voucher->voucher_date ? $voucher->voucher_date : date('Y-m-d') }}" placeholder="@lang('accounting::global.date')" required />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>@lang('accounting::entries.details')</label>
                                    <textarea class="form-control" name="entry_details" placeholder="@lang('accounting::entries.details')">{{ $voucher->details }}</textarea>
                                </div>
                            @endslot
                            @slot('footer')
                                <button type="submit" class="btn btn-primary">@lang('accounting::global.submit')</button>
                            @endslot
                        @endcomponent
                    </div>
                    {{--  <div class="col">
                        @component('components.widget')
                            @slot('title')
                                <i class="fas fa-list"></i>
                                <span>@lang('accounting::global.entry')</span>
                            @endslot
                            @slot('body')
                                <div class="form-group row">
                                    <div class="col">
                                        <label>@lang('accounting::global.safe')</label>
                                        <select name="safe_id" class="form-control" required>
                                            @foreach ($safes as $safe)
                                                <option value="{{ $safe->id }}">{{ $safe->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col">
                                        <label>@lang('accounting::global.account')</label>
                                        @if ($voucher->cv)
                                            <input type="text" class="form-control" value="{{ $voucher->cv->office->account->display() }}" disabled readonly>
                                            <input type="hidden" name="account_id" value="{{ $voucher->cv->office->account->id }}">
                                        @else
                                            <select name="account_id" class="form-control" required>
                                                @foreach ($accounts as $account)
                                                    <option value="{{ $account->id }}">{{ $account->display() }}</option>
                                                @endforeach
                                            </select>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col">
                                        <label>@lang('accounting::global.amount')</label>
                                        <div class="input-group">
                                            <input type="number" id="amount" name="entry_amount" class="form-control" value="{{ $voucher->amount }}" required>
                                            <label class="form-control">ريال</label>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <label>@lang('accounting::global.date')</label>
                                        <input class="form-control" autocomplete="off" type="date" name="entry_date" value="{{ $voucher->voucher_date ? $voucher->voucher_date : date('Y-m-d') }}" placeholder="@lang('accounting::global.date')" required />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>@lang('accounting::entries.details')</label>
                                    <textarea class="form-control" name="entry_details" placeholder="@lang('accounting::entries.details')">{{ $voucher->details }}</textarea>
                                </div>
                            @endslot
                            @slot('footer')
                                <button type="submit" class="btn btn-primary">@lang('accounting::global.submit')</button>
                            @endslot
                        @endcomponent
                    </div>  --}}
                </div>
            </form>
        @endif
        @if (!request()->has('check'))
            <div class="row">
                <div class="col">
                    <form class="form" action="{{ route('vouchers.update', $voucher) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        @component('components.widget')
                            @slot('title')
                                <i class="fas fa-list"></i>
                                <span>@lang('accounting::global.details')</span>
                            @endslot
                            @slot('body')
                                <div class="form-group row">
                                    @if (!$voucher->benefitIsModel())
                                    <div class="col">
                                        <label>@lang('accounting::global.benefit')</label>
                                        <input class="form-control" autocomplete="off" type="text" name="voucherable_type" value="{{ $voucher->voucherable_type }}" placeholder="@lang('accounting::global.benefit')" required />
                                    </div>
                                    @endif
                                    {{--  <div class="col">
                                        <label>@lang('accounting::global.number')</label>
                                        <input class="form-control" autocomplete="off" type="number" name="number" value="{{ $voucher->number ? $voucher->number : $voucher->id }}" placeholder="@lang('accounting::global.number')" />
                                    </div>  --}}
                                    <div class="col">
                                        <label>@lang('accounting::global.type')</label>
                                        <select class="form-control type" name="type" id="type" required>
                                            @foreach (\Modules\Accounting\Models\Voucher::TYPES as $type)
                                            <option value="{{ $type }}" {{ $type == $voucher->type ? 'selected' : '' }}>@lang('accounting::vouchers.types.' . $type)</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col">
                                        <label>@lang('accounting::global.amount')</label>
                                        <div class="input-group">
                                            <input type="number" id="amount" name="amount" class="form-control" value="{{ $voucher->amount }}" required>
                                            <select name="currency" id="currency" class="form-control" required>
                                                <option value="ريال" {{ $voucher->currency == 'ريال' ? 'selected' : ''}}>ريال</option>
                                                <option value="دولار" {{ $voucher->currency == 'دولار' ? 'selected' : ''}}>دولار</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <label>@lang('accounting::global.date')</label>
                                        <input class="form-control" autocomplete="off" type="date" name="voucher_date" value="{{ $voucher->voucher_date }}" placeholder="@lang('accounting::global.date')" required />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>@lang('accounting::global.details')</label>
                                    <textarea class="form-control" name="details" placeholder="@lang('accounting::global.details')">{{ $voucher->details }}</textarea>
                                </div>
                            @endslot
                            @slot('footer')
                                <button type="submit" class="btn btn-primary">@lang('accounting::global.submit')</button>
                            @endslot
                        @endcomponent
                    </form>
                </div>
                <div class="col">
                    @component('components.widget')
                        @slot('noTitle', true)
                        @slot('sticky', true)
                        @slot('title')
                            <i class="fas fa-paperclip"></i>
                            <span>@lang('accounting::global.attachments')</span>
                        @endslot
                        @slot('body')
                            @component('accounting::components.attachments-viewer')
                            @slot('attachments', $voucher->attachments)
                            {{--  @slot('canAdd', true)  --}}
                            @slot('attachableType', 'Modules\Accounting\Models\Voucher')
                            @slot('attachableId', $voucher->id)
                            @endcomponent
                        @endslot
                    @endcomponent
                </div>
            </div>
        @endif
    </section>
@endpush