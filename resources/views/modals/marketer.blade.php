<!-- Modal -->
<div class="modal fade" id="marketerModal" tabindex="-1" role="dialog" aria-labelledby="marketerModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="marketerModalLabel"> اضافة مسوق </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="form_marketers" action="{{ route('servicesmarketers.store') }}" method="post">
            @csrf 
            <div class="modal-body">
              <div class="form-group">
                <label for="name">الاسم </label>
                <input type="text" class="form-control" name="name" id="name" placeholder="الاسم ">
              </div>
              <div class="form-group">
                <label for="phone">رقم الهاتف</label>
                <input type="text" class="form-control" name="phone" id="phone" placeholder="رقم الهاتف">
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

    $('.marketers').click(function() {
        if($(this).hasClass("update")) {
          $('#form_marketers').attr('action', $(this).data('action'))
          $('#form_marketers').append('<input type="hidden" name="_method" value="PUT">')
          $('.modal-title').text('تعديل مسوق')
    
          //set data to inputs
          $('#marketerModal input[name="name"]').val($(this).data('name'))
          $('#marketerModal input[name="phone"]').val($(this).data('phone'))
          
          
        }else {
          $('#form_marketers').attr('action', "{{ route('servicesmarketers.store') }}")
          $('.modal-title').text('حفظ مسوق')
          $('#form_marketers').append('<input type="hidden" name="_method" value="POST">')
          
          //delete data from inputs
          $('#marketerModal input[name="name"]').val('')
          $('#marketerModal input[name="phone"]').val('')
        }
        $('#marketerModal').modal('show')
    })
    </script>