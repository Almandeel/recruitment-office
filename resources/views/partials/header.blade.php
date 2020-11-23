<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="x-ua-compatible" content="ie=edge">

	<title>{{ auth()->guard('office')->check() ? auth()->user()->office->name : config('app.name')  }} @if (isset($title))| {{ $title }} @endif</title>

	<!-- Font Awesome Icons -->
	<link rel="stylesheet" href="{{ asset('dashboard/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- jQuery -->
    <script src="{{ asset('dashboard/plugins/jquery/jquery.min.js') }}"></script>
	<script src="{{ asset('dashboard/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
	
  
	<!-- Theme style -->
	<link rel="stylesheet" href="{{ asset('dashboard/css/adminlte.min.css') }}">
	<link rel="stylesheet" href="{{ asset('dashboard/css/custom.css') }}">
	<link rel="stylesheet" href="{{ asset('dashboard/css/style.css') }}">

	@if(!isset($ltr))
	<!-- RTL -->
	<link rel="stylesheet" href="{{ asset('dashboard/css/bootstrap-rtl.css') }}">
	<style>
		table.table-bordered.dataTable th:first-child,
		table.table-bordered.dataTable td:first-child {
		border-left-width: 0px !important;
		border-right-width: 0px !important;
		}
		
		table.table-bordered.dataTable th:last-child,
		table.table-bordered.dataTable td:last-child {
		border-right-width: 1px !important;
		border-left-width: 0px !important;
		}
	</style>
	@else
	<link rel="stylesheet" href="{{ asset('dashboard/css/style-ltr.css') }}">
	<style>
		body, table.table thead th, table.table tbody td, table.table tfoot th, table.table tfoot td{
			text-align: left;
		}
		body{
			direction: ltr;
		}
		table.table-bordered.dataTable th:last-child,
		table.table-bordered.dataTable td:last-child {
		border-left-width: 1px !important;
		border-right-width: 0px !important;
		}
	
		table.table-bordered.dataTable th:first-child,
		table.table-bordered.dataTable td:first-child {
		border-right-width: 0px !important;
		border-left-width: 0px !important;
		}
	</style>
	@endif
	<!-- Theme style --> 
	<link rel="stylesheet" href="{{ asset('dashboard/css/myEdit.css') }}">

	<link rel="stylesheet" href="{{ asset('dashboard/plugins/sweetalert2/sweetalert2.min.css') }}">
	<!-- Google Font: Source Sans Pro -->
	<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
		<link rel="stylesheet" href="{{ asset('dashboard/plugins/parsleyjs/parsley.min.css') }}">
		<style>
			.note-group-select-from-files {
				display: none;
			}
			.ltr{direction: ltr !important;}
		.rtl{direction: rtl !important;}
		.card-extra{
		padding: 15px 0px;
		border-bottom: 1px solid #ddd;
		}
		.table th, .table td{ vertical-align: middle; }
		@media('print'){
			.table-bordered td, .table-bordered th{
				border-color: #000000 !important;
			}
		}
		.select2-container--default .select2-selection--single{
			padding: .375rem .75rem;
			height: auto;
		}
	</style>
    <script>
		$(document).on('click', '.delete', function(e){
			e.preventDefault()
			let that = $(this);
			Swal.fire({
				title: "@lang('global.confirm_delete_title')",
				text: '@lang("global.confirm_delete_text")',
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				cancelButtonText: '@lang("global.btn_cancel")',
				confirmButtonText: '@lang("global.btn_confirm")',
			}).then((result) => {
				if (result.value) {
					if(that.data('callback')){
						executeFunctionByName(that.data('callback'), window)
					}
					else if(that.data('form')){
						$(that.data('form')).submit()
					}
					else{
						that.closest('form').submit()
					}
				}	
			})
		})
	</script>
	

	@if(isset($datatable))
		<!-- DataTables -->
		{{-- <link rel="stylesheet" href="{{ asset('dashboard/plugins/datatables/dataTables.bootstrap4.css') }}">  --}}
		{{-- <link rel="stylesheet" href="{{ asset('dashboard/plugins/jquery-datatables/buttons.dataTables.min.css') }}"> --}}
		<link rel="stylesheet" href="{{ asset('dashboard/plugins/jquery-datatables/jquery.dataTables.min.css') }}">
		{{-- <link rel="stylesheet" href="{{ asset('dashboard/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}"> --}}
		{{-- <link rel="stylesheet" href="{{ asset('dashboard/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}"> --}}

		<!-- DataTables -->
		{{--  <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
		<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js"></script>
		<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.flash.min.js"></script>
		<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
		<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
		<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
		<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.html5.min.js"></script>
		<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.print.min.js"></script>  --}}

		<script type="text/javascript" language="javascript" src="{{ asset('dashboard/plugins/jquery-datatables/jquery.dataTables.js') }}"></script>
		<script type="text/javascript" language="javascript" src="{{ asset('dashboard/plugins/jquery-datatables/dataTables.buttons.min.js') }}"></script>
		<script type="text/javascript" language="javascript" src="{{ asset('dashboard/plugins/jquery-datatables/fnFilterClear.js') }}"></script>
		<script type="text/javascript" language="javascript" src="{{ asset('dashboard/plugins/jquery-datatables/jszip.min.js') }}"></script>
		<script type="text/javascript" language="javascript" src="{{ asset('dashboard/plugins/jquery-datatables/pdfmake.min.js') }}"></script>
		<script type="text/javascript" language="javascript" src="{{ asset('dashboard/plugins/jquery-datatables/vfs_fonts.js') }}"></script>
		<script type="text/javascript" language="javascript" src="{{ asset('dashboard/plugins/jquery-datatables/buttons.html5.min.js') }}"></script>
		<script type="text/javascript" language="javascript" src="{{ asset('dashboard/plugins/jquery-datatables/buttons.print.min.js') }}"></script>

		{{--  <script src="{{ asset('dashboard/plugins/jquery-datatables/jquery.dataTables.min.js') }}"></script>
		<script src="{{ asset('dashboard/plugins/datatables/dataTables.bootstrap4.js') }}"></script>
		<script src="{{ asset('dashboard/plugins/jquery-datatables/dataTables.buttons.min.js') }}"></script>
		<script src="{{ asset('dashboard/plugins/jquery-datatables/buttons.print.min.js') }}"></script>  --}}
		<script>
			$.extend( true, $.fn.dataTable.defaults, {
				'paging'      : true,
				'lengthChange': true,
				'searching'   : true,
				'ordering'    : false,
				'info'        : true,
				'autoWidth'   : true,
				@if(!isset($ltr))
					'oLanguage'    : {
						"sEmptyTable" : "لا توجد بيانات في هذا الجدول",
						"sInfo" : "عرض _START_ الى _END_ من _TOTAL_ صفوف",
						"sInfoEmpty" : "عرض 0 الى 0 من 0 صفوف",
						"sInfoFiltered" : "(تصفية من _MAX_ مجموع صفوف)",
						"sInfoPostFix" :    "",
						"sInfoThousands" :  ",",
						"sLengthMenu" :     "عرض _MENU_ صفوف",
						"sLoadingRecords" : "تحميل ...",
						"sProcessing" :     "معالجة ...",
						"sSearch" :         "بحث:",
						"sZeroRecords" :    "لا توجد نتائج مطابقة",
						"oPaginate": {
							"sFirst" : "الاول",
							"sLast" : "الاخير",
							"sNext" : "التالي",
							"sPrevious" : "السابق",
						},
						"oAria": {
							"sSortAscending" :  " => تفعيل الترتيب تنازليا",
							"sSortDescending" : " => تفعيل الترتيب تصاعديا"
						}
					},
				@endif
			} );
			$(function(){
				if (!$.fn.DataTable.isDataTable( 'table.datatable' ) ) {
					$('table.datatable').dataTable({
						'dom': 'Bfrtip',
						buttons: [
							{
								extend: 'print',
								text: '@lang("accounting::global.print") <i class="fa fa-print"></i>', 
								className: 'btn btn-default',
								exportOptions: {
									columns: 'th:not(:last-child)',
									stripHtml: false
								}, 
								footer: true
							},
							{
								extend: 'excel',
								text: '@lang("accounting::global.excel") <i class="fa fa-file-excel"></i>', 
								className: 'btn btn-success',
								exportOptions: {
									columns: 'th:not(:last-child)',
									stripHtml: false
								}, 
								footer: true
							},
							
						]
					});
				}
				if (!$.fn.DataTable.isDataTable( 'table.datatable' ) ) {
					$('table.table-ordered').dataTable({
						'ordering' : true,
					});
				}
				if (!$.fn.DataTable.isDataTable( 'table.table-print' ) ) {
					$('table.table-print').dataTable({
						'paging' : false,
						'searching' : false,
						'dom': 'Bfrtip',
						buttons: [
							{
								extend: 'print',
								text: '@lang("accounting::global.print") <i class="fa fa-print"></i>', 
								className: 'btn btn-default',
								footer: true
							},
							{
								extend: 'excel',
								text: '@lang("accounting::global.excel") <i class="fa fa-file-excel"></i>', 
								className: 'btn btn-success',
								footer: true
							},
							
						]
					});
				}
				if (!$.fn.DataTable.isDataTable( 'table.table-search')) {
					$('table.table-search').dataTable({
						// 'paging' : false,
						'searching' : true,
					});
				}
			})
		</script>
	@endif
	@stack('head')
	<style>
		@stack('styles')
	</style>
</head>

<body class="hold-transition sidebar-mini">
  <div class="wrapper">
