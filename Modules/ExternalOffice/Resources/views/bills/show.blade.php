@extends('externaloffice::layouts.master', ['modals' => ['attachment'], 'datatable' => true])

@section('content')
<section class="content">
    @component('components.tabs')
        @slot('items')
            @component('components.tab-item')
                @slot('active', true)
                @slot('id', 'details')
                @slot('title', 'Bill details')
            @endcomponent
            @component('components.tab-item')
                @slot('id', 'cvs')
                @slot('title', 'CVS')
            @endcomponent
            @component('components.tab-item')
                @slot('id', 'attachments')
                @slot('title', 'Attachments')
            @endcomponent
            @component('components.tab-item')
                @slot('id', 'vouchers')
                @slot('title', 'Vouchers')
            @endcomponent
        @endslot
        @slot('contents')
            @component('components.tab-content')
                @slot('active', true)
                @slot('id', 'details')
                @slot('content')
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Total Amount</th>
                                <th>Paid</th>
                                <th>Remain</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $bill->id }}</td>
                                <td>{{ number_format($bill->amount, 2) }}</td>
                                <td>{{ $bill->payed(true) }}</td>
                                <td>{{ $bill->remain(true) }}</td>
                                <td>{{ $bill->displayStatus() }}</td>
                            </tr>
                        </tbody>
                    </table>
                @endslot
            @endcomponent
            @component('accounting::components.tab-content')
                @slot('id', 'cvs')
                @slot('content')
                    <table class="table table-bordered table-striped text-center">
                        <thead>
                            <th>#</th>
                            <th>Name</th>
                            <th>Passport</th>
                            <th>Amount</th>
                        </thead>
                        <tbody>
                            @foreach($bill->cvBill as $cvBill)
                            <tr>
                                <td>{{ $cvBill->cv->id }}</td>
                                <td>{{ $cvBill->cv->name }}</td>
                                <td>{{ $cvBill->cv->passport }}</td>
                                <td>{{ number_format($cvBill->amount, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>{{ number_format($bill->cvBill->sum('amount'), 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                @endslot
            @endcomponent
            @component('accounting::components.tab-content')
                @slot('id', 'attachments')
                @slot('content')
                    @component('accounting::components.attachments-viewer')
                        @slot('attachable', $bill)
                        @slot('view', 'timeline')
                        @slot('canAdd', true)
                    @endcomponent
                @endslot
            @endcomponent
            @component('accounting::components.tab-content')
                @slot('id', 'vouchers')
                @slot('content')
                    @component('accounting::components.vouchers')
                        @slot('voucherable', $bill)
                        @slot('type', 'payment')
                        @slot('currency', 'دولار')
                        @slot('read_only', true)
                    @endcomponent
                @endslot
            @endcomponent
        @endslot
    @endcomponent
</section>
@endsection
