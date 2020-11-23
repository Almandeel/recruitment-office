@extends('layouts.master', [
    'title' => 'ارسال رسالة',
    'datatable' => true, 
    'summernote' => true, 
    'crumbs' => [
        ['#', 'انشاء رسالة'],
    ]
])

@push('head')
@endpush

@section('content')
<section class="content">
	<form id="form" action="{{ route('mail.store') }}" method="post" enctype="multipart/form-data">
	@csrf 
	@component('components.widget')
		@slot('title', 'الرسالة')
		@slot('body')
			<div class="form-group">
				<select class="select2 form-control" multiple="multiple" data-placeholder="جهات الارسال" name="to[]" required>
					@foreach ($users as $user)
					<option value="{{ $user->id }}">{{ $user->name }}</option>
					@endforeach
				</select>
			</div>
			
			<div class="form-group">
				<input class="form-control" name="title" placeholder="الموضوع : " required value="{{ $letter ? $letter->title : '' }}">
			</div>
			
			<div class="form-group">
				<textarea name="content" id="compose-textarea" style="min-height: 200px" class="form-control" style="min-height: 300px">@if ($letter)  {!!  html_entity_decode(htmlspecialchars_decode($letter->content));  !!}  @endif</textarea>
			</div>
		@endslot
	@endcomponent
	@component('components.widget')
		@slot('noPadding', true)
		@slot('noTitle', true)
		@slot('title')
			<i class="fas fa-paperclip"></i>
			<span>المرفقات</span>
		@endslot
		@slot('body')
			@component('components.attachments-uploader')
				@if ($letter)
					@slot('attachments', $letter->attachments)
				@endif
			@endcomponent
		@endslot
	@endcomponent
	@component('components.widget')
		@slot('title', 'الخيارات')
		@slot('body')
			<div class="float-right">
				@if (!$letter)
					<button type="button" class="btn btn-default btn-save"><i class="fas fa-save"></i> حفظ</button>
				@endif
				<button type="submit" class="btn btn-primary"><i class="far fa-envelope"></i> ارسال</button>
			</div>
			{{--  <button type="reset" class="btn btn-default"><i class="fas fa-times"></i> الغاء</button>  --}}
		@endslot
	@endcomponent
	<input type="hidden" name="box" value="{{ $outbox }}">
</form>
</section>
@endsection


@push('foot')
@include('partials.select2')
<script>
	$(function(){
		$('.btn-save').click(function(){
			$('#form input[name=box]').val({{ $drafts }})
			$('#form').submit();
		})
	})
</script>

@endpush

