@extends('layouts.master', [
    'title' => $position->title,
    'modals' => ['position', 'employee'],
    'datatable' => true, 
    'crumbs' => [
        [route('positions.index'), 'الوظائف'],
        ['#', $position->title],
    ]
])
@push('head')
    
@endpush


@section('content')
        <!-- Content Header (Page header) -->
        <!-- Main content -->
        <section class="content">
            <div class="card">
                <div class="card-header">
                    <table class="table table-bordered table-striped text-center">
                        <thead>
                            <tr>
                                <th>المعرف</th>
                                <td>{{ $position->id }}</td>
                                <th>الاسم</th>
                                <td>{{ $position->title }}</td>
                                <th>الخيارات</th>
                                <td>
                                    <a class="btn btn-info btn-xs" href="{{ route('positions.show', $position->id) }}"><i class="fa fa-eye"></i> عرض</a>
                                    @permission('positions-update')
                                    <button class="btn btn-warning btn-xs positions update"
                                        data-action="{{ route('positions.update', $position->id) }}" data-title="{{ $position->title }}"
                                        data-toggle="modal" data-target="#positionModal"><i class="fa fa-edit"></i> تعديل</button>
                                    @endpermission
                                    @permission('positions-delete')
                                    <button type="button" data-form="#deleteForm" class="btn btn-danger btn-xs delete"><i class="fa fa-trash"></i>
                                        <span>حذف</span> </button>
                                    <form id="deleteForm" action="{{ route('positions.destroy', $position->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                    @endpermission
                                </td>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>


            <div class="card text-right">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-6">
                            <h3 class="card-title float-left">قائمة الموظفين ({{ count($position->employees) }})</h3>
                        </div>
                        <div class="col-md-6">
                            <h3 class="card-title float-right">
                                <a href="{{ route('employees.create', ['position_id' => $position->id]) }}" class="btn btn-primary">
                                    <i class="fa fa-plus"></i>
                                    <span>اضافة موظف</span>
                                </a>
                                {{--  <button class="btn btn-primary btn-xs employees" data-toggle="modal" data-target="#employeeModal"><i class="fa fa-plus"></i> إضافة</button>  --}}
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table class="datatable table table-bordered table-striped text-center">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>الاسم</th>
                                <th>المرتب</th>
                                <th>الوظيفة</th>
                                <th>الخيارات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($position->employees as $index=>$employee)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $employee->name }}</td>
                                    <td>{{ $employee->salary }}</td>
                                    <td>{{ $employee->position->title }}</td>
                                    <td>
                                        @permission('employees-read')
                                            <button class="btn btn-info btn-xs employees preview"  data-action="{{ route('employees.update', $employee->id) }}" data-id="{{ $employee->id }}"  data-name="{{ $employee->name }}" data-started="{{ $employee->started_at }}" data-salary="{{ $employee->salary }}" data-position="{{ $employee->position->title }}" data-position="{{ $employee->position->title }}" data-toggle="modal" data-target="#employeeModal"><i class="fa fa-eye"></i> عرض</button>
                                        @endpermission
                                        @permission('employees-update')
                                            <button class="btn btn-warning btn-xs employees update"  data-action="{{ route('employees.update', $employee->id) }}"  data-name="{{ $employee->name }}" data-started="{{ $employee->started_at }}" data-salary="{{ $employee->salary }}" data-position="{{ $employee->position_id }}" data-position="{{ $employee->position_id }}" data-toggle="modal" data-target="#employeeModal"><i class="fa fa-edit"></i> تعديل</button>
                                        @endpermission
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
        <!-- /.content -->    
@endsection


@push('foot')

@endpush
