@extends('accounting::layouts.master',[
    'title' => __('accounting::global.centers'),
    'datatable' => true,
    'accounting_modals' => [
        'center'
    ],
    'crumbs' => [
        ['#', __('accounting::global.centers')],
    ]
])

@push('content')
    @component('accounting::components.tabs')
        @slot('items')
            @component('accounting::components.tab-item')
                @slot('id', 'costs')
                @slot('active', true)
                @slot('title', __('accounting::centers.costs'))
            @endcomponent
            @component('accounting::components.tab-item')
                @slot('id', 'profits')
                @slot('title', __('accounting::centers.profits'))
            @endcomponent
            @component('accounting::components.tab-item')
                @slot('id', 'create')
                @slot('title', __('accounting::centers.create'))
            @endcomponent
        @endslot
        @slot('contents')
            @component('accounting::components.tab-content')
                @slot('id', 'costs')
                @slot('active', true)
                @slot('content')
                    <table class="table table-striped table- datatable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>@lang('accounting::global.id')</th>
                                <th>@lang('accounting::global.name')</th>
                                <th>@lang('accounting::global.options')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($costs as $center)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $center->id }}</td>
                                    <td>{{ $center->name }}</td>
                                    <td class="btn-group">
                                        @permission('centers-read')
                                            {!! centerButton($center, 'show') !!}
                                        @endpermission
                                        @permission('centers-read')
                                            {!! centerButton($center, 'preview') !!}
                                        @endpermission
                                        @permission('centers-update')
                                            {!! centerButton($center, 'update') !!}
                                        @endpermission
                                        @permission('centers-delete')
                                            {!! centerButton($center, 'delete') !!}
                                        @endpermission
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endslot
            @endcomponent
            @component('accounting::components.tab-content')
                @slot('id', 'profits')
                @slot('content')
                    <table class="table table-striped table-condonsed datatable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>@lang('accounting::global.id')</th>
                                <th>@lang('accounting::global.name')</th>
                                <th>@lang('accounting::global.options')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($profits as $center)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $center->id }}</td>
                                    <td>{{ $center->name }}</td>
                                    <td class="btn-group">
                                        @permission('centers-read')
                                            {!! centerButton($center, 'show') !!}
                                        @endpermission
                                        @permission('centers-read')
                                            {!! centerButton($center, 'preview') !!}
                                        @endpermission
                                        @permission('centers-update')
                                            {!! centerButton($center, 'update') !!}
                                        @endpermission
                                        @permission('centers-delete')
                                            {!! centerButton($center, 'delete') !!}
                                        @endpermission
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endslot
            @endcomponent
            @component('accounting::components.tab-content')
                @slot('id', 'create')
                @slot('content')
                    <form action="{{ route('centers.store') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label>@lang('accounting::global.name')</label>
                                <input class="form-control name" autocomplete type="text" name="name" id="name" placeholder="@lang('accounting::global.name')" required>
                            </div>
                            <div class="form-group">
                                <label>@lang('accounting::global.type')</label>
                                <select class="form-control type" name="type" id="type" required>
                                    @foreach (\Modules\Accounting\Models\Center::TYPES as $type)
                                    <option value="{{ $type }}">@lang('accounting::centers.types.' . $type)</option>
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