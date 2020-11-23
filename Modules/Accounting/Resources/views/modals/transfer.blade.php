<div class="modal fade" id="transferModal" tabindex="-1" role="dialog" aria-labelledby="transfersLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title pull-left" id="transferModalLabel">@lang('accounting::transfers.add')</h5>
        <button type="button" class="close pull-right" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form class="transferForm" action="{{ route('transfers.store') }}" method="POST">
          @csrf
          <div class="modal-body">
			  <div class="alert alert-warning">
				  <i class="fa fa-exclamation-triangle"></i>
				  <strong>@lang('accounting::warnings.trsnafer_not_entry')</strong>
			  </div>
			  <table class="table">
				  <tr>
					  <th>@lang('accounting::global.from')</th>
					  <td>
					  <select id="transferModalToId" name="to_id" class="form-control" required>
						  @foreach (accounts(true, true) as $account)
							  <option value="{{ $account->id }}">{{ $account->number . '-' . $account->name }}</option>
						  @endforeach
					  </select>
					  </td>
				  </tr>
				  <tr>
					<th>@lang('accounting::global.to')</th>
					<td>
					  <select id="transferModalFromId" name="from_id" class="form-control" required>
						  @foreach (accounts(true, true) as $account)
							  <option value="{{ $account->id }}">{{ $account->number . '-' . $account->name }}</option>
						  @endforeach
					  </select>
				  	</td>
				  </tr>
				  <tr>
					  <th>@lang('accounting::global.amount')</th>
					  <td>
					  <input id="transferModalAmount"  class="form-control amount" autocomplete="off" type="text" name="amount" placeholder="@lang('accounting::global.amount')">
					  </td>
				  </tr>
				  <tr>
					  <th>@lang('accounting::global.details')</th>
					  <td>
					  <textarea id="transferModalDetails"  class="form-control details" autocomplete="off" type="text" name="details" placeholder="@lang('accounting::global.details')"></textarea>
					  </td>
				  </tr>
			  </table>
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
					<th>@lang('accounting::global.from')</th>
					<td class="from"></td>
				</tr>
				<tr>
					<th>@lang('accounting::global.to')</th>
					<td class="to"></td>
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
			@permission('transfers-delete')
				<form id="deleteTransfer" action="" method="post" class="d-none">
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
	$('*[data-modal=transfer]').click(function() {
		let transfer = new Transfer($(this).data())
		showTransferModal(transfer)
	})

	{{--  function showTransferModal(name = null, type = null, side = null, main_transfer = null, final_transfer = null, action = null, method = null, view = 'create', name = null){  --}}
	function showTransferModal(transfer){
		let field_fromId = $('#transferModalFromId');
		let field_toId = $('#transferModalToId');
		let field_amount = $('#transferModalAmount');
		let field_details = $('#transferModalDetails');
		let field_method = $("#transferModal form.transferForm input#_method");

		if(transfer.view == 'preview' || transfer.view == 'delete'){
			$('#transferModal .preview .id').text(transfer.id)
			$('#transferModal .preview .from').text(transfer.toName)
			$('#transferModal .preview .to').text(transfer.fromName)
			$('#transferModal .preview .amount').text(transfer.amount)
			$('#transferModal .preview .details').text(transfer.details)
			$('#transferModal .preview').removeClass('d-none')
			$('#transferModal .preview').addClass('d-block')

			$('#transferModal form.transferForm').addClass('d-none')
			$('#transferModal form.transferForm').removeClass('d-block')
		}
		if(transfer.view == 'preview'){
			$('#transferModal .modal-title').text('@lang("accounting::transfers.details"): ' + transfer.id)

			$('#transferModal .preview form#deleteTransfer').addClass('d-none')
			$('#transferModal .preview form#deleteTransfer').removeClass('d-inline-block')
		}else if(transfer.view == 'delete'){
			$('#transferModal .modal-title').text('@lang("accounting::transfers.confirm_delete")')
			$('#transferModal .preview form#deleteTransfer').attr('action', transfer.action)

			$('#transferModal .preview form#deleteTransfer').removeClass('d-none')
			$('#transferModal .preview form#deleteTransfer').addClass('d-inline-block')
		}else{
			$('#transferModal .preview').addClass('d-none')
			$('#transferModal .preview').removeClass('d-block')

			$('#transferModal .preview form#deleteTransfer').addClass('d-none')
			$('#transferModal .preview form#deleteTransfer').removeClass('d-inline-block')

			$('#transferModal form.transferForm').removeClass('d-none')
			$('#transferModal form.transferForm').addClass('d-block')
		}

		if(transfer.view == 'update'){
			$('#transferModal .modal-title').text('@lang("accounting::transfers.edit")')
		}
		if(transfer.view == 'create'){
			$('#transferModal .modal-title').text('@lang("accounting::transfers.add")')
		}

		if(transfer.action && transfer.view == 'update'){
			$('#transferModal form.transferForm').attr('action', transfer.action)
		}else if(transfer.view == 'create'){
			$('#transferModal form.transferForm').attr('action', "{{ route('transfers.store') }}")
		}

		if(transfer.method){
			if(field_method.length){
				field_method.val(transfer.method)
			}else{
				$('#transferModal form.transferForm').append('<input type="hidden" name="_method" id="_method" value="' + transfer.method + '" />')
			}
		}else{
			if(field_method.length) field_method.remove()
		}

		if(transfer.toId){
			$('#transferModalToId').val(transfer.toId)
			// $('#transferModalToId').parent().hide()
		}else{
			$("#transferModalToId").val($("#transferModalToId option:first").val());
			// $('#transferModalToId').parent().show()
		}


		if(transfer.view == 'update'){
			// field_fromId.parent().parent().hide()
			// field_toId.parent().parent().hide()
		}else{
			if(transfer.accountId){
				if(transfer.view == 'update'){
					if(transfer.accountId == transfer.fromId){
						// field_fromId.parent().parent().hide()
						field_fromId.val(transfer.accountId)
					}
					if(transfer.accountId == transfer.toId){
						// field_toId.parent().parent().hide()
						field_toId.val(transfer.accountId)
					}
				}
				if(transfer.view == 'create'){
					// field_fromId.parent().parent().show()
					// field_toId.parent().parent().hide()
					field_toId.val(transfer.accountId)
				}
			}else{
				// field_fromId.parent().parent().show()
				field_fromId.val(field_fromId.find('option:selected').val())
				// field_toId.parent().parent().show()
				field_toId.val(field_toId.find('option:selected').val())
			}
		}

		if(transfer.fromId){
			$('#transferModalFromId').val(transfer.fromId)
		}else{
			$("#transferModalFromId").val($("#transferModalFromId option:first").val());
		}

		if(transfer.amount){
			$('#transferModalAmount').val(transfer.amount)
		}else{
			$("#transferModalAmount").val(0);
		}

		if(transfer.details){
			$('#transferModal #transferModalDetails').text(transfer.details)
		}else{
			$("#transferModal #transferModalDetails").text('');
		}

		{{--  if(transfer.max){
			$("#transferModalAmount").attr('max', transfer.max)
		}else{
			$("#transferModalAmount").removeAttr('max')
		}  --}}

		$('#transferModal').modal('show')
	}

	function Transfer(data = null){
		if(data){
			this.id = data.id;
			this.accountId = data.accountId;
			this.toId = data.toId;
			this.toName = data.toName;
			this.fromId = data.fromId;
			this.fromName = data.fromName;
			this.amount = data.amount;
			this.details = data.details;
			this.action = data.action;
			this.method = data.method;
			this.max = data.max ? data.max : 0;
			this.view = data.view ? data.view : 'create';
		}else{
			this.id = null;
			this.accountId = null;
			this.toId = null;
			this.toName = null;
			this.fromId = null;
			this.fromName = null;
			this.amount = null;
			this.details = null;
			this.action = null;
			this.method = null;
			this.max = 0;
			this.view = 'create';
		}
	}
</script>
