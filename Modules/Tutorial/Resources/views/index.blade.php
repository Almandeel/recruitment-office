@extends('tutorial::layouts.master',  [
    'title' => 'مركز المعرفة',
    'summernote' => true,
])
@push('head')
    <link rel="stylesheet" href="{{ asset('dashboard/css/tutorial.css') }}">
    <style>
        .detials {
            color:#777
        }
    </style>
@endpush

@section('content')
    <div class="contener">
        <div class="row">

            <div class="col-md-8">
                @foreach ($tutorials as $tutorial)
                    <div class="post-box">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <a class="float-right" href="{{ route('tutorials.show', $tutorial->id) }}">{{ $tutorial->title }}</a>
                                </h3>
                                <div class="detials clearfix text-right">
                                    <span><i class="fa fa-clock"> {{ $tutorial->created_at->format('Y-m-d') }}</i></span> | 
                                    <span><i class="fa fa-book"> {{ $tutorial->category->name }}</i></span> |
                                    <span><i class="fa fa-user"> {{ $tutorial->user->name }}</i></span>

                                    @permission('tutorials-update')
                                        | <a class="text-warning "  href="{{ route('tutorials.edit', $tutorial->id) }}">تعديل</a> |
                                    @endpermission
                                    
                                    

                                    @permission('tutorials-delete')
                                        <a href="#" class="delete text-danger " data-form="#deleteForm-{{ $tutorial->id }}">
                                            <span>حذف</span>
                                        </a>
                                        <form id="deleteForm-{{ $tutorial->id }}" action="{{ route('tutorials.destroy', $tutorial) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    @endpermission
                                </div>
                            </div>
                            {{-- <div class="card-body">
                                @php $content = html_entity_decode(htmlspecialchars_decode( $tutorial->content))  @endphp
                                <p style="width:100%">{!! \Str::limit($content)  !!}</p>
                            </div> --}}
                        </div>
                        <hr style="border:1px dashed #ccc">

                        <div class="pages clearfix">
                            {!! $tutorials->links() !!}
                        </div>

                    </div>
                @endforeach
            </div>

            <div class="col-md-4">

                {{-- <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">اخر المقالات</h3>
                    </div>
                    <div class="card-body">
                        <p><a href="#">مقال 1</a></p>
                        <p><a href="#">مقال 2</a></p>
                    </div>
                </div> --}}

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">الاقسام</h3>
                    </div>
                    <div class="card-body clearfix">
                        @foreach ($categories as $category)
                            <p><a href="{{ route('tutorials.index') }}?category={{ $category->id }}">{{ $category->name }}</a></p>
                        @endforeach
                        <p><a href="{{ route('tutorials.index') }}">الكل</a></p>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
