@extends('layouts.master', [
    'title' => 'سحب cv: ' . $pull->cv->id,
    'datatable' => true, 
    'modals' => ['attachment'],
    'crumbs' => [
        [route('offices.index'), 'المكاتب الخارجية'],
        [route('offices.pulls.index'), 'المسحوبات'],
        ['#', 'سحب cv: ' . $pull->cv->id],
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
            @if ($pull->isWaiting())
            @permission('pulls-update')
            @component('accounting::components.tab-item')
                @slot('id', 'confirm')
                @slot('title', 'تأكيد عملية السحب')
            @endcomponent
            @endpermission
            @endif
        @endslot
        @slot('contents')
            @component('accounting::components.tab-content')
                @slot('id', 'details')
                @slot('active', true)
                @slot('content')
                    <table class="table table-bordered table-striped text-center">
                        <thead>
                            <tr>
                                <th>@lang('global.id')</th>
                                <th>@lang('global.office')</th>
                                <th>@lang('global.worker')</th>
                                <th>@lang('global.gender')</th>
                                <th>@lang('global.status')</th>
                                @if ($pull->isWaiting())
                                <th>@lang('global.options')</th>
                                @endif
                            </tr>
                        <tbody>
                            <tr>
                                <td>{{ $pull->id }}</td>
                                <td>{{ $pull->office->name }}</td>
                                <td>{{ $pull->cv->name }}</td>
                                <td>{{ $pull->cv->displayGender() }}</td>
                                <td>{!! $pull->displayStatus() !!}</td>
                                @if ($pull->isWaiting())
                                    <td>
                                        @permission('pulls-update')
                                            <button type="button" class="btn btn-danger" 
                                                data-toggle="confirm" data-form="#cancel-form-{{ $pull->id }}"
                                                data-title="إلغاء السحب" data-text="سوف يتم إلغاء طلب سحب السيرة استمرار؟"
                                            >
                                                <i class="fa fa-times"></i>
                                                <span>@lang('global.cancel')</span>
                                            </button>
                                        @endpermission
                                        @endif
                                        @if ($pull->isWaiting())
                                        @permission('pulls-update')
                                            <form id="cancel-form-{{ $pull->id }}" action="{{ route('offices.pulls.update', $pull) }}" method="post">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="operation" value="cancel">
                                            </form>
                                        @endpermission
                                    </td>
                                @endif
                            </tr>
                        </tbody>
                    </table>
                    @if ($pull->advance)
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
                        @slot('attachable', $pull)
                        @slot('view', 'timeline')
                        @slot('canAdd', true)
                    @endcomponent
                @endslot
            @endcomponent
            @if ($pull->isWaiting())
                @permission('pulls-update')
                    @component('accounting::components.tab-content')
                        @slot('id', 'confirm')
                        @slot('content')
                            <form action="{{ route('offices.pulls.update', $pull) }}" method="post">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="operation" value="confirm">
                                @component('components.attachments-uploader')
                                @endcomponent
                                <div class="form-inline">
                                    <div class="form-group mr-2">
                                        <label for="payed">إجمالي المدفوع</label>
                                        <input type="number" id="payed" name="payed" min="0" class="form-control"
                                            value="{{ $pull->cv->payed() }}">
                                    </div>
                                    <div class="form-group mr-2">
                                        <label for="damages">إجمالي الاضرار</label>
                                        <input type="number" id="damages" name="damages" min="0" class="form-control" value="0">
                                    </div>
                                    <button type="button" class="btn btn-warning" data-toggle="confirm" 
                                            data-title="تأكيد السحب" data-text="سوف يتم تأكيد سحب السيرة ولن تظهر في قائمة السير الذاتية استمرار؟"
                                        >
                                        <i class="fa fa-check"></i>
                                        <span>تأكيد طلب السحب</span>
                                    </button>
                                </div>
                            </form>
                        @endslot
                    @endcomponent
                @endpermission
            @endif
        @endslot
    @endcomponent
@endsection
