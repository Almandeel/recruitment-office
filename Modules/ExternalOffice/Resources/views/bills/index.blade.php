@extends('externaloffice::layouts.master', ['datatable' => true])

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Bills list</h3>
                        @permission('bills-create')
                        <a class=" btn btn-primary btn-sm" href="{{ route('cvs.bills.create') }}"><i class="fa fa-plus"></i> New</a>
                        @endpermission
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="datatable" class="table table-bordered table-striped datatable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Total Amoun</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Options</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($bills as $bill)
                                    <tr>
                                        <td>{{ $bill->id }}</td>
                                        <td>{{ $bill->amount }}</td>
                                        <td>{{ $bill->created_at }}</td>
                                        <td>{{ $bill->displayStatus() }}</td>
                                        <td>
                                            @permission('bills-read')
                                            <a class="btn btn-info btn-sm" href="{{ route('cvs.bills.show', $bill) }}">
                                                <i class="fa fa-eye"></i> Show
                                            </a>
                                            @endpermission
                                            {{-- @permission('bills-update')
                                            <a class="btn btn-warning btn-sm bills" href="{{ route('cvs.bills.edit', $bill) }}"><i class="fa fa-edit"></i>Edit</a>
                                            @endpermission --}}
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
