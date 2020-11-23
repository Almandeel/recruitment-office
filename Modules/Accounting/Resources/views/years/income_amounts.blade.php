
@if($type == 'amounts')
    {{--  @if ($account->isSecondary())
    @endif  --}}
    @if ($account->isSecondary())
        <div class="{{ $side }}-account-balance">{{ $account->balance(true, $year->id) }}</div>
    @else
        <div class="transparent">-</div>
    @endif
    @foreach ($account->children as $child)
        @component('accounting::years.income_amounts')
            @slot('year', $year)
            @slot('type', $type)
            @slot('side', $side)
            @slot('account', $child)
        @endcomponent
    @endforeach
@else
    {{--  @if ($account->balance(true, $year->id))  --}}
        <div>
            {{--  <a class="show-screen" href="{{ route('accounts.show', $account) }}">{{ $account->number . '-' . $account->name }}</a>  --}}
            <span class="show-print">{{ $account->number . '-' . $account->name }}</span>
        </div>
    {{--  @endif  --}}
    @foreach ($account->children as $child)
        @component('accounting::years.income_amounts')
            @slot('year', $year)
            @slot('type', $type)
            @slot('side', $side)
            @slot('account', $child)
        @endcomponent
    @endforeach
@endif