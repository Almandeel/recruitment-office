@php
    $layout = request('layout') == 'print' ? 'layouts.print' : 'layouts.master';
    $options = [
        'modals' => ['employee', 'attachment'],
        'datatable' => true, 
        'lightbox' => true, 
        'confirm_status' => true, 
        'title' => 'مرتب شهر: ' . $salary->month . ' للموظف: ' . $salary->employee->name,
        'crumbs' => [
            [route('accounting.salaries.index'), 'المرتبات'],
            ['#', 'مرتب شهر: ' . $salary->month . ' للموظف: ' . $salary->employee->name],
        ]
    ];

    if(request('layout') == 'print'){
        $options = [
            'title' => 'مرتب شهر: ' . $salary->month . ' للموظف: ' . $salary->employee->name,
        ];
    }
@endphp
@extends($layout, $options)
@if (request('layout') == 'print')
@push('content')
    <div id="details" class="clearfix">
        <div id="client">
            <div class="to">القسم:</div>
            <h2 class="name">{{ $salary->employee->department->title }}</h2>
            <div class="to">الموظف:</div>
            <h2 class="name">{{ $salary->employee->name }}</h2>
            <div class="to">الوظيفة:</div>
            <h2 class="name">{{ $salary->employee->position->title }}</h2>
            <div class="phone">{{ $salary->employee->phone }}</div>
            <div class="address">{{ $salary->employee->address }}</div>
            {{-- <div class="email"><a href="mailto:john@example.com">john@example.com</a></div>  --}}
        </div>
        <div id="invoice">
            <h1>مرتب شهر: {{ $salary->month }}</h1>
            <div class=""><strong>المبلغ</strong>: {{ $salary->displayAmount() }}</div>
            {{--  <div class=""><strong>الحالة</strong>: {{ $salary->displayStatus() }}</div>  --}}
            <div class="date"><strong>المسؤول</strong>: {{ $salary->auth()->name }}</div>
            <div class="date"><strong>تاريخ الانشاء</strong>: {{ $salary->created_at->format('Y/m/d') }}</div>
        </div>
    </div>
    <h2>تفاصيل المرتب</h2>
    <table border="0" cellspacing="0" cellpadding="0">
        <thead>
            <tr>
                <th class="desc">المعرف</th>
                <th class="unit">المرتب</th>
                <th class="desc">العلاوات</th>
                <th class="unit">الخصومات</th>
                <th class="desc">السلفيات</th>
                <th class="unit">الاجمالي</th>
                <th class="desc">الصافي</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="desc">{{ $salary->id }}</td>
                <td class="unit">{{ $salary->displayAmount() }}</td>
                <td class="desc">{{ $salary->money('bonus') }}</td>
                <td class="unit">{{ $salary->money('debts') }}</td>
                <td class="desc">{{ $salary->money('deducations') }}</td>
                <td class="unit">{{ $salary->money('total') }}</td>
                <td class="desc">{{ $salary->money('net') }}</td>
            </tr>
        </tbody>
    </table>
@endpush
@push('footer-extra')
    <div class="signatures row">
        <div class="signature col text-center">
            <h3>المحاسب</h3>
            <p>{{ auth()->user()->name }}</p>
        </div>
        <div class="signature col text-center">.</div>
        <div class="signature col text-center">
            <h3>الموظف</h3>
            <p>{{ $salary->employee->name }}</p>
        </div>
    </div>
@endpush
@else
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
                        {{--  @permission('salaries-update')
                        <a href="{{ route('accounting.salaries.edit', $salary) }}" class="dropdown-item">
                            <i class="fa fa-edit"></i>
                            <span>تعديل</span>
                        </a>
                        @endpermission  --}}
                        @permission('salaries-delete')
                        <a href="#" class="dropdown-item delete" data-form="#deleteForm-{{ $salary->id }}">
                            <i class="fa fa-trash"></i>
                            <span>حذف</span>
                        </a>
                        <form id="deleteForm-{{ $salary->id }}" style="display:none;"
                            action="{{ route('accounting.salaries.destroy', $salary->id) }}" method="post">
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
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>المعرف</th>
                                <th>الشهر</th>
                                <th>الموظف</th>
                                <th>المرتب</th>
                                <th>العلاوات</th>
                                <th>الخصومات</th>
                                <th>السلفيات</th>
                                <th>الاجمالي</th>
                                <th>الصافي</th>
                                {{--  <th>الحالة</th>  --}}
                                <th>المسؤول</th>
                                <th>التاريخ</th>
                                <th>الخيارات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $salary->id }}</td>
                                <td>{{ $salary->month }}</td>
                                <td>
                                    @if (auth()->user()->isAbleTo('employees-read'))
                                        <button class="btn btn-info btn-xs employees preview"   data-action="{{ route('employees.update', $salary->employee_id) }}" data-id="{{ $salary->employee->id }}" data-id="{{ $salary->employee->id }}"  data-name="{{ $salary->employee->name }}" data-line="{{ $salary->employee->line }}" data-public-line="{{ $salary->employee->public_line }}" data-started="{{ $salary->employee->started_at }}" data-salary="{{ $salary->employee->salary }}" data-position="{{ $salary->employee->position->title }}" data-department="{{ $salary->employee->department->title }}" data-toggle="modal" data-target="#employeeModal">
                                            <i class="fa fa-eye"></i>
                                            <span>{{ $salary->employee->name }}</span>
                                        </button>
                                    @else
                                        {{ $salary->employee->name }}
                                    @endif
                                </td>
                                <td>{{ $salary->displayAmount() }}</td>
                                <td>{{ $salary->money('bonus') }}</td>
                                <td>{{ $salary->money('debts') }}</td>
                                <td>{{ $salary->money('deducations') }}</td>
                                <td>{{ $salary->money('total') }}</td>
                                <td>{{ $salary->money('net') }}</td>
                                {{--  <td>{{ $salary->displayStatus() }}</td>  --}}
                                <td>{{ $salary->auth()->name }}</td>
                                <td>{{ $salary->created_at->format('Y/m/d') }}</td>
                                <td>
                                    <div class="btn-group">
                                        @permission('salaries-update')
                                        <a href="{{ route('accounting.salaries.show', ['salary' => $salary, 'layout' => 'print', 'prev_url' => route('accounting.salaries.show', $salary)]) }}" class="btn btn-default">
                                            <i class="fa fa-print"></i>
                                            <span>طباعة</span>
                                        </a></li>
                                        @endpermission
                                        @if(auth()->user()->isAbleTo('salaries-update') && $salary->statusIsWaiting())
                                            <button type="button" class="btn btn-success"
                                                data-toggle="status" 
                                                data-id="{{ $salary->id }}" 
                                                data-type="{{ get_class($salary) }}"
                                                data-status="approve"
                                                >
                                                <i class="fa fa-check"></i>
                                                <span>@lang('global.approve')</span>
                                            </button>
                                            <button type="button" class="btn btn-danger"
                                                data-toggle="status" 
                                                data-id="{{ $salary->id }}" 
                                                data-type="{{ get_class($salary) }}"
                                                data-status="reject"
                                                >
                                                <i class="fa fa-times"></i>
                                                <span>@lang('global.reject')</span>
                                            </button>
                                        @endif
                                        {{--  @permission('salaries-update')
                                        <a href="{{ route('accounting.salaries.edit', $salary) }}" class="btn btn-warning">
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
                                    <form id="deleteForm-{{ $salary->id }}" action="{{ route('accounting.salaries.destroy', $salary) }}" method="POST">
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
                        @slot('attachments', $salary->attachments)
                        @slot('canAdd', true)
                        @slot('view', 'timeline')
                        @slot('attachableType', get_class($salary))
                        @slot('attachableId', $salary->id)
                    @endcomponent
                </div>
            </div>
        </div>
        <!-- /.card -->
    </div>
@endsection
@endif
