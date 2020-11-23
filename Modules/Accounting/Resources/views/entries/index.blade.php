@php
    $layout = request('layout') == 'print' ? 'layouts.print' : 'accounting::layouts.master';
    $options = [
        'title' => __('accounting::global.entries'),
        'datatable' => true,
        'accounting_modals' => [
            'center'
        ],
        'crumbs' => [
            ['#', __('accounting::global.entries')],
        ],
        /* 'guides' => [
            [
                'element' => '.guide-create-btn',
                'title' =>  __('accounting::entries.guides.index.guide_create_btn.title'),
                'description' => __('accounting::entries.guides.index.guide_create_btn.description'),
                'position' => 'right',
            ],
            [
                'element' => '.guide-advanced-search',
                'title' =>  __('accounting::entries.guides.index.guide_advanced_search.title'),
                'description' => __('accounting::entries.guides.index.guide_advanced_search.description'),
                'position' => 'top',
            ],
        ], */
    ];

    if(request('layout') == 'print'){
        $options = [
            'title' => __('accounting::global.entries'),
            'heading' => '<i class="fa fa-book"></i><span>' . __('accounting::entries.list') . '</span>',
        ];
    }
@endphp
@extends($layout, $options)
@push('head')
    <style>
        table.dataTable thead th, table.dataTable thead td{
            border-color: #ddd !important;
        }
    </style>
@endpush
@push('content')
    @component('accounting::components.widget')
        @slot('tools')
            <a href="{{ route('entries.create') }}" class="btn btn-primary guide-create-btn">
                <i class="fa fa-plus"></i>
                <span>إنشاء قيد</span>
            </a>
            {{--  <a href="{{ route('entries.index', [
                'layout' => 'print',
                'account_id' => $account_id,
                'type' => $type,
                'from_date' => $from_date,
                'to_date' => $to_date,
            ]) }}" class="btn btn-default guide-print-btn">
                <i class="fa fa-print"></i>
                <span>طباعة</span>
            </a>  --}}
        @endslot
        @slot('extra')
            <form action="" method="GET" class="form-inline guide-advanced-search">
                @csrf
                <div class="form-group mr-2">
                    <label>
                        <i class="fa fa-cogs"></i>
                        <span>@lang('accounting::global.search_advanced')</span>
                    </label>
                </div>
                <div class="form-group mr-2">
                    <label for="account_id">@lang('accounting::global.account')</label>
                    <select name="account_id" id="account_id" class="form-control select2">
                        <option value="all" {{ ($account_id == 'all') ? 'selected' : '' }}>@lang('accounting::global.all')</option>
                        @foreach ($accounts as $acc)
                        <option value="{{ $acc->id }}" {{ ($account_id == $acc->id) ? 'selected' : '' }}>{{ $acc->number . '-' . $acc->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group mr-2">
                    <label for="type">@lang('accounting::global.type')</label>
                    <select class="form-control type" name="type" id="type">
                        <option value="all" {{ ($type == 'all') ? 'selected' : '' }}>@lang('accounting::global.all')</option>
                        @foreach ($types as $t)
                            <option value="{{ $t }}" {{ ($t == $type) ? 'selected' : '' }}>@lang('accounting::entries.types.' . $t)</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group mr-2">
                    <label for="from-date">@lang('accounting::global.from')</label>
                    <input type="date" name="from_date" id="from-date" value="{{ $from_date }}" class="form-control">
                </div>
                <div class="form-group mr-2">
                    <label for="to-date">@lang('accounting::global.to')</label>
                    <input type="date" name="to_date" id="to-date" value="{{ $to_date }}" class="form-control">
                </div>
                <button type="submit" class="btn btn-primary">
                    <span>@lang('accounting::global.search')</span>
                    <i class="fa fa-search"></i>
                </button>
            </form>
        @endslot
        @slot('title')
            <i class="fa fa-book"></i>
            <span>@lang('accounting::entries.list')</span>
        @endslot
        @slot('body')
        <div class="table-wrapper">
            <table id="entries-table" class="datatable table-search table table-bordered">
                <thead>
                    <tr>
                        <th rowspan="2" style="width: 130px;">@lang('accounting::entries.id')</th>
                        {{--  <th rowspan="2" style="width: 130px;">@lang('accounting::global.type')</th>  --}}
                        <th rowspan="2">@lang('accounting::global.amount')</th>
                        <th rowspan="2">@lang('accounting::accounting.details')</th>
                        <th colspan="2">@lang('accounting::accounting.debt')</th>
                        <th colspan="2">@lang('accounting::accounting.credit')</th>
                        <th rowspan="2" style="width: 130px;">@lang('accounting::entries.date')</th>
                        @permission('entries-read')
                        <th rowspan="2" style="width: 130px;">@lang('accounting::global.options')</th>
                        @endpermission
                    </tr>
                    <tr>
                        <th>@lang('accounting::global.amount')</th>
                        <th>@lang('accounting::global.account')</th>
                        <th>@lang('accounting::global.amount')</th>
                        <th>@lang('accounting::global.account')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($entries as $entry)
                    <tr>
                        <td>{{ $entry->id }}</td>
                        {{--  <td>@lang('accounting::entries.types.' . $entry->type)</td>  --}}
                        <td>{{ $entry->money('amount', '') }}</td>
                        <td>{{ $entry->details }}</td>
                        <td style="padding: 0; vertical-align: top;">
                            <table class="table">
                                <tbody>
                                    @foreach($entry->debts() as $account)
                                        <tr>
                                            <td style="background-color: transparent !important; border: none !important;">{{ $account->pivot->amount }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </td>
                        <td style="padding: 0; vertical-align: top;">
                            <table class="table">
                                <tbody>
                                    @foreach($entry->debts() as $account)
                                        <tr>
                                            <td style="background-color: transparent !important; border: none !important;">{{ $account->numeric_name}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </td>
                        <td style="padding: 0; vertical-align: top;">
                            <table class="table">
                                <tbody>
                                    @foreach($entry->credits() as $account)
                                        <tr>
                                            <td style="background-color: transparent !important; border: none !important;">{{ $account->pivot->amount }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </td>
                        <td style="padding: 0; vertical-align: top;">
                            <table class="table">
                                <tbody>
                                    @foreach($entry->credits() as $account)
                                        <tr>
                                            <td style="background-color: transparent !important; border: none !important;">{{ $account->numeric_name}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </td>
                        <td>{{ $entry->entry_date }}</td>
                        <td>
                            <div class="btn-group">
                                @if (!$entry->hasAdverse() && !$entry->isAdverse())
                                @permission('entries-update')
                                    <a href="#" data-toggle="entry" data-url="{{ route('entries.store') }}" data-entry-id="{{ $entry->id }}" data-operation="adverse" class="btn btn-warning">
                                        <i class="fa fa-sync"></i>
                                        <span class="d-xs-none d-lg-inline">@lang('accounting::global.reverse')</span>
                                    </a>
                                @endpermission
                                @endif
                                @permission('entries-delete')
                                <a href="#" class="btn btn-danger delete" data-form="#deleteForm-{{ $entry->id }}">
                                    <i class="fa fa-trash"></i>
                                    <span class="d-xs-none d-lg-inline">@lang('accounting::global.delete')</span>
                                </a>
                                @endpermission
                            </div>
                            @permission('entries-delete')
                            <form id="deleteForm-{{ $entry->id }}" style="display:none;"
                                action="{{ route('entries.destroy', $entry->id) }}" method="post">
                                @csrf
                                @method('DELETE')
                            </form>
                            @endpermission
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endslot
    @endcomponent
@endpush
@push('foot')
    <style>
        table.table table.table{
            margin-bottom: 0 !important;
        }

        .table-bordered td td, .table-bordered td th,
        table.table-bordered.dataTable th:last-child td, table.table-bordered.dataTable td:last-child th
        {
            border: none !important;
            background-color: transparent !important;
        }
        
    </style>
    <script>
        $(function() {
        });
    </script>
@endpush