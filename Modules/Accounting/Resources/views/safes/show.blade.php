@extends('accounting::layouts.master' ,[
    'title' => $safe->name,
    'accounting_modals' => ['safe'], 
    'datatable' => true,
    'crumbs' => [
        [route('safes.index'), __('accounting::global.safes')],
        ['#', $safe->name],
    ],
])
@push('content')
    <div class="card card-primary card-outline">
        <div class="card-header">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>@lang('accounting::global.id')</th>
                        <th>@lang('accounting::global.name')</th>
                        <th>@lang('accounting::global.balance')</th>
                        <th>@lang('accounting::global.options')</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $safe->id }}</td>
                        <td>{{ $safe->name }}</td>
                        <td>{{ $safe->balance(true) }}</td>
                        <td>
                            <div class="btn-group">
                                @permission('safes-read')
                                {!! safeButton($safe, 'preview') !!}
                                @endpermission
                                @permission('accounts-read')
                                <a href="{{ route('accounts.show', ['account' => $safe->id, 'view' => 'statement']) }}" class="btn btn-default">
                                    <i class="fa fa-list"></i>
                                    <span class="d-sm-none d-lg-inline">@lang('accounting::accounts.statement')</span>
                                </a>
                                @endpermission
                                @permission('safes-update')
                                {!! safeButton($safe, 'update') !!}
                                @endpermission
                                @permission('safes-delete')
                                {!! safeButton($safe, 'delete') !!}
                                @endpermission
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card card-primary card-outline">
        <div class="card-header p-b-0 border-bottom-0">
            <div class="card-title">
                <ul class="nav nav-tabs" id="tabs-tab" role="tablist">
                    <li class="nav-item"><a href="#tab-debts" class="nav-link {{ $active_tab == 'debts' || $active_tab == 'default' ? 'active' : '' }}" data-toggle="tab" aria-expanded="true">
                        <span>@lang('accounting::safes.debts')</span>
                    </a></li>
                    <li class="nav-item"><a href="#tab-credits" class="nav-link {{ $active_tab == 'credits' ? 'active' : '' }}" data-toggle="tab" aria-expanded="true">
                        <span>@lang('accounting::safes.credits')</span>
                    </a></li>
                    <li class="nav-item"><a href="#tab-expenses" class="nav-link {{ $active_tab == 'expenses' ? 'active' : '' }}" data-toggle="tab" aria-expanded="true">
                        <span>@lang('accounting::global.expenses')</span>
                    </a></li>
                </ul>
            </div>
            <div class="card-tools btn-group">
                {{--  @permission('accounts-read')
                <a href="{{ route('accounts.show', ['account' => $safe->id, 'view' => 'statement']) }}" class="btn btn-default">
                    <i class="fa fa-list"></i>
                    <span class="d-sm-none d-lg-inline">@lang('accounting::accounts.statement')</span>
                </a>
                @endpermission  --}}
                {{--  @permission('expenses-create')
                <button class="btn btn-primary"
                    data-modal="expense"
                    data-safe-id="{{ $safe->id }}" 
                    data-max="{{ $safe->balance() }}"
                    >
                        <i class="fa fa-plus"></i>
                        <span>@lang('accounting::safes.add_expense')</span>
                    </a></button>
                @endpermission
                @permission('transfers-create')
                <button class="btn btn-info"
                    data-modal="transfer"
                    data-account-id="{{ $safe->id }}" 
                    data-max="{{ $safe->balance() }}">
                        <i class="fa fa-plus"></i>
                        <span>@lang('accounting::safes.add_transfer')</span>
                    </a></button>
                @endpermission  --}}
            </div>
        </div>
        <div class="card-body">
            <div class="tab-content">
                <div class="tab-pane {{ $active_tab == 'debts' || $active_tab == 'default' ? 'active' : '' }}" id="tab-debts">
                    <div>
                        <legend>
                            <a href="#debts-search" data-toggle="collapse" clas="btn btn-secondary btn-sm mb-10 d-inline-block">
                                <i class="fa fa-cogs"></i>
                                <span>بحث متقدم</span>
                            </a>
                        </legend>
                        <div id="debts-search" class="collapse well">
                            <form action="" method="GET" class="form-inline d-inline-block">
                                @csrf
                                <label for="debts-from-date">من</label>
                                <input type="date" name="from_date" id="debts-from-date" value="{{ $from_date }}" class="form-control">
                                <label for="debts-to-date">الى</label>
                                <input type="date" name="to_date" id="debts-to-date" value="{{ $to_date }}" class="form-control">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-search"></i>
                                    <span></span>
                                </button>
                                <input type="hidden" name="account_id" value="{{ $safe->id }}">
                            </form>
                        </div>
                    </div>
                    <table id="debts-table" class="datatable table table-bordered table-hover text-center">
                        <thead>
                            <tr>
                                <th>@lang('accounting::global.id')</th>
                                <th>@lang('accounting::global.account')</th>
                                <th>@lang('accounting::global.amount')</th>
                                <th>@lang('accounting::global.date')</th>
                                <th>@lang('accounting::global.options')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($debts as $debt)
                                <tr>
                                    <td>{{ $debt->id }}</td>
                                    <td>{{ $debt->to->name }}</td>
                                    <td>{{ $debt->amount }}</td>
                                    <td>{{ $debt->created_at->format('Y-m-d') }}</td>
                                    <td>
                                        <div class="btn-group">
                                            @permission('transfers-read')
                                            {!! transferButton($debt, 'preview') !!}
                                            @endpermission
                                            @permission('transfers-update')
                                            {!! transferButton($debt, 'update') !!}
                                            @endpermission
                                            @permission('transfers-delete')
                                            {!! transferButton($debt, 'delete') !!}
                                            @endpermission
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="tab-pane {{ $active_tab == 'credits' ? 'active' : '' }}" id="tab-credits">
                    <div>
                        <legend>
                            <a href="#credits-search" data-toggle="collapse" clas="btn btn-secondary btn-sm mb-10 d-inline-block">
                                <i class="fa fa-cogs"></i>
                                <span>بحث متقدم</span>
                            </a>
                        </legend>
                        <div id="credits-search" class="collapse well">
                            <form action="" method="GET" class="form-inline d-inline-block">
                                @csrf
                                <label for="credits-from-date">من</label>
                                <input type="date" name="from_date" id="credits-from-date" value="{{ $from_date }}" class="form-control">
                                <label for="credits-to-date">الى</label>
                                <input type="date" name="to_date" id="credits-to-date" value="{{ $to_date }}" class="form-control">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-search"></i>
                                    <span></span>
                                </button>
                                <input type="hidden" name="account_id" value="{{ $safe->id }}">
                            </form>
                        </div>
                    </div>
                    <table id="credits-table" class="datatable table table-bordered table-hover text-center">
                        <thead>
                            <tr>
                                <th>@lang('accounting::global.id')</th>
                                <th>@lang('accounting::global.account')</th>
                                <th>@lang('accounting::global.amount')</th>
                                <th>@lang('accounting::global.date')</th>
                                <th>@lang('accounting::global.options')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($credits as $credit)
                            <tr>
                                <td>{{ $credit->id }}</td>
                                <td>{{ $credit->from->name }}</td>
                                <td>{{ $credit->amount }}</td>
                                <td>{{ $credit->created_at->format('Y-m-d') }}</td>
                                <td>
                                    <div class="btn-group">
                                        @permission('transfers-read')
                                        {!! transferButton($credit, 'preview') !!}
                                        @endpermission
                                        @permission('transfers-update')
                                        {!! transferButton($credit, 'update') !!}
                                        @endpermission
                                        @permission('transfers-delete')
                                        {!! transferButton($credit, 'delete') !!}
                                        @endpermission
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>    
                </div>
                <div class="tab-pane {{ $active_tab == 'expenses' ? 'active' : '' }}" id="tab-expenses">
                    <legend>
                        <a href="#expenses-search" data-toggle="collapse">
                            <i class="fa fa-cogs"></i>
                            <span>بحث متقدم</span>
                        </a>
                    </legend>
                    <div id="expenses-search" class="collapse well">
                        <form action="" method="GET" class="form-inline d-inline-block">
                            @csrf
                            <label for="expenses-from-date">من</label>
                            <input type="date" name="from_date" id="expenses-from-date" value="{{ $from_date }}" class="form-control">
                            <label for="expenses-to-date">الى</label>
                            <input type="date" name="to_date" id="expenses-to-date" value="{{ $to_date }}" class="form-control">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-search"></i>
                                <span></span>
                            </button>
                            <input type="hidden" name="account_id" value="{{ $safe->id }}">
                        </form>
                    </div>
                    <table id="expenses-table" class="datatable table table-bordered table-hover text-center">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>القيمة</th>
                                <th>التفاصيل</th>
                                <th>الموظف</th>
                                <th>التاريخ</th>
                                <th>الخيارات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($expenses as $index=>$expense)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $expense->amount }}</td>
                                <td>{{ str_limit($expense->details, 30) }}</td>
                                <td>{{ $expense->auth()->name }}</td>
                                <td>{{ $expense->created_at->format('Y-m-d') }}</td>
                                <td>
                                    <div class="btn-group">
                                        @permission('expenses-read')
                                        {!! expenseButton($expense, 'preview') !!}
                                        @endpermission
                                        @permission('expenses-update')
                                        {!! expenseButton($expense, 'update') !!}
                                        @endpermission
                                        @permission('expenses-delete')
                                        {!! expenseButton($expense, 'delete') !!}
                                        @endpermission
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="active-tab" name="active_tab" value="{{ $active_tab }}" />
@endpush
@push('foot')
    <script>
        $(function(){
            $('form').on('submit', (e) => {
                $(e.target).append($('input#active-tab'))
            })
        })
        $('.nav.nav-tabs > li > a').click(function(){
            let tab = $(this).attr('href') + "";
            $('input#active-tab').val(tab.substring(5, tab.length))
        })
    </script>
@endpush
