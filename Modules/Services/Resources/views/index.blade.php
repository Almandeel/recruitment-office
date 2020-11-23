@extends('layouts.master', [
    'modals' => ['customer', 'complaint']
])

@include('employee::sidebar')

@push('head')
    <style>
        a {
            color : black !important
        }
        a:hover {
            color : #007bff !important
        }
    </style>
@endpush


@section('content')
@permission('services-dashboard')
<div class="row">
    @permission('customers-read')
    <div class="col-md-4 col-sm-6 col-12">
        <a href="{{ route('customers.index') }}">
            <div class="info-box">
                <span class="info-box-icon bg-info"><i class="fas fa-users"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">العملاء</span>
                    <span class="info-box-number">{{ count($customers) }}</span>
                </div>
            </div>
        </a>
    </div>
    @endpermission

    @permission('contracts-read')
    <div class="col-md-4 col-sm-6 col-12">
        <a href="{{ route('contracts.index') }}">
            <div class="info-box">
                <span class="info-box-icon bg-info"><i class="fa fa-file-word"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">العقود</span>
                    <span class="info-box-number">{{ count($contracts) }}</span>
                </div>
            </div>
        </a>
    </div>
    @endpermission

    @permission('cv-read')
    <div class="col-md-4 col-sm-6 col-12">
        <a href="{{ route('servicescvs.index') }}">
            <div class="info-box">
                <span class="info-box-icon bg-info"><i class="fa fa-file-word"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">السير الزاتية</span>
                    <span class="info-box-number">{{ count($cvs) }}</span>
                </div>
            </div>
        </a>
    </div>
    @endpermission

    @permission('complaints-read')
    <div class="col-md-4 col-sm-6 col-12">
        <a href="{{ route('complaints.index') }}">
            <div class="info-box">
                <span class="info-box-icon bg-info"><i class="fa fa-balance-scale"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">الشكاوي</span>
                    <span class="info-box-number">{{ count($complaints) }}</span>
                </div>
            </div>
        </a>
    </div>
    @endpermission

    @permission('marketers-read')
    <div class="col-md-4 col-sm-6 col-12">
        <a href="{{ route('servicesmarketers.index') }}">
            <div class="info-box">
                <span class="info-box-icon bg-info"><i class="fas fa-users"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">المسوقين</span>
                    <span class="info-box-number">{{ count($marketers) }}</span>
                </div>
            </div>
        </a>
    </div>
    @endpermission
</div>
@endpermission
@endsection


@push('foot')
   
@endpush
