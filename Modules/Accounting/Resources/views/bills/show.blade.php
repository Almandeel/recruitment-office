@extends('layouts.master', [
    'title' => 'فاتورة: ' . $bill->id,
    'datatable' => true, 
    'modals' => [],
    'crumbs' => [
        [route('accounting.bills.index'), 'فواير المكاتب الخارجية'],
        ['#', 'فاتورة: ' . $bill->id]
    ]
])
@section('content')
    @component('accounting::components.widget')
        @slot('title')
            <span>فاتورة رقم: {{ $bill->id }}</span>
            <span>للمكتب: {{ $bill->office->name }}</span>
        @endslot
    @endcomponent
    @component('accounting::components.tabs')
        @slot('items')
            @component('accounting::components.tab-item')
                @slot('id', 'details')
                @slot('active', true)
                @slot('title', __('accounting::global.details'))
            @endcomponent
            @component('accounting::components.tab-item')
                @slot('id', 'vouchers')
                @slot('title', __('accounting::global.vouchers'))
            @endcomponent
            @component('accounting::components.tab-item')
                @slot('id', 'attachments')
                @slot('title', __('accounting::global.attachments'))
            @endcomponent
            @if (!$bill->isPayed() || 1)
                @component('accounting::components.tab-item')
                    @slot('id', 'add-voucher')
                    @slot('title', 'تعديل السندات')
                @endcomponent
            @endif
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
                                 <th>الخيارات</th> 
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $bill->id }}</td>
                                <td>{{ number_format($bill->amount, 2) }}</td>
                                <td>{{ $bill->payed(true) }}</td>
                                <td>{{ $bill->remain(true) }}</td>
                                <td>{{ $bill->displayStatus() }}</td>
                                <td>
                                    @if ($bill->statusIs('checking'))
                                        @permission('bills-update')
                                            <form action="{{ route('accounting.bills.update', $bill) }}" method="post" class="d-inline-block">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="{{ App\Traits\Statusable::$STATUS_REJECTED }}" />
                                                <button type="submit" class="btn btn-warning" data-toggle="confirm" data-title="رفض الفاتورة" data-text="سوف يتم رفض الفاتورة هل انت متأكد">
                                                    <i class="fa fa-times"></i>
                                                    رفض
                                                </button>
                                            </form>
                                        @endpermission
                                    @elseif ($bill->statusIs('checked'))
                                    @endif
                                </td> 
                            </tr>
                        </tbody>
                    </table>
                    <h3>
                        <i class="fa fa-users"></i>
                        <span>العمالة</span>
                    </h3>
                    <table class="table table-bordered table-striped text-center">
                        <thead>
                            <tr>
                                <th rowspan="2">#</th>
                                <th rowspan="2">العامل \ العاملة</th>
                                <th rowspan="2">رقم الجواز</th>
                                <th colspan="2">القيمة</th>
                            </tr>
                            <tr>
                                <th>دولار</th>
                                <th>ريال</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bill->cvBill as $cvBill)
                            <tr>
                                <td>{{ $cvBill->cv->id }}</td>
                                <td>{{ $cvBill->cv->name }}</td>
                                <td>{{ $cvBill->cv->passport }}</td>
                                <td>{{ number_format($cvBill->amount, 2) }}</td>
                                <td>{{ number_format($cvBill->amount_in_riyal, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th>{{ number_format($bill->cvBill->sum('amount'), 2) }}</th>
                                <th>{{ number_format($bill->cvBill->sum('amount_in_riyal'), 2) }}</th>
                            </tr>
                        </tfoot>
                    </table>
                @endslot
            @endcomponent
            @component('accounting::components.tab-content')
                @slot('id', 'vouchers')
                @slot('content')
                    @component('accounting::components.vouchers')
                        @slot('voucherable', $bill)
                        @slot('type', 'payment')
                        @slot('read_only', true)
                        @slot('max_amount', $bill->remain())
                        @slot('amount', $bill->remain())
                        @slot('currency', 'دولار')
                    @endcomponent
                @endslot
            @endcomponent
            @component('accounting::components.tab-content')
                @slot('id', 'attachments')
                @slot('content')
                    @component('accounting::components.attachments-viewer')
                        @slot('attachable', $bill)
                        @slot('view', 'timeline')
                        @slot('canAdd', true)
                    @endcomponent
                @endslot
            @endcomponent
            @if (!$bill->isPayed() || 1)
                @component('accounting::components.tab-content')
                    @slot('id', 'add-voucher')
                    @slot('content')
                        <form action="{{ route('accounting.bills.update', $bill) }}" method="post" class="form-inline">
                            @csrf
                            @method('PUT')
                            <table class="table table-bordered table-striped text-center">
                                <thead>
                                    <tr>
                                        <th rowspan="2">#</th>
                                        <th rowspan="2">العامل \ العاملة</th>
                                        <th rowspan="2">رقم الجواز</th>
                                        <th rowspan="2">الحساب الدائن</th>
                                        <th colspan="2">القيمة</th>
                                    </tr>
                                    <tr>
                                        <th>دولار</th>
                                        <th>ريال</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($bill->cvBill as $cvBill)
                                        @php
                                            $cv_voucher = $cvBill->cv->voucher;
                                        @endphp
                                    <tr>
                                        <td>{{ $cvBill->cv->id }}</td>
                                        <td>{{ $cvBill->cv->name }}</td>
                                        <td>{{ $cvBill->cv->passport }}</td>
                                        <td>
                                            <select name="credit_accounts[]" id="credit_accounts">
                                                <option value="">إختر حساب</option>
                                                @foreach ($secondary_accounts as $account)
                                                    <option value="{{ $account->id }}" {{ $cv_voucher ? ($cv_voucher->entry->credits()->first()->id == $account->id ? 'selected' : '') : '' }}>{{ $account->display() }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>{{ number_format($cvBill->amount, 2) }}</td>
                                        <td>
                                            <input type="hidden" name="cvs_ids[]" value="{{ $cvBill->cv_id }}">
                                            <input type="number" name="cvs_amounts[]" class="form-control" value="{{ $cvBill->amount_in_riyal }}">
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-sync"></i>
                                <span>تعديل السندات</span>
                            </button>
                        </form>
                    @endslot
                @endcomponent
            @endif
        @endslot
    @endcomponent
@endsection
