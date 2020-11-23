@extends('externaloffice::layouts.master', ['datatable' => true, 'external_office_modals' => ['country']])

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card text-center">
                    <div class="card-header">
                        <div class="row">
                            <h3 class="card-title">Countries list</h3>
                            @permission('countries-read')
                            <h3 class="card-tool"><button class="btn btn-primary btn-sm countries" data-toggle="modal" data-target="#countryModal"><i class="fa fa-plus"></i> New</button></h3>
                            @endpermission
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="datatable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Options</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($countries as $country)
                                    <tr>
                                        <td>1</td>
                                        <td>{{ $country->name }}</td>
                                        <td>
                                            @permission('countries-read')
                                            <!-- <a class="btn btn-info btn-sm" href="{{ route('countries.show', $country->id) }}"><i class="fa fa-eye"></i> Show</a> -->
                                            @endpermission
                                            @permission('countries-read')
                                            <button class="btn btn-warning btn-sm countries update"
                                                data-action="{{ route('countries.update', $country->id) }}"
                                                data-name="{{ $country->name }}"
                                                data-toggle="modal"
                                                data-target="#countryModal"
                                            ><i class="fa fa-edit"></i>Edit</button>
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
