<div class="modal fade" id="categoryModal" tabindex="-1" role="dialog" aria-labelledby="categoryLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title pull-left" id="categoryLabel">إضافة قسم</h5>
          <button type="button" class="close pull-right" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form class="form" action="{{ route('categories.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label>الاسم</label>
                    <input  class="form-control name" autocomplete="off"  type="text" name="name" placeholder="الاسم" required/>
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
	$('.categories').click(function() {
		if($(this).hasClass("update")){
            $('#categoryLabel').text('تعديل  قسم: ' + $(this).data('name'))
            $('#categoryModal .form').attr('action', $(this).data('action'))
            $('#categoryModal .form').append('<input type="hidden" name="_method" value="PUT">')

            //set fields data
            $('#categoryModal input[name="name"]').val($(this).data('name'))

			$('#categoryModal .form').show()
		}else{
			$('#categoryLabel').text('اضافة قسم')
			$('#categoryModal .form').attr('action', "{{ route('categories.store') }}")
			$('#categoryModal .form input[name="_method"]').remove()


            //Clear from fields
            $('#categoryModal input[name="name"]').val('')
            
		}
		
		$('#categoryModal').modal('show')
	})
</script>
