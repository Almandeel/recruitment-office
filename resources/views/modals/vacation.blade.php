<div class="modal fade" id="vacationModal" tabindex="-1" role="dialog" aria-labelledby="vacationsLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title pull-left" id="vacationsLabel">اضافة طلب اجازة</h5>
          <button type="button" class="close pull-right" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form class="form" action="{{ route('vacations.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                @if(isset($employees))
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
                @endif

                <div class="form-group">
                    <label>الطلب</label>
                    <input  class="form-control" autocomplete="off" type="text" name="title" placeholder="الطلب" required/>
                </div>

                <div class="form-group">
                    <label>النوع</label>
                    <select id="payed"  class="form-control" name="payed" required>
                        <option value="0">غير مدفوعة</option>
                        <option value="1"> مدفوعة</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>بداية الاجازة</label>
                    <input  class="form-control" autocomplete="off" type="date" name="started_at" placeholder="بداية الاجازة" required/>
                </div>

                <div class="form-group">
                    <label>نهاية الاجازة</label>
                    <input  class="form-control" autocomplete="off" type="date" name="ended_at" placeholder="نهاية الاجازة" required/>
                </div>

                <div class="form-group">
                    <label>التفاصيل</label>
                    <textarea  class="form-control" autocomplete="off" name="details" placeholder="التفاصيل" ></textarea>
                </div>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
            </div>
		</form>
      </div>
    </div>
  </div>


  <script>
	$('.vacation').click(function() {
		if($(this).hasClass("update")){
            $('#vacationsLabel').text('طلب اجازة')
            $('#vacationModal .form').attr('action', $(this).data('action'))
            $('#vacationModal .form').append('<input type="hidden" name="_method" value="PUT">')


            //set fields data
            $('#vacationModal input[name="title"]').val($(this).data('title'))
            $('#vacationModal input[name="started_at"]').val($(this).data('started_at'))
            $('#vacationModal input[name="ended_at"]').val($(this).data('ended_at'))
            $('#vacationModal input[name="details"]').val($(this).data('details'))
            $('#vacationModal textarea[name="details"]').val($(this).data('details'))

			let employee = $(this).data('employee')
			let employeeList = $('#employee option');

            let payed = $(this).data('payed')
			let payedList = $('#payed option');

			employeeList.each(function() {
				if ($(this).val() == employee) {
					$(this).attr('selected', true)
				}
			})

            payedList.each(function() {
				if ($(this).val() == payed) {
					$(this).attr('selected', true)
				}
			})

            $('.modal-footer').append('<button type="submit" class="btn btn-success">موافقة</button>');
			
			$('#vacationModal .form').show()
		}else{

			$('#vacationsLabel').text('اضافة طلب اجازة')
			$('#vacationModal .form').attr('action', "{{ route('vacations.store') }}")
			$('#vacationModal .form input[name="_method"]').remove()

            //Clear from fields
            $('#vacationModal input[name="title"]').val('')
            $('#vacationModal input[name="started_at"]').val('')
            $('#vacationModal input[name="ended_at"]').val('')
            $('#vacationModal input[name="details"]').val('')
            $('#vacationModal input[name="details"]').val('')



            $('.modal-footer').append('<button type="submit" class="btn btn-primary">اضافة</button>');

            //employees add vacations 
            if($(this).data('employee-id')) {
                $('#vacationModal .form').append(`<input type="hidden" name="employee_id" value="${$(this).data('employee-id')}" />`)
            }

            
		}
		
		$('#vacationModal').modal('show')
	})
</script>