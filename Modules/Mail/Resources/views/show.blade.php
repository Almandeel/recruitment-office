@extends('layouts.master', [
    'title' => 'صندوق ' . $boxes[$box],
    'datatable' => true,
    'lightbox' => true,
    'modals' => ['attachment'],
    'summernote' => true, 
    'crumbs' => [
        [route('mail.index',['box' => $box]), 'صندوق ' . $boxes[$box]],
        ['#', $letter->title],
    ]
])


@push('head')
@endpush

@section('content')
    <section class="content">
        @component('components.widget')
            @slot('noPadding', true)
            @slot('title')
                {{--  <i class="fas fa-list"></i>  --}}
                <span>{{ $letter->title }}</span>
            @endslot
            @slot('widgets', ['maximize', 'collapse'])
            @slot('body')
                <div class="mailbox-read-info">
                    @if($box == $inbox)
                        <h6>المرسل : {{ $letter->user->name }}</h6>
                    @elseif($box == $outbox)
                    <h6>المستلمون</h6>
                    <div>
                        @foreach ($letter->receivers() as $receiver)
                        <span class="badge badge-info">{{ $receiver->name }}</span>
                        @endforeach
                    </div>
                    @endif
                    <h6>
                        <span class="mailbox-read-time">{{ $letter->created_at->format('Y-m-d H:i') }}</span></h6>
                    </h6>
                </div>
            @endslot
        @endcomponent
        @component('components.widget')
            @slot('title', '')
            @slot('widgets', ['maximize', 'collapse'])
            @slot('body')
                {!!  html_entity_decode(htmlspecialchars_decode($letter->content))  !!}
                <hr>
                <h4 class="text-primary">
                    <i class="fas fa-paperclip"></i>
                    <span>المرفقات</span>
                </h4>
                @component('components.attachments-viewer')
                    @slot('attachments', $letter->attachments)
                    @slot('canAdd', $letter->user_id == auth()->user()->getKey())
                    @slot('attachableType', 'Modules\Mail\Models\Letter')
                    @slot('attachableId', $letter->id)
                @endcomponent
            @endslot
            @slot('footer')
                <button class="btn btn-danger btn-sm delete" data-form="#deleteForm">
                    <i class="fa fa-trash"></i>
                    <span class="d-sm-none d-md-inline">حذف</span>
                </button>
                <a href="{{ route('mail.create', ['letter_id' => $letter->id]) }}" class="btn btn-primary btn-sm">
                    <i class="fa fa-forward"></i>
                    <span class="d-sm-none d-md-inline">إعادة توجيه</span>
                </a>
                <button type="button" class="btn btn-default btn-sm reply-button" data-toggle="tooltip" data-container="body" title="Reply">
                    <i class="fas fa-reply"></i>
                </button>
                <form id="deleteForm" action="{{ route('mail.destroy', $letter->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                </form>
            @endslot
        @endcomponent
        <div class="reply d-none">
            @component('components.widget')
                @slot('title')
                    <i class="fas fa-reply"></i>
                    <span>رد</span>
                @endslot
                @slot('widgets', ['maximize', 'collapse'])
                @slot('body')
                    <form action="{{ route('mail.update', $letter->id) }}?type=reply" method="post">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <textarea name="content" id="compose-textarea" rows="20" cols="20" class="form-control" style="height: 300px">
                                {!!  html_entity_decode(htmlspecialchars_decode(old('content')))  !!}
                            </textarea>
                        </div>
                    
                        <input type="hidden" name="to" value="{{ $letter->user->id }}">
                        <div class="card-footer">
                            <div class="float-right">
                            </div>
                            <button type="submit" class="btn btn-default"><i class="fa fa-reply"></i> ارسال </button>
                        </div>
                    </form>
                @endslot
            @endcomponent
        </div>
        @foreach ($letter->replyLetters as $index=>$reply_letter)
        <!-- Accordion card -->
        <div class="card">
        
            <!-- Card header -->
            <div class="card-header" role="tab" id="reply{{ $index + 1 }}">
                <a class="collapsed{{ $index + 1 }}" data-toggle="collapse{{ $index + 1 }}" data-parent="#accordionEx{{ $index + 1 }}" href="#reply-collapse{{ $index + 1 }}"
                    aria-expanded="false" aria-controls="reply{{ $index + 1 }}">
                    <h6>من : {{ $reply_letter->user->name }}</h6>
                    <h6>
                        <span class="mailbox-read-time">{{ $reply_letter->created_at->format('Y-m-d H:i') }}</span></h6>
                    </h6>
                </a>
            </div>
        
            <!-- Card body -->
            <div id="reply-collapse{{ $index + 1 }}" class="collapse{{ $index + 1 }}" role="tabpanel" aria-labelledby="reply{{ $index + 1 }}"
                data-parent="#accordionEx{{ $index + 1 }}">
                <div class="card-body">
                    {!!  html_entity_decode(htmlspecialchars_decode($reply_letter->content))  !!}
                </div>
            </div>
        
        </div>
        <!-- Accordion card -->
        @endforeach
    </section>
@endsection

@push('foot')
<script src="{{ asset('dashboard/plugins/summernote/summernote-bs4.min.js') }}"></script>
<!-- Page Script -->
<script>
  $(function () {
    //Add text editor
    $('#compose-textarea').summernote()


    $('.reply-button').click(function () {
        $('.reply').toggleClass('d-none')
    })
  })
</script>
@endpush