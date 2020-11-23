@extends('layouts.master', [
    'title' => 'الوظائف',
    'datatable' => true, 
    'modals' => ['position'], 
    'crumbs' => [
        ['#', 'الوظائف'],
    ]
])
@include('employee::sidebar')
@push('head')
    
@endpush


@section('content')
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card text-right">
                    <div class="card-header">
                        <h3 class="card-title">قائمة الوظائف</h3>
                        <button class="btn btn-primary btn-sm pull-left positions" data-toggle="modal" data-target="#positionModal"><i class="fa fa-plus"></i> اضافة</button>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="datatable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>المعرف</th>
                                    <th>الاسم</th>
                                    <th>الخيارات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($positions as $index=>$position)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $position->title }}</td>
                                        <td>
                                            <a class="btn btn-info btn-xs" href="{{ route('positions.show', $position->id) }}">
                                                <i class="fa fa-eye"></i>
                                                <span class="d-none d-md-inline">عرض</span>
                                            </a>
                                            @permission('positions-update')
                                            <button class="btn btn-warning btn-xs positions update" data-action="{{ route('positions.update', $position->id) }}"
                                                data-title="{{ $position->title }}" data-toggle="modal" data-target="#positionModal"><i class="fa fa-edit"></i>
                                                تعديل</button>
                                            @endpermission
                                            @permission('positions-delete')
                                            <button type="button" data-form="#deleteForm-{{ $position->id }}" class="btn btn-danger btn-xs delete"><i class="fa fa-trash"></i>
                                                <span>حذف</span> </button>
                                            <form id="deleteForm-{{ $position->id }}" action="{{ route('positions.destroy', $position->id) }}" method="POST">
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
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->    
@endsection


@push('foot')
   
@endpush
