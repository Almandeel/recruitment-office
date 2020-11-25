@extends('layouts.master', [
    'title' => 'اضافة  تفويض',
    'datatable' => true, 
    'crumbs' => [
        [route('tafweed.index'), 'الوكالات'],
        ['#', 'اضافة  تفويض'],
    ]
]) 
@section('content')
 
   
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark">   </h1>
                        </div>

                    </div>

                </div> 
            </div> 
            <!-- /.content-header -->
            <section class="content">
                <div class="card card-primary card-outline card-outline-tabs">

                    <div class="card-body "> 
                        <form method="POST" action="{{route('tafweed.store')}}"  enctype="multipart/form-data">
                         @csrf
                         
                            <div class="card card-primary  card-outline  ">
                                <div class="card-extra clearfix">

                                </div>
                                <!-- /.card-extra -->
                                <div class="card-body ">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col"> 
                                                <label for="country_id">الدولة</label>
                                                <select name="country_id" class="form-control option" style="padding: 0px;">  
                                                    @foreach(Modules\ExternalOffice\Models\Country::all() as $country) 
                                                        <option value="{{ $country->id }}"> {{ $country->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col">
                                                <label for="customers">
                                                    <span>بيانات العميل</span>
                                                </label>
                                                <select class="form-control select2 custom-select" id="customers">
                                                    <option value="create">إنشاء عميل</option>
                                                    @foreach($customers as $customer)
                                                        <option value="{{ $customer->id }}" data-id="{{ $customer->id }}" data-name="{{ $customer->name }}" data-id-number="{{ $customer->id_number }}" data-phones="{{ $customer->phones }}" data-address="{{ $customer->address }}">{{ $customer->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                        <div class="form-group row">
                                            <div class="col">
                                                <label>الإسم</label>
                                                <input type="text" class="form-control" required name="customer_name" required placeholder="الإسم">
                                            </div>
                                            <div class="col">
                                                <label>رقم الهوية</label>
                                                <input type="text" class="form-control" required name="customer_id_number" placeholder="رقم الهوية">
                                                <input type="hidden" required name="customer_id">
                                            </div>
                                            <div class="col">
                                                <label>رقم التأشيرة</label>
                                                <input type="text" class="form-control" required name="visa" placeholder="رقم التأشيرة">
                                                <input type="hidden" required name="customer_id">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col">
                                                <label>رقم الهاتف</label>
                                                <input type="number" class="form-control" required name="customer_phones" placeholder="رقم الهاتف">
                                            </div>
                                            <div class="col">
                                                <label>العنوان</label>
                                                <input type="text" class="form-control" required name="customer_address" placeholder="العنوان">
                                            </div>
                                        </div>
                                    {{--  </div>  --}}

                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="salary">قيمة العقد</label>
                                                <input  type="number" class="form-control" required name="salary" placeholder="Amount" >
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="comm">نسبة المسوق</label>
                                                <input type="number" class="form-control" required name="comm" placeholder="نسبة المسوق">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="marketer_id">المسوق</label>
                                            <select id="marketer_id" required name="marketer_id" class="form-control option editable" style="padding-top:0px;"> 
                                                @foreach($marketers as $marketer) 
                                                    <option value="{{ $marketer->name }}"> {{ $marketer->name }}</option>
                                                @endforeach
                                            </select>  
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="recruitment_cv_name"> اسم العامل</label> 
                                                <input type="text" class="form-control" required name="recruitment_cv_name" placeholder="اسم العامل  ">
                                            </div>
                                        </div>

                                        <div class="col">
                                            <div class="form-group">
                                                <label for="recruitment_cv_passport"> رقم الجواز</label>
                                                <input type="text" class="form-control" required name="recruitment_cv_passport" placeholder="رقم الجواز  ">
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="office">المكتب الخارجي </label> 
                                                <input type="text" class="form-control" required name="office"> 
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">

                                    </div>

                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="contract_num"> رقم عقد مساند ادارة المكاتب </label>
                                                <input type="number" class="form-control" required name="contract_num" placeholder="   ">
                                            </div>
                                        </div>

                                        <div class="col">
                                            <div class="form-group">
                                                <label for="injaz_num"> رقم تفويض انجاز</label>
                                                <input type="number" class="form-control" required name="injaz_num" placeholder="   ">
                                            </div>
                                        </div>

                                        <div class="col">
                                            <div class="form-group">
                                                <label for="injaz_cost"> تكلفة تفويض انجاز</label>
                                                <input type="number" class="form-control" required name="injaz_cost" placeholder=" تكلفة تفويض انجاز ">
                                            </div>
                                        </div>
                                        <div class="col col-md-12">
                                            <div class="form-group">
                                                <label for="notes">ملاحظات </label>
                                                <br>
                                                <textarea required name="notes" rows="4" class="form-control"></textarea>
                                            </div>
                                        </div>

                                    </div>
                                 
                                   @component('components.attachments-uploader')@endcomponent
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
            let field_customer_name = $('input[name=customer_name]');
            let field_customer_id_number = $('input[name=customer_id_number]');
            let field_customer_id = $('input[name=customer_id]');
            let field_customer_phones = $('input[name=customer_phones]');
            let field_customer_address = $('input[name=customer_address]');
            if(selected_option.val() == 'create'){
                field_customer_phones.val('')
                field_customer_address.val('')
                field_customer_name.val('')
                field_customer_id_number.val('')

                field_customer_id.removeAttr('value')
                field_customer_name.removeAttr('readonly')
                field_customer_id_number.removeAttr('readonly')
                field_customer_phones.removeAttr('readonly')
                field_customer_address.removeAttr('readonly')

                field_customer_name.attr('required', true)
                field_customer_id_number.attr('required', true)
                field_customer_phones.attr('required', true)
                field_customer_address.attr('required', true)
                field_customer_id.attr('readonly', true)
            }else{
                field_customer_name.val(selected_option.data('name'))
                field_customer_id.val(selected_option.data('id'))
                field_customer_phones.val(selected_option.data('phones'))
                field_customer_address.val(selected_option.data('address'))
                field_customer_id.attr('value', selected_option.data('id'))
                field_customer_id_number.val(selected_option.data('id-number'))

                field_customer_name.attr('readonly', true)
                field_customer_id_number.attr('readonly', true)
                field_customer_phones.attr('readonly', true)
                field_customer_address.attr('readonly', true)
                
                field_customer_id.removeAttr('readonly')
                field_customer_name.removeAttr('required')
                field_customer_id_number.removeAttr('required')
                field_customer_phones.removeAttr('required')
                field_customer_address.removeAttr('required')

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