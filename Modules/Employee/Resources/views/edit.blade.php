@extends('layouts.master', [
    'datatable' => true, 
    'title' => 'تعديل موظف',
    'datatable' => true,
    'summernote' => true,
    'lightbox' => true,
    'modals' => ['attachment'],
    'crumbs' => [
        [route('employees.index'), 'الموظفين'],
        [route('employees.show', $employee), $employee->name],
        ['#', 'تعديل'],
    ]
])

@push('head')
    
@endpush

@section('content')
    <section class="content">
        <div class="row">
            <div class="col">
                <form class="form" action="{{ route('employees.update', $employee) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    @component('components.widget')
                        @slot('title')
                            <i class="fas fa-list"></i>
                            <span>البيانات</span>
                        @endslot
                        @slot('body')
                            <div class="form-group">
                                <label>الاسم</label>
                                <input class="form-control name" autocomplete="off" type="text" value="{{ $employee->name }}" name="name" placeholder="الاسم" required />
                            </div>
                            <div class="form-group">
                                <label>الهاتف الداخلي</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1">هاتف عام  <input style="display:block;margin: 0 3px"  autocomplete="off" type="checkbox" name="public_line" value="1" {{ $employee->public_line ? 'checked' : '' }}/></span>
                                    </div>
                                    <input class="form-control" autocomplete="off" type="number" name="line" placeholder="الهاتف الداخلي" value="{{ $employee->line }}" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label>المرتب</label>
                                <input class="form-control" autocomplete="off" type="number" value="{{ $employee->salary }}" name="salary" placeholder="المرتب" required />
                            </div>
                            <div class="form-group">
                                <label> القسم</label>
                                @if (isset($departments))
                                <select id="departments" class="form-control editable" autocomplete="off" name="department_id" required>
                                    @foreach ($departments as $department)
                                    <option value="{{ $department->id }}" {{ $department->id == $employee->department_id ? 'selected' : '' }}>{{ $department->title }}</option>
                                    @endforeach
                                </select>
                                @endif
                            </div>
                            <div class="form-group">
                                <label>الوظيفة</label>
                                @if (isset($positions))
                                <select id="positions" class="form-control editable" autocomplete="off" name="position_id" required>
                                    @foreach ($positions as $position)
                                    <option value="{{ $position->id }}" {{ $position->id == $employee->position_id ? 'selected' : '' }}>{{ $position->title }}</option>
                                    @endforeach
                                </select>
                                @endif
                            </div>
                            <div class="form-group">
                                <label>تاريخ التعيين</label>
                                <input class="form-control" autocomplete="off" type="date" value="{{ $employee->started_at }}" name="started_at" placeholder="تاريخ التعيين" required />
                            </div>
                        @endslot
                        @slot('footer')
                            <button type="submit" class="btn btn-primary">اكمال العملية</button>
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
                        <span>المرفقات</span>
                    @endslot
                    @slot('body')
                        @component('components.attachments-viewer')
                        @slot('attachments', $employee->attachments)
                        @slot('canAdd', true)
                        @slot('attachableType', 'Modules\Employee\Models\Employee')
                        @slot('attachableId', $employee->id)
                        @endcomponent
                    @endslot
                @endcomponent
            </div>
        </div>
    </section>
@endsection