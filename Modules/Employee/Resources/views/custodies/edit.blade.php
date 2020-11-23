@extends('layouts.master', [
    'datatable' => true, 
    'title' => 'تعديل ' . 'عهدة رقم: ' . $custody->id,
    'datatable' => true,
    'summernote' => true,
    'crumbs' => [
        [route('custodies.index'), 'العهد'],
        [route('custodies.show', $custody), 'عهدة رقم: ' . $custody->id],
        ['#', 'تعديل'],
    ]
])

@push('head')
    
@endpush

@section('content')
    <section class="content">
        <form class="form" action="{{ route('custodies.update', $custody) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col">
                    @component('components.widget')
                        @slot('title')
                            <i class="fas fa-list"></i>
                            <span>بيانات العهدة</span>
                        @endslot
                        @slot('body')
                            <div class="form-group row">
                            </div>
                            <div class="form-group">
                                <label>الموظف</label>
                                <select name="employee_id" id="employee_id" class="form-control select2">
                                    @foreach ($employees as $emp)
                                        <option value="{{ $emp->id }}" {{ ($employee->id == $emp->id) ? 'selected' : '' }}>{{ $emp->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>المبلغ</label>
                                <div class="input-group">
                                    <input type="number" id="amount" name="amount" class="form-control" value="{{ $custody->getAmount() }}"
                                        placeholder="@lang('accounting::global.amount')" required min="1">
                                    <select name="currency" id="currency" class="form-control" required>
                                        <option value="ريال" {{ $custody->getAmount('currency') == 'ريال' ? 'selected' : '' }}>ريال</option>
                                        <option value="دولار" {{ $custody->getAmount('currency') == 'دولار' ? 'selected' : '' }}>دولار</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>التفاصيل</label>
                                <textarea class="form-control" autocomplete="off" name="details" placeholder="التفاصيل">{{ $custody->details }}</textarea>
                            </div>
                            <div class="form-group">
                                <label>كلمة المرور الحالية</label>
                                <input type="password" class="form-control" name="password" placeholder="كلمة المرور الحالية" required>
                            </div>
                        @endslot
                        @slot('footer')
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-check"></i>
                                <span>إكمال العملية</span>
                            </button>
                        @endslot
                    @endcomponent
                </div>
                @permission('entries-create')
                    <div class="col">
                        @component('components.widget')
                            @slot('title')
                                <i class="fas fa-list"></i>
                                <span>بيانات قيد السند</span>
                            @endslot
                            @slot('body')
                                <div class="form-group row">
                                    <div class="col">
                                        <label for="debt_account">@lang('accounting::global.from')</label>
                                        <select name="debt_account" id="debt_account" class="form-control accounts" @if($custody->voucher->entry) data-selected="{{ $custody->voucher->entry->debts()->first()->id }}" @endif></select>
                                    </div>
                                    <div class="col">
                                        <label for="credit_account">@lang('accounting::global.to')</label>
                                        <select name="credit_account" id="credit_account" class="form-control accounts" @if($custody->voucher->entry) data-selected="{{ $custody->voucher->entry->credits()->first()->id }}" @endif></select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col">
                                        <label for="entry_amount">@lang('accounting::global.amount')</label>
                                        <div class="input-group">
                                            <input type="number" id="entry_amount" name="entry_amount" class="form-control" @if($custody->voucher->entry) value="{{ $custody->voucher->entry->amount }}" @endif placeholder="@lang('accounting::global.amount')">
                                            <label class="form-control">ريال</label>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <label>@lang('accounting::global.date')</label>
                                        <input class="form-control" type="date" name="entry_date" placeholder="@lang('accounting::global.date')" value="{{ is_null($custody->voucher->entry) ? date('Y-m-d') : $custody->voucher->entry->entry_date }}" />
                                    </div>
                                </div>
                            @endslot
                            @slot('footer')
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-check"></i>
                                    <span>إكمال العملية</span>
                                </button>
                            @endslot
                        @endcomponent
                    </div>
                @endpermission
            </div>
        </form>
    </section>
@endsection