@if ($account)
    @php
        $selected_id = request('account_id') ? request('account_id') : $id ?? '';
    @endphp
    <option value="{{ $account->id }}" {{ $account->id == $selected_id ? 'selected' : '' }}>
        {{ $account->number }} - {{ $account->name }}
    </option>
    @foreach ($account->children as $acc)
        @component('accounting::accounts._options')
            @slot('id', $selected_id)
            @slot('account', $acc)
        @endcomponent
    @endforeach
@endif