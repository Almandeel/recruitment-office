@extends('layouts.master', [
    'datatable' => true, 
    'datatable' => true,
    'summernote' => true,
    'title' => 'مرتب شهر: ' . $salary->month . ' للموظف: ' . $salary->employee->name,
    'crumbs' => [
        [route('salaries.index'), 'المرتبات'],
        [route('salaries.show', $salary), 'مرتب شهر: ' . $salary->month . ' للموظف: ' . $salary->employee->name],
        ['#', 'تعديل'],
    ]
])

@push('head')
    
@endpush

@section('content')
    @component('components.widget')
        @slot('noPadding', true)
        @slot('title')
            <form action="{{ route('salaries.create') }}" method="GET" class="form-inline">
                @csrf
                <div class="form-group mr-2">
                    <label for="employee_id">الموظف</label>
                    <select name="employee_id" id="employee_id" class="form-control select2" required>
                        <option value="">اختر موظف</option>
                        @foreach ($employees as $emp)
                        <option value="{{ $emp->id }}" {{ !is_null($employee) ? (($employee->id == $emp->id) ? 'selected' : '') : '' }}>
                            {{ $emp->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group mr-2">
                    <label for="year">السنة</label>
                    <select class="form-control" id="year" name="year">
                        @for($i = date('Y'); $i >= 2000; $i--)
                            <option value="{{ $i }}" {{ ($year == $i) ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div class="form-group mr-2">
                    <label for="month">الشهر</label>
                    <select class="form-control" id="month" name="month">
                        @for($i = 1; $i <= 12; $i++) 
                            @php $m=($i < 10) ? '0' + $i : $i; @endphp 
                            <option value="{{ $m }}" {{ ($month == $m) ? 'selected' : '' }}>{{ $m }}</option>
                        @endfor
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">
                    <span>@lang('accounting::global.search')</span>
                    <i class="fa fa-search"></i>
                </button>
            </form>
        @endslot
        @if ($employee)
            @slot('body')
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>المعرف</th>
                            <th>الاسم</th>
                            <th>القسم</th>
                            <th>الوظيفة</th>
                            <th>المرتب</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $employee->id }}</td>
                            <td>{{ $employee->name }}</td>
                            <td>{{ $employee->department->title }}</td>
                            <td>{{ $employee->position->title }}</td>
                            <td>{{ $employee->money('salary') }}</td>
                        </tr>
                    </tbody>
                </table>
            @endslot
        @endif
    @endcomponent
    @if ($employee)
        @component('components.tabs')
            @slot('items')
                @component('components.tab-item')
                    @slot('active', true)
                    @slot('id', 'details')
                    @slot('title', 'تفاصيل المرتب')
                @endcomponent
                @component('components.tab-item')
                    @slot('id', 'transactions-payed')
                    @slot('title', 'تفاصيل المعاملات المكتملة')
                @endcomponent
                @component('components.tab-item')
                    @slot('id', 'transactions-remain')
                    @slot('title', 'تفاصيل المعاملات المنتظرة')
                @endcomponent
            @endslot
            @slot('contents')
                @component('components.tab-content')
                    @slot('active', true)
                    @slot('id', 'details')
                    @slot('content')
                    <div class="row">
                        <div class="col">
                            <form class="form" action="{{ route('salaries.update', $salary) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <fieldset>
                                    <legend>
                                        <i class="fas fa-list"></i>
                                        <span>التفاصيل</span>
                                    </legend>
                                    <div class="form-group row">
                                        <div class="col">
                                            <label>السلفيات</label>
                                            <input class="form-control" readonly type="number" name="debts" value="{{ $totalDebts['total'] }}"
                                                placeholder="السلفيات">
                                        </div>
                                        <div class="col">
                                            <label>الخصومات</label>
                                            <input class="form-control" readonly type="number" name="deducations" value="{{ $totalDeducations['total'] }}"
                                                placeholder="الخصومات">
                                        </div>
                                        <div class="col">
                                            <label>العلاوات</label>
                                            <input class="form-control" readonly type="number" name="bonus" value="{{ $totalBonuses['total'] }}"
                                                placeholder="العلاوات">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col">
                                            <label>الاجمالي</label>
                                            <input class="form-control" required autocomplete="off" readonly type="number" name="total"
                                                value="{{ $total }}" placeholder="الاجمالي">
                                        </div>
                                        <div class="col">
                                            <label>المرتب</label>
                                            <input class="form-control" required autocomplete="off" readonly type="number" name="salary"
                                                value="{{ request()->has('employee_id') ? $employee->salary : $salary->amount() }}" placeholder="المرتب">
                                        </div>
                                        <div class="col">
                                            <label>الصافي</label>
                                            <input class="form-control" required autocomplete="off" readonly type="number" name="net" value="{{ $net }}"
                                                placeholder="الصافي">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>كلمة المرور الحالية</label>
                                        <input type="password" class="form-control" name="password" placeholder="كلمة المرور الحالية" required>
                                    </div>
                                    <div class="form-group">
                                        <input type="hidden" name="employee_id" value="{{ $employee->id }}">
                                        <input type="hidden" name="year" value="{{ $year }}">
                                        <input type="hidden" name="month" value="{{ $month }}">
                                        <button type="submit" class="btn btn-primary">اكمال العملية</button>
                                    </div>
                                </fieldset>
                            </form>
                        </div>
                        <div class="col">
                            @component('components.widget')
                                @slot('noTitle', true)
                                @slot('sticky', true)
                                @slot('title')
                                    <i class="fas fa-paperclip"></i>
                                    <span>المرفقات</span>
                                @endslot
                                @slot('body')
                                    @component('components.attachments-viewer')
                                    @slot('attachments', $salary->attachments)
                                    @slot('canAdd', true)
                                    @slot('attachableType', get_class($salary))
                                    @slot('attachableId', $salary->id)
                                    @endcomponent
                                @endslot
                            @endcomponent
                        </div>
                    </div>
                    @endslot
                @endcomponent
                @component('components.tab-content')
                    @slot('id', 'transactions-payed')
                    @slot('content')
                        <table id="transactions-payed-table" class="datatable table table-bordered table-striped table-hover text-center">
                            <thead>
                                <tr>
                                    <th>المعرف</th>
                                    <th>المستخدم</th>
                                    <th>النوع</th>
                                    <th>المبلغ</th>
                                    <th>الخزنة</th>
                                    <th>الحساب</th>
                                    <th>التاريخ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($payed_transactions as $payed_transaction)
                                <tr class="transaction-row" data-toggle="tooltip" data-placement="top" title="Tooltip on top">
                                    <td>{{ $payed_transaction->id}}</td>
                                    <td>{{ $payed_transaction->auth() ? $payed_transaction->auth()->name : '...'}}</td>
                                    <td>{{ $payed_transaction->displayType() }}</td>
                                    <td><strong data-amount="{{ $payed_transaction->amount }}" data-type="{{ $payed_transaction->type }}"
                                            data-payed="1">{{ $payed_transaction->money() }}</strong></td>
                                    <td>{{ $payed_transaction->safe() ? $payed_transaction->safe()->name : '...'}}</td>
                                    <td>{{ $payed_transaction->account() ? $payed_transaction->account()->name : '...'}}</td>
                                    <td>{{ $payed_transaction->created_at->format('Y-m-d')}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endslot
                @endcomponent
                @component('components.tab-content')
                    @slot('id', 'transactions-remain')
                    @slot('content')
                        <table id="transactions-remain-table" class="datatable table table-bordered table-striped table-hover text-center">
                            <thead>
                                <tr>
                                    <th>المعرف</th>
                                    <th>المستخدم</th>
                                    <th>النوع</th>
                                    <th>المبلغ</th>
                                    <th>التاريخ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($remain_transactions as $remain_transaction)
                                <tr class="transaction-row" data-toggle="tooltip" data-placement="top" title="Tooltip on top">
                                    <td>{{ $remain_transaction->id}}</td>
                                    <td>{{ $remain_transaction->auth() ? $remain_transaction->auth()->name : '...'}}</td>
                                    <td>{{ $remain_transaction->displayType() }}</td>
                                    <td><strong data-amount="{{ $remain_transaction->amount }}" data-type="{{ $remain_transaction->type }}"
                                            data-payed="1">{{ $remain_transaction->money() }}</strong></td>
                                    <td>{{ $remain_transaction->created_at->format('Y-m-d')}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endslot
                @endcomponent
            @endslot
        @endcomponent
    @else
        <div class="alert alert-warning">قم بإختيار الموظف اولا</div>
    @endif
@endsection