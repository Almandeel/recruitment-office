@extends('layouts.master', [
    'title' => 'قائمة رحلات الطيران',
    'datatable' => true,
    'crumbs' => [
        [route('services.flights.index'), 'رحلات الطيران'],
    ]
])

@section('content')
<section class="content">
    <div class="card">
        <div class="card-header">
            @permission('flights-create')
            <a href="{{ route('services.flights.create') }}" class="btn btn-primary float-right">إضافة</a>
            @endpermission
        </div>
        {{-- <div class="card-body"> --}}
            @component('components.tabs')
                @slot('items')
                    @component('components.tab-item')
                        @slot('active', true)
                        @slot('id', 'active')
                        @slot('title', 'قائمة الرحلات المعلقة')
                    @endcomponent
                    @component('components.tab-item')
                        @slot('id', 'deactive')
                        @slot('title', 'قائمة الرحلات المنتهية')
                    @endcomponent
                @endslot
                @slot('contents')
                    @component('components.tab-content')
                        @slot('active', true)
                        @slot('id', 'active')
                        @slot('content')
                            <table class="table table-bordered table-striped text-center datatable">
                                <thead>
                                    <th>#</th>
                                    <th>العميل </th>
                                    <th>اسم العامل / العاملة  </th>
                                    <th>دولة الاستقدام</th>
                                    <th>المكتب الخارجي </th>
                                    <th>مطار الوصول</th>
                                    <th>تاريخ الوصول</th>
                                    <th>وقت الوصول</th>
                                    <th>حاله الوصول</th>
                                    <th>تبليغ العميل</th>
                                    <th>خيارات</th>
                                </thead>
                                <tbody>
                                    @foreach ($flights as $flight)
                                        @foreach ($flight->passengers as $passenger)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $passenger->cv->contracts()->latest()->first()->customer->name ?? '-' }}</td>
                                                <td>{{ $passenger->cv->name }}</td>
                                                <td>{{ $flight->departure_airport }}</td>
                                                <td>{{ $flight->office->name ?? '-' }}</td>
                                                <td>{{ $flight->arrival_airport }}</td>
                                                <td>{{ $flight->arrival_at->format('d-m-Y') }}</td>
                                                <td>{{ $flight->arrival_at->format('H:i') }}</td>
                                                <td>
                                                    <span class="badge badge-info badge-bill">{{ $passenger->displayStatus() }}</span>
                                                </td>
                                                <td>
                                                    <span class="badge badge-info badge-bill">{{ $passenger->displayCustomerStatus() }}</span>
                                                </td>
                                                <td>
                                                    @permission('flights-read')
                                                    <a href="{{ route('services.flights.passengers.show', ['flight' => $flight, 'passenger' => $passenger]) }}" class="btn btn-xs btn-info"> <i class="fa fa-eye"></i> عرض</a>
                                                    @endpermission
                                                    @permission('flights-update')
                                                    <a href="{{ route('services.flights.edit', $flight) }}" class="btn btn-xs btn-warning"> <i class="fa fa-edit"></i> تعديل</a>
                                                    @endpermission
                                                    @permission('flights-read')
                                                    <a href="{{ route('services.flights.show', $flight) }}" class="btn btn-xs btn-default"> <i class="fa fa-plane"></i> تفاصيل الرحلة</a>
                                                    @endpermission
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        @endslot
                    @endcomponent
                    @component('components.tab-content')
                        @slot('id', 'deactive')
                        @slot('content')
                            <table class="table table-bordered table-striped text-center datatable">
                                <thead>
                                    <th>#</th>
                                    <th>العميل </th>
                                    <th>اسم العامل / العاملة  </th>
                                    <th>دولة الاستقدام</th>
                                    <th>المكتب الخارجي </th>
                                    <th>مطار الوصول</th>
                                    <th>تاريخ الوصول</th>
                                    <th>وقت الوصول</th>
                                    <th>حاله الوصول</th>
                                    <th>تبليغ العميل</th>
                                    <th>خيارات</th>
                                </thead>
                                <tbody>
                                    @foreach ($finshedFlights as $flight)
                                        @foreach ($flight->passengers as $passenger)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $passenger->cv->contracts()->latest()->first()->customer->name ?? '-' }}</td>
                                                <td>{{ $passenger->cv->name }}</td>
                                                <td>{{ $flight->departure_airport }}</td>
                                                <td>{{ $flight->office->name ?? '-' }}</td>
                                                <td>{{ $flight->arrival_airport }}</td>
                                                <td>{{ $flight->arrival_at->format('d-m-Y') }}</td>
                                                <td>{{ $flight->arrival_at->format('H:i') }}</td>
                                                <td>
                                                    <span class="badge badge-info badge-bill">{{ $passenger->displayStatus() }}</span>
                                                </td>
                                                <td>
                                                    <span class="badge badge-info badge-bill">{{ $passenger->displayCustomerStatus() }}</span>
                                                </td>
                                                <td>
                                                    @permission('flights-read')
                                                    <a href="{{ route('services.flights.passengers.show', ['flight' => $flight, 'passenger' => $passenger]) }}" class="btn btn-xs btn-info"> <i class="fa fa-eye"></i> عرض</a>
                                                    @endpermission
                                                    @permission('flights-update')
                                                    <a href="{{ route('services.flights.edit', $flight) }}" class="btn btn-xs btn-warning"> <i class="fa fa-edit"></i> تعديل</a>
                                                    @endpermission
                                                    @permission('flights-read')
                                                    <a href="{{ route('services.flights.show', $flight) }}" class="btn btn-xs btn-default"> <i class="fa fa-plane"></i> تفاصيل الرحلة</a>
                                                    @endpermission
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        @endslot
                    @endcomponent
                @endslot
            @endcomponent
        {{-- </div> --}}
    </div>
</section>
@endsection
