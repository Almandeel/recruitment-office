@extends('layouts.master' ,   
  [  'title' => ' نقل الكفالات  ',
    'datatable' => true,  ]) 
@section('content')   
        <!-- /.content-header -->
        <section class="content">
 
            <div class="col-12">
                <div class="card">
                    <div class="card-header"> <br>
                        <h3 class="card-title">قائمة نقل الكفالات</h3>
                        <a class="card-title float-right btn btn-primary" href="{{url('services/kafalat/create')}}"><i class="fa fa-plus"></i> اضافة </a>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-extra clearfix">
                        <form action="" method="GET" class="guide-advanced-search" novalidate=""> 
                            <div class="form-group form-inline">
                                <div class="form-group mr-2">
                                    <i class="fa fa-filter"></i>
                                    <span>فرز</span>
                                </div>
                                <div class="form-group mr-2">
                                    <label for="country_id">الدولة</label>
                                     <select name="country_id" id="country_id" class="form-control">
                                     <option value="all" selected="">الكل</option>
                                     @foreach(App\Country::all() as $country) 
                                     <option value="{{$country->id}}">{{$country->name}}</option>
                                     @endforeach
                                    </select> 
                                </div>
                                <div class="form-group mr-2">
                                    <label for="office_id"> المكتب الخارجي   </label>
                                                <select name="office_id" class="form-control option" style="padding-top:0px;">
                                                <option> الكل  </option>
                                                           @foreach($offices as $office) 
                                                <option value="{{ $office->id }}"> {{ $office->name }}</option>
                                                         @endforeach
                                            </select>  
                                </div>
                                <div class="form-group mr-2">
                                    <label for="profession_id">المهنة</label>
                                                    <select name="profession_id" id="profession_id" class="form-control">
                                                        <option value="all" selected="">الكل</option>
                                                        @foreach($professions as $profession) 
                                                        <option value="{{$profession->id}}">{{$profession->name}}</option>
                                                         @endforeach
                                                    </select> 
                                </div>
                                <div class="form-group mr-2">
                                    <label for="gender">الجنس</label>
                                                    <select class="form-control type" name="gender" id="gender">
                                                        <option value="all" selected="">الكل</option>
                                                        <option value="1">ذكر</option>
                                                        <option value="2">انثى</option>
                                                    </select>
                                </div>
                            </div>
                            <div class="form-inline">

                                <div class="form-group mr-2">
                                    <label for="from-date">من</label>
                                    <input type="date" name="from_date" id="from-date" value="2020-10-03" class="form-control">
                                </div>
                                <div class="form-group mr-2">
                                    <label for="to-date">الى</label>
                                    <input type="date" name="to_date" id="to-date" value="2020-12-04" class="form-control">
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <span>بحث</span>
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="card-body">
                        <div id="datatable_wrapper" class="dataTables_wrapper">
                            <div class="dataTables_length" id="datatable_length"><label>عرض <select name="datatable_length" aria-controls="datatable" class="">
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select> صفوف</label></div>
                            <div id="datatable_filter" class="dataTables_filter">
                            
                                <form action="{{url('services/kafalat/create')}}" method="POST" class="guide-advanced-search" novalidate=""> 
                                <label>بحث:<input type="search" class="" id="esearch" placeholder="" aria-controls="datatable"></label>
                                </form>

                            </div> 
                        <table id="datatable" class="table datatable table-bordered table-striped">
                                <thead>
                                    <tr role="row">
                                        <th class="sorting_disabled" rowspan="1" colspan="1" style="width: 10px;">#</th>
                                            <th class="sorting_disabled" rowspan="1" colspan="1" style="width: 117px;">تاريخ فترة التجربة</th>
                                        <th class="sorting_disabled" rowspan="1" colspan="1" style="width: 187px;"> الكفيل الاول 
                                        (العميل)
                                        </th>
                                        <th class="sorting_disabled" rowspan="1" colspan="1" style="width: 105px;">
                                        
                                        الكفيل الثاني 
                                        (العميل)
                                        </th>
                                        <th class="sorting_disabled" rowspan="1" colspan="1" style="width: 103px;">العامل \ العاملة</th>
                                        <th class="sorting_disabled" rowspan="1" colspan="1" style="width: 69px;">رقم الجواز</th>
                                        <th class="sorting_disabled" rowspan="1" colspan="1" style="width: 88px;">المكتب الخارجي</th>
                                        <th class="sorting_disabled" rowspan="1" colspan="1" style="width: 64px;">المهنة</th>
                                        <th class="sorting_disabled" rowspan="1" colspan="1" style="width: 96px;">متبقي فترة التجربه </th>
                                        <th class="sorting_disabled" rowspan="1" colspan="1" style="width: 88px;">تاريخ نقل الكفالة</th> 
                                        <th class="sorting_disabled" rowspan="1" colspan="1" style="width: 62px;">الحالة</th>
                                        <th class="sorting_disabled" rowspan="1" colspan="1" style="width: 290px;">خيارات</th>
                                    </tr>
                                </thead>


                                <tbody> 
                                 
                                @foreach ($kafalats as $index=>$kafalat)
                                    <tr role="row" class="even">
                                        <td> {{ $kafalat->id}} </td> 
                                        <td> {{ $kafalat->trial_date }} </td> 

                                                        <td> @foreach(App\Customer::all() as $customer)
                                                        @if($customer->id==$kafalat->f_customer_id )
                                                             {{$customer->name}}
                                                        @endif
                                                            @endforeach</td>  

                                                        <td> @foreach(App\Customer::all() as $customer)
                                                        @if($customer->id==$kafalat->s_customer_id )
                                                             {{$customer->name}}
                                                        @endif
                                                            @endforeach</td> 
 
                                        <td> {{ $kafalat->recruitment_cv_name }} </td> 
                                        <td> {{ $kafalat->recruitment_cv_passport }} </td> 
                                        
                                                        <td> @foreach(App\Office::all() as $office)
                                                        @if($office->id==$kafalat->office_id )
                                                             {{$office->name}}
                                                        @endif
                                                            @endforeach</td>  
                                                            
                                        <td>@foreach(App\Profession::all() as $profession)
                                                        @if($profession->id==$kafalat->job )
                                                             {{$profession->name}}
                                                        @endif
                                                            @endforeach 
                                         </td> 

                                        
                                        <td> {{ $kafalat->rem_trial }} </td> 
                                        <td> {{ $kafalat->transfer_date }} </td> 
                                        <td>
                                        @if ($kafalat->status==1)  <span class="badge badge-info">  قيد التنفيذ </span> 
                                        
                                        @elseif ($kafalat->status==2)<span class="badge badge-info">  تم النقل  </span> 
                                        @endif
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a class="btn btn-info" href="{{route('kafalat.show',[$kafalat->id])}}"><i class="fa fa-eye"></i> عرض</a>
                                                <a class="btn btn-primary contracts update" href="{{route('kafalat.edit',[$kafalat->id])}}"><i class="fa fa-edit"></i> تعديل</a>
 
                                                
                                                <form method="POST" action="{{route('kafalat.destroy',[$kafalat->id])}}">
                                                  @csrf
                                                  @method('DELETE')
                                                  <button type="button" class="btn btn-danger" data-toggle="confirm"  data-title="حذف  نقل الكفالة" data-text="سوف يتم حذف  نقل الكفالة  نهائيا من النظام استمرار؟"  style="border-radius: 0;font-size: 1rem;">
                                                    <i class="fa fa-trash"></i>
                                                    <span>حذف</span> 
                                                </button> 

                                                </form>    
 
                                            </div> 
                                        </td>
                                    </tr>
                                   

                                   @endforeach


                                </tbody> 
                              
                            </table>
 
  
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>

        </section>

@endsection
<style type="text/css">
      #esearch { 
      position: absolute;
      top: -999px;
      left: -999px;
      width: 0px;
      height: 0px;
}
</style>