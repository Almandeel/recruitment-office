@extends('layouts.master', [
    'title' => 'عرض cv ' . $cv->name,
    'datatable' => true, 
    'confirm_status' => true, 
    'modals' => ['attachment', 'customer'],
    'crumbs' => [
        [route('servicescvs.index'), 'cvs'],
        ['#', $cv->name ],
    ]
])


@section('content')
<section class="content">
        <style>
            .content-header {
    padding: 0px 0.5rem;
}
.card-body {
 
    padding: 7px;
 
}       
    </style>
    
    
    
    
    <section class="content">
        @component('components.tabs')
            @slot('items')
                @component('components.tab-item')
                    @slot('active', true)
                    @slot('id', 'details')
                    @slot('title', 'بيانات العامل')
                @endcomponent
                @component('components.tab-item')
                    @slot('id', 'attachments')
                    @slot('title', 'المرفقات')
                @endcomponent
                @component('components.tab-item')
                    @if (session('active_tab') == 'pulls')
                        @slot('active', true)
                    @endif
                    @slot('id', 'pulls')
                    @slot('title', 'طلب إرجاع عامل \ عاملة')
                @endcomponent
                @component('components.tab-item')
                    @if (session('active_tab') == 'returns')
                        @slot('active', true)
                    @endif
                    @slot('id', 'returns')
                    @slot('title', 'طلب سحب عامل \ عاملة')
                @endcomponent
            @endslot
            @slot('contents')
                @component('components.tab-content')
                    @slot('active', true)
                    @slot('id', 'details')
                    @slot('content')
                    <div class="card">
                        <div class="card-header">
                            <div class="col-md-12 row">
                                <div class="col-md-6">
                                    <h5 style="font-weight: bold; color: #032cc3;">  المعرف :{{ $cv->reference_number }}</h5>
                                </div>
                                <div class="col-md-6">
                                    <h3 class="card-title"  style="float: left; ">تاريخ الانشاء:
                                        {{ $cv->created_at->format('Y-m-d') }}
                                    </h3>
                                    <h6  class="gg" style="float: left; padding-left: 34px;">الحالة   :
                                        <span class="badge badge-info">
                                            @if (! $cv->pull)
                                                <p style="margin-bottom: 2px;" class="{{ $cv->accepted ? '' : 'text-warnying' }}">
                                                    {{-- {{ $cv->accepted ? 'تمت الموافقة' : 'في الانتظار' }} --}}
                                                    @if (!$cv->pull)
                                                        @if($cv->status == Modules\ExternalOffice\Models\Cv::STATUS_CONTRACTED)
                                                            <span class="">
                                                                تم عمل عقد
                                                            </span>
                                                        @endif
                                                        @if($cv->status == Modules\ExternalOffice\Models\Cv::STATUS_ACCEPTED)
                                                            <span class="">
                                                                تمت الموافقة
                                                            </span>
                                                        @endif
                                                        @if($cv->status == Modules\ExternalOffice\Models\Cv::STATUS_WAITING)
                                                            <span class="text-warning">
                                                                في الانتظار
                                                            </span>
                                                        @endif
                                                    @elseif ($cv->status == Modules\ExternalOffice\Models\Cv::STATUS_PULLED)
                                                        <p class="text-info">تم تقديم طلب سحب</p>
                                                    @elseif ($cv->status == Modules\ExternalOffice\Models\Cv::STATUS_PULLED)
                                                        <p class="text-danger">تم السحب</p>
                                                    @endif
                                                </p>
                                            @elseif ($cv->pull && ! $cv->pull->confirmed)
                                                <p >تم تقديم طلب إرجاع</p>
                                            @elseif ($cv->pull && $cv->pull->confirmed)
                                                <p class="text-muted">تم الإرجاع</p>
                                            @endif
                                        </span>
                                    </h6>
                                </div> 
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 row">
                                    <div class="col-md-6">
                                        <h5 style="font-weight: bold;">البيانات الاساسية</h5>
                                    </div>
                                    <div class="col-md-9 row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="name">العامل \ العاملة</label>
                                                <h3>{{ $cv->name }}</h3>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <h5>المهنة : {{ $cv->profession->name }}</h5>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <h5>الديانه : {{ $cv->religion }}</h5>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <h5>النوع : {{ $cv->gender == 1 ? 'ذكر' : 'أنثى' }}</h5>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <h5>الدوله : {{ $cv->country->name ?? '' }}</h5>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <h5>مكان الميلاد : {{ $cv->birthplace ?? '' }}</h5>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <h5>تاريخ الميلاد : {{ $cv->birth_date ?? '' }}</h5>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <h5>الحالة الاجتماعية : {{ $cv->marital_status }}</h5>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <h5>عدد الاطفال : {{ $cv->children }}</h5>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <h5>الجوال : {{ $cv->phone }}</h5>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <h5>المستوي الدراسي : {{ $cv->qualification }}</h5>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <h5>اللغه الانجليزية : {{ $cv->english_speaking_level }}</h5>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <h5>الخبرة في الخارج : {{ $cv->experince }}</h5>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3 pic">
                                        <div class="left-block">
                                            <img src="{{ asset('cvs_data/' . $cv->photo) }}" width="100%" height="200" />
                                            <h6 style="text-align: center; font-weight: bold; padding-top: 6px;">الصورة الشخصية</h6>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <br />
                                        <h5 style="font-weight: bold;">بيانات اضافية</h5>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <h5>الوزن : {{ $cv->weight }}</h5>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <h5>الطول : {{ $cv->height }}</h5>
                                        </div>
                                    </div>
                                    <div class="col-md-8"></div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <h5>الخياطه : {{ $cv->sewing ? 'Yes' : 'No' }}</h5>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <h5>الديكور : {{ $cv->decor ? 'Yes' : 'No' }}</h5>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <h5>النتظيف : {{ $cv->cleaning ? 'Yes' : 'No' }}</h5>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <h5>الغسيل : {{ $cv->washing ? 'Yes' : 'No' }}</h5>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <h5>الطبخ : {{ $cv->cooking ? 'Yes' : 'No' }}</h5>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <h5>تربية الاطفال : {{ $cv->babysitting ? 'Yes' : 'No' }}</h5>
                                        </div>
                                    </div>
                                    <br />
                                    <br />

                                    <div class="col-md-12">
                                        <br />
                                        <br />
                                        <h5 style="font-weight: bold;">بيانات الجواز</h5>
                                    </div>

                                    <div class="col-md-12 row">
                                        <div class="col-md-9 row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <h5>رقم الجواز :{{ $cv->passport }}</h5>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <h5>مكان الاصدار : {{ $cv->passport_place_of_issue }}</h5>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <h5 style="margin-top: -59px;">تاريخ الاصدار : {{ $cv->passport_issuing_date }}</h5>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <h5 style="margin-top: -59px;">تاريخ الانتهاء : {{ $cv->passport_expiration_date }}</h5>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3 pic">
                                            <div class="left-block">
                                                <img style="margin-top: -59px;" src="{{ asset('cvs_data/' . $cv->passport_photo) }}" width="100%" height="200" />
                                                <h6 style="text-align: center; font-weight: bold; padding-top: 6px;">صورة الجواز</h6>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <h5 style="font-weight: bold;">
                                            تفاصيل العقد
                                        </h5>
                                    </div>

                                    <div class="col-md-12 row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <h5>القيمة : {{ number_format($cv->amount, 2) }}</h5>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <h5>مدة العقد : {{ $cv->contract_period }}</h5>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <h5>الراتب : {{ $cv->contract_salary }}</h5>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <label for="procedure">نبذه مختصرة وملاحظات </label>

                                            <br />
                                            <span>
                                                {{ $cv->bio ?? '' }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <h5 style="font-weight: bold;">الاجراء :</h5>

                                            <h5>
                                                {{ $cv->procedure ?? '' }}
                                            </h5>
                                        </div>
                                    </div>
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
                            @slot('attachable', $cv)
                            @slot('canAdd', true)
                            @slot('view', 'timeline')
                        @endcomponent
                    @endslot
                @endcomponent
                @component('components.tab-content')
                    @if (session('active_tab') == 'pulls')
                        @slot('active', true)
                    @endif
                    @slot('id', 'pulls')
                    @slot('content')
                        @if ($cv->pull)
                            <form action="{{ route('cvs.pulls.update', $cv->pull) }}" method="post">
                                @csrf
                                @method('PUT')
                                <table class="table table-bordered table-striped text-center">
                                    <tbody>
                                        <tr>
                                            <th>السبب</th>
                                            <td>{{ $cv->pull->cause }}</td>
                                        </tr>
                                        <tr>
                                            <th>قيمة الأضرار</th>
                                            @if ($cv->pull->confirmed) <td>{{ $cv->pull->damages }}</td>
                                            @else <td><input class="form-control" type="number" name="damages" value="{{ $cv->pull->damages }}"></td> @endif
                                        </tr>
                                        <tr>
                                            <th>الملاحظات</th>
                                            <td>{{ $cv->pull->notes }}</td>
                                        </tr>
                                        <tr>
                                            <th>حالة الطلب</th>
                                            <td>{{ $cv->pull->confirmed ? 'تمت الموافقة' : 'بالإنتظار' }}</td>
                                        </tr>
                                        <tr>
                                            <th>الموظف </th>
                                            <td>{{ $cv->pull->user->name }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                                @if (!$cv->pull->confirmed)
                                    <button class="btn btn-success" type="submit">الموافقة على الطلب</button>
                                @endif
                            </form>
                        @else
                        <p>ﻻ توجد طلبات إرجاع</p>

                        @endif
                    @endslot
                @endcomponent
                @component('components.tab-content')
                    @if (session('active_tab') == 'returns')
                        @slot('active', true)
                    @endif
                    @slot('id', 'returns')
                    @slot('content')
                        @if ($cv->return)
                            <form action="{{ route('returns.store', $cv->return) }}" method="post">
                                @csrf
                                @method('PUT')
                                <table class="table table-bordered table-striped text-center">
                                    <tbody>
                                        <tr>
                                        </tr>
                                    </tbody>
                                </table>
                            </form>
                        @component('components.attachments-viewer')
                            @slot('attachable', $cv->returns)
                            @slot('canAdd', true)
                            @slot('view', 'timeline')
                        @endcomponent

                        @else
                        <h3>إنشاء طلب سحب</h3>

                        <form action="{{ route('returns.store', $cv) }}" method="post">
                            @csrf
                            <div class="form-group">
                                <label for="withAdvance">إنشاء سند بالتكلفة؟</label>
                                <input type="checkbox" name="withAdvance" id="withAdvance" value="true">
                            </div>
                            <div class="col">
                                @component('accounting::components.widget')
                                    @slot('noTitle', true)
                                    @slot('noPadding', true)
                                    @slot('title')
                                        <i class="fas fa-paperclip"></i>
                                        <span>@lang('accounting::global.attachments')</span>
                                    @endslot
                                    @slot('body')
                                        @component('accounting::components.attachments-uploader')@endcomponent
                                    @endslot
                                @endcomponent
                            </div>
                            <button type="submit" class="btn btn-primary">إنشاء طلب سحب</button>
                        </form>
                        @endif
                    @endslot
                @endcomponent
            @endslot
        @endcomponent
</section>
@endsection
