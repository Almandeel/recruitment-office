@php
    $debts = $account->debts($year->id)->sum('amount');
    $credits = $account->credits($year->id)->sum('amount');
@endphp
@if($debts || $credits)
<tr>
    {{--  @if ($account->isDebt())  --}}
    @if ($side == 'debts')
        <td class="col-md-2">{{ $account->balance(true, $year->id) }}</td>
        <td class="col-md-2"></td>
    @else
        <td class="col-md-2"></td>
        <td class="col-md-2">{{ $account->balance(true, $year->id) }}</td>
    @endif
    <td class="col-md-8">
        <span class="show-print">{{ $account->name }}</span>
    </td>
</tr>
@endif
@foreach ($account->children as $child)
@component('accounting::years._account_child')
    @slot('side', $side)
    @slot('year', $year)
    @slot('account', $child)
@endcomponent
@endforeach