@extends('layouts.master', [
    'datatable' => true, 
    'confirm_status' => true,
    'modals' => ['employee'],
    'title' => 'المرتبات',
    'crumbs' => [
        ['#', 'المرتبات'],
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
                                <h3 class="card-title float-left">قائمة المرتبات</h3>
                            </div>
                            <div class="col-md-6">
                                <h3 class="card-title float-right">
                                    {{--  @permission('salaries-create')
                                    <a class="btn btn-primary btn-sm" href="{{ route('salaries.create') }}"><i class="fa fa-plus"></i> إضافة</a>
                                    <button class="btn btn-primary btn-sm salaries" data-toggle="modal" data-target="#salaryModal"><i class="fa fa-plus"></i> إضافة</button>
                                    @endpermission  --}}
                                </h3>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-extra">
                    <form action="" method="GET" class="form-inline guide-advanced-search">
                        @csrf
                        <div class="form-group mr-2">
                            <label>
                                <i class="fa fa-cogs"></i>
                                <span>@lang('accounting::global.search_advanced')</span>
                            </label>
                        </div>
                        <div class="form-group mr-2">
                            <label for="employee_id">@lang('global.employee')</label>
                            <select name="employee_id" id="employee_id" class="form-control select2" required>
                                <option value="all" {{ is_null($employee) ? 'selected' : '' }}>@lang('global.all')</option>
                                @foreach ($employees as $emp)
                                <option value="{{ $emp->id }}" {{ !is_null($employee) ? (($employee->id == $emp->id) ? 'selected' : '') : '' }}>
                                    {{ $emp->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        {{--  <div class="form-group mr-2">
                            <label for="status">@lang('accounting::global.status')</label>
                            <select class="form-control status" name="status" id="status">
                                <option value="all" {{ ($status == 'all') ? 'selected' : '' }}>@lang('global.all')</option>
                                @foreach ($statuses as $value => $key)
                                <option value="{{ $value }}" {{ ($status != 'all' && $value == $status) ? 'selected' : '' }}>
                                    @lang('global.statuses.' . $key)
                                </option>
                                @endforeach
                            </select>
                        </div>  --}}
                        <div class="form-group mr-2">
                            <label for="from-date">@lang('accounting::global.from')</label>
                            <input type="date" name="from_date" id="from-date" value="{{ $from_date }}" class="form-control">
                        </div>
                        <div class="form-group mr-2">
                            <label for="to-date">@lang('accounting::global.to')</label>
                            <input type="date" name="to_date" id="to-date" value="{{ $to_date }}" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <span>@lang('accounting::global.search')</span>
                            <i class="fa fa-search"></i>
                        </button>
                    </form>
                    </div>
                    <div class="card-body text-center">
                        <table class="datatable table table-bordered table-striped table-hover text-center">
                            <thead>
                                <tr>
                                    <th>المعرف</th>
                                    <th>الشهر</th>
                                    <th>الموظف</th>
                                    <th>المرتب</th>
                                    {{--  <th>العلاوات</th>
                                    <th>الخصومات</th>
                                    <th>السلفيات</th>
                                    <th>الاجمالي</th>
                                    <th>الصافي</th>  --}}
                                    {{--  <th>الحالة</th>  --}}
                                    <th>المسؤول</th>
                                    <th>التاريخ</th>
                                    <th>الخيارات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($salaries as $salary)
                                <tr>
                                    <td>{{ $salary->id }}</td>
                                    <td>{{ $salary->month }}</td>
                                    <td>
                                        @if (auth()->user()->isAbleTo('employees-read'))
                                        <button class="btn btn-info btn-xs employees preview"
                                            data-action="{{ route('employees.update', $salary->employee_id) }}"
                                            data-id="{{ $salary->employee->id }}" data-id="{{ $salary->employee->id }}"
                                            data-name="{{ $salary->employee->name }}" data-line="{{ $salary->employee->line }}"
                                            data-public-line="{{ $salary->employee->public_line }}"
                                            data-started="{{ $salary->employee->started_at }}" data-salary="{{ $salary->employee->salary }}"
                                            data-position="{{ $salary->employee->position->title }}"
                                            data-department="{{ $salary->employee->department->title }}" data-toggle="modal"
                                            data-target="#employeeModal">
                                            <i class="fa fa-eye"></i>
                                            <span>{{ $salary->employee->name }}</span>
                                        </button>
                                        @else
                                        {{ $salary->employee->name }}
                                        @endif
                                    </td>
                                    <td>{{ $salary->displayAmount() }}</td>
                                    {{--  <td>{{ $salary->money('bonus') }}</td>
                                    <td>{{ $salary->money('debts') }}</td>
                                    <td>{{ $salary->money('deducations') }}</td>
                                    <td>{{ $salary->money('total') }}</td>
                                    <td>{{ $salary->money('net') }}</td>  --}}
                                    {{--  <td>{{ $salary->displayStatus() }}</td>  --}}
                                    <td>{{ $salary->auth()->name }}</td>
                                    <td>{{ $salary->created_at->format('Y/m/d') }}</td>
                                    <td>
                                        <div class="btn-group">
                                            @permission('salaries-read')
                                            <a href="{{ route('salaries.show', ['salary' => $salary, 'layout' => 'print']) }}"
                                                class="btn btn-default">
                                                <i class="fa fa-print"></i>
                                                <span>طباعة</span>
                                            </a></li>
                                            @endpermission
                                            @permission('salaries-read')
                                            <a href="{{ route('salaries.show', $salary) }}" class="btn btn-info">
                                                <i class="fa fa-eye"></i>
                                                <span>عرض</span>
                                            </a></li>
                                            @endpermission
                                            @if(auth()->user()->isAbleTo('salaries-update') && $salary->statusIsWaiting())
                                            <button type="button" class="btn btn-success" data-toggle="status" data-id="{{ $salary->id }}"
                                                data-type="{{ get_class($salary) }}" data-status="approve">
                                                <i class="fa fa-check"></i>
                                                <span>@lang('global.approve')</span>
                                            </button>
                                            <button type="button" class="btn btn-danger" data-toggle="status" data-id="{{ $salary->id }}"
                                                data-type="{{ get_class($salary) }}" data-status="reject">
                                                <i class="fa fa-times"></i>
                                                <span>@lang('global.reject')</span>
                                            </button>
                                            @endif
                                            {{--  @permission('salaries-update')
                                            <a href="{{ route('salaries.edit', $salary) }}" class="btn btn-warning">
                                                <i class="fa fa-edit"></i>
                                                <span>تعديل</span>
                                            </a></li>
                                            @endpermission  --}}
                                            @permission('salaries-delete')
                                            <a href="#" class="btn btn-danger delete" data-form="#deleteForm-{{ $salary->id }}">
                                                <i class="fa fa-trash"></i>
                                                <span>حذف</span>
                                            </a>
                                            @endpermission
                                        </div>
                                        @permission('salaries-delete')
                                        <form id="deleteForm-{{ $salary->id }}" action="{{ route('salaries.destroy', $salary) }}" method="POST">
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
        </div>
    </section>
    <!-- /.content -->    
@endsection


@push('foot')
   
@endpush
