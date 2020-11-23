<div class="modal fade" id="warehouseCvModal" tabindex="-1" role="dialog" aria-labelledby="taskLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title pull-left" id="taskLabel">اضافة cv</h5>
          <button type="button" class="close pull-right" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form class="form" action="{{ route('warehousecv.store') }}" method="POST">
            @csrf
            <div class="modal-body">

				<div class="form-group">
					<label>CV</label>
					<select id="cvs" required  class="form-control name" name="cv_id" required>
						@foreach ($cvs as $cv)
							<option value="{{ $cv->id }}">{{ $cv->name }}</option>
						@endforeach
					</select>
				</div>
					
				<div class="form-group">
					<input type="hidden" value="{{ $warehouse->id }}" name="warehouse_id">
				</div>

				<div class="enter">
					<div class="form-group">
						<label>تاريخ الدخول</label>
						<input required type="date" class="form-control" name="entry_date" placeholder="ناريخ الدخول">
					</div>
	
					<div class="form-group">
						<label>ملاحظات الدخول</label>
						<textarea class="form-control" name="entry_note" placeholder="ملاحظات الدخول"></textarea>
					</div>
				</div>

				<div class="exit">
					<div class="form-group">
						<label>تاريخ الخروج</label>
						<input type="date" class="form-control" name="exit_date" placeholder="ناريخ الخروج">
					</div>
	
					<div class="form-group">
						<label>ملاحظات الخروج</label>
						<textarea class="form-control" name="exit_note" placeholder="ملاحظات الخروج"></textarea>
					</div>
				</div>

				
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                <button type="submit" class="btn btn-primary">حفظ</button>
            </div>
		</form>
      </div>
    </div>
  </div>

<script>
	$('.warehousecv').click(function() {

		if($(this).hasClass('update')) {
			
			$('#warehouseCvModal .modal-title').text('تعديل cv')
			$('#warehouseCvModal .form').attr('action', $(this).data('action'))
            $('#warehouseCvModal .form').append('<input type="hidden" name="_method" value="PUT">')

			$('#warehouseCvModal input[name="entry_date"]').val($(this).data('entry'))
			$('#warehouseCvModal textarea[name="entry_note"]').val($(this).data('entry_note'))

			if($(this).hasClass('exit-button')) {
				$('.enter').css('display', 'none')
				$('.exit').css('display', 'block')
				$('#warehouseCvModal input[name="exit_date"]').val($(this).data('exit'))
				$('#warehouseCvModal textarea[name="exit_note"]').val($(this).data('exit_note'))
			}

			let cv = $(this).data('cv')
			let cvList = $('#cvs option');

            cvList.each(function() {
				if ($(this).val() == cv) {
					$(this).attr('selected', true)
				}
			})
		}else {
			$('#warehouseCvModal .modal-title').text('اضافة cv')
			$('#warehouseCvModal .form').attr('action', "{{ route('warehousecv.store') }}")
			$('#warehouseCvModal .form input[name="_method"]').remove()


			$('#warehouseCvModal input[name="entry_date"]').val('')
			$('#warehouseCvModal input[name="exit_date"]').val('')

			$('#warehouseCvModal textarea[name="entry_note"]').val('')
			$('#warehouseCvModal textarea[name="exit_note"]').val('')

			$('.exit').css('display', 'none')
			$('.enter').css('display', 'block')

		}

		$('#warehouseCvModal').modal('show')

	})
</script>
