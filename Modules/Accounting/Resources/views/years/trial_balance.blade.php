{{--  @extends('layouts.accounting')
@section('title' => __('accounting::lists.trial_balance') . ': ' . $year->id)
@section('page_title')
    <i class="icon-expenses"></i>
    <span>@lang('accounting::global.years')</span>
    <a href="{{ route('years.trial_balance', $year) }}?by={{ $by == 'balances' ? 'totals' : 'balances' }}" class="btn btn-info">
        @lang('accounting::global.by_' . ($by == 'balances' ? 'totals' : 'balances'))
    </a>
@endsection  --}}
@extends('layouts.print', [
    'title' => __('accounting::lists.trial_balance') . ': ' . $year->id,
    'heading' => __('accounting::lists.trial_balance') . ': ' . $year->id,
])
@push('content')
    <h3>
        <i class="fa fa-list"></i>
        <span>@lang('accounting::lists.trial_balance'): </span>
        <span>{{ $year->id }}</span>
        <strong>(@lang('accounting::global.by_' . $by))</strong>
    </h3>
    <div class="table-wrapper">
        <table id="datatable" class="table table-bordered table-condensed table-striped">
            {{-- <thead>
                <tr>
                    <th colspan="3">
                        <i class="fa fa-list"></i>
                        <span>@lang('accounting::lists.trial_balance'): </span>
                        <span>{{ $year->id }}</span>
                        <strong>(@lang('accounting::global.by_' . $by))</strong>
                    </th>
                </tr>
            </thead> --}}
            <tbody>
                @if ($by == 'totals')
                    @php $total_totals_debts = 0; $total_totals_credits = 0; @endphp
                    @foreach ([assetsAccount(), revenuesAccount(), ownersAccount(), liabilitiesAccount(), expensesAccount()] as $child)
                        @php $total_totals_debts += $child->balances(false, $year->id); 
                        $total_totals_credits += $child->balances(false, $year->id); @endphp    
                        @component('accounting::years._account_children')
                            @slot('year', $year)
                            @slot('account', $child)
                        @endcomponent
                    @endforeach
                @else
                    @php $total_balances_debts = 0; $total_balances_credits = 0; @endphp
                    @foreach ([assetsAccount(), revenuesAccount()] as $child)
                        @php $total_balances_debts += $child->balances(false, $year->id); @endphp    
                        @component('accounting::years._account_child')
                            @slot('year', $year)
                            @slot('side', 'debts')
                            @slot('account', $child)
                        @endcomponent
                    @endforeach
                    @foreach ([ownersAccount(), liabilitiesAccount(), expensesAccount()] as $child)
                        @php $total_balances_credits += $child->balances(false, $year->id); @endphp    
                        @component('accounting::years._account_child')
                            @slot('year', $year)
                            @slot('side', 'credits')
                            @slot('account', $child)
                        @endcomponent
                    @endforeach
                @endif
            </tbody>
            <tfoot>
                <tr>
                    <th  class="total_debts">{{ number_format(($by == 'totals' ? $total_totals_debts : $total_balances_debts), 2)  }}</th>
                    <th  class="total_credits">{{ number_format(($by == 'totals' ? $total_totals_credits : $total_balances_credits), 2)  }}</th>
                    <th>@lang('accounting::global.total')</th>
                </tr>
            </tfoot>
        </table>
    </div>
@endpush
@push('scripts')
    <script>
        $(document).ready(function(){
            /*
            var total_debts = 0, total_credits = 0;
            $('#datatable tbody tr').each(function(index, tr){
                total_debts += Number($($(tr).children()[0]).text());
                total_credits += Number($($(tr).children()[1]).text());
            });
            $('#datatable tfoot .total_debts').text(total_debts);
            $('#datatable tfoot .total_credits').text(total_credits);
            */
        });
    </script>
@endpush