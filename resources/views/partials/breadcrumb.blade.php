<!-- Content Header (Page header) -->
<div class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-12">
				<ol class="breadcrumb">
					@if (request()->segment(1) == 'office')
						<li class="breadcrumb-item"><a href="{{ url('/office') }}">Control Panel </a></li>
					@else
						<li class="breadcrumb-item"><a href="{{ url('/') }}">  لوحة التحكم</a></li>
					@endif
					@if (isset($crumbs))
						@if ((request()->segment(1) == 'hr'))
							<li class="breadcrumb-item"><a href="{{ route('hr.dashboard') }}">نظام الموارد البشرية</a></li>		
						@elseif ((request()->segment(1) == 'services'))
							<li class="breadcrumb-item"><a href="{{ route('services.dashboard') }}">نظام خدمة العملاء</a></li>			
						@elseif ((request()->segment(1) == 'accounting'))
							<li class="breadcrumb-item"><a href="{{ route('accounting.dashboard') }}">نظام الحسابات</a></li>			
						@elseif ((request()->segment(1) == 'users'))
							<li class="breadcrumb-item"><a href="{{ route('users.dashboard') }}">نظام المستخدمين</a></li>
						@elseif ((request()->segment(1) == 'warehouse'))
							<li class="breadcrumb-item"><a href="{{ route('warehouse.dashboard') }}">نظام الإيواء</a></li>						
						@elseif ((request()->segment(1) == 'tutorial'))
							<li class="breadcrumb-item"><a href="{{ route('tutorials.dashboard') }}">مركز المعرفة</a></li>						
						@endif
						@foreach ($crumbs as $crumb)
							<li class="breadcrumb-item">
								@if ($crumb[0] == '#')
									<span>{{ $crumb[1] }}</span>
								@else
									<a href="{{ $crumb[0] }}">{{ $crumb[1] }}</a>
								@endif
							</li>
						@endforeach
					@endif
				</ol>
			</div><!-- /.col -->
			<div class="col-sm-12">
				@if (isset($title))
					<h1>{{ $title }}</h1>
				@endif
			</div><!-- /.col -->
		</div><!-- /.row -->
	</div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->