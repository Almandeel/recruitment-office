<!-- Modal -->
<div class="modal fade" id="advanceModal" tabindex="-1" role="dialog" aria-labelledby="advanceModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="advanceModalLabel">Add new</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="form_advances" action="{{ route('advances.store') }}" method="post">
          @csrf
          <div class="modal-body">
            <div class="form-group">
              <label for="amount">Amount</label>
              <input type="number" class="form-control" name="amount" placeholder="Amount" min="0">
            </div>
            <div class="col">
              @component('components.widget')
                @slot('noTitle', true)
                @slot('title')
                  <i class="fas fa-paperclip"></i>
                  <span>Attachments</span>
                @endslot
                @slot('body')
                  @component('accounting::components.attachments-uploader')@endcomponent
                @endslot
              @endcomponent
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
  $('.advance').click(function() {
    if($(this).hasClass("update")) {
      $('#form_advances').attr('action', $(this).data('action'))
      $('#form_advances').append('<input type="hidden" name="_method" value="PUT">')
      $('.modal-title').text('Edit Advance')

      //set data to inputs
      $('#advanceModal input[name="amount"]').val($(this).data('amount'))
    }else {
      $('#form_advances').attr('action', "{{ route('advances.store') }}")
      $('.modal-title').text('Add new')
      $('#form_advances').append('<input type="hidden" name="_method" value="POST">')
      
      //delete data from inputs
      $('#advanceModal input[name="amount"]').val('')
    }

    $('#advanceModal').modal('show')
  })
</script>