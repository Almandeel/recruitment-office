@php
    $selected_id = $id ?? '';
    $name = $name ?? 'debts';
@endphp
@foreach ($group->accounts as $account)
    <tr>
        <td>{{ $account->id }} - {{ $account->name() }} <input type="hidden" name="{{ $name }}_accounts[]" value="{{ $account->id }}"></td>
        <td><button type="button" class="btn btn-danger btn-xs btn-remove-row"><i class="fa fa-trash"></i></button></td>
    </tr>
@endforeach
@foreach ($group->groups as $grp)
    @component('accounting::years._accounts_rows')
        @slot('id', $selected_id)
        @slot('group', $grp)
    @endcomponent
@endforeach