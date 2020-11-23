@extends('layouts.master', [
    'title' => 'القسم: ' . $department->title,
    'modals' => ['department', 'employee'],
    'datatable' => true, 
    'crumbs' => [
        [route('departments.index'), ' الاقسام'],
        ['#', $department->title],
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
                                <td>{{ $department->id }}</td>
                                <th>الاسم</th>
                                <td>{{ $department->title }}</td>
                                <th>الخيارات</th>
                                <td>
                                    <button class="btn btn-warning btn-sm departments update" data-action="{{ route('departments.update', $department->id) }}"  data-title="{{ $department->title }}" data-toggle="modal" data-target="#departmentModal"><i class="fa fa-edit"></i> تعديل</button>
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
                            <h3 class="card-title float-left">قائمة الموظفين ({{ count($department->employees) }})</h3>
                        </div>
                        <div class="col-md-6">
                            <h3 class="card-title float-right">
                                <a href="{{ route('employees.create', ['department_id' => $department->id]) }}" class="btn btn-primary">
                                    <i class="fa fa-plus"></i>
                                    <span>اضافة موظف</span>
                                </a>
                                {{--  <button class="btn btn-primary btn-sm employees" data-toggle="modal" data-target="#employeeModal"><i class="fa fa-plus"></i> إضافة</button>  --}}
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
                            @foreach($department->employees as $index=>$employee)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $employee->name }}</td>
                                    <td>{{ $employee->salary }}</td>
                                    <td>{{ $employee->position->title }}</td>
                                    <td>
                                        @permission('employees-read')
                                            <button class="btn btn-info btn-xs employees preview" data-action="{{ route('employees.update', $employee->id) }}"
                                                data-id="{{ $employee->id }}" data-name="{{ $employee->name }}" data-started="{{ $employee->started_at }}"
                                                data-salary="{{ $employee->salary }}" data-position="{{ $employee->position->title }}"
                                                data-position="{{ $employee->position->title }}" data-toggle="modal" data-target="#employeeModal"><i
                                                    class="fa fa-eye"></i> عرض</button>
                                        @endpermission
                                        <div class="dropdown d-inline-block">
                                            <button class="btn btn-default btn-xs dropdown-toggle" type="button" data-toggle="dropdown">
                                                <span>المزيد</span>
                                                <span class="caret"></span>
                                                {{-- <span class="caret"></span> --}}
                                            </button>
                                            <div class="dropdown-menu">
                                                @permission('employees-read')
                                                <a href="{{ route('employees.show', $employee) }}" class="dropdown-item text-info">
                                                    <i class="fa fa-eye"></i>
                                                    <span>التفاصيل</span>
                                                </a></li>
                                                @endpermission
                                                @permission('vacations-read')
                                                <a href="#" class="dropdown-item text-primary vacation" data-toggle="modal" data-target="#vacationModal">
                                                    <i class="fa fa-plus"></i>
                                                    <span>طلب اجازة</span>
                                                </a></li>
                                                @endpermission
                                                @permission('salaries-read')
                                                <a href="{{ route('salaries.index', $employee->id) }}" class="dropdown-item text-info">
                                                    <i class="fa fa-eye"></i>
                                                    <span>المرتبات</span>
                                                </a></li>
                                                @endpermission
                                                @permission('employees-update')
                                                <a href="#" class="dropdown-item text-warning" data-action="{{ route('employees.update', $employee->id) }}"
                                                    data-id="{{ $employee->id }}" data-name="{{ $employee->name }}" data-started="{{ $employee->started_at }}"
                                                    data-salary="{{ $employee->salary }}" data-position="{{ $employee->position->id }}"
                                                    data-department="{{ $employee->department->id }}">
                                                    <i class="fa fa-edit"></i>
                                                    <span>تعديل</span>
                                                </a></li>
                                                @endpermission
                                                @permission('employees-delete')
                                                <a href="#" class="dropdown-item delete text-danger" data-form="#deleteForm">
                                                    <i class="fa fa-trash"></i>
                                                    <span>حذف</span>
                                                </a>
                                                @endpermission
                                            </div>
                                            @permission('employees-delete')
                                            <form id="deleteForm" action="{{ route('employees.destroy', $employee) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i> حذف</button>
                                            </form>
                                            @endpermission
                                        </div>
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
