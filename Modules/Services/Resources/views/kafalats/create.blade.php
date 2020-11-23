@extends('layouts.master', [
    'title' => 'اضافة   نقل كفالة',
    'datatable' => true, 
    'crumbs' => [
        [route('kafalat.index'), 'الكفالات'],
        ['#', 'اضافة   نقل كفالة'],
    ]
]) 
@section('content') 
   
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark">      نقل  كفالة  </h1>
                        </div>

                    </div>
 
            <!-- /.content-header -->
            <section class="content">
                <div class="card card-primary card-outline card-outline-tabs">

                    <div class="card-body ">
                        <form method="POST" action="{{route('kafalat.store')}}">
                         @csrf
                            <div class="card card-primary  card-outline  ">
                                <div class="card-extra clearfix">

                                </div>
                                <!-- /.card-extra -->
                                <div class="card-body ">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label> رقم العقد      </label>  
                                           <select class="form-control select2 custom-select" name="contract_id" id="contracts" style="height: 38px;">                                         
                                            @foreach($contracts as $contract)
                                            <option value="{{ $contract->id }}" data-contract_id="{{ $contract->id }}"
                                             data-customer_id="@foreach(App\Customer::all() as $customer)  @if($customer->id== $contract->customer_id )  {{ $customer->name }} @endif @endforeach" data-country_id="@foreach(App\Country::all() as $country)  @if($country->id== $contract->country_id) {{ $country->name }} @endif @endforeach"  data-profession_id="@foreach(App\Profession::all() as $profession) @if($profession->id== $contract->profession_id ) {{ $profession->name }} @endif  @endforeach">{{ $contract->id }}</option>
                                            @endforeach
                                        </select> 
                                            </div> 
                                        </div> 
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label> اسم العميل </label> 
                                                <input required=""  type="text" class="form-control" name="customer_id" > 

                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>  الدولة </label>
                                                <input required="" type="text" class="form-control" name="country_id" placeholder="الدولة" > 
                                            </div>
                                        </div> 
                                           <div class="col-md-2">
                                            <div class="form-group">
                                                <label>   المهنة  </label>
                                                <input required="" type="text" class="form-control" name="profession_id" placeholder="المهنة" >
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">

                                            <span>بيانات العميل الاول </span>  
                                    </div>

                                        <br>

                                    <div class="row"> 
 
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="customer_id"> اسم العميل الاول </label>  
                                                 
                                           <select class="form-control select2 custom-select" name="customer_id" id="customers2" style="height: 38px;">
                                            <option value="create"> قم باختيار   لاعميل لعرض البيانات   </option>
                                             @foreach($customers as $customer)
                                            <option value="{{ $customer->id }}" data-id="{{ $customer->id }}" data-fname="{{ $customer->name }}" data-fid_number="{{ $customer->id_number }}" data-fphone="{{ $customer->phones }}" data-faddr="{{ $customer->address }}">{{ $customer->name }}</option>
                                            @endforeach
                                            </select> 

                                            </div>
                                        </div>

                                     
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="addr"> العنوان </label>   
                                                <input type="text" class="form-control" name="faddr" value=""> 
 
                                            </div>
                                        </div>

                                     
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="id_number"> رقم الهوية </label> 
                                                <input type="text"  class="form-control" name="fid_number" placeholder="رقم الهوية"   > 
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group"> 
                                                <label for="phone"> رقم الجوال </label> 
                                                <input type="text"  class="form-control" name="fphone" placeholder="رقم الجوال"  > 
                                            </div>
                                        </div>

                                        
                                    </div> 

                                        <div class="row">
                                        <div class="col-md-12">
                                            بيانات العميل الثاني
                                        </div>
                                        </div>

                                        <div class="row">
                                        <div class="col-md-12">
                                         <div class="form-group">
                                                
                                           <label for="s_customer_id"> اسم العميل</label>
                                           <select class="form-control select2 custom-select" name="s_customer_id" id="customers" style="height: 38px;">
                                            <option value="create">إنشاء عميل</option>
                                             @foreach($customers as $customer)
                                            <option value="{{ $customer->id }}" data-id="{{ $customer->id }}" data-name="{{ $customer->name }}" data-id_number="{{ $customer->id_number }}" data-phone="{{ $customer->phones }}" data-addr="{{ $customer->address }}">{{ $customer->name }}</option>
                                            @endforeach
                                            </select> 

                                            </div>
                                        </div>
                                        </div> 

                                        
                                        <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="name"> الاسم </label>
                                                <input type="text" class="form-control" name="name" placeholder="الاسم   "> 
                                            </div>
                                        </div>

                                        
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="addr"> العنوان </label>
                                                <input type="text" class="form-control" name="addr" placeholder="العنوان   ">
                                            </div>
                                        </div>

                                        </div>


                                        <div class="row">                                     
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="id_number"> رقم الهوية </label>

                                                <input type="text" style="border-radius: 0;" class="form-control" name="id_number" placeholder="رقم الهوية">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="phone"> رقم الجوال </label>

                                                <input type="text" style="border-radius: 0;" class="form-control" name="phone" placeholder="رقم الجوال">
                                            </div>
                                        </div>

                                        
                                        
                                           <div class="col-md-3">
                                            <div class="form-group">
                                                <label>تاريخ  نقل الكفالة </label>
                                                <input required="" type="date" class="form-control" name="transfer_date" placeholder="date" min="0" value="0">
                                            </div>
                                        </div>
                                        
                                        
                                    
                                        
                                        
                                          <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="amount">قيمة نقل الكفالة</label>
                                                <input required="" type="number" class="form-control" name="amount" placeholder="Amount" min="0" value="0">
                                            </div>
                                        </div>
                                        </div>

                                  <div class="row">
                                    <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="name">  المسوق</label>
                                        <select name="marketer" class="form-control option" style="padding-top:0px;"> 
                                          <option>    المسوق      </option>
                                      @foreach(App\Marketer::all() as $marketer) 
                                                <option value="{{ $marketer->name }}"> {{ $marketer->name }}</option>
                                                         @endforeach
                                            </select>  
                                            </div>
                                        </div>
                                    <div class="col_md-3">
                                            <div class="form-group">
                                                <label for="comm">نسبة المسوق</label>
                                                <input type="number" class="form-control" name="comm" placeholder="نسبة المسوق">
                                                
                                            </div>
                                     </div> 
                                        <div class="col">
                                            <div class="form-group">
                                                <label>تاريخ بداية التجربة </label>
                                                <input required="" type="date" class="form-control" name="trial_date" placeholder="date" >
                                            </div>
                                        </div> 
                                           <div class="col">
                                            <div class="form-group">
                                                <label> فترة التجربة </label>
                                                <input required="" type="number" class="form-control" name="rem_trial" placeholder="date" min="0" value="0">
                                            </div>
                                        </div>
 
                                    </div>

                                    <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="notes">ملاحظات </label>
                                                <br>
                                                <textarea rows="4" cols="100" name="notes">

                                                </textarea>
                                            </div>
                                        </div>
                                   @component('components.attachments-uploader')
                                     @endcomponent
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
            $(document).on('change', 'select#customers2', function(){
                selectCustomer2() 
            })  
            $(document).on('change', 'select#contracts', function(){
                selectContract() 
            }) 

        })
 
        function selectCustomer(){
            let selected_option = $('select#customers option:selected');
            let field_customer_name = $('input[name=name]');
            let field_customer_id_number = $('input[name=id_number]');
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
                field_customer_id_number.val(selected_option.data('id_number'))
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

        function selectCustomer2(){
            let selected_option = $('select#customers2 option:selected');
            let field_customer_name = $('input[name=fname]');
            let field_customer_id_number = $('input[name=fid_number]');
            let field_customer_address = $('input[name=faddr]');
            let field_customer_phones = $('input[name=fphone]');
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
                field_customer_name.val(selected_option.data('fname'))
                field_customer_id.val(selected_option.data('id'))
                field_customer_id.attr('value', selected_option.data('id'))
                field_customer_id_number.val(selected_option.data('fid_number'))
                field_customer_address.val(selected_option.data('faddr'))
                field_customer_phones.val(selected_option.data('fphone'))
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

        function selectContract(){
            let selected_option = $('select#contracts option:selected');
            let field_contract_customer_id = $('input[name=customer_id]');  
            let field_contract_country_id = $('input[name=country_id]');
            let field_contract_profession_id = $('input[name=profession_id]'); 
            let field_contract_id = $('input[name=contract_id]');
            if(selected_option.val() == 'create'){
                field_contract_customer_id.val('')
                field_contract_country_id.val('')
                field_contract_profession_id.val('') 
                field_contract_id.removeAttr('value')
                field_contract_customer_id.removeAttr('disabled')
                field_contract_country_id.removeAttr('disabled')
                field_contract_profession_id.removeAttr('disabled') 
                field_contract_customer_id.attr('required', true)
                field_contract_id.attr('disabled', true)
            }else{  
                field_contract_customer_id.val(selected_option.data('customer_id'))
                field_contract_customer_id.attr('value', selected_option.data('customer_id'))
                field_contract_id.val(selected_option.data('contract_id'))
                field_contract_id.attr('value', selected_option.data('contract_id'))
                field_contract_country_id.val(selected_option.data('country_id'))
                field_contract_profession_id.val(selected_option.data('profession_id')) 
                field_contract_customer_id.attr('disabled', true)
                field_contract_country_id.attr('disabled', true)
                field_contract_profession_id.attr('disabled', true) 
                field_contract_id.removeAttr('disabled')
                field_contract_customer_id.removeAttr('required')
                $('input.parsley-error[name="contract_id"]')
                    .siblings('ul.parsley-errors-list').remove()
                $('input[name="contract_id"]').removeClass('parsley-error')
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