@extends('externaloffice::layouts.master', ['datatable' => true, 'external_office_modals' => ['advance']])

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card text-center">
                    <div class="card-header">
                        <h3 class="card-title">Advances list</h3>
                        @permission('advances-create')
                        <h3 class="card-tools"><button class="btn btn-primary btn-sm advances" data-toggle="modal" data-target="#advanceModal"><i class="fa fa-plus"></i> New</button></h3>
                        @endpermission
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="datatable" class="table table-bordered table-striped datatable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Created By</th>
                                    <th>Date</th>
                                    <th>Options</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($advances as $advance)
                                    <tr>
                                        <td>{{ $advance->id }}</td>
                                        <td>{{ $advance->amount }}</td>
                                        <td>
                                            @if ($advance->status == 0)
                                                <span class="text-warning">On waiting</span>
                                            @elseif ($advance->status == 1)
                                                <span class="text-success">Accepted</span>
                                            @elseif ($advance->status == 2)
                                                <span class="text-danger">Canceled</span>
                                            @endif
                                        </td>
                                        <td>{{ $advance->user->name }}</td>
                                        <td>{{ $advance->created_at->format('Y-m-d') }}</td>
                                        <td>
                                            @permission('advances-read')
                                            <a class="btn btn-info btn-sm" href="{{ route('advances.show', $advance) }}"><i class="fa fa-eye"></i> Show</a>
                                            @endpermission
                                            @if ($advance->status == 0)
                                                @permission('advances-update')
                                                <button class="btn btn-warning btn-sm advance update"
                                                    data-action="{{ route('advances.update', $advance->id) }}"
                                                    data-amount="{{ $advance->amount }}"
                                                    data-toggle="modal"
                                                    data-target="#advanceModal"
                                                ><i class="fa fa-edit"></i>Edit</button>
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
@endsection
