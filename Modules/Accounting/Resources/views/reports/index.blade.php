@extends('accounting::layouts.master',[
    'title' => 'التقارير',
    'accounting_modals' => [
        // 'account'
    ],
    // 'models_js' => ['Account', 'Accounts', 'Entry', 'Entries'],
    'select2' => true,
    'crumbs' => [
        ['#', 'التقارير']
    ],
])

@push('content')
    @permission('years-print')
        @component('accounting::components.widget')
            @slot('collapsed', true)
            @slot('noPadding', true)
            @slot('widgets', ['maximize', 'collapse'])
            @slot('title', 'السنوات المالية')
            @slot('body')
                <table class="table table-striped">
                    <tbody>
                    <tr>
                        <td>
                            <h3>القوائم المالية</h3>
                            <form id="form-years-lists" class="form-inline">
                                <div class="form-group mr-2">
                                    <label>السنة</label>
                                    <select name="year_id" class="form-control">
                                        @foreach ($years as $year)
                                            <option value="{{ $year->id }}" {{ !is_null(year()) ? (yearId() == $year->id ? 'selected' : '') : '' }}>{{ $year->id }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group mr-2">
                                    <label>القائمة</label>
                                    <select name="list" class="form-control">
                                        <option value="{{ route('years.income_statement', ':year_id') }}">@lang('accounting::lists.income_statement')</option>
                                        <option value="{{ route('years.trial_balance', ['year' => ':year_id', 'by' => 'totals']) }}">@lang('accounting::lists.trial_balance_by_totals')</option>
                                        <option value="{{ route('years.trial_balance', ['year' => ':year_id', 'by' => 'balances']) }}">@lang('accounting::lists.trial_balance_by_balances')</option>
                                        <option value="{{ route('years.balance_sheet', ':year_id') }}">@lang('accounting::lists.balance_sheet')</option>
                                    </select>
                                </div>
                                <button type="button" class="btn btn-primary btn-report">
                                    <i class="fa fa-print"></i>
                                    <span>تقرير</span>
                                </button>
                            </form>
                        </td>
                    </tr>
                    </tbody>
                </table>
            @endslot
        @endcomponent
    @endpermission
    @permission('accounts-print|centers-print')
        @component('accounting::components.widget')
            @slot('collapsed', true)
            @slot('noPadding', true)
            @slot('widgets', ['maximize', 'collapse'])
            @slot('title', 'الحسابات والمراكز')
            @slot('body')
                <table class="table table-striped">
                    <tbody>
                        <tr>
                            <td>
                                <h3>كشف حساب</h3>
                                <form id="form-accounts-statement" class="form-inline">
                                    <div class="form-group mr-2">
                                        <label>السنة المالية</label>
                                        <select name="year_id" class="form-control">
                                            @foreach ($years as $year)
                                                <option value="{{ $year->id }}" {{ !is_null(year()) ? (yearId() == $year->id ? 'selected' : '') : '' }}>{{ $year->id }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group mr-2">
                                        <label>الحساب</label>
                                        <select name="account_id" class="form-control accounts">
                                        </select>
                                    </div>
                                    <div class="form-group mr-2">
                                        <label for="from-date">@lang('accounting::global.from')</label>
                                        <input type="date" name="from_date" value="{{ date('Y-m-d') }}" class="form-control">
                                    </div>
                                    <div class="form-group mr-2">
                                        <label for="to-date">@lang('accounting::global.to')</label>
                                        <input type="date" name="to_date" value="{{ date('Y-m-d') }}" class="form-control">
                                    </div>
                                    <button type="button" class="btn btn-primary btn-report">
                                        <i class="fa fa-print"></i>
                                        <span>تقرير</span>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <h3>تقيم اداء المراكز</h3>
                                <form id="form-center" class="form-inline">
                                    <div class="form-group mr-2">
                                        <label>السنة المالية</label>
                                        <select name="year_id" class="form-control">
                                            @foreach ($years as $year)
                                                <option value="{{ $year->id }}" {{ !is_null(year()) ? (yearId() == $year->id ? 'selected' : '') : '' }}>{{ $year->id }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group mr-2">
                                        <label>المركز</label>
                                        <select name="center_id" class="form-control">
                                            @foreach ($centers as $center)
                                                <option value="{{ $center->id }}">{{ $center->displayName() }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group mr-2">
                                        <label for="from-date">@lang('accounting::global.from')</label>
                                        <input type="date" name="from_date" value="{{ date('Y-m-d') }}" class="form-control">
                                    </div>
                                    <div class="form-group mr-2">
                                        <label for="to-date">@lang('accounting::global.to')</label>
                                        <input type="date" name="to_date" value="{{ date('Y-m-d') }}" class="form-control">
                                    </div>
                                    <button type="button" class="btn btn-primary btn-report">
                                        <i class="fa fa-print"></i>
                                        <span>تقرير</span>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <tr>
                            <td>

                            </td>
                        </tr>
                    </tbody>
                </table>
            @endslot
        @endcomponent
    @endpermission
    @permission('safes-print')
        @component('accounting::components.widget')
            @slot('collapsed', true)
            @slot('noPadding', true)
            @slot('widgets', ['maximize', 'collapse'])
            @slot('title', 'الخزن')
            @slot('body')
                <table class="table table-striped">
                    <tbody>
                    <tr>
                        <td>
                            <h3>حركة الخزنة</h3>
                            <form id="form-safe" class="form-inline">
                                <div class="form-group mr-2">
                                    <label>السنة المالية</label>
                                    <select name="year_id" class="form-control">
                                        @foreach ($years as $year)
                                            <option value="{{ $year->id }}" {{ !is_null(year()) ? (yearId() == $year->id ? 'selected' : '') : '' }}>{{ $year->id }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group mr-2">
                                    <label for="from-date">@lang('accounting::global.from')</label>
                                    <input type="date" name="from_date" value="{{ date('Y-m-d') }}" class="form-control">
                                </div>
                                <div class="form-group mr-2">
                                    <label for="to-date">@lang('accounting::global.to')</label>
                                    <input type="date" name="to_date" value="{{ date('Y-m-d') }}" class="form-control">
                                </div>
                                @if (auth()->user()->isAbleTo('safes-read'))
                                    <div class="form-group mr-2">
                                        <label for="safe_id">الخزن</label>
                                        <select name="safe_id" id="safe_id" class="form-control">
                                            @foreach ($safes as $s)
                                                <option value="{{ $s->id }}">{{ $s->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
                                <button type="button" class="btn btn-primary btn-report">
                                    <i class="fa fa-print"></i>
                                    <span>تقرير</span>
                                </button>
                            </form>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h3>حركة الخزن</h3>
                            <form id="form-safes" class="form-inline">
                                <div class="form-group mr-2">
                                    <label>السنة المالية</label>
                                    <select name="year_id" class="form-control">
                                        @foreach ($years as $year)
                                            <option value="{{ $year->id }}" {{ !is_null(year()) ? (yearId() == $year->id ? 'selected' : '') : '' }}>{{ $year->id }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group mr-2">
                                    <label for="from-date">@lang('accounting::global.from')</label>
                                    <input type="date" name="from_date" value="{{ date('Y-m-d') }}" class="form-control">
                                </div>
                                <div class="form-group mr-2">
                                    <label for="to-date">@lang('accounting::global.to')</label>
                                    <input type="date" name="to_date" value="{{ date('Y-m-d') }}" class="form-control">
                                </div>
                                {{--                            @if (auth()->user()->isAbleTo('safes-read'))--}}
                                {{--                                <div class="form-group mr-2">--}}
                                {{--                                    <label for="safe_id">@lang('accounting::global.safe')</label>--}}
                                {{--                                    <select name="safe_id" id="safe_id" class="form-control">--}}
                                {{--                                        <option value="all">@lang('accounting::global.all')</option>--}}
                                {{--                                        @foreach ($safes as $s)--}}
                                {{--                                            <option value="{{ $s->id }}">{{ $s->name }}</option>--}}
                                {{--                                        @endforeach--}}
                                {{--                                    </select>--}}
                                {{--                                </div>--}}
                                {{--                            @endif--}}
                                <div class="form-group mr-2">
                                    <label for="user_id">@lang('accounting::global.user')</label>
                                    <select name="user_id" id="user_id" class="form-control">
                                        <option value="all">@lang('accounting::global.all')</option>
                                        @foreach ($users as $s)
                                            <option value="{{ $s->id }}">{{ $s->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="button" class="btn btn-primary btn-report">
                                    <i class="fa fa-print"></i>
                                    <span>تقرير</span>
                                </button>
                            </form>
                        </td>
                    </tr>
                    <tr>
                        <td>

                        </td>
                    </tr>
                    </tbody>
                </table>
            @endslot
        @endcomponent
    @endpermission
    @component('accounting::components.widget')
        @slot('collapsed', true)
        @slot('noPadding', true)
        @slot('widgets', ['maximize', 'collapse'])
        @slot('title')
            @lang('accounting::global.vouchers')
            <span>|</span>
            @lang('accounting::global.expenses')
            <span>|</span>
            @lang('accounting::global.transfers')
        @endslot
        @slot('body')
            <table class="table table-striped">
                <tbody>
                    <tr>
                        <td>
                            <h3>@lang('accounting::global.vouchers')</h3>
                            <form id="form-vouchers">
                                <div class="form-group">
                                    <div class="form-inline">
                                        <div class="form-group mr-2">
                                            <label>السنة المالية</label>
                                            <select name="year_id" class="form-control">
                                                @foreach ($years as $year)
                                                    <option value="{{ $year->id }}" {{ !is_null(year()) ? (yearId() == $year->id ? 'selected' : '') : '' }}>{{ $year->id }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group mr-2">
                                            <label for="type">@lang('accounting::global.type')</label>
                                            <select name="type" id="type" class="form-control" required>
                                                <option value="all">@lang('accounting::global.all')
                                                @foreach (Modules\Accounting\Models\Voucher::TYPES as $type)
                                                    <option value="{{ $type }}">
                                                        {{ Modules\Accounting\Models\Voucher::getStaticType($type) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group mr-2">
                                            <label for="status">@lang('accounting::global.status')</label>
                                            <select class="form-control type" name="status" id="status">
                                                <option value="all">@lang('accounting::global.all')</option>
                                                @foreach (App\Traits\Statusable::$STATUSES as $value => $status)
                                                    <option value="{{ $status }}">
                                                        @lang('global.statuses.' . $status)
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="form-inline">
                                        <div class="form-group mr-2">
                                            <label for="from-date">@lang('accounting::global.from')</label>
                                            <input type="date" name="from_date" value="{{ date('Y-m-d') }}" class="form-control">
                                        </div>
                                        <div class="form-group mr-2">
                                            <label for="to-date">@lang('accounting::global.to')</label>
                                            <input type="date" name="to_date" value="{{ date('Y-m-d') }}" class="form-control">
                                        </div>
                                        <button type="button" class="btn btn-primary btn-report">
                                            <i class="fa fa-print"></i>
                                            <span>تقرير</span>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h3>@lang('accounting::global.expenses')</h3>
                            <form id="form-expenses">
                                <div class="form-group">
                                    <div class="form-inline">
                                        <div class="form-group mr-2">
                                            <label>السنة المالية</label>
                                            <select name="year_id" class="form-control">
                                                @foreach ($years as $year)
                                                    <option value="{{ $year->id }}" {{ !is_null(year()) ? (yearId() == $year->id ? 'selected' : '') : '' }}>{{ $year->id }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group mr-2">
                                            <label for="safe_id">@lang('accounting::global.safe')</label>
                                            <select name="safe_id" id="safe_id" class="form-control">
                                                <option value="all">@lang('accounting::global.all')</option>
                                                @foreach ($safes as $s)
                                                    <option value="{{ $s->id }}">{{ $s->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group mr-2">
                                            <label for="account_id">@lang('accounting::global.account')</label>
                                            <select name="account_id" id="account_id" class="form-control accounts">
                                                <option value="all">@lang('accounting::global.all')</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="form-inline">
                                        <div class="form-group mr-2">
                                            <label for="user_id">@lang('accounting::global.user')</label>
                                            <select name="user_id" id="user_id" class="form-control">
                                                <option value="all">@lang('accounting::global.all')</option>
                                                @foreach ($users as $user)
                                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group mr-2">
                                            <label for="from-date">@lang('accounting::global.from')</label>
                                            <input type="date" name="from_date" value="{{ date('Y-m-d') }}" class="form-control">
                                        </div>
                                        <div class="form-group mr-2">
                                            <label for="to-date">@lang('accounting::global.to')</label>
                                            <input type="date" name="to_date" value="{{ date('Y-m-d') }}" class="form-control">
                                        </div>
                                        <button type="button" class="btn btn-primary btn-report">
                                            <i class="fa fa-print"></i>
                                            <span>تقرير</span>
                                        </button>
                                    </div>
                                </div>

                            </form>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h3>@lang('accounting::global.transfers')</h3>
                            <form id="form-transfers">
                                <div class="form-group">
                                    <div class="form-inline">
                                        <div class="form-group mr-2">
                                            <label>السنة المالية</label>
                                            <select name="year_id" class="form-control">
                                                @foreach ($years as $year)
                                                    <option value="{{ $year->id }}" {{ !is_null(year()) ? (yearId() == $year->id ? 'selected' : '') : '' }}>{{ $year->id }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group mr-2">
                                            <label for="from_account_id">@lang('accounting::accounts.from_account')</label>
                                            <select name="from_account_id" id="from_account_id" class="form-control accounts">
                                                <option value="all">@lang('accounting::global.all')</option>
                                            </select>
                                        </div>
                                        <div class="form-group mr-2">
                                            <label for="to_account_id">@lang('accounting::accounts.to_account')</label>
                                            <select name="to_account_id" id="to_account_id" class="form-control accounts">
                                                <option value="all">@lang('accounting::global.all')</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="form-inline">
                                        <div class="form-group mr-2">
                                            <label for="user_id">@lang('accounting::global.user')</label>
                                            <select name="user_id" id="user_id" class="form-control">
                                                <option value="all">@lang('accounting::global.all')</option>
                                                @foreach ($users as $user)
                                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group mr-2">
                                            <label for="from-date">@lang('accounting::global.from')</label>
                                            <input type="date" name="from_date" value="{{ date('Y-m-d') }}" class="form-control">
                                        </div>
                                        <div class="form-group mr-2">
                                            <label for="to-date">@lang('accounting::global.to')</label>
                                            <input type="date" name="to_date" value="{{ date('Y-m-d') }}" class="form-control">
                                        </div>
                                        <button type="button" class="btn btn-primary btn-report">
                                            <i class="fa fa-print"></i>
                                            <span>تقرير</span>
                                        </button>
                                    </div>
                                </div>

                            </form>
                        </td>
                    </tr>
                    <tr>
                        <td>

                        </td>
                    </tr>
                </tbody>
            </table>
        @endslot
    @endcomponent
    @component('accounting::components.widget')
        @slot('collapsed', true)
        @slot('noPadding', true)
        @slot('widgets', ['maximize', 'collapse'])
        @slot('title')
            @lang('accounting::global.employees')
        @endslot
        @slot('body')
            <table class="table table-striped">
                <tbody>
                <tr>
                    <td>
                        <h3>@lang('accounting::global.transactions')</h3>
                        <form id="form-transactions">
                            <div class="form-group">
                                <div class="form-inline">
                                    <div class="form-group mr-2">
                                        <label>السنة المالية</label>
                                        <select name="year_id" class="form-control">
                                            @foreach ($years as $year)
                                                <option value="{{ $year->id }}" {{ !is_null(year()) ? (yearId() == $year->id ? 'selected' : '') : '' }}>{{ $year->id }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group mr-2">
                                        <label for="employee_id">@lang('accounting::global.employee')</label>
                                        <select name="employee_id" id="employee_id" class="form-control">
                                            <option value="all">@lang('accounting::global.all')</option>
                                            @foreach ($employees as $employee)
                                                <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group mr-2">
                                        <label for="type">@lang('accounting::global.type')</label>
                                        <select name="type" id="type" class="form-control" required>
                                            <option value="all">@lang('accounting::global.all')
                                            @foreach (Modules\Accounting\Models\Transaction::TYPES as $type)
                                                <option value="{{ $type }}">
                                                    {{ Modules\Accounting\Models\Transaction::getStaticType($type) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group mr-2">
                                        <label for="status">@lang('accounting::global.status')</label>
                                        <select class="form-control type" name="status" id="status">
                                            <option value="all">@lang('accounting::global.all')</option>
                                            @foreach (App\Traits\Statusable::$STATUSES as $value => $status)
                                                <option value="{{ $status }}">
                                                    @lang('global.statuses.' . $status)
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-inline">
                                    <div class="form-group mr-2">
                                        <label for="user_id">@lang('accounting::global.user')</label>
                                        <select name="user_id" id="user_id" class="form-control">
                                            <option value="all">@lang('accounting::global.all')</option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group mr-2">
                                        <label for="from-date">@lang('accounting::global.from')</label>
                                        <input type="date" name="from_date" value="{{ date('Y-m-d') }}" class="form-control">
                                    </div>
                                    <div class="form-group mr-2">
                                        <label for="to-date">@lang('accounting::global.to')</label>
                                        <input type="date" name="to_date" value="{{ date('Y-m-d') }}" class="form-control">
                                    </div>
                                    <button type="button" class="btn btn-primary btn-report">
                                        <i class="fa fa-print"></i>
                                        <span>تقرير</span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </td>
                </tr>
                <tr>
                    <td>
                        <h3>@lang('accounting::global.salaries')</h3>
                        <form id="form-salaries">
                            <div class="form-group">
                                <div class="form-inline">
                                    <div class="form-group mr-2">
                                        <label>السنة المالية</label>
                                        <select name="year_id" class="form-control">
                                            @foreach ($years as $year)
                                                <option value="{{ $year->id }}" {{ !is_null(year()) ? (yearId() == $year->id ? 'selected' : '') : '' }}>{{ $year->id }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group mr-2">
                                        <label for="employee_id">@lang('accounting::global.employee')</label>
                                        <select name="employee_id" id="employee_id" class="form-control">
                                            <option value="all">@lang('accounting::global.all')</option>
                                            @foreach ($employees as $employee)
                                                <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group mr-2">
                                        <label for="status">@lang('accounting::global.status')</label>
                                        <select class="form-control type" name="status" id="status">
                                            <option value="all">@lang('accounting::global.all')</option>
                                            @foreach (App\Traits\Statusable::$STATUSES as $value => $status)
                                                <option value="{{ $status }}">
                                                    @lang('global.statuses.' . $status)
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-inline">
                                    <div class="form-group mr-2">
                                        <label for="user_id">@lang('accounting::global.user')</label>
                                        <select name="user_id" id="user_id" class="form-control">
                                            <option value="all">@lang('accounting::global.all')</option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group mr-2">
                                        <label for="from-date">@lang('accounting::global.from')</label>
                                        <input type="date" name="from_date" value="{{ date('Y-m-d') }}" class="form-control">
                                    </div>
                                    <div class="form-group mr-2">
                                        <label for="to-date">@lang('accounting::global.to')</label>
                                        <input type="date" name="to_date" value="{{ date('Y-m-d') }}" class="form-control">
                                    </div>
                                    <button type="button" class="btn btn-primary btn-report">
                                        <i class="fa fa-print"></i>
                                        <span>تقرير</span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </td>
                </tr>
                <tr>
                    <td>

                    </td>
                </tr>
                </tbody>
            </table>
        @endslot
    @endcomponent
@endpush
@push('foot')
    <script>
        $(function(){
            let _accounts_options = ``;
            let _accounts = @json($accounts);
            for(let index = 0; index < _accounts.length; index++)
            {
                let _account = _accounts[index];
                _accounts_options += `
                    <option value="` + _account.id + `">` + _account.number + `-` + _account.name + `</option>
                `;
            }
            $('select.accounts').append(_accounts_options)

            $('#form-years-lists .btn-report').click(function(){
                let form = $(this).closest('form')
                let field_year_id = $(form.find('select[name=year_id]'))
                let field_list = $(form.find('select[name=list]'))
                let url = field_list.val()
                url = url.replace(':year_id', field_year_id.val())
                window.location.href = url
            })
            $('#form-accounts-statement .btn-report').click(function(){
                let form = $(this).closest('form')
                let field_account_id = $('#form-accounts-statement select[name=account_id]')
                let field_from_date = $('#form-accounts-statement input[name=from_date]')
                let field_to_date = $('#form-accounts-statement input[name=to_date]')
                let url = "{{ route('accounts.show', ':account_id') }}";
                let field_year_id = form.find('select[name=year_id]')
                url = url.replace(':account_id', field_account_id.val())
                url += '?from_date=' + field_from_date.val()
                url += '&to_date=' + field_to_date.val()
                url += '&view=statement'
                url += '&year_id=' + field_year_id.val()
                window.location.href = url
            })
            $('#form-safe .btn-report').click(function(){
                let form = $(this).closest('form')
                let field_safe_id = form.find('select[name=safe_id]')
                let field_from_date = form.find('input[name=from_date]')
                let field_to_date = form.find('input[name=to_date]')
                let field_year_id = form.find('select[name=year_id]')
                let url = "{{ route('accounting.reports.safe', ':safe_id') }}";
                url = url.replace(':safe_id', field_safe_id.val())
                url += '?from_date=' + field_from_date.val()
                url += '&to_date=' + field_to_date.val()
                url += '&year_id=' + field_year_id.val()

                window.location.href = url
            })
            $('#form-safes .btn-report').click(function(){
                let form = $(this).closest('form')
                let field_year_id = form.find('select[name=year_id]')
                // let field_safe_id = form.find('select[name=safe_id]')
                let field_user_id = form.find('select[name=user_id]')
                let field_from_date = form.find('input[name=from_date]')
                let field_to_date = form.find('input[name=to_date]')
                let url = "{{ route('accounting.reports.safes') }}";
                url += '?user_id=' + field_user_id.val()
                url += '&year_id=' + field_year_id.val()
                // url += '&safe_id=' + field_safe_id.val()
                url += '&from_date=' + field_from_date.val()
                url += '&to_date=' + field_to_date.val()
                url += '&layout=print'
                window.location.href = url
            })
            $('#form-center .btn-report').click(function(){
                let form = $(this).closest('form')
                let field_center_id = form.find('select[name=center_id]')
                let field_from_date = form.find('input[name=from_date]')
                let field_to_date = form.find('input[name=to_date]')
                let field_year_id = form.find('select[name=year_id]')
                let url = "{{ route('accounting.reports.center', ':center_id') }}";
                url = url.replace(':center_id', field_center_id.val())
                url += '?from_date=' + field_from_date.val()
                url += '&to_date=' + field_to_date.val()
                url += '&year_id=' + field_year_id.val()
                // url += '&layout=print'
                window.location.href = url
            })
            $('#form-vouchers .btn-report').click(function(){
                let form = $(this).closest('form')
                let field_from_date = form.find('input[name=from_date]')
                let field_to_date = form.find('input[name=to_date]')
                let field_year_id = form.find('select[name=year_id]')
                let field_type = form.find('select[name=type]')
                let field_status = form.find('select[name=status]')
                let url = "{{ route('accounting.reports.vouchers') }}";
                url += '?from_date=' + field_from_date.val()
                url += '&to_date=' + field_to_date.val()
                url += '&year_id=' + field_year_id.val()
                url += '&type=' + field_type.val()
                url += '&status=' + field_status.val()
                // url += '&layout=print'
                window.location.href = url
            })
            $('#form-expenses .btn-report').click(function(){
                let form = $(this).closest('form')
                let field_user_id = form.find('select[name=user_id]')
                let field_year_id = form.find('select[name=year_id]')
                let field_safe_id = form.find('select[name=safe_id]')
                let field_account_id = form.find('select[name=account_id]')
                let field_from_date = form.find('input[name=from_date]')
                let field_to_date = form.find('input[name=to_date]')
                let url = "{{ route('accounting.reports.expenses') }}";
                url += '?user_id=' + field_user_id.val()
                url += '&year_id=' + field_year_id.val()
                url += '&safe_id=' + field_safe_id.val()
                url += '&account_id=' + field_account_id.val()
                url += '&from_date=' + field_from_date.val()
                url += '&to_date=' + field_to_date.val()
                window.location.href = url
            })
            $('#form-transfers .btn-report').click(function(){
                let form = $(this).closest('form')
                let field_user_id = form.find('select[name=user_id]')
                let field_year_id = form.find('select[name=year_id]')
                let field_from_account_id = form.find('select[name=from_account_id]')
                let field_to_account_id = form.find('select[name=to_account_id]')
                let field_from_date = form.find('input[name=from_date]')
                let field_to_date = form.find('input[name=to_date]')
                let url = "{{ route('accounting.reports.transfers') }}";
                url += '?user_id=' + field_user_id.val()
                url += '&year_id=' + field_year_id.val()
                url += '&from_account_id=' + field_from_account_id.val()
                url += '&to_account_id=' + field_to_account_id.val()
                url += '&from_date=' + field_from_date.val()
                url += '&to_date=' + field_to_date.val()
                window.location.href = url
            })
            $('#form-transactions .btn-report').click(function(){
                let form = $(this).closest('form')
                let field_from_date = form.find('input[name=from_date]')
                let field_to_date = form.find('input[name=to_date]')
                let field_year_id = form.find('select[name=year_id]')
                let field_employee_id = form.find('select[name=employee_id]')
                let field_type = form.find('select[name=type]')
                let field_status = form.find('select[name=status]')
                let url = "{{ route('accounting.reports.transactions') }}";
                url += '?from_date=' + field_from_date.val()
                url += '&to_date=' + field_to_date.val()
                url += '&year_id=' + field_year_id.val()
                url += '&employee_id=' + field_employee_id.val()
                url += '&type=' + field_type.val()
                url += '&status=' + field_status.val()
                // url += '&layout=print'
                window.location.href = url
            })
            $('#form-salaries .btn-report').click(function(){
                let form = $(this).closest('form')
                let field_from_date = form.find('input[name=from_date]')
                let field_to_date = form.find('input[name=to_date]')
                let field_year_id = form.find('select[name=year_id]')
                let field_employee_id = form.find('select[name=employee_id]')
                let field_status = form.find('select[name=status]')
                let url = "{{ route('accounting.reports.salaries') }}";
                url += '?from_date=' + field_from_date.val()
                url += '&to_date=' + field_to_date.val()
                url += '&year_id=' + field_year_id.val()
                url += '&employee_id=' + field_employee_id.val()
                url += '&status=' + field_status.val()
                // url += '&layout=print'
                window.location.href = url
            })
        })
    </script>
@endpush
