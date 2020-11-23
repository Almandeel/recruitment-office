@extends('layouts.master', ['datatable' => true, 'modals' => ['marketer', 'customer', 'complaint']])

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card text-center">
                    <div class="card-header">
                        <h3 class="card-title">قائمة المسوقين</h3>
                        @permission('marketers-create')
                        <h3 class="card-title float-right"><button class="btn btn-primary btn-sm marketers" data-toggle="modal" data-target="#marketerModal"><i class="fa fa-plus"></i> اضافة </button></h3>
                        @endpermission
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="datatable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>الاسم </th>
                                    <th>رقم الهاتف</th>
                                    <th>الخيارات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($marketers as $marketer)
                                    <tr>
                                        <td>{{ $marketer->id }}</td>
                                        <td>{{ $marketer->name }}</td>
                                        <td>{{ $marketer->phone }}</td>
                                        <td>
                                            @permission('marketers-update')
                                            <button class="btn btn-warning btn-sm marketers update"
                                                data-action="{{ route('servicesmarketers.update', $marketer->id) }}"
                                                data-name="{{ $marketer->name }}"
                                                data-phone="{{ $marketer->phone }}"
                                                data-toggle="modal"
                                                data-target="#marketerModal"
                                            ><i class="fa fa-edit"></i> تعديل </button>
                                            @endpermission
                                            @permission('marketers-read')
                                            <a href="{{ route('servicesmarketers.show', $marketer->id) }}" class="btn btn-info btn-sm"><i class="fa fa-eye"></i> عرض</a>
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
