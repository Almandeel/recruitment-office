<div class="modal fade" id="employeeModal" tabindex="-1" role="dialog" aria-labelledby="employeesLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title pull-left" id="employeesLabel">إضافة موظف</h5>
          <button type="button" class="close pull-right" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form class="form" action="{{ route('employees.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label>الاسم</label>
                    <input  class="form-control name" autocomplete="off" type="text" name="name" placeholder="الاسم" required/>
                </div>
                <div class="form-group">
					<label>الهاتف الداخلي</label>
					<div class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text" id="basic-addon1">هاتف عام  <input style="display:block;margin: 0 3px"  autocomplete="off" type="checkbox" name="public_line" value="1" /></span>
						</div>
						<input class="form-control" autocomplete="off" type="number" name="line" placeholder="الهاتف الداخلي" />
					</div>
				</div>
                <div class="form-group">
                    <label>المرتب</label>
                    <input  class="form-control" autocomplete="off" type="number" name="salary" placeholder="المرتب" required/>
                </div>
                <div class="form-group">
					<label>الوظيفة</label>
					@if (isset($positions))
                    <select id="positions" class="form-control editable" autocomplete="off" name="position_id" required >
						@foreach ($positions ?? '' as $position)
							<option value="{{ $position->id }}">{{ $position->title }}</option>
						@endforeach
					</select>
					@endif
                </div>
                <div class="form-group">
                    <label> القسم</label>
					@if (isset($departments))
                    <select id="departments" class="form-control editable" autocomplete="off" name="department_id" required >
						@foreach ($departments as $department)
							<option value="{{ $department->id }}">{{ $department->title }}</option>
						@endforeach
					</select>
					@endif
				</div>
				<div class="form-group">
                    <label>تاريخ التعيين</label>
                    <input  class="form-control" autocomplete="off" type="date" name="started_at" placeholder="تاريخ التعيين" required/>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                <button type="submit" class="btn btn-primary">حفظ</button>
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
						<th>الاسم</th>
						<td class="name"></td>
					</tr>
					<tr>
						<th>الخط</th>
						<td class="line"></td>
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
		</section>
      </div>
    </div>
  </div>

<script>
	$('.employees').click(function() {
		if($(this).hasClass("update")){
            $('#employeesLabel').text('تعديل بيانات الموظف: ' + $(this).data('name'))
            $('#employeeModal .form').attr('action', $(this).data('action'))
            $('#employeeModal .form').append('<input type="hidden" name="_method" value="PUT">')

            //set fields data
            $('#employeeModal input[name="name"]').val($(this).data('name'))
            $('#employeeModal input[name="salary"]').val($(this).data('salary'))
            $('#employeeModal input[name="line"]').val($(this).data('line'))
            $('#employeeModal input[name="started_at"]').val($(this).data('started'))

			if($(this).data('public-line')) {
				$('#employeeModal input[name="public_line"]').attr('checked', true)
			}else {
				$('#employeeModal input[name="public_line"]').attr('checked', false)
			}

			let department = $(this).data('department')
			if(department) $('#employeeModal #departments').val(department);


			let position = $(this).data('position')
			if(position) $('#employeeModal #positions').val(position);
			// let positionList = $('#positions option');
            // positionList.each(function() {
			// 	if ($(this).val() == position) {
			// 		$(this).attr('selected', true)
			// 	}
			// })
			// let position = $(this).data('position')
			// if(position) $('#employeeModal #positions').val(position);

			
            $('#employeeModal .preview').hide()
			$('#employeeModal .form').show()
		}else if($(this).hasClass("preview")) {
			$('#employeeModal .form').hide()
			$('#employeeModal .preview').show()
            $('#employeesLabel').text(' بيانات الموظف: ' + $(this).data('name'))

			//set fields data
            $('#employeeModal .id').text($(this).data('id'))
            $('#employeeModal .name').text($(this).data('name'))
            $('#employeeModal .line').text($(this).data('line'))
            $('#employeeModal .salary').text($(this).data('salary'))
            $('#employeeModal .started_at').text($(this).data('started'))
            $('#employeeModal .position').text($(this).data('position'))
            $('#employeeModal .department').text($(this).data('department'))

		}else{

			$('#employeeModal .preview').hide()
			$('#employeeModal .form').show()

			$('#employeesLabel').text('اضافة موظف')
			$('#employeeModal .form').attr('action', "{{ route('employees.store') }}")
			$('#employeeModal .form input[name="_method"]').remove()

            //Clear from fields
            $('#employeeModal input[name="name"]').val('')
            $('#employeeModal input[name="line"]').val('')
            $('#employeeModal input[name="salary"]').val('')
            $('#employeeModal input[name="phone"]').val('')
            $('#employeeModal input[name="address"]').val('')

			$('#employeeModal input[name="public_line"]').attr('checked', false)
            
		}
		
		$('#employeeModal').modal('show')
	})
</script>