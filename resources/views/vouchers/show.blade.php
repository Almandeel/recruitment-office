@php
    $layout = request('layout') == 'print' ? 'layouts.print' : 'accounting::layouts.master';
    $options = [
    'datatable' => true, 
    'lightbox' => true, 
    'title' => $voucher->getType() .  ': ' . $voucher->id,
    'modals' => ['attachment'],
    'crumbs' => [
        // [route('vouchers.index'), __('accounting::global.vouchers')],
        ['#', $voucher->getType() .  ': ' . $voucher->id],
    ]
    ];

    if(request('layout') == 'print'){
        $options = [
            'title' => $voucher->getType() .  ': ' . $voucher->id,
            'heading' => $voucher->getType(),
            'auto_print' => true,
        ];
    }
@endphp
@extends($layout, $options)
@if (request('layout') == 'print')
@push('content')
    {{--  <h2 class="text-center">{{ $voucher->getType() .  ': ' . $voucher->id }}</h2>  --}}
    <table class="table table-bordered table-striped text-center">
        <thead>
            <tr>
                <th>@lang('accounting::global.number')</th>
                <th>@lang('accounting::global.benefit')</th>
                <th>@lang('accounting::global.amount')</th>
                <th>@lang('accounting::global.date')</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $voucher->id }}</td>
                <td>{{ $voucher->getBenefit() }}</td>
                <td>{{ $voucher->displayAmount() }}</td>
                <td>{{ $voucher->voucher_date ? date('Y/m/d', strtotime($voucher->voucher_date)) : '...' }}</td>
            </tr>
            <tr>
                <th colspan="4" style="text-align: center;">{{ $voucher->details }}</th>
            </tr>
        </tbody>
    </table>@php $entry = $voucher->entry; @endphp
    @if ($entry)
    <table id="entries-table" class="table table-bordered">
        <thead>
            <tr>
                <th colspan="2">@lang('accounting::accounting.debt')</th>
                <th colspan="2">@lang('accounting::accounting.credit')</th>
            </tr>
            <tr>
                <th>@lang('accounting::global.amount')</th>
                <th>@lang('accounting::global.account')</th>
                <th>@lang('accounting::global.amount')</th>
                <th>@lang('accounting::global.account')</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="padding: 0; vertical-align: top;">
                    <table class="table">
                        <tbody>
                            @foreach($entry->debts() as $account)
                            <tr>
                                <td style="background-color: transparent !important; border: none !important;">
                                    {{ $account->pivot->amount }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </td>
                <td style="padding: 0; vertical-align: top;">
                    <table class="table">
                        <tbody>
                            @foreach($entry->debts() as $account)
                            <tr>
                                <td style="background-color: transparent !important; border: none !important;">
                                    {{ $account->numeric_name}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </td>
                <td style="padding: 0; vertical-align: top;">
                    <table class="table">
                        <tbody>
                            @foreach($entry->credits() as $account)
                            <tr>
                                <td style="background-color: transparent !important; border: none !important;">
                                    {{ $account->pivot->amount }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </td>
                <td style="padding: 0; vertical-align: top;">
                    <table class="table">
                        <tbody>
                            @foreach($entry->credits() as $account)
                            <tr>
                                <td style="background-color: transparent !important; border: none !important;">
                                    {{ $account->numeric_name}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    @endif
@endpush
@push('footer-extra')
    <div class="user">
        <p>تم إعتماد السند من المستخدم: <strong>{{ auth()->user()->name }}</strong></p>
    </div>
    @endpush
@else
@push('content')
    @component('accounting::components.tabs')
        @slot('items')
            @component('accounting::components.tab-item')
                @slot('id', 'details')
                @slot('active', true)
                @slot('title', __('accounting::global.details'))
            @endcomponent
            @component('accounting::components.tab-item')
                @slot('id', 'attachments')
                @slot('title', __('accounting::global.attachments'))
            @endcomponent
        @endslot
        @slot('contents')
            @component('accounting::components.tab-content')
                @slot('id', 'details')
                @slot('active', true)
                @slot('content')
                    <table class="table table-bordered text-center">
                        <thead>
                            <tr>
                                <th colspan="6">{{ $voucher->getType() .  ': ' . $voucher->id }}</th>
                            </tr>
                            <tr>
                                <th>@lang('accounting::global.id')</th>
                                {{--  <th>@lang('accounting::global.number')</th>  --}}
                                <th>@lang('accounting::global.benefit')</th>
                                <th>@lang('accounting::global.amount')</th>
                                <th>@lang('accounting::global.date')</th>
                                {{--  <th>@lang('accounting::global.create_date')</th>  --}}
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $voucher->id }}</td>
                                {{--  <td>{{ $voucher->number }}</td>  --}}
                                <td>{{ $voucher->getBenefit() }}</td>
                                <td>{{ $voucher->displayAmount() }}</td>
                                <td>{{ $voucher->voucher_date ? date('Y/m/d', strtotime($voucher->voucher_date)) : '...' }}</td>
                                {{--  <td>{{ $voucher->created_at->format('Y/m/d') }}</td>  --}}
                            </tr>
                            <tr>
                                <td colspan="4" style="text-align: center;">{{ $voucher->details }}</td>
                            </tr>
                        </tbody>
                    </table>
                    @php $entry = $voucher->entry; @endphp
                    @if ($entry)
                        <table id="entries-table" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th colspan="2">@lang('accounting::accounting.debt')</th>
                                    <th colspan="2">@lang('accounting::accounting.credit')</th>
                                </tr>
                                <tr>
                                    <th>@lang('accounting::global.amount')</th>
                                    <th>@lang('accounting::global.account')</th>
                                    <th>@lang('accounting::global.amount')</th>
                                    <th>@lang('accounting::global.account')</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="padding: 0; vertical-align: top;">
                                        <table class="table">
                                            <tbody>
                                                @foreach($entry->debts() as $account)
                                                    <tr>
                                                        <td style="background-color: transparent !important; border: none !important;">{{ $account->pivot->amount }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </td>
                                    <td style="padding: 0; vertical-align: top;">
                                        <table class="table">
                                            <tbody>
                                                @foreach($entry->debts() as $account)
                                                    <tr>
                                                        <td style="background-color: transparent !important; border: none !important;">{{ $account->numeric_name}}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </td>
                                    <td style="padding: 0; vertical-align: top;">
                                        <table class="table">
                                            <tbody>
                                                @foreach($entry->credits() as $account)
                                                    <tr>
                                                        <td style="background-color: transparent !important; border: none !important;">{{ $account->pivot->amount }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </td>
                                    <td style="padding: 0; vertical-align: top;">
                                        <table class="table">
                                            <tbody>
                                                @foreach($entry->credits() as $account)
                                                    <tr>
                                                        <td style="background-color: transparent !important; border: none !important;">{{ $account->numeric_name}}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    @endif
                    <hr>
                    <div class="text-center">
                        @permission('vouchers-update')
                            <div class="btn-group">
                                @permission('vouchers-read')
                                <a href="{{ route('home.voucher', ['voucher' => $voucher, 'layout' => 'print']) }}" class="btn btn-default">
                                    <i class="fa fa-print"></i>
                                    <span>طباعة</span>
                                </a></li>
                                @endpermission
                                {{--  @if ($voucher->entry)
                                    <span>
                                        <i class="fa fa-check"></i>
                                        <span>@lang('accounting::global.confirmed')</span>
                                    </span>
                                @else
                                    @if (auth()->user()->isAbleTo('vouchers-update'))
                                        <a href="#" class="btn btn-success" 
                                            data-confirm="true" 
                                            data-modal="safeable"
                                            data-safeable-type="Modules\Accounting\Models\Voucher"
                                            data-safeable-id="{{ $voucher->id }}"
                                            data-amount="{{ $voucher->amount }}"
                                            data-type="{{ $voucher->getType() }}"
                                            data-account-id="{{ $voucher->benefitIsModel() ? $voucher->voucherable_id : 0 }}">
                                            <i class="fa fa-check"></i>
                                            <span>@lang('accounting::global.confirm_title')</span>
                                        </a>
                                    @else
                                        <span>
                                            <i class="fa fa-time"></i>
                                            <span>@lang('accounting::global.confirming')</span>
                                        </span>
                                    @endif
                                @endif  --}}
                                @if(auth()->user()->isAbleTo('vouchers-update') && $voucher->statusIsWaiting())
                                <button type="button" class="btn btn-success" data-toggle="status" data-id="{{ $voucher->id }}"
                                    data-type="{{ get_class($voucher) }}" data-status="approve">
                                    <i class="fa fa-check"></i>
                                    <span>@lang('global.approve')</span>
                                </button>
                                <button type="button" class="btn btn-danger" data-toggle="status" data-id="{{ $voucher->id }}"
                                    data-type="{{ get_class($voucher) }}" data-status="reject">
                                    <i class="fa fa-times"></i>
                                    <span>@lang('global.reject')</span>
                                </button>
                                @endif
                                @if ($voucher->isEditable())
                                    {{--  @permission('vouchers-update')
                                    <a href="{{ route('vouchers.edit', $voucher) }}" class="btn btn-warning">
                                        <i class="fa fa-edit"></i>
                                        <span>@lang('accounting::global.edit')</span>
                                    </a>
                                    @endpermission  --}}
                                    @permission('vouchers-delete')
                                    <a href="#" class="btn btn-danger delete" data-form="#deleteForm-{{ $voucher->id }}">
                                        <i class="fa fa-trash"></i>
                                        <span>@lang('accounting::global.delete')</span>
                                    </a>
                                    @endpermission
                                @endif
                            </div>
                            @if ($voucher->isEditable())
                                @permission('vouchers-delete')
                                <form id="deleteForm-{{ $voucher->id }}" style="display:none;"
                                    action="{{ route('vouchers.destroy', $voucher->id) }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                </form>
                                @endpermission
                            @endif
                         @endpermission
                    </div>
                @endslot
            @endcomponent
            @component('accounting::components.tab-content')
                @slot('id', 'attachments')
                @slot('content')
                    @component('accounting::components.attachments-viewer')
                        @slot('attachable', $voucher)
                        @slot('view', 'timeline')
                        @slot('canAdd', true)
                    @endcomponent
                @endslot
            @endcomponent
        @endslot
    @endcomponent
@endpush
@endif