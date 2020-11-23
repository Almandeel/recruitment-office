@extends('layouts.master', [
    'title' => 'الإيواء',
    'modals' => ['warehouse'],
    'crumbs' => [
    ]
])

@section('content')
@permission('warehouses-dashboard')
<div class="row">
    <div class="col-md-3 col-sm-6 col-12">
        <div class="info-box">
            <span class="info-box-icon bg-info"><i class="fas fa-home"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">الإيواء</span>
                <span class="info-box-number">{{ $warehousesCount }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-12">
        <div class="info-box">
            <span class="info-box-icon bg-success"><i class="fas fa-list"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">النزلاء</span>
                <span class="info-box-number">{{ $warehouseCvCount }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-12">
        <div class="info-box">
            <span class="info-box-icon bg-warning"><i class="fas fa-users"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">المشرفين</span>
                <span class="info-box-number">{{ $warehouseUserCount }}</span>
            </div>
        </div>
    </div>
</div>
@endpermission
@endsection