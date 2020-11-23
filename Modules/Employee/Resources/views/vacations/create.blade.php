@extends('layouts.master', [
    'datatable' => true, 
    'title' => 'اضافة اجازة',
    'datatable' => true,
    'summernote' => true,
    'crumbs' => [
        [route('vacations.index'), 'الاجازات'],
        ['#', 'اضافة اجازة'],
    ]
])

@push('head')
    
@endpush

@section('content')
    <section class="content">
        <form class="form" action="{{ route('vacations.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col">
                    @component('components.widget')
                        @slot('title')
                            <i class="fas fa-list"></i>
                            <span>البيانات</span>
                        @endslot
                        @slot('body')
                            <div class="employees">
                                <div class="form-group">
                                    <label>الموظف</label>
                                    <select id="employee" class="form-control" name="employee_id" required>
                                        @foreach ($employees as $emp)
                                            <option value="{{ $emp->id }}" {{ $employee->id == $emp->id ? 'selected' : '' }}>{{ $emp->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label>العنوان</label>
                                <input class="form-control" autocomplete="off" type="text" name="title" placeholder="العنوان" required />
                            </div>
                            
                            <div class="form-group">
                                <label>النوع</label>
                                <select id="payed" class="form-control" name="payed" required>
                                    <option value="0">غير مدفوعة</option>
                                    <option value="1"> مدفوعة</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label>بداية الاجازة</label>
                                <input class="form-control" autocomplete="off" type="date" name="started_at" placeholder="بداية الاجازة" required />
                            </div>
                            
                            <div class="form-group">
                                <label>نهاية الاجازة</label>
                                <input class="form-control" autocomplete="off" type="date" name="ended_at" placeholder="نهاية الاجازة" required />
                            </div>
                            
                            <div class="form-group">
                                <label>التفاصيل</label>
                                <textarea class="form-control" autocomplete="off" name="details" placeholder="التفاصيل"></textarea>
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