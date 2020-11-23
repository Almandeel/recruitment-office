@extends('layouts.master', [
    'datatable' => true, 
    'title' => 'اضافة عهدة',
    'datatable' => true,
    'summernote' => true,
    'crumbs' => [
        [route('custodies.index'), 'العهد'],
        ['#', 'اضافة عهدة'],
    ]
])

@push('head')

@endpush

@section('content')
    <section class="content">
        <form class="form" action="{{ route('custodies.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
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
                                        <option value="{{ $emp->id }}" {{ !is_null($employee) ? (($employee->id == $emp->id) ? 'selected' : '') : '' }}>{{ $emp->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>المبلغ</label>
                                <div class="input-group">
                                    <input type="number" id="amount" name="amount" class="form-control" value="0"
                                        placeholder="@lang('accounting::global.amount')" required>
                                    <select name="currency" id="currency" class="form-control" required>
                                        <option value="ريال">ريال</option>
                                        <option value="دولار">دولار</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>التفاصيل</label>
                                <textarea class="form-control" autocomplete="off" name="details" placeholder="التفاصيل"></textarea>
                            </div>
                            <div class="form-group">
                                <label>كلمة المرور الحالية</label>
                                <input type="password" class="form-control" name="password" placeholder="كلمة المرور الحالية" required>
                            </div>
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
                                        <select name="debt_account" id="debt_account" class="form-control accounts"></select>
                                    </div>
                                    <div class="col">
                                        <label for="credit_account">@lang('accounting::global.to')</label>
                                        <select name="credit_account" id="credit_account" class="form-control accounts"></select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col">
                                        <label for="entry_amount">@lang('accounting::global.amount')</label>
                                        <div class="input-group">
                                            <input type="number" id="entry_amount" name="entry_amount" class="form-control" placeholder="@lang('accounting::global.amount')">
                                            <label class="form-control">ريال</label>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <label>@lang('accounting::global.date')</label>
                                        <input class="form-control" type="date" name="entry_date" placeholder="@lang('accounting::global.date')" value="{{ date('Y-m-d') }}" />
                                    </div>
                                </div>
                            @endslot
                        @endcomponent
                    </div>
                @endpermission
            </div>
            <div class="row">
                <div class="col">
                    @component('components.widget')
                        @slot('noTitle', true)
                        @slot('sticky', true)
                        @slot('title')
                            <i class="fas fa-paperclip"></i>
                            <span>المرفقات</span>
                        @endslot
                        @slot('body')
                            @component('components.attachments-uploader')
                            @endcomponent
                        @endslot
                        @slot('footer')
                            <button type="submit" class="btn btn-primary">اكمال العملية</button>
                        @endslot
                    @endcomponent
                </div>
            </div>
        </form>
    </section>
@endsection