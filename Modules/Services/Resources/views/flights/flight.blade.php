@extends('layouts.master', [
    'title' => "رحلة: {$flight->id}",
    'datatable' => true,
    'crumbs' => [
        [route('services.flights.index'), 'رحلات الطيران'],
        ['#', $flight->id],
    ]
])

@section('content')
<section class="content">
        @component('components.tabs')
            @slot('items')
                @component('components.tab-item')
                    @slot('active', true)
                    @slot('id', 'details')
                    @slot('title', 'بيانات الرحلة')
                @endcomponent
                @component('components.tab-item')
                    @slot('id', 'attachments')
                    @slot('title', 'المرفقات')
                @endcomponent
                @component('components.tab-item')
                    @slot('id', 'vouchers')
                    @slot('title', 'السندات')
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
                                        <th>الإسم</th>
                                        <th>المهنة</th>
                                        <th>رقم الجواز</th>
                                        <th>تاريخ الاصدار</th>
                                        <th>تاريخ الانتهاء</th>
                                        <th>تبليغ العميل</th>
                                        <th>حالة الوصول</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($flight->passengers as $passenger)
                                        <tr>
                                            <td>{{ $passenger->id }}</td>
                                            <td>{{ $passenger->cv->name }}</td>
                                            <td>{{ $passenger->cv->profession->name }}</td>
                                            <td>{{ $passenger->cv->passport }}</td>
                                            <td>{{ $passenger->cv->passport_issuing_date }}</td>
                                            <td>{{ $passenger->cv->passport_expiration_date }}</td>
                                            <td>
                                                @if ($passenger->customer_status == $passenger::STATUS_CUSTOMER_WAITING)
                                                    <form class="d-inline-block" action="{{ route('services.flights.passengers.customer.update', ['flight' => $flight, 'passenger' => $passenger]) }}" method="post" id="passenger-status-form">
                                                        @csrf @method('PUT')
                                                        <input type="hidden" name="passenger_id" id="status" value="{{ $passenger->id }}">
                                                        <input type="hidden" name="status" id="status" value="{{ $passenger::STATUS_CUSTOMER_NOTIFIED }}" @if ($passenger->customer_status) checked @endif>
                                                        <button class="btn btn-xs btn-info" type="submit">تبليغ العميل</button>
                                                    </form>
                                                @else
                                                    <span class="badge badge-info">
                                                        {{ $passenger->displayCustomerStatus() }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($passenger->status == $passenger::STATUS_WAITING && $flight->office_id == null)
                                                    <form action="{{ route('services.flights.passengers.update', ['flight' => $passenger->flight, 'passenger' => $passenger]) }}" method="post" id="passenger-status-form">
                                                        @csrf @method('PUT')
                                                        <input type="hidden" name="status" id="status" value="{{ $passenger::STATUS_ARRIVING }}">
                                                        <input type="hidden" name="passenger_id" id="passenger_id"  value="{{ $passenger->id }}">
                                                        <button class="btn btn-xs btn-primary" type="submit">تأكيد المغادرة</button>
                                                    </form>
                                                @elseif ($passenger->status == $passenger::STATUS_ARRIVING)
                                                    <form class="d-inline-block" action="{{ route('services.flights.passengers.update', ['flight' => $passenger->flight, 'passenger' => $passenger]) }}" method="post" id="passenger-status-form">
                                                        @csrf @method('PUT')
                                                        <input type="hidden" name="passenger_id" id="status" value="{{ $passenger->id }}">
                                                        <input type="hidden" name="status" id="status" value="{{ $passenger::STATUS_ARRIVED }}" @if ($passenger->status) checked @endif>
                                                        <button class="btn btn-xs btn-info" type="submit">تم الوصول</button>
                                                    </form>
                                                    <form class="d-inline-block" action="{{ route('services.flights.passengers.update', ['flight' => $passenger->flight, 'passenger' => $passenger]) }}" method="post" id="passenger-status-form">
                                                        @csrf @method('PUT')
                                                        <input type="hidden" name="passenger_id" id="status" value="{{ $passenger->id }}">
                                                        <input type="hidden" name="status" id="status" value="{{ $passenger::STATUS_NOT_ARRIVED }}" @if ($passenger->status) checked @endif>
                                                        <button class="btn btn-xs btn-danger" type="submit">لم يتم الوصول</button>
                                                    </form>
                                                @elseif ($passenger->status == $passenger::STATUS_ARRIVED)
                                                    <form class="d-inline-block" action="{{ route('services.flights.passengers.update', ['flight' => $passenger->flight, 'passenger' => $passenger]) }}" method="post" id="passenger-status-form">
                                                        @csrf @method('PUT')
                                                        <input type="hidden" name="passenger_id" id="status" value="{{ $passenger->id }}">
                                                        <input type="hidden" name="status" id="status" value="{{ $passenger::STATUS_RECIVED }}" @if ($passenger->status) checked @endif>
                                                        <button class="btn btn-xs btn-info" type="submit">تسليم للعميل</button>
                                                    </form>
                                                    <form class="d-inline-block" action="{{ route('services.flights.passengers.update', ['flight' => $passenger->flight, 'passenger' => $passenger]) }}" method="post" id="passenger-status-form">
                                                        @csrf @method('PUT')
                                                        <input type="hidden" name="passenger_id" id="status" value="{{ $passenger->id }}">
                                                        <input type="hidden" name="status" id="status" value="{{ $passenger::STATUS_HOUSED }}" @if ($passenger->status) checked @endif>
                                                        <button class="btn btn-xs btn-info" type="submit">نقل إلى السكن</button>
                                                    </form>
                                                @elseif ($passenger->status == $passenger::STATUS_HOUSED)
                                                    <form class="d-inline-block" action="{{ route('services.flights.passengers.update', ['flight' => $passenger->flight, 'passenger' => $passenger]) }}" method="post" id="passenger-status-form">
                                                        @csrf @method('PUT')
                                                        <input type="hidden" name="passenger_id" id="status" value="{{ $passenger->id }}">
                                                        <input type="hidden" name="status" id="status" value="{{ $passenger::STATUS_RECIVED }}" @if ($passenger->status) checked @endif>
                                                        <button class="btn btn-xs btn-info" type="submit">تسليم للعميل</button>
                                                    </form>
                                                @else
                                                    <span class="badge badge-info">
                                                        {{ $passenger->displayStatus() }}
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <span> تاريخ المغادره : {{ $flight->departure_at->format('d-m-Y') }}</span>
                            </div>
                            <div class="col-md-3">
                                <span> تاريخ الوصول : {{ $flight->arrival_at->format('d-m-Y') }}</span>
                            </div>
                            <div class="col-md-3">
                                <span> مدينة المغادره : {{ $flight->departure_airport }}</span>
                            </div>
                            <div class="col-md-3">
                                <span> الوصول مطار : {{ $flight->arrival_airport }}</span>
                            </div>

                            <div class="col-md-3">
                                <span>وقت المغادره : {{ $flight->departure_at->format('H:i') }}</span>
                            </div>
                            <div class="col-md-3">
                                <span> وقت الوصول : {{ $flight->arrival_at->format('d-m-Y') }}</span>
                            </div>
                            <div class="col-md-3">
                                <span> رقم الرحلة: {{ $flight->trip_number }}</span>
                            </div>
                            <div class="col-md-3">
                                <span> شركة الطيران : {{ $flight->airline_name }}</span>
                            </div>
                            <div class="col-md-3">
                                <span> المكتب الخارجي  : {{ $flight->office->name ?? '-' }}</span>
                            </div>
                            <div class="col-md-3">
                                <span> الموظف : {{ $flight->user->name ?? '-' }}</span>
                            </div>
                        </div>
                    @endslot
                @endcomponent
                @component('components.tab-content')
                    @slot('id', 'attachments')
                    @slot('content')
                        @component('components.attachments-viewer')
                            @slot('attachable', $flight)
                            @slot('canAdd', true)
                            @slot('view', 'timeline')
                        @endcomponent
                    @endslot
                @endcomponent
                @component('accounting::components.tab-content')
                    @slot('id', 'vouchers')
                    @slot('content')
                        @component('accounting::components.vouchers')
                            @slot('voucherable', $flight)
                            @slot('type', 'payment')
                        @endcomponent
                    @endslot
                @endcomponent
            @endslot
        @endcomponent
</section>
@endsection
