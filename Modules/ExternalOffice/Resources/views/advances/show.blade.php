@extends('externaloffice::layouts.master', ['modals' => ['attachment']])

@section('content')
<section class="content">
        @component('components.tabs')
            @slot('items')
                @component('components.tab-item')
                    @slot('active', true)
                    @slot('id', 'details')
                    @slot('title', 'Advance Data')
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
                        <table class="table table-bordered table-striped text-center">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <td>{{ $advance->id }}</td>
                                </tr>
                                <tr>
                                    <th>Amount</th>
                                    <td>{{ number_format($advance->amount) }}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        @if ($advance->status == 0)
                                            <span class="text-warning">On waiting</span>
                                        @elseif ($advance->status == 1)
                                            <span class="text-success">Accepted</span>
                                        @elseif ($advance->status == 2)
                                            <span class="text-danger">Canceled</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Created By</th>
                                    <td>{{ $advance->user->name }}</td>
                                </tr>
                                <tr>
                                    <th>Date</th>
                                    <td>{{ $advance->created_at->format('Y-m-d') }}</td>
                                </tr>
                            </thead>
                        </table>
                    @endslot
                @endcomponent
                @component('components.tab-content')
                    @slot('id', 'attachments')
                    @slot('content')
                        @component('components.attachments-viewer')
                            @slot('attachable', $advance)
                            @slot('canAdd', true)
                            @slot('view', 'timeline')
                        @endcomponent
                    @endslot
                @endcomponent
            @endslot
        @endcomponent
</section>
@endsection
