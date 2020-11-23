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

 
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark"> عرض الوكالات </h1>
                        </div>

                    </div>

                </div>
            </div>
            <!-- /.content-header -->
            <section class="content">
                
                 <div class="card card-primary card-outline card-outline-tabs">
    <div class="card-header p-0 border-bottom-0 ">
                        <ul class="nav nav-tabs" id="tabs-tab" role="tablist">
            <li class="nav-item">
    <a class="nav-link active" id="tabs-details-tab" data-toggle="pill" href="#tabs-details" role="tab" aria-controls="tabs-details" aria-selected="true">بيانات الوكالة</a>
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
                    
                    <div class="col-md-12 row"> 
                     
                     <div class="row"> 
                                        <div class="col-md-3">
                                              <span>  الدولة :   @foreach(App\Country::all() as $country)
                                                        @if($country->id==$tafweed->country_id )
                                                             {{$country->name}}
                                                        @endif
                                                            @endforeach </span> 
                                      
                                        </div>

  
                                        <div class="col-md-3">
                                    <span>  اسم العميل :      @foreach(App\Customer::all() as $customer)
                                                        @if($customer->id_number==$tafweed->customer_id )
                                                             {{$customer->name}}
                                                        @endif
                                                            @endforeach   </span> 

                                       
                                        </div>
                                         <div class="col-md-3">
                               
                                            <span>  رقم التاشيرة   :      {{ $tafweed->visa }}     </span> 

                                        
                                        </div>
                                         <div class="col-md-3">
                                       <span>  رقم الهوية   :     {{ $tafweed->identification_num }}     </span> 

                                         
                                        </div>
                                        <div class="col-md-3">
                                     <span>  رقم الجوال   :   {{ $tafweed->phone }}     </span> 

                         
                                                       
                                        </div>
                                        <div class="col-md-12">
                                             <span>  العنوان  :  {{ $tafweed->addr }}      </span> 
                                         
                                        </div>

                                        <div class="col-md-3">
                                     <span>     اسم العامل   : {{ $tafweed->recruitment_cv_name }}  </span> 

                                          
                                        </div>
                                         <div class="col-md-3">
                                       <span>  رقم الجواز   :    {{ $tafweed->recruitment_cv_passport }}    </span> 

                                         
                                        </div>
                                          <div class="col-md-6">
                                     <span>     المكتب الخارجي    :      {{$tafweed->office}}  </span> 

                                          
                                        </div>
                                        
                                        <div class="col-md-3">
                                     <span>   قيمة العقد   :     {{ $tafweed->salary }}    </span> 

                                          
                                        </div>
                                        <div class="col-md-3">
                                     <span>     المسوق   :    {{ $tafweed->marketer }}      </span> 

                                          
                                        </div>
                                        <div class="col-md-3">
                                     <span>    نسبة المسوق   :    {{ $tafweed->comm }}      </span> 

                                          
                                        </div>
                                       
                                      
                                      
                                        <div class="col-md-4">
                                     <span>  رقم عقد مساند ادارة المكاتب     : {{ $tafweed->contract_num }}        </span> 

                                          
                                        </div>
                                        <div class="col-md-3">
                                     <span>  رقم تفويض انجاز   : {{ $tafweed->injaz_num }}         </span> 

                                          
                                        </div>
                                        <div class="col-md-3">
                                     <span>     تكلفة تفويض انجاز  : {{ $tafweed->injaz_cost }}  </span> 

                                          
                                        </div>
                                        <div class="col-md-12">
                                     <span>    ملاحظة   </span> 
<br>
                                               <p>
                                                {{ $tafweed->note }}   
                                               </p>
                                          
                                        </div>
                                    </div>

                                 
                   </div>
          
            
</div>           
            </div>
       
            

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
                        <option value="all" selected="">الكل                                                </option><option value="1">
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
 
        <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper no-footer"><div class="dt-buttons"> <button class="dt-button buttons-print btn btn-default" tabindex="0" aria-controls="DataTables_Table_0" type="button"><span>طباعة <i class="fa fa-print"></i></span></button> <button class="dt-button buttons-excel buttons-html5 btn btn-success" tabindex="0" aria-controls="DataTables_Table_0" type="button"><span>اكسل <i class="fa fa-file-excel"></i></span></button> </div><div id="DataTables_Table_0_filter" class="dataTables_filter"><label>بحث:<input type="search" class="" placeholder="" aria-controls="DataTables_Table_0"></label></div><table class="table table-bordered datatable text-center dataTable no-footer" id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info">
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
            <div class="dataTables_info" id="DataTables_Table_0_info" role="status" aria-live="polite">عرض 0 الى 0 من 0 صفوف</div><div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_0_paginate"><a class="paginate_button previous disabled" aria-controls="DataTables_Table_0" data-dt-idx="0" tabindex="-1" id="DataTables_Table_0_previous">السابق</a><span></span><a class="paginate_button next disabled" aria-controls="DataTables_Table_0" data-dt-idx="1" tabindex="-1" id="DataTables_Table_0_next">التالي</a></div></div>
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
                <input type="hidden" name="voucherable_id" value="{{$tafweed->customer_id}}">
        <input type="hidden" name="voucherable_type" value="<?php foreach(App\Customer::all() as $customer){ 
                                                        if($customer->id_number==$tafweed->customer_id ) {
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
    
                
                
                
                
               
            </section> 

   
 
         
@endsection

            