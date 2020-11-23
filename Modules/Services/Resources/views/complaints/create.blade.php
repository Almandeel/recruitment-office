@extends('layouts.master', [
    'title' => 'اضافة شكوي',
    'modals' => ['customer'],
    'crumbs' => [
        [route('complaints.index'), 'الشكاوي'],
        ['#', 'اضافة شكوى'],
    ]
])

@section('content')
<section class="content">
    <form id="form_positions" class="form" action="{{ route('complaints.store') }}" method="post">
        @csrf
        <div class="row">
            <div class="col">
                @component('components.widget')
                    @slot('title')
                        <i class="fas fa-list"></i>
                        <span>اضافة شكوى</span>
                    @endslot
                    @slot('body')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title">العميل</label>
                                    <select id="customers" onchange="getCvs()" class="form-control" name="customer_id" placeholder="العميل" required>
                                        @foreach ($customers as $customer)
                                            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title">CV</label>
                                    <select id="cvs" class="form-control" name="cv_id" placeholder="CV">
                                        
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="title">الشكوى</label>
                            <textarea name="cause" class="form-control" placeholder="الشكوى" rows="5" cols="5" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> اكمال العملية</button>
                    @endslot
                @endcomponent
            </div>
            <div class="col">
                @component('components.widget')
                @slot('noTitle', true)
                @slot('title')
                    <i class="fas fa-paperclip"></i>
                    <span>المرفقات</span>
                @endslot
                @slot('body')
                    @component('components.attachments-uploader')
                    @endcomponent
                @endslot
                @slot('footer')
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> اكمال العملية</button>
                @endslot
            @endcomponent
            </div>
        </div>
    </form>
</section>
@endsection

@push('foot')
    <script>
        function getCvs() {
            var customer = $('#customers').val();
            $.ajax({
                url: "{{ url('services/get/cvs') }}" + '/' + customer,
            }).done(function(cvs) {
                $('#cvs').html('')
                cvs.forEach(cv => {
                    $('#cvs').append(`<option value="${cv.id}">${cv.name}  | ${cv.passport}</option>`)
                });
            });
        }


        $(function () {
            getCvs();
        })
    </script>
@endpush