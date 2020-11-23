@extends('accounting::layouts.master',[
    'title' => __('accounting::global.safes'),
    'datatable' => true,
    'accounting_modals' => [
        'safe'
    ],
    'crumbs' => [
        ['#', __('accounting::global.safes')],
    ]
])

@push('content')
    @component('accounting::components.tabs')
        @slot('items')
            @component('accounting::components.tab-item')
                @slot('id', 'cashes')
                @slot('active', true)
                @slot('title', __('accounting::safes.cashes'))
            @endcomponent
            @component('accounting::components.tab-item')
                @slot('id', 'banks')
                @slot('title', __('accounting::safes.banks'))
            @endcomponent
            @component('accounting::components.tab-item')
                @slot('id', 'create')
                @slot('title', __('accounting::safes.create'))
            @endcomponent
        @endslot
        @slot('contents')
            @component('accounting::components.tab-content')
                @slot('id', 'cashes')
                @slot('active', true)
                @slot('content')
                    <table class="table table-striped table-condonsed datatable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>@lang('accounting::global.id')</th>
                                <th>@lang('accounting::global.name')</th>
                                <th>@lang('accounting::global.balance')</th>
                                <th>@lang('accounting::global.options')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cashes as $safe)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $safe->id }}</td>
                                    <td>{{ $safe->name }}</td>
                                    <td>{{ $safe->balance(true) }}</td>
                                    <td><div class="btn-group">
                                        @permission('accounts-read')
                                        <a href="{{ route('accounts.show', ['account' => $safe->id, 'view' => 'statement']) }}" class="btn btn-default">
                                            <i class="fa fa-list"></i>
                                            <span class="d-sm-none d-lg-inline">@lang('accounting::accounts.statement')</span>
                                        </a>
                                        @endpermission
                                        @permission('safes-read')
                                            {!! safeButton($safe, 'show') !!}
                                        @endpermission
                                        @permission('safes-read')
                                            {!! safeButton($safe, 'preview') !!}
                                        @endpermission
                                        @permission('safes-update')
                                            {!! safeButton($safe, 'update') !!}
                                        @endpermission
                                        @permission('safes-delete')
                                            {!! safeButton($safe, 'delete') !!}
                                        @endpermission
                                    </div></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endslot
            @endcomponent
            @component('accounting::components.tab-content')
                @slot('id', 'banks')
                @slot('content')
                    <table class="table table-striped table-condonsed datatable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>@lang('accounting::global.id')</th>
                                <th>@lang('accounting::global.name')</th>
                                <th>@lang('accounting::global.balance')</th>
                                <th>@lang('accounting::global.options')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($banks as $safe)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $safe->id }}</td>
                                    <td>{{ $safe->name }}</td>
                                    <td>{{ $safe->balance(true) }}</td>
                                    <td><div class="btn-group">
                                        @permission('accounts-read')
                                        <a href="{{ route('accounts.show', ['account' => $safe->id, 'view' => 'statement']) }}" class="btn btn-default">
                                            <i class="fa fa-list"></i>
                                            <span class="d-sm-none d-lg-inline">@lang('accounting::accounts.statement')</span>
                                        </a>
                                        @endpermission
                                        @permission('safes-read')
                                            {!! safeButton($safe, 'show') !!}
                                        @endpermission
                                        @permission('safes-read')
                                            {!! safeButton($safe, 'preview') !!}
                                        @endpermission
                                        @permission('safes-update')
                                            {!! safeButton($safe, 'update') !!}
                                        @endpermission
                                        @permission('safes-delete')
                                            {!! safeButton($safe, 'delete') !!}
                                        @endpermission
                                    </div></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endslot
            @endcomponent
            @component('accounting::components.tab-content')
                @slot('id', 'create')
                @slot('content')
                    <form action="{{ route('safes.store') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label>@lang('accounting::global.name')</label>
                                <input class="form-control name" autocomplete type="text" name="name" id="name" placeholder="@lang('accounting::global.name')" required>
                            </div>
                            <div class="form-group">
                                <label>@lang('accounting::global.type')</label>
                                <select class="form-control type" name="type" id="type" required>
                                    @foreach (Modules\Accounting\Models\Safe::TYPES as $type)
                                    <option value="{{ $type }}">@lang('accounting::safes.types.' . $type)</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">@lang('accounting::global.save')</button>
                            </div>
                        </div>
                    </form>
                @endslot
            @endcomponent
        @endslot
    @endcomponent
@endpush
@push('foot')
    <script>
        $(function(){
        })
    </script>
@endpush