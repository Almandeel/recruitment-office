<!-- Modal -->
<div class="modal fade" id="complaintModal" tabindex="-1" role="dialog" aria-labelledby="complaintModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="complaintModalLabel">اضافة شكوى</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form_positions" action="{{ route('complaints.store') }}" method="post">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="title">العميل</label>
                        <select class="form-control" name="customer_id" placeholder="العميل" required>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="title">CV</label>
                        <select class="form-control" name="cv_id" placeholder="CV" required>
                            @foreach ($cvs as $cv)
                                <option value="{{ $cv->id }}">{{ $cv->name }} | {{ $cv->passport }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="title">الشكوى</label>
                        <textarea name="cause" class="form-control" placeholder="الشكوى" rows="5" cols="5" required></textarea>
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
</script>
