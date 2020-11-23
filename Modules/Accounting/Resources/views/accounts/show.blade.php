@extends($layout, $options)

@push('content')
    @component('accounting::components.tabs')
        @slot('items')
            @component('accounting::components.tab-item')
                @slot('id', 'details')
                @slot('active', true)
                @slot('title', __('accounting::global.details'))
            @endcomponent
            @if ($account->isPrimary())
                @component('accounting::components.tab-item')
                    @slot('id', 'accounts')
                    @slot('title', __('accounting::accounts.secondaries'))
                @endcomponent
                @component('accounting::components.tab-item')
                    @slot('id', 'account-add')
                    @slot('title', __('accounting::accounts.add'))
                @endcomponent
            @else
                @component('accounting::components.tab-item')
                    @slot('id', 'debts')
                    @slot('title', __('accounting::accounting.debts'))
                @endcomponent
                @component('accounting::components.tab-item')
                    @slot('id', 'credits')
                    @slot('title', __('accounting::accounting.credits'))
                @endcomponent
                @component('accounting::components.tab-item')
                    @slot('id', 'costs')
                    @slot('title', __('accounting::centers.costs'))
                @endcomponent
                @component('accounting::components.tab-item')
                    @slot('id', 'profits')
                    @slot('title', __('accounting::centers.profits'))
                @endcomponent
            @endif
        @endslot
        @slot('contents')
            @component('accounting::components.tab-content')
                @slot('id', 'details')
                @slot('active', true)
                @slot('content')
                    <table class="table table-striped">
                        <tr>
                            <th style="width: 20%;">@lang('accounting::global.id')</th>
                            <td>{{ $account->id }}</td>
                        </tr>
                        <tr>
                            <th>@lang('accounting::global.type')</th>
                            <td>{{ $account->getType() }}</td>
                        </tr>
                        <tr>
                            <th>@lang('accounting::global.side')</th>
                            <td>{{ $account->getSide() }}</td>
                        </tr>
                        <tr>
                            <th>@lang('accounting::accounts.main')</th>
                            <td>{{ $account->parent ? $account->parent->name : __("accounting::global.not_exists") }}</td>
                        </tr>
                        {{--  <tr>
                            <th>@lang('accounting::accounts.final')</th>
                            <td>{{ $account->final ? $account->final->name : __("accounting::global.not_exists") }}</td>
                        </tr>  --}}
                        <tr>
                            <th>@lang('accounting::global.balance')</th>
                            <td>{{ $account->balance(true) }}</td>
                        </tr>
                        <tr>
                            <th>@lang('accounting::global.options')</th>
                            <td>
                                <div class="btn-group">
                                    @if ($account->isSecondary())
                                        @permission('accounts-read')
                                            <a href="{{ route('accounts.show', ['account' => $account, 'view' => 'statement']) }}" class="btn btn-default">
                                                <i class="fa fa-list"></i>
                                                <span class="d-sm-none d-lg-inline">@lang('accounting::accounts.statement')</span>
                                            </a>
                                        @endpermission
                                    @endif
                                    {{--  @permission('accounts-read')
                                        <button class="btn btn-info show-modal-account"
                                            data-id="{{ $account->id }}"
                                            data-name="{{ $account->name }}"
                                            data-view="preview"
                                            data-type="{{ $account->type }}"
                                            data-side="{{ $account->side }}"
                                            @if ($account->main_account)
                                            data-main-id="{{ $account->main_account }}"
                                            data-main-name="{{ $account->parent->name }}"
                                            @else
                                            data-main-name="لا يوجد"
                                            @endif
                                            @if ($account->final_account)
                                            data-final-id="{{ $account->final_account }}"
                                            data-final-name="{{ $account->final->name }}"
                                            @else
                                            data-final-name="لا يوجد"
                                            @endif
                                        >
                                            <i class="fa fa-list"></i>
                                            <span class="d-sm-none d-lg-inline">ملخص</span>
                                        </button>
                                    @endpermission  --}}
                                    @permission('accounts-update')
                                        <a href="{{ route('accounts.edit', $account) }}" class="btn btn-warning">
                                            <i class="fa fa-edit"></i>
                                            <span class="d-sm-none d-lg-inline">@lang('accounting::global.edit')</span>
                                        </a>
                                        {{--  <button class="btn btn-warning show-modal-account"
                                            data-action="{{ route('accounts.update', $account) }}"
                                            data-method="PUT"
                                            data-id="{{ $account->id }}"
                                            data-name="{{ $account->name }}"
                                            data-view="update"
                                            data-type="{{ $account->type }}"
                                            data-side="{{ $account->side }}"
                                            @if ($account->main_account)
                                            data-main-id="{{ $account->main_account }}"
                                            data-main-name="{{ $account->parent->name }}"
                                            @else
                                            data-main-name="لا يوجد"
                                            @endif
                                            @if ($account->final_account)
                                            data-final-id="{{ $account->final_account }}"
                                            data-final-name="{{ $account->final->name }}"
                                            @else
                                            data-final-name="لا يوجد"
                                            @endif
                                        >
                                            <i class="fa fa-edit"></i>
                                            <span class="d-sm-none d-lg-inline">تعديل</span>
                                        </button>  --}}
                                    @endpermission
                                    @permission('accounts-delete')
                                        <button class="btn btn-danger"
                                            data-form="#delete-account-form"
                                            data-toggle="confirm"
                                            data-title="@lang('accounting::accounts.delete_confirm_title')"
                                            data-text="@lang('accounting::accounts.delete_confirm_text')"
                                        >
                                            <i class="fa fa-trash"></i>
                                            <span class="d-sm-none d-lg-inline">حذف</span>
                                        </button>
                                        {{--  <button class="btn btn-danger show-modal-account"
                                            data-action="{{ route('accounts.destroy', $account) }}"
                                            data-method="DELETE"
                                            data-id="{{ $account->id }}"
                                            data-name="{{ $account->name }}"
                                            data-view="confirm-delete"
                                            data-type="{{ $account->type }}"
                                            data-side="{{ $account->side }}"
                                            @if ($account->main_account)
                                            data-main-id="{{ $account->main_account }}"
                                            data-main-name="{{ $account->parent->name }}"
                                            @else
                                            data-main-name="لا يوجد"
                                            @endif
                                            @if ($account->final_account)
                                            data-final-id="{{ $account->final_account }}"
                                            data-final-name="{{ $account->final->name }}"
                                            @else
                                            data-final-name="لا يوجد"
                                            @endif
                                        >
                                            <i class="fa fa-trash"></i>
                                            <span class="d-sm-none d-lg-inline">حذف</span>
                                        </button>  --}}
                                    @endpermission
                                </div>
                                <form id="delete-account-form" action="{{ route('accounts.destroy', $account) }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    </table>
                @endslot
            @endcomponent
            @if($account->isPrimary())
                @component('accounting::components.tab-content')
                    @slot('id', 'accounts')
                    @slot('content')
                        <table class="table table-bordered table-striped datatable">
                            <thead>
                                <th>@lang('accounting::global.id')</th>
                                <th>@lang('accounting::global.name')</th>
                                <th>@lang('accounting::global.balance')</th>
                                <th>@lang('accounting::global.options')</th>
                            </thead>
                            <tbody>
                                @foreach ($account->children->sortBy('number') as $child)
                                    <tr>
                                        <td>{{ $child->number }}</td>
                                        <td>{{ $child->name }}</td>
                                        <td>{{ $child->balance() }}</td>
                                        <td class="btn-group">
                                            @permission('accounts-read')
                                                <a href="{{ route('accounts.show', $child) }}" class="btn btn-primary">
                                                    <i class="fa fa-eye"></i>
                                                    <span class="d-sm-none d-lg-inline">عرض</span>
                                                </a>
                                                {{--  <button class="btn btn-info show-modal-account"
                                                    data-id="{{ $child->id }}"
                                                    data-name="{{ $child->name }}"
                                                    data-view="preview"
                                                    data-type="{{ $child->type }}"
                                                    data-side="{{ $child->side }}"
                                                    @if ($child->main_account)
                                                    data-main-id="{{ $child->main_account }}"
                                                    data-main-name="{{ $child->parent->name }}"
                                                    @else
                                                    data-main-name="@lang('accounting::global.not_exists')"
                                                    @endif
                                                    @if ($child->final_account)
                                                    data-final-id="{{ $child->final_account }}"
                                                    data-final-name="{{ $child->final->name }}"
                                                    @else
                                                    data-final-name="@lang('accounting::global.not_exists')"
                                                    @endif
                                                >
                                                    <i class="fa fa-list"></i>
                                                    <span class="d-sm-none d-lg-inline">@lang('accounting::global.summary')</span>
                                                </button>  --}}
                                            @endpermission
                                            {{--  @if ($child->isSecondary())  --}}
                                                @permission('accounts-update')
                                                    <a href="{{ route('accounts.edit', $child) }}" class="btn btn-warning">
                                                        <i class="fa fa-edit"></i>
                                                        <span class="d-sm-none d-lg-inline">@lang('accounting::global.edit')</span>
                                                    </a>
                                                {{--  <button class="btn btn-warning show-modal-account"
                                                    data-action="{{ route('accounts.update', $child) }}"
                                                    data-method="PUT"
                                                    data-id="{{ $child->id }}"
                                                    data-name="{{ $child->name }}"
                                                    data-view="update"
                                                    data-type="{{ $child->type }}"
                                                    data-side="{{ $child->side }}"
                                                    @if ($child->main_account)
                                                    data-main-id="{{ $child->main_account }}"
                                                    data-main-name="{{ $child->parent->name }}"
                                                    @else
                                                    data-main-name="@lang('accounting::global.not_exists')"
                                                    @endif
                                                    @if ($child->final_account)
                                                    data-final-id="{{ $child->final_account }}"
                                                    data-final-name="{{ $child->final->name }}"
                                                    @else
                                                    data-final-name="@lang('accounting::global.not_exists')"
                                                    @endif
                                                    @if ($account->id == Modules\Accounting\Models\Account::ACCOUNT_CUSTOMERS && $child->accountable)
                                                    data-address="{{ $child->accountable->address }}"
                                                    data-phones="{{ $child->accountable->phones }}"
                                                    @endif
                                                >
                                                    <i class="fa fa-edit"></i>
                                                    <span class="d-sm-none d-lg-inline">@lang('accounting::global.edit')</span>
                                                </button>  --}}
                                                @endpermission
                                                @permission('accounts-delete')
                                                {{--  <button class="btn btn-danger show-modal-account"
                                                    data-action="{{ route('accounts.destroy', $child) }}"
                                                    data-method="DELETE"
                                                    data-id="{{ $child->id }}"
                                                    data-name="{{ $child->name }}"
                                                    data-view="confirm-delete"
                                                    data-type="{{ $child->type }}"
                                                    data-side="{{ $child->side }}"
                                                    @if ($child->main_account)
                                                    data-main-id="{{ $child->main_account }}"
                                                    data-main-name="{{ $child->parent->name }}"
                                                    @else
                                                    data-main-name="@lang('accounting::global.not_exists')"
                                                    @endif
                                                    @if ($child->final_account)
                                                    data-final-id="{{ $child->final_account }}"
                                                    data-final-name="{{ $child->final->name }}"
                                                    @else
                                                    data-final-name="@lang('accounting::global.not_exists')"
                                                    @endif
                                                >
                                                    <i class="fa fa-trash"></i>
                                                    <span class="d-sm-none d-lg-inline">@lang('accounting::global.delete')</span>
                                                </button>  --}}
                                                @endpermission
                                            {{--  @endif  --}}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endslot
                @endcomponent
                @component('accounting::components.tab-content')
                    @slot('id', 'account-add')
                    @slot('content')
                        <form class="accountForm" action="{{ route('accounts.store') }}" method="POST">
                            @csrf
                            <div class="form-group row">
                                <div class="col">
                                    <label>@lang('accounting::global.name')</label>
                                    <input class="form-control name" autocomplete type="text" name="name" id="name" placeholder="الاسم" required>
                                </div>
                                @if ($account->id == Modules\Accounting\Models\Account::ACCOUNT_CUSTOMERS)
                                    <div class="col">
                                        <label>@lang('accounting::global.address')</label>
                                        <input class="form-control name" autocomplete="off" type="text" name="address" placeholder="@lang('accounting::global.address')" required />
                                    </div>
                                    
                                    <div class="col">
                                        <label>@lang('accounting::global.phone')</label>
                                        <input class="form-control" autocomplete="off" type="number" name="phones" placeholder="@lang('accounting::global.phone')" required />
                                    </div>
                                @endif
                            </div>
                            <div class="row form-group">
                                <div class="col">
                                    <label>@lang('accounting::global.type')</label>
                                    <select class="form-control type" name="type" id="type" required>
                                        @foreach (array_keys(\Modules\Accounting\Models\Account::TYPES) as $type)
                                        <option value="{{ $type }}">{{ \Modules\Accounting\Models\Account::TYPES[$type] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col">
                                    <label>@lang('accounting::global.side')</label>
                                    <select class="form-control side" name="side" id="side" required>
                                        @foreach (array_keys(\Modules\Accounting\Models\Account::SIDES) as $side)
                                        <option value="{{ $side }}">{{ \Modules\Accounting\Models\Account::SIDES[$side] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>@lang('accounting::accounts.final')</label>
                                <select class="form-control select2 final_account" name="final_account" id="final_account" required>
                                    <option>@lang('accounting::global.not_exists')</option>
                                    @component('accounting::accounts._options')
                                    @slot('account', finalAccount())
                                    @endcomponent
                                </select>
                            </div>
                            <div class="form-group">
                                <input type="hidden" name="main_account" value="{{ $account->id }}" />
                                <button type="submit" class="btn btn-primary">@lang('accounting::global.save')</button>
                            </div>
                            
                        </form>
                    @endslot
                @endcomponent
            @else
                @component('accounting::components.tab-content')
                    @slot('id', 'debts')
                    @slot('content')
                        <form action="" method="GET" class="form-inline guide-advanced-search">
                            @csrf
                            <div class="form-group mr-2">
                                <label>
                                    <i class="fa fa-cogs"></i>
                                    <span>@lang('accounting::global.search_advanced')</span>
                                </label>
                            </div>
                            <div class="form-group mr-2">
                                <label for="from-date">@lang('accounting::global.from')</label>
                                <input type="date" name="debts_from_date" id="from-date" value="{{ $debts_from_date }}" class="form-control">
                            </div>
                            <div class="form-group mr-2">
                                <label for="to-date">@lang('accounting::global.to')</label>
                                <input type="date" name="debts_to_date" id="to-date" value="{{ $debts_to_date }}" class="form-control">
                            </div>
                            <input type="hidden" name="credits_from_date" value="{{ $credits_from_date }}">
                            <input type="hidden" name="credits_to_date" value="{{ $credits_to_date }}">
                            <button type="submit" class="btn btn-primary">
                                <span>@lang('accounting::global.search')</span>
                                <i class="fa fa-search"></i>
                            </button>
                        </form>
                        <div class="table-wrapper">
                            <table id="debts-table" class="table table-bordered table-condensed table-striped datatable">
                                <thead>
                                    <tr>
                                        <th>@lang('accounting::global.date')</th>
                                        <th>@lang('accounting::accounts.details')</th>
                                        <th>@lang('accounting::global.to')</th>
                                        <th>@lang('accounting::global.amount')</th>
                                        <th>@lang('accounting::global.total')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $total = 0;
                                    @endphp
                                    @foreach ($debts as $entry)
                                    @php $total += $entry->pivot->amount; @endphp
                                    <tr>
                                        <td>{{ $entry->getDate() }}</td>
                                        <td>{{ $entry->details}}</td>
                                        <td>
                                            @if(count($entry->credits()) == 1)
                                            @lang('accounting::entries.to_account') {{ $entry->credits()->first()->name }}
                                            @else
                                            <div>@lang('accounting::entries.to_accounts')</div>
                                            @foreach($entry->credits() as $amount)
                                            <div>@lang('accounting::global.account') {{ $amount->name}}</div>
                                            @endforeach
                                            @endif
                                        </td>
                                        <td>{{ number_format($entry->pivot->amount, 2) }}</td>
                                        <td>{{ number_format($total, 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <th colspan="3">@lang('accounting::global.total')</th>
                                    <th>{{ number_format($debts->sum('pivot.amount'), 2) }}</th>
                                    <td>{{ number_format($total, 2) }}</td>
                                </tfoot>
                            </table>
                        </div>
                    @endslot
                @endcomponent
                @component('accounting::components.tab-content')
                    @slot('id', 'credits')
                    @slot('content')
                        <form action="" method="GET" class="form-inline guide-advanced-search">
                            @csrf
                            <div class="form-group mr-2">
                                <label>
                                    <i class="fa fa-cogs"></i>
                                    <span>@lang('accounting::global.search_advanced')</span>
                                </label>
                            </div>
                            <div class="form-group mr-2">
                                <label for="from-date">@lang('accounting::global.from')</label>
                                <input type="date" name="credits_from_date" id="from-date" value="{{ $credits_from_date }}" class="form-control">
                            </div>
                            <div class="form-group mr-2">
                                <label for="to-date">@lang('accounting::global.to')</label>
                                <input type="date" name="credits_to_date" id="to-date" value="{{ $credits_to_date }}" class="form-control">
                            </div>
                            <input type="hidden" name="debts_from_date" value="{{ $debts_from_date }}">
                            <input type="hidden" name="debts_to_date" value="{{ $debts_to_date }}">
                            <button type="submit" class="btn btn-primary">
                                <span>@lang('accounting::global.search')</span>
                                <i class="fa fa-search"></i>
                            </button>
                        </form>
                        <div class="table-wrapper">
                            <table id="credits-table" class="table table-bordered table-condensed table-striped datatable">
                                <thead>
                                    <tr>
                                        <th>@lang('accounting::global.date')</th>
                                        <th>@lang('accounting::accounts.details')</th>
                                        <th>@lang('accounting::global.from')</th>
                                        <th>@lang('accounting::global.amount')</th>
                                        <th>@lang('accounting::global.total')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $total = 0;
                                    @endphp
                                    @foreach ($credits as $entry)
                                    @php $total += $entry->pivot->amount; @endphp
                                    <tr>
                                        <td>{{ $entry->getDate() }}</td>
                                        <td>{{ $entry->details}}</td>
                                        <td>
                                            @if(count($entry->debts()) == 1)
                                            @lang('accounting::entries.from_account') {{ $entry->debts()->first()->name }}
                                            @else
                                            <div>@lang('accounting::entries.from_accounts')</div>
                                            @foreach($entry->debts() as $amount)
                                            <div>@lang('accounting::global.account') {{ $amount->name}}</div>
                                            @endforeach
                                            @endif
                                        </td>
                                        <td>{{ number_format($entry->pivot->amount, 2) }}</td>
                                        <td>{{ number_format($total, 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <th colspan="3">@lang('accounting::global.total')</th>
                                    <th>{{ number_format($credits->sum('pivot.amount'), 2) }}</th>
                                    <td>{{ number_format($total, 2) }}</td>
                                </tfoot>
                            </table>
                        </div>
                    @endslot
                @endcomponent
                @component('accounting::components.tab-content')
                    @slot('id', 'costs')
                    @slot('content')
                        <table id="debts-table" class="table table-bordered table-condensed table-striped datatable">
                            <thead>
                                <tr>
                                    <th>@lang('accounting::global.id')</th>
                                    <th>@lang('accounting::global.name')</th>
                                    <th>@lang('accounting::global.options')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($costs as $center)
                                    <tr>
                                        <td>{{ $center->id }}</td>
                                        <td>{{ $center->name }}</td>
                                        <td>
                                            <div class="btn-group">
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
                                            </div>
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
                        <table id="debts-table" class="table table-bordered table-condensed table-striped datatable">
                            <thead>
                                <tr>
                                    <th>@lang('accounting::global.id')</th>
                                    <th>@lang('accounting::global.name')</th>
                                    <th>@lang('accounting::global.options')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($profits as $center)
                                    <tr>
                                        <td>{{ $center->id }}</td>
                                        <td>{{ $center->name }}</td>
                                        <td>
                                            <div class="btn-group">
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
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endslot
                @endcomponent
            @endif
        @endslot
    @endcomponent
@endpush