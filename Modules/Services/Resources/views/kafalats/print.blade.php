@extends('layouts.print')  

<br><br><br><br>
<br><br>
<br><br>

      <table id="datatable" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="datatable_info">
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
                                    @foreach($kafalats as $kafalat)  
                                 
                                    <tr role="row" class="even">
                                        <td> {{ $kafalat->id}} </td> 
                                        <td> {{ $kafalat->trial_date }} </td> 

                                                        <td> @foreach(App\Customer::all() as $customer)
                                                        @if($customer->id_number==$kafalat->f_customer_id )
                                                             {{$customer->name}}
                                                        @endif
                                                            @endforeach</td>  

                                                        <td> @foreach(App\Customer::all() as $customer)
                                                        @if($customer->id_number==$kafalat->s_customer_id )
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
                                                            
                                        <td> {{ $kafalat->job }} </td> 

                                        
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