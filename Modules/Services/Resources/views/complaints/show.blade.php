@extends('layouts.master', [
    'title' => 'شكوي : ' . $complaint->customer->name,
    'modals' => ['attachment'],
    'crumbs' => [
        [route('complaints.index'), ' الشكاوى'],
        ['#', $complaint->customer->name],
    ]
])
@push('head')

@endpush


@section('content')
<!-- Content Header (Page header) -->
<!-- Main content -->
<section class="content">
        @component('components.tabs')
            @slot('items')
                @component('components.tab-item')
                    @if (session('active_tab') != 'vouchers')
                        @slot('active', true)
                    @endif
                    @slot('id', 'details')
                    @slot('title', 'بيانات الشكوى')
                @endcomponent
                @component('components.tab-item')
                    @slot('id', 'attachments')
                    @slot('title', 'المرفقات')
                @endcomponent
            @endslot
            @slot('contents')
                @component('components.tab-content')
                    @if (session('active_tab') != 'vouchers')
                        @slot('active', true)
                    @endif
                    @slot('id', 'details')
                    @slot('content')
                    <table  class="table table-bordered table-striped text-center">
                        <thead>
                            <tr>
                                <th>المعرف</th>
                                <th>{{ $complaint->id }}</th>
                            </tr>
                            <tr>
                                <th>الشاكي</th>
                                <th>{{ $complaint->customer->name }}</th>
                            </tr>
                            <tr>
                                <th>العامل \ العاملة</th>
                                <th>{{ $complaint->cv->name ?? '' }}</th>
                            </tr>
                            <tr>
                                <th>رقم الجواز</th>
                                <th>{{ $complaint->cv->passport ?? '' }}</th>
                            </tr>
                            <tr>
                                <th style="width:50%">الشكوي</th>
                                <th>{{ $complaint->cause }}</th>
                            </tr>
                            <tr>
                                <th style="width:50%">الموظف</th>
                                <th>{{ $complaint->user->name }}</th>
                            </tr>
                            @if($complaint->status == 0)
                                <tr>
                                    <th>خيارات</th>
                                    <th>
                                        @permission('complaints-update')
                                            <form style="display:inline-block" action="{{ route('complaints.destroy', $complaint->id) }}" method="post">
                                                @csrf 
                                                @method('delete')
                                                <button type="submit" class="btn btn-danger btn-sm delete"><i class="fa fa-trash"></i> حذف</button>
                                            </form>
                                        @endpermission
                                        @permission('complaints-update')
                                            <form style="display:inline-block; position: relative;" action="{{ route('complaints.update', $complaint->id) }}" method="post">
                                                @csrf 
                                                @method('PUT')
                                                <button type="submit" class="btn btn-success btn-sm"><i class="fa fa-check"></i> تم</button>
                                            </form>
                                        @endpermission
                                    </th>
                                </tr>
                            @endif
                        </thead>
                    </table>
                    @endslot
                @endcomponent
                @component('components.tab-content')
                    @slot('id', 'attachments')
                    @slot('content')
                        @component('components.attachments-viewer')
                            @slot('attachable', $complaint)
                            @slot('canAdd', true)
                            @slot('view', 'timeline')
                        @endcomponent
                    @endslot
                @endcomponent
            @endslot
        @endcomponent
</section>
@endsection