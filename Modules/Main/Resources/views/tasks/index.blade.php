@extends('layouts.master', ['datatable' => true, 'modals' => ['task']])

@push('head')
    
@endpush


@section('content')
        <section class="content">
            <div class="row">
                <div class="col-12">
                    <div class="card text-center">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-6">
                                    <h3 class="card-title float-right">قائمة المهام</h3>
                                </div>
                                <div class="col-md-6">
                                    <h3 class="card-title float-left">
                                        <button class="btn btn-primary btn-sm tasks" data-toggle="modal" data-target="#taskModal"><i class="fa fa-plus"></i> إضافة</button>
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
                                        <th>الموظفين</th>
                                        <th>تاريخ الانشاء</th>
                                        <th>الخيارات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($tasks as $index=>$task)
                                        @if($task->task->status == 0)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $task->task->name }}</td>
                                                <td>
                                                    @foreach($task->task->taskuser as $user)
                                                        <i style="font-size:14px;" class="badge badge-success">{{ $user->user->name }}</i>
                                                    @endforeach
                                                </td>
                                                <td>{{ $task->created_at->format('Y-m-d') }}</td>
                                                <td>
                                                    <form style="display:inline-block" action="{{ route('tasks.update', $task->task->id) }}?type=done" method="post">
                                                        @csrf 
                                                        @method('PUT')
                                                        <button type="submit" class="btn btn-success btn-sm" href=""><i class="fa fa-list"></i> تم الانجاز</button>
                                                    </form>
                                                    <button class="btn btn-warning btn-sm tasks update"  data-action="{{ route('tasks.update', $task->task->id) }}"  data-name="{{ $task->task->name }}" data-users="{{ $task->task->taskuser }}"  data-toggle="modal" data-target="#taskModal"><i class="fa fa-edit"></i> تعديل</button>
                                                </td>
                                            </tr>
                                        @endif
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


@include('partials.select2')
@endpush
