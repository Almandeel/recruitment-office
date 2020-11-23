<div class="modal fade" id="attendanceModal" tabindex="-1" role="dialog" aria-labelledby="attendancesLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title pull-left" id="attendancesLabel">الحضور</h5>
          <button type="button" class="close pull-right" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form class="form" action="{{ route('attendance.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="employees">
                    <div class="form-group">
                        <label>الموظف</label>
                        <select id="employee"  class="form-control" name="employee_id" required>
                            @foreach ($employees as $employee)
                                <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="time">
                    <div class="form-group">
                        <label>وقت الحضور</label>
                        <input  class="form-control" autocomplete="off" type="time" name="time_in" placeholder="وقت الحضور" required/>
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
	$('.attendance').click(function() {
		if($(this).hasClass("update")){
            $('#attendancesLabel').text('مغادرة')
            $('#attendanceModal .form').attr('action', $(this).data('action'))
            $('#attendanceModal .form').append('<input type="hidden" name="_method" value="PUT">')

            //Add time out field and note
            let input_time_out =
            `   <div class="time-out">
                    <div class="form-group">
                        <label>وقت المغادرة</label>
                        <input type="time" class="form-control" name="time_out" required placeholder="وقت المغادرة">
                    </div>

                    <div class="form-group">
                        <label>ملاحظات</label>
                        <textarea class="form-control" class="form-control" name="notes" placeholder="ملاحظات"></textarea>
                    </div>
                </div>
            `;


            $('#attendanceModal .form .time').append(input_time_out)


            //set fields data
            $('#attendanceModal input[name="time_in"]').val($(this).data('time-in'))

			let employee = $(this).data('employee')
			let employeeList = $('#employee option');

			employeeList.each(function() {
				if ($(this).val() == employee) {
					$(this).attr('selected', true)
				}
			})

			
			$('#attendanceModal .form').show()
		}else{

			$('#attendancesLabel').text('الحضور')
			$('#attendanceModal .form').attr('action', "{{ route('attendance.store') }}")
			$('#attendanceModal .form input[name="_method"]').remove()


            //Clear from fields
            $('#attendanceModal input[name="time_in"]').val('')

            //clear time out filed
            $('#attendanceModal .form .time .time-out').remove()
            
		}
		
		$('#attendanceModal').modal('show')
	})
</script>