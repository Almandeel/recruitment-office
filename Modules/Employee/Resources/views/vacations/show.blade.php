@extends('layouts.master', [
    'modals' => ['employee', 'attachment'],
    'datatable' => true, 
    'lightbox' => true, 
    'title' => 'الاجازات',
    'crumbs' => [
        [route('vacations.index'), 'الاجازات'],
        ['#', 'اجازة رم: ' . $vacation->id . ' للموظف : ' . $vacation->employee->show],
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
                        @permission('vacations-update')
                            <a href="{{ route('vacations.edit', $vacation) }}" class="dropdown-item">
                                <i class="fa fa-edit"></i>
                                <span>تعديل</span>
                            </a>
                        @endpermission
                        @permission('vacations-delete')
                            <a href="#" class="dropdown-item delete" data-form="#deleteForm-{{ $vacation->id }}">
                                <i class="fa fa-times"></i>
                                <span>الغاء</span>
                            </a>
                            <form id="deleteForm-{{ $vacation->id }}" style="display:none;"
                                action="{{ route('vacations.destroy', $vacation->id) }}" method="post">
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
                    <table class="table table-striped">
                        <tr>
                            <th style="width: 140px;">الرقم</th>
                            <td>{{ $vacation->id }}</td>
                        </tr>
                        <tr>
                            <th>الموظف</th>
                            <td>
                                @if (auth()->user()->can('employees-read'))
                                    <a href="{{ route('employees.show', $vacation->employee) }}">{{ $vacation->employee->name }}</a>
                                @else
                                    {{ $vacation->employee->name }}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>الدفع</th>
                            <td>{{ $vacation->payed ? 'مدفوعة' : 'غير مدفوعة' }}</td>
                        </tr>
                        <tr>
                            <th>العنوان</th>
                            <td>{!! $vacation->title!!}</td>
                        </tr>
                        <tr>
                            <th>التفاصيل</th>
                            <td>{{ $vacation->details }}</td>
                        </tr>
                        <tr>
                            <th>تاريخ البداية</th>
                            <td>{{ $vacation->started_at }}</td>
                        </tr>
                        <tr>
                            <th>تاريخ النهاية</th>
                            <td>{{ $vacation->ended_at }}</td>
                        </tr>
                        <tr>
                            <th>تاريخ الانشاء</th>
                            <td>{{ $vacation->created_at->format('Y-m-d') }}</td>
                        </tr>
                    </table>
                </div>
                <div class="tab-pane fade" id="tabs-attachments" role="tabpanel" aria-labelledby="tabs-attachments-tab">
                    @component('components.attachments-viewer')
                        @slot('attachments', $vacation->attachments)
                        @slot('canAdd', true)
                        @slot('attachableType', 'Modules\Employee\Models\Vacation')
                        @slot('attachableId', $vacation->id)
                    @endcomponent
                </div>
            </div>
        </div>
        <!-- /.card -->
    </div>
@endsection
