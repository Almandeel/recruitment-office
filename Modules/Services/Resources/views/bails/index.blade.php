@extends('layouts.master', [
    'title' => 'نقل الكفالة',
    'modals' => ['customer'],
    'datatable' => true,
    'crumbs' => [
        ['#', 'نقل الكفالة'],
    ]
])

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">نقل الكفالة</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-extra clearfix">
                        <form action="" method="GET" class="guide-advanced-search">
                            @csrf
                            <div class="form-group form-inline">
                                <div class="form-group mr-2">
                                    <i class="fa fa-filter"></i>
                                    <span>@lang('global.filter')</span>
                                </div>
                                <div class="form-group mr-2">
                                    <label for="country_id">@lang('global.country')</label>
                                    <select name="country_id" id="country_id" class="form-control">
                                        <option value="all" {{ $country_id == 'all' ? 'selected' : ''}}>@lang('global.all')</option>
                                        @foreach ($countries as $country)
                                            <option value="{{ $country->id }}" {{ $country_id == $country->id ? 'selected' : ''}}>{{ $country->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group mr-2">
                                    <label for="office_id">@lang('global.office')</label>
                                    <select name="office_id" id="office_id" class="form-control">
                                        <option value="all" {{ $office_id == 'all' ? 'selected' : ''}}>@lang('global.all')</option>
                                        @foreach ($offices as $office)
                                            <option value="{{ $office->id }}" {{ $office_id == $office->id ? 'selected' : ''}}>{{ $office->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group mr-2">
                                    <label for="profession_id">@lang('global.profession')</label>
                                    <select name="profession_id" id="profession_id" class="form-control">
                                        <option value="all" {{ $profession_id == 'all' ? 'selected' : ''}}>@lang('global.all')</option>
                                        @foreach ($professions as $profession)
                                            <option value="{{ $profession->id }}" {{ $profession_id == $profession->id ? 'selected' : ''}}>{{ $profession->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group mr-2">
                                    <label for="gender">@lang('global.gender')</label>
                                    <select class="form-control type" name="gender" id="gender">
                                        <option value="all" {{ ($gender == 'all') ? 'selected' : '' }}>@lang('global.all')</option>
                                        <option value="male" {{ ($gender == 'male') ? 'selected' : '' }}>@lang('global.gender_male')</option>
                                        <option value="female" {{ ($gender == 'female') ? 'selected' : '' }}>@lang('global.gender_female')</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-inline">
                                <div class="form-group mr-2">
                                    <label for="status">@lang('global.status')</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="all" {{ $status == 'all' ? 'selected' : ''}}>@lang('global.all')</option>
                                        <option value="trail_pending" {{ $status == 'trail_pending' ? 'selected' : ''}}>قيد التجربة / لم يتم النقل</option>
                                        @foreach (__('bails.statuses') as $key => $value)
                                            <option value="{{ $key }}" {{ $status == $key ? 'selected' : ''}}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group mr-2">
                                    <label for="from-date">@lang('global.from')</label>
                                    <input type="date" name="from_date" id="from-date" value="{{ $from_date }}"
                                        class="form-control">
                                </div>
                                <div class="form-group mr-2">
                                    <label for="to-date">@lang('global.to')</label>
                                    <input type="date" name="to_date" id="to-date" value="{{ $to_date }}"
                                        class="form-control">
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <span>@lang('global.search')</span>
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="card-body">
                        <table id="datatable" class="table datatable table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>رقم الكفالة</th>
                                    <th>تاريخ فترة التجربة</th>
                                    <th>الكفيل الاول (العميل)</th>
                                    <th>الكفيل الثاني (العميل)</th>
                                    <th>المكتب الخارجي</th>
                                    <th>العامل \ العاملة</th>
                                    <th>رقم الجواز</th>
                                    <th>رقم التأشيرة</th>
                                    <th>المهنة</th>
                                    <th>متبقي فترة التجربة</th>
                                    <th>تاريخ نقل الكفالة</th>
                                    <th>الحالة</th>
                                    <th>خيارات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($bails as $index=>$bail)
                                    <tr>
                                        <td>{{ $bail->id }}</td>
                                        <td>{{ $bail->trail_date }}</td>
                                        <td>{{ $bail->x_customer->name }}</td>
                                        <td>{{ $bail->customer->name }}</td>
                                        <td>{{ $bail->cv->office ? $bail->cv->office->name : '' }}</td>
                                        <td>{{ $bail->cv->name }}</td>
                                        <td>{{ $bail->cv->passport }}</td>
                                        <td>{{ $bail->contract->visa }}</td>
                                        <td>{{ $bail->cv->profession ? $bail->cv->profession->name : '' }}</td>
                                        <td>{{ $bail->display_remain_period_in_days }}</td>
                                        <td>{{ $bail->bail_date }}</td>
                                        <td>{{ $bail->display_status }}</td>
                                        <td>
                                            <div class="dropdown d-inline-block">
                                                <button class="btn btn-default btn-xs dropdown-toggle" type="button" data-toggle="dropdown">
                                                    <span>المزيد</span>
                                                    <span class="caret"></span>
                                                </button>
                                                <div class="dropdown-menu">
                                                    @permission('bails-read')
                                                    <a class="dropdown-item text-info" href="{{ route('bails.show', $bail) }}">
                                                        <i class="fa fa-eye"></i>
                                                        <span>عرض</span>
                                                    </a>
                                                    @endpermission
                                                    @permission('bails-update')
                                                    <a class="dropdown-item text-primary" href="{{ route('bails.edit', $bail->id) }}"><i class="fa fa-edit"></i> تعديل</a>
                                                    @endpermission
                                                    @permission('bails-delete')
                                                        <a href="#" class="dropdown-item text-danger"
                                                            data-toggle="confirm" data-form="#delete-form-{{ $bail->id }}"
                                                            data-title="حذف الكفالة"
                                                            data-text="سوف يتم حذف الكفالة نهائيا من النظام استمرار؟"
                                                            >
                                                            <i class="fa fa-trash"></i>
                                                            <span>حذف</span>
                                                        </a>
                                                    @endpermission
                                                    @permission('bails-delete')
                                                    <form id="delete-form-{{ $bail->id }}" style="display: none" action="{{ route('bails.destroy', $bail) }}" method="post">
                                                        @csrf 
                                                        @method('DELETE')
                                                        <input type="hidden" name="operation" value="delete"/>
                                                    </form>
                                                    @endpermission
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
@endsection
@push('foot')
    <script>
        $(function(){
            /*
            let table_filtered = $('#datatable').DataTable({
                'ordering': true,
            });
            $('select#status').change(function(){
                var value = $(this).val();
                console.log(table_filtered, this, value)
                if(value != 'all'){
                    table_filtered
                        .columns(9)
                        .search(value)
                        .order( 'asc')
                        .draw();
                }else{
                    $('#datatable').dataTable().fnFilterClear()
                }
            })
            */

            // $('table#datatable').DataTable( {
            //     initComplete: function () {
            //         this.api().columns().every( function () {
            //             var column = this;
            //             var select = $('<select><option value=""></option></select>')
            //                 .appendTo( $(column.footer()).empty() )
            //                 .on( 'change', function () {
            //                     var val = $.fn.dataTable.util.escapeRegex(
            //                         $(this).val()
            //                     );
        
            //                     column
            //                         .search( val ? '^'+val+'$' : '', true, false )
            //                         .draw();
            //                 } );
        
            //             column.data().unique().sort().each( function ( d, j ) {
            //                 select.append( '<option value="'+d+'">'+d+'</option>' )
            //             } );
            //         } );
            //     }
            // } );
        })
    </script>
@endpush