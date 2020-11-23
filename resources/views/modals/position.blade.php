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
        <form id="form_positions" action="{{ route('positions.store') }}" method="post">
            @csrf 
            <div class="modal-body">
              <div class="form-group">
                <label for="title">الاسم</label>
                <input type="text" class="form-control" name="title" placeholder="الاسم" required>
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

    $('.positions').click(function() {
        if($(this).hasClass("update")) {
          $('#form_positions').attr('action', $(this).data('action'))
          $('#form_positions').append('<input type="hidden" name="_method" value="PUT">')
          $('.modal-title').text('تعديل')
    
    
          //set data to inputs
          $('#departmentModal input[name="title"]').val($(this).data('title'))
          
          
        }else {
          $('#form_positions').attr('action', "{{ route('positions.store') }}")
          $('.modal-title').text('اضافة')
          
          
          //delete data from inputs
          $('#departmentModal input[name="title"]').val('')
        }
        $('#departmentModal').modal('show')
    })
    </script>