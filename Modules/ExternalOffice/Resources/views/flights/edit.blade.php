@extends('externaloffice::layouts.master', [
    'title' => "Edit Flight $flight->id",
    'datatable' => true,
    'crumbs' => [
        [route('office.flights.index'), 'Flights'],
        [route('office.flights.show', $flight), $flight->id],
        ['#', 'Edit'],
    ]
])

@section('content')
<section class="content">
    <div class="card card-primary card-outline card-outline-tabs">
        <div class="card-body ">
            <form action="{{ route('office.flights.update', $flight) }}" method="post">
                @csrf @method('PUT')
                <div class="col-md-12">
                    <div class="table table-hover">
                        <table class="table bill-items datatable">
                            <thead>
                                <th>#</th>
                                <th>Name</th>
                                <th>Job Title</th>
                                <th>Passport</th>
                                <th>DATE OF ISSUE</th>
                                <th>DATE OF EXPIRY</th>
                                <th>Options</th>
                            </thead>
                            <tbody>
                                @foreach($flight->passengers as $passenger)
                                    <tr id="{{ $passenger->cv->id }}">
                                        <td>{{ $passenger->cv->id }}</td>
                                        <td>
                                            {{ $passenger->cv->name }}
                                        </td>
                                        <td>{{ $passenger->cv->profession->name_en }}</td>
                                        <td>{{ $passenger->cv->passport }}</td>
                                        <td>{{ $passenger->cv->passport_issuing_date }}</td>
                                        <td>{{ $passenger->cv->passport_expiration_date }}</td>
                                        <td>
                                            <button class="btn btn-danger btn-xs btn-delete" type="button" onclick="deleteRow({{ $passenger->cv->id }})"
                                                data-id="{{ $passenger->cv->id }}"
                                                data-name="{{ $passenger->cv->name }}"
                                                data-passport="{{ $passenger->cv->passport }}"
                                                data-job_title="{{ $passenger->cv->profession->name_en }}"
                                                data-date_of_issue="{{ $passenger->cv->passport_issuing_date }}"
                                                data-date_of_expiry="{{ $passenger->cv->passport_expiration_date }}"
                                            >
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                        <input type="hidden" name="cv_id[]" value="{{ $passenger->cv->id }}">
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="7">
                                        <div class="input-group">
                                            <label class="input-group-prepend" for="cv_id">Cvs: </label>
                                            <select class="custom-select select2" id="cv_id">
                                                @foreach($cvs as $cv)
                                                <option data-id="{{ $cv->id }}"
                                                    data-name="{{ $cv->name }}"
                                                    data-passport="{{ $cv->passport }}"
                                                    data-job_title="{{ $cv->profession->name }}"
                                                    data-date_of_issue="{{ $cv->passport_issuing_date }}"
                                                    data-date_of_expiry="{{ $cv->passport_expiration_date }}"
                                                >
                                                    {{ $cv->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                            <div class="input-group-append">
                                                <button class="btn btn-xs add-cv btn-primary btn-block" type="button">
                                                    <i class="fa fa-plus"></i>
                                                    <span>Add</span>
                                                </button>
                                            </div>
                                        </div>
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="departure_date">Date of DEP </label>
                            <input type="date" class="form-control" name="departure_date" required="" value="{{ $flight->departure_at->format('d-m-Y') }}" />
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="arrival_date">Date of Arrival </label>
                            <input type="date" class="form-control" name="arrival_date" required="" value="{{ $flight->arrival_at->format('d/m/Y') }}"/>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="departure_airport">Departing Airport</label>
                            <input type="text" class="form-control" name="departure_airport" placeholder="Departure Airport" required="" value="{{ $flight->departure_airport }}"/>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="arrival_airport">Arrival Airport</label>
                            <input type="text" class="form-control" name="arrival_airport" placeholder="Arrival Airport" required="" value="{{ $flight->arrival_airport }}"/>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="departure_time"> Departure Time</label>
                            <input type="time" class="form-control" name="departure_time" required="" value="{{ $flight->departure_at->format('H:i') }}"/>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="arrival_time"> Arrival Time</label>
                            <input type="time" class="form-control" name="arrival_time" required="" value="{{ $flight->arrival_at->format('H:i') }}"/>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="trip_number">TRIP NO. </label>
                            <input type="text" class="form-control" name="trip_number" placeholder="TRIP NO" required="" value="{{ $flight->trip_number }}"/>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="airline_name">Airline Name </label>
                            <input type="text" class="form-control" name="airline_name" placeholder="Airline Name" required="" value="{{ $flight->airline_name }}"/>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
            </form>
        </div>
    </div>
</section>
@endsection

@push('foot')
<script>
    $('.add-cv').click(function() {
        if ($('#cv_id option').length == 0) {
            alert("There\'s no more cvs");
            return;
        }

        var cv = $('select option:selected');

        if (cv === undefined) {
            return alert('Please selecte one!')
        }

        var row = `<tr id="`+cv.data('id')+`">
                    <td>`+cv.data('id')+`</td>
                    <td>
                        <input type="hidden" name="cv_id[]" value="`+cv.data('id')+`">
                        `+cv.data('name')+`
                    </td>
                    <td>`+cv.data('passport')+`</td>
                    <td><input class="form-control" type="number" name="amount[]" value="`+ cv.data('amount') +`" min="0" required></td>
                    <td>
                        <button class="btn btn-danger btn-xs btn-delete" type="button" onclick="deleteRow(`+ cv.data('id') +`)"
                            data-id="`+cv.data('id')+`" data-name="`+cv.data('name')+`" data-passport="`+cv.data('passport')+`" data-amount="`+cv.data('amount')+`"
                        ><i class="fa fa-trash"></i></button>
                    </td>
                </tr>`;

        $('select option:selected').remove();

        $('.bill-items tbody td.dataTables_empty').remove();
        $('.bill-items tbody').append(row);
    });

    function deleteRow(id) {
        let cv = $(`#${id} .btn-delete`)

        let selectOptionTag = `<option data-id="`+cv.data('id')+`" data-name="`+cv.data('name')+`" data-passport="`+cv.data('passport')+`" data-amount="`+cv.data('amount')+`">`+cv.data('name')+`</option>`;

        $('#cv_id').append(selectOptionTag);

        $(`#${id}`).remove();
    }
</script>
@endpush
