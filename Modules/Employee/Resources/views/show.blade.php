@extends('layouts.master', [
    'modals' => ['employee', 'salary', 'transaction', 'attachment'],
    'datatable' => true, 
    'lightbox' => true, 
    'confirm_safeable' => true, 
    'title' => $employee->name,
    'crumbs' => [
        [route('employees.index'), 'الموظفين'],
        ['#', $employee->name],
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
                    <a class="nav-link" id="tabs-attendance-tab" data-toggle="pill" href="#tabs-attendance" role="tab" aria-controls="tabs-attendance"
                        aria-selected="false">الحضور</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="tabs-salaries-tab" data-toggle="pill" href="#tabs-salaries" role="tab"
                        aria-controls="tabs-salaries" aria-selected="false">المرتبات</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="tabs-vacations-tab" data-toggle="pill" href="#tabs-vacations" role="tab"
                        aria-controls="tabs-vacations" aria-selected="false">الاجازات</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="tabs-transactions-tab" data-toggle="pill" href="#tabs-transactions" role="tab"
                        aria-controls="tabs-transactions" aria-selected="false">المعاملات</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="tabs-attachments-tab" data-toggle="pill" href="#tabs-attachments" role="tab"
                        aria-controls="tabs-attachments" aria-selected="false">المرفقات</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">
                        اضافة(<span>معاملة / اجازة / مرتب</span>)
                        <span class="caret"></span>
                    </a>
                    <div class="dropdown-menu" x-placement="top-end">
                        @permission('transactions-create')
                        {{--  <a class="dropdown-item showTransactionModal" data-employee-id="{{ $employee->id }}" tabindex="-1" href="#">
                            <i class="fa fa-plus"></i>
                            <span>معاملة</span>
                        </a>  --}}
                        <a class="dropdown-item" tabindex="-1" href="{{ route('transactions.create', ['employee_id' => $employee->id]) }}">
                            <i class="fa fa-plus"></i>
                            <span>معاملة</span>
                        </a>
                        @endpermission
                        @permission('vacations-create')
                        <a class="dropdown-item" href="{{ route('vacations.create', ['employee_id' => $employee->id]) }}" tabindex="-1">
                            <i class="fa fa-plus"></i>
                            <span>اجازة</span>
                        </a>
                        @endpermission
                        @permission('salaries-create')
                        <a class="dropdown-item showSalaryModal"
                            data-action="{{ route('salaries.create', $employee) }}"
                            data-employee-id="{{ $employee->id }}" tabindex="-1" href="#">
                            <i class="fa fa-plus"></i>
                            <span>مرتب</span>
                        </a>
                        @endpermission
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                        <span>الخيارات</span>
                        <span class="caret"></span>
                    </a>
                    <div class="dropdown-menu">
                        @permission('employees-update')
                        <a href="{{ route('employees.edit', $employee) }}" class="dropdown-item">
                            <i class="fa fa-edit"></i>
                            <span>تعديل</span>
                        </a>
                        @endpermission
                        @permission('employees-delete')
                        <a href="#" class="dropdown-item delete" data-form="#deleteForm-{{ $employee->id }}">
                            <i class="fa fa-trash"></i>
                            <span>حذف</span>
                        </a>
                        <form id="deleteForm-{{ $employee->id }}" style="display:none;"
                            action="{{ route('employees.destroy', $employee->id) }}" method="post">
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
                    <table class="table table-bordered table-hover text-center">
                        <thead>
                            <tr>
                                <th>المعرف</th>
                                <th>اسم</th>
                                <th>المرتب</th>
                                <th>رقم الهاتف</th>
                                <th>القسم</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $employee->id }}</td>
                                <td>{{ $employee->name }}</td>
                                <td>{{ $employee->salary != null ? number_format($employee->salary) : 0 }}</td>
                                <td>{{ $employee->line }}</td>
                                <td>{{ $employee->department->title }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="tab-pane fade" id="tabs-attendance" role="tabpanel" aria-labelledby="tabs-attendance-tab">
                    <table id="datatable" class="datatable table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>التاريخ</th>
                                <th>وقت الحضور</th>
                                <th>وقت المغادرة</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($employee->attendances->where('time_out', '!=', null) as $index=>$attendance)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $attendance->created_at->format('Y-m-d') }}</td>
                                    <td>{{ \Carbon\Carbon::createFromFormat('H:i:s', $attendance->time_in)->format('h:i')  }}</td>
                                    <td>{{ \Carbon\Carbon::createFromFormat('H:i:s', $attendance->time_out)->format('h:i')  }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="tab-pane fade" id="tabs-salaries" role="tabpanel" aria-labelledby="tabs-salaries-tab">
                    <table id="datatable" class="datatable table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th class="desc">المعرف</th>
                                <th class="unit">المرتب</th>
                                <th class="unit">الاجمالي</th>
                                <th class="desc">الصافي</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($employee->salaries as $salary)
                            <tr>
                                <td class="desc">{{ $salary->id }}</td>
                                <td class="unit">{{ $salary->displayAmount() }}</td>
                                <td class="unit">{{ $salary->money('total') }}</td>
                                <td class="desc">{{ $salary->money('net') }}</td>
                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
                <div class="tab-pane fade" id="tabs-transactions" role="tabpanel" aria-labelledby="tabs-transactions-tab">
                    <div class="row">
                        <div class="col-xs-12 col-md-6">
                            <h3>المعاملات المالية</h3>
                        </div>
                        <div class="col-xs-12 col-md-6">
                            <form action="" method="get" class="form-inline">
                                @csrf
                                <div class="form-group">
                                    <label>السنة</label>
                                    <select class="form-control" name="year">
                                        @for($i = date('Y'); $i >= 2000; $i--)
                                        <option value="{{ $i }}" @if($year==$i) selected @endif>{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>الشهر</label>
                                    <select class="form-control" name="month">
                                        @for($i = 1; $i <= 12; $i++) @php $m=$i < 10 ? '0' + $i : $i; @endphp <option value="{{ $m }}"
                                            @if($month==$m) selected @endif>{{ $m }}</option>
                                            @endfor
                                    </select>
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-primary">
                                        <i class="fa fa-search"></i>
                                        <span>بحث</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    {{--  <h3>إجمالي المعاملات المالية لشهر: {{ $year . '-' . $month }}</h3>  --}}
                    <table class="table table-bordered table-hover text-center">
                        <thead>
                            <tr>
                                <td colspan="3">سلفة</td>
                                <td colspan="3">خصم</td>
                                <td colspan="3">علاوة</td>
                            </tr>
                            <tr>
                                <th>لاحقا</th>
                                <th>خزنة</th>
                                <th>بنك</th>
                                <th>لاحقا</th>
                                <th>خزنة</th>
                                <th>بنك</th>
                                <th>لاحقا</th>
                                <th>خزنة</th>
                                <th>بنك</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $totalDebts['later'] }}</td>
                                <td>{{ $totalDebts['safe'] }}</td>
                                <td>{{ $totalDebts['bank'] }}</td>
                                <td>{{ $totalDeducations['later'] }}</td>
                                <td>{{ $totalDeducations['safe'] }}</td>
                                <td>{{ $totalDeducations['bank'] }}</td>
                                <td>{{ $totalCredits['later'] }}</td>
                                <td>{{ $totalCredits['safe'] }}</td>
                                <td>{{ $totalCredits['bank'] }}</td>
                            </tr>
                        </tbody>
                    </table>

                    <h3>تفاصيل المعاملات المالية لشهر: {{ $year . '-' . $month }}</h3>
                    <table id="transactions-table" class="table table-bordered table-hover text-center">
                        <thead>
                            <tr>
                                <th>المعرف</th>
                                <th>الموظف</th>
                                <th>الخزنة</th>
                                <th>النوع</th>
                                <th>المبلغ</th>
                                <th>التفاصيل</th>
                                <th>التاريخ</th>
                                <th>الخيارات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transactions as $transaction)
                                <tr>
                                    <td>{{ $transaction->id }}</td>
                                    <td>{{ $transaction->auth() ? $transaction->auth()->name : '' }}</td>
                                    <td>{{ $transaction->safe() ? $transaction->safe()->name : 'لاحقا'}}</td>
                                    <td>{{ $transaction->getType() }}</td>
                                    <td>{{ number_format($transaction->amount, 2) }}</td>
                                    <td>{{ $transaction->details}}</td>
                                    <td>{{ $transaction->created_at->format('Y-m-d')}}</td>
                                    <td>
                                        @if ($transaction->entry)
                                            <span>
                                                <i class="fa fa-check"></i>
                                                <span>@lang('accounting::global.confirmed')</span>
                                            </span>
                                        @else
                                            @if (auth()->user()->isAbleTo('transactions-update'))
                                                <a href="#" class="btn btn-success btn-xs" 
                                                    data-confirm="safeable" 
                                                    data-safeable-type="{{ get_class($transaction) }}"
                                                    data-safeable-id="{{ $transaction->id }}"
                                                    data-safe-id="{{ $transaction->safe() ? $transaction->safe()->id : 0 }}"
                                                    data-amount="{{ $transaction->amount }}"
                                                    data-type="{{ $transaction->getType() }}"
                                                    data-account-id="{{ $transaction->account_id }}"
                                                    >
                                                    <i class="fa fa-check"></i>
                                                    <span>@lang('accounting::global.confirm_title')</span>
                                                </a>
                                            @else
                                                <span>
                                                    <i class="fa fa-time"></i>
                                                    <span>@lang('accounting::global.confirming')</span>
                                                </span>
                                            @endif
                                        @endif
                                        @permission('transactions-update')
                                        <button type="button" class="btn btn-warning btn-xs showTransactionModal update"
                                            data-action="{{ route('transactions.update', $transaction->id) }}" 
                                            data-id="{{ $transaction->id }}"
                                            data-employee-id="{{ $transaction->employee_id }}"
                                            data-safe-id="{{ $transaction->safe_id }}"
                                            data-user-id="{{ $transaction->user_id }}"
                                            data-amount="{{ $transaction->amount }}" data-type="{{ $transaction->type }}" data-details="{{ $transaction->details }}"
                                            data-month="{{ $transaction->month }}"><i class="fa fa-edit"></i> تعديل </button>
                                        @endpermission
                                        @permission('transactions-delete')
                                        <form action="{{ route('transactions.destroy', $transaction->id) }}" method="POST" style="display: inline">
                                            @csrf
                                            {{ method_field('DELETE') }}
                                            <button type="submit" class="btn btn-danger btn-xs delete"><i class="fa fa-trash"></i> حذف </button>
                                        </form>
                                        @endpermission
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="tab-pane fade" id="tabs-vacations" role="tabpanel" aria-labelledby="tabs-vacations-tab">
                    <table id="datatable" class="datatable table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th> الاجازة</th>
                                <th>بداية الاجازة</th>
                                <th>نهاية الاجازة</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($employee->vacations as $index=>$vacation)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $vacation->title }}</td>
                                    <td>{{ \Carbon\Carbon::createFromFormat('Y-m-d', $vacation->started_at)->format('Y-m-d')  }}</td>
                                    <td>{{ \Carbon\Carbon::createFromFormat('Y-m-d', $vacation->ended_at)->format('Y-m-d')  }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="tab-pane fade" id="tabs-attachments" role="tabpanel" aria-labelledby="tabs-attachments-tab">
                    @component('components.attachments-viewer')
                        @slot('attachments', $employee->attachments)
                        @slot('canAdd', true)
                        @slot('view', 'timeline')
                        @slot('attachableType', get_class($employee))
                        @slot('attachableId', $employee->id)
                    @endcomponent
                </div>
            </div>
        </div>
        <!-- /.card -->
    </div>
@endsection
