@extends('externaloffice::layouts.master', [
    'title' => 'Edit user',
    'datatable' => true, 
    'ltr' => true, 
    'crumbs' => [
        [route('office.users.index'), 'Users list'],
        ['#', 'Edit user'],
    ]
])
@section('content')
<section class="content">
	<form id="form" action="{{ route('office.users.update', $user) }}" method="POST">
		@csrf
		@method('PUT')
		<div class="card card-outline card-primary">
			<div class="card-header" data-card-widget="collapse">
				<h3 class="card-title">Basic information</h3>
		
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
							<label>Name</label>
							<input type="text" class="form-control" name="name" value="{{ $user->name }}" placeholder="Name" required>
						</div>
					</div>
					<div class="col">
						<div class="form-group">
							<label>Phone</label>
							<input type="number" class="form-control" name="phone" value="{{ $user->phone }}" placeholder="Phone" required>
						</div>
					</div>
					<div class="col">
						<div class="form-group">
							<label for="status">Status</label>
							<select class="custom-select" name="status" id="status" required>
								<option value="1" @if($user->status == 1) selected @endif>Active</option>
								<option value="0" @if($user->status == 0) selected @endif>Unactive</option>
							</select>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col">
						<div class="form-group">
							<label>Username</label>
							<input type="text" class="form-control" name="username" value="{{ $user->username }}" placeholder="Username" required>
						</div>
					</div>
					<div class="col">
						<div class="form-group">
							<label>Password</label>
							<input id="password" type="password" class="form-control" name="password" placeholder="Password">
						</div>
					</div>
					<div class="col">
						<div class="form-group">
							<label>Password confirmation</label>
							<input type="password" class="form-control" name="password_confirmation" placeholder="Password confirmation" data-parsley-equalto="#password">
						</div>
					</div>
				</div>
			</div>
			<!-- /.card-body -->
			<div class="card-footer">
				<div class="row">
					<div class="col-md-6">
						<button type="submit" class="btn btn-primary btn-submit"><i class="fa fa-plus"></i> Save</button>
					</div>
				</div>
			</div>
			<!-- /.card-footer -->
		</div>
		<div class="card card-outline card-primary collapsed-card">
			<div class="card-header" data-card-widget="collapse">
				<h3 class="card-title">Roles</h3>
		
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
								<input type="checkbox" class="flat-red" name="roles[]" value="{{ $role->id }}"  @if ($user->hasRole($role->name)) checked @endif>
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
						<button type="submit" class="btn btn-primary btn-submit"><i class="fa fa-plus"></i> Save</button>
					</div>
				</div>
			</div>
			<!-- /.card-footer -->
		</div>
	</form>
</section>
@endsection
