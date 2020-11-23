@php
    $selected_id = request($sid ?? 'account_id') ? request($sid ?? 'account_id') : $id ?? '';
@endphp
@if (isset($accounts))
    @foreach ($accounts as $acc)
        @component('accounting::components.account_options')
            @slot('id', $selected_id)
            @slot('account', $acc)
        @endcomponent
    @endforeach
@else
    @foreach (roots() as $acc)
        @component('accounting::components.account_options')
            @slot('id', $selected_id)
            @slot('account', $acc)
        @endcomponent
    @endforeach
@endif