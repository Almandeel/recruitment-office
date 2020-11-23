<form class="modal fade" id="attachmentModal" role="dialog" method="POST" action="{{ route('attachments.store') }}" enctype="multipart/form-data">
    @csrf
    <div class="modal-dialog modal-sm vertical-align-center">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">@lang('accounting::attachments.add')</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="name">@lang('accounting::global.name')</label>
                    <input type="text" id="name" name="name" class="form-control name" required>
                    <textarea name="name" class="form-control name" required></textarea>
                </div>
                <div class="form-group">
                    <label for="file">@lang('accounting::global.file')</label>
                    <input type="file" id="file" name="file" class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">@lang('accounting::global.submit')</button>
                <button type="button" data-dismiss="modal" class="btn btn-default">@lang('accounting::global.close')</button>
            </div>
        </div>
    </div>
</form>

<script>
    $(function(){
        $('*[data-modal=attachment]').click(function() {
            let title = $(this).data('title');
            let isNote = $(this).data('is-note');
            let name = $(this).data('name');
            let action = $(this).data('action');
            let method = $(this).data('method');
            let attachmentMethod = $('#attachmentModal #atachmentMethod');

            let attachableId = $(this).data('attachable-id');
            let attachmentAttachableId = $('#attachmentModal #attachmentAttachableId');
            
            let attachableType = $(this).data('attachable-type');
            let attachmentAttachableType = $('#attachmentModal #attachmentAttachableType');
            if(isNote){
                $('#attachmentModal label[for=name]').text("@lang('accounting::global.note')")
                $('#attachmentModal input#file').parent().hide()

                $('#attachmentModal input.name').hide()
                $('#attachmentModal textarea.name').show()

                $('#attachmentModal input.name').attr('name', '')
                $('#attachmentModal textarea.name').attr('name', 'name')

                $('#attachmentModal input.name').attr('required', false)
                $('#attachmentModal textarea.name').attr('required', true)
            }else{
                $('#attachmentModal label[for=name]').text("@lang('accounting::global.name')")
                $('#attachmentModal input#file').parent().show()

                $('#attachmentModal textarea.name').hide()
                $('#attachmentModal input.name').show()

                $('#attachmentModal textarea.name').attr('name', '')
                $('#attachmentModal input.name').attr('name', 'name')

                $('#attachmentModal textarea.name').attr('required', false)
                $('#attachmentModal input.name').attr('required', true)
            }
            
            if(method == 'PUT'){
                $('#attachmentModal .modal-title').text("@lang('accounting::attachments.edit')")
            }else{
                $('#attachmentModal .modal-title').text("@lang('accounting::attachments.add')")
            }
            if(name){
                if(isNote){
                    $('#attachmentModal textarea[name=name]').val(name)
                }else{
                    $('#attachmentModal input[name=name]').val(name)
                }
            }else{
                $('#attachmentModal input[name=name]').val('')
                $('#attachmentModal textarea[name=name]').val('')
            }
            if(action){
                $('#attachmentModal').attr('action', action);
            }else{
                $('#attachmentModal').attr('action', '{{ route("attachments.store") }}');
            }

            if(method){
                if(attachmentMethod.length <= 0){
                    $('#attachmentModal').append('<input type="hidden" id="atachmentMethod" name="_method" value="'+method+'">')
                }else{
                    attachmentMethod.val(method)
                }
            }else{
                if(attachmentMethod.length > 0){
                    attachmentMethod.remove()
                }
            }

            if(attachableId){
                if(attachmentAttachableId.length <= 0){
                    $('#attachmentModal').append('<input type="hidden" id="attachmentAttachableId" name="attachable_id" value="'+attachableId+'">')
                }else{
                    attachmentAttachableId.val(attachableId)
                }
            }else{
                if(attachmentAttachableId.length > 0){
                    attachmentAttachableId.remove()
                }
            }

            if(attachableType){
                if(attachmentAttachableType.length <= 0){
                    $('#attachmentModal').append('<input type="hidden" id="attachmentAttachableType" name="attachable_type" value="'+attachableType+'">')
                }else{
                    attachmentAttachableType.val(attachableType)
                }
            }else{
                if(attachmentAttachableType.length > 0){
                    attachmentAttachableType.remove()
                }
            }


            $('#attachmentModal').modal('show');
        })
    })
</script>