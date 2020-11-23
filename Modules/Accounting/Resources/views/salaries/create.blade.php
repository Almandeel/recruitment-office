@extends('layouts.master', [
    'datatable' => true, 
    'title' => 'اضافة مرتب',
    'datatable' => true,
    'summernote' => true,
    'crumbs' => [
        [route('accounting.salaries.index'), 'المرتبات'],
        ['#', 'اضافة مرتب'],
    ]
])

@push('head')
    <style>
        .form-wizard .step
        {
            display: none;
        }

        .form-wizard .step.active
        {
            display: block;
        }
    </style>
@endpush

@section('content')
    @component('components.widget')
        @slot('noPadding', true)
        @slot('title')
            <form action="{{ route('accounting.salaries.create') }}" method="GET" class="form-inline">
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
                    @slot('id', 'transactions')
                    @slot('title', 'تفاصيل معاملات الشهر')
                @endcomponent
            @endslot
            @slot('contents')
                @component('components.tab-content')
                    @slot('active', true)
                    @slot('id', 'details')
                    @slot('content')
                        <form id="form_salaries" action="{{ route('accounting.salaries.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col">
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
                                                    value="{{ $employee ? $employee->salary : '' }}" placeholder="المرتب">
                                            </div>
                                            <div class="col">
                                                <label>الصافي</label>
                                                <input class="form-control" required autocomplete="off" readonly type="number" name="net" value="{{ $net }}" min="1" placeholder="الصافي">
                                            </div>
                                        </div>
                                        <div class="form-grou row">
                                            <div class="col">
                                                <div class="form-group mr-2">
                                                    <label>الخزنة</label>
                                                    <select name="safe_id" class="form-control safes" required>
                                                        @foreach ($safes as $safe)
                                                        <option value="{{ $safe->id }}">{{ $safe->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="form-group mr-2">
                                                    <label>الحساب</label>
                                                    <select name="account_id" class="form-control accounts" required>
                                                        @foreach ($accounts as $account)
                                                        <option value="{{ $account->id }}">{{ $account->display() }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
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
                                </div>
                                <div class="col">
                                    <fieldset>
                                        <legend>
                                            <i class="fas fa-paperclip"></i>
                                            <span>المرفقات</span>
                                        </legend>
                                        <div>
                                            @component('components.attachments-uploader')
                                            @endcomponent
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary">اكمال العملية</button>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>
                        </form>
                    @endslot
                @endcomponent
                @component('components.tab-content')
                    @slot('id', 'transactions')
                    @slot('content')
                        <table class="datatable table table-bordered table-hover text-center">
                            <thead>
                                <tr>
                                    <th>المعرف</th>
                                    <th>الخزنة</th>
                                    <th>الحساب</th>
                                    <th>المبلغ</th>
                                    {{--  <th>الحالة</th>  --}}
                                    <th>المسؤول</th>
                                    <th>التاريخ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transactions as $transaction)
                                    <tr>
                                        <td>{{ $transaction->id }}</td>
                                        <td>
                                            @if (auth()->user()->isAbleTo('safes-read') && $transaction->safe->id)
                                                <a href="{{ route('safes.show', $transaction->safe) }}">
                                                    <span>{{ $transaction->safe->name }}</span>
                                                </a>
                                            @else
                                                {{ $transaction->safe->name }}
                                            @endif
                                        </td>
                                        <td>
                                            @if (auth()->user()->isAbleTo('accounts-read') && $transaction->account->id)
                                                <a href="{{ route('accounts.show', $transaction->account) }}">
                                                    <span>{{ $transaction->account->name }}</span>
                                                </a>
                                            @else
                                                {{ $transaction->account->name }}
                                            @endif
                                        </td>
                                        <td>{{ number_format($transaction->amount, 2) }}</td>
                                        {{--  <td>{{ $transaction->displayStatus() }}</td>  --}}
                                        <td>{{ $transaction->auth()->name }}</td>
                                        <td>{{ $transaction->created_at->format('Y/m/d') }}</td>
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

@push('foot')
<script>
</script>
@endpush
