@php
/*
    if(isset($accounting_modals)){
        $accounting_modals[] = 'expense';
        $accounting_modals[] = 'transfer';
    }else{
        $accounting_modals = [
            'expense', 'transfer'
        ];
    }
    */
@endphp
@extends('layouts.master', [
    // 'accounting_modals' => $accounting_modals
])
@section('content')
    <section class="content">
        @stack('content')
    </section>
    <form id="safeableForm" action="{{ route('entries.store') }}" method="POST">
        @csrf
        <input type="hidden" name="operation" value="safeable" />
        <input type="hidden" name="safeable_type" />
        <input type="hidden" name="safeable_id" />
        <input type="hidden" name="safe_id" />
        <input type="hidden" name="account_id" value="0"/>
        <input type="hidden" name="amount" />
        <input type="hidden" name="details" />
    </form>
@endsection
@push('foot')
    <script>
        $(function(){
            $('*[data-toggle="entry"]').bind('ajaxStart', function(){
                $(this).text('...');
                alert('started')
            }).bind('ajaxStop', function(){
                $(this).text('hide');
                alert('stoped')
            });
            $(document).on('click', '*[data-toggle="entry"]', function(e){
                e.preventDefault()
                let that = $(this);
                let icon = that.children('i.fa')
                if(!(icon.hasClass('fa-sync') || icon.hasClass('fa-spinner'))){
                    icon.addClass('fa-spinner')
                }
                icon.addClass('fa-spin')
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-Token' : '{{ csrf_token() }}'
                    }
                })
                data = {
                    ajax: true,
                    operation: that.data('operation'),
                    entry_id: that.data('entry-id'),
                };

                $.ajax({
                    method: 'POST',
                    url: '{{ route("entries.store") }}',
                    dataType: 'JSON',
                    data: data,
                    success: function(response){
                        icon.removeClass('fa-spinner')
                        icon.removeClass('fa-spin')
                        console.log(response)
                        if(response.status == 201){
                            let params = getURLParameters();
                            if(params.length){
                                window.location.href = '{{ url("") }}' + window.location.pathname + '?' + params.join('&');
                            }else{
                                window.location.href = '{{ url("") }}' + window.location.pathname;
                            }
                        }else{
                            sweet("@lang('accounting::global.operation_failed_title')", "@lang('accounting::global.operation_failed_text')", 'error')
                        }
                    }
                })
            })
            if($('*[data-confirm="true"]').length){
                $(document).on('click', '*[data-confirm="true"]', function(e){
                    e.preventDefault()
                    let that = $(this);
                    if(that.data('safeable-type') && that.data('safeable-id') && that.data('amount')){
                        let form = $('form#safeableForm');
                        let accountable= true;
                        $('form#safeableForm input[name=safeable_type]').val(that.data('safeable-type'));
                        $('form#safeableForm input[name=safeable_id]').val(that.data('safeable-id'));
                        $('form#safeableForm input[name=amount]').val(that.data('amount'));
                        
                        if(that.data('account-id')){
                            $('form#safeableForm input[name=account_id]').val(that.data('account-id'));
                            accountable = false;
                        }else{
                            $('form#safeableForm input[name=account_id]').val(0);
                            accountable = true;
                        }
                        
                        if(that.data('safe-id')){
                            $('form#safeableForm input[name=safe_id]').val(that.data('safe-id'));
                        }else{
                            $('form#safeableForm input[name=safe_id]').val(0);
                        }
                        
                        if(that.data('action')){
                            form.attr('action', that.data('action'))
                        }else{
                            form.attr('action', "{{ route('entries.store') }}")
                        }
    
                        let field_method = $("#safeableModal form.safeableForm input#_method");
                        if(that.data('method')){
                            if(field_method.length){
                                field_method.val(that.data('method'))
                            }else{
                                form.append('<input type="hidden" name="_method" id="_method" value="' + that.data('method') + '" />')
                            }
                        }else{
                            if(field_method.length) field_method.remove()
                        }
    
                        let select_safes = `<select id="safeId" name="safe_id" class="form-control" required>`;
                            {{--  @foreach (safes() as $safe)
                                select_safes += `<option value="{{ $safe->id }}">{{ $safe->account->number  . '-' . $safe->name }}</option>`;
                            @endforeach  --}}
                        select_safes += `</select>`;
    
                        let title = that.data('title') ? that.data('title') : '@lang("accounting::global.confirm_voucher_title")';
                        let icon = that.data('icon') ? that.data('icon') : 'warning';
                        let text = that.data('text') ? that.data('text') : '@lang("accounting::global.confirm_voucher_text")';
                        if(that.data('safeable-type') == 'Modules\\Accounting\\Models\\Expense'){
                            title   = '@lang("accounting::global.confirm_expense_title")';
                            icon    = 'warning';
                            text    = '@lang("accounting::global.confirm_expense_text")';
                            let safeable_details = '@lang("accounting::entries.details_expense")';
                            safeable_details = safeable_details.replace('__id__', that.data('safeable-id'));
                            $('form#safeableForm input[name=details]').val(safeable_details);
                        }
                        else if(that.data('safeable-type') == 'Modules\\Accounting\\Models\\Voucher'){
                            title   = '@lang("accounting::global.confirm_voucher_title")';
                            icon    = 'warning';
                            text    = '@lang("accounting::global.confirm_voucher_text")';
    
                            let safeable_details = '@lang("accounting::entries.details_voucher")';
                            safeable_details = safeable_details.replace('__type__', that.data('type'));
                            safeable_details = safeable_details.replace('__id__', that.data('safeable-id'));
                            $('form#safeableForm input[name=details]').val(safeable_details);
                        }
                        let html = '<div class="form-group"><label>@lang("accounting::global.safe")</label>' + select_safes + '</div>';
                        
                        if(accountable){
                            let select_accounts = `<select id="accountId" name="account_id" class="form-control" required>`;
                                select_accounts += `<option value="0">@lang("accounting::accounts.choose")</option>`;
                                {{--  @foreach (accounts(true, true) as $account)
                                    select_accounts += `<option value="{{ $account->id }}">{{ $account->number  . '-' . $account->name }}</option>`;
                                @endforeach  --}}
                            select_accounts += `</select>`;
                            html += `<div class="form-group"><label>@lang("accounting::global.account")</label>` + select_accounts + `</div>`;
                        }
    
                        Swal.fire({
                            title: title,
                            text: text,
                            icon: icon,
                            html: html,
                            confirmButtonText: '@lang("accounting::global.submit")',
                            preConfirm: () => {
                                let safeId = Swal.getPopup().querySelector('#safeId').value
                                let accountId = Swal.getPopup().querySelector('#accountId').value
                                if (accountId == 0) {
                                    Swal.showValidationMessage(`{{ str_replace(':attribute', __("accounting::validation.attributes.account"), __("accounting::validation.required")) }}`)
                                }
                                return {safeId: safeId, accountId: accountId}
                            }
                        }).then((result) => {
                            $('form#safeableForm input[name=safe_id]').val(result.value.safeId);
                            $('form#safeableForm input[name=account_id]').val(result.value.accountId);
                            $('form#safeableForm').submit();
                        })
                    }else{
                        let title = that.data('title') ? that.data('title') : '@lang("accounting::global.confirm_delete_title")';
                        let icon = that.data('icon') ? that.data('icon') : 'warning';
                        let text = that.data('text') ? that.data('text') : '@lang("accounting::global.confirm_delete_text")';
                        Swal.fire({
                            title: title,
                            text: text,
                            icon: icon,
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            cancelButtonText: '@lang("accounting::global.btn_cancel")',
                            confirmButtonText: '@lang("accounting::global.btn_confirm")',
                        }).then((result) => {
                            if (result.value) {
                                if(that.prop("tagName") == 'A'){
                                    window.location.href = that.attr('href')
                                }else{
                                    if(that.data('callback')){
                                        executeFunctionByName(that.data('callback'), window)
                                    }
                                    else if(that.data('form')){
                                        $(that.data('form')).submit()
                                    }
                                    else{
                                        that.closest('form').submit()
                                    }
                                }
                            }	
                        })
                    }
                })
            }
            
            $(document).on('click', '.confirm', function(e){
				e.preventDefault()
				let that = $(this);
				let title = that.data('title') ? that.data('title') : '@lang("accounting::global.confirm_delete_title")';
				let icon = that.data('icon') ? that.data('icon') : 'warning';
				let text = that.data('text') ? that.data('text') : '@lang("accounting::global.confirm_delete_text")';
				Swal.fire({
					title: title,
					text: text,
					icon: icon,
					showCancelButton: true,
					confirmButtonColor: '#3085d6',
					cancelButtonColor: '#d33',
					cancelButtonText: '@lang("accounting::global.btn_cancel")',
					confirmButtonText: '@lang("accounting::global.btn_confirm")',
				}).then((result) => {
					if (result.value) {
                        if(that.prev().is("a")){
                            window.location.href = that.attr('href')
                        }else{
                            if(that.data('callback')){
                                executeFunctionByName(that.data('callback'), window)
                            }
                            else if(that.data('form')){
                                $(that.data('form')).submit()
                            }
                            else{
                                that.closest('form').submit()
                            }
                        }
					}	
				})
			})
        })
    </script>
@endpush