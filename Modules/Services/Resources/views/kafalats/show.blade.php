@extends('layouts.master',
[
    'datatable' => true,  
    'title' => '  عرض  بيانات نقل كفالة  : ' . $kafalat->id, 
    'modals' => [ 'attachment'],
    'crumbs' => [
        [route('kafalat.index'), 'الكفالات'],
        ['#', '   قل كفالة   : ' . $kafalat->id],
    ],
])

@section('content')  
<section class="content">
        <div class="card card-primary card-outline card-outline-tabs">
    <div class="card-header p-0 border-bottom-0 ">
                        <ul class="nav nav-tabs" id="tabs-tab" role="tablist">
            <li class="nav-item">
    <a class="nav-link active" id="tabs-details-tab" data-toggle="pill" href="#tabs-details" role="tab" aria-controls="tabs-details" aria-selected="true">   بيانات نقل كفالة</a>
</li>  
 
                @component('components.tab-item')
                    @slot('id', 'attachments')
                    @slot('title', 'المرفقات')
                @endcomponent            
 <li class="nav-item">
    <a class="nav-link " id="tabs-vouchers-tab" data-toggle="pill" href="#tabs-vouchers" role="tab" aria-controls="tabs-vouchers" aria-selected="true">السندات</a>
</li>
        </ul> 
    </div>
    <div class="card-body ">
        
       
        <div class="tab-content row" id="tabs-tabContent">
            <div class="tab-pane fade active show" id="tabs-details" role="tabpanel" aria-labelledby="tabs-details-tab">
        
                <div class="row">
                    
                    <div class="col-md-6">
                        
                       <center>     <h5 div class="text-center">  بيانات العميل الاول</h5>  </center>
                        <div class="row">
                              <div class="col-md-6">
                       
                              <span> <b>  تاريخ العقد: </b>   {{ $kafalat->trial_date }}  </span> 
                     </div>
                        
                             <div class="col-md-6">
                       
                              <span> <b>  المهنة  : </b>   {{ $kafalat->job }}    </span> 
                     </div>
                             <div class="col-md-6">
                       
                              <span>  <b> رقم العقد : </b> {{ $kafalat->contract_id }}  </span> 
                     </div>
                             <div class="col-md-6">
                       
                              <span>  <b>   العامل \ العاملة  :   </b> {{ $kafalat->recruitment_cv_name }}  </span> 
                     </div>
                             <div class="col-md-6">
                       
                              <span> <b>  اسم العميل   :  </b>  @foreach(App\Customer::all() as $customer)
                                                        @if($customer->id==$kafalat->f_customer_id )
                                                             {{$customer->name}}
                                                        @endif
                                                            @endforeach    </span> 
                     </div>
                             <div class="col-md-6">
                       
                              <span>  <b> الحالة الإجتماعية   : </b> {{ $cv->marital_status }}  </span> 
                     </div>
                             <div class="col-md-6">
                       
                              <span>  <b> قيمة العقد    : </b>  {{ $contract->amount }}   </span> 
                     </div>
                             <div class="col-md-6">
                       
                              <span> <b>  الديانة   :   </b>  {{$cv->religion}}   </span> 
                     </div>
                             <div class="col-md-6">
                       
                              <span>  <b>  المسوق :  </b> @foreach(App\Marketer::all() as $marketer)
                                                        @if($marketer->id==$contract->marketer_id )
                                                             {{$marketer->name}}
                                                        @endif
                                                            @endforeach   </span> 
                     </div>
                             <div class="col-md-6">
                       
                              <span>  <b> نسبة المسوق   :  </b> {{ $contract->marketing_ratio }}   </span> 
                     </div>
                             <div class="col-md-6">
                       
                              <span> <b>  جهة القدوم :   </b>{{ $contract->destination }}     </span> 
                     </div>
                             <div class="col-md-6">
                       
                              <span> <b> مطار القدوم : </b>  {{ $contract->arrival_airport }}   </span> 
                     </div>
                             <div class="col-md-6">
                       
                              <span> <b>الدولة  : </b>  @foreach(App\Country::all() as $country)
                                                        @if($country->id==$contract->country_id )
                                                             {{$country->name}}
                                                        @endif
                                                            @endforeach  </span> 
                     </div>
                             <div class="col-md-6">
                       
                              <span> <b> تاريخ الوصول  :  </b> {{ $contract->date_arrival }} </span> 
                     </div>
                             <div class="col-md-6">
                       
                              <span>  <b>تاريخ التقديم  :  </b> {{ $contract->start_date }} </span> 
                     </div>
                             <div class="col-md-6">
                       
                              <span>  <b> مدة التقديم   : </b> {{ $kafalat->rem_trial }}  يوم  </span> 
                     </div>
                                                  <div class="col-md-12">
                                                       <h5>ملاحظات العقد :</h5>
                                                       
                       
                              <span>    {{ $kafalat->note }}  
                                                       </span> 
                           
                          
                       
                     </div>
                           
                            
                        
                    </div></div>
            <div class="col-md-6 data"  accesskey=""
                               style="
    border-right: 1px dotted;
"
                               >
                              
                              
  <center> <h5 div class="text-center">  بيانات العميل الثاني</h5></center>
                
                    <div class="row">  
                    <div class="col-md-12" style="display: inline-block;">  
            
  <form action="{{url('/services/kafalat/tog')}}" method="POST"> تاكيد نقل الكفالة          
   {{csrf_field()}} 
   <input <?php if($kafalat->status == 2) echo "checked"; ?> type="checkbox" name="status">
   <input class="btn btn-primary" type="submit" value="حفظ" name=""> 
    <input type="hidden" name="id" value="{{$kafalat->id}}"> 
      <?php  if($kafalat->status==2) { echo "تم النقل" ; } else echo "قيد النقل" ; ?> 
</form> 
</div></div>


                <br>
             
                              <div class="row">
                                <div class="col-md-6">
                       
                              <span>  <b>تاريخ  بداية التجربة: </b> {{ $kafalat->trial_date }} </span> 
                           
                          
                                   
                       
                     </div>
                                         <div class="col-md-6">
                       
                              <span>  <b>فترة التجربة :  </b>{{ $kafalat->rem_trial }}  ايام </span> 
                           
                          
                       
                     </div>
                                                <div class="col-md-12">
                       
                              <span>  <b> اسم العميل : </b> @foreach(App\Customer::all() as $customer)
                                                        @if($customer->id==$kafalat->s_customer_id )
                                                             {{$customer->name}}
                                                        @endif
                                                            @endforeach    </span> 
                           
                          
                       
                     </div>               <div class="col-md-6">
                       
                              <span>  <b>رقم الهوية  : </b>{{ $kafalat->id_number }} </span> 
                           
                          
                       
                     </div>
                                              <div class="col-md-6">
                       
                              <span> <b> رقم الجوال  : </b> {{ $kafalat->phone }} </span> 
                           
                          
                       
                     </div>
                                       <div class="col-md-6">
                       
                              <span> <b> تاريخ   نقل الكفالة:   </b> {{ $kafalat->transfer_date}}  </span> 
                           
                          
                       
                     </div> 
                                  
                                       <div class="col-md-12">
                       
                              <span>  <span>  <b> العنوان : </b>  {{ $kafalat->addr }} </span> 
                           
                          
                       
                     </div> 
                                  
                                       <div class="col-md-6">
                       
                              <span>  <b>قيمة نقل الكفاله : </b>  {{ $kafalat->amount }}   </span> 
                           
                          
                       
                     </div>
                                  
                                  
                                             <div class="col-md-6">
                       
                              <span> <b> المسوق:   </b>  {{ $kafalat->marketer }}     </span> 
                           
                          
                       
                     </div>
                                  
                                             <div class="col-md-6">
                       
                              <span> <b>  النسبة:    </b> {{ $kafalat->comm }}   </span> 
                           
                          
                       
                     </div>
                                                   <div class="col-md-12">
                                                       <h5>ملاحظة :</h5>
                                                       
                       
                              <span>    {{ $kafalat->note }}     
                                                       </span> 
                           
                          
                       
                     </div>
                                  
                                 
                              
                              
                    </div>
                </div>
            
</div>           
            </div>
       
                @component('components.tab-content')
                    @slot('id', 'attachments')
                    @slot('content')
                        @component('components.attachments-viewer')
                            @slot('attachable', $kafalat)
                            @slot('canAdd', true)
                            @slot('view', 'timeline')
                        @endcomponent
                    @endslot
                @endcomponent            


  <div class="tab-pane fade " id="tabs-vouchers" role="tabpanel" aria-labelledby="tabs-vouchers-tab">
    <div class="vouchers-view">
    <div class="vouchers-view-header clearfix">
        <h3 class="vouchers-title float-left">
            <i class="fa fa-list"></i>
            <span>قائمة السندات</span>
        </h3>
                                <button class="btn btn-primary btn-add-voucher float-right">
                <i class="fa fa-plus"></i>
                <span>اضافة سند</span>
            </button>
                        </div>
    <div class="vouchers-view-body">
        <div class="mb-2">
            <div class="form-group">
                <label>
                    <i class="fa fa-cogs"></i>
                    <span>بحث متقدم</span>
                </label>
            </div>
            <form action="" method="GET" class="form-inline guide-advanced-search" novalidate="">
                @csrf               <div class="form-group mr-2">
                    <label for="vouchers-type">النوع</label>
                    <select name="vouchers_type" id="vouchers-type" class="form-control" required="">
                        <option value="all" selected="">الكل                                                </option>
                        <option value="1">
                            سند قبض
                        </option>
                                                <option value="-1">
                            سند صرف
                        </option>
                                            </select>
                </div>
                <div class="form-group mr-2">
                    <label for="vouchers-status">الحالة</label>
                    <select class="form-control type" name="vouchers_status" id="vouchers-status">
                        <option value="all" selected="">الكل</option>
                                                <option value="waiting">
                            في انتظار التأكيد                        </option>
                                                <option value="approved">
                            تم التأكيد                        </option>
                                                <option value="checking">
                            في الحسابات                        </option>
                                                <option value="checked">
                            تم الصرف                        </option>
                                                <option value="rejected">
                            تم الرفض                        </option>
                                            </select>
                </div>
                <div class="form-group mr-2">
                    <label for="vouchers-from-date">من</label>
                    <input type="date" name="vouchers_from_date" id="vouchers-from-date" value="2020-10-02" class="form-control">
                </div>
                <div class="form-group mr-2">
                    <label for="vouchers-to-date">الى</label>
                    <input type="date" name="vouchers_to_date" id="vouchers-to-date" value="2020-10-02" class="form-control">
                </div>
                <button type="submit" class="btn btn-primary">
                    <span>بحث</span>
                    <i class="fa fa-search"></i>
                </button>
            </form>
        </div> 
        <table class="table table-bordered datatable text-center dataTable no-footer" id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info">
            <thead>
                <tr role="row">
                  <th class="sorting_disabled" rowspan="1" colspan="1" style="width: 0px;">المعرف</th>
                  <th class="sorting_disabled" rowspan="1" colspan="1" style="width: 0px;">النوع</th>
                  <th class="sorting_disabled" rowspan="1" colspan="1" style="width: 0px;">القيمة</th>
                  <th class="sorting_disabled" rowspan="1" colspan="1" style="width: 0px;">التاريخ</th>
                  <th class="sorting_disabled" rowspan="1" colspan="1" style="width: 0px;">الحالة</th>
                  <th class="sorting_disabled" rowspan="1" colspan="1" style="width: 0px;">مستخدم</th>
                  <th class="sorting_disabled" rowspan="1" colspan="1" style="width: 0px;">الخيارات</th>
                </tr>
            </thead>
            <tbody>
              @foreach($voucher as $voucher)
                <tr class="odd"> 
                  <td>{{ $voucher->id }}</td>
                  <td> @if ( $voucher->type==1)     سند قبض   
                      @elseif ($voucher->type==-1) سند صرف    
                       @endif 
                  </td>
                  <td>{{ $voucher->amount }}</td>
                  <td>{{ $voucher->voucher_date }}</td>
                  <td>@if ( $voucher->status==1)        في انتظار التاكيد   
                      @elseif ($voucher->status==2)    تم التاكيد   
                       @endif 
                     </td>
                  <td>{{ $voucher->voucherable_type }}</td>
                  <td> </td>
                </tr>
              @endforeach  
                </tbody>
        </table> 
    </div>
    <div class="vouchers-view-footer clearfix">
        <div class="float-left">

        </div>
                                <button class="btn btn-primary btn-add-voucher float-right">
                <i class="fa fa-plus"></i>
                <span>اضافة سند</span>
            </button>
                        </div>
        
    </div>
        <form class="vouchers-form" method="POST" action="{{ url('/accounting/vouchers') }}" novalidate="" enctype="multipart/form-data">
        @csrf
                <input type="hidden" name="_method" value="POST">        
                <input type="hidden" name="voucherable_id" value="{{$kafalat->f_customer_id}}">
        <input type="hidden" name="voucherable_type" value="<?php foreach(App\Customer::all() as $customer){ 
                                                        if($customer->id_number==$kafalat->f_customer_id ) {
                                                           echo $customer->name ; } }
                                                          ?>">
        <fieldset>
            <legend>
                <i class="fa fa-plus"></i>
                <span>اضافة سند</span>
            </legend>
            <div class="form-group row">
                                    <div class="col col-xs-12">
                        <label for="type">النوع</label>
                        <select name="type" id="type" class="form-control" required="">
                            <option value="">اختر نوع السند</option>
                                                            <option value="1">
                                    سند قبض
                                </option>
                                                            <option value="-1">
                                    سند صرف
                                </option>
                                                    </select>
                    </div>
                                <div class="col col-xs-12">
                    <label for="amount">القيمة</label>
                    <div class="input-group">
                        <input type="number" id="amount" name="amount" value="0" min="1" class="form-control" required="">
                                                    <select name="currency" id="currency" class="form-control" required="">
                                <option value="ريال">ريال</option>
                                <option value="دولار">دولار</option>
                            </select>
                                            </div>
                </div>
                
            </div>
            <div class="form-group">
                <label>
                    <i class="fas fa-paperclip"></i>
                    <span>المرفقات</span>
                </label>
                <table class="table table-bordered table-attachments">
    <thead>
                <tr>
            <th style="width: 50px">#</th>
            <th colspan="2">الملفات والملاحظات</th>
            <th style="width: 50px">الخيارات</th>
        </tr>
    </thead>

    <tbody></tbody>
    <tfoot>
        <tr>
            <th colspan="4">
                <div class="btn-group text-center">
                                                                <button type="button" class="btn btn-default btn-sm btn-add" data-component="file">
                            <i class="fa fa-file"></i>
                            <span class="d-none d-md-inline">إضافة ملف</span>
                        </button>
                                                                <button type="button" class="btn btn-success btn-sm btn-add" data-component="note">
                            <i class="fa fa-sticky-note"></i>
                            <span class="d-none d-md-inline">إضافة ملاحظة</span>
                        </button>
                                                        </div>
            </th>
        </tr>
    </tfoot>
</table>

            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">اكمال العملية</button>
                <button type="button" class="btn btn-secondary btn-cancel-voucher">إلغاء</button>
            </div>
        </fieldset>
    </form>
</div>
        </div>
            
            
        </div>
        
        
        
    </div>
</div>
    </section> 

    @endsection
 