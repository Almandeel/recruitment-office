@extends('layouts.master', [
    'datatable' => true, 
    'confirm_status' => true, 
    'modals' => ['marketer', 'marketer_credit'],
    'title' => 'المسوق : ' . $marketer->name,
])

@section('content')
    <section class="content">
        @component('components.tabs')
            @slot('items')
                @component('components.tab-item')
                    @slot('active', true)
                    @slot('id', 'details')
                    @slot('title', 'بيانات المسوق')
                @endcomponent
                @component('components.tab-item')
                    @slot('id', 'contracts')
                    @slot('title', 'العقود')
                @endcomponent
                @component('components.tab-item')
                    @slot('id', 'tafweeds')
                    @slot('title', 'الوكالات')
                @endcomponent
                @component('components.tab-item')
                    @slot('id', 'vouchers')
                    @slot('title', 'السندات')
                @endcomponent
            @endslot
            @slot('contents')
                @component('components.tab-content')
                    @slot('active', true)
                    @slot('id', 'details')
                    @slot('content')
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>الاسم</th>
                                    <td>{{ $marketer->name }}</td>
                                    <th>رقم الهاتف</th>
                                    <td>{{ $marketer->phone }}</td>
                                    <th>العقود</th>
                                    <td>{{ $marketer->contracts->count() }}</td>
                                </tr>
                                <tr>
                                    <th>مدين</th>
                                    <td>{{ number_format($marketer->debts(), 2) }}</td>
                                    <th>دائن</th>
                                    <td>{{ number_format($marketer->credits(), 2) }}</td>
                                    <th>الرصيد</th>
                                    <td>{{ $marketer->displayBalance() }}</td>
                                </tr>
                                <tr>
                                    <th colspan="2">الخيارات</th>
                                    <td colspan="4">
                                        @permission('marketers-update')
                                        <button class="btn btn-warning btn-sm marketers update"
                                            data-action="{{ route('servicesmarketers.update', $marketer->id) }}"
                                            data-name="{{ $marketer->name }}" data-phone="{{ $marketer->phone }}" data-toggle="modal"
                                            data-target="#marketerModal"><i class="fa fa-edit"></i> تعديل </button>
                                        @endpermission
                                        {{--  <button class="btn btn-primary btn-sm marketer-credit" data-marketer="{{ $marketer->id }}"
                                            data-max="{{ $marketer->debt }}" data-toggle="modal" data-target="#marketerCreditModal"><i
                                                class="fa fa-dollar"></i> اضافة دفعة </button>  --}}
                                    </td>
                                </tr>
                            </thead>
                        </table>
                    @endslot
                @endcomponent
                @component('components.tab-content')
                    @slot('id', 'contracts')
                    @slot('content')
                        <table id="datatable" class="datatable table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>العامل \ العاملة</th>
                                    <th>رقم التأشيرة</th>
                                    <th>المهنة</th>
                                    <th>الدولة</th>
                                    <th>قيمة العقد</th>
                                    <th>عمولة المسوق</th>
                                    <th>خيارات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($marketer->contracts as $index=>$contract)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $contract->cv->name }}</td>
                                    <td>{{ $contract->visa }}</td>
                                    <td>{{ $contract->profession->name }}</td>
                                    <td>{{ $contract->country->name }}</td>
                                    <td>{{ $contract->amount }}</td>
                                    <td>
                                        @php
                                            $voucher = $contract->marketer_voucher;
                                        @endphp
                                        @if ($voucher)
                                            {{ number_format($contract->marketing_ratio, 2) }}
                                        @else
                                            <form action="{{ route('vouchers.store') }}" method="post" class="form-inline">
                                                @csrf
                                                <input type="hidden" name="marketer_id" value="{{ $marketer->id }}">
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
                                    <td>
                                        <div class="btn-group">
                                            @permission('contracts-read')
                                                <a class="btn btn-info" href="{{route('contracts.show', $contract)}}"><i class="fa fa-eye"></i> عرض</a>
                                            @endpermission
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endslot
                @endcomponent
                @component('components.tab-content')
                    @slot('id', 'tafweeds')
                    @slot('content')
                        <table id="datatable" class="table datatable table-bordered table-striped">
                            <thead>
                                <tr role="row">
                                    <th>#</th>
                                    <th>اسم العميل   </th>
                                    <th>رقم التأشيرة</th>
                                    <th>رقم الهوية</th>
                                    <th>الدوله</th>
                                    <th>المكتب الخارجي</th>
                                    <th>العامل \ العاملة</th>
                                    <th>رقم الجواز</th>
                                    <th>عمولة المسوق</th>
                                    <th>خيارات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($marketer->tafweeds as $tafweed)
                                    <tr>
                                        <td> {{ $tafweed->id }}</td>
                                        <td>{{ $tafweed->customer->name ?? 'لا يوجد' }}</td>
                                        <td> {{ $tafweed->visa }}</td>
                                        <td> {{ $tafweed->identification_num }}</td>
                                        <td>{{ $tafweed->country->name ?? 'لا يوجد' }}</td>
                                        <td>  {{$tafweed->office}} </td>
                                        <td> {{ $tafweed->recruitment_cv_name }}</td>
                                        <td> {{ $tafweed->recruitment_cv_passport }}</td>
                                        <td>
                                            @php
                                                $voucher = $tafweed->marketer_voucher;
                                            @endphp
                                            @if ($voucher)
                                                {{ number_format($tafweed->comm, 2) }}
                                            @else
                                                <form action="{{ route('vouchers.store') }}" method="post" class="form-inline">
                                                    @csrf
                                                    <input type="hidden" name="marketer_id" value="{{ $marketer->id }}">
                                                    <input type="hidden" name="voucherable_id" value="{{ $tafweed->id }}">
                                                    <input type="hidden" name="voucherable_type" value="{{ get_class($tafweed) }}">
                                                    <input type="hidden" name="currency" value="ريال">
                                                    <input type="hidden" name="tafweed_id" value="{{ $tafweed->id }}">
                                                    <div class="input-group">
                                                        <input type="number" name="amount" class="form-control" value="{{ $tafweed->comm }}" min="1">
                                                        <div class="input-group-append">
                                                            <button class="btn btn-primary">
                                                                <span>إنشاء سند</span>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                @permission('delegations-read')
                                                    <a class="btn btn-info" href="{{route('tafweed.show',[$tafweed->id])}}"><i class="fa fa-eye"></i> عرض</a>
                                                @endpermission
                                            </div>
                                        </td>
                                    </tr>

                                    @endforeach
                            </tbody>
                        </table>
                    @endslot
                @endcomponent
                @component('components.tab-content')
                    @slot('id', 'vouchers')
                    @slot('content')
                        @component('accounting::components.vouchers')
                            @slot('voucherable', $marketer)
                            @slot('read_only', true)
                        @endcomponent
                    @endslot
                @endcomponent
            @endslot
        @endcomponent
    </section>
@endsection
