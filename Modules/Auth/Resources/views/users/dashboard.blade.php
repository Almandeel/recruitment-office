@extends('layouts.master', [
    'title' => 'المستخدمين',
    'crumbs' => [
    ]
])

@section('content')
@permission('users-dashboard')
<div class="row">
    <div class="col-md-3 col-sm-6 col-12">
        <div class="info-box">
            <span class="info-box-icon bg-info"><i class="fas fa-users"></i></span>

            <div class="info-box-content">
            <span class="info-box-text">Users</span>
            <span class="info-box-number">{{ $usersCount }}</span>
            </div>
            <!-- /.info-box-content -->
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-12">
        <div class="info-box">
            <span class="info-box-icon bg-success"><i class="fas fa-list"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Roles</span>
                <span class="info-box-number">{{ $rolesCount }}</span>
            </div>
            <!-- /.info-box-content -->
        </div>
    </div>
</div>
@endpermission
@endsection
