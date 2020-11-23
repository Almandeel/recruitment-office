@extends('layouts.master', [
    'title' => 'المكاتب الخارجية',
    'datatable' => true,
    'crumbs' => [
        ['#', 'المكاتب الخارجية'],
    ]
])


@section('content')
    <section class="content">
        <div class="card text-center">
            <div class="card-header">
                <h3 class="card-title text-right d-inline-block">المكاتب الخارجية</h3>
                <a class="card-tool btn btn-primary btn-sm offices float-right" href="{{ route('offices.create') }}"><i class="fa fa-plus"></i> إضافة</a>
            </div>
            @component('components.tabs')
                @slot('items')
                    @component('components.tab-item')
                        @slot('active', true)
                        @slot('id', 'active')
                        @slot('title', 'قائمة المكاتب الخارجية المعتمدة')
                    @endcomponent
                    @component('components.tab-item')
                        @slot('id', 'deactive')
                        @slot('title', 'قائمة المكاتب الخارجية غير المعتمدة')
                    @endcomponent
                @endslot
                @slot('contents')
                    @component('components.tab-content')
                        @slot('active', true)
                        @slot('id', 'active')
                        @slot('content')
                            <table class="table table-bordered table-striped datatable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>الدولة</th>
                                        <th>الإسم</th>
                                        <th>الإيميل</th>
                                        <th>المشرف</th>
                                        <th>الخيارات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($offices->where('status', 1) as $office)
                                        <tr>
                                            <td>{{ $loop->index + 1 }}</td>
                                            <td>{{ $office->country->name ?? '-' }}</td>
                                            <td>{{ $office->name }}</td>
                                            <td>{{ $office->email }}</td>
                                            <td>{{ $office->admin->name ?? '-' }}</td>
                                            <td>
                                                <div class="dropdown d-inline-block">
                                                    <button class="btn btn-default btn-xs dropdown-toggle" type="button" data-toggle="dropdown">
                                                        <span>المزيد</span>
                                                        <span class="caret"></span>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item text-info" href="{{ route('offices.show', $office->id) }}"><i class="fa fa-eye"></i> عرض</a>
                                                        <a class="dropdown-item text-warning" href="{{ route('offices.edit', $office->id) }}"><i class="fa fa-edit"></i> تعديل</a>
                                                        <form class="d-inline-block" action="{{ route('offices.destroy', $office->id) }}" method="post">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button class="dropdown-item text-danger" type="submit"><i class="fa fa-retweet"></i>إلغاء التعاقد</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endslot
                    @endcomponent
                    @component('components.tab-content')
                        @slot('id', 'deactive')
                        @slot('content')
                            <table class="table table-bordered table-striped datatable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>الدولة</th>
                                        <th>الإسم</th>
                                        <th>الإيميل</th>
                                        <th>المشرف</th>
                                        <th>الخيارات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($offices->where('status', 0) as $deactiveOffice)
                                        <tr>
                                            <td>{{ $loop->index + 1 }}</td>
                                            <td>{{ $deactiveOffice->country->name ?? '-' }}</td>
                                            <td>{{ $deactiveOffice->name }}</td>
                                            <td>{{ $deactiveOffice->email }}</td>
                                            <td>{{ $deactiveOffice->admin->name ?? '-' }}</td>
                                            <td>
                                                <div class="dropdown d-inline-block">
                                                    <button class="btn btn-default btn-xs dropdown-toggle" type="button" data-toggle="dropdown">
                                                        <span>المزيد</span>
                                                        <span class="caret"></span>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item text-info" href="{{ route('offices.show', $deactiveOffice->id) }}"><i class="fa fa-eye"></i> عرض</a>
                                                        <a class="dropdown-item text-warning" href="{{ route('offices.edit', $deactiveOffice->id) }}"><i class="fa fa-edit"></i> تعديل</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endslot
                    @endcomponent
                @endslot
            @endcomponent
        </div>
    </section>
@endsection
