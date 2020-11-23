@extends('layouts.master', [
    'title' => 'فاتورة: ' . $bill->id,
    'datatable' => true, 
    'modals' => [],
    'crumbs' => [
        [route('offices.index'), 'المكاتب الخارجية'],
        [route('offices.show', ['office' => $bill->office, 'active_tab' => 'bills']), 'مكتب: ' . $bill->office->name],
        ['#', 'فاتورة: ' . $bill->id]
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
                @slot('id', 'cvs')
                @slot('title', 'العمالة')
            @endcomponent
            @component('accounting::components.tab-item')
                @slot('id', 'vouchers')
                @slot('title', __('accounting::global.vouchers'))
            @endcomponent
            @component('accounting::components.tab-item')
                @slot('id', 'attachments')
                @slot('title', __('accounting::global.attachments'))
            @endcomponent
            @if (!$bill->isPayed())
                @component('accounting::components.tab-item')
                    @slot('id', 'advance-voucher')
                    @slot('title', 'إضافة سند من السلفيات')
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
                                    @if ($bill->statusIsWaiting())
                                        @permission('bills-update')
                                            <form action="{{ route('offices.bills.update', $bill) }}" method="post" class="d-inline-block">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="{{ App\Traits\Statusable::$STATUS_CHECKING }}" />
                                                <button type="submit" class="btn btn-success" data-toggle="confirm" data-title="إرسال الى الحسابات" data-text="سوف يتم إرسال الفاتورة الى الحسابات هل انت متأكد" data-icon="success">
                                                    <i class="fa fa-calculator"></i>
                                                    إرسال إلى الحسابات
                                                </button>
                                            </form>
                                        @endpermission
                                        @permission('bills-update')
                                            <form action="{{ route('offices.bills.update', $bill) }}" method="post" class="d-inline-block">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="{{ App\Traits\Statusable::$STATUS_REJECTED }}" />
                                                <button type="submit" class="btn btn-warning" data-toggle="confirm" data-title="رفض الفاتورة" data-text="سوف يتم رفض الفاتورة هل انت متأكد">
                                                    <i class="fa fa-times"></i>
                                                    رفض
                                                </button>
                                            </form>
                                        @endpermission
                                    @endif
                                    @permission('bills-delete')
                                        <form action="{{ route('offices.bills.destroy', $bill) }}" method="post" class="d-inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" data-toggle="confirm" data-title="حذف الفاتورة" data-text="سوف يتم حذف الفاتورة هل انت متأكد">
                                                <i class="fa fa-trash"></i>
                                                حذف
                                            </button>
                                        </form>
                                    @endpermission
                                </td>
                            </tr>
                        </tbody>
                    </table>
                @endslot
            @endcomponent
            @component('accounting::components.tab-content')
                @slot('id', 'cvs')
                @slot('content')
                    <form action="{{ route('offices.bills.update', $bill) }}" method="post" class="form-inline">
                        @csrf
                        @method('PUT')
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
                                    <td>
                                        <input type="hidden" name="cvs_ids[]" value="{{ $cvBill->cv_id }}">
                                        <input type="number" name="cvs_amounts[]" class="form-control" value="{{ $cvBill->amount_in_riyal }}">
                                    </td>
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
                                <tr>
                                    <th colspan="4"></th>
                                    <th class="text-right">
                                        <button type="submit" class="btn btn-primary btn-block">
                                            <i class="fa fa-save"></i>
                                            <span>حفظ</span>
                                        </button>
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    </form>
                @endslot
            @endcomponent
            @component('accounting::components.tab-content')
                @slot('id', 'vouchers')
                @slot('content')
                    @component('accounting::components.vouchers')
                        @slot('voucherable', $bill)
                        @slot('type', 'payment')
                        @slot('read_only', $bill->isPayed())
                        @slot('currency', 'دولار')
                        @slot('amount', $bill->remain())
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
            @if (!$bill->isPayed())
                @component('accounting::components.tab-content')
                    @slot('id', 'advance-voucher')
                    @slot('content')
                        <table class="table table-bordered table-striped text-center datatable">
                            <thead>
                                <tr>
                                    <th>المعرف</th>
                                    <th>القيمة</th>
                                    {{--  <th>الحالة</th>  --}}
                                    {{--  <th>الملاحظات</th>  --}}
                                    <th>الخيارات</th>
                                </tr>
                            <tbody>
                                @foreach ($advances as $index=>$advance)
                                <tr>
                                    <td>{{ $advance->id }}</td>
                                    <td>{{ number_format($advance->amount, 2) }}</td>
                                    {{--  <td>{{ $advance->displayStatus() }}</td>  --}}
                                    {{--  <td>{{ $advance->notes }}</td>  --}}
                                    <td>
                                        <form id="confirm-form-{{ $advance->id }}" action="{{ route('vouchers.store') }}" method="post" class="form-inline">
                                            @csrf
                                            <div class="form-group mr-2">
                                                <a href="{{ route('show.advance', $advance->id) }}" class="btn btn-info">
                                                    <i class="fa fa-eye"></i>
                                                    <span>عرض</span>
                                                </a>
                                            </div>
                                            <div class="form-group">
                                                <input type="number" name="amount" class="form-control" min="1" max="{{ $advance->remain() > $bill->remain() ? $bill->remain() : $advance->remain() }}" value="{{ $advance->remain() > $bill->remain() ? $bill->remain() : $advance->remain() }}">
                                            </div>
                                            <div class="form-group mr-2">
                                                <button type="submit" class="btn btn-success" data-toggle="confirm"
                                                    data-form="#confirm-form-{{ $advance->id }}" data-title="إضافة سند"
                                                    data-text="سوف يتم إضافة سند من السلفية استمرار؟">
                                                    <i class="fa fa-plus"></i>
                                                    <span>إضافة سند</span>
                                                </button>
                                            </div>
                                            <input type="hidden" name="type" value="{{ Modules\Accounting\Models\Voucher::TYPE_RECEIPT }}">
                                            <input type="hidden" name="voucherable_type" value="{{ get_class($bill) }}">
                                            <input type="hidden" name="voucherable_id" value="{{ $bill->id }}">
                                            <input type="hidden" name="advance_id" value="{{ $advance->id }}">
                                            {{--  <input type="hidden" name="no_validation" value="true">  --}}
                                            <input type="hidden" name="currency" value="دولار">
                                            <input type="hidden" name="status" value="{{ App\Traits\Statusable::$STATUS_CHECKED }}">
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endslot
                @endcomponent
            @endif
        @endslot
    @endcomponent
@endsection
