@extends('accounting::layouts.master' ,[
    'title' => __('accounting::transfers.list'),
    'accounting_modals' => ['transfer', 'safe'], 
    'datatable' => true,
    'crumbs' => [
        ['#', __('accounting::transfers.list')],
    ],
])
@push('content')
@component('accounting::components.widget')
    @slot('tools')
        @permission('transfers-create')
        <a href="{{ route('transfers.create') }}" class="btn btn-primary">
            <i class="fa fa-plus"></i>
            <span>@lang('accounting::transfers.add')</span>
        </a>
        @endpermission
    @endslot
    @slot('title', __('accounting::transfers.list'))
    @slot('extra')
        <form action="" method="GET" class="form-inline guide-advanced-search">
            @csrf
            <div class="form-group mr-2">
                <label>
                    <i class="fa fa-cogs"></i>
                    <span>@lang('accounting::global.search_advanced')</span>
                </label>
            </div>
            <div class="form-group mr-2">
                <label for="from-date">@lang('accounting::global.from')</label>
                <input type="date" name="from_date" id="from-date" value="{{ $from_date }}" class="form-control">
            </div>
            <div class="form-group mr-2">
                <label for="to-date">@lang('accounting::global.to')</label>
                <input type="date" name="to_date" id="to-date" value="{{ $to_date }}" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">
                <span>@lang('accounting::global.search')</span>
                <i class="fa fa-search"></i>
            </button>
        </form>
    @endslot
    @slot('body')
        <table id="transfers-table" class="table table-striped datatable">
            <thead>
                <tr>
                    <th>@lang('accounting::global.from')</th>
                    <th>@lang('accounting::global.to')</th>
                    <th>@lang('accounting::global.amount')</th>
                    {{--  <th>@lang('accounting::global.details')</th>  --}}
                    <th>@lang('accounting::global.user')</th>
                    <th>@lang('accounting::global.create_date')</th>
                    <th>@lang('accounting::global.options')</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transfers as $transfer)
                    <tr class="text-center">
                        <td>{{ $transfer->to->name }}</td>
                        <td>{{ $transfer->from->name }}</td>
                        <td>{{ number_format($transfer->amount, 2) }}</td>
                        {{--  <td>{{ $transfer['details']  }}</td>  --}}
                        <td>{{ $transfer->auth()->name }}</td>
                        <td>{{ $transfer->created_at->format('Y-m-d') }}</td>
                        <td>
                            <div class="btn-group">
                                @permission('transfers-read')
                                {!! transferButton($transfer, 'preview') !!}
                                @endpermission
                                @permission('transfers-update')
                                {!! transferButton($transfer, 'update') !!}
                                @endpermission
                                @permission('transfers-delete')
                                {!! transferButton($transfer, 'delete') !!}
                                @endpermission
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endslot
@endcomponent
@endpush

@push('foot')
@endpush