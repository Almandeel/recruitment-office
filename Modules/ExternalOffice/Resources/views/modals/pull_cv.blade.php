<div class="modal fade" id="pullModal" tabindex="-1" role="dialog" aria-labelledby="pullModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="pullModalLabel">Pull a Cv</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form action="#" method="post">
				@csrf 
				<div class="modal-body">
					@component('components.attachments-uploader')
					@endcomponent
					{{--  <div class="form-group">
						<label for="damages">Damages</label>
						<input type="number" class="form-control" id="damages" name="damages" placeholder="Damages">
					</div>
					<div class="form-group">
						<label for="cause">Cause</label>
						<textarea class="form-control" id="cause" name="cause" rows="4" placeholder="Cause"></textarea>
					</div>
					<div class="form-group">
						<label for="notes">Notes</label>
						<textarea class="form-control" id="notes" name="notes" rows="4" placeholder="Notes"></textarea>
					</div>  --}}
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
					<button type="submit" class="btn btn-warning">Pull</button>
				</div>
			</form>
		</div>
	</div>
</div>

<script>
	$('.pull').click(function() {
		$('form').attr('action', $(this).data('action'))
		$('#pullModal').modal('show')
	})
</script>