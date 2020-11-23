@extends('externaloffice::layouts.master', ['datatable' => true, 'external_office_modals' => ['marketer']])

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card text-center">
                    <div class="card-header">
                        <h3 class="card-title">Marketers list</h3>
                        @permission('marketers-show')
                        <h3 class="card-tool"><button class="btn btn-primary btn-sm marketers" data-toggle="modal" data-target="#marketerModal"><i class="fa fa-plus"></i> New</button></h3>
                        @endpermission
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="datatable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Phone Number</th>
                                    <th>Options</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($marketers as $marketer)
                                    <tr>
                                        <td>1</td>
                                        <td>{{ $marketer->name }}</td>
                                        <td>{{ $marketer->phone }}</td>
                                        <td>
                                            @permission('marketers-show')
                                            <!-- <a class="btn btn-info btn-sm" href="{{ route('marketers.show', $marketer->id) }}"><i class="fa fa-eye"></i> Show</a> -->
                                            @endpermission
                                            @permission('marketers-show')
                                            <button class="btn btn-warning btn-sm marketers update"
                                                data-action="{{ route('marketers.update', $marketer->id) }}"
                                                data-name="{{ $marketer->name }}"
                                                data-phone="{{ $marketer->phone }}"
                                                data-toggle="modal"
                                                data-target="#marketerModal"
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
