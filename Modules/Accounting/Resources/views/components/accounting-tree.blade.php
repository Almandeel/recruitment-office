<div class="treeview">
    <ul id="treeData" style="display: none;">
        @foreach ($accounts as $account)
            @component('accounting::components.accounting-tree-leaf')
                @slot('account', $account)
            @endcomponent
        @endforeach
    </ul>
</div>