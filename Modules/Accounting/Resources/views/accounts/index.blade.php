@extends('accounting::layouts.master',[
    'title' => 'شجرة الحسابات',
    'accounting_modals' => [
        'account'
    ],
    'select2' => true,
    'treeview' => true,
    'models_js' => ['Account', 'Accounts', 'Entry', 'Entries'],
    'crumbs' => [['#', 'شجرة الحسابات']],
])

@push('content')
    <div class="row">
        <div class="col col-lg-12">
            @component('accounting::components.widget')
                @slot('widgets', ['maximize'])
                @slot('tools')
                    {{--  <div class="form-group d-inline-block">
                        <input type="text" id="search" class="form-control" placeholder="إبحث من هنا ...">
                    </div>  --}}
                @endslot
                @slot('title', 'شجرة الحسابات')
                @slot('body')
                    {{--  @component('accounting::components.accounting-tree')
                        @slot('accounts', $roots)
                    @endcomponent  --}}
                    <div class="treeview">
                        <ul id="treeData" style="display: none;">
                        </ul>
                    </div>
                @endslot
            @endcomponent
        </div>
    </div>
@endpush
@push('head')
    <script>
        $(function(){
            let leafs = ``;
            leafs += accountLeaf(accounts.assets());
            leafs += accountLeaf(accounts.liabilities());
            leafs += accountLeaf(accounts.owners());
            leafs += accountLeaf(accounts.expenses());
            leafs += accountLeaf(accounts.revenues());
            leafs += accountLeaf(accounts.finals());

            $('#treeData').html(leafs);
        })
        function accountLeaf(account){
            let leaf = ``;
            let url = `{{ route('accounts.show', '::account_id') }}`;
            let children = account.children();
            url = url.replace('::account_id', account.get('id'));
            leaf += `<li id="laeaf-` + account.id + `" class="`;
            if(account.isPrimary()) leaf += `folder `;
            if(account.get('id') <= 6) leaf += ` expanded`; 
            leaf += `">`
            leaf += `<a target="_self" href="` + url + `" data-account-id="` + account.get('id') + `"  class="treeview-link">` + account.get('number') + `-` + account.get('name') + `</a>`;
            if (children.length){
                leaf += `<ul>`;
                for(let index = 0; index < children.length; index++){
                    leaf += accountLeaf(children[index]);
                }
               leaf += ` </ul>`;	
            }
            leaf += `</li>`;

            return leaf;
        }
    </script>
@endpush
@push('foot')
    <script>
        $(function(){
            $('input#search').change(function(){
                let niddle = $(this).val();
                let titles = $('.fancytree-title');
                for(let index = 0; index < titles.length; index++){
                    let title = $(titles[index]);
                    let li = title.closest('li');
                    let text = title.text();
                    if(niddle.length > 0){
                        if(text.includes(niddle)){
                            li.show()
                        }else{
                            li.hide()
                        }
                    }else{
                        li.show()
                    }
                    
                }
            })
        })
    </script>
@endpush