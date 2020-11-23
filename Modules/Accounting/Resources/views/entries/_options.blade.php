@php
    $selected_id = $id ?? '';
@endphp
<option value="{{ $account->id }}" {{ $account->id == $selected_id ? 'selected' : '' }}>
    {{ $account->number . '-' . $account->name }}
</option>
@foreach ($account->children as $child)
    @component('accounting::entries._options')
        @slot('id', $selected_id)
        @slot('account', $child)
    @endcomponent
@endforeach