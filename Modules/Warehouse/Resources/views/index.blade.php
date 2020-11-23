@extends('layouts.master', [
    'datatable' => true, 
    'modals' => ['warehouse'],
    'crumbs' => [
        ['#', 'الإيواء']
    ]
    ])

@push('head')
    
@endpush


@section('content')
        <section class="content">
            <div class="row">
                <div class="col-12">
                    <div class="card text-right">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-6">
                                    <h3 class="card-title float-left">قائمة الإيواء</h3>
                                </div>
                                <div class="col-md-6">
                                    <h3 class="card-title float-right">
                                        @permission('warehouses-create')
                                            <button class="btn btn-primary btn-sm warehouses" data-toggle="modal" data-target="#warehouseModal"><i class="fa fa-plus"></i> إضافة</button>
                                        @endpermission
                                    </h3>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body text-center">
                            <table id="datatable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>الاسم</th>
                                        <th>العنوان</th>
                                        <th>رقم الهاتف</th>
                                        <th>الخيارات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($warehouses as $index=>$warehouse)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $warehouse->name }}</td>
                                                <td>{{ $warehouse->address }}</td>
                                                <td>{{ $warehouse->phone }}</td>
                                                <td>
                                                    @permission('warehouses-read')
                                                    <a href="{{ route('warehouses.show', $warehouse->id) }}" class="btn btn-default btn-sm"><i class="fa fa-eye"></i> عرض</a>
                                                    @endpermission
                                                    @permission('warehouses-update')
                                                    <button class="btn btn-warning btn-sm warehouse update"  data-action="{{ route('warehouses.update', $warehouse->id) }}"  data-name="{{ $warehouse->name }}" data-address="{{ $warehouse->address }}" data-phone="{{ $warehouse->phone }}"  data-toggle="modal" data-target="#warehouseModal"><i class="fa fa-edit"></i> تعديل</button>
                                                    @endpermission
                                                    @permission('warehouses-delete')
                                                        <button type="button" class="btn btn-danger"
                                                            data-toggle="confirm"
                                                            data-title="@lang('global.confirm_delete_title')"
                                                            data-text="@lang('global.confirm_delete_text')"
                                                            data-form="#deleteForm-{{ $warehouse->id }}"
                                                            >
                                                            <i class="fa fa-trash"></i>
                                                            <span class="d-none d-lg-inline">@lang('global.delete')</span>
                                                        </button>
                                                    @endpermission
                                                    @permission('warehouses-delete')
                                                        <form id="deleteForm-{{ $warehouse->id }}" action="{{ route('warehouses.destroy', $warehouse) }}" method="post">
                                                            @csrf
                                                            @method('DELETE')
                                                        </form>
                                                    @endpermission
                                                </td>
                                            </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->    
@endsection


@push('foot')


@endpush
