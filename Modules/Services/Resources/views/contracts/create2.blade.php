@extends('layouts.master', [
    'title' => 'اضافة عقد',
    'modals' => ['customer', 'marketer'],
    'crumbs' => [
        [route('contracts.index'), 'العقود'],
        ['#', 'اضافة عقد'],
    ]
])

@section('content')
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
                                            <div class="input-group-prepend">
                                                <button class="btn btn-success btn-sm" type="button" data-toggle="modal" data-target="#countryModal"><i class="fa fa-plus"></i></button>
                                            </div>
                                            <select onchange="getCvs()" id="country" class="custom-select" name="country_id">
                                                <option selected disabled value="0">الدولة</option>
                                                @foreach($countries as $country)
                                                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
        
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="profession_id">المهنة</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <button class="btn btn-success btn-sm professions" type="button" data-toggle="modal" data-target="#professionModal"><i class="fa fa-plus"></i></button>
                                            </div>
                                            <select onchange="getCvs()" id="profession" class="custom-select" name="profession_id">
                                                <option selected disabled value="0">المهنة</option>
                                                @foreach($professions as $profession)
                                                    <option value="{{ $profession->id }}">{{ $profession->name }}</option>
                                                @endforeach
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
                                            <option selected disabled value="0">العميل</option>
                                            @foreach($customers as $customer)
                                                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                            @endforeach
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
                                        <input required type="number" class="form-control" name="amount" placeholder="Amount" min="0" value="0">
                                    </div>
                                </div>
                                
                                
        
                                <div class="col-md-6">
                                    <label for="marketer_id">المسوق</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <button class="btn btn-success btn-sm" type="button" data-toggle="modal" data-target="#marketerModal"><i class="fa fa-plus"></i></button>
                                        </div>
                                        <select class="custom-select" name="marketer_id">
                                            <option selected disabled value="">المسوق</option>
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
                                        <input  type="text" class="form-control" name="destination" placeholder="جهة الوصول ">
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
                                        <label for="arrival_airport">مطار الوصول</label>
                                        <input  type="text" class="form-control" name="arrival_airport" placeholder="مطار الوصول ">
                                    </div>
                                </div>
                                
        
                                {{--  <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="details">ملاحظات اخري</label>
                                        <textarea  class="form-control" name="details" id="details" rows="3" placeholder="التفاصيل"></textarea>
                                    </div>
                                </div>  --}}

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="start_date">بداية التقديم</label>
                                        <input  type="date" class="form-control" name="start_date" >
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="ex_date">مدة التقديم</label>
                                        <input  type="number" class="form-control" name="ex_date" placeholder="مدة التقديم">
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
@endsection


@push('foot')
<script>
    function getCvs() {
        var profession = $('#profession').val();
        var country = $('#country').val();
        $.ajax({
            url: "{{ url('services/get/cvs') }}" + '/' + country + '/' + profession,
        }).done(function(cvs) {
            $('#cvs').html('')
            cvs.forEach(cv => {
                $('#cvs').append(`<option value="${cv.id}">${cv.name}  | ${cv.passport}</option>`)
            });
        });
    }

    // $(function () {
    //     getCvs()
    // });
</script>
@endpush