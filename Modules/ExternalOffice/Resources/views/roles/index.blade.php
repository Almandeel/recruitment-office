@extends('externaloffice::layouts.master', [
    'title' => 'Roles list',
    'datatable' => true, 
    'ltr' => true, 
    'crumbs' => [
        ['#', 'Roles list'],
    ]
])

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Roles list</h3>
        {{-- <div class="card-tools"> --}}
            @permission('roles-create')
            <h3 class="card-tools"><a href="{{ route('office.roles.create') }}" class="btn btn-primary btn-sm" role="button"><i class="fa fa-plus"> Add</i></a></h3>
            @endpermission
        {{-- </div> --}}
    </div>
    <div class="card-body">
        <table class="datatable table table-bordered table-hover">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Options</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($roles as $role)
                    <tr>
                        <td>{{ $role->name }}</td>
                        <td>
                            @permission('roles-show')
                            <a class="btn btn-info btn-xs" href="{{ route('office.roles.show', $role->id) }}"><i class="fa fa-eye"></i> Show  </a>
                            @endpermission
                            @if($role->id != 1)
                            @permission('roles-update')
                            <a class="btn btn-warning btn-xs" href="{{ route('office.roles.edit', $role->id) }}"><i class="fa fa-edit"></i> Edit </a>
                            @endpermission
                            @permission('roles-delete')
                            <form class="d-inline-block" action="{{ route('office.roles.destroy', $role->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="button" data-form="#deleteForm" class="btn btn-danger btn-xs delete"><i class="fa fa-trash"></i> <span>Delete</span> </button>
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
