@extends('layouts.master', [
    'modals' => ['employee', 'attachment'],
    'datatable' => true, 
    'lightbox' => true, 
    'confirm_status' => true, 
    'title' => $transaction->displayType() . ': ' . $transaction->id,
    'crumbs' => [
        [route('transactions.index'), 'المعاملات'],
        ['#', $transaction->displayType() . ': ' . $transaction->id],
    ]
])
@section('content')
    <div class="card card-primary card-outline card-outline-tabs">
        <div class="card-header p-0 border-bottom-0">
            <ul class="nav nav-tabs" id="tabs-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="tabs-details-tab" data-toggle="pill" href="#tabs-details" role="tab"
                        aria-controls="tabs-details" aria-selected="true">البيانات</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="tabs-attachments-tab" data-toggle="pill" href="#tabs-attachments" role="tab"
                        aria-controls="tabs-attachments" aria-selected="false">المرفقات</a>
                </li>
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                        <span>الخيارات</span>
                        <span class="caret"></span>
                    </a>
                    <div class="dropdown-menu">
                        @permission('transactions-update')
                        <a href="{{ route('transactions.edit', $transaction) }}" class="dropdown-item">
                            <i class="fa fa-edit"></i>
                            <span>تعديل</span>
                        </a>
                        @endpermission
                        @permission('transactions-delete')
                        <a href="#" class="dropdown-item delete" data-form="#deleteForm-{{ $transaction->id }}">
                            <i class="fa fa-trash"></i>
                            <span>حذف</span>
                        </a>
                        <form id="deleteForm-{{ $transaction->id }}" style="display:none;"
                            action="{{ route('transactions.destroy', $transaction->id) }}" method="post">
                            @csrf
                            @method('DELETE')
                        </form>
                        @endpermission
                    </div>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="tabs-tabContent">
                <div class="tab-pane fade active show" id="tabs-details" role="tabpanel" aria-labelledby="tabs-details-tab">
                    <table class="datatable table table-bordered table-hover text-center">
                        <thead>
                            <tr>
                                <th>المعرف</th>
                                <th>الموظف</th>
                                <th>المبلغ</th>
                                <th>الحالة</th>
                                <th>المسؤول</th>
                                <th>التاريخ</th>
                                <th>الخيارات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $transaction->id }}</td>
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
                                <td>{{ $transaction->displayStatus() }}</td>
                                <td>{{ $transaction->auth()->name }}</td>
                                <td>{{ $transaction->created_at->format('Y/m/d') }}</td>
                                <td>
                                    <div class="btn-group">
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
                                        @permission('transactions-update')
                                        <a href="{{ route('transactions.edit', $transaction) }}" class="btn btn-warning">
                                            <i class="fa fa-edit"></i>
                                            <span>تعديل</span>
                                        </a></li>
                                        @endpermission
                                        @permission('transactions-delete')
                                        <a href="#" class="btn btn-danger delete" data-form="#deleteForm-{{ $transaction->id }}">
                                            <i class="fa fa-trash"></i>
                                            <span>حذف</span>
                                        </a>
                                        @endpermission
                                    </div>
                                    @permission('transactions-delete')
                                    <form id="deleteForm-{{ $transaction->id }}" action="{{ route('transactions.destroy', $transaction) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                    @endpermission
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="tab-pane fade" id="tabs-attachments" role="tabpanel" aria-labelledby="tabs-attachments-tab">
                    @component('components.attachments-viewer')
                        @slot('attachments', $transaction->attachments)
                        @slot('canAdd', true)
                        @slot('view', 'timeline')
                        @slot('attachableType', get_class($transaction))
                        @slot('attachableId', $transaction->id)
                    @endcomponent
                </div>
            </div>
        </div>
        <!-- /.card -->
    </div>
@endsection
