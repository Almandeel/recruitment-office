@extends('layouts.master', [
    'datatable' => true, 
    'title' => 'تعديل اجازة',
    'datatable' => true,
    'summernote' => true,
    'lightbox' => true,
    'modals' => ['attachment'],
    'crumbs' => [
        [route('vacations.index'), 'الاجازات'],
        [route('vacations.show', $vacation), 'اجازة رم: ' . $vacation->id . ' للموظف : ' . $employee->name],
        ['#', 'تعديل'],
    ]
])

@push('head')
    
@endpush

@section('content')
    <section class="content">
        <div class="row">
            <div class="col">
                <form class="form" action="{{ route('vacations.update', $vacation) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
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
                                <input class="form-control" autocomplete="off" type="text" name="title" value="{{ $vacation->title }}" placeholder="العنوان" required />
                            </div>
                            
                            <div class="form-group">
                                <label>النوع</label>
                                <select id="payed" class="form-control" name="payed" required>
                                    <option value="0" {{ $vacation->payed == 0 ? 'selected' : '' }}>غير مدفوعة</option>
                                    <option value="1" {{ $vacation->payed == 1 ? 'selected' : '' }}> مدفوعة</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label>بداية الاجازة</label>
                                <input class="form-control" autocomplete="off" type="date" name="started_at" placeholder="بداية الاجازة" value="{{ $vacation->started_at }}" required />
                            </div>
                            
                            <div class="form-group">
                                <label>نهاية الاجازة</label>
                                <input class="form-control" autocomplete="off" type="date" name="ended_at" placeholder="نهاية الاجازة" value="{{ $vacation->ended_at }}" />
                            </div>
                            
                            <div class="form-group">
                                <label>التفاصيل</label>
                                <textarea class="form-control" autocomplete="off" name="details" placeholder="التفاصيل">{{ $vacation->details }}</textarea>
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
                        @slot('attachments', $vacation->attachments)
                        @slot('canAdd', true)
                        @slot('attachableType', 'Modules\Employee\Models\Vacation')
                        @slot('attachableId', $vacation->id)
                        @endcomponent
                    @endslot
                @endcomponent
            </div>
        </div>
    </section>
@endsection