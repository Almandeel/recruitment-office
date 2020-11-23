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
                @if(auth()->user()->hasRole('business'))
                    <h1 class="text-center text-primary m-t-5">{{ config('app.name') }}</h1>
                    <h3 class="text-secondary text-center">
                        <span>نظام الخدمة الذاتية</span>
                    </h3>
                    <div class="row">
                        <div class="col">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ Modules\ExternalOffice\Models\Cv::getAccepted()->count() }}</h3>
                            
                                    <p>العمالة المتاحه</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-person-add"></i>
                                    {{--  <i class="fas fa-user"></i>  --}}
                                </div>
                                <a href="{{ route('contracts.create', ['view' => 'initial']) }}" class="small-box-footer">
                                    <span>إختيار عمالة</span>
                                    <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
                        <div class="col">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>بحث</h3>
                            
                                    <p>البحث عن عقد مبدئي</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-search"></i>
                                </div>
                                <a href="{{ route('contracts.search') }}" class="small-box-footer">
                                    <span>بحث</span>
                                    <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @else
                    <h1 class="text-center text-primary m-t-5">{{ config('app.name') }}</h1>
                    <div class="container">
                        <div class="row">
                            @permission(['employees-read', 'departments-read', 'positions-read', 'vacations-read', 'attendance-read', 'transactions-read', 'salaries-read', 'custodies-read'])
                                <div class="col">
                                    @component('components.widget')
                                        @slot('title')
                                            <i class="fa fa-users"></i>
                                            <span>نظام الموارد البشرية</span>
                                        @endslot
                                        @slot('body')
                                            <a href="{{ route('hr.dashboard') }}" class="btn btn-primary btn-block">
                                                <i class="fa fa-eye"></i>
                                                <span>عرض</span>
                                            </a>
                                        @endslot
                                    @endcomponent
                                </div>
                            @endpermission

                            @permission('users-read')
                                <div class="col">
                                    @component('components.widget')
                                        @slot('title')
                                            <i class="fa fa-users"></i>
                                            <span>نظام المستخدمين</span>
                                        @endslot
                                        @slot('body')
                                            <a href="{{ route('users.dashboard') }}" class="btn btn-primary btn-block">
                                                <i class="fa fa-eye"></i>
                                                <span>عرض</span>
                                            </a>
                                        @endslot
                                    @endcomponent
                                </div>
                            @endpermission

                            @permission('mail-read')
                                <div class="col">
                                    @component('components.widget')
                                        @slot('title')
                                            <i class="fa fa-envelope"></i>
                                            <span>نظام المراسلات</span>
                                            <div style="display: inline;list-style-type: none;position: absolute;top: 6px;left: 15px;" id="app">
                                                <notification-component></notification-component>
                                            </div>
                                        @endslot
                                        @slot('body')
                                            <a href="{{ route('mail.index') }}" class="btn btn-primary btn-block">
                                                <i class="fa fa-eye"></i>
                                                <span>عرض</span>
                                            </a>
                                        @endslot
                                    @endcomponent
                                </div>
                            @endpermission
                        </div>

                        <div class="row">
                            @permission('accounts-read')
                                <div class="col-md-4">
                                    @component('components.widget')
                                        @slot('title')
                                            <i class="fa fa-calculator"></i>
                                            <span>نظام الحسابات</span>
                                        @endslot
                                        @slot('body')
                                            <a href="{{ route('accounting.dashboard') }}" class="btn btn-primary btn-block">
                                                <i class="fa fa-eye"></i>
                                                <span>عرض</span>
                                            </a>
                                        @endslot
                                    @endcomponent
                                </div>
                            @endpermission
                            
                            @permission(['warehouses-read', 'warehousecvs-read'])
                                <div class="col-md-4">
                                    @component('components.widget')
                                        @slot('title')
                                            <i class="fa fa-building"></i>
                                            <span>الايواء</span>
                                        @endslot
                                        @slot('body')
                                            <a href="{{ route('warehouses.index') }}" class="btn btn-primary btn-block">
                                                <i class="fa fa-eye"></i>
                                                <span>عرض</span>
                                            </a>
                                        @endslot
                                    @endcomponent
                                </div>
                            @endpermission

                            @permission(['services-read', 'contracts-read', 'cv-read', 'customers-read', 'complaints-read', 'marketers-read'])
                                <div class="col-md-4">
                                    @component('components.widget')
                                        @slot('title')
                                            <i class="fa fa-users"></i>
                                            <span>نظام العملاء</span>
                                        @endslot
                                        @slot('body')
                                            <a href="{{ route('services.dashboard') }}" class="btn btn-primary btn-block">
                                                <i class="fa fa-eye"></i>
                                                <span>عرض</span>
                                            </a>
                                        @endslot
                                    @endcomponent
                                </div>
                            @endpermission

                            @permission('offices-read')
                                <div class="col-md-4">
                                    @component('components.widget')
                                        @slot('title')
                                            <i class="fa fa-globe"></i>
                                            <span>نظام المكاتب الخارجية</span>
                                        @endslot
                                        @slot('body')
                                            <a href="{{ route('offices.dashboard') }}" class="btn btn-primary btn-block">
                                                <i class="fa fa-eye"></i>
                                                <span>عرض</span>
                                            </a>
                                        @endslot
                                    @endcomponent
                                </div>
                            @endpermission

                            @permission('tutorials-read')
                                <div class="col-md-4">
                                    @component('components.widget')
                                        @slot('title')
                                            <i class="fa fa-book"></i>
                                            <span>إدارة مركز المعرفة</span>
                                        @endslot
                                        @slot('body')
                                            <a href="{{ route('tutorials.dashboard') }}" class="btn btn-primary btn-block">
                                                <i class="fa fa-eye"></i>
                                                <span>عرض</span>
                                            </a>
                                        @endslot
                                    @endcomponent
                                </div>
                            @endpermission

                            @permission('users-dashboard')
                                <div class="col-md-4">
                                    @component('components.widget')
                                        @slot('title')
                                            <i class="fa fa-file"></i>
                                            <span>التقارير</span>
                                        @endslot
                                        @slot('body')
                                            <a href="{{ route('report.dashboard') }}" class="btn btn-primary btn-block">
                                                <i class="fa fa-eye"></i>
                                                <span>عرض</span>
                                            </a>
                                        @endslot
                                    @endcomponent
                                </div>
                            @endpermission
                        </div>
                        <div class="row">
                            {{--  <div class="col-md-4">
                                @component('components.widget')
                                    @slot('title')
                                        <i class="fa fa-graduation-cap"></i>
                                        <span>مركز المعرفة</span>
                                    @endslot
                                    @slot('body')
                                        <a href="{{ route('tutorials.dashboard') }}" class="btn btn-primary btn-block">
                                            <i class="fa fa-eye"></i>
                                            <span>عرض</span>
                                        </a>
                                    @endslot
                                @endcomponent
                            </div>  --}}
                        </div>
                    
                    </div>
                @endif
            {{--  @if(auth()->user()->hasRole('business'))
                <div class="container-fluid">
                    <div class="contrainer">
                        <div class="business">
                            <h1 class="text-center text-primary m-t-5">مرحبا بكم في نظام اعمال</h1>
                        </div>
                        <div class="content">
                            <form action="" method="get">
                                <div class="card card-primary card-outline">
                                    <div class="card-header">
                                        <div class="">
                                            <div class="form-group">
                                                <label>
                                                    <i class="fa fa-search"></i> فرز 
                                                </label>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col">
                                                    <label for="profession_id">المهنة</label>
                                                    <select name="profession_id" id="profession_id" class="form-control">
                                                        <option value="all" {{ $profession_id == 'all' ? 'selected' : '' }}>الكل</option>
                                                        @foreach ($professions as $profession)
                                                            <option value="{{ $profession->id }}" {{ $profession->id == $profession_id ? 'selected' : '' }}>{{ $profession->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group col">
                                                    <label for="country_id">الدولة</label>
                                                    <select name="country_id" id="country_id" class="form-control">
                                                        <option value="all" {{ $country_id == 'all' ? 'selected' : '' }}>الكل</option>
                                                        @foreach ($countries as $country)
                                                            <option value="{{ $country->id }}" {{ $country->id == $country_id ? 'selected' : '' }}>{{ $country->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <button type="submit" class="btn btn-primary btn-filter">
                                                <i class="fa fa-refresh"></i>
                                                <span>تحديث</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <form action="{{ route('contracts.business') }}" method="post">
                                @csrf
                                @component('components.widget')
                                    @slot('noPadding', null)
                                    @slot('extra')

                                    @endslot
                                    @slot('body')
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="table-container">
                                                    <table id="table-cvs" class="datatable table table-bordered table-striped text-center">
                                                        <thead>
                                                            <tr>
                                                                <th>#</th>
                                                                <th>العامل \ العاملة</th>
                                                                <th>المهنة</th>
                                                                <th>الدولة</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($cvs as $c)
                                                                @if($c)
                                                                    <tr class="row-cv">
                                                                        <td><input type="radio" required name="cv_id" value="{{ $c['id'] }}"></td>
                                                                        <td>{{ $c['name'] }}
                                                                        <td>{{ $c['profession_name'] }}</td>
                                                                        <td>{{ $c['country_name'] }}</td>
                                                                    </tr>
                                                                @endif
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <table class="table table-bordered">
                                                    <div class="form-group">
                                                        <label for="name">الاسم</label>
                                                        <input type="text" name="name" id="name" required class="form-control" placeholder="الاسم">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="address">السكن</label>
                                                        <input type="text" name="address" id="address" required class="form-control" placeholder="السكن">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="phones">رقم الهاتف</label>
                                                        <input type="text" name="phones" id="phones" required class="form-control" placeholder="رقم الهاتف">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="id_number">رقم الهوية</label>
                                                        <input type="text" name="id_number" id="id_number" required class="form-control" placeholder="رقم الهوية">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="visa">رقم التأشيرة</label>
                                                        <input type="text" name="visa" id="visa" required class="form-control" placeholder="رقم التأشيرة">
                                                    </div>
                                                </table>
                                            </div>
                                        </div>
                                    @endslot
                                    @slot('footer')
                                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> اكمال العملية</button>
                                    @endslot
                                @endcomponent
                            </form>
                        </div>
                    </div>
                </div>
            @endif  --}}
        </div>
        <div class="text-center">
            <button type="button" class="btn btn-secondary logout">
                <i class="fa fa-power-off"></i>
                <span>تسجيل الخروج</span>
            </button>
            {{--  @if(auth()->user()->hasRole('business') && !request()->country_id)
                <button type="button" class="btn btn-primary start">
                    <i class="fa fa-check"></i>
                    <span>ابدا</span>
                </button>
            @endif  --}}
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
					title: 'هل انت متأكد؟',
					text: "سوف يتم تسجيل خروجك من النظام",
					icon: 'warning',
					showCancelButton: true,
					confirmButtonColor: '#3085d6',
					cancelButtonColor: '#d33',
					cancelButtonText: 'إلغاء',
					confirmButtonText: 'نعم',
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





<script src="{{ asset('js/app.js') }}"></script>
</html>