@if ((request()->segment(1) == 'employee'))
	<li class="nav-item">
		<a href="{{ route('employees.index') }}" class="nav-link">
			<i class="fa fa-circle-o nav-icon"></i>
			<p>الموظفين</p>
		</a>
	</li>
	<li class="nav-item">
		<a href="{{ route('positions.index') }}" class="nav-link">
			<i class="fa fa-circle-o nav-icon"></i>
			<p>الوظائف</p>
		</a>
	</li>
	<li class="nav-item">
		<a href="{{ route('departments.index') }}" class="nav-link">
			<i class="fa fa-circle-o nav-icon"></i>
			<p>الاقسام</p>
		</a>
	</li>
	<li class="nav-item">
		<a href="{{ route('vacations.index') }}" class="nav-link">
			<i class="fa fa-circle-o nav-icon"></i>
			<p>الاجازات</p>
		</a>
	</li>
	<li class="nav-item">
		<a href="{{ route('users.index') }}" class="nav-link">
			<i class="fa fa-circle-o nav-icon"></i>
			<p>المستخدمين</p>
		</a>
	</li>
@endif

