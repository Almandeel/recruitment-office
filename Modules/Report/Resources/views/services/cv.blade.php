@extends('layouts.master')

@push('head')
    
@endpush


@section('content')
    <section class="container">
        <div class="card">
            <div class="card-header">
                <form action="{{ route('report.cvs') }}" method="get">
                    @csrf 
                    <div class="row">
                        <div class="col-md-4">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <label class="input-group-text" for="inputGroupSelect01">بحث بواسطة : </label>
                                </div>
                                <select onchange="searchContent(this.value)" name="type" class="custom-select" id="inputGroupSelect01">
                                    <option value="*">الكل</option>
                                    <option value="profession">مهنة</option>
                                    <option value="country">دولة</option>
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
                <table class="table table-bordered table-striped datatable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>العمال \ العاملة</th>
                            <th>النوع</th>
                            <th>تاريخ الميلاد</th>
                            <th>المهنة</th>
                            <th>رقم الجواز</th>
                            <th>الاجراء الحالى</th>
                            <th>البلد</th>
                            <th>الحالة</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cvs as $index=>$cv)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $cv->name }}</td>
                                <td>{{ $cv->gender == 1 ? 'ذكر' : 'انثي' }}</td>
                                <td>{{ $cv->birth_date }}</td>
                                <td>{{ $cv->profession->name }}</td>
                                <td>{{ $cv->passport }}</td>
                                <td>{{ $cv->procedure }}</td>
                                <td>{{ $cv->country->name }}</td>
                                <td>
                                    <p class="{{ $cv->accepted ? 'text-success' : 'text-warning' }}">
                                        {{ $cv->accepted ? 'تمت الموافقة' : 'في الانتظار' }}
                                    </p>
                                </td>
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

        if(content == 'profession') {
            $('#search-content').append(`
                <div id="profession">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="profession">اسم المهنة</span>
                        </div>
                        <input type="text" name="profession" class="form-control" placeholder="اسم المهنة" aria-label="profession" aria-describedby="profession">
                    </div>
                </div>
            `)
        }
        
        if(content == 'country') {
            $('#search-content').append(`
                <div id="country">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="country">اسم الدولة</span>
                        </div>
                        <input type="text" name="country" class="form-control" placeholder="اسم الدولة" aria-label="country" aria-describedby="country">
                    </div>
                </div>
            `)
        }
    }
</script>

@endpush