<div class="modal fade" id="transactionModal" tabindex="-1" role="dialog" aria-labelledby="transactionsLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title pull-left" id="transactionsLabel">إضافة معاملة مالية</h5>
          <button type="button" class="close pull-right" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="form_transactions" action="{{ route('transactions.store') }}" method="POST">
            @csrf
            <div class="modal-body">
				<div class="form-group row">
                    <div class="col-md-6">
                        <div class="input-group">
                            <div class="input-group-addon">
                                السنة
                            </div>
                            <select class="form-control" name="year">
                                @for($i = date('Y'); $i >= 2000; $i--)
                                    <option value="{{ $i }}" @if($year == $i) selected @endif>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group">
                            <div class="input-group-addon">
                                الشهر
                            </div>
                            <select class="form-control" name="month">
                                @for($i = 1; $i <= 12; $i++)
                                    @php
                                        $m = $i < 10 ? '0' + $i : $i;
                                    @endphp
                                    <option value="{{ $m }}" @if($month == $m) selected @endif>{{ $m }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
				</div>
                <div class="form-group row">
                    <div class="col-md-6">
                        <label>النوع</label>
                        {{--  <select  class="form-control" name="type">
                            @foreach (App\Transaction::TYPES as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>  --}}
                        @foreach (Modules\Accounting\Models\Transaction::TYPES as $value => $key)
                        <label>
                            <input type="radio" name="type" value="{{ $value }}" id="type-{{ $value }}" @if ($value == Modules\Accounting\Models\Transaction::TYPE_DEBT) checked="checked" @endif />
                            <span>{{ $key }}</span>
                        </label>
                        @endforeach
                    </div>
                    <div class="col-md-6">
                        <label>طريقة الدفع</label>
                        <label>
                            <input id="later" type="radio" name="paymethod" value="0" checked>
                            <span>لاحقا</span>
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label>المبلغ</label>
                    <input  class="form-control" required autocomplete="off" type="number" name="amount" placeholder="المبلغ">
                </div>
                <div class="form-group">
                    <label>التفاصيل</label>
                    <textarea  class="form-control" autocomplete="off" name="details" placeholder="التفاصيل"></textarea>
                </div>
                @component('accounting::components.form-safe')
                    @slot('layout', 'inline')
                @endcomponent
                <div class="form-group">
                    <label>كلمة المرور الحالية</label>
                    <input type="password" class="form-control" name="password" placeholder="كلمة المرور الحالية" required>
                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" name="employee_id" value="">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                <button type="submit" class="btn btn-primary">حفظ</button>
            </div>
        </form>
      </div>
    </div>
  </div>

<script>
	$('.showTransactionModal').click(function() {
		if($(this).hasClass("update")){
            $('#transactionsLabel').text('تعديل بيانات المعاملة رقم: ' + $(this).data('id'))
            $('#form_transactions').attr('action', $(this).data('action'))
            $('#form_transactions').append('<input type="hidden" name="_method" value="PUT">')

            //set fields data
            $('#transactionModal input[name="amount"]').val($(this).data('amount'))
            $('#transactionModal select[name="type"]').first()
            $('#transactionModal textarea[name="details"]').text($(this).data('details'))

            var that = $(this);
            $('#transactionModal').find('input[name="paymethod"]')
            .each(function(index, input){
                var method = $(input);
                method.prop('checked', (that.data('account-id') == method.val()))
            })
		}
		else{
			$('#transactionsLabel').text('اضافة معاملة مالية')
			$('#form_transactions').attr('action', "{{ route('transactions.store') }}")
			$('#form_transactions').remove('input[name="_method"]')

            //Clear from fields
            $('#transactionModal input[name="amount"]').val('')
            $('#transactionModal textarea[name="details"]').text('')
            
        }
        @foreach(Modules\Accounting\Models\Transaction::TYPES as $value => $key)
            var type = $('#transactionModal input#type-{{ $value }}');
            type.prop('checked', ($(this).data('type') == type.val()));
        @endforeach
		$('#transactionModal input[name="employee_id"]').val($(this).data('employee-id'))
		$('#transactionModal').modal('show')
	})
</script>