@extends('externaloffice::layouts.master', [
    'title' => "{$passenger->cv->name} flight",
    'datatable' => true,
    'crumbs' => [
        [route('office.flights.index'), 'Flights'],
        ['#', $flight->id],
        ['#', $passenger->cv->name],
    ]
])

@section('content')
<section class="content">
        @component('components.tabs')
            @slot('items')
                @component('components.tab-item')
                    @slot('active', true)
                    @slot('id', 'details')
                    @slot('title', 'Flight Data')
                @endcomponent
                @component('components.tab-item')
                    @slot('id', 'attachments')
                    @slot('title', 'Attachments')
                @endcomponent
            @endslot
            @slot('contents')
                @component('components.tab-content')
                    @slot('active', true)
                    @slot('id', 'details')
                    @slot('content')
                        <div class="table table-hover">
                            <table class="table table-bordered table-striped text-center">
                                <thead>
                                    <tr>
                                        <th>#</th></th>
                                        <th>Name</th>
                                        <th>Job Title</th>
                                        <th>Passport</th>
                                        <th>DATE OF ISSUE</th>
                                        <th>DATE OF EXPIRY</th>
                                        <th>@if ($passenger->status == $passenger::STATUS_WAITING) Confirm DEP @else Worker Status @endif</th>
                                    </tr>
                                </thead>
                                <tbody>
                                        <td>{{ $passenger->id }}</td>
                                        <td>{{ $passenger->cv->name }}</td>
                                        <td>{{ $passenger->cv->profession->name }}</td>
                                        <td>{{ $passenger->cv->passport }}</td>
                                        <td>{{ $passenger->cv->passport_issuing_date }}</td>
                                        <td>{{ $passenger->cv->passport_expiration_date }}</td>
                                        <td>
                                            @if ($passenger->status == $passenger::STATUS_WAITING)
                                                <form action="{{ route('office.flights.passengers.update', ['flight' => $passenger->flight, 'passenger' => $passenger]) }}" method="post" id="passenger-status-form">
                                                    @csrf @method('PUT')
                                                    <input type="hidden" name="status" id="status" value="{{ $passenger::STATUS_ARRIVING }}">
                                                    <input type="hidden" name="passenger_id" id="passenger_id"  value="{{ $passenger->id }}">
                                                    <button class="btn btn-xs btn-primary" type="submit">Confirm</button>
                                                </form>
                                            @else
                                                <span class="badge badge-info">
                                                    {{ $passenger->displayStatus() }}
                                                </span>
                                            @endif
                                        </td>
                                </tbody>
                            </table>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <span> Date of DEP : {{ $flight->departure_at->format('d-m-Y') }}</span>
                            </div>
                            <div class="col-md-3">
                                <span> Date of Arrival : {{ $flight->arrival_at->format('d-m-Y') }}</span>
                            </div>
                            <div class="col-md-3">
                                <span> Departing Airport : {{ $flight->departure_airport }}</span>
                            </div>
                            <div class="col-md-3">
                                <span> Arrival Airport : {{ $flight->arrival_airport }}</span>
                            </div>

                            <div class="col-md-3">
                                <span> Departure Time : {{ $flight->departure_at->format('H:i') }}</span>
                            </div>
                            <div class="col-md-3">
                                <span> Arrival Time : {{ $flight->arrival_at->format('d-m-Y') }}</span>
                            </div>
                            <div class="col-md-3">
                                <span> TRIP NO : {{ $flight->trip_number }}</span>
                            </div>
                            <div class="col-md-3">
                                <span> Airline Name : {{ $flight->airline_name }}</span>
                            </div>
                        </div>
                    @endslot
                @endcomponent
                @component('components.tab-content')
                    @slot('id', 'attachments')
                    @slot('content')
                        @component('components.attachments-viewer')
                            @slot('attachable', $passenger)
                            @slot('canAdd', true)
                            @slot('view', 'timeline')
                        @endcomponent
                    @endslot
                @endcomponent
            @endslot
        @endcomponent
</section>
@endsection
