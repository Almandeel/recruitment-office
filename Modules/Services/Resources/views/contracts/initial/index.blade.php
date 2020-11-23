@extends('layouts.master', [
    'title' => 'العقود',
    'modals' => ['customer'],
    'datatable' => true,
    'crumbs' => [
        ['#', 'العقود'],
    ]
])

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">قائمة العقود</h3>
                        @permission('contracts-create')
                        <a class="card-title float-right btn btn-primary" href="{{ route('contracts.create') }}"><i class="fa fa-plus"></i>  اضافة </a>
                        @endpermission
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
                                {{--  <div class="form-group mr-2">
                                    <label for="status">@lang('global.status')</label>
                                    <select class="form-control status" name="status" id="status">
                                        <option value="all" {{ ($status == 'all') ? 'selected' : '' }}>@lang('global.all')
                                        </option>
                                        @foreach (__('contracts.statuses') as $value => $key)
                                        <option value="{{ $value }}" {{ $value == $status ? 'selected' : '' }}>
                                            {{ $key }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>  --}}
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
                        <table id="datatable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>تاريخ اختيار العاملة</th>
                                    <th>العميل</th>
                                    <th>المكتب الخارجي</th>
                                    <th>العامل \ العاملة</th>
                                    <th>رقم الجواز</th>
                                    <th>رقم التأشيرة</th>
                                    <th>المهنة</th>
                                    <th>المدة المتبقية</th>
                                    <th>الحالة</th>
                                    <th>خيارات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($contracts as $index=>$contract)
                                    @php 
                                        $start_time = \Carbon\Carbon::parse($contract->start_date);
                                        $finish_time = \Carbon\Carbon::parse(date('Y-m-d'));
                                        $result = $start_time->diffInDays($finish_time, false);
                                    @endphp
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $contract->updated_at->format('Y-m-d') }}</td>
                                        <td>{{ $contract->getCustomerName() }}</td>
                                        <td>{{ $contract->getOfficeName() }}</td>
                                        <td>{{ $contract->getCvName() }}</td>
                                        <td>{{ $contract->getCvPassport() }}</td>
                                        <td>{{ $contract->visa }}</td>
                                        <td>{{ $contract->getProfessionName() }}</td>
                                        <td>{{ $contract->getApplicationDays(true, true) }}</td>
                                        <td>{{ $contract->displayStatus() }}</td>
                                        <td>
                                            <div class="btn-group">
                                                @permission('contracts-read')
                                                <a class="btn btn-info" href="{{ route('contracts.show', $contract) }}"><i class="fa fa-eye"></i> عرض</a>
                                                @endpermission
                                                @permission('contracts-update')
                                                <a class="btn btn-primary contracts update" href="{{ route('contracts.edit', $contract->id) }}"><i class="fa fa-edit"></i> تعديل</a>
                                                @endpermission
                                                @if (!$contract->isCanceled())
                                                    @permission('contracts-delete')
                                                        <button type="button" class="btn btn-warning"
                                                            data-toggle="confirm" data-form="#cancel-form-{{ $contract->id }}"
                                                            data-title="إلغاء العقد"
                                                            data-text="سوف يتم إلغاء العقد وفسخ الارتباط مع ال cv إستمرار"
                                                            >
                                                            <i class="fa fa-times"></i>
                                                            <span>إلغاء</span>
                                                        </button>
                                                    @endpermission
                                                @endif
                                                @permission('contracts-delete')
                                                    <button type="button" class="btn btn-danger"
                                                        data-toggle="confirm" data-form="#delete-form-{{ $contract->id }}"
                                                        data-title="حذف العقد"
                                                        data-text="سوف يتم حذف العقد نهائيا من النظام استمرار؟"
                                                        >
                                                        <i class="fa fa-trash"></i>
                                                        <span>حذف</span>
                                                    </button>
                                                @endpermission
                                            </div>
                                            @if (!$contract->isCanceled())
                                                @permission('contracts-delete')
                                                <form id="cancel-form-{{ $contract->id }}" style="display: inline-block" action="{{ route('contracts.destroy', $contract->id) }}" method="post">
                                                    @csrf 
                                                    @method('DELETE')
                                                    <input type="hidden" name="operation" value="cancel"/>
                                                </form>
                                                @endpermission
                                            @endif
                                            @permission('contracts-delete')
                                            <form id="delete-form-{{ $contract->id }}" style="display: inline-block" action="{{ route('contracts.destroy', $contract->id) }}" method="post">
                                                @csrf 
                                                @method('DELETE')
                                                <input type="hidden" name="operation" value="delete"/>
                                            </form>
                                            @endpermission
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>تاريخ اختيار العاملة</th>
                                    <th>العميل</th>
                                    <th>المكتب الخارجي</th>
                                    <th>العامل \ العاملة</th>
                                    <th>رقم الجواز</th>
                                    <th>رقم التأشيرة</th>
                                    <th>المهنة</th>
                                    <th>المدة المتبقية</th>
                                    <th>الحالة</th>
                                    <th>خيارات</th>
                                </tr>
                            </tfoot>
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

            $('table#datatable').DataTable( {
                initComplete: function () {
                    this.api().columns().every( function () {
                        var column = this;
                        var select = $('<select><option value=""></option></select>')
                            .appendTo( $(column.footer()).empty() )
                            .on( 'change', function () {
                                var val = $.fn.dataTable.util.escapeRegex(
                                    $(this).val()
                                );
        
                                column
                                    .search( val ? '^'+val+'$' : '', true, false )
                                    .draw();
                            } );
        
                        column.data().unique().sort().each( function ( d, j ) {
                            select.append( '<option value="'+d+'">'+d+'</option>' )
                        } );
                    } );
                }
            } );
        })
    </script>
@endpush