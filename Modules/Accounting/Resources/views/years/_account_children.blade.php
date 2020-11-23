@php
    $debts = $account->debts($year->id)->sum('amount');
    $credits = $account->credits($year->id)->sum('amount');
@endphp
@if($debts || $credits)
<tr>
    <td class="col-md-2">{{ number_format($debts, 2) }}</td>
    <td class="col-md-2">{{ number_format($credits, 2) }}</td>
    <td class="col-md-8">{{ $account->name }}</td>
</tr>
@endif
@foreach ($account->children as $child)
    @component('accounting::years._account_children')
        @slot('year', $year)
        @slot('account', $child)
    @endcomponent
@endforeach