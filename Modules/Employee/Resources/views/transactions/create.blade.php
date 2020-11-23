@extends('layouts.master', [
    'datatable' => true, 
    'title' => 'اضافة معاملة',
    'datatable' => true,
    'summernote' => true,
    'crumbs' => [
        [route('transactions.index'), 'المعاملات'],
        ['#', 'اضافة معاملة'],
    ]
])

@push('head')
    
@endpush

@section('content')
    <section class="content">
        <form class="form" action="{{ route('transactions.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col">
                    @component('components.widget')
                        @slot('title')
                            <i class="fas fa-list"></i>
                            <span>البيانات</span>
                        @endslot
                        @slot('body')
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <label class="input-group-addon">
                                            السنة
                                        </label>
                                        <select class="form-control" name="year">
                                            @for($i = date('Y'); $i >= 2000; $i--)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <label class="input-group-addon">
                                            الشهر
                                        </label>
                                        <select class="form-control" name="month">
                                            @for($i = 1; $i <= 12; $i++)
                                                @php $m = ($i < 10) ? '0' + $i : $i; @endphp 
                                                <option value="{{ $m }}" {{ (date('m') == $m) ? 'selected' : '' }}>{{ $m }}</option>
                                                @endfor
                                        </select>
                                    </div>
                                </div>
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
                                <label>النوع</label>
                                <div class="row">
                                    @foreach (Modules\Accounting\Models\Transaction::TYPES as $type)
                                    <div class="col col-xs-12">
                                        <div class="custom-control custom-radio">
                                            <input 
                                                class="custom-control-input" type="radio" id="type-{{ $loop->index }}" 
                                                name="type" value="{{ $type }}" @if($type==Modules\Accounting\Models\Transaction::TYPE_DEBT) checked="checked" @endif />
                                            <label for="type-{{ $loop->index }}" class="custom-control-label">@lang('employee::transactions.types.' . $type)</label>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="form-group">
                                <label>المبلغ</label>
                                <input class="form-control" required autocomplete="off" type="number" name="amount" placeholder="المبلغ">
                            </div>
                            <div class="form-group">
                                <label>التفاصيل</label>
                                <textarea class="form-control" autocomplete="off" name="details" placeholder="التفاصيل"></textarea>
                            </div>
                            {{--  @component('accounting::components.form-safe')
                            @slot('layout', 'inline')
                            @endcomponent  --}}
                            <div class="form-group">
                                <label>كلمة المرور الحالية</label>
                                <input type="password" class="form-control" name="password" placeholder="كلمة المرور الحالية" required>
                            </div>
                        @endslot
                        @slot('footer')
                        <button type="submit" class="btn btn-primary">اكمال العملية</button>
                        @endslot
                    @endcomponent
                </div>
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