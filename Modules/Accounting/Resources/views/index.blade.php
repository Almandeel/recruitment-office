@extends('accounting::layouts.master',[
    'title' => 'لوحة التحكم',
    'crumbs' => [],
])

@push('content')
@permission('accounts-dashboard')
    <div class="row">
        <div class="col-md-4 col-sm-6 col-12">
            <div class="info-box">
                <span class="info-box-icon bg-info"><i class="fa fa-book"></i></span>
    
                <div class="info-box-content">
                    <span class="info-box-text">القيود</span>
                    <span class="info-box-number">{{ $entriesCount }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-6 col-12">
            <div class="info-box">
                <span class="info-box-icon bg-success"><i class="fa fa-file"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">السندات</span>
                    <span class="info-box-number">{{ $vouchersCount }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-6 col-12">
            <div class="info-box">
                <span class="info-box-icon bg-warning"><i class="fa fa-money-check-alt"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">المنصرفات</span>
                    <span class="info-box-number">{{ $expensesCount }}</span>
                </div>
            </div>
        </div>
    </div>
@endpermission
@endpush
