@extends('layouts.master', [
    'title' => 'الشكاوى',
    'datatable' => true, 
    'modals' => ['customer', 'complaint'],
    'crumbs' => [
        ['#', 'الشكاوى'],
    ]
])
@push('head')
    
@endpush


@section('content')
        <section class="content">
            <div class="row">
                <div class="col-12">
                    <div class="card text-right">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-6">
                                    <h3 class="card-title float-left">قائمة الشكاوى </h3>
                                </div>
                                <div class="col-md-6">
                                    @permission('complaints-create')
                                    <h3 class="card-title float-right">
                                        <a href="{{ route('complaints.create') }}" class="btn btn-primary btn-sm" ><i class="fa fa-plus"></i> اضافة </a>
                                    </h3>
                                    @endpermission
                                </div>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body text-center">
                        @component('components.tabs')
                            @slot('items')
                                @component('components.tab-item')
                                    @slot('active', true)
                                    @slot('id', 'active')
                                    @slot('title', 'الشكاوي الجديدة')
                                @endcomponent
                                @component('components.tab-item')
                                    @slot('id', 'deactive')
                                    @slot('title', 'الشكاوي المنتهية')
                                @endcomponent
                            @endslot
                            @slot('contents')
                                @component('components.tab-content')
                                    @slot('active', true)
                                    @slot('id', 'active')
                                    @slot('content')
                                        <table id="datatable" class="datatable table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>الشاكي</th>
                                                    <th>رقم الهاتف</th>
                                                    <th>العامل \ العاملة</th>
                                                    <th>رقم الجواز</th>
                                                    <th>التاريخ</th>
                                                    <th>الخيارات</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($complaints->where('status', 0) as $index=>$complaint)
                                                        <tr>
                                                            <td>{{ $index + 1 }}</td>
                                                            <td>{{ $complaint->customer->name }}</td>
                                                            <td>{{ $complaint->customer->phones }}</td>
                                                            <td>{{ $complaint->cv->name ?? '' }}</td>
                                                            <td>{{ $complaint->cv->passport ?? '' }}</td>
                                                            <td>{{ $complaint->created_at->format('Y-m-d') }}</td>
                                                            @permission('complaints-read')
                                                                <td>
                                                                    <a href="{{ route('complaints.show', $complaint->id) }}" class="btn btn-default btn-sm"><i class="fa fa-eye"></i> عرض</a>
                                                                </td>
                                                            @endpermission
                                                        </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @endslot
                                @endcomponent
                                @component('components.tab-content')
                                    @slot('id', 'deactive')
                                    @slot('content')
                                    <table id="datatable" class="datatable table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>الشاكي</th>
                                                <th>رقم الهاتف</th>
                                                <th>العامل \ العاملة</th>
                                                <th>رقم الجواز</th>
                                                <th>التاريخ</th>
                                                <th>الخيارات</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($complaints->where('status', 1) as $index=>$complaint)
                                                    <tr>
                                                        <td>{{ $index + 1 }}</td>
                                                        <td>{{ $complaint->customer->name }}</td>
                                                        <td>{{ $complaint->customer->phones }}</td>
                                                        <td>{{ $complaint->cv->name ?? '' }}</td>
                                                        <td>{{ $complaint->cv->passport ?? '' }}</td>
                                                        <td>{{ $complaint->created_at->format('Y-m-d') }}</td>
                                                        @permission('complaints-read')
                                                            <td>
                                                                <a href="{{ route('complaints.show', $complaint->id) }}" class="btn btn-default btn-sm"><i class="fa fa-eye"></i> عرض</a>
                                                            </td>
                                                        @endpermission
                                                    </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    @endslot
                                @endcomponent
                            @endslot
                        @endcomponent
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
