<div class="modal fade" id="warehouseUserModal" tabindex="-1" role="dialog" aria-labelledby="taskLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title pull-left" id="taskLabel">   </h5>
          <button type="button" class="close pull-right" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form class="form" action="{{ route('warehouseuser.storeuser') }}" method="POST">
            @csrf
            <div class="modal-body">

        <div class="form-group">
            <label>المشرف</label>
            <select  class="form-control name" name="user_id" placeholder="الاسم" required>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
				</div>
				
        <div class="form-group">
            <input type="hidden" value="{{ $warehouse->id }}" name="warehouse_id">
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
	$('.warehouseuser').click(function() {
    $('#warehouseUserModal .modal-title').text(' اضافة مشرف الي : ' + $(this).data('name'))
		$('#warehouseUserModal').modal('show')
	})
</script>
