@extends('layouts.master', [
    'datatable' => true, 
    'title' => 'اضافة موظف',
    'datatable' => true,
    'summernote' => true,
    'crumbs' => [
        [route('employees.index'), 'الموظفين'],
        ['#', 'اضافة موظف'],
    ]
])

@push('head')
    
@endpush

@section('content')
    <section class="content">
        <form class="form" action="{{ route('employees.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col">
                    @component('components.widget')
                        @slot('title')
                            <i class="fas fa-list"></i>
                            <span>البيانات</span>
                        @endslot
                        @slot('body')
                            <div class="form-group">
                                <label>الاسم</label>
                                <input class="form-control name" autocomplete="off" type="text" name="name" placeholder="الاسم" required />
                            </div>

                            <div class="form-group">
                                <label>الهاتف الداخلي</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text" id="basic-addon1">
                                            <input style="display:block;margin: 0 3px"  autocomplete="off" type="checkbox" name="public_line" value="1"  checked/>
                                            <span>هاتف عام</span>
                                        </label>
                                    </div>
                                    <input class="form-control" autocomplete="off" type="number" name="line" placeholder="الهاتف الداخلي" />
                                </div>
                            </div>


                            <div class="form-group">
                                <label>المرتب</label>
                                <input class="form-control" autocomplete="off" type="number" name="salary" placeholder="المرتب" required />
                            </div>
                            <div class="form-group">
                                <label> القسم</label>
                                @if (isset($departments))
                                <select id="departments" class="form-control editable" autocomplete="on" name="department_id" required>
                                    @foreach ($departments as $dm)
                                    <option value="{{ $dm->id }}" {{ $dm->id == $department_id ? 'selected' : '' }}>{{ $dm->title }}</option>
                                    @endforeach
                                </select>
                                @endif
                            </div>
                            <div class="form-group">
                                <label>الوظيفة</label>
                                @if (isset($positions))
                                <select id="positions" class="form-control editable" autocomplete="on" name="position_id" required>
                                    @foreach ($positions as $pos)
                                    <option value="{{ $pos->id }}" {{ $pos->id == $position_id ? 'selected' : '' }}>{{ $pos->title }}</option>
                                    @endforeach
                                </select>
                                @endif
                            </div>
                            <div class="form-group">
                                <label>تاريخ التعيين</label>
                                <input class="form-control" autocomplete="off" type="date" name="started_at" value="{{ date('Y-m-d') }}" placeholder="تاريخ التعيين" required />
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