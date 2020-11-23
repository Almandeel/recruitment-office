<div class="modal fade" id="accountModal" tabindex="-1" role="dialog" aria-labelledby="accountsLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title pull-left" id="accountModalLabel">إضافة حساب</h5>
        <button type="button" class="close pull-right" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form class="accountForm" action="{{ route('accounts.store') }}" method="POST">
          @csrf
          <div class="modal-body">
              <div class="form-group">
                  <label>الاسم</label>
                  <input  class="form-control name" autocomplete type="text" name="name" id="name" placeholder="الاسم" required>
			  </div>
			  <div class="form-group">
				<label>@lang('accounting::global.address')</label>
				<input class="form-control name" autocomplete="off" type="text" id="address" name="address" placeholder="@lang('accounting::global.address')" required />
			</div>
			
			<div class="form-group">
				<label>@lang('accounting::global.phone')</label>
				<input class="form-control" autocomplete="off" type="number" id="phones" name="phones" placeholder="@lang('accounting::global.phone')" required />
			</div>
			  <div class="row form-group">
			  		<div class="col">
						<label>النوع</label>
						<select  class="form-control type" name="type" id="type" required>
							@foreach (array_keys(\Modules\Accounting\Models\Account::TYPES) as $type)
								<option value="{{ $type }}">{{ \Modules\Accounting\Models\Account::TYPES[$type] }}</option>
							@endforeach
						</select>
					</div>
					<div class="col">
						<label>الجانب</label>
						<select  class="form-control side" name="side" id="side" required>
							@foreach (array_keys(\Modules\Accounting\Models\Account::SIDES) as $side)
								<option value="{{ $side }}">{{ \Modules\Accounting\Models\Account::SIDES[$side] }}</option>
							@endforeach
						</select>
					</div>
			  </div>
				<div class="row form-group">
					<div class="col">
						<label>الحساب الرئيسي</label>
						<select  class="form-control select main_account" name="main_account" id="main_account" required>
								{{--  @foreach (roots(true) as $root)
									@component('accounting::accounts._options')
										@slot('account', $root)
									@endcomponent
								@endforeach  --}}
						</select>
					</div>
					{{--  <div class="col">
						<label>الحساب الختامي</label>
						<select  class="form-control select2 final_account" name="final_account" id="final_account" required>
								<option>لا يوجد</option>
								@component('accounting::accounts._options')
									@slot('account', finalAccount())
								@endcomponent
						</select>
					</div>  --}}
				</div>
              {{--  <div class="form-group">
                  <label>الرصيد الافتتاحي</label>
                  <input  class="form-control" type="number" name="opening_balance" placeholder="الرصيد الافتتاحي" />
			  </div>  --}}
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
              <button type="submit" class="btn btn-primary">حفظ</button>
          </div>
	  </form>
	  <section class="preview d-none">
		<div class="modal-body">
			<table class="table table-striped">
				<tr>
					<th style="width: 20%;">المعرف</th>
					<td class="id"></td>
				</tr>
				<tr>
					<th>الاسم</th>
					<td class="name"></td>
				</tr>
				<tr>
					<th>النوع</th>
					<td class="type"></td>
				</tr>
				<tr>
					<th>الجانب</th>
					<td class="side"></td>
				</tr>
				<tr>
					<th>الحساب الرئيسي</th>
					<td class="parent"></td>
				</tr>
				{{--  <tr>
					<th>الحساب الختامي</th>
					<td class="final"></td>
				</tr>  --}}
				<tr>
					<th>الرصيد</th>
					<td class="balance"></td>
				</tr>
			</table>
		</div>
		<div class="modal-footer">
			@permission('accounts-delete')
				<form id="deleteAccount" action="" method="post" class="d-none">
					<button type="button" class="btn btn-danger delete">
						<i class="fa fa-trash"></i>
						<span>حذف</span>
					</button>
					@csrf
					@method('DELETE')
				</form>
			@endpermission
			<button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
		</div>
	</section>
    </div>
  </div>
</div>


<script>
	$(function(){
		let field_name = $('#accountModal #name');
		let field_type = $('#accountModal #type');
		let field_side = $('#accountModal #side');
		let field_main_account = $('#accountModal #main_account');
		let field_final_account = $('#accountModal #final_account');
		/*
		let _options = ``;
		let _accounts = 'Modules\Accounting\Models\Account::secondaryAccounts()->toJson()';
		for(let index = 0; index < _accounts.length; index++)
		{
			let _account = _accounts[index];
			_options += `
				<option value="` + _account.id + `">` + _account.nnumber + `-` + _account.name + `</option>
			`;
		}
		field_main_account.html(_options)
		*/
	})
	$('.show-modal-account').click(function() {
		let account = new Account($(this).data())
		showAccountModal(account)
	})

	{{--  function showAccountModal(name = null, type = null, side = null, main_account = null, final_account = null, action = null, method = null, view = 'create', name = null){  --}}
	function showAccountModal(account){
		if(!account.mainIsPrimary() && account.view == 'create'){
			sweet('عذرا', 'لا يمكن اضافة حساب الى حساب فرعي')
		}else{
			if(account.view == 'preview'){
				$('#accountModal .modal-title').text('بيانات الحساب: ' + account.name)
				$('#accountModal .preview .id').text(account.id)
				$('#accountModal .preview .name').text(account.name)
				$('#accountModal .preview .type').text(account.type)
				$('#accountModal .preview .side').text(account.side)
				$('#accountModal .preview .parent').text(account.mainName)
				$('#accountModal .preview .final').text(account.finalName)
				$('#accountModal .preview .balance').text(account.balance)
				
				$('#accountModal .preview').removeClass('d-none')
				$('#accountModal .preview').addClass('d-block')
				
				$('#accountModal form.accountForm').addClass('d-none')
				$('#accountModal form.accountForm').removeClass('d-block')

				$('#accountModal .preview form#deleteAccount').addClass('d-none')
				$('#accountModal .preview form#deleteAccount').removeClass('d-inline-block')
			}else if(account.view == 'confirm-delete'){
				$('#accountModal .modal-title').text('تأكيد حذف حساب')
				$('#accountModal .preview .id').text(account.id)
				$('#accountModal .preview .name').text(account.name)
				$('#accountModal .preview .type').text(account.type)
				$('#accountModal .preview .side').text(account.side)
				$('#accountModal .preview .parent').text(account.mainName)
				$('#accountModal .preview .final').text(account.finalName)
				$('#accountModal .preview .balance').text(account.balance)
	
				$('#accountModal .preview form#deleteAccount').attr('action', account.action)
				
				$('#accountModal .preview').removeClass('d-none')
				$('#accountModal .preview').addClass('d-block')
				
				$('#accountModal form.accountForm').addClass('d-none')
				$('#accountModal form.accountForm').removeClass('d-block')

				$('#accountModal .preview form#deleteAccount').removeClass('d-none')
				$('#accountModal .preview form#deleteAccount').addClass('d-inline-block')
			}else{
				$('#accountModal .preview').addClass('d-none')
				$('#accountModal .preview').removeClass('d-block')

				$('#accountModal .preview form#deleteAccount').addClass('d-none')
				$('#accountModal .preview form#deleteAccount').removeClass('d-inline-block')
				
				$('#accountModal form.accountForm').removeClass('d-none')
				$('#accountModal form.accountForm').addClass('d-block')
			}

			if(account.view == 'update'){
				$('#accountModal .modal-title').text('تعديل حساب')
			}
			if(account.view == 'create'){
				$('#accountModal .modal-title').text('اضافة حساب')
			}
	
			if(account.action && account.view == 'update'){
				$('#accountModal form.accountForm').attr('action', account.action)
			}else if(account.view == 'create'){
				$('#accountModal form.accountForm').attr('action', "{{ route('accounts.store') }}")
			}
	
			let field_method = $("#accountModal form.accountForm input#_method");
			if(account.method){
				if(field_method.length){
					field_method.val(account.method)
				}else{
					$('#accountModal form.accountForm').append('<input type="hidden" name="_method" id="_method" value="' + account.method + '" />')
				}
			}else{
				if(field_method.length) field_method.remove()
			}
			
			if(account.name){
				$('#accountModal input[name="name"]').val(account.name)
			}else{
				$('#accountModal input[name="name"]').val('')
			}
			
			
			if(account.mainId){
				let main_account = account.mainId + '';
				if(main_account.startsWith('{{ \Modules\Accounting\Models\Account::ACCOUNT_CUSTOMERS }}')){
					$('#accountModal input[name="address"]').attr('required', true)
					$('#accountModal input[name="phones"]').attr('required', true)
					
					if(account.address){
						$('#accountModal input[name="address"]').val(account.address)
					}
					if(account.phones){
						$('#accountModal input[name="phones"]').val(account.phones)
					}
				}else{
					$('#accountModal input[name="address"]').val('')
					$('#accountModal input[name="address"]').removeAttr('required')
					$('#accountModal input[name="address"]').parent().hide()
					
					$('#accountModal input[name="phones"]').val('')
					$('#accountModal input[name="phones"]').removeAttr('required')
					$('#accountModal input[name="phones"]').parent().hide()
				}
			}
	
			if(account.type){
				$('#accountModal select[name="type"]').val(account.type)
			}else{
				$("#accountModal #type").val($("#accountModal #type option:first").val());
			}
	
			if(account.side){
				$('#accountModal select[name="side"]').val(account.side)
			}else{
				$("#accountModal #side").val($("#accountModal #side option:first").val());
			}
			
			if(account.mainId){
				$('#accountModal select[name="main_account"]').val(account.mainId)
			}else{
				$("#accountModal #main_account").val($("#accountModal #main_account option:first").val());
			}
	
			if(account.finalId){
				$('#accountModal select[name="final_account"]').val(account.finalId)
			}else{
				$("#accountModal #final_account").val($("#accountModal #final_account option:first").val());
			}
	
			let defaultMainOption = $('#accountModal select[name="main_account"] option[value="لا يوجد"]');
			if(account.isDefault() && (account.view == 'update')){
				let fields = $('#accountModal select, #accountModal input');
				for(let index = 0; index < fields.length; index++){
					$(fields[index]).attr('readonly', true)
				}
				$('#accountModal input[name="name"]').attr('readonly', false)
			}else{
				let fields = $('#accountModal select, #accountModal input');
				for(let index = 0; index < fields.length; index++){
					$(fields[index]).attr('readonly', false)
				}
			}
			$('#accountModal').modal('show')
		}
	}

	{{--  function Account(name = null, type = null, side = null, main_account = null, final_account = null, action = null, method = null, view = 'create', id = null){
		this.id= id;
		this.name = name;
		this.type = type;
		this.side = side;
		this.main_account = main_account;
		this.final_account = final_account;
		this.mainId = mainId;
		this.mainName = null;
		this.action = action;
		this.method = method;
		this.view= view;
	}  --}}

	function Account(data = null){
		this.id = data.id ? data.id : null;
		this.number = data.number ? data.number : null;
		this.name = data.name ? data.name : null;
		this.address = data.address ? data.address : null;
		this.phones = data.phones ? data.phones : null;
		this.type = data.type ? data.type : null;
		this.side = data.side ? data.side : null;
		this.mainId = data.mainId ? data.mainId : null;
		this.mainType = data.mainType ? data.mainType : null;
		this.mainName = data.mainName ? data.mainName : null;
		this.finalId = data.finalId ? data.finalId : null;
		this.finalName = data.finalName ? data.finalName : null;
		this.action = data.action ? data.action : null;
		this.method = data.method ? data.method : null;
		this.view = data.view ? data.view : 'create';
		this.balance = data.balance ? data.balance : 0;
		this.defaults = @json(\Modules\Accounting\Models\Account::DEFAULTS);
		this.isDefault = function(){
			return this.defaults.includes(this.id);
		};
		this.isRoot = function(){
			return this.id <= 6;
		};
		this.isPrimary = function(){
			return this.type == {{ \Modules\Accounting\Models\Account::TYPE_PRIMARY }};
		};
		this.mainIsPrimary = function(){
			return this.mainType == {{ \Modules\Accounting\Models\Account::TYPE_PRIMARY }};
		};
	}
</script>