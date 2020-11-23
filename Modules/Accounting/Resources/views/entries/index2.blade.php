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
@push('content')
    @if (request('layout') == 'print')
        {{--  <h2>
            
        </h2>  --}}
        @for ($page = 1; $page <= $pages; $page++)
        {{--  @for ($page = $pages; $page >= 1; $page--)  --}}
            @php
                $first = $page == 1 ? 1 : ((($page - 1) * $rows_per_page));
                $last = $page == $pages ? $entries->count() : ($page * $rows_per_page);
            @endphp
            <div class="page">
                <table id="entries-table" class="datatable table table-bordered table-condensed table-striped table-hover">
                    <thead>
                        <tr>
                            <th style="width: 130px;">@lang('accounting::entries.id')</th>
                            <th style="width: 130px;">@lang('accounting::global.type')</th>
                            <th>@lang('accounting::global.from')</th>
                            <th>@lang('accounting::global.to')</th>
                            {{-- <th>@lang('accounting::global.debt')</th>
                                        <th>@lang('accounting::global.credit')</th> --}}
                            <th>@lang('accounting::accounting.details')</th>
                            <th style="width: 130px;">@lang('accounting::entries.date')</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                        @for ($j = $first; $j <= $last; $j++)
                        @php
                            // dd($j, $entries);
                            $entry = $entries[$j - 1];
                        @endphp
                            <tr>
                                <td>{{ $entry->id }}</td>
                                <td>@lang('accounting::entries.types.' . $entry->type)</td>
                                <td>
                                    @if($entry->debts()->count() > 1)<div style="color: transparent;">.</div>@endif
                                    {{--  @if($entry->credits()->count() > 1)<div style="color: transparent;">.</div>@endif  --}}
                                    
                                    @foreach($entry->debts() as $amount)
                                        <div>{{ $amount->pivot->amount}}</div>
                                    @endforeach
                                    @foreach($entry->credits() as $credit)
                                        <div style="color: transparent;">.</div>
                                    @endforeach
                                </td>
                                <td>
                                    @if($entry->debts()->count() > 1)<div style="color: transparent;">.</div>@endif
                                    @if($entry->credits()->count() > 1)<div style="color: transparent;">.</div>@endif
                                    @foreach($entry->debts() as $debt)
                                        <div style="color: transparent;">.</div>
                                    @endforeach
                                    @foreach($entry->credits() as $amount)
                                        <div>{{ $amount->pivot->amount}}</div>
                                    @endforeach
                                </td>
                                <td>
                                    <div>
                                        @if(count($entry->debts()) == 1)
                                            @lang('accounting::entries.from_account') {{ $entry->debts()->first()->name }}
                                        @else
                                            <div>@lang('accounting::entries.from_accounts')</div>
                                            @foreach($entry->debts() as $amount)
                                                <div>@lang('accounting::global.account') {{ $amount->name}}</div>
                                            @endforeach
                                        @endif
                                    </div>
                                    <div>
                                        @if(count($entry->credits()) == 1)
                                            @lang('accounting::entries.to_account') {{ $entry->credits()->first()->name }}
                                        @else
                                            <div>@lang('accounting::entries.to_accounts')</div>
                                            @foreach($entry->credits() as $amount)
                                                <div class="entry_to">@lang('accounting::global.account') {{ $amount->name}}</div>
                                            @endforeach
                                        @endif
                                    </div>
                                    <div class="text-center"><strong>{{ $entry->details }}</strong></div>
                                </td>
                                <td>{{ $entry->entry_date }}</td>
                            </tr>
                        @endfor
                    </tbody>
                </table>
            </div>
        @endfor
    @else
        @component('accounting::components.widget')
            @slot('tools')
                <a href="{{ route('entries.create') }}" class="btn btn-primary guide-create-btn">
                    <i class="fa fa-plus"></i>
                    <span>إنشاء قيد</span>
                </a>
                <a href="{{ route('entries.index', [
                    'layout' => 'print',
                    'account_id' => $account_id,
                    'type' => $type,
                    'from_date' => $from_date,
                    'to_date' => $to_date,
                ]) }}" class="btn btn-default guide-print-btn">
                    <i class="fa fa-print"></i>
                    <span>طباعة</span>
                </a>
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
                <table id="entries-table" class="datatable table-search table table-bordered table-condensed table-striped table-hover">
                    <thead>
                        <tr>
                            <th style="width: 130px;">@lang('accounting::entries.id')</th>
                            <th style="width: 130px;">@lang('accounting::global.type')</th>
                            <th>@lang('accounting::global.from')</th>
                            <th>@lang('accounting::global.to')</th>
                            {{-- <th>@lang('accounting::global.debt')</th>
                            <th>@lang('accounting::global.credit')</th> --}}
                            <th>@lang('accounting::accounting.details')</th>
                            <th style="width: 130px;">@lang('accounting::entries.date')</th>
                            @permission('entries-read')
                            <th style="width: 130px;">@lang('accounting::global.options')</th>
                            @endpermission
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($entries as $entry)
                        {{--  <tr>
                            <td>{{ $entry->id }}</td>
                            <td>@lang('accounting::entries.types.' . $entry->type)</td>
                            <td>
                                @foreach($entry->debts() as $account)
                                    <div>{{ $account->pivot->amount}}</div>
                                @endforeach
                            </td>
                            <td>
                                @foreach($entry->credits() as $account)
                                    <div>-</div>
                                @endforeach
                                @foreach($entry->credits() as $account)
                                    <div>{{ $account->pivot->amount}}</div>
                                @endforeach
                            </td>
                            <td>
                                <div>
                                    @if(count($entry->debts()) == 1)
                                        @lang('accounting::entries.from_account') {{ $entry->debts()->first()->name  }}
                                    @else
                                        <div>@lang('accounting::entries.from_accounts')</div>
                                        @foreach($entry->debts() as $account)
                                            <div>@lang('accounting::global.account') {{ $account->name}}</div>
                                        @endforeach
                                    @endif
                                </div>
                                <div>
                                    @if(count($entry->credits()) == 1)
                                        @lang('accounting::entries.to_account') {{ $entry->credits()->first()->name  }}
                                    @else
                                        <div>@lang('accounting::entries.to_accounts')</div>
                                        @foreach($entry->credits() as $account)
                                            <div class="entry_to">@lang('accounting::global.account') {{ $account->name }}</div>
                                        @endforeach
                                    @endif
                                </div>
                                <div class="text-center"><strong>{{ $entry->details }}</strong></div>
                            </td>
                            <td>{{ format_date($entry->entry_date) }}</td>

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
                        </tr>  --}}
                        <tr>
                            <td>{{ $entry->id }}</td>
                            <td>@lang('accounting::entries.types.' . $entry->type)</td>
                            <td>
                                @if($entry->debts()->count() > 1)<div style="color: transparent;">.</div>@endif
                                {{--  @if($entry->credits()->count() > 1)<div style="color: transparent;">.</div>@endif  --}}
                                
                                @foreach($entry->debts() as $amount)
                                    <div>{{ $amount->pivot->amount}}</div>
                                @endforeach
                                @foreach($entry->credits() as $credit)
                                    <div style="color: transparent;">.</div>
                                @endforeach
                            </td>
                            <td>
                                @if($entry->debts()->count() > 1)<div style="color: transparent;">.</div>@endif
                                @if($entry->credits()->count() > 1)<div style="color: transparent;">.</div>@endif
                                @foreach($entry->debts() as $debt)
                                    <div style="color: transparent;">.</div>
                                @endforeach
                                @foreach($entry->credits() as $amount)
                                    <div>{{ $amount->pivot->amount}}</div>
                                @endforeach
                            </td>
                            <td>
                                <div>
                                    @if(count($entry->debts()) == 1)
                                        @lang('accounting::entries.from_account') {{ $entry->debts()->first()->name }}
                                    @else
                                        <div>@lang('accounting::entries.from_accounts')</div>
                                        @foreach($entry->debts() as $amount)
                                            <div>@lang('accounting::global.account') {{ $amount->name}}</div>
                                        @endforeach
                                    @endif
                                </div>
                                <div>
                                    @if(count($entry->credits()) == 1)
                                        @lang('accounting::entries.to_account') {{ $entry->credits()->first()->name }}
                                    @else
                                        <div>@lang('accounting::entries.to_accounts')</div>
                                        @foreach($entry->credits() as $amount)
                                            <div class="entry_to">@lang('accounting::global.account') {{ $amount->name}}</div>
                                        @endforeach
                                    @endif
                                </div>
                                <div class="text-center"><strong>{{ $entry->details }}</strong></div>
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
    @endif
@endpush
@push('foot')
    <script>
        $(function() {
        });
    </script>
@endpush