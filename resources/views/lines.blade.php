@extends('layouts.master')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fa fa-phone-square"></i>
                <span>دليل الهاتف</span>
            </h3>
        </div>
        <div class="card-body">
            <table class="table table-striped table-condensed datatable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>القسم</th>
                        <th>الاسم</th>
                        <th>رقم الهاتف الداخلي</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($telephoneBook as $telephone)
                    <tr>
                        <td>{{ $loop->index + 1 }}</td>
                        <td>{{ $telephone->department->title}}</td>
                        <td>{{ $telephone->name }}</td>
                        <td>{{ $telephone->line }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
