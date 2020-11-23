@extends('layouts.master', ['datatable' => true, 'modals' => ['country', 'marketer']])

@section('content')
    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title text-right">تعديل عقد</h3>
            </div>

            <div class="card-body">

                <form action="{{ route('contracts.update', $contract) }}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="row">

                        <div class="col-md-4">
                            <label for="country_id">الدولة</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <button class="btn btn-success btn-sm" type="button" data-toggle="modal" data-target="#countryModal"><i class="fa fa-plus"></i></button>
                                </div>
                                <select id="country" required onchange="getCvs()" class="custom-select" name="country_id">
                                    <option selected disabled value="">الدولة </option>
                                    @foreach($countries as $country)
                                        <option @if($contract->country->id == $country->id) selected @endif value="{{ $country->id }}">
                                            {{ $country->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>


                        <div class="col-md-4">
                            <label for="profession_id">المهنة</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <button class="btn btn-success btn-sm" type="button" data-toggle="modal" data-target="#countryModal"><i class="fa fa-plus"></i></button>
                                </div>
                                <select id="profession" required onchange="getCvs()" class="custom-select" name="profession_id">
                                    <option selected disabled value="">المهنة</option>
                                    @foreach($professions as $profession)
                                        <option @if($contract->profession->id == $profession->id) selected @endif value="{{ $profession->id }}">
                                            {{ $profession->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="cv_id">CV</label>
                                <select required id="cvs" class="custom-select select2" name="cv_id">
                                    <option value="{{ $contract->cv->id }}">{{ $contract->cv->name}} | {{ $contract->cv->passport }} </option>
                                    
                                </select>
                            </div>
                        </div>
        
                        <div class="col-md-6">
                            <label for="customer_id">العميل</label>
                            <div class="input-group">
                                <select required class="select2 custom-select" name="customer_id">
                                    <option selected disabled value="0">العميل</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}" {{ $contract->customer()->id == $customer->id ? 'selected' : '' }}>{{ $customer->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="visa">رقم التأشيرة</label>
                                <input type="text" class="form-control" name="visa" value="{{ $contract->visa }}">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="amount">قيمة العقد</label>
                                <input type="number" class="form-control" name="amount" value="{{ $contract->amount }}">
                            </div>
                        </div>

                        

                        <div class="col-md-4">
                            <label for="marketer_id">المسوق</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <button class="btn btn-success btn-sm" type="button" data-toggle="modal" data-target="#marketerModal"><i class="fa fa-plus"></i></button>
                                </div>
                                <select class="custom-select" name="marketer_id">
                                    <option selected disabled value="">المسوق</option>
                                    @foreach($marketers as $marketer)
                                        <option @if($contract->marketer_id == $marketer->id) selected @endif value="{{ $marketer->id }}">
                                            {{ $marketer->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="marketing_ratio">نسبة المسوق</label>
                                <input type="number" class="form-control" name="marketing_ratio" value="{{ $contract->marketing_ratio }}">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="destination">جهة الوصول</label>
                                <input  type="text" class="form-control" name="destination" placeholder="جهة الوصول " value="{{ $contract->destination }}">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="arrival_airport">مطار الوصول</label>
                                <input  type="text" class="form-control" name="arrival_airport" placeholder="مطار الوصول " value="{{ $contract->arrival_airport }}">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="date_arrival">تاريخ الوصول</label>
                                <input  type="date" class="form-control" name="date_arrival" placeholder="تاريخ الوصول " value="{{ $contract->date_arrival }}">
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="details">التفاصيل</label>
                                <textarea class="form-control" name="details" id="details" rows="3">{{ $contract->details }}</textarea>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="start_date">بداية التقديم</label>
                                <input  type="date" value="{{ $contract->start_date }}" class="form-control" name="start_date" >
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="ex_date">مدة التقديم</label>
                                <input  type="number" value="{{ $contract->ex_date }}" class="form-control" name="ex_date" placeholder="مدة التقديم">
                            </div>
                        </div>
                    </div>
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> حفظ التعديلات</button>
                </form>
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
            cvs.forEach(cv => {
                //$('#cvs').html('')
                $('#cvs').append(`<option value="${cv.id}">${cv.name}  | ${cv.passport}</option>`)
            });
        });
    }

    $(function () {
        getCvs()
    });
</script>
@endpush