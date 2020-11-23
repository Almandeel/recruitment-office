@extends('layouts.master', [
'modals' => ['category'],
'title' => 'تعديل مقالة',
'summernote' => true, 
'crumbs' => [
['#', 'المقالات'],
]
])

@push('head')

@endpush


@section('content')
<section class="content">
    <form action="{{ route('tutorials.update', $tutorial->id) }}" method="post">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label>العنوان</label>
            <input type="text" name="title" class="form-control" placeholder="العنوان" value="{{ $tutorial->title }}">
        </div>

        <div class="form-group">
            <select name="category_id" class="form-control">
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ $tutorial->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>المحتوي</label>
            <textarea id="compose-textarea" style="min-height: 300px !important"  name="content" cols="30" rows="10" class="form-control">{{ html_entity_decode(htmlspecialchars_decode( $tutorial->content)) }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-save"></i> حفظ التعديلات</button>
    </form>
</section>
@endsection


@push('foot')

@endpush