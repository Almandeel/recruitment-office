@if ($account)
    @php
        $selected_id = request($sid ?? 'account_id') ? request($sid ?? 'account_id') : $id ?? '';
    @endphp
    <option value="{{ $account->id }}" {{ $account->id == $selected_id ? 'selected' : '' }} {{ ($account->isPrimary()) ? 'disabled' : '' }}>
        {{ $account->number }} - {{ $account->name }}
    </option>
    @foreach ($account->children as $acc)
        @component('accounting::components.account_options')
            @slot('id', $selected_id)
            @slot('account', $acc)
        @endcomponent
    @endforeach
@endif