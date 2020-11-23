<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
	<!-- Left navbar links -->
	<ul class="navbar-nav">

		<li class="nav-item d-none d-sm-inline-block">
			<a href="{{ url('') }}" class="nav-link">@lang('global.home')</a>
		</li>
		<li class="nav-item dropdown">
			<a id="accountingSubMenu" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
				class="nav-link dropdown-toggle">
				<i class="fa fa-magic"></i>
				<span>@lang('navbar.speed_links')</span>
			</a>
			@if(auth()->guard('office')->check())
			<ul aria-labelledby="accountingSubMenu" class="dropdown-menu">
				<li><a href="{{ route('cvs.index') }}" class="dropdown-item">
					<span>Cvs</span>
				</a></li>

				<li><a href="{{ route('office.users.index') }}" class="dropdown-item">
					<span>Users</span>
				</a></li>

				<li><a href="{{ route('office.roles.index') }}" class="dropdown-item">
					<span>Roles</span>
				</a></li>

				<li><a href="{{ route('advances.index') }}" class="dropdown-item">
					<span>Advances</span>
				</a></li>

				<li><a href="{{ route('cvs.bills.index') }}" class="dropdown-item">
					<span>Bills</span>
				</a></li>
			</ul>
			@else
			<ul aria-labelledby="accountingSubMenu" class="dropdown-menu">
				@if((request()->segment(1) == 'accounting'))
					@permission('entries-create')
					<li><a href="{{ route('entries.create') }}" class="dropdown-item">
						<i class="fa fa-plus"></i>
						<span>اضافة قيد</span>
					</a></li>
					@endpermission
					<li class="dropdown-divider"></li>
				@elseif((request()->segment(1) == 'hr'))
					{{--  @permission('custodies-create')
					<li><a href="{{ route('custodies.create') }}" class="dropdown-item">
						<i class="fa fa-plus"></i>
						<span>اضافة عهدة</span>
					</a></li>
					@endpermission
					@permission('transactions-create')
					<li><a href="{{ route('transactions.create') }}" class="dropdown-item">
						<i class="fa fa-plus"></i>
						<span>اضافة معاملة</span>
					</a></li>
					@endpermission
					@permission('salaries-create')
					<li><a href="{{ route('salaries.create') }}" class="dropdown-item">
						<i class="fa fa-plus"></i>
						<span>اضافة مرتب</span>
					</a></li>
					@endpermission  --}}
					{{--  <li class="dropdown-divider"></li>  --}}
				@endif
				@permission(['employees-read', 'departments-read', 'positions-read', 'vacations-read', 'attendance-read', 'transactions-read', 'salaries-read'])
					<li><a href="{{ route('hr.dashboard') }}" class="dropdown-item">
						<span>نظام الموارد البشرية</span>
					</a></li>
				@endpermission

				@permission('mail-read')
					<li><a href="{{ route('mail.index') }}" class="dropdown-item">
						<span>نظام المراسلات</span>
					</a></li>
				@endpermission

				@permission('users-read')
					<li><a href="{{ route('users.dashboard') }}" class="dropdown-item">
						<span>نظام المستخدمين</span>
					</a></li>
				@endpermission

				@permission('accounts-read')
					<li><a href="{{ route('accounting.dashboard') }}" class="dropdown-item">
						<span>نظام الحسابات</span>
					</a></li>
				@endpermission

				@permission('warehouses-read')
					<li><a href="{{ route('warehouses.dashboard') }}" class="dropdown-item">
						<span>نظام الإيواء</span>
					</a></li>
				@endpermission

				@permission(['services-read', 'contracts-read', 'cv-read', 'customers-read', 'complaints-read', 'marketers-read'])
				<li><a href="{{ route('services.dashboard') }}" class="dropdown-item">
						<span>نظام خدمات العملاء</span>
					</a></li>
				@endpermission

				@permission(['offices-read', 'professions-read', 'countries-read', 'advances-read', 'bills-read', 'returns-read'])
					<li><a href="{{ route('offices.index') }}" class="dropdown-item">
						<span>نظام المكاتب الخارجية</span>
					</a></li>
				@endpermission

			</ul>
			@endif
		</li>
		@if((request()->segment(1) == 'accounting'))
		<li class="nav-item dropdown">
			<a id="transactionsSubMenu" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
				class="nav-link dropdown-toggle">
				<i class="fa fa-money"></i>
				<span>المعاملات المالية</span>
			</a>
			<ul aria-labelledby="transactionsSubMenu" class="dropdown-menu">
				@permission('vouchers-create')
				<li><a href="{{ route('vouchers.create') }}" class="dropdown-item">
					<i class="fa fa-plus"></i>
					<span>اضافة سند</span>
				</a></li>
				@endpermission
				@permission('expenses-create')
				<li><a href="{{ route('expenses.create') }}" class="dropdown-item">
					<i class="fa fa-plus"></i>
					<span>اضافة منصرف</span>
				</a></li>
				@endpermission
				@permission('transfers-create')
				<li><a href="{{ route('transfers.create') }}" class="dropdown-item">
					<i class="fa fa-plus"></i>
					<span>اضافة تحويلة</span>
				</a></li>
				@endpermission
				@permission('custodies-create')
				<li><a href="{{ route('accounting.custodies.create') }}" class="dropdown-item">
					<i class="fa fa-plus"></i>
					<span>اضافة عهدة</span>
				</a></li>
				@endpermission
				@permission('salaries-create')
				<li><a href="{{ route('accounting.salaries.create') }}" class="dropdown-item">
					<i class="fa fa-plus"></i>
					<span>اضافة مرتب</span>
				</a></li>
				@endpermission
				@permission('transactions-create')
				<li><a href="{{ route('accounting.transactions.create') }}" class="dropdown-item">
					<i class="fa fa-plus"></i>
					<span>اضافة معاملة لموظف</span>
				</a></li>
				@endpermission
				<li class="dropdown-divider"></li>
				@permission('vouchers-read')
				<li><a href="{{ route('vouchers.index') }}" class="dropdown-item">
					<i class="fa fa-list"></i>
					<span>السندات</span>
				</a></li>
				@endpermission
				@permission('expenses-read')
				<li><a href="{{ route('expenses.index') }}" class="dropdown-item">
					<i class="fa fa-list"></i>
					<span>المنصرفات</span>
				</a></li>
				@endpermission
				@permission('transfers-read')
				<li><a href="{{ route('transfers.index') }}" class="dropdown-item">
					<i class="fa fa-list"></i>
					<span>التحويلات</span>
				</a></li>
				@endpermission
				@permission('transactions-read')
				<li><a href="{{ route('accounting.custodies.index') }}" class="dropdown-item">
					<i class="fa fa-list"></i>
					<span>العهد</span>
				</a></li>
				@endpermission
				@permission('transactions-read')
				<li><a href="{{ route('accounting.transactions.index') }}" class="dropdown-item">
					<i class="fa fa-list"></i>
					<span>معاملات الموظفين</span>
				</a></li>
				@endpermission
				@permission('salaries-read')
				<li><a href="{{ route('accounting.salaries.index') }}" class="dropdown-item">
					<i class="fa fa-list"></i>
					<span>مرتبات الموظفين</span>
				</a></li>
				@endpermission
			</ul>
		</li>
		@endif
		@stack('navbar-links')
	</ul>

	<!-- Right navbar links -->
	<ul class="navbar-nav ml-auto">
		@if(auth()->guard('office')->check() != true)
			<div id="app">
                <notification-component></notification-component>
            </div>
            <li class="navbar-link" id="app">
                <flights-notification-component></flights-notification-component>
            </li>
			<li class="nav-item dropdown">
				<a id="mainSubMenu" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">
					<i class="fa fa-th"></i>
					{{--  <span>روابط عامة</span>  --}}
				</a>
				<ul aria-labelledby="transactionsSubMenu" class="dropdown-menu">
					 @if (auth()->user()->employee_id)
						<li><a href="{{ route('employees.custodies', auth()->user()->employee_id) }}" class="dropdown-item">
							<i class="fa fa-money-check"></i>
							<span>العهد</span>
						</a></li>
					 @endif
					<li><a href="{{ route('tutorials.index') }}" class="dropdown-item">
						<i class="fa fa-question-circle"></i>
						<span>مركز المعرفة</span>
					</a></li>
					<li><a href="{{ route('home.lines') }}" class="dropdown-item">
						<i class="fa fa-phone-square"></i>
						<span>دليل الهاتف</span>
					</a></li>
					<li><a href="{{ route('users.profile') }}" class="dropdown-item">
						<i class="fa fa-user"></i>
						<span> الملف الشخصي </span>
					</a></li>
					@permission('tasks-read')
					<li><a href="{{ route('tasks.index') }}" class="dropdown-item">
						<i class="fa fa-check-square"></i>
						<span> المهام </span>
					</a></li>
					@endpermission
					@permission('suggestions-read')
					<li><a href="{{ route('suggestions.index') }}" class="dropdown-item">
						<i class="fa fa-exclamation-circle"></i>
						<span> الاقتراحات </span>
					</a></li>
					@endpermission
					{{-- @permission('logs-read')
					<li><a href="{{ route('logs.index') }}" class="dropdown-item">
						<i class="fa fa-list"></i>
						<span> السجلات </span>
					</a></li>
					@endpermission --}}
					@permission('backups-read')
					{{--  <li><a href="{{ route('backups.index') }}" class="dropdown-item">
						<i class="fa fa-recycle"></i>
						<span> النسخ الاحتياطي </span>
					</a></li>  --}}
					@endpermission
				</ul>
			</li>
			{{--  <li class="navbar-link"><a href="{{ route('users.profile') }}" class="nav-link">
				<i class="fa fa-user"></i>
			</a></li>  --}}
			{{--  @permission('users-read')
			<li class="navbar-link"><a href="{{ route('home') }}" class="nav-link">
				<i class="fa fa-bell"></i>
			</a></li>
			@endpermission  --}}
			{{--  @permission('letters-read')
			<li class="navbar-link"><a href="{{ route('mail.index') }}" class="nav-link">
				<i class="fa fa-envelope"></i>
			</a></li>
			@endpermission  --}}
		@endif
		<li class="navbar-link"><a href="#" class="nav-link logout">
			<i class="fa fa-power-off"></i>
		</a></li>

	</ul>
</nav>
<!-- /.navbar -->
