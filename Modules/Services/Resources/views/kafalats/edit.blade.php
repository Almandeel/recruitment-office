@extends('layouts.master', [
    'title' => 'تعديل  بيانات   نقل  كفالة : ' . $kafalat->id,
    'datatable' => true,  
    'crumbs' => [
        [route('kafalat.index'), ' الكفالات'], 
        ['#', 'تعديل'],
    ]
])

@section('content')  
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
                                        <div class="col">
                                            <div class="form-group">
                                                <label> رقم العقد      </label>
                                                <input required="" type="text" class="form-control" name="contract_id" placeholder=" رقم العقد   " value="{{ $contract->id }}">
                                            </div>
                                        </div> 
                                        <div class="col">
                                            <div class="form-group">
                                                <label>تاريخ بداية التجربة </label>
                                                <input required="" type="date" class="form-control" name="trial_date" placeholder="date" value="{{ $kafalat->trial_date }}">
                                            </div>
                                        </div> 
                                           <div class="col">
                                            <div class="form-group">
                                                <label> فترة التجربة </label>
                                                <input required="" type="number" class="form-control" name="rem_trial" placeholder="date" value="{{ $kafalat->rem_trial }}">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                        <span>بيانات العميل الاول </span> 
                                        </div>

                                        <br>
 
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="addr"> اسم العميل الاول </label>
                                                        @foreach(App\Customer::all() as $customer)
                                                        @if($customer->id==$contract->customer_id ) 
                                                <input type="text" class="form-control" name="f_name" placeholder="الاسم " value="{{$customer->name}}" disabled="disabled">
                                                        @endif
                                                        @endforeach
                                            </div>
                                        </div>

                                     
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="addr"> العنوان </label>
                                                        @foreach(App\Customer::all() as $customer)
                                                        @if($customer->id==$contract->customer_id ) 
                                                <input type="text" class="form-control" name="f_addr" placeholder="العنوان " value="{{$customer->address}}" disabled="disabled">
                                                        @endif
                                                        @endforeach
                                            </div>
                                        </div>

                                     
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="id_number"> رقم الهوية </label>
                                                        @foreach(App\Customer::all() as $customer)
                                                        @if($customer->id==$contract->customer_id ) 
                                                <input type="text" style="border-radius: 0;" class="form-control" name="f_id_number" placeholder="رقم الهوية" value="{{$customer->id_number}}" disabled="disabled">
                                                        @endif
                                                        @endforeach
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                        @foreach(App\Customer::all() as $customer)
                                                        @if($customer->id==$contract->customer_id ) 
                                                <label for="phone"> رقم الجوال </label>

                                                <input type="text" style="border-radius: 0;" class="form-control" name="f_phone" placeholder="رقم الجوال" value="{{$customer->phones}}" disabled="disabled">
                                                        @endif
                                                        @endforeach
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
                                                <label for="name"> الاسم </label>@foreach(App\Customer::all() as $customer)
                                                    @if($customer->id==$kafalat->s_customer_id )
                                                             
                                                       
                                                <input type="text" class="form-control" name="name" value="{{ $customer->name }}">                                      @endif
                                                 @endforeach             
                                            </div>
                                        </div>

                                        
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="addr"> العنوان </label>
                                                <input type="text" class="form-control" name="addr" value="{{ $kafalat->addr }}">
                                            </div>
                                        </div>

                                        </div>


                                        <div class="row">                                     
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="id_number"> رقم الهوية </label>

                                                <input type="text" style="border-radius: 0;" class="form-control" name="id_number"  value="{{ $kafalat->id_number }}">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="phone"> رقم الجوال </label>

                                                <input type="text" style="border-radius: 0;" class="form-control" name="phone"  value="{{ $kafalat->phone }}">
                                            </div>
                                        </div>

                                        
                                        
                                           <div class="col-md-3">
                                            <div class="form-group">
                                                <label>تاريخ  نقل الكفالة </label>
                                                <input required="" type="date" class="form-control" name="transfer_date"   value="{{ $kafalat->transfer_date }}">
                                            </div>
                                        </div>
                                        
                                        
                                     
                                          <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="amount">قيمة نقل الكفالة</label>
                                                <input required="" type="number" class="form-control" name="amount"  value="{{ $kafalat->amount }}">
                                            </div>
                                        </div>
                                        </div>

                                  <div class="row">
                                    <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="name">  المسوق</label>
                                        <select name="marketer" class="form-control option" style="padding-top:0px;"> 
                                          <option value="{{ $kafalat->marketer }}">    {{ $kafalat->marketer }}  </option>
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
                                                <input type="number" class="form-control" name="comm" placeholder="نسبة المسوق" value="{{ $kafalat->comm }}">
                                                
                                            </div>
                                     </div> 
 
                                    </div>

                                    <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="notes">ملاحظات </label>
                                                <br>
                                                <textarea rows="4" cols="100" name="notes"  value="{{ $kafalat->notes }}">

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