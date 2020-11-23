<div class="modal fade" id="voucherModal" tabindex="-1" role="dialog" aria-labelledby="vouchersLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title pull-left" id="voucherModalLabel">@lang('accounting::vouchers.add')</h5>
        <button type="button" class="close pull-right" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form class="voucherForm" action="{{ route('vouchers.store') }}" method="POST">
          @csrf
          <div class="modal-body">
            	<div class="form-group">
					<label>@lang('accounting::global.safe')</label>
					<select id="safeId" name="safe_id" class="form-control" required>
						@foreach (safes() as $safe)
							<option value="{{ $safe->id }}">{{ $safe->name }}</option>
						@endforeach
					</select>
				</div>
            	<div class="form-group">
					<input type="hidden" id="accountId" name="account_id" value="{{ null }}">
				</div>
                <div class="form-group">
                    <label>@lang('accounting::global.amount')</label>
                    <input id="amount"  class="form-control amount" autocomplete="off" type="text" name="amount" placeholder="@lang('accounting::global.amount')">
                </div>
                <div class="form-group">
                    <label>@lang('accounting::global.details')</label>
                    <textarea id="details"  class="form-control details" autocomplete="off" type="text" name="details" placeholder="@lang('accounting::global.details')"></textarea>
                </div>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('accounting::global.close')</button>
              <button type="submit" class="btn btn-primary">@lang('accounting::global.save')</button>
          </div>
	  </form>
	  <section class="preview d-none">
		<div class="modal-body">
			<table class="table table-striped">
				<tr>
					<th style="width: 20%;">@lang('accounting::global.id')</th>
					<td class="id"></td>
				</tr>
				<tr>
					<th>@lang('accounting::global.safe')</th>
					<td class="safe"></td>
				</tr>
				<tr>
					<th>@lang('accounting::global.account')</th>
					<td class="account"></td>
				</tr>
				<tr>
					<th>@lang('accounting::global.amount')</th>
					<td class="amount"></td>
				</tr>
				<tr>
					<th>@lang('accounting::global.details')</th>
					<td class="details"></td>
				</tr>
			</table>
		</div>
		<div class="modal-footer">
			@permission('vouchers-delete')
				<form id="deleteVoucher" action="" method="post" class="d-none">
					<button type="button" class="btn btn-danger delete">
						<i class="fa fa-trash"></i>
						<span>@lang('accounting::global.delete')</span>
					</button>
					@csrf
					@method('DELETE')
				</form>
			@endpermission
			<button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('accounting::global.close')</button>
		</div>
	</section>
    </div>
  </div>
</div>


<script>
	$(function(){
		let field_safeId = $('#voucherModal #safeId');
		let field_accountId = $('#voucherModal #accountId');
		let field_amount = $('#voucherModal #amount');
	})
	$('*[data-modal=voucher]').click(function() {
		let voucher = new Voucher($(this).data())
		showVoucherModal(voucher)
	})

	function showVoucherModal(voucher){
		if(voucher.view == 'preview' || voucher.view == 'delete'){
			$('#voucherModal .preview .id').text(voucher.id)
			$('#voucherModal .preview .safe').text(voucher.safeName)
			$('#voucherModal .preview .account').text(voucher.accountName)
			$('#voucherModal .preview .amount').text(voucher.amount)
			$('#voucherModal .preview .details').text(voucher.details)
			$('#voucherModal .preview').removeClass('d-none')
			$('#voucherModal .preview').addClass('d-block')
			
			$('#voucherModal form.voucherForm').addClass('d-none')
			$('#voucherModal form.voucherForm').removeClass('d-block')
		}
		
		if(voucher.view == 'preview'){
			$('#voucherModal .modal-title').text('@lang("accounting::vouchers.details"): ' + voucher.id)

			$('#voucherModal .preview form#deleteVoucher').addClass('d-none')
			$('#voucherModal .preview form#deleteVoucher').removeClass('d-inline-block')
		}else if(voucher.view == 'delete'){
			$('#voucherModal .modal-title').text('@lang("accounting::vouchers.confirm_delete")')
			$('#voucherModal .preview form#deleteVoucher').attr('action', voucher.action)

			$('#voucherModal .preview form#deleteVoucher').removeClass('d-none')
			$('#voucherModal .preview form#deleteVoucher').addClass('d-inline-block')
		}else{
			$('#voucherModal .preview').addClass('d-none')
			$('#voucherModal .preview').removeClass('d-block')

			$('#voucherModal .preview form#deleteVoucher').addClass('d-none')
			$('#voucherModal .preview form#deleteVoucher').removeClass('d-inline-block')
			
			$('#voucherModal form.voucherForm').removeClass('d-none')
			$('#voucherModal form.voucherForm').addClass('d-block')
		}

		if(voucher.view == 'update'){
			$('#voucherModal .modal-title').text('@lang("accounting::vouchers.edit")')
		}
		if(voucher.view == 'create'){
			$('#voucherModal .modal-title').text('@lang("accounting::vouchers.add")')
		}

		if(voucher.action && voucher.view == 'update'){
			$('#voucherModal form.voucherForm').attr('action', voucher.action)
		}else if(voucher.view == 'create'){
			$('#voucherModal form.voucherForm').attr('action', "{{ route('vouchers.store') }}")
		}

		let field_method = $("#voucherModal form.voucherForm input#_method");
		if(voucher.method){
			if(field_method.length){
				field_method.val(voucher.method)
			}else{
				$('#voucherModal form.voucherForm').append('<input type="hidden" name="_method" id="_method" value="' + voucher.method + '" />')
			}
		}else{
			if(field_method.length) field_method.remove()
		}

		if(voucher.safeId){
			$('#voucherModal #safeId').val(voucher.safeId)
			$('#voucherModal #safeId').parent().hide()
		}else{
			$("#voucherModal #safeId").val($("#voucherModal #safeId option:first").val());
			$('#voucherModal #safeId').parent().show()
		}

		if(voucher.accountId){
			$('#voucherModal #accountId').val(voucher.accountId)
		}else{
			$("#voucherModal #accountId").val($("#voucherModal #accountId option:first").val());
		}

		if(voucher.amount){
			$('#voucherModal #amount').val(voucher.amount)
		}else{
			$("#voucherModal #amount").val(0);
		}

		if(voucher.details){
			$('#voucherModal #details').text(voucher.details)
		}else{
			$("#voucherModal #details").text('');
		}

		if(voucher.max){
			$("#voucherModal #amount").attr('max', voucher.max)
		}else{
			$("#voucherModal #amount").removeAttr('max')
		}

		$('#voucherModal').modal('show')
	}

	function Voucher(data = null){
		if(data){
			this.id = data.id;
			this.safeId = data.safeId;
			this.safeName = data.safeName;
			this.accountId = data.accountId;
			this.accountName = data.accountName;
			this.amount = data.amount;
			this.details = data.details;
			this.action = data.action;
			this.method = data.method;
			this.max = data.max ? data.max : 0;
			this.view = data.view ? data.view : 'create';
		}else{
			this.id = null;
			this.safeId = null;
			this.safeName = null;
			this.accountId = null;
			this.accountName = null;
			this.amount = null;
			this.details = null;
			this.action = null;
			this.method = null;
			this.max = 0;
			this.view = 'create';
		}
	}
</script>