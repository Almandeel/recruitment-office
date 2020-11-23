<div class="modal fade" id="taskModal" tabindex="-1" role="dialog" aria-labelledby="taskLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title pull-left" id="taskLabel">إضافة مهام</h5>
          <button type="button" class="close pull-right" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form class="form" action="{{ route('tasks.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label>الاسم</label>
                    <input  class="form-control name" autocomplete="off"  type="text" name="name" placeholder="الاسم" required/>
                </div>
                <div class="form-group">
                    <select id="users" class="select2 form-control" multiple="multiple" data-placeholder="الموظفين" name="user_id[]" required >
						@foreach ($users as $user)
							<option value="{{ $user->id }}">{{ $user->name }}</option>
						@endforeach
					</select>
				</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                <button type="submit" class="btn btn-primary">حفظ</button>
            </div>
		</form>
		{{-- <section class="preview">
			<div class="modal-body">
				<table class="table table-striped">
					<tr>
						<th style="width: 120px;">المعرف</th>
						<td class="id"></td>
					</tr>
					<tr>
						<th>الاسم</th>
						<td class="name"></td>
					</tr>
					<tr>
						<th>المرتب</th>
						<td class="salary"></td>
					</tr>
					<tr>
						<th>الوظيفة</th>
						<td class="position"></td>
					</tr>
					<tr>
						<th>القسم</th>
						<td class="department"></td>
					</tr>
					<tr>
						<th>تاريخ التعيين</th>
						<td class="started_at"></td>
					</tr>
				</table>
			</div>
			<div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
			</div>
		</section> --}}
      </div>
    </div>
  </div>

<script>
	$('.tasks').click(function() {
		if($(this).hasClass("update")){
            $('#taskLabel').text('تعديل  هام: ' + $(this).data('name'))
            $('#taskModal .form').attr('action', $(this).data('action'))
            $('#taskModal .form').append('<input type="hidden" name="_method" value="PUT">')

            //set fields data
            $('#taskModal input[name="name"]').val($(this).data('name'))

			let users = $(this).data('users')
			let userList = $('#users option');

            let selected = [];
            users.forEach(user => {
                userList.each(function() {
                    if ($(this).val() == user.user_id) {
                        selected.push(user.user_id)
                    }
                })
            });

            $('#users').val(selected);
            $('#users').trigger('change')

			
            // $('#taskModal .preview').hide()
			$('#taskModal .form').show()
		}else{
			$('#taskLabel').text('اضافة مهام')
			$('#taskModal .form').attr('action', "{{ route('tasks.store') }}")
			$('#taskModal .form input[name="_method"]').remove()


            //Clear from fields
            $('#taskModal input[name="name"]').val('')
            $('#users').val(null).trigger('change');
            
		}
		
		$('#taskModal').modal('show')
	})
</script>
