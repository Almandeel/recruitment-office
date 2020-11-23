<div class="modal fade" id="warehouseModal" tabindex="-1" role="dialog" aria-labelledby="taskLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title pull-left" id="taskLabel">إضافة سكن</h5>
          <button type="button" class="close pull-right" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form class="form" action="{{ route('warehouses.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label>الاسم</label>
                    <input  class="form-control name" autocomplete="off"  type="text" name="name" placeholder="الاسم" required/>
				</div>
				
                <div class="form-group">
                    <label>العنوان</label>
                    <input  class="form-control name" autocomplete="off"  type="text" name="address" placeholder="العنوان" required/>
				</div>
				
				<div class="form-group">
                    <label>رقم الهاتف</label>
                    <input  class="form-control name" autocomplete="off"  type="number" name="phone" placeholder="رقم الهاتف" required/>
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
	$('.warehouse').click(function() {
		if($(this).hasClass("update")){
            $('#taskLabel').text('تعديل  سكن: ' + $(this).data('name'))
            $('#warehouseModal .form').attr('action', $(this).data('action'))
            $('#warehouseModal .form').append('<input type="hidden" name="_method" value="PUT">')

            //set fields data
            $('#warehouseModal input[name="name"]').val($(this).data('name'))
            $('#warehouseModal input[name="address"]').val($(this).data('address'))
            $('#warehouseModal input[name="phone"]').val($(this).data('phone'))

			
            // $('#warehouseModal .preview').hide()
			$('#warehouseModal .form').show()
		}else{
			$('#taskLabel').text('اضافة سكن')
			$('#warehouseModal .form').attr('action', "{{ route('warehouses.store') }}")
			$('#warehouseModal .form input[name="_method"]').remove()


            //Clear from fields
            $('#warehouseModal input[name="name"]').val('')
            $('#warehouseModal input[name="address"]').val('')
            $('#warehouseModal input[name="phone"]').val('')
            
		}
		
		$('#warehouseModal').modal('show')
	})
</script>
