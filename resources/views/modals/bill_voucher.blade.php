    <!-- Modal -->
<div class="modal fade" id="billVoucherModal" tabindex="-1" role="dialog" aria-labelledby="billVoucherModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="billVoucherModalLabel">اضافة دفعة</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form id="form_countries" action="{{ route('bill.voucher') }}" method="post">
            @csrf 
            <div class="modal-body">
                <div class="form-group">
                <label for="amount">القيمة</label>
                <input type="text" id="amount" class="form-control" name="amount" placeholder="القيمة">
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
$('.billVoucherModal').click(function () {
    $('#billVoucherModal form').append(`<input type="hidden" name="bill_id" value="${$(this).data('bill_id')}"/>`);
})
</script>