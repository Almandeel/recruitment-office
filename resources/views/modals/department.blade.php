<!-- Modal -->
<div class="modal fade" id="departmentModal" tabindex="-1" role="dialog" aria-labelledby="departmentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="departmentModalLabel">اضافة</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="form_departments" action="{{ route('departments.store') }}" method="post">
            @csrf 
            <div class="modal-body">
              <div class="form-group">
                <label for="title">الاسم</label>
                <input type="text" class="form-control" name="title" placeholder="الاسم">
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">اغلاق</button>
              <button type="submit" class="btn btn-primary">حفظ</button>
            </div>
        </form>
      </div>
    </div>
  </div>


  <script>

    $('.departments').click(function() {
        if($(this).hasClass("update")) {
          $('#form_departments').attr('action', $(this).data('action'))
          $('#form_departments').append('<input type="hidden" name="_method" value="PUT">')
          $('.modal-title').text('تعديل')
    
    
          //set data to inputs
          $('#departmentModal input[name="title"]').val($(this).data('title'))
          
          
        }else {
          $('#form_departments').attr('action', "{{ route('departments.store') }}")
          $('.modal-title').text('اضافة')
          
          
          //delete data from inputs
          $('#departmentModal input[name="title"]').val('')
        }
        $('#departmentModal').modal('show')
    })
    </script>