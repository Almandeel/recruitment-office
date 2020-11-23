@extends('layouts.master', [
    'title' => 'العميل : ' . $customer->name,
    'modals' => ['customer', 'complaint'],
    'crumbs' => [
        [route('customers.index'), ' العملاء'],
        ['#', $customer->name],
    ]
])
@push('head')

@endpush


@section('content')
<!-- Content Header (Page header) -->
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="card" style="width:100%">
                <div class="card-header">
                    <table  class="table table-bordered table-striped text-center">
                        <thead>
                            <tr>
                                <th>المعرف</th>
                                <th>{{ $customer->id }}</th>
                            </tr>
                            <tr>
                                <th>الاسم</th>
                                <th>{{ $customer->name }}</th>
                            </tr>
                            <tr>
                                <th> رقم الهاتف </th>
                                <th>{{ $customer->phones }}</th>
                            </tr>
                            <tr>
                                <th>العنوان</th>
                                <th>{{ $customer->address }}</th>
                            </tr>
                            <tr>
                                <th>رقم الهوية</th>
                                <th>{{ $customer->id_number }}</th>
                            </tr>
                            <tr>
                                <th>ملاحظات</th>
                                <th>{{ $customer->description }}</th>
                            </tr>
                            <tr>
                                <th>تاريخ الإضافة</th>
                                <th>{{ $customer->created_at }}</th>
                            </tr>
                            <tr>
                                <th>الموظف</th>
                                <th>{{ $customer->user->name ?? '-' }}</th>
                            </tr>
                            <tr>
                                <th>خيارات</th>
                                <th>
                                    @permission('contracts-update')
                                    <button class="btn btn-warning btn-sm customer update" data-description="{{ $customer->description }}"   data-action="{{ route('customers.update', $customer->id) }}"  data-number="{{ $customer->id_number }}" data-name="{{ $customer->name }}" data-id_number="{{ $customer->id_number }}" data-address="{{ $customer->address }}" data-phones="{{ $customer->phones }}"  data-toggle="modal" data-target="#warehouseModal"><i class="fa fa-edit"></i> تعديل</button>
                                    @endpermission
                                </th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="card text-right">
        <div class="card-header">
            <div class="row">
                <div class="col-md-6">
                    <h3 class="card-title float-left"> الشكاوى</h3>
                </div>
                <div class="col-md-6">
                    <h3 class="card-title float-right">
                        {{-- @permission('complaints-create')
                        <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#complaintModal"><i class="fa fa-plus"></i> اضافة </button>
                        @endpermission --}}
                    </h3>
                </div>
            </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body text-center">
            <table id="datatable" class="datatable table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>الشاكي</th>
                        <th>رقم الهاتف</th>
                        <th>العامل \ العاملة</th>
                        <th>الحالة</th>
                        <th>التاريخ</th>
                        <th>الخيارات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($customer->complaints as $index=>$complaint)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $complaint->customer->name }}</td>
                                <td>{{ $complaint->customer->phones }}</td>
                                <td>{{ $complaint->cv->name }}</td>
                                <td class="{{ $complaint->status ? 'text-success' : 'text-warning' }}">{{ $complaint->status ? 'تم التعامل' : 'جاري التعامل' }}</td>
                                <td>{{ $complaint->created_at->format('Y-m-d') }}</td>
                                <td>
                                    @permission('complaints-read')
                                        <a href="{{ route('complaints.show', $complaint->id) }}" class="btn btn-default btn-sm"><i class="fa fa-eye"></i> عرض</a>
                                    @endpermission
                                </td>
                            </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- /.card-body -->
    </div>
</section>
@endsection