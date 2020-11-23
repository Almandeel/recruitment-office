<div class="modal fade" id="expenseModal" tabindex="-1" role="dialog" aria-labelledby="expensesLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title pull-left" id="expenseModalLabel">@lang('accounting::expenses.add')</h5>
        <button type="button" class="close pull-right" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form class="expenseForm" action="{{ route('expenses.store') }}" method="POST">
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
					<label>@lang('accounting::global.account')</label>
					<select id="accountId" name="account_id" class="form-control" required>
						@foreach (expensesAccount()->children(true) as $account)
							@if ($account->children->count() <= 0)
							<option value="{{ $account->id }}">{{ $account->number . '-' . $account->name }}</option>
							@endif
						@endforeach
					</select>
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
			@permission('expenses-delete')
				<form id="deleteExpense" action="" method="post" class="d-none">
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
		let field_safeId = $('#expenseModal #safeId');
		let field_accountId = $('#expenseModal #accountId');
		let field_amount = $('#expenseModal #amount');
	})
	$('*[data-modal=expense]').click(function() {
		let expense = new Expense($(this).data())
		showExpenseModal(expense)
	})

	{{--  function showExpenseModal(name = null, type = null, side = null, main_expense = null, final_expense = null, action = null, method = null, view = 'create', name = null){  --}}
	function showExpenseModal(expense){
		if(expense.view == 'preview' || expense.view == 'delete'){
			$('#expenseModal .preview .id').text(expense.id)
			$('#expenseModal .preview .safe').text(expense.safeName)
			$('#expenseModal .preview .account').text(expense.accountName)
			$('#expenseModal .preview .amount').text(expense.amount)
			$('#expenseModal .preview .details').text(expense.details)
			$('#expenseModal .preview').removeClass('d-none')
			$('#expenseModal .preview').addClass('d-block')
			
			$('#expenseModal form.expenseForm').addClass('d-none')
			$('#expenseModal form.expenseForm').removeClass('d-block')
		}
		if(expense.view == 'preview'){
			$('#expenseModal .modal-title').text('@lang("accounting::expenses.details"): ' + expense.id)

			$('#expenseModal .preview form#deleteExpense').addClass('d-none')
			$('#expenseModal .preview form#deleteExpense').removeClass('d-inline-block')
		}else if(expense.view == 'delete'){
			$('#expenseModal .modal-title').text('@lang("accounting::expenses.confirm_delete")')
			$('#expenseModal .preview form#deleteExpense').attr('action', expense.action)

			$('#expenseModal .preview form#deleteExpense').removeClass('d-none')
			$('#expenseModal .preview form#deleteExpense').addClass('d-inline-block')
		}else{
			$('#expenseModal .preview').addClass('d-none')
			$('#expenseModal .preview').removeClass('d-block')

			$('#expenseModal .preview form#deleteExpense').addClass('d-none')
			$('#expenseModal .preview form#deleteExpense').removeClass('d-inline-block')
			
			$('#expenseModal form.expenseForm').removeClass('d-none')
			$('#expenseModal form.expenseForm').addClass('d-block')
		}

		if(expense.view == 'update'){
			$('#expenseModal .modal-title').text('@lang("accounting::expenses.edit")')
		}
		if(expense.view == 'create'){
			$('#expenseModal .modal-title').text('@lang("accounting::expenses.add")')
		}

		if(expense.action && (expense.view == 'create' || expense.view == 'update')){
			$('#expenseModal form.expenseForm').attr('action', expense.action)
		}else if(expense.view == 'create'){
			$('#expenseModal form.expenseForm').attr('action', "{{ route('expenses.store') }}")
		}

		let field_method = $("#expenseModal form.expenseForm input#_method");
		if(expense.method){
			if(field_method.length){
				field_method.val(expense.method)
			}else{
				$('#expenseModal form.expenseForm').append('<input type="hidden" name="_method" id="_method" value="' + expense.method + '" />')
			}
		}else{
			if(field_method.length) field_method.remove()
		}

		if(expense.safeId){
			$('#expenseModal #safeId').val(expense.safeId)
			// $('#expenseModal #safeId').parent().hide()
		}else{
			$("#expenseModal #safeId").val($("#expenseModal #safeId option:first").val());
			// $('#expenseModal #safeId').parent().show()
		}

		if(expense.accountId){
			$('#expenseModal #accountId').val(expense.accountId)
		}else{
			$("#expenseModal #accountId").val($("#expenseModal #accountId option:first").val());
		}

		if(expense.amount){
			$('#expenseModal #amount').val(expense.amount)
		}else{
			$("#expenseModal #amount").val(0);
		}

		if(expense.details){
			$('#expenseModal #details').text(expense.details)
		}else{
			$("#expenseModal #details").text('');
		}

		if(expense.max){
			$("#expenseModal #amount").attr('max', expense.max)
		}else{
			$("#expenseModal #amount").removeAttr('max')
		}

		$('#expenseModal').modal('show')
	}

	function Expense(data = null){
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