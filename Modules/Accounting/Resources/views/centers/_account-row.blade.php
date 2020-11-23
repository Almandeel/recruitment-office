@php
    $center = $center ?? null;
@endphp
@if ($center)
@if (!$center->accounts->contains($account->id))
<tr>
    <td>{{ $account->number }}</td>
    <td>{{ $account->name }}</td>
    <td>
        <form class="accountForm" action="{{ route('centers.update', $center) }}" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="account_id" value="{{ $account->id }}" />
            <input type="hidden" name="operation" value="add" />
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-plus"></i>
                <span class="d-xs-none d-lg-inline">@lang('accounting::global.add')</span>
            </button>
        </form>
    </td>
</tr>
@endif
@foreach ($account->children->sortBy('number') as $child)
    @component('accounting::centers._account-row')
        @slot('center', $center)
        @slot('account', $child)
    @endcomponent
@endforeach
@endif