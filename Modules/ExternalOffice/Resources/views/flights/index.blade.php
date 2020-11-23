@extends('externaloffice::layouts.master', [
    'title' => 'Flights list',
    'datatable' => true,
    'crumbs' => [
        [route('office.flights.index'), 'Flights'],
    ]
])

@section('content')
<section class="content">
    <div class="card">
        <div class="card-header">
            <a href="{{ route('office.flights.create') }}" class="btn btn-primary float-right">New</a>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped text-center datatable">
                <thead>
                    <th>#</th>
                    <th>Name </th>
                    <th>Arrival Airport </th>
                    <th>Date of Arrival</th>
                    <th>Arrival Time </th>
                    <th>Arrival Status </th>
                    <th>Worker status </th>
                    <th>Options  </th>
                </thead>
                <tbody>
                    @foreach ($flights as $flight)
                        @foreach ($flight->passengers as $passenger)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $passenger->cv->name ?? '-' }}</td>
                                <td>{{ $flight->arrival_airport }}</td>
                                <td>{{ $flight->arrival_at->format('d-m-Y') }}</td>
                                <td>{{ $flight->arrival_at->format('H:i') }}</td>
                                <td>
                                    <span class="badge badge-info badge-bill">{{ $flight->displayStatus() }}</span>
                                </td>
                                <td>
                                    <span class="badge badge-info badge-bill">{{ $passenger->displayStatus() }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('office.flights.passengers.show', ['flight' => $flight, 'passenger' => $passenger]) }}" class="btn btn-xs btn-info"> <i class="fa fa-eye"></i> Show</a>
                                    <a href="{{ route('office.flights.edit', $flight) }}" class="btn btn-xs btn-warning"> <i class="fa fa-edit"></i> Edit</a>
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>
@endsection
