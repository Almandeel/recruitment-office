@extends('externaloffice::layouts.master', [
    'title' => 'Edit role',
    'datatable' => true, 
    'ltr' => true, 
    'crumbs' => [
        [route('office.roles.index'), 'Roles'],
        ['#', 'Edit role'],
    ]
])

@section('content')
<form action="{{ route('office.roles.update', $role) }}" method="POST">
        @csrf @method('PUT')
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
					<label>Name</label>
					<input type="text" class="form-control" name="name" value="{{ $role->name }}" placeholder="Name" required>
				</div>
				<table class="table table-bordered table-centered table-responsive">
					<thead>
						<tr>
							<th>#</th>
							<th class="text-right"><label><input type="checkbox" class="all-permissions" data-permission="all" /> <br>
									<span>Group</span></label></th>
							<th><label><input type="checkbox" class="all-permissions permission" data-permission="create" /> <br>
									<span>Create</span></label></th>
							<th><label><input type="checkbox" class="all-permissions permission" data-permission="read" /> <br>
									<span>Read</span></label></th>
							<th><label><input type="checkbox" class="all-permissions permission" data-permission="update" /> <br>
									<span>Edit</span></label></th>
							<th><label><input type="checkbox" class="all-permissions permission" data-permission="delete" /> <br>
									<span>Delete</span></label></th>
							{{-- <th><label><input type="checkbox" class="all-permissions permission" data-permission="print" /> <br>
									<span>Print</span></label></th>
							<th><label><input type="checkbox" class="all-permissions permission" data-permission="send" /> <br>
									<span>Send</span></label></th> --}}
						</tr>
					</thead>
					<tbody>
						@foreach ($permissions->groupBy('description') as $group)
						@php
						$create = $group->filter(function($permission){ return $permission->permission() == 'create'; })->first();
						$read = $group->filter(function($permission){ return $permission->permission() == 'read'; })->first();
						$update = $group->filter(function($permission){ return $permission->permission() == 'update'; })->first();
						$delete = $group->filter(function($permission){ return $permission->permission() == 'delete'; })->first();
						// $print = $group->filter(function($permission){ return $permission->permission() == 'print'; })->first();
						// $send = $group->filter(function($permission){ return $permission->permission() == 'send'; })->first();
						@endphp
						<tr>
							<th>{{ $loop->index + 1 }}</td>
							<td class="text-capitalze"><label>
									<input type="checkbox" class="permission-group permission"
										data-group="{{ $group->first()->group() }}" /> {{ $group->first()->group() }}
								</label></td>
							<td>@if($create) <input type="checkbox"
									class="permission permission-create permission-{{ $group->first()->group() }}" name="permissions[]"
									value="{{ $create->id }}" @if ($role->hasPermission($create->name)) checked @endif /> @else - @endif</td>
							<td>@if($read) <input type="checkbox"
									class="permission permission-read permission-{{ $group->first()->group() }}" name="permissions[]"
									value="{{ $read->id }}" @if ($role->hasPermission($read->name)) checked @endif /> @else - @endif</td>
							<td>@if($update) <input type="checkbox"
									class="permission permission-update permission-{{ $group->first()->group() }}" name="permissions[]"
									value="{{ $update->id }}" @if ($role->hasPermission($update->name)) checked @endif /> @else - @endif</td>
							<td>@if($delete) <input type="checkbox"
									class="permission permission-delete permission-{{ $group->first()->group() }}" name="permissions[]"
									value="{{ $delete->id }}" @if ($role->hasPermission($delete->name)) checked @endif /> @else - @endif</td>
							{{-- <td>@if($print) <input type="checkbox"
									class="permission permission-print permission-{{ $group->first()->group() }}" name="permissions[]"
									value="{{ $print->id }}" /> @else - @endif</td>
							<td>@if($send) <input type="checkbox"
									class="permission permission-send permission-{{ $group->first()->group() }}" name="permissions[]"
									value="{{ $send->id }}" /> @else - @endif</td> --}}
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
			<!-- /.card-body -->
			<div class="card-footer">
				<div class="row">
					<button type="submit" class="btn btn-primary btn-submit"><i class="fa fa-plus"></i> Save</button>
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
				if($(this).data('permission') == 'all'){
					$('.permission').prop('checked', $(this).prop('checked'))
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