@extends('layouts.master', [
	'modals' => ['employee'], 
    'title' => 'مستخدم: ' . $user->name,
    'datatable' => true, 
    'crumbs' => [
        [route('users.index'), 'المستخدمين'],
        ['#', $user->name],
    ]
])
@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="box">
                <div class="box-header">
                    <h3>{{ $user->username }}</h3>
                </div>
            </div>
        </div>
    </div>
<form action="{{ route('users.destroy', $user->id) }}" method="POST">
        @csrf
        {{ method_field('DELETE') }}
        <div class="row">
            <div class="col-md-8">
                <div class="box">
                    <div class="box-header">
                        <table class="table table-borderd">
                            <tr>
                                <th>الاسم</th>
                                <td>{{ $user->name }}</td>
                            </tr>
                            <tr>
                                <th>@lang('users.username')</th>
                                <td>{{ $user->username }}</td>
                            </tr>
                            <tr>
                                <th>اسم الموظف</th>
                                <td>{{ $user['employee']['name'] ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>الأدوار</th>
                                <td>
                                    @forelse ($user->roles as $role)
                                        <p>{{ $role->name }}</p>
                                    @empty
                                        <span>-</span>
                                    @endforelse
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="box-footer">
                        @if(request()->delete)
                            @permission('users-delete')
                                <button type="submit" class="btn btn-danger btn-sm delete"><i class="fa fa-trash"></i> حذف </button>
                            @endpermission
                        @else
                            @permission('users-delete')
                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i>  تعديل </a>
                            @endpermission
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection