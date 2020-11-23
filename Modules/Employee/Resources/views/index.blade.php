@extends('layouts.master', [
    'datatable' => true, 
    'modals' => ['employee', 'vacation'],
    'title' => 'الموظفين',
    'crumbs' => [
        ['#', 'الموظفين'],
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
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-6">
                                    <h3 class="card-title float-left">قائمة الموظفين</h3>
                                </div>
                                <div class="col-md-6">
                                    <h3 class="card-title float-right">
                                        @permission('employees-create')
                                        <a class="btn btn-primary btn-sm" href="{{ route('employees.create') }}"><i class="fa fa-plus"></i> إضافة</a>
                                        {{--  <button class="btn btn-primary btn-sm employees" data-toggle="modal" data-target="#employeeModal"><i class="fa fa-plus"></i> إضافة</button>  --}}
                                        @endpermission
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
                                        <th>الاسم</th>
                                        <th>الهاتف الداخلي</th>
                                        <th>المرتب</th>
                                        <th>الوظيفة</th>
                                        <th>الخيارات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($employees as $index=>$employee)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $employee->name }}</td>
                                            <td>{{ $employee->line }}</td>
                                            <td>{{ $employee->salary }}</td>
                                            <td>{{ $employee->position->title }}</td>
                                            <td>
                                                @permission('employees-read')
                                                <button class="btn btn-info btn-xs employees preview"   data-action="{{ route('employees.update', $employee->id) }}" data-id="{{ $employee->id }}" data-id="{{ $employee->id }}"  data-name="{{ $employee->name }}" data-line="{{ $employee->line }}" data-public-line="{{ $employee->public_line }}" data-started="{{ $employee->started_at }}" data-salary="{{ $employee->salary }}" data-position="{{ $employee->position->title }}" data-department="{{ $employee->department->title }}" data-toggle="modal" data-target="#employeeModal"><i class="fa fa-eye"></i> عرض</button>
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
                                                        {{--  <a href="#" class="dropdown-item text-warning employees update"
                                                            data-action="{{ route('employees.update', $employee->id) }}" data-id="{{ $employee->id }}" data-public-line="{{ $employee->public_line }}"  data-name="{{ $employee->name }}" data-line="{{ $employee->line }}" data-started="{{ $employee->started_at }}" data-salary="{{ $employee->salary }}" data-position="{{ $employee->position->title }}" data-department="{{ $employee->department->title }}">
                                                                <i class="fa fa-edit"></i>
                                                                <span>تعديل</span>
                                                            </a></li>  --}}
                                                        <a href="{{ route('employees.edit', $employee->id) }}" class="dropdown-item text-warning">
                                                                <i class="fa fa-edit"></i>
                                                                <span>تعديل</span>
                                                            </a></li>
                                                        @endpermission
                                                        @permission('employees-delete')
                                                        <a href="#" class="dropdown-item delete text-danger" data-form="#deleteForm-{{ $employee->id }}">
                                                            <i class="fa fa-trash"></i>
                                                            <span>حذف</span>
                                                        </a>
                                                    </div>
                                                    <form id="deleteForm-{{ $employee->id }}" action="{{ route('employees.destroy', $employee) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                    @endpermission
                                                </div>
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
            </div>
        </section>
        <!-- /.content -->    
@endsection


@push('foot')
   
@endpush
