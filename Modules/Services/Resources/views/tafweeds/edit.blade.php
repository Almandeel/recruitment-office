@extends('layouts.master', [
    'title' => 'تعديل  بيانات  : ' . $tafweed->id,
    'datatable' => true,
    'crumbs' => [
        [route('tafweed.index'), 'الوكالات'],
        [route('tafweed.show', $tafweed), 'عقد  التفويض: ' . $tafweed->id],
        ['#', 'تعديل'],
    ]
])

@section('content')


                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark"> تعديل  الوكالات </h1>
                        </div>

                    </div>

                </div>
            </div>
            <!-- /.content-header -->
            <section class="content">
                <div class="card card-primary card-outline card-outline-tabs">

                    <div class="card-body ">
                        <form method="POST" action="{{route('tafweed.update',[$tafweed->id])}}">
                         @csrf
                         @method('PUT')
                            <div class="card card-primary  card-outline  ">
                                <div class="card-extra clearfix">

                                </div>
                                <!-- /.card-extra -->
                                <div class="card-body ">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="country_id">الدولة</label>
                                                <select name="country_id" class="form-control option" style="padding: 0px;">
                                                <option value="{{ $tafweed->country_id }}"> @foreach($countries as $country)
                                                        @if($country->id==$tafweed->country_id )
                                                             {{$country->name}}
                                                        @endif
                                                            @endforeach</option>
                                                <option>  اختر  الدولة </option>
                                                           @foreach($countries as $country)
                                                <option value="{{ $country->id }}"> {{ $country->name }}</option>
                                                         @endforeach
                                            </select>
                                            </div>
                                        </div>


                                    </div>

                                <div class="row">

                                        <div class="col-md-12">
                                        <span>العملاء</span>
                                            <br>
                                        </div>

                                        <br><br>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="customer_id"> اسم العميل</label>
                                                 <select class="form-control select2 custom-select" name="customer_id" id="customers" style="height: 38px;">
                                               <option value="{{ $tafweed->customer_id }}">
                                                @foreach($customers as $customer)
                                                        @if($customer->id==$tafweed->customer_id )
                                                             {{$customer->name}}
                                                        @endif
                                                            @endforeach</option>
                                            <option value="create">إنشاء عميل       </option>
                                            @foreach($customers as $customer)
                                            <option value="{{ $customer->id }}" data-id="{{ $customer->id }}" data-name="{{ $customer->name }}" data-identification_num="{{ $customer->id_number }}" data-phone="{{ $customer->phones }}" data-addr="{{ $customer->address }}">{{ $customer->name }}</option>
                                            @endforeach
                                        </select>

                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="name"> الاسم </label>
                                                <input type="text" class="form-control" name="name" placeholder="الاسم   " >
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="addr"> العنوان </label>
                                                <input type="text" class="form-control" name="addr" placeholder="العنوان   "  value="{{$tafweed->addr}}">
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="identification_num"> رقم الهوية </label>

                                                <input type="text" style="border-radius: 0;" class="form-control" name="identification_num" placeholder="رقم الهوية" value="{{$tafweed->identification_num}}">
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="phone"> رقم الجوال </label>

                                                <input type="text" style="border-radius: 0;" class="form-control" name="phone" placeholder="رقم الجوال" value="{{$tafweed->phone}}">
                                            </div>
                                        </div>

                                        <div class="col">
                                            <div class="form-group">
                                                <label for="visa"> رقم التاشيرة </label>

                                                <input type="text" style="border-radius: 0;" class="form-control" name="visa" placeholder="التأشيرة" value="{{$tafweed->visa}}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="salary">قيمة العقد</label>
                                                <input  type="number" class="form-control" name="salary" placeholder="Amount" value="{{$tafweed->salary}}">
                                            </div>
                                        </div>

                                        <div class="col">
                                            <div class="form-group">
                                                <label for="comm">نسبة المسوق</label>
                                                <input type="number" class="form-control" name="comm" placeholder="نسبة المسوق" value="{{$tafweed->comm}}">
                                            </div>
                                        </div>
                                        <div class="col">
                                            <label for="marketer">المسوق</label>
                                                <select name="marketer" class="form-control option" style="padding-top:0px;">
                                                <option value="{{$tafweed->marketer}}"> {{$tafweed->marketer}} </option>
                                                <option>    المسوق     </option>
                                                         @foreach($marketers as $marketer)
                                                <option value="{{$marketer->name}}"> {{$marketer->name}}</option>
                                                         @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="recruitment_cv_name"> اسم العامل</label>
                                                <input type="text" class="form-control" name="recruitment_cv_name" placeholder="اسم العامل  " value="{{$tafweed->recruitment_cv_name}}">
                                            </div>
                                        </div>

                                        <div class="col">
                                            <div class="form-group">
                                                <label for="recruitment_cv_passport"> رقم الجواز</label>
                                                <input type="text" class="form-control" name="recruitment_cv_passport" placeholder="رقم الجواز  " value="{{$tafweed->recruitment_cv_passport}}">
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="office">المكتب الخارجي </label>
                                                <input type="text" class="form-control" name="office" placeholder="لمكتب الخارجي " value="{{$tafweed->office}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">

                                    </div>

                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="contract_num"> رقم عقد مساند ادارة المكاتب </label>
                                                <input type="number" class="form-control" name="contract_num" placeholder="" value="{{$tafweed->contract_num}}">
                                            </div>
                                        </div>

                                        <div class="col">
                                            <div class="form-group">
                                                <label for="injaz_num"> رقم تفويض انجاز</label>
                                                <input type="number" class="form-control" name="injaz_num" placeholder="" value="{{$tafweed->injaz_num}}">
                                            </div>
                                        </div>

                                        <div class="col">
                                            <div class="form-group">
                                                <label for="injaz_cost"> تكلفة تفويض انجاز</label>
                                                <input type="number" class="form-control" name="injaz_cost" placeholder=" تكلفة تفويض انجاز " value="{{$tafweed->injaz_cost}}">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="notes">ملاحظات </label>
                                                <br>
                                                <textarea name="notes" rows="4" cols="100">
                                                    {{$tafweed->notes}}

                                                </textarea>
                                            </div>
                                        </div>

                @component('components.attachments-uploader')
                @endcomponent

                                    </div>
                                </div>
                                <!-- /.card-body -->
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> اكمال العملية</button>
                                </div>
                                <!-- /.card-footer -->
                            </div>
                        </form>


                    </div>



                </div>

            </section>
            <!-- /.control-sidebar -->

@endsection


@push('foot')
    <script>
        $(function(){
            $(document).on('change, keyup', 'select#country_id, select#office_id, select#profession_id, #age_min, #age_max', function(){
                filter();
            })
            $(document).on('click', '.btn-filter', function(){
                filter();
            })
            $(document).on('change', 'select#customers', function(){
                selectCustomer()
            })

        })
        function selectCustomer(){
            let selected_option = $('select#customers option:selected');
            let field_customer_name = $('input[name=name]');
            let field_customer_id_number = $('input[name=identification_num]');
            let field_customer_address = $('input[name=addr]');
            let field_customer_phones = $('input[name=phone]');
            let field_customer_id = $('input[name=id]');
            if(selected_option.val() == 'create'){
                field_customer_name.val('')
                field_customer_id_number.val('')
                field_customer_address.val('')
                field_customer_phones.val('')
                field_customer_id.removeAttr('value')
                field_customer_name.removeAttr('disabled')
                field_customer_id_number.removeAttr('disabled')
                field_customer_address.removeAttr('disabled')
                field_customer_phones.removeAttr('disabled')
                field_customer_name.attr('required', true)
                field_customer_id.attr('disabled', true)
            }else{
                field_customer_name.val(selected_option.data('name'))
                field_customer_id.val(selected_option.data('id'))
                field_customer_id.attr('value', selected_option.data('id'))
                field_customer_id_number.val(selected_option.data('identification_num'))
                field_customer_address.val(selected_option.data('addr'))
                field_customer_phones.val(selected_option.data('phone'))
                field_customer_name.attr('disabled', true)
                field_customer_id_number.attr('disabled', true)
                field_customer_address.attr('disabled', true)
                field_customer_phones.attr('disabled', true)
                field_customer_id.removeAttr('disabled')
                field_customer_name.removeAttr('required')
                $('input.parsley-error[name="customer_name"]')
                    .siblings('ul.parsley-errors-list').remove()
                $('input[name="customer_name"]').removeClass('parsley-error')
            }
        }
    </script>

    <style type="text/css">
        .select2-container--default.select2-container--focus .select2-selection--multiple, .select2-container--default.select2-container--focus .select2-selection--single {
        border-color: #80bdff;
        height: 38px;
        }
        .select2-container--default .select2-selection--single {
            background-color: #fff;
            border: 1px solid #aaa;
            border-radius: 4px;
            height: 38px;
        }
    </style>
@endpush
