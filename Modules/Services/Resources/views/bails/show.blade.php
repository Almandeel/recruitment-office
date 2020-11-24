@extends('layouts.master', 
[
    'datatable' => true, 
    'confirm_status' => true, 
    'title' => 'كفالة رقم: ' . $bail->id, 
    'modals' => ['position', 'employee', 'attachment', 'show_customer', 'show_cv'],
    'crumbs' => [
        [route('bails.index'), 'نقل الكفالة'],
        ['#', 'كفالة رقم: ' . $bail->id],
    ],
])

@push('head')
    <style>
        @media (min-width: 992px) {
            .modal-lg, .modal-xl {
                max-width : 100%
            }
        }
        @media (min-width: 576px) {
            .modal-dialog {
                max-width : 90%
            }
        }
    </style>
@endpush


@section('content')
    <section class="content">
        @component('components.tabs')
            @slot('items')
                @component('components.tab-item')
                    @if (session('active_tab') != 'vouchers')
                        @slot('active', true)
                    @endif
                    @slot('id', 'details')
                    @slot('title', 'بيانات الكفالة')
                @endcomponent
                @component('components.tab-item')
                    @slot('id', 'contract')
                    @slot('title', 'بيانات العقد')
                @endcomponent
                @component('components.tab-item')
                    @slot('id', 'attachments')
                    @slot('title', 'المرفقات')
                @endcomponent
                @component('components.tab-item')
                    @if (session('active_tab') == 'vouchers')
                        @slot('active', true)
                    @endif
                    @slot('id', 'vouchers')
                    @slot('title', 'السندات')
                @endcomponent
                @permission('accounts-read')
                    @component('components.tab-item')
                        @slot('id', 'customerAccount')
                        @slot('title', 'كشف حساب العميل')
                    @endcomponent
                @endpermission
            @endslot
            @slot('contents')
                @component('components.tab-content')
                    @if (session('active_tab') != 'vouchers')
                        @slot('active', true)
                    @endif
                    @slot('id', 'details')
                    @slot('content')
                        <div class="row">
                            <div class="col">
                                <h4 class="text-center">بيانات العميل الاول</h4>
                                <div class="row">
                                    <div class="col">
                                        <strong>تاريخ العقد: </strong>
                                        <span>{{ $x_contract->created_at->format('Y-m-d') }}</span>
                                    </div>
                                    <div class="col">
                                        <strong>المهنة: </strong>
                                        <span>{{ $x_contract->cv->profession->name }}</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <strong>رقم العقد: </strong>
                                        <span>{{ $x_contract->id }}</span>
                                    </div>
                                    <div class="col">
                                        <strong>العاملة: </strong>
                                        <span>{{ $x_contract->cv->name }}</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <strong>قيمة العقد: </strong>
                                        <span>{{ number_format($x_contract->amount, 2) }}</span>
                                    </div>
                                    <div class="col">
                                        <strong>الديانة: </strong>
                                        <span>{{ $x_contract->cv->religion }}</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <strong>اسم العميل: </strong>
                                        <span>{{ $x_customer->name }}</span>
                                    </div>
                                    <div class="col">
                                        <strong>الحالة الاجتماعية: </strong>
                                        <span>{{ $x_contract->cv->marital_status }}</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <strong>المسوق: </strong>
                                        <span>{{ $x_contract->marketer->name }}</span>
                                    </div>
                                    <div class="col">
                                        <strong>نسبة المسوق: </strong>
                                        <span>{{ number_format($x_contract->marketing_ratio, 2) }}</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <strong>جهة القدوم: </strong>
                                        <span>{{ $x_contract->destination }}</span>
                                    </div>
                                    <div class="col">
                                        <strong>مطار القدوم: </strong>
                                        <span>{{ $x_contract->arrival_airport }}</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <strong>الدولة: </strong>
                                        <span>{{ $x_contract->country->name }}</span>
                                    </div>
                                    <div class="col">
                                        <strong>تاريخ الوصول: </strong>
                                        <span>{{ $x_contract->date_arrival }}</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <strong>تاريخ التقديم: </strong>
                                        <span>{{ $x_contract->start_date }}</span>
                                    </div>
                                    <div class="col">
                                        <strong>مدة التقديم: </strong>
                                        <span>{{ $x_contract->rem_trial }}  يوم</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col" style="border-right: 1px dotted;">
                                <h4 class="text-center">بيانات العميل الثاني</h4>
                                @if ($bail->isTrail())
                                    <form action="{{ route('bails.update', $bail) }}" method="post" class="form-inline">
                                        @csrf
                                        @method('PUT')
                                        <div class="form-group mr-2">
                                            <label for="status">
                                                <input type="checkbox" name="status" id="status" value="confirmed" required>
                                                <span>تأكيد نقل الكفالة</span>
                                            </label>
                                        </div>
                                        <div class="form-group mr-2">
                                            <button class="btn btn-primary">
                                                <span>حفظ</span>
                                            </button>
                                        </div>
                                        <div class="form-group">
                                            <span>قيد النقل</span>
                                        </div>
                                    </form>
                                @else
                                    <div>
                                        <strong>الحالة: </strong>
                                        <span>{{ $bail->display_status }}</span>
                                    </div>
                                @endif
                                <div class="row">
                                    <div class="col">
                                        <strong>تاريخ بداية التجربة</strong>
                                        <span>{{ $bail->trail_date }}</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <strong>فترة التجربة</strong>
                                        <span>{{ $bail->display_period_in_days }}</span>
                                    </div>
                                    <div class="col">
                                        <strong>المتبقي</strong>
                                        <span>{{ $bail->display_remain_period_in_days }}</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <strong>اسم العميل</strong>
                                        <span>{{ $customer->name }}</span>
                                    </div>
                                    <div class="col">
                                        <strong>رقم الهوية</strong>
                                        <span>{{ $customer->id_number }}</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <strong>تاريخ نقل الكفالة</strong>
                                        <span>{{ $bail->trail_date }}</span>
                                    </div>
                                    <div class="col">
                                        <strong>قيمة نقل الكفالة</strong>
                                        <span>{{ number_format($bail->amount, 2) }}</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <strong>المسوق</strong>
                                        <span>{{ $contract->marketer ? $contract->marketer->name : '' }}</span>
                                    </div>
                                    <div class="col">
                                        <strong>نسبة المسوق</strong>
                                        <span>{{ number_format($contract->marketing_ratio, 2) }}</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <strong>ملاحظة</strong>
                                        <span>{{ $bail->notes }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endslot
                @endcomponent
                @component('components.tab-content')
                    @slot('id', 'contract')
                    @slot('content')
                        <table class="table table-bordered table-striped text-center">
                            <thead>
                                <tr>
                                    <th>تاريخ الكفالة</th>
                                    <td>{{ $contract->created_at->format('Y-m-d') }}</td>
                                    <th>المهنة</th>
                                    <td>{{ $contract->profession->name }}</td>
                                </tr>
                                <tr>
                                    <th>
                                        الموظف
                                    </th>
                                    <td>{{ $contract->user->name ?? ' ' }}</td>
                                    <th>
                                        العامل \ العاملة
                                    </th>
                                    <td>
                                        <a href="#" data-toggle="modal" data-target="#showCvModal">
                                            {{ $contract->cv->name ?? '-' }}
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        رقم التأشيرة
                                    </th>
                                    <td>{{ $contract->visa }}</td>
                                    <th>
                                        الحالة الإجتماعية 
                                    </th>
                                    <td>{{ $contract->cv->nationality ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>
                                        اسم العميل
                                    </th>
                                    <td>
                                        <a href="#" class="show-customer" data-toggle="modal" data-target="#showModal" 
                                        data-name="{{ $contract->customer->name }}" 
                                        data-phone="{{ $contract->customer->phones }}"
                                        data-address="{{ $contract->customer->address }}"
                                        data-description="{{ $contract->customer->description }}"
                                        >
                                            {{ $contract->customer->name }}
                                        </a>
                                    </td>
                                    <th>
                                        رقم الهوية
                                    </th>
                                    <td>{{ $contract->customer->id_number }}</td>
                                </tr>
                                <tr>
                                    <th>
                                        قيمة الكفالة
                                    </th>
                                    <td>{{ $contract->amount }}</td>
                                    <th>
                                     الديانة
                                    </th>
                                    <td>{{ $contract->cv->religion ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>
                                        المسوق
                                    </th>
                                    <td>
                                        @if (auth()->user()->isAbleTo('marketers-show'))
                                            <a href="{{ route('marketers.show', $contract->marketer) }}">{{ $contract->marketer->name }}</a>
                                        @else
                                            {{ $contract->marketer->name ?? '' }}
                                        @endif
                                    </td>
                                    <th>
                                        جهة القدوم
                                    </th>
                                    <td>{{ $contract->destination  }}</td>
                                </tr>
                                <tr>
                                    <th>
                                        نسبة المسوق
                                    </th>
                                    <td>
                                        @php
                                            $marketer = $contract->marketer;
                                            $voucher = $contract->marketer_voucher;
                                        @endphp
                                        @if ($voucher)
                                            {{ number_format($contract->marketing_ratio, 2) }}
                                        @else
                                            <form action="{{ route('vouchers.store') }}" method="post" class="form-inline">
                                                @csrf
                                                <input type="hidden" name="marketer_id" value="{{ $marketer->id ?? '' }}">
                                                <input type="hidden" name="voucherable_id" value="{{ $contract->id }}">
                                                <input type="hidden" name="voucherable_type" value="{{ get_class($contract) }}">
                                                <input type="hidden" name="currency" value="ريال">
                                                <input type="hidden" name="contract_id" value="{{ $contract->id }}">
                                                <div class="input-group">
                                                    <input type="number" name="amount" class="form-control" value="{{ $contract->marketing_ratio }}" min="1">
                                                    <div class="input-group-append">
                                                        <button class="btn btn-primary">
                                                            <span>إنشاء سند</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        @endif
                                    </td>
                                    <th>
                                        مطار القدوم
                                    </th>
                                    <td>{{ $contract->arrival_airport  }}</td>
                                </tr>
                                <tr>
                                    <th>
                                        الدولة
                                    </th>
                                    <td>{{ $contract->country->name }}</td>
                                    <th>
                                        تاريخ الوصول
                                    </th>
                                    <td>{{ $contract->date_arrival  }}</td>
                                </tr>
                                <tr>
                                    <th>
                                        تاريخ التقديم
                                    </th>
                                    <td>{{ $contract->start_date }}</td>
                                    <th>
                                        مدة التقديم
                                    </th>
                                    <td>{{ $contract->getApplicationDays(true)  }}</td>
                                </tr>
                            </thead>
                        </table>
                    @endslot
                @endcomponent
                @component('components.tab-content')
                    @slot('id', 'attachments')
                    @slot('content')
                        @component('components.attachments-viewer')
                            @slot('attachable', $contract)
                            @slot('canAdd', true)
                            @slot('view', 'timeline')
                        @endcomponent
                    @endslot
                @endcomponent
                @component('components.tab-content')
                    @if (session('active_tab') == 'vouchers')
                        @slot('active', true)
                    @endif
                    @slot('id', 'vouchers')
                    @slot('content')
                        @component('accounting::components.vouchers')
                            @slot('voucherable', $contract)
                            @slot('currency', 'ريال')
                            @slot('vouchers', $contract->all_vouchers->merge($contract->cv_vouchers))
                        @endcomponent
                    @endslot
                @endcomponent
                @permission('accounts-read')
                    @component('components.tab-content')
                        @slot('id', 'customerAccount')
                        @slot('content')
                            @component('components.widget')
                                @slot('body')
                                    <table class="table table-bordered table-striped text-center datatable">
                                        <thead>
                                            <tr>
                                                <th>رقم الكفالة</th>
                                                <th>القيمة</th>
                                                <th>نسبة المسوق</th>
                                                <th>مصروفات العمالة</th>
                                                <th>إجمالي المصروفات</th>
                                                <th>الصافي</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>{{ $contract->id }}</td>
                                                <td>{{ $contract->money('amount') }}</td>
                                                <td>{{ $contract->money('marketing_ratio') }}</td>
                                                <td>{{ $contract->money('cvs_expenses') }}</td>
                                                <td>{{ $contract->money('expenses') }}</td>
                                                <td>{{ $contract->money('net') }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                @endslot
                            @endcomponent
                        @endslot
                    @endcomponent
                @endpermission
            @endslot
        @endcomponent
    </section>
    <!-- /.content -->    
@endsection


@push('foot')

@endpush
