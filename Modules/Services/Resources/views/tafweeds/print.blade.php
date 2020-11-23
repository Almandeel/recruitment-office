@extends('layouts.print')  

<br><br><br><br>
<br><br>
<br><br>
<h3 class="card-title">قائمة الوكالات - التفاويض</h3> 
<table id="datatable" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="datatable_info">
    <thead>
        <tr role="row">
            <th class="sorting_disabled" rowspan="1" colspan="1" style="width: 10px;">#</th> 
            <th class="sorting_disabled" rowspan="1" colspan="1" style="width: 317px;">اسم العميل	</th>
              <th class="sorting_disabled" rowspan="1" colspan="1" style="width: 118px;">رقم التأشيرة</th>
            <th class="sorting_disabled" rowspan="1" colspan="1" style="width: 92px;">رقم الهوية</th>
              <th class="sorting_disabled" rowspan="1" colspan="1" style="width: 92px;">الدوله</th> 
              <th class="sorting_disabled" rowspan="1" colspan="1" style="width: 92px;">المكتب الخارجي</th> 
          
            
            <th class="sorting_disabled" rowspan="1" colspan="1" style="width: 133px;">العامل \ العاملة</th>
            <th class="sorting_disabled" rowspan="1" colspan="1" style="width: 99px;">رقم الجواز</th>
           
        
             
        </tr>
    </thead>
    <tbody> 

    @foreach($tafweeds as $tafweed)  
        <tr role="row" class="odd">
            <td style="width: 10px;"> {{ $tafweed->id }}</td>  

            <td style="width: 317px;"> @foreach(App\Customer::all() as $customer)
            @if($customer->id_number==$tafweed->customer_id )
                 {{$customer->name}}
            @endif
                @endforeach</td>  

            <td style="width: 118px;"> {{ $tafweed->visa }}</td> 
            <td style="width: 92px;"> {{ $tafweed->identification_num }}</td> 

            <td style="width: 92px;"> @foreach(App\Country::all() as $country)
            @if($country->id==$tafweed->country_id )
                 {{$country->name}}
            @endif
                @endforeach</td> 


            <td style="width: 92px;"> {{ $tafweed->office}} </td>  

            <td style="width: 133px;"> {{ $tafweed->recruitment_cv_name }}</td> 
            <td style="width: 99px;"> {{ $tafweed->recruitment_cv_passport }}</td>  
             
        </tr>

    </tbody>
    @endforeach
</table>
 