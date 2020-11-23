@extends('layouts.master', [
    'title' => 'إضافة مكتب خارجي',
    'datatable' => true, 
    'modals' => ['country'],
    'crumbs' => [
        [route('offices.index'), 'المكاتب الخارجية'],
        ['#', 'إضافة جديد'],
    ]
])


@section('content')
    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">إضافة مكتب خارجي جديد</h3>
            </div>

            <div class="card-body">
                <form action="{{ route('offices.store') }}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="name">الإسم</label>
                                <input type="text" class="form-control" name="office_name" placeholder="الإسم" required value="{{ old('office_name') }}">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">الإيميل</label>
                                <input type="email" class="form-control" name="office_email" placeholder="الإيميل" required value="{{ old('office_email') }}">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="phone">رقم الهاتف</label>
                                <input type="number" class="form-control" name="office_phone" placeholder="رقم الهاتف" required value="{{ old('office_phone') }}">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status">الحالة</label>
                                <select class="custom-select" name="office_status" id="status" required>
                                    <option value="0">غير نشط</option>
                                    <option value="1">نشط</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="country_id">الدولة</label>
                            <div class="input-group">
                                <select class="custom-select" name="office_country_id" required>
                                    @foreach($countries as $country)
                                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                                    @endforeach
                                </select>
                                <div class="input-group-append">
                                    <button class="btn btn-primary btn-sm" type="button" data-toggle="modal" data-target="#countryModal"><i class="fa fa-plus"></i></button>
                                </div>
                            </div>
                        </div>

                        <p class="col-md-12 text-center mt-4">مشرف المكتب الخارجي</p>

                        <div class="form-group col-md-6">
                            <label for="name">إسم مشرف المكتب الخارجي</label>
                            <input type="text" class="form-control" name="name" placeholder="الإسم" value="{{ old('name') }}">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="username">إسم المستخدم</label>
                            <input type="text" class="form-control" name="username" placeholder="إسم المستخدم" value="{{ old('username') }}">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="password">كلمة المرور</label>
                            <input class="form-control" type="password" name="password" id="password" placeholder="كلمة المرور">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="phone">رقم الهاتف</label>
                            <input type="number" class="form-control" name="phone" placeholder="رقم الهاتف" value="{{ old('phone') }}">
                        </div>

                        <div class="form-group col-md-6">
                            <label for="password">إعادة كلمة المرور</label>
                            <input class="form-control" type="password" name="password_confirmation" placeholder="إعادة كلمة المرور" 
                            data-parsley-equalto="#password" data-parsley-equalto-message="كلمة المرور غير متطابقة">
                        </div>
            
                        <div class="form-group col-md-6">
                            <label for="status">الحالة</label>
                            <select class="custom-select" name="status" id="status">
                                <option value="1">نشط</option>
                                <option value="0">غير نشط</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">حفظ</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection