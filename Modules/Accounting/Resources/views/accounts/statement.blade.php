@extends($layout, $options)
@push('content')
@if ($layout == 'layouts.print')
    <div class="table-wrapper">
        <table id="entries-table" class="table table-bordered table-condensed table-striped table-print">
            <thead>
                <tr>
                    <th>@lang('accounting::global.date')</th>
                    <th>@lang('accounting::global.details')</th>
                    <th>@lang('accounting::global.from')</th>
                    <th>@lang('accounting::global.to')</th>
                    <th>@lang('accounting::global.total')</th>
                </tr>
            </thead>
            <tbody>
                @php
                $total_debts = $opening_balance == 'debt' ? $opening_balance[1] : 0;
                $total_credits = $opening_balance == 'credit' ? $opening_balance[1] : 0;
                $total = 0;
                if($opening_balance[0] == 'debt'){
                $total += $opening_balance[1];
                }
                else if($opening_balance[0] == 'credit'){
                $total -= $opening_balance[1];
                }
                @endphp
                @if ($opening_balance[1] != 0 && $opening_balance[2] != null)
                <tr>
                    <td>
                        @if ($opening_balance[2])
                        {{ $opening_balance[2] }}
                        @endif
                    </td>
                    <td>
                        @if ($opening_balance[2])
                        {{ str_replace(':date', $opening_balance[2], __('accounting::accounts.opening_details')) }}
                        @endif
                    </td>
                    <td>
                        @if ($opening_balance[0] == 'debt')
                        {{ number_format($opening_balance[1], 2) }}
                        @endif
                    </td>
                    <td>
                        @if ($opening_balance[0] == 'credit')
                        {{ number_format($opening_balance[1], 2) }}
                        @endif
                    </td>
                    <td>
                        {{ number_format($total, 2) }}
                    </td>
                </tr>
                @endif
                @foreach ($debts as $debt)
                @php $total += $debt->pivot->amount; @endphp
                <tr>
                    <td>{{ $debt->getDate() }}</td>
                    <td>{{ $debt->details}}</td>
                    <td>{{ number_format($debt->pivot->amount, 2) }}</td>
                    <td></td>
                    <td>{{ number_format($total, 2) }}</td>
                </tr>
                @endforeach
                @foreach ($credits as $credit)
                @php $total -= $credit->pivot->amount; @endphp
                <tr>
                    <td>{{ $credit->getDate() }}</td>
                    <td>{{ $credit->details}}</td>
                    <td></td>
                    <td>{{ number_format($credit->pivot->amount, 2) }}</td>
                    <td>{{ number_format($total, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <th colspan="2">@lang('accounting::global.total')</th>
                <th>
                    @if ($opening_balance[0] == 'debt')
                    {{ number_format($debts->sum('pivot.amount') + $opening_balance[1], 2) }}
                    @else
                    {{ number_format($debts->sum('pivot.amount'), 2) }}
                    @endif
                </th>
                <th>
                    @if ($opening_balance[0] == 'credit')
                    {{ number_format($credits->sum('pivot.amount') + $opening_balance[1], 2) }}
                    @else
                    {{ number_format($credits->sum('pivot.amount'), 2) }}
                    @endif
                </th>
                <td>{{ number_format($total, 2) }}</td>
            </tfoot>
        </table>
    </div>
@else
    @component('accounting::components.widget')
        @slot('tools')
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
                {{--  <div class="form-group mr-2">
                    <label for="account_id">@lang('accounting::global.account')</label>
                    <select name="account_id" id="account_id" class="form-control select2">
                        @foreach ($accounts as $acc)
                        <option value="{{ $acc->id }}" {{ ($account->id == $acc->id) ? 'selected' : '' }}>{{ $acc->number . '-' . $acc->name }}</option>
                        @endforeach
                    </select>
                </div>  --}}
                <div class="form-group mr-2">
                    <label for="from-date">@lang('accounting::global.from')</label>
                    <input type="date" name="from_date" id="from-date" value="{{ $from_date }}" class="form-control">
                </div>
                <div class="form-group mr-2">
                    <label for="to-date">@lang('accounting::global.to')</label>
                    <input type="date" name="to_date" id="to-date" value="{{ $to_date }}" class="form-control">
                </div>
                <input type="hidden" name="view" value="statement">
                <button type="submit" class="btn btn-primary">
                    <span>@lang('accounting::global.search')</span>
                    <i class="fa fa-search"></i>
                </button>
            </form>
        @endslot
        @slot('title')
            <i class="fa fa-list"></i>
            <span>@lang('accounting::accounts.statement'): {{ $account->id . '-' . $account->name }}</span>
        @endslot
        @slot('body')
        <div class="table-wrapper">
            <table id="entries-table" class="table table-bordered table-condensed table-striped table-print">
                <thead>
                    <tr>
                        <th>@lang('accounting::global.date')</th>
                        <th>@lang('accounting::global.details')</th>
                        <th>@lang('accounting::global.from')</th>
                        <th>@lang('accounting::global.to')</th>
                        <th>@lang('accounting::global.total')</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $total_debts = $opening_balance == 'debt' ? $opening_balance[1] : 0;
                        $total_credits = $opening_balance == 'credit' ? $opening_balance[1] : 0;
                        $total = 0;
                        if($opening_balance[0] == 'debt'){
                            $total += $opening_balance[1];
                        }
                        else if($opening_balance[0] == 'credit'){
                            $total -= $opening_balance[1];
                        }
                    @endphp
                    @if ($opening_balance[1] != 0 && $opening_balance[2] != null)
                    <tr>
                        <td>
                            @if ($opening_balance[2])
                                {{ $opening_balance[2] }}
                            @endif
                        </td>
                        <td>
                            @if ($opening_balance[2])
                                {{ str_replace(':date', $opening_balance[2], __('accounting::accounts.opening_details')) }}
                            @endif
                        </td>
                        <td>
                            @if ($opening_balance[0] == 'debt')
                                {{ number_format($opening_balance[1], 2) }}
                            @endif
                        </td>
                        <td>
                            @if ($opening_balance[0] == 'credit')
                                {{ number_format($opening_balance[1], 2) }}
                            @endif
                        </td>
                        <td>
                            {{ number_format($total, 2) }}
                        </td>
                    </tr>
                    @endif
                    @foreach ($debts as $debt)
                        @php $total += $debt->pivot->amount; @endphp
                        <tr>
                            <td>{{ $debt->getDate() }}</td>
                            <td>{{ $debt->details}}</td>
                            <td>{{ number_format($debt->pivot->amount, 2) }}</td>
                            <td></td>
                            <td>{{ number_format($total, 2) }}</td>
                        </tr>
                    @endforeach
                    @foreach ($credits as $credit)
                        @php $total -= $credit->pivot->amount; @endphp
                        <tr>
                            <td>{{ $credit->getDate() }}</td>
                            <td>{{ $credit->details}}</td>
                            <td></td>
                            <td>{{ number_format($credit->pivot->amount, 2) }}</td>
                            <td>{{ number_format($total, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <th colspan="2">@lang('accounting::global.total')</th>
                    <th>
                        @if ($opening_balance[0] == 'debt')
                            {{ number_format($debts->sum('pivot.amount') + $opening_balance[1], 2) }}
                        @else
                            {{ number_format($debts->sum('pivot.amount'), 2) }}
                        @endif
                    </th>
                    <th>
                        @if ($opening_balance[0] == 'credit')
                            {{ number_format($credits->sum('pivot.amount') + $opening_balance[1], 2) }}
                        @else
                            {{ number_format($credits->sum('pivot.amount'), 2) }}
                        @endif
                    </th>
                    <td>{{ number_format($total, 2) }}</td>
                </tfoot>
            </table>
        </div>
        @endslot
    @endcomponent
@endif
@endpush
@push('foot')
    <script>
        $(function() {
        });
    </script>
@endpush