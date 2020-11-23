<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="x-ua-compatible" content="ie=edge">

        <title>{{ config('app.name')  }} @if (isset($title))| {{ $title }} @endif</title>

        <!-- Font Awesome Icons -->
        <link rel="stylesheet" href="{{ asset('dashboard/plugins/fontawesome-free/css/all.min.css') }}">
        <!-- jQuery -->
        <script src="{{ asset('dashboard/plugins/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('dashboard/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
        <link rel="stylesheet" href="{{ asset('dashboard/plugins/sweetalert2/sweetalert2.min.css') }}">


        <!-- Theme style -->
        <link rel="stylesheet" href="{{ asset('dashboard/css/adminlte.min.css') }}">
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
        <style>
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
        <link rel="stylesheet" href="{{ asset('dashboard/css/style.css') }}">
        <link rel="stylesheet" href="{{ asset('dashboard/css/custom.css') }}">
        <link rel="stylesheet" href="{{ asset('dashboard/plugins/sweetalert2/sweetalert2.min.css') }}">
        <!-- Google Font: Source Sans Pro -->
        <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('dashboard/plugins/parsleyjs/parsley.min.css') }}">
        <!-- Ionicons -->
        <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
        @stack('head')
        <style>
            .wrapper{
                margin-top: 30px;
            }
            .wrapper h1{
                margin: 48px 0px;
            }
            .business {
                margin-top:15%
            }
        </style>
        @if(request()->country_id)
            <style>
                .business {
                    display: none
                }
            </style>
        @else 
            <style>
                .content {
                    display: none
                }
            </style>
            <script>
                $(function () {
                    $('.content').fadeOut()
                })
            </script>
        @endif
    </head>
    <body>
        <div class="wrapper">
            <div class="container">
                <div class="alert alert-warning text-center">
                    Your account isn't activated yet!.
                    You can contact the managements
                </div>
            </div>
        <div class="text-center">
            <button type="button" class="btn btn-secondary logout">
                <i class="fa fa-power-off"></i>
                <span>Log out</span>
            </button>
            <form id="logoutForm" action="{{ route('logout') }}" method="POST">@csrf @method('POST')</form>
        </div>
        </div>
    </body>


    <script src="{{ asset('dashboard/plugins/sweetalert2/sweetalert2.all.min.js') }}"></script>
	<script>
		$(function(){
			let navTabs = $('ul.nav.nav-tabs')
			let tabsContent = $('div.tab-content')
			if(navTabs.length && tabsContent.length){
				$('body').append('<input type="hidden" id="active-tab" name="active_tab" value="{{ request("active_tab") }}" />')
				
				$('.nav.nav-tabs > li > a').click(function(){
					let tab = $(this).attr('href') + "";
					$('input#active-tab').val(tab.substring(6, tab.length))
				})	

				$('form').on('submit', (e) => {
					$(e.target).append($('input#active-tab'))
				})
			}
			$(document).on('click', '.logout', function(e){
				e.preventDefault()
				let that = $(this);
				Swal.fire({
					title: '@lang("global.sure")',
					text: "@lang('global.log_out_message')",
					icon: 'warning',
					showCancelButton: true,
					confirmButtonColor: '#3085d6',
					cancelButtonColor: '#d33',
					cancelButtonText: '@lang("global.btn_cancel")',
					confirmButtonText: "@lang('global.btn_confirm')",
				}).then((result) => {
					if (result.value) {
						if(that.data('callback')){
							executeFunctionByName(that.data('callback'), window)
						}
						else if(that.data('form')){
							$(that.data('form')).submit()
						}
						else{
							$('form#logoutForm').submit()
						}
					}	
				})
			})

			@foreach (['success' => 'تم بنجاح', 'error' => 'حدث خطأ', 'warning' => 'تحذير'] as $icon => $title)
				@if (session()->has($icon))
					Swal.fire({
						icon: '{{ $icon }}',
						title: '{{ $title }}',
						text: '{{ session()->get($icon) }}',
						okButtonText: 'حسنا',
					})
				@endif
			@endforeach
		})

        $('.start').click(function () {
            $('.business').fadeOut(15)
            $(this).fadeOut(15)
            $('.content').fadeIn(15)
        })
    </script>
</html>