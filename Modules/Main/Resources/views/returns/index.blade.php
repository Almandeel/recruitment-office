@extends('layouts.master', [
    'title' => 'المرتجعات',
    'datatable' => true, 
    'modals' => [],
    'crumbs' => [
        [route('offices.index'), 'المكاتب الخارجية'],
        ['#', 'المرتجعات']
    ]
])
@section('content')
    @component('components.widget')
        @slot('title', '')
        @slot('tools')
            <a href="{{ route('offices.returns.create') }}" class="btn btn-primary">
                <i class="fa fa-reply"></i>
                <span>إرجاع cv</span>
            </a>
        @endslot
        @slot('extra')
            <form action="" method="GET" class="form-inline guide-advanced-search">
                @csrf
                <div class="form-group mr-2">
                    <i class="fa fa-cogs"></i>
                    <label>@lang('accounting::global.search_advanced')</label>
                </div>
                <div class="form-group mr-2">
                    <label for="office_id">المكتب</label>
                    <select name="office_id" id="office_id" class="form-control">
                        <option value="all" {{ $office_id == 'all' ? 'selected' : ''}}>الكل</option>
                        @foreach ($offices as $office)
                            <option value="{{ $office->id }}" {{ $office_id == $office->id ? 'selected' : ''}}>{{ $office->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group mr-2">
                    <label for="type">@lang('accounting::global.type')</label>
                    <select name="type" id="type" class="form-control">
                        <option value="all" {{ $type == 'all' ? 'selected' : ''}}>@lang('accounting::global.all')</option>
                        <option value="free" {{ $type == 'free' ? 'selected' : ''}}>@lang('accounting::global.return_free')</option>
                        <option value="payed" {{ $type == 'payed' ? 'selected' : ''}}>@lang('accounting::global.return_payed')</option>
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
                        <th>العامل / العامله</th>
                        <th>قيمة الضرر</th>
                        <th>الخيارات</th>
                    </tr>
                <tbody>
                    @foreach ($returns as $index=>$return)
                    <tr>
                        <td>{{ $return->id }}</td>
                        <td>{{ $return->office->name }}</td>
                        <td>{{ $return->cv->name }}</td>
                        <td>{{ $return->getAmount(true) }}</td>
                        <td>
                            <a href="{{ route('offices.returns.show', $return) }}" class="btn btn-info btn-sm"><i class="fa fa-eye"></i>
                                عرض</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endslot
    @endcomponent
@endsection
