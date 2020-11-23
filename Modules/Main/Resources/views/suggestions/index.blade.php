@extends('layouts.master', ['datatable' => true, 'modals' => ['suggestion']])

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
                                    <h3 class="card-title float-right">قائمة الاقتراحات</h3>
                                </div>
                                <div class="col-md-6">
                                    <h3 class="card-title float-left">
                                        <button class="btn btn-primary btn-sm suggestions" data-toggle="modal" data-target="#suggestionModal"><i class="fa fa-plus"></i> إضافة</button>
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
                                        <th>الاقتراح</th>
                                        <th>الموظف</th>
                                        <th>تاريخ الانشاء</th>
                                        <th>الخيارات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($suggestions as $index=>$suggestion)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ substr(strip_tags($suggestion->content), 0, 50)}} ...</td>
                                            <td>{{ $suggestion->user->name }}</td>
                                            <td>{{ $suggestion->created_at->format('Y-m-d') }}</td>
                                            <td>
                                                <button class="btn btn-default btn-sm suggestions preview"   data-content="{{ $suggestion->content }}" data-user="{{ $suggestion->user->name }}"  data-toggle="modal" data-target="#taskModal"><i class="fa fa-eye"></i> عرض</button>
                                                
                                                <form style="display:inline-block" action="{{ route('suggestions.update', $suggestion->id) }}" method="post">
                                                    @csrf 
                                                    @method('put')
                                                    <button type="submit" class="btn btn-success btn-sm" href=""><i class="fa fa-list"></i>  مفيد</button>
                                                </form>

                                                <form style="display:inline-block" action="{{ route('suggestions.destroy', $suggestion->id) }}" method="post">
                                                    @csrf 
                                                    @method('delete')
                                                    <button type="submit" class="btn btn-danger btn-sm" href=""><i class="fa fa-list"></i> غير مفيد</button>
                                                </form>
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


@include('partials.select2')
@endpush
