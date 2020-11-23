@extends('layouts.master', [
    'datatable' => true, 
    'modals' => ['vacation'],
    'datatable' => true, 
    'title' => 'الاجازات',
    'crumbs' => [
        ['#', 'الاجازات'],
    ]
])

@push('head')@endpush


@section('content')
        <!-- Content Header (Page header) -->
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-12">
                    <div class="card text-center">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-6">
                                    <h3 class="card-title float-left">قائمة الاجازات</h3>
                                </div>
                                <div class="col-md-6">
                                    <h3 class="card-title float-right">
                                        <a href="{{ route('vacations.create') }}" class="btn btn-primary btn-ms"><i class="fa fa-plus"></i> اضافة</a>
                                    </h3>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="datatable" class="datatable table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>الموظف</th>
                                        <th> الاجازة</th>
                                        <th>بداية الاجازة</th>
                                        <th>نهاية الاجازة</th>
                                        <th>الخيارات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($vacations as $index=>$vacation)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $vacation->employee->name }}</td>
                                            <td>{{ $vacation->title }}</td>
                                            <td>{{ \Carbon\Carbon::createFromFormat('Y-m-d', $vacation->started_at)->format('Y-m-d')  }}</td>
                                            <td>{{ \Carbon\Carbon::createFromFormat('Y-m-d', $vacation->ended_at)->format('Y-m-d')  }}</td>
                                            <td>
                                                @if($vacation->accepted == 0)
                                                    @permission('vacations-read')
                                                    <a href="{{ route('vacations.show', $vacation) }}" class="btn btn-info btn-xs">
                                                        <i class="fa fa-eye"></i>
                                                        <span class="d-sm-none d-lg-inline">عرض</span>
                                                    </a>
                                                    @endpermission

                                                    @permission('vacations-update')
                                                        <a href="{{ route('vacations.edit', $vacation) }}" class="btn btn-warning btn-xs">
                                                            <i class="fa fa-edit"></i>
                                                            <span class="d-sm-none d-lg-inline-block">تعديل</span>
                                                        </a>
                                                    @endpermission
                                                    @permission('vacations-delete')
                                                        <button type="type" class="btn btn-danger btn-xs delete" data-form="#deleteForm-{{ $vacation->id }}">
                                                            <i class="fa fa-times"></i>
                                                            <span class="d-sm-none d-lg-inline-block">حذف</span>
                                                        </button>
                                                        <form id="deleteForm-{{ $vacation->id }}" style="display:inline-block" action="{{ route('vacations.destroy', $vacation->id) }}" method="post">
                                                            @csrf 
                                                            @method('DELETE')
                                                        </form>
                                                    @endpermission
                                                @endif
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
        <!-- /.content -->    
@endsection


@push('foot')
   
@endpush
