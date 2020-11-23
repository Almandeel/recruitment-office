@extends('layouts.master', [
    'title' => 'سلفية: ' . $advance->id,
    'datatable' => true, 
    'modals' => ['attachment'],
    'crumbs' => [
        [route('offices.index'), 'المكاتب الخارجية'],
        [route('offices.show', ['office' => $advance->office, 'active_tab' => 'advances']), 'مكتب: ' . $advance->office->name],
        ['#', 'سلفية: ' . $advance->id]
    ]
])
@section('content')
    @component('accounting::components.tabs')
        @slot('items')
            @component('accounting::components.tab-item')
                @slot('id', 'details')
                @slot('active', true)
                @slot('title', __('accounting::global.details'))
            @endcomponent
            @component('accounting::components.tab-item')
                @slot('id', 'attachments')
                @slot('title', __('accounting::global.attachments'))
            @endcomponent
        @endslot
        @slot('contents')
            @component('accounting::components.tab-content')
                @slot('id', 'details')
                @slot('active', true)
                @slot('content')
                    <table class="table table-bordered table-striped text-center">
                        <thead>
                            <tr>
                                <th>المعرف</th>
                                <th>المبلغ</th>
                                <th>المدفوع</th>
                                <th>المتبقي</th>
                                <th>الحالة</th>
                                {{-- <th>الخيارات</th>  --}}
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $advance->id }}</td>
                                <td>{{ number_format($advance->amount, 2) }}</td>
                                <td>{{ $advance->payed(true) }}</td>
                                <td>{{ $advance->remain(true) }}</td>
                                <td>{{ $advance->displayStatus() }}</td>
                                {{-- <td>
                                                            <a href="{{ route('show.bill', $advance->id) }}" class="btn btn-info btn-sm">
                                <i class="fa fa-eye"></i>
                                <span>عرض</span>
                                </a>
                                </td> --}}
                            </tr>
                        </tbody>
                    </table>
                    <h3>السند</h3>
                    <table class="table table-bordered text-center">
                        <thead>
                            <tr>
                                <th>@lang('accounting::global.id')</th>
                                {{--  <th>@lang('accounting::global.type')</th>  --}}
                                <th>@lang('accounting::global.amount')</th>
                                <th>@lang('accounting::global.date')</th>
                                <th>@lang('accounting::global.status')</th>
                                <th>@lang('accounting::global.user')</th>
                                <th>@lang('accounting::global.options')</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $voucher->id }}</td>
                                {{--  <td>{{ $voucher->displayType() }}</td>  --}}
                                <td>{{ $voucher->money('amount', false) }}</td>
                                <td>{{ $voucher->voucher_date ? date('Y/m/d', strtotime($voucher->voucher_date)) : '...' }}</td>
                                <td>{{ $voucher->displayStatus() }}
                                </td>
                                <td>{{ is_object($voucher->auth()) ? $voucher->auth()->name : '...' }}</td>
                                <td>
                                    <div class="btn-group">
                                        @permission('vouchers-read')
                                        <a href="{{ route('home.voucher', ['voucher' => $voucher, 'layout' => 'print']) }}"
                                            class="btn btn-default">
                                            <i class="fa fa-print"></i>
                                            <span>طباعة</span>
                                        </a></li>
                                        @endpermission
                                        @if(auth()->user()->isAbleTo('vouchers-update') && $voucher->statusIsWaiting())
                                        <button type="button" class="btn btn-success" data-toggle="status" data-id="{{ $voucher->id }}"
                                            data-type="{{ get_class($voucher) }}" data-status="approve">
                                            <i class="fa fa-check"></i>
                                            <span>@lang('global.approve')</span>
                                        </button>
                                        <button type="button" class="btn btn-danger" data-toggle="status" data-id="{{ $voucher->id }}"
                                            data-type="{{ get_class($voucher) }}" data-status="reject">
                                            <i class="fa fa-times"></i>
                                            <span>@lang('global.reject')</span>
                                        </button>
                                        @endif
                                        @permission('vouchers-read')
                                        <a href="{{ route('home.voucher', $voucher) }}" class="btn btn-info">
                                            <i class="fa fa-eye"></i>
                                            <span>@lang('accounting::global.show')</span>
                                        </a>
                                        @endpermission
                                        @if ($voucher->isEditable())
                                        @permission('vouchers-delete')
                                        <a href="#" class="btn btn-danger delete" data-form="#deleteForm-{{ $voucher->id }}">
                                            <i class="fa fa-trash"></i>
                                            <span>@lang('accounting::global.delete')</span>
                                        </a>
                                        @endpermission
                                        @endif
                                    </div>
                                    @if ($voucher->isEditable())
                                    @permission('vouchers-delete')
                                    <form id="deleteForm-{{ $voucher->id }}" style="display:none;"
                                        action="{{ route('vouchers.destroy', $voucher->id) }}" method="post">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                    @endif
                                    @endpermission
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <h3>المدفوعات</h3>
                    <table class="table table-bordered datatable text-center">
                        <thead>
                            <tr>
                                <th>@lang('accounting::global.id')</th>
                                {{--  <th>@lang('accounting::global.type')</th>  --}}
                                <th>@lang('accounting::global.amount')</th>
                                <th>@lang('accounting::global.date')</th>
                                <th>@lang('accounting::global.status')</th>
                                <th>@lang('accounting::global.user')</th>
                                <th>@lang('accounting::global.options')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($vouchers as $voucher)
                            <tr>
                                <td>{{ $voucher->id }}</td>
                                {{--  <td>{{ $voucher->displayType() }}</td>  --}}
                                <td>{{ $voucher->money('amount', false) }}</td>
                                <td>{{ $voucher->voucher_date ? date('Y/m/d', strtotime($voucher->voucher_date)) : '...' }}</td>
                                <td>{{ $voucher->displayStatus() }}
                                </td>
                                <td>{{ is_object($voucher->auth()) ? $voucher->auth()->name : '...' }}</td>
                                <td>
                                    <div class="btn-group">
                                        @permission('vouchers-read')
                                        <a href="{{ route('home.voucher', ['voucher' => $voucher, 'layout' => 'print']) }}"
                                            class="btn btn-default">
                                            <i class="fa fa-print"></i>
                                            <span>طباعة</span>
                                        </a></li>
                                        @endpermission
                                        @if(auth()->user()->isAbleTo('vouchers-update') && $voucher->statusIsWaiting())
                                        <button type="button" class="btn btn-success" data-toggle="status" data-id="{{ $voucher->id }}"
                                            data-type="{{ get_class($voucher) }}" data-status="approve">
                                            <i class="fa fa-check"></i>
                                            <span>@lang('global.approve')</span>
                                        </button>
                                        <button type="button" class="btn btn-danger" data-toggle="status" data-id="{{ $voucher->id }}"
                                            data-type="{{ get_class($voucher) }}" data-status="reject">
                                            <i class="fa fa-times"></i>
                                            <span>@lang('global.reject')</span>
                                        </button>
                                        @endif
                                        @permission('vouchers-read')
                                        <a href="{{ route('home.voucher', $voucher) }}" class="btn btn-info">
                                            <i class="fa fa-eye"></i>
                                            <span>@lang('accounting::global.show')</span>
                                        </a>
                                        @endpermission
                                        @if ($voucher->isEditable())
                                        @permission('vouchers-delete')
                                        <a href="#" class="btn btn-danger delete" data-form="#deleteForm-{{ $voucher->id }}">
                                            <i class="fa fa-trash"></i>
                                            <span>@lang('accounting::global.delete')</span>
                                        </a>
                                        @endpermission
                                        @endif
                                    </div>
                                    @if ($voucher->isEditable())
                                    @permission('vouchers-delete')
                                    <form id="deleteForm-{{ $voucher->id }}" style="display:none;"
                                        action="{{ route('vouchers.destroy', $voucher->id) }}" method="post">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                    @endif
                                    @endpermission
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endslot
            @endcomponent
            @component('accounting::components.tab-content')
                @slot('id', 'attachments')
                @slot('content')
                    @component('accounting::components.attachments-viewer')
                        @slot('attachable', $advance)
                        @slot('view', 'timeline')
                        @slot('canAdd', true)
                    @endcomponent
                @endslot
            @endcomponent
        @endslot
    @endcomponent
@endsection
