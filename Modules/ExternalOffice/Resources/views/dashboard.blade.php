@extends('externaloffice::layouts.master')

@section('content')
<div class="row d-none">
    @permission('bills-read')
    <div class="col-md-3 col-sm-6 col-12">
        <div class="info-box">
            <span class="info-box-icon bg-success"><i class="fas fa-arrow-alt-circle-up"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Credits</span>
                <span class="info-box-number">{!! auth()->user()->office->credits(true) !!}</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-12">
        <div class="info-box">
            <span class="info-box-icon bg-warning"><i class="fas fa-arrow-alt-circle-down"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Debts</span>
                <span class="info-box-number">{!! auth()->user()->office->debts(true) !!}</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-12">
        <div class="info-box">
            <span class="info-box-icon bg-{{ auth()->user()->office->balance('side') == 'debt' ? 'warning' : 'success' }}"><i class="fas fa-money-check"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Balance</span>
                <span class="info-box-number">{!! auth()->user()->office->balance(true) !!}</span>
            </div>
        </div>
    </div>
    @endpermission
</div>
@endsection
