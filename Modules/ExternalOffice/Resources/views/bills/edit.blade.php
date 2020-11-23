@extends('externaloffice::layouts.master', ['datatable' => true, 'model' => ['attachment']])

@section('content')
<section class="content">
    <form action="{{ route('cvs.bills.update', $bill) }}" method="post">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">CVs </h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="cv_id">Cvs</label>
                            <select class="custom-select select2 d-inline-block" id="cv_id">
                                @foreach($cvs as $value)
                                <option data-id="{{ $value->id }}" data-name="{{ $value->name }}" data-passport="{{ $value->passport }}" data-amount="{{ $value->amount }}">{{ $value->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-xs btn-primary btn-xs add-cv btn-block" type="button"> <i class="fa fa-plus"></i> </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Edit Bill</h3>
                    </div>
    
                    <div class="card-body">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-12">
    
                                <div class="table table-hover">
                                    <table class="table datatable bill-items">
                                        <thead>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Passport</th>
                                            <th>Amount</th>
                                            <th>Options</th>
                                        </thead>
                                        <tbody>
                                            @foreach($bill->cvBill as $cvBill)
                                            <tr id="{{ $cvBill->cv->id }}">
                                                <td>{{ $cvBill->cv->id }}</td>
                                                <td>
                                                    <input type="hidden" name="cv_id[]" value="{{ $cvBill->cv->id }}">
                                                    {{ $cvBill->cv->name }}
                                                </td>
                                                <td>{{ $cvBill->cv->passport }}</td>
                                                <td>
                                                    <input type="number" class="form-control" name="amount[]" min="0" required value="{{ $cvBill->amount }}">
                                                    </td>
                                                <td>
                                                    <button class="btn btn-danger btn-xs btn-delete" type="button"  onclick="deleteRow({{ $cvBill->cv->id }})"
                                                        data-id="{{ $cvBill->cv->id }}" data-name="{{ $cvBill->cv->name }}" data-passport="{{ $cvBill->cv->passport }}" data-amount="{{ $cvBill->cv->amount }}"
                                                    >
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
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