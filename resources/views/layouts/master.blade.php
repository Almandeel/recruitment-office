@include('partials.header')
@include('partials.navbar')
@include('partials.main-sidebar')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Main content -->
	<div class="content">
		<div class="container-fluid">

			@include('partials.breadcrumb')

			@include('partials.messages')

			@yield('content')

			<!-- /.row -->
		</div><!-- /.container-fluid -->
	</div>
	<!-- /.content -->
</div>
<!-- /.content-wrapper -->

@include('partials.footer')