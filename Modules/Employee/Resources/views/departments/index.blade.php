@extends('layouts.master', [
    'title' => 'الوظائف',
    'datatable' => true, 
    'modals' => ['department'], 
    'crumbs' => [
        ['#', 'الاقسام'],
    ]
])
@push('head')
    
@endpush


@section('content')
        <!-- Content Header (Page header) -->
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-12">
                    <div class="card text-right">
                        <div class="card-header">
                            <h3 class="card-title">قائمة الاقسام</h3>
                            <div class="card-tools">
                                <button class="btn btn-primary departments" data-toggle="modal" data-target="#departmentModal"><i class="fa fa-plus"></i> اضافة</button>
                                <button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i></button>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="datatable" class="datatable table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>الاسم</th>
                                        <th>الخيارات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($departments as $index=>$department)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $department->title }}</td>
                                            <td>
                                                @permission('departments-read')
                                                    <a class="btn btn-info btn-xs" href="{{ route('departments.show', $department->id) }}"><i class="fa fa-eye"></i> عرض</a>
                                                @endpermission
                                                @permission('departments-update')
                                                    <button class="btn btn-warning btn-xs departments update" data-action="{{ route('departments.update', $department->id) }}"  data-title="{{ $department->title }}" data-toggle="modal" data-target="#departmentModal"><i class="fa fa-edit"></i> تعديل</button>
                                                @endpermission
                                                @permission('departments-delete')
                                                <button type="button" data-form="#deleteForm-{{ $department->id }}" class="btn btn-danger btn-xs delete"><i class="fa fa-trash"></i>
                                                    <span>حذف</span> </button>
                                                <form id="deleteForm-{{ $department->id }}" action="{{ route('departments.destroy', $department->id) }}" method="POST">
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
