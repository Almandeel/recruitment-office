<div class="modal fade" id="centerModal" tabindex="-1" role="dialog" aria-labelledby="centersLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title pull-left" id="centerModalLabel">@lang('accounting::centers.create')</h5>
        <button type="button" class="close pull-right" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form class="centerForm" action="{{ route('centers.store') }}" method="POST">
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
							@foreach (\Modules\Accounting\Models\Center::TYPES as $type)
								<option value="{{ $type }}">@lang('accounting::centers.types.' . $type)</option>
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
			@permission('centers-delete')
				<form id="deleteCenter" action="" method="post" class="d-none">
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
		let field_name = $('#centerModal #name');
		let field_type = $('#centerModal #type');
	})
	$('*[data-modal=center]').click(function() {
		let center = new Center($(this).data())
		showCenterModal(center)
	})

	{{--  function showCenterModal(name = null, type = null, side = null, main_center = null, final_center = null, action = null, method = null, view = 'create', name = null){  --}}
	function showCenterModal(center){
		if(center.view == 'preview'){
			$('#centerModal .modal-title').text('@lang("accounting::centers.details"): ' + center.name)
			$('#centerModal .preview .id').text(center.id)
			$('#centerModal .preview .name').text(center.name)
			$('#centerModal .preview .type').text(center.type)
			$('#centerModal .preview .balance').text(center.balance)
			
			$('#centerModal .preview').removeClass('d-none')
			$('#centerModal .preview').addClass('d-block')
			
			$('#centerModal form.centerForm').addClass('d-none')
			$('#centerModal form.centerForm').removeClass('d-block')

			$('#centerModal .preview form#deleteCenter').addClass('d-none')
			$('#centerModal .preview form#deleteCenter').removeClass('d-inline-block')
		}else if(center.view == 'delete'){
			$('#centerModal .modal-title').text('@lang("accounting::centers.confirm_delete")')
			$('#centerModal .preview .id').text(center.id)
			$('#centerModal .preview .name').text(center.name)
			$('#centerModal .preview .type').text(center.type)
			$('#centerModal .preview .balance').text(center.balance)

			$('#centerModal .preview form#deleteCenter').attr('action', center.action)
			
			$('#centerModal .preview').removeClass('d-none')
			$('#centerModal .preview').addClass('d-block')
			
			$('#centerModal form.centerForm').addClass('d-none')
			$('#centerModal form.centerForm').removeClass('d-block')

			$('#centerModal .preview form#deleteCenter').removeClass('d-none')
			$('#centerModal .preview form#deleteCenter').addClass('d-inline-block')
		}else{
			$('#centerModal .preview').addClass('d-none')
			$('#centerModal .preview').removeClass('d-block')

			$('#centerModal .preview form#deleteCenter').addClass('d-none')
			$('#centerModal .preview form#deleteCenter').removeClass('d-inline-block')
			
			$('#centerModal form.centerForm').removeClass('d-none')
			$('#centerModal form.centerForm').addClass('d-block')
		}

		if(center.view == 'update'){
			$('#centerModal .modal-title').text('@lang("accounting::centers.edit")')
		}
		if(center.view == 'create'){
			$('#centerModal .modal-title').text('@lang("accounting::centers.cretae")')
		}

		if(center.action && center.view == 'update'){
			$('#centerModal form.centerForm').attr('action', center.action)
		}else if(center.view == 'create'){
			$('#centerModal form.centerForm').attr('action', "{{ route('centers.store') }}")
		}

		let field_method = $("#centerModal form.centerForm input#_method");
		if(center.method){
			if(field_method.length){
				field_method.val(center.method)
			}else{
				$('#centerModal form.centerForm').append('<input type="hidden" name="_method" id="_method" value="' + center.method + '" />')
			}
		}else{
			if(field_method.length) field_method.remove()
		}
		
		if(center.name){
			$('#centerModal input[name="name"]').val(center.name)
		}else{
			$('#centerModal input[name="name"]').val('')
		}

		if(center.type){
			$('#centerModal select[name="type"]').val(center.type)
		}else{
			$("#centerModal #type").val($("#centerModal #type option:first").val());
		}
		$('#centerModal').modal('show')
	}

	function Center(data = null){
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
		this.isCost = function(){
			return this.type == {{ \Modules\Accounting\Models\Center::TYPE_COST }};
		};
		this.isProfit = function(){
			return this.type == {{ \Modules\Accounting\Models\Center::TYPE_PROFIT }};
		};
	}
</script>