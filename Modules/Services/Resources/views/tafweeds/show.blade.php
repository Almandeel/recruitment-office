@extends('layouts.master',
[
    'datatable' => true,  
    'title' => '   تفويض رقم: ' . $tafweed->id, 
    'modals' => [ 'attachment'],
    'crumbs' => [
        [route('tafweed.index'), 'الوكلات'],
        ['#', 'تفويض رقم: ' . $tafweed->id],
    ],
])
@section('content')
    @component('components.tabs')
        @slot('items')
            @component('components.tab-item')
                @if (session('active_tab') != 'vouchers')
                    @slot('active', true)
                @endif
                @slot('id', 'details')
                @slot('title', 'بيانات الوكالة')
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
                            <strong>الدولة: </strong><span>{{ $tafweed->country->name ?? '' }}</span>
            
                        </div>
                        <div class="col">
                            <strong>المكتب الخارجي: </strong><span>{{$tafweed->office}} </span>
                        </div>
                        <div class="col">
                            <strong>اسم العميل: </strong><span>{{ $tafweed->customer->name ?? '' }} </span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <strong>رقم التاشيرة: </strong><span>{{ $tafweed->visa }} </span>
                        </div>
                        <div class="col">
                            <strong>رقم الهوية: </strong><span>{{ $tafweed->identification_num }} </span>
                        </div>
                        <div class="col">
                            <strong>رقم الجوال: </strong><span>{{ $tafweed->phone }} </span>
                        </div>
                        {{--  <div class="col">
                            <strong>العنوان: </strong><span>{{ $tafweed->addr }} </span>
                        </div>  --}}
                    </div>
                    <div class="row">
                        <div class="col">
                            <strong>اسم العامل: </strong><span>{{ $tafweed->recruitment_cv_name }} </span>
                        </div>
                        <div class="col">
                            <strong>رقم الجواز: </strong><span>{{ $tafweed->recruitment_cv_passport }} </span>
                        </div>
                        <div class="col">
                            <strong>قيمة العقد: </strong><span>{{ $tafweed->salary }} </span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <strong>المسوق: </strong><span>{{ $tafweed->marketer->name ?? '' }} </span>
                        </div>
                        <div class="col">
                            <strong>نسبة المسوق: </strong>
                            <span>
                                @php
                                    $marketer = $tafweed->marketer;
                                    $voucher = $tafweed->marketer_voucher;
                                @endphp
                                @if ($voucher)
                                    {{ number_format($tafweed->comm, 2) }}
                                @else
                                    <form action="{{ route('vouchers.store') }}" method="post" class="form-inline">
                                        @csrf
                                        <input type="hidden" name="marketer_id" value="{{ $marketer->id ?? null }}">
                                        <input type="hidden" name="voucherable_id" value="{{ $tafweed->id }}">
                                        <input type="hidden" name="voucherable_type" value="{{ get_class($tafweed) }}">
                                        <input type="hidden" name="currency" value="ريال">
                                        <input type="hidden" name="contract_id" value="{{ $tafweed->id }}">
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
                            </span>
            
                        </div>
                        <div class="col">
                            <strong>رقم عقد مساند ادارة المكاتب: </strong><span>{{ $tafweed->contract_num }} </span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <strong>رقم تفويض انجاز: </strong><span>{{ $tafweed->injaz_num }} </span>
                        </div>
                        <div class="col">
                            <strong>تكلفة تفويض انجاز: </strong><span>{{ $tafweed->injaz_cost }} </span>
                        </div>
                        <div class="col">
                            <strong>ملاحظة: </strong><span>{{ $tafweed->note }}</span>
                        </div>
                    </div>
                @endslot
            @endcomponent
            @component('components.tab-content')
                @slot('id', 'attachments')
                @slot('content')
                    @component('components.attachments-viewer')
                        @slot('attachable', $tafweed)
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
                        @slot('voucherable', $tafweed)
                    @endcomponent
                @endslot
            @endcomponent
        @endslot
        @endcomponent
@endsection

            