<div class="modal fade" id="advanceModal" tabindex="-1" role="dialog" aria-labelledby="advancesLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title pull-left" id="advancesLabel">  </h5>
          <button type="button" class="close pull-right" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form class="form" action="#" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-group">
					<label>الحالة</label>
                    <select id="status" class="custom-select " autocomplete="off" name="status" required >
						<option value="0">قيد الانتظار</option>
						<option value="1">تأكيد السلفية</option>
						<option value="2">إلغاء السلفية</option>
					</select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                <button type="submit" class="btn btn-primary">تحديث</button>
            </div>
		</form>
		<section class="preview">
			<div class="modal-body">
				<table class="table table-striped">
					<tr>
						<th style="width: 120px;">المعرف</th>
						<td class="id"></td>
					</tr>
					<tr>
						<th>المكتب الخارجي</th>
						<td class="office"></td>
					</tr>
					<tr>
						<th>القيمة</th>
						<td class="amount"></td>
					</tr>
					<tr>
						<th>الحالة</th>
						<td class="status"></td>
					</tr>
					<tr>
						<th>التاريخ</th>
						<td class="date"></td>
					</tr>
				</table>
			</div>
			<div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
			</div>
		</section>
      </div>
    </div>
  </div>

<script>
	$('.advance').click(function() {
		if($(this).hasClass("update")){

            $('#advancesLabel').text('تحديث حالة السلفة للمكتب: ' + $(this).data('office'))
            $('#advanceModal .form').attr('action', $(this).data('action'))
            $('#advanceModal .form').append('<input type="hidden" name="_method" value="PUT">')
            $('#advanceModal .form').append('<input type="hidden" name="id" value="' + $(this).data('id') +'">')

            //set fields data
			let status = $(this).data('status')
			let statuses = $('#status option');

            statuses.each(function() {
				if ($(this).val() == status) {
					$(this).attr('selected', true)
				}
			})

            $('#advanceModal .preview').hide()
			$('#advanceModal .form').show()
		}
		else if($(this).hasClass("preview")) {
			$('#advanceModal .form').hide()
			$('#advanceModal .preview').show()

            $('#advancesLabel').text(' بيانات السلفية للمكتب: ' + $(this).data('office'))

			//set fields data
            $('#advanceModal .id').text($(this).data('id'))
            $('#advanceModal .office').text($(this).data('office'))
            $('#advanceModal .amount').text($(this).data('amount'))
            $('#advanceModal .status').text($(this).data('status'))
            $('#advanceModal .date').text($(this).data('date'))         
		}

		$('#advanceModal').modal('show')
	})
</script>