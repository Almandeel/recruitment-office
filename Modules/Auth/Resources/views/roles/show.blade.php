@extends('layouts.master', [
	'modals' => ['employee'], 
    'title' => 'الدور: ' . $role->name,
    'datatable' => true, 
    'crumbs' => [
        [route('roles.index'), 'المستخدمين'],
        ['#', $role->name],
    ]
])

@section('title')
    عرض
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3>{{ $role->name }}</h3>
                </div>
            </div>
        </div>
    </div>
<form action="{{ route('roles.destroy', $role->id) }}" method="POST">
        @csrf
        {{ method_field('DELETE') }}
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <table class="table table-borderd">
                            <tr>
                                <th>الاسم </th>
                                <td>{{ $role['name'] }}</td>
                            </tr>
                            <tr>
                                <th>الصلاحيات</th>
                                <td>
                                    @foreach ($role->permissions as $permission)
                                        <span style="background-color:#499c47; padding:6px 10px" class="badge">{{ __('permissions.' . $permission->name) }}</span>
                                    @endforeach
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="card-footer">
                        @if(request()->delete && $role->id != 1)
                            @permission('roles-delete')
                                <button type="submit" class="btn btn-danger btn-sm delete"><i class="fa fa-trash"></i> حذف </button>
                            @endpermission
                        @else
                            @if($role->id != 1)
                            @permission('roles-update')
                                <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i>  تعديل </a>
                            @endpermission
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection