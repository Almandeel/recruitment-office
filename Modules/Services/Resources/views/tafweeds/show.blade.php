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
                                              <span>  الدولة :   @foreach($countries as $country)
                                                        @if($country->id==$tafweed->country_id )
                                                             {{$country->name}}
                                                        @endif
                                                            @endforeach </span> 
                                      
                                        </div>

  
                                        <div class="col-md-3">
                                    <span>  اسم العميل :      @foreach($customers as $customer)
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
                @component('accounting::components.vouchers')
                    @slot('voucherable', $tafweed)
                @endcomponent
            </div>
        </div>
            
            
        </div>
        
        
        
    </div>
    
                
                
                
                
               
            </section> 

   
 
         
@endsection

            