@extends('layouts.master', [
    'datatable' => true, 
    'confirm_status' => true,
    'modals' => ['employee'],
    'title' => 'عهد الموظف: ' . $employee->name,
    'crumbs' => [
        ['#', 'عهد الموظف: ' . $employee->name],
    ]
])

@push('head')
    
@endpush


@section('content')
        <!-- Content Header (Page header) -->
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-6">
                                    <h3 class="card-title float-left">قائمة العهد</h3>
                                </div>
                                <div class="col-md-6">
                                    <h3 class="card-title float-right">
                                        <a class="btn btn-primary btn-sm" href="{{ route('custodies.create') }}"><i class="fa fa-plus"></i> إضافة</a>
                                        {{--  <button class="btn btn-primary btn-sm custodies" data-toggle="modal" data-target="#custodyModal"><i class="fa fa-plus"></i> إضافة</button>  --}}
                                    </h3>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-extra">
                        <form action="" method="GET" class="form-inline guide-advanced-search">
                            @csrf
                            <div class="form-group mr-2">
                                <label>
                                    <i class="fa fa-cogs"></i>
                                    <span>@lang('accounting::global.search_advanced')</span>
                                </label>
                            </div>
                            <div class="form-group mr-2">
                                <label for="status">@lang('global.status')</label>
                                <select class="form-control status" name="status" id="status">
                                    <option value="all" {{ ($status == 'all') ? 'selected' : '' }}>@lang('global.all')</option>
                                    @foreach (__('custodies.statuses') as $value => $key)
                                    <option value="{{ $value }}" {{ $value == $status ? 'selected' : '' }}>
                                        {{ $key }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group mr-2">
                                <label for="from-date">@lang('accounting::global.from')</label>
                                <input type="date" name="from_date" id="from-date" value="{{ $from_date }}" class="form-control">
                            </div>
                            <div class="form-group mr-2">
                                <label for="to-date">@lang('accounting::global.to')</label>
                                <input type="date" name="to_date" id="to-date" value="{{ $to_date }}" class="form-control">
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <span>@lang('accounting::global.search')</span>
                                <i class="fa fa-search"></i>
                            </button>
                        </form>
                        </div>
                        <div class="card-body text-center">
                            <table id="datatable" class="datatable table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>المعرف</th>
                                        <th>المبلغ</th>
                                        <th>الحالة</th>
                                        <th>المسؤول</th>
                                        <th>التاريخ</th>
                                        <th>الخيارات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($custodies as $custody)
                                        <tr>
                                            <td>{{ $custody->id }}</td>
                                            <td>{{ $custody->formated_amount }}</td>
                                            <td>{{ $custody->displayStatus() }}</td>
                                            <td>{{ $custody->user->name }}</td>
                                            <td>{{ $custody->created_at->format('Y/m/d') }}</td>
                                            <td>
                                                <div class="btn-group">
                                                    @permission('custodies-read')
                                                    <a href="{{ route('custodies.show', $custody) }}" class="btn btn-info">
                                                        <i class="fa fa-eye"></i>
                                                        <span>@lang('global.show')</span>
                                                    </a></li>
                                                    @endpermission
                                                    @permission('custodies-update')
                                                    <a href="{{ route('custodies.edit', $custody) }}" class="btn btn-warning">
                                                        <i class="fa fa-edit"></i>
                                                        <span>تعديل</span>
                                                    </a></li>
                                                    @endpermission
                                                    @permission('custodies-delete')
                                                    <a href="#" class="btn btn-danger delete" data-form="#deleteForm-{{ $custody->id }}">
                                                        <i class="fa fa-trash"></i>
                                                        <span>حذف</span>
                                                    </a>
                                                    @endpermission
                                                </div>
                                                @permission('custodies-delete')
                                                <form id="deleteForm-{{ $custody->id }}" action="{{ route('custodies.destroy', $custody) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                                @endpermission
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
            </div>
        </section>
        <!-- /.content -->    
@endsection


@push('foot')
   
@endpush
