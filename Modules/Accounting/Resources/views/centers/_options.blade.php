@if ($center)
    @php
        $selected_id = request('account_id') ? request('account_id') : $id ?? '';
    @endphp
    <option value="{{ $center->id }}" {{ $center->id == $selected_id ? 'selected' : '' }}>
        {{ $center->number }} - {{ $center->name }}
    </option>
    @foreach ($center->children as $acc)
        @component('accounting::accounts._options')
            @slot('id', $selected_id)
            @slot('account', $acc)
        @endcomponent
    @endforeach
@endif