@extends('externaloffice::layouts.master', [
    'title' => 'Users list',
    'datatable' => true, 
    'ltr' => true, 
    'crumbs' => [
        ['#', 'Users list'],
    ]
])

@section('content')
<section class="content">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Users list</h3>
            @permission('users-create')
                <h3 class=""><a href="{{ route('office.users.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Add</a></h3>
            @endpermission
            </div>
        <div class="card-body">
            <table class="table table-bordered table-hover text-center datatable">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Roles</th>
                        <th>Options</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>
                            @foreach ($user->roles as $role)
                                <span class="badge badge-info">{{ $role->name }}</span>
                            @endforeach
                        </td>
                        <td>
                            @permission('users-read')
                            <a class="btn btn-info btn-xs" href="{{ route('office.users.show', $user) }}"><i class="fa fa-eye"></i> Show
                            </a>
                            @endpermission
            
                            @permission('users-update')
                            <a class="btn btn-warning btn-xs" href="{{ route('office.users.edit', $user) }}"><i class="fa fa-edit"></i>
                                Edit </a>
                            @endpermission
            
                            @permission('users-delete')
                            <a class="btn btn-danger btn-xs" href="{{ route('office.users.show', $user) }}?delete=true"><i
                                    class="fa fa-trash"></i> Delete </a>
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
