@extends('layouts.master', ['datatable' => true, 'modals' => ['profession']])

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card text-center">
                    <div class="card-header">
                        <h3 class="card-title">قائمة المهن</h3>
                        @permission('professions-create')
                        <h3 class="card-title float-right"><button class="btn btn-primary btn-sm professions" data-toggle="modal" data-target="#professionModal"><i class="fa fa-plus"></i>  اضافة </button></h3>
                        @endpermission
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="datatable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>الاسم عربي</th>
                                    <th>الاسم انجليزي </th>
                                    <th>خيارات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($professions as $profession)
                                    <tr>
                                        <td>1</td>
                                        <td>{{ $profession->name }}</td>
                                        <td>{{ $profession->name_en }}</td>
                                        <td>
                                            @permission('professions-update')
                                            <!-- <a class="btn btn-info btn-sm" href="{{ route('mainprofessions.show', $profession->id) }}"><i class="fa fa-eye"></i> Show</a> -->
                                            <button class="btn btn-warning btn-sm professions update"
                                                data-action="{{ route('mainprofessions.update', $profession->id) }}"
                                                data-name="{{ $profession->name }}"
                                                data-name_en="{{ $profession->name_en }}"
                                                data-toggle="modal"
                                                data-target="#professionModal"
                                            ><i class="fa fa-edit"></i> تعديل </button>
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
@endsection
