@extends('layouts.master', ['datatable' => true, 'modals' => ['attendance']])
@include('employee::sidebar')
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
                                    <h3 class="card-title float-left">قائمة الحضور</h3>
                                </div>
                                <div class="col-md-6">
                                    <h3 class="card-title float-right">
                                        <button class="btn btn-primary btn-sm attendance" data-toggle="modal" data-target="#attendanceModal"><i class="fa fa-plus"></i> اضافة</button>
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
                                        <th>وقت الحضور</th>
                                        <th>الخيارات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($attendance as $index=>$attendance)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $attendance->employee->name }}</td>
                                            <td>{{ \Carbon\Carbon::createFromFormat('H:i:s', $attendance->time_in)->format('h:i')  }}</td>
                                            <td>
                                                <button class="btn btn-danger btn-sm attendance update" data-action="{{ route('attendance.update', $attendance->id) }}"  data-employee="{{ $attendance->employee_id }}" data-time-in="{{ $attendance->time_in }}"  data-toggle="modal" data-target="#attendanceModal"><i class="fa fa-exit"></i> مغادرة</button>
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
