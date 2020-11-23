<!-- Modal -->
<div class="modal fade" id="professionModal" tabindex="-1" role="dialog" aria-labelledby="professionModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="professionModalLabel">Add new</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="form_professions" action="{{ route('professions.store') }}" method="post">
            @csrf 
            <div class="modal-body">
              <div class="form-group">
                <label for="name">Arabic name</label>
                <input type="text" class="form-control" name="name" id="name" placeholder="Arabic name">
              </div>
              <div class="form-group">
                <label for="name_en">English name</label>
                <input type="text" class="form-control" name="name_en" id="name_en" placeholder="English name">
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

    $('.professions').click(function() {
        if($(this).hasClass("update")) {
          $('#form_professions').attr('action', $(this).data('action'))
          $('#form_professions').append('<input type="hidden" name="_method" value="PUT">')
          $('.modal-title').text('Edit')
    
          //set data to inputs
          $('#professionModal input[name="name"]').val($(this).data('name'))
          $('#professionModal input[name="name_en"]').val($(this).data('name_en'))
          
          
        }else {
          $('#form_professions').attr('action', "{{ route('professions.store') }}")
          $('.modal-title').text('Add new')
          $('#form_professions').append('<input type="hidden" name="_method" value="POST">')
          
          //delete data from inputs
          $('#professionModal input[name="name"]').val('')
          $('#professionModal input[name="name_en"]').val($(this).data(''))
        }
        $('#professionModal').modal('show')
    })
    </script>