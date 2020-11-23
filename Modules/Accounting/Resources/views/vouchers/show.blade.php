@extends('accounting::layouts.master', [
    'datatable' => true, 
    'lightbox' => true, 
    'title' => $voucher->getType() .  ': ' . $voucher->id,
    'modals' => ['attachment'],
    'crumbs' => [
        [route('vouchers.index'), __('accounting::global.vouchers')],
        ['#', $voucher->getType() .  ': ' . $voucher->id],
    ]
])
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
                                <th>@lang('accounting::global.details')</th>
                                <th>@lang('accounting::global.date')</th>
                                {{--  <th>@lang('accounting::global.create_date')</th>  --}}
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $voucher->id }}</td>
                                {{--  <td>{{ $voucher->number }}</td>  --}}
                                <td>{{ $voucher->getBenefit() }}</td>
                                <td>{{ $voucher->amount }}</td>
                                <td>{{ $voucher->details }}</td>
                                <td>{{ $voucher->voucher_date ? date('Y/m/d', strtotime($voucher->voucher_date)) : '...' }}</td>
                                {{--  <td>{{ $voucher->created_at->format('Y/m/d') }}</td>  --}}
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
                    <hr>
                    <div class="text-center">
                        @permission('vouchers-update')
                            <div class="btn-group">
                                @if ($voucher->entry)
                                    <span>
                                        <i class="fa fa-check"></i>
                                        <span>@lang('accounting::global.confirmed')</span>
                                    </span>
                                @else
                                    @if (auth()->user()->isAbleTo('vouchers-update'))
                                        <a href="{{ route('vouchers.edit', ['voucher' => $voucher, 'check' => true]) }}" class="btn btn-success">
                                            <i class="fa fa-check"></i>
                                            <span>@lang('accounting::global.confirm_title')</span>
                                        </a>
                                    @else
                                        <span>
                                            <i class="fa fa-time"></i>
                                            <span>@lang('accounting::global.confirming')</span>
                                        </span>
                                    @endif
                                @endif
                                @permission('vouchers-read')
                                <a href="{{ route('home.voucher', ['voucher' => $voucher, 'layout' => 'print']) }}" class="btn btn-default">
                                    <i class="fa fa-print"></i>
                                    <span>@lang('accounting::global.print')</span>
                                </a></li>
                                @endpermission
                                @if ($voucher->isEditable())
                                    @permission('vouchers-update')
                                    <a href="{{ route('vouchers.edit', $voucher) }}" class="btn btn-warning">
                                        <i class="fa fa-edit"></i>
                                        <span>@lang('accounting::global.edit')</span>
                                    </a>
                                    @endpermission
                                @endif
                                @permission('vouchers-delete')
                                <a href="#" class="btn btn-danger delete" data-form="#deleteForm-{{ $voucher->id }}">
                                    <i class="fa fa-trash"></i>
                                    <span>@lang('accounting::global.delete')</span>
                                </a>
                                @endpermission
                            </div>
                            @permission('vouchers-delete')
                            <form id="deleteForm-{{ $voucher->id }}" style="display:none;"
                                action="{{ route('vouchers.destroy', $voucher->id) }}" method="post">
                                @csrf
                                @method('DELETE')
                            </form>
                            @endpermission
                         @endpermission
                    </div>
                @endslot
            @endcomponent
            @component('accounting::components.tab-content')
                @slot('id', 'attachments')
                @slot('content')
                    @component('accounting::components.attachments-viewer')
                        @slot('view', 'timeline')
                        @slot('canAdd', true)
                        @slot('attachable', $voucher)
                        {{--  @slot('attachments', $voucher->attachments)  --}}
                        {{--  @slot('attachableType', 'Modules\Accounting\Models\Voucher') --}}
                        {{--  @slot('attachableId', $voucher->id)  --}}
                    @endcomponent
                @endslot
            @endcomponent
        @endslot
    @endcomponent
@endpush
