@if($account->balances(false, $year->id) > 0)
    {{--  @if ($side == 'amounts')
        <div style="color: transparent">.</div>
    @else
        <div><strong style="text-decoration: underline">{{ $account->name }}</strong></div>
    @endif  --}}
    <div>
        @if ($side == 'amounts')
            @if ($account->balance(false, $year->id))
                <div class="amount">{{ $account->balance(true, $year->id) }}</div>
                @else
                <div style="color: transparent">.</div>
            @endif
        @else
            <div class="">
                <span class="show-print" style="{{ $account->isPrimary() ? 'text-decoration: underline; font-weight: bold;' : '' }}">
                    @for($i = 0; $i < $account->parents()->count(); $i++)
                        <span>-</span>
                    @endfor
                    <span>{{ $account->name }}</span>
                </span>
            </div>
        @endif
    </div>
    @foreach ($account->children as $child)
        @if($child->balance(true, $year->id))
        @component('accounting::years._accounts_children')
            @slot('side', $side)
            @slot('year', $year)
            @slot('account', $child)
        @endcomponent
        @endif
    @endforeach
    @if ($account->isPrimary())
        @if ($side == 'amounts')
            <div style="border-top: 4px double; border-bottom: 4px double; margin: 15px 0px;">{{ $account->balances(true, $year->id) }} </div>
        @else
            <div style="border-top: 4px double transparent; border-bottom: 4px double transparent; margin: 15px 0px;" class="">@lang('accounting::accounting.total') <strong style="">({{ $account->name }})</strong></div>
        @endif
    @endif
@endif