@extends('externaloffice::layouts.master', [
    'title' => 'Returned cv: ' . $return->cv->id,
    'datatable' => true, 
    'modals' => ['attachment'],
    'crumbs' => isset($crumbs) ? $crumbs : [
        [route('office.returns.index'), 'Returns'],
        ['#', 'Returned cv: ' . $return->cv->id]
    ],
])
@section('content')
    <h3>Details</h3>
    <table class="table table-bordered table-striped text-center">
        <thead>
            <tr>
                <th>Id</th>
                <th>Cv</th>
                <th>Payments</th>
                <th>Damages</th>
                <th>Total</th>
            </tr>
        <tbody>
            <tr>
                <td>{{ $return->id }}</td>
                <td>{{ $return->cv->name }}</td>
                <td>{{ $return->money('payed') }}</td>
                <td>{{ $return->money('damages') }}</td>
                <td>{{ $return->totalAmount() }}</td>
            </tr>
        </tbody>
    </table>
    <h3>Attachments</h3>
    @component('components.attachments-viewer')
        @slot('attachable', $return)
        @slot('view', 'timeline')
        @slot('canAdd', true)
    @endcomponent
@endsection
