@extends('layouts.master')

@push('head')
    
@endpush


@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title"> <i class="fa fa-file"></i> تقرير العقود</h3>
                </div>
                <div class="card-body">
                    <a href="{{ route('report.contracts') }}" class="btn btn-primary btn-sm btn-block"><i class="fa fa-eye"></i> عرض</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title"> <i class="fa fa-file"></i> تقرير ال cvs</h3>
                </div>
                <div class="card-body">
                    <a href="{{ route('report.cvs') }}" class="btn btn-primary btn-sm btn-block"><i class="fa fa-eye"></i> عرض</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title"> <i class="fa fa-file"></i> تقرير العملاء</h3>
                </div>
                <div class="card-body">
                    <a href="{{ route('report.customers') }}" class="btn btn-primary btn-sm btn-block"><i class="fa fa-eye"></i> عرض</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('foot')

@endpush