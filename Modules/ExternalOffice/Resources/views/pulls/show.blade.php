@extends('externaloffice::layouts.master', [
    'title' => 'Pulled cv: ' . $pull->cv->id,
    'datatable' => true, 
    'modals' => ['attachment'],
    'crumbs' => [
        [route('office.pulls.index'), 'Pulled Cvs'],
        ['#', 'Pulled cv: ' . $pull->cv->id]
    ],
])
@section('content')
    @component('accounting::components.widget')
        @slot('noPadding', true)
        @slot('body')
            <table class="table table-bordered table-striped text-center">
                <thead>
                    <tr>
                        <th>@lang('global.id')</th>
                        <th>@lang('global.worker')</th>
                        <th>@lang('global.gender')</th>
                        <th>@lang('global.status')</th>
                        <th>Cv Payments</th>
                        <th>Damages</th>
                        <th>Total Amount</th>
                    </tr>
                <tbody>
                    <tr>
                        <td>{{ $pull->id }}</td>
                        <td>{{ $pull->cv->name }}</td>
                        <td>{{ $pull->cv->displayGender() }}</td>
                        <td>{!! $pull->displayStatus() !!}</td>
                        <td>{{ $pull->money('payed') }}</td>
                        <td>{{ $pull->money('damages') }}</td>
                        <td>{{ $pull->totalAmount() }}</td>
                    </tr>
                </tbody>
            </table>
        @endslot
    @endcomponent
    <h3>Notes & Files</h3>
    @component('accounting::components.attachments-viewer')
        @slot('attachable', $pull)
        @slot('view', 'timeline')
        @slot('canAdd', true)
    @endcomponent
@endsection
