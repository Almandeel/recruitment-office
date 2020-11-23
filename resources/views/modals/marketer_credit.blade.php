<!-- Modal -->
<div class="modal fade" id="marketerCreditModal" tabindex="-1" role="dialog" aria-labelledby="marketerCridetModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="marketerCridetModalLabel"> اضافة دفعة </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="form_marketers_credit" action="{{ route('servicesmarketers.credit') }}" method="post">
            @csrf 
            <div class="modal-body">
              <div class="form-group">
                <label for="credit">المبلغ</label>
                <input type="text" class="form-control" min="1" name="credit" id="credit" placeholder="المبلغ">
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

    $('.marketer-credit').click(function() {
      $('#form_marketers_credit').append(`<input type="hidden" name="marketer_id" value="${$(this).data('marketer')}"/>`)

      $('#form_marketers_credit #credit').attr('max', $(this).data('max'));
      $('#marketerCreditModal').modal('show')
    })
    </script>