@extends('externaloffice::layouts.master', [
    'title' => 'User: ' . $user->name,
    'ltr' => true, 
    'crumbs' => [
        [route('office.users.index'), 'User'],
        ['#', $user->name],
    ]
])
@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3>{{ $user->name }}</h3>
            </div>
            <div class="card-body">
                <table class="table table-borderd">
                    <tr>
                        <th>Name</th>
                        <td>{{ $user->name }}</td>
                    </tr>
                    <tr>
                        <th>Username</th>
                        <td>{{ $user->username }}</td>
                    </tr>
                    <tr>
                        <th>Phone</th>
                        <td>{{ $user->phone }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>{{ $user->status ? 'Active' : 'Unactive' }}</td>
                    </tr>
                    <tr>
                        <th>Roles</th>
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
            <div class="card-footer">
                @if(request()->delete)
                    @permission('users-delete')
                    <form action="{{ route('office.users.destroy', $user) }}" method="POST">
                        @csrf
                        {{ method_field('DELETE') }}
                        <button type="submit" class="btn btn-danger btn-sm delete"><i class="fa fa-trash"></i> Delete </button>
                    </form>
                    @endpermission
                @else
                    @permission('users-delete')
                        <a href="{{ route('office.users.edit', $user) }}" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i>  Edit </a>
                    @endpermission
                @endif
            </div>
        </div>
    </div>
</div>
@endsection