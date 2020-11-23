<!-- Modal -->
<div class="modal fade" id="countryModal" tabindex="-1" role="dialog" aria-labelledby="countryModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="countryModalLabel">Add new</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="form_countries" action="{{ route('countries.store') }}" method="post">
            @csrf 
            <div class="modal-body">
              <div class="form-group">
                <label for="title">Name</label>
                <input type="text" class="form-control" name="name" placeholder="Name">
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

    $('.countries').click(function() {
        if($(this).hasClass("update")) {
          $('#form_countries').attr('action', $(this).data('action'))
          $('#form_countries').append('<input type="hidden" name="_method" value="PUT">')
          $('.modal-title').text('Edit')
    
          //set data to inputs
          $('#countryModal input[name="name"]').val($(this).data('name'))
          
          
        }else {
          $('#form_countries').attr('action', "{{ route('countries.store') }}")
          $('.modal-title').text('Add new')
          $('#form_countries').append('<input type="hidden" name="_method" value="POST">')
          
          //delete data from inputs
          $('#countryModal input[name="name"]').val('')
        }
        $('#countryModal').modal('show')
    })
    </script>