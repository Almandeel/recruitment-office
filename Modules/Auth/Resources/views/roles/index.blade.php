@extends('layouts.master', [
    'title' => 'الادوار',
    'datatable' => true, 
    'crumbs' => [
        ['#', 'الادوار'],
    ]
])

@section('content')
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title">قائمة الادوار</h3>
            <div class="card-tools">
                @permission('roles-create')
                <a  href="{{ route('roles.create') }}" class="btn btn-primary" >
                    <i class="fa fa-plus"> إضافة</i>
                </a>
                @endpermission
            </div>

        </div>
        <div class="card-body">
            <table id="roles-table" class="datatable table table-bordered table-hover text-center">
                <thead>
                    <tr>
                        <th>الاسم</th>
                        <th>الخيارات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($roles as $role)
                        <tr>
                            <td>{{ $role->name }}</td>
                                <td>
                                    <a class="btn btn-info btn-xs" href="{{ route('roles.show', $role->id) }}"><i class="fa fa-eye"></i> عرض  </a>
                                    @if($role->id != 1 && $role->name != 'office')
                                    @permission('roles-update')
                                    <a class="btn btn-warning btn-xs" href="{{ route('roles.edit', $role->id) }}"><i class="fa fa-edit"></i> تعديل </a>
                                    @endpermission
                                    @permission('roles-delete')
                                        <button type="button" data-form="#deleteForm" class="btn btn-danger btn-xs delete"><i class="fa fa-trash"></i> <span>حذف</span> </button>
                                        <form id="deleteForm" action="{{ route('roles.destroy', $role->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    @endpermission
                                    @endif
                                </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
