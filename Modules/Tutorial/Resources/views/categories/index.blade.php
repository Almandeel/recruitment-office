@extends('layouts.master', [
    'datatable' => true, 
    'modals' => ['category'],
    'title' => 'الاقسام',
    'crumbs' => [
        ['#', 'الاقسام'],
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
                                    <h3 class="card-title float-left">قائمة الاقسام</h3>
                                </div>
                                <div class="col-md-6">
                                    <h3 class="card-title float-right">
                                        <button class="btn btn-primary btn-sm categories" data-toggle="modal" data-target="#categoryModal"><i class="fa fa-plus"></i> إضافة</button> 
                                    </h3>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body text-center">
                            <table id="datatable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>الاسم</th>
                                        <th>الخيارات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($categories as $index=>$category)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $category->name }}</td>
                                            <td>
                                                @permission('categories-update')
                                                    <button class="btn btn-warning btn-ms update categories" data-toggle="modal" data-target="#categoryModal" type="button" data-action="{{ route('categories.update', $category->id) }}" data-id="{{ $category->id }}"  data-name="{{ $category->name }}" >
                                                        <i class="fa fa-edit"></i>
                                                        <span>تعديل</span>
                                                    </button>
                                                @endpermission

                                                @permission('categories-delete')
                                                    <a href="#" class="delete btn btn-danger btn-sm" data-form="#deleteForm-{{ $category->id }}">
                                                        <i class="fa fa-trash"></i>
                                                        <span>حذف</span>
                                                    </a>

                                                    <form id="deleteForm-{{ $category->id }}" action="{{ route('categories.destroy', $category) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
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
            </div>
        </section>
        <!-- /.content -->    
@endsection


@push('foot')
   
@endpush
