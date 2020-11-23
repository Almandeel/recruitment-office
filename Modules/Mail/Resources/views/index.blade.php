@extends('layouts.master', [
    'title' => 'صندوق ' . $boxes[$box],
    'datatable' => true, 
    'crumbs' => [
        ['#', 'صندوق ' . $boxes[$box]],
    ]
])


@section('content')
<section class="content">
	@component('components.widget')
	@slot('noPadding', true)
		@slot('title')
			<i class="fas fa-list"></i>
			<span>الرسائل</span>
		@endslot
		@slot('widgets', ['maximize', 'collapse'])
		@slot('tools')
			<button type="button" class="btn btn-default btn-xs checkbox-toggle"><i class="far fa-square"></i>
			</button>
			<div class="btn-group">
				<button type="button" class="btn btn-danger btn-xs"><i class="far fa-trash-alt"></i></button>
			</div>
		@endslot
		@slot('body')
			<table id="datatable" class="table datatable table-striped">
				<thead>
					<tr>
						<th>
							<div class="icheck-primary">
								<input type="checkbox" value="" id="check-all">
								<label for="check-all"></label>
							</div>
						</th>
						<th>العنوان</th>
						@if ($box == $inbox)
						<th>المرسل</th>
						@elseif ($box == $outbox)
						<th>المستلمون</th>
						@endif
						<th>التاريخ</th>
						<th>الخيارات</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($letters as $letter)
						<a href="{{ route('mail.show', $letter) }}">
							<tr>
								<td>
									<div class="icheck-primary">
										<input type="checkbox" value="" id="check{{ $loop->index + 1 }}">
										<label for="check{{ $loop->index + 1 }}"></label>
									</div>
								</td>
								<td><a href="{{ route('mail.show', $letter) }}"> {{ $letter->title }} </a></td>
								@if ($box == $inbox)
									<td>{{ $letter->user->name }}</td>
								@elseif ($box == $outbox)
									<td>
										@foreach ($letter->receivers() as $receiver)
											<div class="badge badge-info">{{ $receiver->name }}</div>
										@endforeach
									</td>
								@endif
								<td>{{ $letter->created_at->format('Y-m-d') }}</td>
								<td>
									<a href="{{ route('mail.show', $letter) }}" class="btn btn-info btn-xs">
										<i class="fa fa-eye"></i>
										<span class="d-sm-none d-md-inline">عرض</span>
									</a>
									<a href="{{ route('mail.create', ['letter_id' => $letter->id]) }}" class="btn btn-primary btn-xs">
										<i class="fa fa-forward"></i>
										<span class="d-sm-none d-md-inline">إعادة توجيه</span>
									</a>
									@if ($box == $drafts)
										<button class="btn btn-danger btn-xs delete" data-form="#deleteForm">
											<i class="fa fa-trash"></i>
											<span>حذف</span>
										</button>
										<form id="deleteForm" action="{{ route('mail.destroy', $letter->id) }}" method="POST">
											@csrf
											@method('DELETE')
										</form>
									@endif
								</td>
							</tr>
						</a>
					@endforeach
				</tbody>
			</table>
		@endslot
	@endcomponent	
</section>
@endsection


@push('foot')
<script>
    $(function () {
      //Enable check and uncheck all functionality
      $('.checkbox-toggle').click(function () {
        var clicks = $(this).data('clicks')
        if (clicks) {
          //Uncheck all checkboxes
          $('.mailbox-messages input[type=\'checkbox\']').prop('checked', false)
          $('.checkbox-toggle .far.fa-check-square').removeClass('fa-check-square').addClass('fa-square')
        } else {
          //Check all checkboxes
          $('.mailbox-messages input[type=\'checkbox\']').prop('checked', true)
          $('.checkbox-toggle .far.fa-square').removeClass('fa-square').addClass('fa-check-square')
        }
        $(this).data('clicks', !clicks)
      })
  
      //Handle starring for glyphicon and font awesome
      $('.mailbox-star').click(function (e) {
        e.preventDefault()
        //detect type
        var $this = $(this).find('a > i')
        var glyph = $this.hasClass('glyphicon')
        var fa    = $this.hasClass('fa')
  
        //Switch states
        if (glyph) {
          $this.toggleClass('glyphicon-star')
          $this.toggleClass('glyphicon-star-empty')
        }
  
        if (fa) {
          $this.toggleClass('fa-star')
          $this.toggleClass('fa-star-o')
        }
      })
    })
  </script>
@endpush
