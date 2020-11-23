@extends('layouts.master', [
    'datatable' => true, 
    'confirm_status' => true,
    'modals' => ['employee'],
    'title' => 'المعاملات',
    'crumbs' => [
        ['#', 'المعاملات'],
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
                                    <h3 class="card-title float-left">قائمة المعاملات</h3>
                                </div>
                                <div class="col-md-6">
                                    <h3 class="card-title float-right">
                                        <a class="btn btn-primary btn-sm" href="{{ route('accounting.transactions.create') }}"><i class="fa fa-plus"></i> إضافة</a>
                                        {{--  <button class="btn btn-primary btn-sm transactions" data-toggle="modal" data-target="#transactionModal"><i class="fa fa-plus"></i> إضافة</button>  --}}
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
                                <label for="type">@lang('accounting::global.type')</label>
                                <select class="form-control type" name="type" id="type">
                                    <option value="all" {{ ($type == 'all') ? 'selected' : '' }}>@lang('global.all')</option>
                                    @foreach ($types as $t)
                                    <option value="{{ $t }}" {{ ($type != 'all' && $t == $type) ? 'selected' : '' }}>
                                        @lang('transactions.types.' . $t)
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            {{--  <div class="form-group mr-2">
                                <label for="type">@lang('accounting::global.status')</label>
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
                            <table id="datatable" class="datatable table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>المعرف</th>
                                        <th>النوع</th>
                                        <th>الموظف</th>
                                        <th>المبلغ</th>
                                        {{--  <th>الحالة</th>  --}}
                                        <th>المسؤول</th>
                                        <th>التاريخ</th>
                                        <th>الخيارات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($transactions as $transaction)
                                        <tr>
                                            <td>{{ $transaction->id }}</td>
                                            <td>{{ $transaction->displayType() }}</td>
                                            <td>
                                                @if (auth()->user()->isAbleTo('employees-read'))
                                                    <button class="btn btn-info btn-xs employees preview"   data-action="{{ route('employees.update', $transaction->employee_id) }}" data-id="{{ $transaction->employee->id }}" data-id="{{ $transaction->employee->id }}"  data-name="{{ $transaction->employee->name }}" data-line="{{ $transaction->employee->line }}" data-public-line="{{ $transaction->employee->public_line }}" data-started="{{ $transaction->employee->started_at }}" data-salary="{{ $transaction->employee->salary }}" data-position="{{ $transaction->employee->position->title }}" data-department="{{ $transaction->employee->department->title }}" data-toggle="modal" data-target="#employeeModal">
                                                        <i class="fa fa-eye"></i>
                                                        <span>{{ $transaction->employee->name }}</span>
                                                    </button>
                                                @else
                                                    {{ $transaction->employee->name }}
                                                @endif
                                            </td>
                                            <td>{{ number_format($transaction->amount, 2) }}</td>
                                            {{--  <td>{{ $transaction->displayStatus() }}</td>  --}}
                                            <td>{{ $transaction->auth()->name }}</td>
                                            <td>{{ $transaction->created_at->format('Y/m/d') }}</td>
                                            <td>
                                                <div class="btn-group">
                                                    @permission('transactions-read')
                                                    <a href="{{ route('accounting.transactions.show', $transaction) }}" class="btn btn-info">
                                                        <i class="fa fa-eye"></i>
                                                        <span>@lang('global.show')</span>
                                                    </a></li>
                                                    @endpermission
                                                    @if(auth()->user()->isAbleTo('transactions-update') && $transaction->statusIsWaiting())
                                                        <button type="button" class="btn btn-success"
                                                            data-toggle="status" 
                                                            data-id="{{ $transaction->id }}" 
                                                            data-type="{{ get_class($transaction) }}"
                                                            data-status="approve"
                                                            >
                                                            <i class="fa fa-check"></i>
                                                            <span>@lang('global.approve')</span>
                                                        </button>
                                                        <button type="button" class="btn btn-danger"
                                                            data-toggle="status" 
                                                            data-id="{{ $transaction->id }}" 
                                                            data-type="{{ get_class($transaction) }}"
                                                            data-status="reject"
                                                            >
                                                            <i class="fa fa-times"></i>
                                                            <span>@lang('global.reject')</span>
                                                        </button>
                                                    @endif
                                                    {{--  @permission('transactions-update')
                                                    <a href="{{ route('accounting.transactions.edit', $transaction) }}" class="btn btn-warning">
                                                        <i class="fa fa-edit"></i>
                                                        <span>تعديل</span>
                                                    </a></li>
                                                    @endpermission  --}}
                                                    @permission('transactions-delete')
                                                    <a href="#" class="btn btn-danger delete" data-form="#deleteForm-{{ $transaction->id }}">
                                                        <i class="fa fa-trash"></i>
                                                        <span>حذف</span>
                                                    </a>
                                                    @endpermission
                                                </div>
                                                @permission('transactions-delete')
                                                <form id="deleteForm-{{ $transaction->id }}" action="{{ route('accounting.transactions.destroy', $transaction) }}" method="POST">
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
