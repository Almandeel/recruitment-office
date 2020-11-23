@php
	$children_ids = $account->children->pluck('id')->toArray();
@endphp
<li id="laeaf-{{ $account->id }}" class="{{ $account->isPrimary() ? 'folder' : '' }} {{ $account->id <= 6 || in_array($account->id, $children_ids) ? 'expanded' : '' }}">
	<a target="_self" href="{{ route('accounting.tree', ['account_id' => $account->id]) }}" data-account-id="{{ $account->id }}"  class="treeview-link">{{ $account->number }} - {{ $account->name }}</a>
    @if ($account->children->count())
		<ul>
			@foreach ($account->children as $child)
				@component('accounting::components.accounting-tree-leaf')
					@slot('account', $child)
				@endcomponent
			@endforeach
		</ul>		
	@endif
</li>