@extends('layouts.master', [
    'title' => 'المستخدمين',
    'datatable' => true, 
    'crumbs' => [
    ]
])
@section('content')
<section class="content">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">قائمة المستخدمين</h3>
        </div>
        <div class="card-body">
            <table id="users-table" class="table table-bordered table-hover text-center">
                <thead>
                    <tr>
                        <th>اسم المستخدم</th>
                        <th>الادوار</th>
                        <th>الخيارات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $u)
                    <tr>
                        <td>{{ $u->username }}</td>
                        <td>
                            @foreach ($u->roles as $role)
                                <span class="badge badge-info">{{ $role->name }}</span>
                            @endforeach
                        </td>
                        <td>
                            @permission('users-read')
                            <a class="btn btn-info btn-xs" href="{{ route('users.show', $u->id) }}"><i class="fa fa-eye"></i> عرض
                            </a>
                            @endpermission
            
                            @permission('users-update')
                            <a class="btn btn-warning btn-xs" href="{{ route('users.edit', $u->id) }}"><i class="fa fa-edit"></i>
                                تعديل </a>
                            @endpermission
            
                            @permission('users-delete')
                            <a class="btn btn-danger btn-xs" href="{{ route('users.show', $u->id) }}?delete=true"><i
                                    class="fa fa-trash"></i> حذف </a>
                            @endpermission
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>
@endsection
