<div class="modal fade" id="safeModal" tabindex="-1" role="dialog" aria-labelledby="safesLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title pull-left" id="safeModalLabel">@lang('accounting::safes.create')</h5>
        <button type="button" class="close pull-right" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form class="safeForm" action="{{ route('safes.store') }}" method="POST">
          @csrf
          <div class="modal-body">
              <div class="form-group">
                  <label>@lang('accounting::global.name')</label>
                  <input  class="form-control name" autocomplete type="text" name="name" id="name" placeholder="@lang('accounting::global.name')" required>
			  </div>
			  <div class="row form-group">
			  		<div class="col">
						<label>@lang('accounting::global.type')</label>
						<select  class="form-control type" name="type" id="type" required>
							@foreach (\Modules\Accounting\Models\Safe::TYPES as $type)
								<option value="{{ $type }}">@lang('accounting::safes.types.' . $type)</option>
							@endforeach
						</select>
					</div>
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
					<th>@lang('accounting::global.name')</th>
					<td class="name"></td>
				</tr>
				<tr>
					<th>@lang('accounting::global.type')</th>
					<td class="type"></td>
				</tr>
				<tr>
					<th>@lang('accounting::global.balance')</th>
					<td class="balance"></td>
				</tr>
			</table>
		</div>
		<div class="modal-footer">
			@permission('safes-delete')
				<form id="deleteSafe" action="" method="post" class="d-none">
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
		let field_name = $('#safeModal #name');
		let field_type = $('#safeModal #type');
	})
	$('*[data-modal=safe]').click(function() {
		let safe = new Safe($(this).data())
		showSafeModal(safe)
	})

	{{--  function showSafeModal(name = null, type = null, side = null, main_safe = null, final_safe = null, action = null, method = null, view = 'create', name = null){  --}}
	function showSafeModal(safe){
		if(safe.view == 'preview'){
			$('#safeModal .modal-title').text('@lang("accounting::safes.details"): ' + safe.name)
			$('#safeModal .preview .id').text(safe.id)
			$('#safeModal .preview .name').text(safe.name)
			$('#safeModal .preview .type').text(safe.type)
			$('#safeModal .preview .balance').text(safe.balance)
			
			$('#safeModal .preview').removeClass('d-none')
			$('#safeModal .preview').addClass('d-block')
			
			$('#safeModal form.safeForm').addClass('d-none')
			$('#safeModal form.safeForm').removeClass('d-block')

			$('#safeModal .preview form#deleteSafe').addClass('d-none')
			$('#safeModal .preview form#deleteSafe').removeClass('d-inline-block')
		}else if(safe.view == 'delete'){
			$('#safeModal .modal-title').text('@lang("accounting::safes.confirm_delete")')
			$('#safeModal .preview .id').text(safe.id)
			$('#safeModal .preview .name').text(safe.name)
			$('#safeModal .preview .type').text(safe.type)
			$('#safeModal .preview .balance').text(safe.balance)

			$('#safeModal .preview form#deleteSafe').attr('action', safe.action)
			
			$('#safeModal .preview').removeClass('d-none')
			$('#safeModal .preview').addClass('d-block')
			
			$('#safeModal form.safeForm').addClass('d-none')
			$('#safeModal form.safeForm').removeClass('d-block')

			$('#safeModal .preview form#deleteSafe').removeClass('d-none')
			$('#safeModal .preview form#deleteSafe').addClass('d-inline-block')
		}else{
			$('#safeModal .preview').addClass('d-none')
			$('#safeModal .preview').removeClass('d-block')

			$('#safeModal .preview form#deleteSafe').addClass('d-none')
			$('#safeModal .preview form#deleteSafe').removeClass('d-inline-block')
			
			$('#safeModal form.safeForm').removeClass('d-none')
			$('#safeModal form.safeForm').addClass('d-block')
		}

		if(safe.view == 'update'){
			$('#safeModal .modal-title').text('@lang("accounting::safes.edit")')
		}
		if(safe.view == 'create'){
			$('#safeModal .modal-title').text('@lang("accounting::safes.cretae")')
		}

		if(safe.action && safe.view == 'update'){
			$('#safeModal form.safeForm').attr('action', safe.action)
		}else if(safe.view == 'create'){
			$('#safeModal form.safeForm').attr('action', "{{ route('safes.store') }}")
		}

		let field_method = $("#safeModal form.safeForm input#_method");
		if(safe.method){
			if(field_method.length){
				field_method.val(safe.method)
			}else{
				$('#safeModal form.safeForm').append('<input type="hidden" name="_method" id="_method" value="' + safe.method + '" />')
			}
		}else{
			if(field_method.length) field_method.remove()
		}
		
		if(safe.name){
			$('#safeModal input[name="name"]').val(safe.name)
		}else{
			$('#safeModal input[name="name"]').val('')
		}

		if(safe.type){
			$('#safeModal select[name="type"]').val(safe.type)
		}else{
			$("#safeModal #type").val($("#safeModal #type option:first").val());
		}
		$('#safeModal').modal('show')
	}

	function Safe(data = null){
		if(data){
			this.id = data.id;
			this.number = data.number;
			this.name = data.name;
			this.type = data.type;
			this.action = data.action;
			this.method = data.method;
			this.view = data.view ? data.view : 'create';
			this.balance = data.balance ? data.balance : 0;
		}else{
			this.id = null;
			this.number = null;
			this.name = null;
			this.type = null;
			this.action = null;
			this.method = null;
			this.balance = 0;
			this.view = 'create';
		}
		this.isCash = function(){
			return this.type == {{ \Modules\Accounting\Models\Safe::TYPE_CASH }};
		};
		this.isBank = function(){
			return this.type == {{ \Modules\Accounting\Models\Safe::TYPE_BANK }};
		};
	}
</script>