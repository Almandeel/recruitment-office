		<!-- Main Footer -->
		<footer class="main-footer">
			<!-- To the right -->
			<div class="float-right d-none d-sm-inline">
				
			</div>
		</footer>
	</div>
	<!-- ./wrapper -->
	<!-- Logout Form -->
	<form id="logoutForm" action="{{ route('logout') }}" method="POST">@csrf</form>
	
	<!-- REQUIRED SCRIPTS -->
	<!-- Bootstrap 4 -->
	<script src="{{ asset('dashboard/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
	
	<!-- AdminLTE App -->
	{{--  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous"></script>  --}}
	<script src="{{ asset('dashboard/js/adminlte.min.js') }}"></script>
	@if (isset($summernote))
		<link rel="stylesheet" href="{{ asset('dashboard/plugins/summernote/summernote-bs4.css') }}">
		<script src="{{ asset('dashboard/plugins/summernote/summernote-bs4.min.js') }}"></script>
		<script src="{{ asset('dashboard/plugins/summernote/lang/summernote-ar-AR.min.js') }}"></script>
		<script>
			$(function () {
		    //Add text editor
		    $('#compose-textarea').summernote({
				@if(!isset($ltr)) lang: 'ar-AR' @endif
			})
		    $('textarea.summernote').summernote({
				@if(!isset($ltr)) lang: 'ar-AR' @endif
			})
		  })
		</script>
	@endif

	@if(isset($modals))
		@foreach ($modals as $modal)
			@include('modals.' . $modal)
		@endforeach
	@endif

	@if(!isset($noForm))
		<!-- Parsley -->
		<script src="{{ asset('dashboard/plugins/parsleyjs/parsley.js')}}"></script>
		<script src="{{ asset('dashboard/plugins/parsleyjs/i18n/ar.js')}}"></script>
		<script src="{{ asset('dashboard/js/snippts.js')}}"></script>
		{{--  /*
			// File size validation
			//<input type="file" name="Upload" required="required" parsley-filemaxsize="Upload|1.5" />
			window.ParsleyValidator
			.addValidator('filemaxsize', function (val, req) {
			var reqs = req.split('|');
			var input = $('input[type="file"][name="'+reqs[0]+'"]');
			var maxsize = reqs[1];
			var max_bytes = maxsize * 1000000, file = input.files[0];
			
			return file.size <= max_bytes; }, 32) .addMessage('en', 'filemaxsize' , 'The File size is too big.' )
				.addMessage('de', 'filemaxsize' , 'Die Datei ist zu groß.' );
		*/  --}}
		<link href="{{ asset('dashboard/plugins/jquery-editable-select/dist/jquery-editable-select.min.css') }}" rel="stylesheet">
		<script src="{{ asset('dashboard/plugins/jquery-editable-select/dist/jquery-editable-select.min.js') }}"></script>
		<script src="{{ asset('dashboard/plugins/select2/select2.full.min.js') }}"></script>
		<script src="{{ asset('dashboard/plugins/select2/i18n/ar.js') }}"></script>
		<link href="{{ asset('dashboard/plugins/select2/select2.min.css') }}" rel="stylesheet">
		<link href="{{ asset('dashboard/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}" rel="stylesheet">
		@if (!auth()->guard('office')->check())
		<script>
			$(function(){
				$('select.editable').editableSelect();
				$('select.select2').select2();
				$('form').parsley();
				let table_attachments = $('.table-attachments')
				if(table_attachments.length){
					table_attachments.closest('form').attr('enctype', "multipart/form-data")
				}
				let input_file = $('input[type=file]')
				if(input_file.length){
					input_file.closest('form').attr('enctype', "multipart/form-data")
				}
				
				let select_safes = $('select.safes')
				if(select_safes.length){
					let safes_options = ``;
					{{--  @foreach (safes() as $safe)
						safes_options += `<option value="{{ $safe->id }}">{{ $safe->account->number  . '-' . $safe->name }}</option>`;
					@endforeach
					select_safes.html(safes_options)  --}}
				}

				let select_accounts = $('select.accounts')
				if(select_accounts.length){
					let accounts_options = ``;
					accounts_options += `<option value="">@lang("accounting::accounts.choose")</option>`;
					{{--  @foreach (accounts(true, true) as $account)
						accounts_options += `<option value="{{ $account->id }}">{{ $account->number  . '-' . $account->name }}</option>`;
					@endforeach  --}}
					// select_accounts.html(accounts_options)
					/*
					if(select_accounts.data('selected')){
						select_accounts.val(select_accounts.data('selected'))
					}
					*/
					for(let index = 0; index < select_accounts.length; index++){
						let select_account = $(select_accounts[index])
						if(select_account.data('selected')){
							select_account.val(select_account.data('selected'))
						}
					}
				}
				
		
				window.Parsley.on('form:success', function(){
					$('button[type="submit"]').attr('disabled', true)
				})
			});
			
		</script>
		@endif
	@endif
	@if(isset($external_office_modals))
		@foreach ($external_office_modals as $modal)
			@include('externaloffice::modals.' . $modal)
		@endforeach
	@endif
	@if(isset($accounting_modals))
		@foreach ($accounting_modals as $modal)
			@include('accounting::modals.' . $modal)
		@endforeach
	@endif
	@if (isset($lightbox))
		<link rel="stylesheet" type="text/css" href="{{ asset('dashboard/plugins/ekko-lightbox/ekko-lightbox.css') }}"/>
		<script src="{{ asset('dashboard/plugins/ekko-lightbox/ekko-lightbox.min.js') }}"></script>
		<script>
			$(function () {
				$(document).on('click', '[data-toggle="lightbox"]', function(event) {
					event.preventDefault();
					$(this).ekkoLightbox({
						alwaysShowClose: true
					});
				});
			});
		</script>
	@endif
	@if(isset($treeview))
		<script src="{{ asset('dashboard/plugins/fancytree/dist/jquery.fancytree-all-deps.min.js') }}"></script>
		<script src="{{ asset('dashboard/plugins/fancytree/3rd-party/extensions/contextmenu/js/jquery.fancytree.contextMenu.js') }}"></script>
		<script src="//cdn.jsdelivr.net/npm/jquery-contextmenu@2.9.2/dist/jquery.contextMenu.min.js"></script>
		<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/jquery-contextmenu@2.9.2/dist/jquery.contextMenu.min.css" />
		<link rel="stylesheet" href="{{ asset('dashboard/plugins/fancytree/dist/skin-win8/ui.fancytree.min.css') }}">
		<style>
			ul.fancytree-container,
			ul.fancytree-container:focus
			{
				border: none !important;
				outline: none !important;
			}
		</style>
		<script>
			$(function(){
				{{--  $(".treeview").fancytree({
					extensions: ["contextMenu"],
					contextMenu: {
						menu: {
							"edit": { "name": "@lang('global.edit')", "icon": "edit" },
							"delete": { "name": "@lang('global.delete')", "icon": "delete" },
							actions: function(node, action, options) {
								console.log("Selected action '" + action + "' on node " + node + ".");
							}
						},
					},
				});  --}}
				$(".treeview").fancytree({
					extensions: ["edit", "filter"],
					rtl: {!! !isset($ltr) == 'rtl' ? 'true' : 'false'  !!},
					click: function(event, data){ // allow re-loads
						var node = data.node,
							orgEvent = data.originalEvent;

						if(node.isActive() && node.data.href){
							window.open(node.data.href, (orgEvent.ctrlKey || orgEvent.metaKey) ? "_blank" : node.data.target);
						}
					}
				});
				$(document).click(function(event) {
					var node = $.ui.fancytree.getNode(event);
				});
			})
		</script>
	@endif
	@if(isset($guides))
		<style>
			.pulse {
				box-shadow: 0 0 0 0 rgba(0, 0, 0, 1);
				transform: scale(1);
				animation: pulse 2s infinite;
			}

			@keyframes pulse {
				0% {
					transform: scale(0.95);
					box-shadow: 0 0 0 0 rgba(0, 0, 0, 0.7);
				}

				70% {
					transform: scale(1);
					box-shadow: 0 0 0 10px rgba(0, 0, 0, 0);
				}

				100% {
					transform: scale(0.95);
					box-shadow: 0 0 0 0 rgba(0, 0, 0, 0);
				}
			}
			#btn-guides{
				display: none;
				top: 100px;
				position: fixed;
				z-index: 9999;
				left: 50px;
				width: 50px;
				height: 50px;
				border-radius: 50%;
				{{-- line-height: 50px; --}}
			}
		</style>
		<button id="btn-guides" class="btn btn-default pulse">
			<i class="fa fa-question"></i>
		</button>
		<link rel="stylesheet" href="{{ asset('dashboard/plugins/driver/dist/driver.min.css') }}">
		<script src="{{ asset('dashboard/plugins/driver/dist/driver.min.js') }}"></script>
		<script>
			const driver = new Driver({
				animate: true, // Animate while changing highlighted element
				allowClose: true, // Whether clicking on overlay should close or not
				doneBtnText: '@lang("global.done")', // Text on the final button
				closeBtnText: '@lang("global.close")', // Text on the close button for this step
				nextBtnText: '@lang("global.next")', // Next button text for this step
				prevBtnText: '@lang("global.prev")', // Previous button text for this step
				showButtons: true, // Do not show control buttons in footer
				keyboardControl: true,
			});
			let steps = [];
			@foreach ($guides as $guide)
				var step = {
					element: '{{ $guide["element"] }}',
					popover: {
						title: '{{ $guide["title"] }}',
						description: '{{ $guide["description"] }}',
						position: '{{ $guide["position"] }}'
					}
				};
				steps.push(step)
			@endforeach
			// Define the steps for introduction
			driver.defineSteps(steps);

			// Start the introduction
			$('#btn-guides').click(function(){
			})
			driver.start();
		</script>
	@endif
	<!-- Sweet Alert 2 -->
	<script src="{{ asset('dashboard/plugins/sweetalert2/sweetalert2.all.min.js') }}"></script>
	<script>
		function sweet(title, text, icon = 'warning'){
			Swal.fire({
				title: title,
				text: text,
				icon: icon,
				confirmButtonText: '@lang("global.ok")',
			})
		}

		function sweet2(title, text, icon = 'warning'){
			Swal.fire({
				title: title,
				text: text,
				icon: icon,
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				cancelButtonText: "@lang('global.btn_cancel')",
				confirmButtonText: '@lang("global.ok")',
			}).then((result) => {
					if (result.value == true) {
						$('form#destroyContract').submit()
					}	
				})
		}
		function insertParam(key, value) {
			key = encodeURIComponent(key);
			value = encodeURIComponent(value);

			// kvp looks like ['key1=value1', 'key2=value2', ...]
			var kvp = document.location.search.substr(1).split('&');
			let i=0;

			for(; i<kvp.length; i++){
				if (kvp[i].startsWith(key + '=')) {
					let pair = kvp[i].split('=');
					pair[1] = value;
					kvp[i] = pair.join('=');
					break;
				}
			}

			if(i >= kvp.length){
				kvp[kvp.length] = [key,value].join('=');
			}

			// can return this or...
			let params = kvp.join('&');

			// reload page with new params
			document.location.search = params;
		}
		$(function(){
			let navTabs = $('ul.nav.nav-tabs')
			let tabsContent = $('div.tab-content')
			if(navTabs.length && tabsContent.length){
				$('body').append('<input type="hidden" id="active-tab" name="active_tab" value="{{ request("active_tab") }}" />')
				
				$('.nav.nav-tabs > li > a').click(function(){
					let tab = $(this).attr('href') + "";
					$('input#active-tab').val(tab.substring(6, tab.length))
					// insertParam('active_tab', tab.substring(6, tab.length))
				})	

				$('form').on('submit', (e) => {
					$(e.target).append($('input#active-tab'))
				})
			}
			$(document).on('click', '.logout', function(e){
				e.preventDefault()
				let that = $(this);
				Swal.fire({
					title: '@lang("global.sure")',
					text: "@lang('global.log_out_message')",
					icon: 'warning',
					showCancelButton: true,
					confirmButtonColor: '#3085d6',
					cancelButtonColor: '#d33',
					cancelButtonText: '@lang("global.btn_cancel")',
					confirmButtonText: "@lang('global.btn_confirm')",
				}).then((result) => {
					if (result.value) {
						if(that.data('callback')){
							executeFunctionByName(that.data('callback'), window)
						}
						else if(that.data('form')){
							$(that.data('form')).submit()
						}
						else{
							$('form#logoutForm').submit()
						}
					}	
				})
			})

			@foreach (['success', 'error', 'warning'] as $message)
				@if (session()->has($message))
					Swal.fire({
						icon: '{{ $message }}',
						title: "@lang('global.messages.' . $message)",
						text: '{{ session()->get($message) }}',
						confirmButtonText: "@lang('global.ok')",
					})
				@endif
			@endforeach

			var rtlChar = /[\u0590-\u083F]|[\u08A0-\u08FF]|[\uFB1D-\uFDFF]|[\uFE70-\uFEFF]/mg;
			$('input').keyup(function(){
				if($(this).val()){
					var isRTL = this.value.substring(0, 1).match(rtlChar);
					if(isRTL !== null) {
						$(this).css("cssText", 'direction: rtl !important;')
					}
					else {
						$(this).css("cssText", 'direction: ltr !important;')
					}
				}
			});

			$(document).on('click', '*[data-toggle="confirm"]', function(e){
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
						
						let form = that.data('form') ? $(that.data('form')) : null;
						if(form){
							if(that.data('action')){
								form.attr('action', that.data('action'))
							}
							if(that.data('method')){
								if(form.find('input[name=_method]').length){
									form.find('input[name=_method]').val(that.data('method'))
								}else{
									form.append('<input name="_method" value="'+ that.data('method') + '">')
								}
							}
							form.submit()
						}else{
							let href = that.attr('href')
							if (typeof href !== typeof undefined && href !== false){
								window.location.href = href
							}else{
								if(that.data('callback')){
									executeFunctionByName(that.data('callback'), window)
								}
								else {
									let form = that.data('form') ? $(that.data('form')) : that.closest('form');
									if(form){
										if(that.data('action')){
											form.attr('action', that.data('action'))
										}
										if(that.data('method')){
											if(form.find('input[name=_method]').length){
												form.find('input[name=_method]').val(that.data('method'))
											}else{
												form.append('<input name="_method" value="'+ that.data('method') + '">')
											}
										}
										form.submit()
									}
								}
							}
						}
					}	
				})
				
			})
		})
	</script>
	@isset($confirm_status)
		<!-- changeStatus Form -->
		<form id="changeStatusForm" method="POST">
			@csrf 
			<input type="hidden" name="id">
			<input type="hidden" name="type">
			<input type="hidden" name="status">
		</form>
		<script>
			$(document).on('click', '*[data-toggle="status"]', function(e){
				let that = $(this)
				let form = $('#changeStatusForm')
				let field_id = $('#changeStatusForm input[name=id]')
				let field_type = $('#changeStatusForm input[name=type]')
				let field_status = $('#changeStatusForm input[name=status]')
				if(that.data('id') && that.data('type') && that.data('status'))
					if(that.data('action')){
						form.attr('action', that.data('action'))
					}else{
						form.attr('action', "{{ route('status.change') }}")
					}

					let icon = that.data('icon') ? that.data('icon') : 'warning';
					let title = that.data('title') ? that.data('title') : '@lang("accounting::global.confirm_delete_title")';
					let text = that.data('text') ? that.data('text') : '@lang("accounting::global.confirm_delete_text")';
					
					if(that.data('status') == 'approve'){
						title = '@lang("global.confirm_approve_title")';
						text = '@lang("global.confirm_approve_text")';
					}
					else if(that.data('status') == 'reject'){
						title = '@lang("global.confirm_reject_title")';
						text = '@lang("global.confirm_reject_text")';
					}

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
							field_id.val(that.data('id'))
							field_type.val(that.data('type'))
							field_status.val(that.data('status'))
							form.submit()
						}	
					})
			})
		</script>
	@endisset
	@isset($confirm_safeable)
		<form id="safeableConfirmForm" action="{{ route('safes.confirm') }}" method="POST">
			@csrf
			<input type="hidden" name="safeable_type" />
			<input type="hidden" name="safeable_id" />
			<input type="hidden" name="safe_id" />
			<input type="hidden" name="account_id" value="0" />
			<input type="hidden" name="amount" />
			<input type="hidden" name="details" />
		</form>
		<script>
			$(document).on('click', '*[data-confirm="safeable"]', function(e){
				e.preventDefault()
				let that = $(this);
				if(that.data('safeable-type') && that.data('safeable-id') && that.data('amount')){
					let form = $('form#safeableConfirmForm');
					let safeable = true;
					let accountable = true;
					$('form#safeableConfirmForm input[name=safeable_type]').val(that.data('safeable-type'));
					$('form#safeableConfirmForm input[name=safeable_id]').val(that.data('safeable-id'));
					$('form#safeableConfirmForm input[name=amount]').val(that.data('amount'));
					
					if(that.data('account-id')){
						$('form#safeableConfirmForm input[name=account_id]').val(that.data('account-id'));
						accountable = false;
					}else{
						$('form#safeableConfirmForm input[name=account_id]').val(0);
						accountable = true;
					}
					
					if(that.data('safe-id')){
						$('form#safeableConfirmForm input[name=safe_id]').val(that.data('safe-id'));
						safeable = false;
					}else{
						$('form#safeableConfirmForm input[name=safe_id]').val(0);
						safeable = true;
					}
					
					
					if(that.data('action')){
						form.attr('action', that.data('action'))
					}else{
						form.attr('action', "{{ route('safes.confirm') }}")
					}

					let field_method = $("#safeableConfirmForm input#_method");
					if(that.data('method')){
						if(field_method.length){
							field_method.val(that.data('method'))
						}else{
							form.append('<input type="hidden" name="_method" id="_method" value="' + that.data('method') + '" />')
						}
					}else{
						if(field_method.length) field_method.remove()
					}

					let title = that.data('title') ? that.data('title') : '@lang("accounting::global.confirm_voucher_title")';
					let icon = that.data('icon') ? that.data('icon') : 'warning';
					let text = that.data('text') ? that.data('text') : '@lang("accounting::global.confirm_voucher_text")';
					if(that.data('safeable-type') == 'Modules\\Accounting\\Models\\Expense'){
						title   = '@lang("accounting::global.confirm_expense_title")';
						icon    = 'warning';
						text    = '@lang("accounting::global.confirm_expense_text")';
						let safeable_details = '@lang("accounting::entries.details_expense")';
						safeable_details = safeable_details.replace('__id__', that.data('safeable-id'));
						$('form#safeableConfirmForm input[name=details]').val(safeable_details);
					}
					else if(that.data('safeable-type') == 'Modules\\Employee\\Models\\Transaction'){
						title   = '@lang("accounting::global.confirm_transaction_title")';
						icon    = 'warning';
						text    = '@lang("accounting::global.confirm_transaction_text")';
						let safeable_details = '@lang("accounting::entries.details_transaction")';
						safeable_details = safeable_details.replace('__type__', that.data('type'));
						safeable_details = safeable_details.replace('__id__', that.data('safeable-id'));
						$('form#safeableConfirmForm input[name=details]').val(safeable_details);
					}
					else if(that.data('safeable-type') == 'Modules\\Accounting\\Models\\Voucher'){
						title   = '@lang("accounting::global.confirm_voucher_title")';
						icon    = 'warning';
						text    = '@lang("accounting::global.confirm_voucher_text")';

						let safeable_details = '@lang("accounting::entries.details_voucher")';
						safeable_details = safeable_details.replace('__type__', that.data('type'));
						safeable_details = safeable_details.replace('__id__', that.data('safeable-id'));
						$('form#safeableConfirmForm input[name=details]').val(safeable_details);
					}

					let html = '';
					if(safeable){
						let select_safes = `<select id="safeId" name="safe_id" class="form-control select2" required>`;
							@foreach (safes() as $safe)
								select_safes += `<option value="{{ $safe->id }}">{{ $safe->account->number  . '-' . $safe->name }}</option>`;
							@endforeach
						select_safes += `</select>`;
						html += '<div class="form-group"><label>@lang("accounting::global.safe")</label>' + select_safes + '</div>';
					}
					
					if(accountable){
						let select_accounts = `<select id="accountId" name="account_id" class="form-control select2" required>`;
							select_accounts += `<option value="0">@lang("accounting::accounts.choose")</option>`;
							@foreach (accounts(true, true) as $account)
								select_accounts += `<option value="{{ $account->id }}">{{ $account->number  . '-' . $account->name }}</option>`;
							@endforeach
						select_accounts += `</select>`;
						html += `<div class="form-group"><label>@lang("accounting::global.account")</label>` + select_accounts + `</div>`;
					}

					Swal.fire({
						title: title,
						text: text,
						icon: icon,
						html: html,
						showCancelButton: true,
						confirmButtonText: '@lang("accounting::global.submit")',
						cancelButtonText: '@lang("accounting::global.cancel")',
						cancelButtonColor: '#dc3545',
						preConfirm: () => {
							let safeId = safeable ? Swal.getPopup().querySelector('#safeId').value : null;
							let accountId = accountable ? Swal.getPopup().querySelector('#accountId').value : null;
							if (accountId == 0 && accountable) {
								Swal.showValidationMessage(`{{ str_replace(':attribute', __("accounting::validation.attributes.account"), __("accounting::validation.required")) }}`)
							}
							return {safeId: safeId, accountId: accountId}
						}
					}).then((result) => {
						if(safeable)
						$('form#safeableConfirmForm input[name=safe_id]').val(result.value.safeId);
						if(accountable)
						$('form#safeableConfirmForm input[name=account_id]').val(result.value.accountId);
						$('form#safeableConfirmForm').submit();
					})
				}
			})
		</script>
	@endisset
	@isset($models_js)
		@foreach ($models_js as $model)
			<script src="{{ asset('dashboard/js/models/' . $model .'.js') }}"></script>
		@endforeach
		@php
			$accounts = isset($accounts) ? $accounts : \Modules\Accounting\Models\Account::allToJson();
			$year = isset($year) ? $year : year();
			$year = is_null($year) ? ['id' => null] : $year;
		@endphp
		<script>
			const accounts = new Accounts({!! $accounts !!}, {{ $year['id'] }});
		</script>
	@endisset
	@stack('foot')



	<script src="{{ asset('js/app.js') }}"></script>
</body>

</html>
