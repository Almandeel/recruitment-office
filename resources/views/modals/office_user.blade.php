<!-- Modal -->
<div class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-labelledby="userModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="userModalLabel">إضافة جديد</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      {{-- <form id="form_users" action="{{ route('offices.users.store') }}" method="post">
        @csrf  --}}
        <div class="modal-body">
          <div class="row">
            <div class="form-group col-md-6">
              <label for="name">الإسم</label>
              <input type="text" class="form-control" name="name" placeholder="الإسم" value="{{ old('name') }}">
            </div>
            <div class="form-group col-md-6">
              <label for="username">إسم المستخدم</label>
              <input type="text" class="form-control" name="username" placeholder="إسم المستخدم" value="{{ old('username') }}">
            </div>
            <div class="form-group col-md-6">
              <label for="password">كلمة المرور</label>
              <input class="form-control" type="password" name="password" id="password" placeholder="كلمة المرور">
            </div>
            <div class="form-group col-md-6">
              <label for="phone">رقم الهاتف</label>
              <input type="number" class="form-control" name="phone" placeholder="رقم الهاتف" value="{{ old('phone') }}">
            </div>
            <div class="form-group col-md-6">
              <label for="password">إعادة كلمة المرور</label>
              <input class="form-control" type="password" name="password_confirmation" placeholder="إعادة كلمة المرور" 
               data-parsley-equalto="#password" data-parsley-equalto-message="كلمة المرور غير متطابقة">
            </div>
            <div class="form-group col-md-6">
              <label for="status">الحالة</label>
              <select class="custom-select" name="status" id="status">
                <option value="1">نشط</option>
                <option value="0">غير نشط</option>
              </select>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="reset" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
          <button type="button" class="btn btn-primary" data-dismiss="modal">حفظ</button>
        </div>
      {{-- </form> --}}
    </div>
  </div>
</div>


<script>
  $('.users').click(function() {
    if($(this).hasClass("update")) {
      $('#form_users').attr('action', $(this).data('action'))
      $('#form_users').append('<input type="hidden" name="_method" value="PUT">')
      $('.modal-title').text('تعديل')
      
      //set data to inputs
      $('#userModal input[name="name"]').val($(this).data('name'))
      $('#userModal input[name="username"]').val($(this).data('username'))
      $('#userModal input[name="phone"]').val($(this).data('phone'))
      $('#userModal select[value="'+$(this).data('status')+'"]').attr(selected, true)
    }else {
      $('#form_users').attr('action', "{{ route('users.store') }}")
      $('.modal-title').text('إضافة جديد')
      $('#form_users').append('<input type="hidden" name="_method" value="POST">')
      
      //delete data from inputs
      resetFromFields();
    }

    $('#userModal').modal('show')
  })

  $('button type="reset"').click(function() {
    resetFromFields();
  });

  function resetFromFields() {
    $('#userModal input[name="name"]').val('')
    $('#userModal input[name="username"]').val('')
    $('#userModal input[name="phone"]').val('')
    $('#userModal select[valeu="1"]').attr(selected, true)
  }
</script>