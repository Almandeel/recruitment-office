@extends('layouts.master')

@push('head')
    
@endpush


@section('content')
    <section class="container">
        <div class="card">
            <div class="card-header">
                <form action="{{ route('report.employees') }}" method="get">
                    @csrf 
                    <div class="row">
                        <div class="col-md-4">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <label class="input-group-text" for="inputGroupSelect01">بحث بواسطة : </label>
                                </div>
                                <select onchange="searchContent(this.value)" name="type" class="custom-select" id="inputGroupSelect01">
                                    <option value="*">الكل</option>
                                    <option value="department">الاقسام</option>
                                    <option value="position">الوظائف</option>
                                    <option value="start">تاريخ التعيين</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div id="search-content">

                            </div>
                        </div>
                        
                        <div class="col-md-2">
                            <button class="btn btn-primary btn-sm btn-block"><i class="fa fa-search"></i> بحث</button>
                        </div>
                    </div>

                </form>
            </div>
            <div class="card-body">
                <table id="datatable" class="datatable table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>الاسم</th>
                            <th>المرتب</th>
                            <th>الوظيفة</th>
                            <th>القسم</th>
                            <th>تاريخ التعيين</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($employees as $index=>$employee)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $employee->name }}</td>
                                <td>{{ $employee->salary }}</td>
                                <td>{{ $employee->position->title }}</td>
                                <td>{{ $employee->department->title }}</td>
                                <td>{{ $employee->started_at }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection

@push('foot')


<script>
    function searchContent(content) {
        $('#search-content').html('')

        if(content == 'department') {
            $('#search-content').append(`
                <div id="department">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="department">اسم القسم</span>
                        </div>
                        <input type="text" name="department" class="form-control" placeholder="اسم القسم" aria-label="department" aria-describedby="department">
                    </div>
                </div>
            `)
        }
        
        if(content == 'position') {
            $('#search-content').append(`
                <div id="position">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="position">اسم الوظيفة</span>
                        </div>
                        <input type="text" name="position" class="form-control" placeholder="اسم الوظيفة" aria-label="position" aria-describedby="position">
                    </div>
                </div>
            `)
        }

        if(content == 'start') {
            $('#search-content').append(`
                <div id="start">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="from">من</span>
                                </div>
                                <input type="date" name="from" class="form-control" aria-label="from" aria-describedby="from">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="to">الى</span>
                                </div>
                                <input type="date" name="to" class="form-control" aria-label="to" aria-describedby="to">
                            </div>
                        </div>
                    </div>
                </div>
            `)
        }
    }
</script>

@endpush