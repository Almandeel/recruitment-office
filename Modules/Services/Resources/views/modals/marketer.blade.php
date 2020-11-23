<!-- Modal -->
<div class="modal fade" id="marketerModal" tabindex="-1" role="dialog" aria-labelledby="marketerModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="marketerModalLabel">Add new</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="form_marketers" action="{{ route('marketers.store') }}" method="post">
            @csrf 
            <div class="modal-body">
              <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" name="name" id="name" placeholder="Name">
              </div>
              <div class="form-group">
                <label for="phone">Phone</label>
                <input type="text" class="form-control" name="phone" id="phone" placeholder="Phone">
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-primary">Save</button>
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
          $('.modal-title').text('Edit')
    
          //set data to inputs
          $('#marketerModal input[name="name"]').val($(this).data('name'))
          $('#marketerModal input[name="phone"]').val($(this).data('phone'))
          
          
        }else {
          $('#form_marketers').attr('action', "{{ route('marketers.store') }}")
          $('.modal-title').text('Add new')
          $('#form_marketers').append('<input type="hidden" name="_method" value="POST">')
          
          //delete data from inputs
          $('#marketerModal input[name="name"]').val('')
          $('#marketerModal input[name="phone"]').val($(this).data(''))
        }
        $('#marketerModal').modal('show')
    })
    </script>