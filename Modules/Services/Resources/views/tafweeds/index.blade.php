@extends('layouts.master' ,
  [  'title' => '     الوكالات - التفاويض  ',
    'datatable' => true,  ])

@section('content')
                    <!-- /.content-header -->
                    <div class="col-md-12">
                    </div>
                    <section class="content">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">قائمة الوكالات - التفاويض</h3>
                                        @permission('delegations-create')
                                        <a class="card-title float-right btn btn-primary" href="{{url('services/tafweed/create')}}"><i class="fa fa-plus"></i> اضافة </a>
                                        @endpermission
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
                                     @foreach(\Modules\ExternalOffice\Models\Country::all() as $country)
                                     <option value="{{$country->id}}">{{$country->name}}</option>
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
                            <div class="dataTables_length" id="datatable_length"><label>
                                عرض
                                <select name="datatable_length" aria-controls="datatable" class="">
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select> صفوف</label></div>
                            <div id="datatable_filter" class="dataTables_filter">


                            </div>
                        <table id="datatable" class="table datatable table-bordered table-striped">
                                                <thead>
                                                    <tr role="row">
                                                        <th class="sorting_disabled" rowspan="1" colspan="1" style="width: 10px;">#</th>
                                                        <th class="sorting_disabled" rowspan="1" colspan="1" style="width: 187px;">اسم العميل   </th>
                                                          <th class="sorting_disabled" rowspan="1" colspan="1" style="width: 88px;">رقم التأشيرة</th>
                                                        <th class="sorting_disabled" rowspan="1" colspan="1" style="width: 62px;">رقم الهوية</th>
                                                          <th class="sorting_disabled" rowspan="1" colspan="1" style="width: 62px;">الدوله</th>
                                                        <th class="sorting_disabled" rowspan="1" colspan="1" style="width: 105px;">المكتب الخارجي</th>

                                                        <th class="sorting_disabled" rowspan="1" colspan="1" style="width: 103px;">العامل \ العاملة</th>
                                                        <th class="sorting_disabled" rowspan="1" colspan="1" style="width: 69px;">رقم الجواز</th>


                                                        <th class="sorting_disabled" rowspan="1" colspan="1" style="width: 290px;">خيارات</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                @foreach($tafweeds as $tafweed)
                                                    <tr role="row" class="odd">
                                                        <td> {{ $tafweed->id }}</td>
                                                        <td> @foreach(\Modules\Services\Models\Customer::all() as $customer)
                                                        @if($customer->id==$tafweed->customer_id )
                                                             {{$customer->name}}
                                                        @endif
                                                            @endforeach</td>

                                                        <td> {{ $tafweed->visa }}</td>
                                                        <td> {{ $tafweed->identification_num }}</td>

                                                        <td> @foreach(\Modules\ExternalOffice\Models\Country::all() as $country)
                                                        @if($country->id==$tafweed->country_id )
                                                             {{$country->name}}
                                                        @endif
                                                            @endforeach</td>


                                                        <td>  {{$tafweed->office}} </td>

                                                        <td> {{ $tafweed->recruitment_cv_name }}</td>
                                                        <td> {{ $tafweed->recruitment_cv_passport }}</td>
                                                        <td>
                                                            <div class="btn-group">
                                                                @permission('delegations-read')
                                                                <a class="btn btn-info" href="{{route('tafweed.show',[$tafweed->id])}}"><i class="fa fa-eye"></i> عرض</a>
                                                                @endpermission
                                                                @permission('delegations-update')
                                                                <a class="btn btn-primary contracts update" href="{{route('tafweed.edit',[$tafweed->id])}}"><i class="fa fa-edit"></i> تعديل</a>
                                                                @endpermission
                                                                @permission('delegations-delete')
                                                                <form method="POST" action="{{route('tafweed.destroy',[$tafweed->id])}}">
                                                                  @csrf
                                                                  @method('DELETE')
                                                                  <button type="submit" class="btn btn-danger" data-toggle="confirm"
                                                                  data-title="حذف  الوكالة"
                                                                  data-text="سوف يتم حذف   الوكالة  نهائيا من النظام استمرار؟"
                                                                  style="border-radius: 0;font-size: 1rem;">
                                                                    <i class="fa fa-trash"></i>
                                                                    <span>حذف</span>
                                                                  </button>
                                                                </form>
                                                                @endpermission
                                                            </div>
                                                        </td>
                                                    </tr>

                                                </tbody>
                                                @endforeach
                                            </table>


                                        </div>
                                    </div>
                                    <!-- /.card-body -->
                                </div>
                                <!-- /.card -->
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->
                    </section>


            <!-- /.control-sidebar -->


@endsection

