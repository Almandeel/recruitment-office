<div class="modal fade" id="safeableModal" tabindex="-1" role="dialog" aria-labelledby="safeablesLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title pull-left" id="safeableModalLabel">@lang('accounting::global.add')</h5>
        <button type="button" class="close pull-right" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form class="safeableForm" action="{{ route('entries.store') }}" method="POST">
		  @csrf
		  <input type="hidden" name="operation" value="safeable"/>
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
    </div>
  </div>
</div>


<script>
	$(function(){
		let field_safeId = $('#safeableModal #safeId');
		let field_accountId = $('#safeableModal #accountId');
		let field_amount = $('#safeableModal #amount');
	})
	$('*[data-modal=safeable]').click(function() {
		let safeable = new Safeable($(this).data())
		showSafeableModal(safeable)
	})

	function showSafeableModal(safeable){
		if(safeable.view == 'update'){
			$('#safeableModal .modal-title').text('@lang("accounting::global.edit")')
		}
		if(safeable.view == 'create'){
			$('#safeableModal .modal-title').text('@lang("accounting::global.add")')
		}

		if(safeable.action && safeable.view == 'update'){
			$('#safeableModal form.safeableForm').attr('action', safeable.action)
		}else if(safeable.view == 'create'){
			$('#safeableModal form.safeableForm').attr('action', "{{ route('safeables.store') }}")
		}

		let field_method = $("#safeableModal form.safeableForm input#_method");
		if(safeable.method){
			if(field_method.length){
				field_method.val(safeable.method)
			}else{
				$('#safeableModal form.safeableForm').append('<input type="hidden" name="_method" id="_method" value="' + safeable.method + '" />')
			}
		}else{
			if(field_method.length) field_method.remove()
		}

		if(safeable.safeId){
			$('#safeableModal #safeId').val(safeable.safeId)
			$('#safeableModal #safeId').parent().hide()
		}else{
			$("#safeableModal #safeId").val($("#safeableModal #safeId option:first").val());
			$('#safeableModal #safeId').parent().show()
		}

		if(safeable.accountId){
			$('#safeableModal #accountId').val(safeable.accountId)
		}else{
			$("#safeableModal #accountId").val($("#safeableModal #accountId option:first").val());
		}

		if(safeable.amount){
			$('#safeableModal #amount').val(safeable.amount)
		}else{
			$("#safeableModal #amount").val(0);
		}

		if(safeable.details){
			$('#safeableModal #details').text(safeable.details)
		}else{
			$("#safeableModal #details").text('');
		}

		if(safeable.max){
			$("#safeableModal #amount").attr('max', safeable.max)
		}else{
			$("#safeableModal #amount").removeAttr('max')
		}

		$('#safeableModal').modal('show')
	}

	function Safeable(data = null){
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