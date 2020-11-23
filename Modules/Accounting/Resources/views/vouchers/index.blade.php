@extends('accounting::layouts.master', [
    'datatable' => true, 
    'title' => __('accounting::global.vouchers'),
    'crumbs' => [
        ['#', __('accounting::global.vouchers')],
    ]
])
@push('content')
    @component('accounting::components.widget')
        @slot('title')
            <i class="fa fa-list"></i>
            <span>@lang('accounting::vouchers.list')</span>
        @endslot
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
                    <label for="type">@lang('accounting::global.type')</label>
                    <select name="type" id="type" class="form-control" required>
                        <option value="all" {{ ($type == 'all') ? 'selected' : '' }}>@lang('accounting::global.all')
                        @foreach (Modules\Accounting\Models\Voucher::TYPES as $t)
                        <option value="{{ $t }}" {{ $t == $type ? 'selected' : '' }}>
                            {{ Modules\Accounting\Models\Voucher::getStaticType($t) }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group mr-2">
                    <label for="status">@lang('accounting::global.status')</label>
                    <select class="form-control type" name="status" id="status">
                        <option value="all" {{ ($status == 'all') ? 'selected' : '' }}>@lang('accounting::global.all')
                        </option>
                        <option value="confirmed" {{ ($status == 'confirmed') ? 'selected' : '' }}>
                            @lang('accounting::global.confirmed')</option>
                        <option value="confirming" {{ ($status == 'confirming') ? 'selected' : '' }}>
                            @lang('accounting::global.confirming')</option>
                    </select>
                </div>
                <div class="form-group mr-2">
                    <label for="from-date">@lang('accounting::global.from')</label>
                    <input type="date" name="from_date" id="from-date" value="{{ $from_date }}"
                        class="form-control">
                </div>
                <div class="form-group mr-2">
                    <label for="to-date">@lang('accounting::global.to')</label>
                    <input type="date" name="to_date" id="to-date" value="{{ $to_date }}"
                        class="form-control">
                </div>
                <button type="submit" class="btn btn-primary">
                    <span>@lang('accounting::global.search')</span>
                    <i class="fa fa-search"></i>
                </button>
            </form>
        @endslot
        @slot('body')
            <table class="table table-bordered datatable text-center">
                <thead>
                    <tr>
                        <th>@lang('accounting::global.id')</th>
                        {{--  <th>@lang('accounting::global.number')</th>  --}}
                        @if ($type == 'all')
                        @endif
                        <th>@lang('accounting::global.type')</th>
                        <th>@lang('accounting::global.benefit')</th>
                        <th>@lang('accounting::global.amount')</th>
                        <th>@lang('accounting::global.date')</th>
                        <th>@lang('accounting::global.status')</th>
                        <th>@lang('accounting::global.user')</th>
                        <th>@lang('accounting::global.options')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($vouchers as $voucher)
                    <tr>
                        <td>{{ $voucher->id }}</td>
                        {{--  <td>{{ $voucher->number ? $voucher->number : '...' }}</td>  --}}
                        <td>{{ $voucher->displayType() }}</td>
                        <td>{{ $voucher->getBenefit() }}</td>
                        <td>{{ $voucher->amount }}</td>
                        <td>{{ $voucher->voucher_date ? date('Y/m/d', strtotime($voucher->voucher_date)) : '...' }}</td>
                        <td>{{ $voucher->displayStatus() }}</td>
                        <td>{{ $voucher->auth()->name }}</td>
                        <td>
                            <div class="btn-group">
                                @permission('vouchers-read')
                                <a href="{{ route('home.voucher', ['voucher' => $voucher, 'layout' => 'print']) }}" class="btn btn-default">
                                    <i class="fa fa-print"></i>
                                    <span>@lang('accounting::global.print')</span>
                                </a></li>
                                @endpermission
                                @if (auth()->user()->isAbleTo('vouchers-update') && is_null($voucher->entry))
                                <a href="{{ route('vouchers.edit', ['voucher' => $voucher, 'check' => true]) }}" class="btn btn-success">
                                    <i class="fa fa-check"></i>
                                    <span>@lang('accounting::global.confirm_title')</span>
                                </a>
                                @endif
                                @permission('vouchers-read')
                                <a href="{{ route('vouchers.show', $voucher) }}" class="btn btn-info">
                                    <i class="fa fa-eye"></i>
                                    <span>@lang('accounting::global.show')</span>
                                </a>
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
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endslot
    @endcomponent
@endpush