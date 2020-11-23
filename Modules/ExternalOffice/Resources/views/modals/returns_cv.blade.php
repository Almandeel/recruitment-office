<div class="modal fade" id="returnsModal" tabindex="-1" role="dialog" aria-labelledby="returnsModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="returnsModalLabel">طلب سحب</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form action="{{ route('cvs.returns.store') }}" method="post">
				@csrf
				<div class="modal-body">
					<div class="form-group">
						<label for="withAdvance">إنشاء سند بالتكلفة</label>
						<input type="checkbox" name="withAdvance" id="withAdvance" value="true">
					</div>
					<div class="form-group">
						<label for="cause">السبب</label>
						<textarea class="form-control" id="cause" name="cause" rows="4" placeholder="السبب"></textarea>
					</div>
					<div class="col">
						@component('components.widget')
							@slot('noTitle', true)
							@slot('title')
							<i class="fas fa-paperclip"></i>
							<span>المرفقات</span>
							@endslot
							@slot('body')
							@component('accounting::components.attachments-uploader')@endcomponent
							@endslot
						@endcomponent
		            </div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
					<button type="submit" class="btn btn-primary">حفظ</button>
				</div>
			</form>
		</div>
	</div>
</div>

<script>
	$('.returns').click(function() {
		$('form').attr('action', $(this).data('action'))
		$('#returnsModal').modal('show')
	})
</script>