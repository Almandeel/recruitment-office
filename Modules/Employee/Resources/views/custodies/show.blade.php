@extends('layouts.master', [
    'modals' => ['employee', 'attachment'],
    'datatable' => true, 
    'lightbox' => true, 
    'confirm_status' => true, 
    'title' => 'عهدة رقم: ' . $custody->id,
    'crumbs' => [
        [route('custodies.index'), 'العهد'],
        ['#', 'عهدة رقم: ' . $custody->id],
    ]
])
@section('content')
    @component('components.tabs')
        @slot('items')
            @component('components.tab-item')
                @slot('id', 'details')
                @slot('active', true)
                @slot('title', __('accounting::global.details'))
            @endcomponent
            @component('components.tab-item')
                @slot('id', 'vouchers')
                @slot('title', __('accounting::global.vouchers'))
            @endcomponent
            @component('components.tab-item')
                @slot('id', 'attachments')
                @slot('title', __('accounting::global.attachments'))
            @endcomponent
            @permission('custodies-update|custodies-delete')
                @component('components.tab-dropdown')
                    @slot('title', __('accounting::global.options'))
                    @slot('items')
                        @permission('custodies-update')
                            @component('components.tab-dropdown-item')
                                @slot('href', route('custodies.edit', $custody))
                                @slot('content')
                                    <i class="fa fa-edit"></i>
                                    <span>تعديل</span>
                                @endslot
                            @endcomponent
                        @endpermission
                        @permission('custodies-delete')
                            @component('components.tab-dropdown-item')
                                @slot('href', "#")
                                @slot('content')
                                    <i class="fa fa-trash"></i>
                                    <span>حذف</span>
                                @endslot
                                @slot('attributes')
                                    data-toggle="confirm"
                                    data-form="#deleteForm-{{ $custody->id }}"
                                @endslot
                                @slot('extra')
                                    <form id="deleteForm-{{ $custody->id }}" style="display:none;"
                                        action="{{ route('custodies.destroy', $custody->id) }}" method="post">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                @endslot
                            @endcomponent
                        @endpermission
                    @endslot
                @endcomponent
            @endpermission
        @endslot
        @slot('contents')
            @component('components.tab-content')
                @slot('id', 'details')
                @slot('active', true)
                @slot('content')
                    <table class="datatable table table-bordered table-hover text-center">
                        <thead>
                            <tr>
                                <th>المعرف</th>
                                <th>الموظف</th>
                                <th>المبلغ</th>
                                <th>الحالة</th>
                                <th>المسؤول</th>
                                <th>التاريخ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $custody->id }}</td>
                                <td>
                                    @if (auth()->user()->isAbleTo('employees-read'))
                                        <button class="btn btn-info btn-xs employees preview"   data-action="{{ route('employees.update', $custody->employee_id) }}" data-id="{{ $custody->employee->id }}" data-id="{{ $custody->employee->id }}"  data-name="{{ $custody->employee->name }}" data-line="{{ $custody->employee->line }}" data-public-line="{{ $custody->employee->public_line }}" data-started="{{ $custody->employee->started_at }}" data-salary="{{ $custody->employee->salary }}" data-position="{{ $custody->employee->position->title }}" data-department="{{ $custody->employee->department->title }}" data-toggle="modal" data-target="#employeeModal">
                                            <i class="fa fa-eye"></i>
                                            <span>{{ $custody->employee->name }}</span>
                                        </button>
                                    @else
                                        {{ $custody->employee->name }}
                                    @endif
                                </td>
                                <td>{{ $custody->formated_amount }}</td>
                                <td>{{ $custody->displayStatus() }}</td>
                                <td>{{ $custody->user->name }}</td>
                                <td>{{ $custody->created_at->format('Y/m/d') }}</td>
                            </tr>
                        </tbody>
                    </table>
                @endslot
            @endcomponent
            @component('components.tab-content')
                @slot('id', 'vouchers')
                @slot('content')
                    @component('accounting::components.vouchers')
                        @slot('voucherable', $custody)
                        @slot('type', 'payment')
                        @slot('read_only', $custody->isPayed())
                        @slot('max_amount', $custody->remain())
                        @slot('amount', $custody->remain())
                        @slot('currency', $custody->getAmount('currency'))
                    @endcomponent
                @endslot
            @endcomponent
            @component('components.tab-content')
                @slot('id', 'attachments')
                @slot('content')
                    @component('components.attachments-viewer')
                        @slot('attachable', $custody)
                        @slot('view', 'timeline')
                        @slot('canAdd', true)
                    @endcomponent
                @endslot
            @endcomponent
        @endslot
    @endcomponent
@endsection
