@extends('layouts.master', ['datatable' => true, 'modals' => ['country']])

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card text-center">
                    <div class="card-header">
                        <h3 class="card-title">قائمة الدول</h3>
                        @permission('countries-create')
                        <h3 class="card-title float-right"><button class="btn btn-primary btn-sm countries" data-toggle="modal" data-target="#countryModal"><i class="fa fa-plus"></i> اضافة </button></h3>
                        @endpermission
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="datatable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>الاسم</th>
                                    <th>الخيارات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($countries as $country)
                                    <tr>
                                        <td>1</td>
                                        <td>{{ $country->name }}</td>
                                        <td>
                                            @permission('countries-update')
                                            <!-- <a class="btn btn-info btn-sm" href="{{ route('maincountries.show', $country->id) }}"><i class="fa fa-eye"></i> Show</a> -->
                                            <button class="btn btn-warning btn-sm countries update"
                                                data-action="{{ route('maincountries.update', $country->id) }}"
                                                data-name="{{ $country->name }}"
                                                data-toggle="modal"
                                                data-target="#countryModal"
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
