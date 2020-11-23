@extends('layouts.master', [
    'title' => 'العملاء',
    'datatable' => true, 
    'modals' => ['customer', 'complaint'], 
    'crumbs' => [
        ['#', 'العملاء'],
    ]
])

@section('content')
    <section class="content">
        @component('components.tabs')
            @slot('tools')
                @permission('contracts-create')
                    <button class="btn btn-primary btn-sm customers" data-toggle="modal" data-target="#customerModal"><i class="fa fa-plus"></i> إضافة</button>
                @endpermission
            @endslot
            @slot('items')
                @component('components.tab-item')
                    @slot('active', true)
                    @slot('id', 'active')
                    @slot('title', 'عملاء جدد')
                @endcomponent
                @component('components.tab-item')
                    @slot('id', 'deactive')
                    @slot('title', "عملاء تم التعاقد معهم")
                @endcomponent
            @endslot
            @slot('contents')
                @component('components.tab-content')
                    @slot('active', true)
                    @slot('id', 'active')
                    @slot('content')
                        <table class="datatable table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>الاسم</th>
                                    <th>العنوان</th>
                                    <th>رقم الهاتف</th>
                                    <th>رقم الهوية</th>
                                    <th>تاريخ الإضافة</th>
                                    <th>الموظف</th>
                                    <th>الخيارات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($newCustomers as $index=>$customer)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $customer->name }}</td>
                                            <td>{{ $customer->address }}</td>
                                            <td>{{ $customer->phones }}</td>
                                            <td>{{ $customer->id_number }}</td>
                                            <td>{{ $customer->created_at->format('Y/m/d') }}</td>
                                            <td>{{ $customer->user->name ?? '-' }}</td>
                                            <td>
                                                <div class="dropdown d-inline-block">
                                                    <button class="btn btn-default btn-xs dropdown-toggle" type="button" data-toggle="dropdown">
                                                        <span>المزيد</span>
                                                        <span class="caret"></span>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        @permission('customers-read')
                                                        <a href="{{ route('customers.show', $customer->id) }}" class="dropdown-item text-default"><i class="fa fa-eye"></i> عرض</a>
                                                        @endpermission
                                                        @permission('contracts-create')
                                                            <a href="{{ route('customers.addcontract', $customer->id) }}" class="dropdown-item text-info"><i class="fa fa-plus"></i>  انشاء طلب  توظيف </a>
                                                        @endpermission
                                                        @permission('customers-update')
                                                            <button class="dropdown-item text-warning btn-sm customer update" data-description="{{ $customer->description }}"  data-number="{{ $customer->id_number }}" data-action="{{ route('customers.update', $customer->id) }}"  data-name="{{ $customer->name }}" data-address="{{ $customer->address }}" data-phones="{{ $customer->phones }}"  data-toggle="modal" data-target="#warehouseModal"><i class="fa fa-edit"></i> تعديل</button>
                                                        @endpermission
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endslot
                @endcomponent
                @component('components.tab-content')
                    @slot('id', 'deactive')
                    @slot('content')
                        <table class="datatable table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>الاسم</th>
                                    <th>العنوان</th>
                                    <th>رقم الهاتف</th>
                                    <th>رقم الهوية</th>
                                    <th>تاريخ الإضافة</th>
                                    <th>الموظف</th>
                                    <th>الخيارات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($contractedCustomers as $index=>$customer)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $customer->name }}</td>
                                            <td>{{ $customer->address }}</td>
                                            <td>{{ $customer->phones }}</td>
                                            <td>{{ $customer->id_number }}</td>
                                            <td>{{ $customer->created_at->format('Y/m/d') }}</td>
                                            <td>{{ $customer->user->name ?? '-' }}</td>
                                            <td>
                                                <div class="dropdown d-inline-block">
                                                    <button class="btn btn-default btn-xs dropdown-toggle" type="button" data-toggle="dropdown">
                                                        <span>المزيد</span>
                                                        <span class="caret"></span>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        @permission('customers-read')
                                                        <a href="{{ route('customers.show', $customer->id) }}" class="dropdown-item text-default"><i class="fa fa-eye"></i> عرض</a>
                                                        @endpermission
                                                        @permission('contracts-create')
                                                            <a href="{{ route('customers.addcontract', $customer->id) }}" class="dropdown-item text-info"><i class="fa fa-plus"></i>  انشاء طلب  توظيف </a>
                                                        @endpermission
                                                        @permission('customers-update')
                                                            <button class="dropdown-item text-warning btn-sm customer update" data-description="{{ $customer->description }}"  data-number="{{ $customer->id_number }}" data-action="{{ route('customers.update', $customer->id) }}"  data-name="{{ $customer->name }}" data-address="{{ $customer->address }}" data-phones="{{ $customer->phones }}"  data-toggle="modal" data-target="#warehouseModal"><i class="fa fa-edit"></i> تعديل</button>
                                                        @endpermission
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endslot
                @endcomponent
            @endslot
        @endcomponent
    </section>
@endsection