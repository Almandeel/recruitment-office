@extends('layouts.master', [
	'modals' => ['employee'], 
    'title' => 'إضافة مستخدم',
    'datatable' => true, 
    'crumbs' => [
        [route('users.index'), 'المستخدمين'],
        ['#', 'إضافة مستخدم'],
    ]
])
@section('content')
<section class="content">
	<form id="form" action="{{ route('users.store') }}" method="POST">
		@csrf
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
					<div class="col">
						<div class="form-group">
							<label>الإسم</label>
							<input type="text" class="form-control" name="name" value="{{ old('name') }}"
								placeholder="الإسم">
						</div>
					</div>
					<div class="col">
						<div class="form-group">
							<label>الموظف</label>
							<div class="input-group">
								<select class="form-control select2-single" name="employee_id">
									<option value="" selected disabled>الموظف</option>
									@foreach ($employees as $employee)
									<option value="{{ $employee->id }}">{{ $employee->name }}</option>
									@endforeach
								</select>
								@permission('employees-create')
								<div class="input-group-btn">
									<button type="button" class="btn btn-success employees showEmployeeModal"><i class="fa fa-plus"></i></button>
								</div>
								@endpermission
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col">
						<div class="form-group">
							<label>إسم المرور</label>
							<input type="text" class="form-control" name="username" value="{{ old('username') }}"
								placeholder="إسم المرور" required>
						</div>
					</div>
					<div class="col">
						<div class="form-group">
							<label>كلمة المرور</label>
							<input id="password" type="password" class="form-control" name="password"
								placeholder="كلمة المرور" required>
						</div>
					</div>
					<div class="col">
						<div class="form-group">
							<label>تأكيد كلمة المرور</label>
							<input type="password" class="form-control" name="password_confirmation" placeholder="تأكيد كلمة المرور"
								data-parsley-equalto="#password" required>
						</div>
					</div>
				</div>
			</div>
			<!-- /.card-body -->
			<div class="card-footer">
				<div class="row">
					<div class="col-md-6">
						<label>
							<input type="radio" name="next" value="back" checked="checked" />
							<span>حفظ و اضافة جديد</span>
						</label>
						<label>
							<input type="radio" name="next" value="list">
							<span>حفظ و عرض على القائمة</span>
						</label>
					</div>
					<div class="col-md-6">
						<button type="submit" class="btn btn-primary btn-submit"><i class="fa fa-plus"></i> إضافة</button>
					</div>
				</div>
			</div>
			<!-- /.card-footer -->
		</div>
		<div class="card card-outline card-primary collapsed-card">
			<div class="card-header">
				<h3 class="card-title">الادوار</h3>
		
				<div class="card-tools">
					<button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
					</button>
				</div>
				<!-- /.card-tools -->
			</div>
			<!-- /.card-header -->
			<div class="card-body">
				<div class="row">
					@foreach ($roles as $role)
					<div class="col-md-3">
						<div class="form-group">
							<label>
								{{ $role->name }}
								<input type="checkbox" class="flat-red" name="roles[]" value="{{ $role->id }}">
							</label>
						</div>
					</div>
					@endforeach
				</div>
			</div>
			<!-- /.card-body -->
			<div class="card-footer">
				<div class="row">
					<div class="col-md-6">
						<label>
							<input type="radio" name="next" value="back" checked="checked" />
							<span>حفظ و اضافة جديد</span>
						</label>
						<label>
							<input type="radio" name="next" value="list">
							<span>حفظ و عرض على القائمة</span>
						</label>
					</div>
					<div class="col-md-6">
						<button type="submit" class="btn btn-primary btn-submit"><i class="fa fa-plus"></i> إضافة</button>
					</div>
				</div>
			</div>
			<!-- /.card-footer -->
		</div>
		<div class="card card-outline card-primary collapsed-card">
			<div class="card-header">
				<h3 class="card-title">الصلاحيات</h3>
		
				<div class="card-tools">
					<button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i></button>
					<button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i></button>
				</div>
				<!-- /.card-tools -->
			</div>
			<!-- /.card-header -->
			<div class="card-body">
				@foreach ($modules as $name=>$per)
					<div id="accordion{{ $loop->index + 1 }}">
						<div class="card">
							<div class="card-header" id="headingOne">
								<h5 class="mb-0">
								<button type="button" class="btn btn-link" data-toggle="collapse" data-target="#collapse{{ $loop->index + 1 }}" aria-expanded="true" aria-controls="collapse{{ $loop->index + 1 }}">
									نظام  @lang('modules.' . $name)
								</button>
								</h5>
							</div>
							<div id="collapse{{ $loop->index + 1 }}" class="collapse " aria-labelledby="headingOne" data-parent="#accordion{{ $loop->index + 1 }}">
								<div class="card-body">
									<table class="table table-bordered table-centered table-responsive">
										<thead>
											<tr>
												<th>#</th>
												<th class="text-right"><label><input type="checkbox" class="all-permissions" data-permission="all-{{ $name }}"  data-module="{{ $name }}"/> <br>
														<span>المجموعة</span></label></th>
												<th><label><input type="checkbox" class="all-permissions permission-{{ $name }}" data-permission="create-{{ $name }}" data-module="{{ $name }}" /> <br>
														<span>انشاء</span></label></th>
												<th><label><input type="checkbox" class="all-permissions permission-{{ $name }}" data-permission="read-{{ $name }}" data-module="{{ $name }}" /> <br>
														<span>قراءة</span></label></th>
												<th><label><input type="checkbox" class="all-permissions permission-{{ $name }}" data-permission="update-{{ $name }}" data-module="{{ $name }}" /> <br>
														<span>تعديل</span></label></th>
												<th><label><input type="checkbox" class="all-permissions permission-{{ $name }}" data-permission="delete-{{ $name }}" data-module="{{ $name }}" /> <br>
														<span>حذف</span></label></th>
												<th><label><input type="checkbox" class="all-permissions permission-{{ $name }}" data-permission="print-{{ $name }}" data-module="{{ $name }}" /> <br>
														<span>طباعه</span></label></th>
												<th><label><input type="checkbox" class="all-permissions permission-{{ $name }}" data-permission="send-{{ $name }}" data-module="{{ $name }}" /> <br>
														<span>ارسال</span></label></th>
												<th><label><input type="checkbox" class="all-permissions permission-{{ $name }}" data-permission="dashboard-{{ $name }}" data-module="{{ $name }}" /> <br>
													<span>لوحة التحكم</span></label></th>
											</tr>
										</thead>
										<tbody>
											@foreach ($permissions->whereIn('description', $per)->groupBy('description') as $group)
											@php
											$create 	= $group->filter(function($permission){ return $permission->permission() == 'create'; })->first();
											$read 		= $group->filter(function($permission){ return $permission->permission() == 'read'; })->first();
											$update 	= $group->filter(function($permission){ return $permission->permission() == 'update'; })->first();
											$delete 	= $group->filter(function($permission){ return $permission->permission() == 'delete'; })->first();
											$print 		= $group->filter(function($permission){ return $permission->permission() == 'print'; })->first();
											$send 		= $group->filter(function($permission){ return $permission->permission() == 'send'; })->first();
											$dashboard	= $group->filter(function($permission){ return $permission->permission() == 'dashboard'; })->first();
											@endphp
					
					
											<tr>
												<th>{{ $loop->index + 1 }}</th>
												<td class="text-right"><label>
														<input type="checkbox" class="permission-group permission-{{ $name }}"
															data-group="{{ $group->first()->group() }}" /> {{ $group->first()->getGroup() }}
													</label></td>
												<td>@if($create) <input type="checkbox"
														class="permission-{{ $name }} permission-create-{{ $name }} permission-{{ $group->first()->group() }}" name="permissions[]"
														value="{{ $create->id }}" /> @else - @endif</td>
												<td>@if($read) <input type="checkbox"
														class="permission-{{ $name }} permission-read-{{ $name }} permission-{{ $group->first()->group() }}" name="permissions[]"
														value="{{ $read->id }}" /> @else - @endif</td>
												<td>@if($update) <input type="checkbox"
														class="permission-{{ $name }} permission-update-{{ $name }} permission-{{ $group->first()->group() }}" name="permissions[]"
														value="{{ $update->id }}" /> @else - @endif</td>
												<td>@if($delete) <input type="checkbox"
														class="permission-{{ $name }} permission-delete-{{ $name }} permission-{{ $group->first()->group() }}" name="permissions[]"
														value="{{ $delete->id }}" /> @else - @endif</td>
												<td>@if($print) <input type="checkbox"
														class="permission-{{ $name }} permission-print-{{ $name }} permission-{{ $group->first()->group() }}" name="permissions[]"
														value="{{ $print->id }}" /> @else - @endif</td>
												<td>@if($send) <input type="checkbox"
														class="permission-{{ $name }} permission-send-{{ $name }} permission-{{ $group->first()->group() }}" name="permissions[]"
														value="{{ $send->id }}" /> @else - @endif</td>
												<td>@if($dashboard) <input type="checkbox"
													class="permission-{{ $name }} permission-dashboard-{{ $name }} permission-{{ $group->first()->group() }}" name="permissions[]"
													value="{{ $dashboard->id }}" /> @else - @endif</td>
											</tr>
											@endforeach
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				@endforeach
				{{-- <table class="datatables table table-bordered table-centered table-responsive">
					<thead>
						<tr>
							<th>#</th>
							<th class="text-right"><label><input type="checkbox" class="all-permissions" data-permission="all"/> <br> <span>المجموعة</span></label></th>
							<th><label><input type="checkbox" class="all-permissions permission" data-permission="create"/> <br> <span>انشاء</span></label></th>
							<th><label><input type="checkbox" class="all-permissions permission" data-permission="read"/> <br> <span>قراءة</span></label></th>
							<th><label><input type="checkbox" class="all-permissions permission" data-permission="update"/> <br> <span>تعديل</span></label></th>
							<th><label><input type="checkbox" class="all-permissions permission" data-permission="delete"/> <br> <span>حذف</span></label></th>
							<th><label><input type="checkbox" class="all-permissions permission" data-permission="print"/> <br> <span>طباعه</span></label></th>
							<th><label><input type="checkbox" class="all-permissions permission" data-permission="send"/> <br> <span>ارسال</span></label></th>
						</tr>
					</thead>
					<tbody>
						@foreach ($permissions->groupBy('description') as $group)
							@php
								$create = $group->filter(function($permission){ return $permission->permission() == 'create'; })->first();
								$read = $group->filter(function($permission){ return $permission->permission() == 'read'; })->first();
								$update = $group->filter(function($permission){ return $permission->permission() == 'update'; })->first();
								$delete = $group->filter(function($permission){ return $permission->permission() == 'delete'; })->first();
								$print = $group->filter(function($permission){ return $permission->permission() == 'print'; })->first();
								$send = $group->filter(function($permission){ return $permission->permission() == 'send'; })->first();
							@endphp
							<tr>
								<th>{{ $loop->index + 1 }}</td>
								<td class="text-right"><label>
									<input type="checkbox" class="permission-group permission" data-group="{{ $group->first()->group() }}" /> {{ $group->first()->getGroup() }}
								</label></td>
								<td>@if($create) <input type="checkbox" class="permission permission-create permission-{{ $group->first()->group() }}" name="permissions[]" value="{{ $create->id }}" /> @else - @endif</td>
								<td>@if($read) <input type="checkbox" class="permission permission-read permission-{{ $group->first()->group() }}" name="permissions[]" value="{{ $read->id }}" /> @else - @endif</td>
								<td>@if($update) <input type="checkbox" class="permission permission-update permission-{{ $group->first()->group() }}" name="permissions[]" value="{{ $update->id }}" /> @else - @endif</td>
								<td>@if($delete) <input type="checkbox" class="permission permission-delete permission-{{ $group->first()->group() }}" name="permissions[]" value="{{ $delete->id }}" /> @else - @endif</td>
								<td>@if($print) <input type="checkbox" class="permission permission-print permission-{{ $group->first()->group() }}" name="permissions[]" value="{{ $print->id }}" /> @else - @endif</td>
								<td>@if($send) <input type="checkbox" class="permission permission-send permission-{{ $group->first()->group() }}" name="permissions[]" value="{{ $send->id }}" /> @else - @endif</td>
							</tr>
						@endforeach
					</tbody>
				</table> --}}
			</div>
			<!-- /.card-body -->
			<div class="card-footer">
				<div class="row">
					<div class="col-md-6">
						<label>
							<input type="radio" name="next" value="back" checked="checked" />
							<span>حفظ و اضافة جديد</span>
						</label>
						<label>
							<input type="radio" name="next" value="list">
							<span>حفظ و عرض على القائمة</span>
						</label>
					</div>
					<div class="col-md-6">
						<button type="submit" class="btn btn-primary btn-submit"><i class="fa fa-plus"></i> إضافة</button>
					</div>
				</div>
			</div>
			<!-- /.card-footer -->
		</div>
	</form>
</section>
@endsection
@push('foot')
	<script>
		$(function(){
			$('table.datatables').dataTable({
				'paging' : false,
				{{--  "iDisplayLength": -1,
				"aaSorting": [[ 0, "asc" ]],  --}}
				"columnDefs": [
					{ "orderable": false, "targets": 1 },
					{ "orderable": false, "targets": 2 },
					{ "orderable": false, "targets": 3 },
					{ "orderable": false, "targets": 4 },
					{ "orderable": false, "targets": 5 },
					{ "orderable": false, "targets": 6 },
					{ "orderable": false, "targets": 7 }
				]
			});

			$('.all-permissions').change(function(){
				if($(this).data('permission') == 'all-' + $(this).data('module')){
					$('.permission-' + $(this).data('module')).prop('checked', $(this).prop('checked'))
				}else{
					$('.permission-' + $(this).data('permission')).prop('checked', $(this).prop('checked'))
				}
			})
			$('.permission-group').change(function(){
				$('.permission-' + $(this).data('group')).prop('checked', $(this).prop('checked'))
			})
		})
	</script>
@endpush