@if ($errors->any())
	@component('components.widget')
		@slot('type', 'danger')
		@slot('not_outlined', true)
		@slot('title')
			اخطاء النموذج
		@endslot
		@slot('body')
			<ol>
				@foreach ($errors->all() as $error)
					<li>{{ $error }}</li>
				@endforeach
			</ol>
		@endslot
	@endcomponent
@endif