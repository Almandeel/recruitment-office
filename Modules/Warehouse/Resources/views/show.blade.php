@extends('layouts.master', [
    'datatable' => true, 
    'modals' => ['warehouse', 'warehouseUser', 'warehouseCv'],
    'crumbs' => [
        [route('warehouses.index'), 'الإيواء'],
        ['#', "$warehouse->name" ]
    ]
    ])

@push('head')@endpush


@section('content')
    <section class="content">
        @component('components.tabs')
            @slot('items')
                @component('components.tab-item')
                    @slot('active', true)
                    @slot('id', 'details')
                    @slot('title', 'بيانات السكن')
                @endcomponent
                @component('components.tab-item')
                    @slot('id', 'cvs')
                    @slot('title')
                        النزلاء ({{ count($warehouse->warehouseCv) }})
                    @endslot
                @endcomponent
                @component('components.tab-item')
                    @slot('id', 'users')
                    @slot('title')
                        المشرفين ({{ count($warehouse->warehouseUsers) }})
                    @endslot
                @endcomponent
                @component('components.tab-item')
                    @slot('id', 'vouchers')
                    @slot('title', 'السندات')
                @endcomponent
            @endslot
            @slot('contents')
                @component('components.tab-content')
                    @slot('active', true)
                    @slot('id', 'details')
                    @slot('content')
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                <tr>
                                    <th style="width: 120px;">المعرف</th>
                                    <td>{{ $warehouse->id }}</td>
                                </tr>
                                <tr>
                                    <th>الاسم</th>
                                    <td>{{ $warehouse->name }}</td>
                                </tr>
                                <tr>
                                    <th>العنوان</th>
                                    <td>{{ $warehouse->address }}</td>
                                </tr>
                                <tr>
                                    <th>رقم الهاتف</th>
                                    <td>{{ $warehouse->phone }}</td>
                                </tr>
                                <tr>
                                    <th>الخيارات</th>
                                    <td>
                                        @permission('warehouses-update')
                                        <button class="btn btn-warning btn-xs warehouse update"
                                            data-action="{{ route('warehouses.update', $warehouse->id) }}" data-name="{{ $warehouse->name }}"
                                            data-address="{{ $warehouse->address }}" data-phone="{{ $warehouse->phone }}" data-toggle="modal"
                                            data-target="#warehouseModal"><i class="fa fa-edit"></i> تعديل</button>
                                        @endpermission
                                    </td>
                                </tr>
                                </tr>
                            </thead>
                        </table>
                    @endslot
                @endcomponent
                @component('components.tab-content')
                    @slot('id', 'cvs')
                    @slot('content')
                        <div class="clearfix">
                            <h3 class="float-left">
                                <i class="fa fa-list"></i>
                                <span>قائمة النزلاء</span>
                            </h3>
                            @permission('warehousecvs-create')
                            <button class="btn btn-primary float-right warehousecv" data-toggle="modal" data-name="{{ $warehouse->name }}" data-id="{{ $warehouse->id }}"  data-target="#warehouseCvModal">
                                <i class="fa fa-plus"></i>
                                <span>اضافة نزيل</span>
                            </button>
                            @endpermission
                        </div>
                        <table class="table table-bordered table-striped text-center">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>الاسم</th>
                                    <th>تاريخ الدخول</th>
                                    <th>تاريخ الخروج</th>
                                    <th>ملاحظات الدخول</th>
                                    <th>ملاحظات الخروج</th>
                                    <th>خيارات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($warehouse->warehouseCv as $index=>$warehouse_cv)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $warehouse_cv->cv->name }}</td>
                                    <td>{{ $warehouse_cv->entry_date }}</td>
                                    <td>{{ $warehouse_cv->exit_date ?? '-' }}</td>
                                    <td>{{ $warehouse_cv->entry_note }}</td>
                                    <td>{{ $warehouse_cv->exit_note ?? '-' }}</td>
                                    <td>
                                        @permission('warehousecvs-update')
                                            <button class="btn btn-warning btn-xs warehousecv update" data-toggle="modal"
                                            data-status="{{ $warehouse_cv->status }}"
                                            data-entry_note="{{ $warehouse_cv->entry_note }}"
                                            data-exit_note="{{ $warehouse_cv->exit_note }}"
                                            data-action="{{ route('warehousecv.update', $warehouse_cv->id) }}"
                                            data-cv="{{ $warehouse_cv->cv->id }}" data-entry="{{ $warehouse_cv->entry_date }}"
                                            data-exit="{{ $warehouse_cv->exit_date }}" data-target="#warehouseCvModal"><i
                                            class="fa fa-edit"></i> تعديل</button>
                                        @endpermission

                                        @if($warehouse_cv->exit_date == null)
                                            @permission('warehousecvs-update')
                                                <button class="btn btn-danger btn-xs warehousecv update exit-button" data-toggle="modal"
                                                data-status="{{ $warehouse_cv->status }}"
                                                data-entry_note="{{ $warehouse_cv->entry_note }}"
                                                data-exit_note="{{ $warehouse_cv->exit_note }}"
                                                data-action="{{ route('warehousecv.update', $warehouse_cv->id) }}"
                                                data-cv="{{ $warehouse_cv->cv->id }}" data-entry="{{ $warehouse_cv->entry_date }}"
                                                data-exit="{{ $warehouse_cv->exit_date }}" data-target="#warehouseCvModal"><i
                                                class="fa fa-edit"></i> خروج نزيل</button>
                                            @endpermission
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endslot
                @endcomponent
                @component('components.tab-content')
                    @slot('id', 'users')
                    @slot('content')
                        <div class="clearfix">
                            <h3 class="float-left">
                                <i class="fa fa-list"></i>
                                <span>قائمة المستخدمين</span>
                            </h3>
                            @permission('warehouses-create')
                            <button class="btn btn-primary float-right warehouseuser" data-toggle="modal" data-name="{{ $warehouse->name }}" data-id="{{ $warehouse->id }}"  data-target="#warehouseUserModal">
                                <i class="fa fa-plus"></i>
                                <span>اضافة مستخدم</span>
                            </button>
                            @endpermission
                        </div>
                        <table class="table table-bordered table-striped text-center">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>الاسم</th>
                                    <th>خيارات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($warehouse->warehouseUsers as $index=>$warehouse_user)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $warehouse_user->user->name }}</td>
                                    <td>
                                        @permission('warehouses-delete')
                                        <form action="{{ route('warehouseuser.distroyuser', $warehouse_user->id) }}" method="post">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-xs delete"><i class="fa fa-trash"> حذف</i></button>
                                        </form>
                                        @endpermission
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endslot
                @endcomponent
                @component('components.tab-content')
                    @slot('id', 'vouchers')
                    @slot('content')
                        @component('accounting::components.vouchers')
                            @slot('type', 'payment')
                            @slot('voucherable', $warehouse)
                        @endcomponent
                    @endslot
                @endcomponent
            @endslot
        @endcomponent
    </section>
    <!-- /.content -->    
@endsection


@push('foot')

@endpush
