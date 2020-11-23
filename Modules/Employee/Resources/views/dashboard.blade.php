@extends('layouts.master', [
    'title' => 'الموارد البشرية',
    'modals' => ['warehouse'],
    'crumbs' => [
    ]
])

@section('content')
@permission('employees-dashboard')
<div class="row">
    <div class="col-md-4 col-sm-6 col-12">
        <div class="info-box">
            <span class="info-box-icon bg-info"><i class="fas fa-users"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">الموظفين</span>
                <span class="info-box-number">{{ $employeesCount }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-6 col-12">
        <div class="info-box">
            <span class="info-box-icon bg-success"><i class="fas fa-list"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">الوظائف</span>
                <span class="info-box-number">{{ $positionsCount }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-6 col-12">
        <div class="info-box">
            <span class="info-box-icon bg-warning"><i class="fas fa-th-large"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">الأقسام</span>
                <span class="info-box-number">{{ $departmentsCount }}</span>
            </div>
        </div>
    </div>
</div>
@endpermission
@endsection