<div class="modal fade" id="suggestionModal" tabindex="-1" role="dialog" aria-labelledby="suggestionLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title pull-left" id="suggestionLabel">إضافة اقتراح</h5>
          <button type="button" class="close pull-right" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form class="form" action="{{ route('suggestions.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label>الاقتراح</label>
                    <textarea class="form-control" rows="3" cols="20" autocomplete="off"  name="content" placeholder="الاقتراح" required></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                <button type="submit" class="btn btn-primary">حفظ</button>
            </div>
		</form>
		<section class="preview">
			<div class="modal-body text-right">
				<table class="table table-striped">
					<tr>
						<th>الموظف</th>
						<td class="name"></td>
					</tr>
					<tr>
						<th>الاقتراح</th>
                    </tr>
                    <tr>
                        <th></th>
                        <th class="content"></th>
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
	$('.suggestions').click(function() {
		if($(this).hasClass("preview")){
            $('#suggestionLabel').text('عرض اقتراح' + $(this).data('user'))

            //set fields data
            $('#suggestionModal td.name').text($(this).data('user'))
            $('#suggestionModal th.content').text($(this).data('content'))
			
			$('#suggestionModal .form').hide()
            $('#suggestionModal .preview').show()
		}else{
			$('#suggestionLabel').text('اضافة اقتراح')

            $('#suggestionModal .preview').hide()
			$('#suggestionModal .form').show()
		}
		
		$('#suggestionModal').modal('show')
	})
</script>
