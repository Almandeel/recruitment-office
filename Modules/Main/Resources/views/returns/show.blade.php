@extends('layouts.master', [
    'title' => 'إرجاع cv: ' . $return->cv->id,
    'datatable' => true, 
    'modals' => ['attachment'],
    'crumbs' => isset($crumbs) ? $crumbs : [
        [route('offices.index'), 'المكاتب الخارجية'],
        [route('offices.show', ['office' => $return->cv->office, 'active_tab' => 'returns']), 'مكتب: ' . $return->cv->office->name],
        ['#', 'إرجاع cv: ' . $return->cv->id]
    ],
])
@section('content')
    @component('accounting::components.tabs')
        @slot('items')
            @component('accounting::components.tab-item')
                @slot('id', 'details')
                @slot('active', true)
                @slot('title', __('accounting::global.details'))
            @endcomponent
            @component('accounting::components.tab-item')
                @slot('id', 'attachments')
                @slot('title', __('accounting::global.attachments'))
            @endcomponent
        @endslot
        @slot('contents')
            @component('accounting::components.tab-content')
                @slot('id', 'details')
                @slot('active', true)
                @slot('content')
                    <table class="table table-bordered table-striped text-center">
                        <thead>
                            <tr>
                                <th>المعرف</th>
                                <th>المكتب</th>
                                <th>العامل / العامله</th>
                                <th>إجمالي المدفوعات</th>
                                <th>قيمة الضرر</th>
                                {{--  <th>الخيارات</th>  --}}
                            </tr>
                        <tbody>
                            <tr>
                                <td>{{ $return->id }}</td>
                                <td>{{ $return->office->name }}</td>
                                <td>{{ $return->cv->name }}</td>
                                <td>{{ $return->money('payed', 'dollar') }}</td>
                                <td>{{ $return->money('damages', 'dollar') }}</td>
                                {{--  <td>
                                    <a href="{{ route('offices.returns.show', $return) }}" class="btn btn-info btn-sm"><i class="fa fa-eye"></i>
                                        عرض</a>
                                </td>  --}}
                            </tr>
                        </tbody>
                    </table>
                    @if ($return->advance)
                        <table class="table table-bordered table-striped text-center">
                            <thead>
                                <tr>
                                    <th colspan="7">المديونية(السلفة)</th>
                                </tr>
                                <tr>
                                    <th>المعرف</th>
                                    <th>القيمة</th>
                                    <th>التاريخ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ $advance->id }}</td>
                                    <td>{{ number_format($advance->amount, 2) }}</td>
                                    <td>{{ $advance->created_at->format('Y-m-d') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    @endif
                @endslot
            @endcomponent
            @component('accounting::components.tab-content')
                @slot('id', 'attachments')
                @slot('content')
                    @component('accounting::components.attachments-viewer')
                        @slot('attachable', $return)
                        @slot('view', 'timeline')
                        @slot('canAdd', true)
                    @endcomponent
                @endslot
            @endcomponent
        @endslot
    @endcomponent
@endsection
