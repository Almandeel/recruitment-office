@extends('layouts.master', [
    'title' => 'الفواتير',
    'datatable' => true, 
    'modals' => [],
    'crumbs' => [
        [route('offices.index'), 'المكاتب الخارجية'],
        ['#', 'الفواتير']
    ]
])
@section('content')
    @component('components.widget')
        {{--  @slot('title', '')  --}}
        @slot('extra')
            <form action="" method="GET" class="form-inline guide-advanced-search">
                @csrf
                <div class="form-group mr-2">
                    <i class="fa fa-cogs"></i>
                    <label for="status">@lang('accounting::global.search_advanced')</label>
                </div>
                <div class="form-group mr-2">
                    <label for="office_id">المكتب</label>
                    <select name="office_id" id="office_id" class="form-control">
                        <option value="all" {{ $office_id == 'all' ? 'selected' : ''}}>الكل</option>
                        @foreach ($offices as $office)
                            <option value="waiting" {{ $office_id == $office->id ? 'selected' : ''}}>{{ $office->name }}</option>
                        @endforeach
                    </select>
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
        @endslot
        @slot('body')
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
                                @permission('bills-update')
                                    <form action="{{ route('offices.bills.update', $bill) }}" method="post" class="d-inline-block">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="{{ App\Traits\Statusable::$STATUS_CHECKING }}" />
                                        <button type="submit" class="btn btn-success" data-toggle="confirm" data-title="إرسال الى الحسابات" data-text="سوف يتم إرسال الفاتورة الى الحسابات هل انت متأكد" data-icon="success">
                                            <i class="fa fa-calculator"></i>
                                            إرسال إلى الحسابات
                                        </button>
                                    </form>
                                @endpermission
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
        @endslot
    @endcomponent
@endsection
