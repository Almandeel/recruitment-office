@extends('layouts.master', [
'modals' => ['category'],
'title' => 'اضافة مقالة',
'summernote' => true, 
'crumbs' => [
    [route('tutorials.index'), 'المقالات'],
    ['#', 'اضافة'],
]
])

@push('head')

@endpush


@section('content')
<section class="content">
    <form action="{{ route('tutorials.store') }}" method="post">
        @csrf
        <div class="form-group">
            <label>العنوان</label>
            <input type="text" name="title" class="form-control" placeholder="العنوان">
        </div>

        <div class="form-group">
            <select name="category" class="form-control editable">
                @foreach($categories as $category)
                    <option value="{{ $category->name }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>المحتوي</label>
            <textarea id="compose-textarea" style="min-height: 300px !important"  name="content" cols="30" rows="10" class="form-control"></textarea>
        </div>

        <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> اضافة</button>
    </form>
</section>
@endsection


@push('foot')

@endpush