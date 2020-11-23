<div class="modal fade" id="customerModal" tabindex="-1" role="dialog" aria-labelledby="customerLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left" id="customerLabel">إضافة عميل</h5>
                <button type="button" class="close pull-right" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="form" action="{{ route('customers.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>الاسم</label>
                        <input class="form-control name" autocomplete="off" type="text" name="name" placeholder="الاسم"
                            required />
                    </div>

                    <div class="form-group">
                        <label>العنوان</label>
                        <input class="form-control name" autocomplete="off" type="text" name="address"
                            placeholder="العنوان" required />
                    </div>

                    <div class="form-group">
                        <label>رقم الهاتف</label>
                        <input class="form-control" autocomplete="off" type="number" name="phones"
                            placeholder="رقم الهاتف" required />
                    </div>
                    <div class="form-group">
                        <label>رقم الهوية</label>
                        <input class="form-control" autocomplete="off" type="number" name="id_number"
                            placeholder="رقم الهوية"  />
                    </div>
                    <div class="form-group">
                        <label>ملاحظات</label>
                        <textarea class="form-control" autocomplete="off" name="description" placeholder="ملاحظات"></textarea>
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
    $('.customer').click(function () {
        if ($(this).hasClass("update")) {
            $('#customerLabel').text('تعديل عميل: ' + $(this).data('name'))
            $('#customerModal .form').attr('action', $(this).data('action'))
            $('#customerModal .form').append('<input type="hidden" name="_method" value="PUT">')

            //set fields data
            $('#customerModal input[name="name"]').val($(this).data('name'))
            $('#customerModal input[name="address"]').val($(this).data('address'))
            $('#customerModal input[name="phones"]').val($(this).data('phones'))
            $('#customerModal input[name="id_number"]').val($(this).data('number'))
            $('#customerModal textarea[name="description"]').val($(this).data('description'))


            // $('#customerModal .preview').hide()
            $('#customerModal .form').show()
        } else {
            $('#customerLabel').text('اضافة عميل')
            $('#customerModal .form').attr('action', "{{ route('customers.store') }}")
            $('#customerModal .form input[name="_method"]').remove()


            //Clear from fields
            $('#customerModal input[name="name"]').val('')
            $('#customerModal input[name="address"]').val('')
            $('#customerModal input[name="phones"]').val('')
            $('#customerModal input[name="id_number"]').val('')
            $('#customerModal textarea[name="description"]').val('')

        }

        $('#customerModal').modal('show')
    })
</script>