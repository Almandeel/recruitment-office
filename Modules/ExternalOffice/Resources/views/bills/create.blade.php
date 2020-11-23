@extends('externaloffice::layouts.master', ['datatable' => true])

@section('content')
<section class="content">
    <form action="{{ route('cvs.bills.store') }}" method="post">
        <div class="row">
            {{--  <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Cvs</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="cv_id">Cvs</label>
                            <select class="custom-select select2" id="cv_id">
                                @foreach($cvs as $cv)
                                <option data-id="{{ $cv->id }}" data-name="{{ $cv->name }}" data-passport="{{ $cv->passport }}" data-amount="{{ $cv->amount }}" data-payed="{{ $cv->payed() }}"  data-remain="{{ $cv->remain() }}">{{ $cv->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-xs add-cv btn-primary btn-block" type="button"><i class="fa fa-plus"></i></button>
                        </div>
                    </div>
                </div>
            </div>  --}}
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Add Bill</h3>
                    </div>
                    <div class="card-body">
                            @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table table-hover">
                                    <table class="table bill-items datatable">
                                        <thead>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Passport</th>
                                            <th>Amount</th>
                                            <th>Payed</th>
                                            <th>Remain</th>
                                            <th>Options</th>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="7">
                                                    <div class="input-group">
                                                        <label class="input-group-prepend" for="cv_id">Cvs: </label>
                                                        <select class="custom-select select2" id="cv_id">
                                                            @foreach($cvs as $cv)
                                                            <option data-id="{{ $cv->id }}" data-name="{{ $cv->name }}" data-passport="{{ $cv->passport }}"
                                                                data-amount="{{ $cv->amount }}" data-payed="{{ $cv->payed() }}" data-remain="{{ $cv->remain() }}">
                                                                {{ $cv->name }}</option>
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
                            <div class="col mt-5">
                            @component('components.widget')
                                @slot('body')
                                @component('accounting::components.attachments-uploader')@endcomponent
                                @endslot
                            @endcomponent
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

        var cv = $('select#cv_id option:selected');

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
                    <td>`+cv.data('amount')+`</td>
                    <td>`+cv.data('payed')+`</td>
                    <td><input class="form-control" type="number" name="amount[]" value="`+ cv.data('remain') +`" min="0" required></td>
                    <td>
                        <button class="btn btn-danger btn-xs btn-delete" type="button" onclick="deleteRow(`+ cv.data('id') +`)"
                            data-id="`+cv.data('id')+`" data-name="`+cv.data('name')+`" data-passport="`+cv.data('passport')+`" data-amount="`+cv.data('amount')+`"
                            data-remain="`+cv.data('remain')+`" data-payed="`+cv.data('payed')+`"
                        ><i class="fa fa-trash"></i></button>
                    </td>
                </tr>`;

        $('select option:selected').remove();

        $('.bill-items tbody td.dataTables_empty').remove();
        $('.bill-items tbody').append(row);
    });

    function deleteRow(id) {
        let cv = $(`#${id} .btn-delete`)

        let selectOptionTag = `<option data-id="`+cv.data('id')+`" data-name="`+cv.data('name')+`" data-passport="`+cv.data('passport')+`" data-amount="`+cv.data('amount')+`" data-remain="`+cv.data('remain')+`" data-payed="`+cv.data('payed')+`">`+cv.data('name')+`</option>`;

        $('#cv_id').append(selectOptionTag);

        $(`#${id}`).remove();
    }
</script>

@endpush