@extends('layouts.master', [
    'title' => 'العميل : ' . $customer->name,
    'modals' => ['customer', 'marketer','complaint'],
    'datatable' => true,
    'crumbs' => [
        [route('customers.index'), ' العملاء'],
        ['#', $customer->name],
    ]
])
@push('head')

@endpush


@section('content')
<!-- Content Header (Page header) -->
<!-- Main content -->
<section class="content">
    <div class="container">
        <div class="row">
            <div class="col">
                <table class="table table-bordered table-striped text-center">
                    <tr>
                        <th><i class="fa fa-user"></i> العميل </th>
                        <td>{{ $customer->name }}</td>
                    </tr>
                    <tr>
                        <th><i class="fa fa-map"></i> العنوان </th>
                        <td>{{ $customer->address }}</td>
                    </tr>
                    <tr>
                        <th><i class="fa fa-phone"></i> رقم الهاتف </th>
                        <td>{{ $customer->phones }}</td>
                    </tr>
                </table>
            </div>
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-bordered table-striped datatable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>العمال \ العاملة</th>
                                    <th>رقم الجواز</th>
                                    <th>النوع</th>
                                    <th>المكتب الخارجي</th>
                                    <th>الحالة</th>
                                    <th>الاجراء الحالى</th>
                                    <th>تاريخ الانشاء</th>
                                    <th>خيارات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cvs->whereIn('status', [Modules\ExternalOffice\Models\Cv::STATUS_ACCEPTED, '']) as $index=>$cv)
                                    <tr>
                                        <td>
                                            @if(!$cv->status == Modules\ExternalOffice\Models\Cv::STATUS_WAITING)
                                                <input type="radio"  name="done" onclick="getCv(this.value)" value="{{ $cv->id }}">
                                            @endif
                                        </td>
                                        <td>{{ $cv->name }}</td>
                                        <td>{{ $cv->passport }}</td>
                                        <td>{{ $cv->gender == 1 ? 'ذكر' : 'انثى' }}</td>
                                        <td>{{ $cv->office->name ?? '' }}</td>
                                        <td>
                                            @if (!$cv->pull)
                                                @if($cv->status == Modules\ExternalOffice\Models\Cv::STATUS_CONTRACTED)
                                                    <span class="text-success">
                                                        تم عمل عقد
                                                    </span>
                                                @endif
                                                @if($cv->status == Modules\ExternalOffice\Models\Cv::STATUS_ACCEPTED)
                                                    <span class="text-success">
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
                                        </td>
                                        <td>{{ $cv->procedure }}</td>
                                        <td>{{ $cv->created_at->format('Y-m-d') }}</td>
                                        <td>
                                            @permission('cv-read')
                                            <a class="btn btn-info btn-sm" href="{{ route('servicescvs.show', $cv->id) }}"><i class="fa fa-eye"></i> عرض</a>
                                            @endpermission

                                            @permission('cv-read')
                                            <a class="btn btn-warning btn-sm cvs update" href="{{ route('servicescvs.edit', $cv->id) }}"><i class="fa fa-edit"></i> تعديل </a>
                                            @endpermission

                                            @permission('cv-read')
                                            @if($cv->status == Modules\ExternalOffice\Models\Cv::STATUS_WAITING)
                                            <form class="d-inline-block" action="{{ route('servicescvs.update', $cv->id) }}?type=accept" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <button class="btn btn-success btn-sm" type="submit"><i class="fa fa-check"></i> موافقة</button>
                                            </form>
                                            @endif
                                            @endpermission
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <form action="{{ route('contracts.store') }}" method="post">
                <div class="row">
                    <div class="col">
                        @component('components.widget')
                            @slot('title')
                                <i class="fas fa-list"></i>
                                <span>اضافة عقد</span>
                            @endslot
                            @slot('body')
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="country_id">الدولة</label>
                                            <div class="input-group">
                                                
                                                <select id="country" required id="country" class="custom-select" name="country_id">
                                                    <option selected disabled value="0">الدولة</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
            
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="profession_id">المهنة</label>
                                            <div class="input-group">
                                                <select  required  id="profession" class="custom-select" name="profession_id">
                                                    <option selected disabled value="0">المهنة</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
            
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="cv_id">CV</label>
                                            <select required id="cvs" class="custom-select select2" name="cv_id">
                                            </select>
                                        </div>
                                    </div>
            
                                    <div class="col-md-6">
                                        <label for="customer_id">العميل</label>
                                        <div class="input-group">
                                            <select required class="select2 custom-select" name="customer_id">
                                                <option selected  value="{{ $customer->id }}">{{ $customer->name }}</option>
                                            </select>
                                        </div>
                                    </div>
            
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="visa">رقم التاشيرة</label>
                                            <input required type="number" class="form-control" name="visa" placeholder="رقم التاشيرة">
                                        </div>
                                    </div>
            
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="amount">قيمة العقد</label>
                                            <input required type="number" step="0.01" class="form-control" name="amount" placeholder="Amount" min="0" value="0">
                                        </div>
                                    </div>
                                    
                                    
            
                                    <div class="col-md-6">
                                        <label for="marketer_id">المسوق</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <button class="btn btn-success btn-sm" type="button" data-toggle="modal" data-target="#marketerModal"><i class="fa fa-plus"></i></button>
                                            </div>
                                            <select class="custom-select editable" name="marketer_id">
                                                {{-- <option selected disabled value="">المسوق</option> --}}
                                                @foreach($marketers as $marketer)
                                                    <option value="{{ $marketer->id }}">{{ $marketer->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
            
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="marketing_ratio">نسبة المسوق</label>
                                            <input  type="number" class="form-control" name="marketing_ratio" placeholder="نسبة المسوق">
                                        </div>
                                    </div>
    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="destination">جهة الوصول</label>
                                            <input required  type="text" class="form-control" name="destination" placeholder="جهة الوصول ">
                                        </div>
                                    </div>
    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="arrival_airport">مطار الوصول</label>
                                            <input   type="text" class="form-control" name="arrival_airport" placeholder="مطار الوصول ">
                                        </div>
                                    </div>
    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="date_arrival">تاريخ الوصول</label>
                                            <input  type="date" class="form-control" name="date_arrival" placeholder="تاريخ الوصول ">
                                        </div>
                                    </div>
                                    
            
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="details">التفاصيل اخري</label>
                                            <textarea  class="form-control" name="details" id="details" rows="3" placeholder="التفاصيل"></textarea>
                                        </div>
                                    </div>
    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="start_date">بداية التقديم</label>
                                            <input required  type="date" class="form-control" name="start_date" >
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="ex_date">مدة التقديم</label>
                                            <input required  type="number" class="form-control" name="ex_date" placeholder="مدة التقديم">
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> اكمال العملية</button>
                            </form>
                        @endslot
                        @endcomponent
                    </div>
                    <div class="col">
                        @component('components.widget')
                            @slot('noTitle', true)
                            @slot('title')
                                <i class="fas fa-paperclip"></i>
                                <span>المرفقات</span>
                            @endslot
                            @slot('body')
                                @component('components.attachments-uploader')
                                @endcomponent
                            @endslot
                            @slot('footer')
                                <button type="submit" class="btn btn-primary">اكمال العملية</button>
                            @endslot
                        @endcomponent
                    </div>
                </div>
        </section>
    </div>
</section>
@endsection



@push('foot')
<script>
    function getCv(cv) {
        console.log(cv);
        $.ajax({
            url: "{{ url('services/get/cv') }}" + '/' + cv
        }).done(function(data) {
            $('#cvs').html('')
            $('#country').html('')
            $('#profession').html('')
            $.each(data, function( index, value ) {
                if(index == 'cv') {
                    $('#cvs').append(`<option value="${value.id}">${value.name}  | ${value.passport}</option>`)
                }
                if(index == 'country') {
                    $('#country').append(`<option value="${value.id}">${value.name}</option>`)
                }
                if(index == 'profession') {
                    $('#profession').append(`<option value="${value.id}">${value.name}</option>`)
                }
            });

            // data.forEach(r => {
            //     console.log(r)
            //     //$('#cvs').append(`<option value="${cv.id}">${cv.name}  | ${cv.passport}</option>`)
            // });
        });
    }
</script>
@endpush