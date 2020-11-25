@extends('layouts.master', [
    'title' => $title,
    'datatable' => true, 
    'crumbs' => [
        [route('offices.index'), 'المكاتب الخارجية'],
        ['#', $title],
    ]
])

@section('content')
<section class="content">
    <div class="card card-primary card-outline card-outline-tabs">
        <div class="card-header p-0 border-bottom-0">
            <ul class="nav nav-tabs" id="tabs-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link {{ ($active_tab == 'details') ? 'active' : '' }}" href="?active_tab=details" role="tab"  aria-selected="true">البيانات</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ ($active_tab == 'contracts') ? 'active' : '' }}" href="?active_tab=contracts" role="tab"  aria-selected="true">العقود</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ ($active_tab == 'cvs') ? 'active' : '' }}" href="?active_tab=cvs" role="tab"  aria-selected="true">السير الذاتيه</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ ($active_tab == 'bills') ? 'active' : '' }}" href="?active_tab=bills" role="tab"  aria-selected="true">الفواتير</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ ($active_tab == 'advances') ? 'active' : '' }}" href="?active_tab=advances" role="tab"  aria-selected="true">السلفيات</a>
                </li>
                {{-- <li class="nav-item">
                    <a class="nav-link {{ ($active_tab == 'returns') ? 'active' : '' }}" href="?active_tab=returns" role="tab"  aria-selected="true">المرتجعات</a>
                </li> --}}
                <li class="nav-item">
                    <a class="nav-link {{ ($active_tab == 'pulls') ? 'active' : '' }}" href="?active_tab=pulls" role="tab"  aria-selected="true">المسحوبات</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">
                        <span>الخيارات</span>
                        <span class="caret"></span>
                    </a>
                    <div class="dropdown-menu" x-placement="top-start">
                        @permission('offices-update')
                            <a href="{{ route('offices.edit', $office) }}" class="dropdown-item">
                                <i class="fa fa-edit"></i>
                                <span>تعديل</span>
                            </a>
                        @endpermission
                        @permission('offices-update')
                            <a href="#" class="dropdown-item" data-toggle="confirm" data-form="#delete-form"
                                data-title='{{ $office->status ? "إلغاء التعاقد" : "تفعيل" }}' data-text="سوف يتم {{ $office->status ? 'الإلغاء التعاقد' : 'التفعيل' }} هل انت متأكد">
                                <i class="fa {{ $office->status ? 'fa-lock' : 'fa-unlock' }}"></i>
                                <span>{{ $office->status ? "إلغاء التعاقد" : "تفعيل" }}</span>
                            </a>
                        @endpermission
                    </div>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="tabs-tabContent">
                @if ($active_tab == 'details')
                    <div class="tab-pane fade {{ ($active_tab == 'details') ? 'active show' : '' }}" role="tabpanel">
                        <table class="table table-bordered table-striped text-center">
                            <tbody>
                                <tr>
                                    <th>الدولة</th>
                                    <th>الإسم</th>
                                    <th>المشرف</th>
                                </tr>
                                <tr>
                                    <td>{{ $office->country->name ?? '' }}</td>
                                    <td>{{ $office->name ?? '' }}</td>
                                    <td>{{ $office->admin->name ?? '' }}</td>
                                </tr>
                                <tr>
                                    <th>الإيميل</th>
                                    <th>الهاتف</th>
                                    <th>الحالة</th>
                                </tr>
                                <tr>
                                    <td>{{ $office->email }}</td>
                                    <td>{{ $office->phone }}</td>
                                    <td>{{ $office->displayStatus() }}</td>
                                </tr>
                                {{--  <tr>
                                    <th colspan="3">الرصيد</th>
                                </tr>  --}}
                                <tr>
                                    <th>مدين</th>
                                    <th>دائن</th>
                                    <th>الرصيد</th>
                                </tr>
                                <tr>
                                    <td>{{ $office->debts(true) }}</td>
                                    <td>{{ $office->credits(true) }}</td>
                                    <td>{{ $office->displayBalance() }}</td>
                                </tr>
                                <tr>
                                    <th colspan="3">
                                        @if (is_null($office->account))
                                            @permission('accounts-create')
                                                <form action="{{ route('accounts.store') }}" method="post" class="d-inline-block">
                                                    @csrf
                                                    <input type="hidden" name="accountable_type" value="{{ get_class($office) }}" />
                                                    <input type="hidden" name="accountable_id" value="{{ $office->id }}" />
                                                    <input type="hidden" name="name" value="{{ $office->name }}" />
                                                    <input type="hidden" name="main_account" value="{{ Modules\Accounting\Models\Account::ACCOUNT_EXTERNAL_OFFICES }}" />
                                                    <button type="submit" class="btn btn-success" data-toggle="confirm" data-title="إنشاء حساب للمكتب" data-text="سوف يتم إنشاء حساب للمكتب في شجرة الحسابات هل انت متأكد">
                                                        <i class="fa fa-book"></i>
                                                        إنشاء حساب
                                                    </button>
                                                </form>
                                            @endpermission
                                        @else
                                            @permission('accounts-read')
                                                <a href="{{ route('accounts.show', ['account' => $office->account, 'view' => 'statement']) }}" class="btn btn-default">
                                                    <i class="fa fa-list"></i>
                                                    <span class="d-sm-none d-lg-inline">@lang('accounting::accounts.statement')</span>
                                                </a>
                                            @endpermission
                                        @endif
                                    </th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                @elseif ($active_tab == 'contracts')
                    <div class="tab-pane fade {{ ($active_tab == 'contracts') ? 'active show' : '' }}" role="tabpanel">
                        <div class="mb-2">
                            <form action="" method="GET" class="form-inline guide-advanced-search">
                                @csrf
                                <div class="form-group mr-2">
                                    <label for="profession_id">المهنة</label>
                                    <select name="profession_id" id="profession_id" class="form-control" required>
                                        <option value="all" {{ ($profession_id == 'all') ? 'selected' : '' }}>@lang('accounting::global.all')</option>
                                        @foreach ($professions as $profession)
                                            <option value="{{ $profession->id }}" {{ ($profession_id == $profession->id) ? 'selected' : '' }}>{{ $profession->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group mr-2">
                                    <label for="from-date">@lang('accounting::global.from')</label>
                                    <input type="date" name="from_date" id="from-date" value="{{ $from_date }}"
                                        class="form-control">
                                </div>
                                <div class="form-group mr-2">
                                    <label for="to-date">@lang('accounting::global.to')</label>
                                    <input type="date" name="to_date" id="to-date" value="{{ $to_date }}"
                                        class="form-control">
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <span>@lang('accounting::global.search')</span>
                                    <i class="fa fa-search"></i>
                                </button>
                            </form>
                        </div>
                        <table id="datatable" class="datatable table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>المعرف</th>
                                    <th>العميل</th>
                                    <th>العامل / العاملة</th>
                                    <th>رقم التأشيرة</th>
                                    <th>المهنة</th>
                                    {{--  <th>الحالة</th>  --}}
                                    <th>قيمة العقد</th>
                                    {{--  <th>عمولة المسوق</th>  --}}
                                    <th>الخيارات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($contracts as $contract)
                                <tr>
                                    <td>{{ $contract->id }}</td>
                                    <td>{{ $contract->customer->name }}</td>
                                    <td>{{ $contract->cv->name }}</td>
                                    <td>{{ $contract->visa }}</td>
                                    <td>{{ $contract->profession->name }}</td>
                                    {{--  <td>{{ $contract->displayStatys() }}</td>  --}}
                                    {{--  <td>{{ $contract->cv->country->name }}</td>  --}}
                                    <td>{{ number_format($contract->amount, 2) }}</td>
                                    {{--  <td>{{ $contract->getMarketerMoney() }}</td>  --}}
                                    <td>
                                        @permission('contracts-read')
                                            <a href="{{ route('contracts.show', $contract) }}" class="btn btn-info">
                                                <i class="fa fa-eye"></i>
                                                <span>عرض</span>
                                            </a>
                                        @endpermission
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @elseif ($active_tab == 'cvs')
                    <div class="tab-pane fade {{ ($active_tab == 'cvs') ? 'active show' : '' }}" role="tabpanel">
                        <div class="mb-2">
                            <form action="" method="GET" class="form-inline guide-advanced-search">
                                @csrf
                                <div class="form-group mr-2">
                                    <label for="type">النوع</label>
                                    <select name="type" id="type" class="form-control" required>
                                        <option value="all" {{ ($type == 'all') ? 'selected' : '' }}>@lang('accounting::global.all')</option>
                                        <option value="waiting" {{ ($type == 'waiting') ? 'selected' : '' }}>في الانتظار</option>
                                        <option value="contracted" {{ ($type == 'contracted') ? 'selected' : '' }}>تمت الموافقة</option>
                                    </select>
                                </div>
                                <div class="form-group mr-2">
                                    <label for="profession_id">المهنة</label>
                                    <select name="profession_id" id="profession_id" class="form-control" required>
                                        <option value="all" {{ ($profession_id == 'all') ? 'selected' : '' }}>@lang('accounting::global.all')
                                        </option>
                                        @foreach ($professions as $profession)
                                        <option value="{{ $profession->id }}" {{ ($profession_id == $profession->id) ? 'selected' : '' }}>
                                            {{ $profession->name }}</option>
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
                        </div>
                        <table id="datatable" class="datatable table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>المعرف</th>
                                    <th>المهنة</th>
                                    <th>العامل / العاملة</th>
                                    <th>رقم الجواز</th>
                                    <th>النوع</th>
                                    <th>تاريخ الميلاد</th>
                                    <th>الحالة</th>
                                    <th>الاجراء الحالى</th>
                                    <th>الخيارات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cvs as $cv)
                                <tr>
                                    <td>{{ $cv->id }}</td>
                                    <td>{{ $cv->profession->name }}</td>
                                    <td>{{ $cv->name }}</td>
                                    <td>{{ $cv->passport }}</td>
                                    <td>{{ $cv->gender == 1 ? 'ذكر' : 'انثي' }}</td>
                                    <td>{{ $cv->birth_date }}</td>
                                    <td>
                                        @if (!$cv->pull)
                                            @if($cv->status == Modules\ExternalOffice\Models\Cv::STATUS_CONTRACTED)
                                                <span class="text-success">
                                                    تم عمل عقد
                                                </span>
                                            @endif
                                            @if($cv->status == Modules\ExternalOffice\Models\Cv::STATUS_ACCEPTED)
                                                <span class="text-success">
                                                    تمت الموافقة
                                                </span>
                                            @endif
                                            @if($cv->status == Modules\ExternalOffice\Models\Cv::STATUS_WAITING)
                                                <span class="text-warning">
                                                    في الانتظار
                                                </span>
                                            @endif
                                        @elseif ($cv->status == Modules\ExternalOffice\Models\Cv::STATUS_PULLED)
                                            <p class="text-info">تم تقديم طلب سحب</p>
                                        @elseif ($cv->status == Modules\ExternalOffice\Models\Cv::STATUS_PULLED)
                                            <p class="text-danger">تم السحب</p>
                                        @endif
                                    </td>
                                    <td>{{ $cv->procedure }}</td>
                                    <td>
                                        <a class="btn btn-info" href="{{ route('servicescvs.show', $cv->id) }}"><i class="fa fa-eye"></i> عرض</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @elseif ($active_tab == 'bills')
                    <div class="tab-pane fade {{ ($active_tab == 'bills') ? 'active show' : '' }}" role="tabpanel">
                        <div class="mb-2">
                            <form action="" method="GET" class="form-inline guide-advanced-search">
                                @csrf
                                <div class="form-group mr-2">
                                    <label for="status">@lang('accounting::global.search_advanced')</label>
                                </div>
                                <div class="form-group mr-2">
                                    <label for="status">@lang('accounting::global.status')</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="all" {{ $status == 'all' ? 'selected' : ''}}>الكل</option>
                                        @foreach (array_keys(__('accounting::global.statuses')) as $st)
                                            <option value="{{ $st }}" {{ $status == $st ? 'selected' : ''}}>@lang('accounting::global.statuses.' . $st)</option>
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
                        </div>
                        <table class="table table-bordered table-striped text-center">
                            <thead>
                                <tr>
                                    <th>المعرف</th>
                                    <th>المكتب</th>
                                    <th>القيمة</th>
                                    <th>الحالة</th>
                                    <th>الخيارات</th>
                                </tr>
                            <tbody>
                                @foreach ($bills as $index=>$bill)
                                <tr>
                                    <td>{{ $bill->id }}</td>
                                    <td>{{ $bill->office->name }}</td>
                                    <td>{{ number_format($bill->amount, 2) }}</td>
                                    <td>{{ $bill->displayStatus() }}</td>
                                    <td>
                                        @permission('bills-read')
                                        <a href="{{ route('offices.bills.show', $bill) }}" class="btn btn-info"><i class="fa fa-eye"></i>
                                            عرض</a>
                                        @endpermission
                                        @if ($bill->statusIsWaiting())
                                            {{--  @permission('bills-update')
                                                <form action="{{ route('offices.bills.update', $bill) }}" method="post" class="d-inline-block">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status" value="{{ App\Traits\Statusable::$STATUS_CHECKING }}" />
                                                    <button type="submit" class="btn btn-success" data-toggle="confirm" data-title="إرسال الى الحسابات" data-text="سوف يتم إرسال الفاتورة الى الحسابات هل انت متأكد" data-icon="success">
                                                        <i class="fa fa-calculator"></i>
                                                        إرسال إلى الحسابات
                                                    </button>
                                                </form>
                                            @endpermission  --}}
                                            @permission('bills-update')
                                                <form action="{{ route('offices.bills.update', $bill) }}" method="post" class="d-inline-block">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status" value="{{ App\Traits\Statusable::$STATUS_REJECTED }}" />
                                                    <button type="submit" class="btn btn-warning" data-toggle="confirm" data-title="رفض الفاتورة" data-text="سوف يتم رفض الفاتورة هل انت متأكد">
                                                        <i class="fa fa-times"></i>
                                                        رفض
                                                    </button>
                                                </form>
                                            @endpermission
                                        @endif
                                        @permission('bills-delete')
                                            <form action="{{ route('offices.bills.destroy', $bill) }}" method="post" class="d-inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger" data-toggle="confirm" data-title="حذف الفاتورة" data-text="سوف يتم حذف الفاتورة هل انت متأكد">
                                                    <i class="fa fa-trash"></i>
                                                    حذف
                                                </button>
                                            </form>
                                        @endpermission
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @elseif ($active_tab == 'advances')
                    <div class="tab-pane fade {{ ($active_tab == 'advances') ? 'active show' : '' }}" role="tabpanel">
                        <div class="mb-2">
                            <form action="" method="GET" class="form-inline guide-advanced-search">
                                @csrf
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
                        </div>
                        <table class="table table-bordered table-striped text-center">
                            <thead>
                                <tr>
                                    <th>المعرف</th>
                                    <th>القيمة</th>
                                    <th>المدفوع</th>
                                    <th>المتبقي</th>
                                    <th>الحالة</th>
                                    {{--  <th>الملاحظات</th>  --}}
                                    <th>الخيارات</th>
                                </tr>
                            <tbody>
                                @foreach ($advances as $index=>$advance)
                                <tr>
                                    <td>{{ $advance->id }}</td>
                                    <td>{{ number_format($advance->amount, 2) }}</td>
                                    <td>{{ $advance->payed(true) }}</td>
                                    <td>{{ $advance->remain(true) }}</td>
                                    <td>{{ $advance->displayStatus() }}</td>
                                    {{--  <td>{{ $advance->notes }}</td>  --}}
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('show.advance', $advance->id) }}" class="btn btn-info">
                                                <i class="fa fa-eye"></i>
                                                <span>عرض</span>
                                            </a>
                                            @permission('advances-update')
                                            @if (!$advance->isVouched())
                                            <button type="submit" class="btn btn-success"
                                                data-toggle="confirm" data-form="#confirm-form-{{ $advance->id }}"
                                                data-title="انشاء سند" data-text="سوف يتم انشاء سند للسلفيه استمرار؟"
                                            >
                                                <i class="fa fa-plus"></i>
                                                <span>انشاء سند</span>
                                            </button>
                                            @endif
                                            @endpermission
                                        </div>
                                        @permission('advances-update')
                                        @if (!$advance->isVouched())
                                            <form id="confirm-form-{{ $advance->id }}" action="{{ route('vouchers.store') }}" method="post">
                                                @csrf
                                                <input type="hidden" name="advance_id" value="{{ $advance->id }}">
                                                <input type="hidden" name="type" value="1">
                                                <input type="hidden" name="amount" value="{{ $advance->amount }}">
                                                <input type="hidden" name="currency" value="دولار">
                                            </form>
                                        @endif
                                        @endpermission
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                {{-- @elseif ($active_tab == 'returns')
                <div class="tab-pane fade {{ ($active_tab == 'returns') ? 'active show' : '' }}" role="tabpanel">
                </div> --}}
                @elseif ($active_tab == 'pulls')
                <div class="tab-pane fade {{ ($active_tab == 'pulls') ? 'active show' : '' }}" role="tabpanel">
                </div>
                @endif
            </div>
        </div>
        <!-- /.card -->
    </div>
    <form id="delete-form" action="{{ route('offices.destroy', $office->id) }}" method="post">
        @csrf
        @method('DELETE')
    </form>
</section>
@endsection
