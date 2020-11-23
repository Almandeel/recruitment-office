@extends('layouts.master', [
    'title' => 'تعديل دور',
    'datatable' => true, 
    'crumbs' => [
		[route('roles.index'), 'الادوار'],
        [route('roles.show', $role), $role->name],
        ['#', 'تعديل دور'],
    ]
])

@section('content')
<form action="{{ route('roles.update', $role) }}" method="POST">
		@csrf
		@method('PUT')
        <div class="card card-outline card-primary collapsed">
			<div class="card-header">
		
				<div class="card-tools">
					<button type="button" class="btn btn-tool" data-card-widget="maximize"><i
							class="fas fa-expand"></i></button>
					<button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
				</div>
				<!-- /.card-tools -->
			</div>
			<!-- /.card-header -->
			<div class="card-body">
				<div class="form-group">
					<label>الاسم</label>
					<input type="text" class="form-control" name="name" value="{{ old('name') ? old('name') : $role->name }}">
				</div>

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
											$dashboard  = $group->filter(function($permission){ return $permission->permission() == 'dashboard'; })->first();
											@endphp
					
					
											<tr>
												<th>{{ $loop->index + 1 }}</th>
												<td class="text-right"><label>
														<input type="checkbox" class="permission-group permission-{{ $name }}"
															data-group="{{ $group->first()->group() }}" /> {{ $group->first()->getGroup() }}
													</label></td>
												<td>@if($create) <input type="checkbox"
														class="permission-{{ $name }} permission-create-{{ $name }} permission-{{ $group->first()->group() }}" name="permissions[]"
														{{ $role->hasPermission($create->name) ? 'checked' : '' }} value="{{ $create->id }}" /> @else - @endif</td>
												<td>@if($read) <input type="checkbox"
														class="permission-{{ $name }} permission-read-{{ $name }} permission-{{ $group->first()->group() }}" name="permissions[]"
														{{ $role->hasPermission($read->name) ? 'checked' : '' }} value="{{ $read->id }}" /> @else - @endif</td>
												<td>@if($update) <input type="checkbox"
														class="permission-{{ $name }} permission-update-{{ $name }} permission-{{ $group->first()->group() }}" name="permissions[]"
														{{ $role->hasPermission($update->name) ? 'checked' : '' }} value="{{ $update->id }}" /> @else - @endif</td>
												<td>@if($delete) <input type="checkbox"
														class="permission-{{ $name }} permission-delete-{{ $name }} permission-{{ $group->first()->group() }}" name="permissions[]"
														{{ $role->hasPermission($delete->name) ? 'checked' : '' }} value="{{ $delete->id }}" /> @else - @endif</td>
												<td>@if($print) <input type="checkbox"
														class="permission-{{ $name }} permission-print-{{ $name }} permission-{{ $group->first()->group() }}" name="permissions[]"
														{{ $role->hasPermission($print->name) ? 'checked' : '' }} value="{{ $print->id }}" /> @else - @endif</td>
												<td>@if($send) <input type="checkbox"
														class="permission-{{ $name }} permission-send-{{ $name }} permission-{{ $group->first()->group() }}" name="permissions[]"
														{{ $role->hasPermission($send->name) ? 'checked' : '' }} value="{{ $send->id }}" /> @else - @endif</td>
												<td>@if($dashboard) <input type="checkbox"
													class="permission-{{ $name }} permission-dashboard-{{ $name }} permission-{{ $group->first()->group() }}" name="permissions[]"
													{{ $role->hasPermission($dashboard->name) ? 'checked' : '' }} value="{{ $dashboard->id }}" /> @else - @endif</td>
											</tr>
											@endforeach
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				@endforeach

				{{-- <table class="table table-bordered table-centered table-responsive">
					<thead>
						<tr>
							<th>#</th>
							<th class="text-right"><label><input type="checkbox" class="all-permissions" data-permission="all" /> <br>
									<span>المجموعة</span></label></th>
							<th><label><input type="checkbox" class="all-permissions permission" data-permission="create" /> <br>
									<span>انشاء</span></label></th>
							<th><label><input type="checkbox" class="all-permissions permission" data-permission="read" /> <br>
									<span>قراءة</span></label></th>
							<th><label><input type="checkbox" class="all-permissions permission" data-permission="update" /> <br>
									<span>تعديل</span></label></th>
							<th><label><input type="checkbox" class="all-permissions permission" data-permission="delete" /> <br>
									<span>حذف</span></label></th>
							<th><label><input type="checkbox" class="all-permissions permission" data-permission="print" /> <br>
									<span>طباعه</span></label></th>
							<th><label><input type="checkbox" class="all-permissions permission" data-permission="send" /> <br>
									<span>ارسال</span></label></th>
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
									<input type="checkbox" class="permission-group permission"
										data-group="{{ $group->first()->group() }}" /> {{ $group->first()->getGroup() }}
								</label></td>
							<td>@if($create) <input type="checkbox"
									class="permission permission-create permission-{{ $group->first()->group() }}" name="permissions[]"
									{{ $role->hasPermission($create->name) ? 'checked' : '' }} value="{{ $create->id }}" /> @else -
								@endif</td>
							<td>@if($read) <input type="checkbox"
									class="permission permission-read permission-{{ $group->first()->group() }}" name="permissions[]"
									{{ $role->hasPermission($read->name) ? 'checked' : '' }} value="{{ $read->id }}" /> @else - @endif
							</td>
							<td>@if($update) <input type="checkbox"
									class="permission permission-update permission-{{ $group->first()->group() }}" name="permissions[]"
									{{ $role->hasPermission($update->name) ? 'checked' : '' }} value="{{ $update->id }}" /> @else -
								@endif</td>
							<td>@if($delete) <input type="checkbox"
									class="permission permission-delete permission-{{ $group->first()->group() }}" name="permissions[]"
									{{ $role->hasPermission($delete->name) ? 'checked' : '' }} value="{{ $delete->id }}" /> @else -
								@endif</td>
							<td>@if($print) <input type="checkbox"
									class="permission permission-print permission-{{ $group->first()->group() }}" name="permissions[]"
									{{ $role->hasPermission($print->name) ? 'checked' : '' }} value="{{ $print->id }}" /> @else - @endif
							</td>
							<td>@if($send) <input type="checkbox"
									class="permission permission-send permission-{{ $group->first()->group() }}" name="permissions[]"
									{{ $role->hasPermission($send->name) ? 'checked' : '' }} value="{{ $send->id }}" /> @else - @endif
							</td>
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
@endsection
@push('foot')
<script>
	$(function(){
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