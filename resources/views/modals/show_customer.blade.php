<div class="modal fade" id="showModal" tabindex="-1" role="dialog" aria-labelledby="taskLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left" id="taskLabel"></h5>
                <button type="button" class="close pull-right" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered table-striped">
                    <tr>
                        <th>الاسم</th>
                        <td id="name"></td>
                    </tr>
                    <tr>
                        <th>رقم الهاتف</th>
                        <td id="phone"></td>
                    </tr>
                    <tr>
                        <th>العنوان</th>
                        <td id="address"></td>
                    </tr>
                    <tr>
                        <th>الملاحظات</th>
                        <td id="description"></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
	$('.show-customer').click(function() {
        //set fields data
        $('#showModal #name').text($(this).data('name'))
        $('#showModal #phone').text($(this).data('phone'))
        $('#showModal #address').text($(this).data('address'))
        $('#showModal #descriptoin').text($(this).data('descriptoin'))
	})
</script>
