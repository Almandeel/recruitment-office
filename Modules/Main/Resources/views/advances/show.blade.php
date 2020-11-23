@extends('layouts.master', ['modals' => ['attachment']])

@section('content')
<section class="content">
        @component('components.tabs')
            @slot('items')
                @component('components.tab-item')
                    @slot('active', true)
                    @slot('id', 'details')
                    @slot('title', 'تفاصيل السلفية')
                @endcomponent
                @component('components.tab-item')
                    @slot('id', 'attachments')
                    @slot('title', 'المرفقات')
                @endcomponent
            @endslot
            @slot('contents')
                @component('components.tab-content')
                    @slot('active', true)
                    @slot('id', 'details')
                    @slot('content')
                        <table class="table table-bordered table-striped text-center">
                            <thead>
                                <tr>
                                    <th>المعرف</th>
                                    <td>{{ $advance->id }}</td>
                                </tr>
                                <tr>
                                    <th>القيمة</th>
                                    <td>{{ number_format($advance->amount) }}</td>
                                </tr>
                                <tr>
                                    <th>الحالة</th>
                                    <td>
                                        @if ($advance->status == 0)
                                            <span class="text-warning">قيد الانتظار</span>
                                        @elseif ($advance->status == 1)
                                            <span class="text-success">تم تأكيد السلفية</span>
                                        @elseif ($advance->status == 2)
                                            <span class="text-danger">تم إلغاء السلفية</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>الموظف</th>
                                    <td>{{ $advance->user->name }}</td>
                                </tr>
                                <tr>
                                    <th>التاريخ</th>
                                    <td>{{ $advance->created_at->format('Y-m-d') }}</td>
                                </tr>
                            </thead>
                        </table>
                    @endslot
                @endcomponent
                @component('components.tab-content')
                    @slot('id', 'attachments')
                    @slot('content')
                        @component('components.attachments-viewer')
                            @slot('attachable', $advance)
                            @slot('canAdd', true)
                            @slot('view', 'timeline')
                        @endcomponent
                    @endslot
                @endcomponent
            @endslot
        @endcomponent
</section>
@endsection
