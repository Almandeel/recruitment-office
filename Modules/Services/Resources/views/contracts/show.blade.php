@extends('layouts.master',
[
    'datatable' => true,
    'confirm_status' => true,
    'title' => 'عقد رقم: ' . $contract->id,
    'modals' => ['position', 'employee', 'attachment', 'show_customer', 'show_cv'],
    'crumbs' => [
        [route('contracts.index'), 'العقود'],
        ['#', 'عقد رقم: ' . $contract->id],
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
                        @slot('title', 'كشف حساب العقد')
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
                        <table class="table table-bordered table-striped text-center">
                            <thead>
                                @if ($contract->getApplicationDays(false, true) > 0)
                                    <tr>
                                        <th colspan="4">
                                            <span>مدة التقديم {{ $contract->getApplicationDays(true) }}</span>
                                            <span>-</span>
                                            <span>المتبقي {{ $contract->getApplicationDays(true, true) }}</span>
                                        </th>
                                    </tr>
                                @elseif ($contract->getApplicationDays(false, true) < 0)
                                    <tr>
                                        <th colspan="4">
                                            <span>مدة التقديم {{ $contract->getApplicationDays(true) }}</span>
                                            <span>-</span>
                                            <span>{{ $contract->getApplicationDays(true, true) }}</span>
                                        </th>
                                    </tr>
                                @endif
                                <tr>
                                    <th>تاريخ العقد</th>
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
                                        قيمة العقد
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
                                                <input type="hidden" name="marketer_id" value="{{ $marketer->id ?? null }}">
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
                                {{--  <tr>
                                    <th>
                                        ملاحظات العقد
                                    </th>
                                    <td colspan="3">{{ $contract->details  }}</td>
                                </tr>  --}}
                                {{--
                                    <tr>
                                        <tr>
                                            <th>تاريخ العقد</th>
                                            <td>{{ $contract->created_at->format('Y-m-d') }}</td>
                                        </tr>
                                        <tr>
                                            <th>العميل</th>
                                            <td>{{ $contract->customer->name }}</td>
                                        </tr>
                                    </tr>
                                    <tr>
                                        <th>المكتب الخارجي</th>
                                        <td>{{ $contract->office->name ?? '' }}  {{ $contract->country->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>العامل \ العاملة</th>
                                        <td>{{ $contract->cv->name ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>رقم التأشيرة</th>
                                        <td>{{ $contract->visa }}</td>
                                    </tr>
                                    <tr>
                                        <th>المهنة</th>
                                        <td>{{ $contract->profession->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>قيمة العقد</th>
                                        <td>{{ $contract->amount }}</td>
                                    </tr>
                                    <tr>
                                        <th>المسوق</th>
                                        <td>{{ $contract->marketer->name ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <th>نسبة المسوق</th>
                                        <td>{{ $contract->marketing_ratio  }}</td>
                                    </tr>
                                    <tr>
                                        <th>جهة الوصول</th>
                                        <td>{{ $contract->destination  }}</td>
                                    </tr>
                                    <tr>
                                        <th>مطار الوصول</th>
                                        <td>{{ $contract->arrival_airport  }}</td>
                                    </tr>
                                    <tr>
                                        <th>تاريخ الوصول</th>
                                        <td>{{ $contract->date_arrival  }}</td>
                                    </tr>
                                    <tr>
                                        <th>خيارات</th>
                                        <td>
                                            <a class="btn btn-warning btn-sm contracts update"
                                                href="{{ route('contracts.edit', $contract->id) }}"><i class="fa fa-edit"></i> تعديل</a>
                                        </td>
                                    </tr> --}}
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
                            @slot('vouchers', $contract->all_vouchers)
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
                                                <th>رقم العقد</th>
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
