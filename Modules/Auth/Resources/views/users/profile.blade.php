@extends('layouts.master', [
	'modals' => ['vacation'], 
    'title' => 'الملف الشخصي',
    'crumbs' => [
        [url('/'), 'الملف الشخصي'],
    ]
])
@section('content')
<section class="content">
	<form id="form" action="{{ route('users.profile.update') }}" method="POST">
        @csrf
        @method('PUT')
		<div class="card card-outline card-primary">
			<div class="card-header">
				<h3 class="card-title">البيانات الاساسية</h3>
				<div class="card-tools">
					<button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
				</div>
				<!-- /.card-tools -->
			</div>
			<!-- /.card-header -->
			<div class="card-body">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label>إسم المرور</label>
							<input type="text" class="form-control" name="username" value="{{ auth()->user()->username }}"
								placeholder="إسم المرور" required>
						</div>
                    </div>
                    <div class="col-md-6">
						<div class="form-group">
							<label>كلمة المرور القديمة</label>
							<input type="password" class="form-control" name="old_password"
								placeholder="كلمة المرور القديمة" required>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>كلمة المرور الجديدة</label>
							<input id="password" type="password" class="form-control" name="password"
								placeholder="كلمة المرور الجديدة" required>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>تأكيد كلمة المرور الجديدة</label>
							<input type="password" class="form-control" name="password_confirmation" placeholder="تأكيد كلمة المرور الجديدة"
								data-parsley-equalto="#password" required>
						</div>
					</div>
					<button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-save"></i> حفظ</button>
				</div>
			</div>
		</div>
	</form>

	@if(auth()->user()->employee_id)
		<div class="card card-outline card-primary">
			<div class="card-header">
				<h3 class="card-title">الاجازات</h3>
				<div class="card-tools">
					<button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
					<button class="btn btn-primary btn-sm vacation" data-employee-id="{{ auth()->user()->employee_id }}" data-toggle="modal" data-target="#vacationModal"><i class="fa fa-plus"></i> طلب اجازة</button>
				</div>
			</div>
			<!-- /.card-header -->
			<div class="card-body">
				<table id="datatable" class="datatable table table-bordered table-striped">
					<thead>
						<tr>
							<th>#</th>
							<th> الاجازة</th>
							<th>النوع</th>
							<th>بداية الاجازة</th>
							<th>نهاية الاجازة</th>
							<th>الحالة</th>
						</tr>
					</thead>
					<tbody>
						@foreach (auth()->user()->employee->vacations as $index=>$vacation)
							<tr>
								<td>{{ $index + 1 }}</td>
								<td>{{ $vacation->title }}</td>
								<td>{{ $vacation->payed ? 'مدفوعة' : 'غير مدفوعة' }}</td>
								<td>{{ \Carbon\Carbon::createFromFormat('Y-m-d', $vacation->started_at)->format('Y-m-d')  }}</td>
								<td>{{ \Carbon\Carbon::createFromFormat('Y-m-d', $vacation->ended_at)->format('Y-m-d')  }}</td>
								<td>{{ $vacation->accepted ? 'تمت الموافقة' : 'لم تتم الموافقة'  }}</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	@endif
</section>
@endsection
