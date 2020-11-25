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
                        <div class="col-md-12 row">
                    
                            <div class="row">
                                <div class="col-md-3">
                                    <span> الدولة : @foreach($countries as $country)
                                        @if($country->id==$tafweed->country_id )
                                        {{$country->name}}
                                        @endif
                                        @endforeach </span>
                    
                                </div>
                    
                    
                                <div class="col-md-3">
                                    <span> اسم العميل : {{ $tafweed->customer->name ?? '' }} </span>
                    
                    
                                </div>
                                <div class="col-md-3">
                    
                                    <span> رقم التاشيرة : {{ $tafweed->visa }} </span>
                    
                    
                                </div>
                                <div class="col-md-3">
                                    <span> رقم الهوية : {{ $tafweed->identification_num }} </span>
                    
                    
                                </div>
                                <div class="col-md-3">
                                    <span> رقم الجوال : {{ $tafweed->phone }} </span>
                    
                    
                    
                                </div>
                                <div class="col-md-12">
                                    <span> العنوان : {{ $tafweed->addr }} </span>
                    
                                </div>
                    
                                <div class="col-md-3">
                                    <span> اسم العامل : {{ $tafweed->recruitment_cv_name }} </span>
                    
                    
                                </div>
                                <div class="col-md-3">
                                    <span> رقم الجواز : {{ $tafweed->recruitment_cv_passport }} </span>
                    
                    
                                </div>
                                <div class="col-md-6">
                                    <span> المكتب الخارجي : {{$tafweed->office}} </span>
                    
                    
                                </div>
                    
                                <div class="col-md-3">
                                    <span> قيمة العقد : {{ $tafweed->salary }} </span>
                    
                    
                                </div>
                                <div class="col-md-3">
                                    <span> المسوق : {{ $tafweed->marketer }} </span>
                    
                    
                                </div>
                                <div class="col-md-3">
                                    <span> نسبة المسوق : {{ $tafweed->comm }} </span>
                    
                    
                                </div>
                    
                    
                    
                                <div class="col-md-4">
                                    <span> رقم عقد مساند ادارة المكاتب : {{ $tafweed->contract_num }} </span>
                    
                    
                                </div>
                                <div class="col-md-3">
                                    <span> رقم تفويض انجاز : {{ $tafweed->injaz_num }} </span>
                    
                    
                                </div>
                                <div class="col-md-3">
                                    <span> تكلفة تفويض انجاز : {{ $tafweed->injaz_cost }} </span>
                    
                    
                                </div>
                                <div class="col-md-12">
                                    <span> ملاحظة </span>
                                    <br>
                                    <p>
                                        {{ $tafweed->note }}
                                    </p>
                    
                                </div>
                            </div>
                    
                    
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

            