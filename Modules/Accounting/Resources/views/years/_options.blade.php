@php
    $selected_id = $id ?? '';
    $name = $name ?? 'debts';
@endphp
@foreach ($account->accounts as $account)
    <option value="{{ $account->id }}" data-account-id="{{ $account->id }}" data-account-name="{{ $account->name }}" data-name="{{ $name }}_accounts[]" {{ $account->id == $selected_id ? 'selected' : '' }}>
        {{ $account->id }} - {{ $account->name }}
    </option>
@endforeach
@foreach ($account->accounts as $acc)
    @component('accounting::years._options')
        @slot('id', $selected_id)
        @slot('account', $acc)
    @endcomponent
@endforeach