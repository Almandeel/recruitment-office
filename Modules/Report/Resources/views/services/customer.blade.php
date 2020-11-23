@extends('layouts.master')

@push('head')
    
@endpush


@section('content')
    <section class="container">
        <div class="card">
            <div class="card-header">
                
            </div>
            <div class="card-body">
                <table class="datatable table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>الاسم</th>
                            <th>العنوان</th>
                            <th>رقم الهاتف</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($customers as $index=>$customer)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $customer->name }}</td>
                                    <td>{{ $customer->address }}</td>
                                    <td>{{ $customer->phones }}</td>
                                </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection

@push('foot')


@endpush