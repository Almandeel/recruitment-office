@extends('accounting::layouts.master', [
    'datatable' => true, 
    'title' => __('accounting::vouchers.create'),
    'datatable' => true,
    'summernote' => true,
    'crumbs' => [
        [route('vouchers.index'), __('accounting::global.vouchers')],
        ['#', __('accounting::vouchers.create')],
    ]
])
@push('content')
    <section class="content">
        <form class="form" action="{{ route('vouchers.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col">
                    @component('components.widget')
                        @slot('title')
                            <i class="fas fa-list"></i>
                            <span>@lang('accounting::global.details')</span>
                        @endslot
                        @slot('body')
                            @if (request('voucherable_id') && request('voucherable_type'))
                                <input type="hidden" name="voucherable_id" value="{{ request('voucherable_id') }}">
                                <input type="hidden" name="voucherable_type" value="{{ request('voucherable_type') }}">
                            @else
                            {{--  <div class="form-group">
                                <label>@lang('accounting::global.benefit')</label>
                                <input class="form-control" autocomplete="off" type="text" name="voucherable_type" placeholder="@lang('accounting::global.benefit')" required />
                            </div>  --}}
                            @endif
                            <div class="form-group row">
                                <div class="col">
                                    <label>@lang('accounting::global.benefit')</label>
                                    <input class="form-control" autocomplete="off" type="text" name="voucherable_type" placeholder="@lang('accounting::global.benefit')" required />
                                </div>
                                {{--  <div class="col">
                                    <label>@lang('accounting::global.number')</label>
                                    <input class="form-control" autocomplete="off" type="number" name="number" placeholder="@lang('accounting::global.number')" />
                                </div>  --}}
                                <div class="col">
                                    <label>@lang('accounting::global.type')</label>
                                    <select class="form-control type" name="type" id="type" required>
                                        @foreach (\Modules\Accounting\Models\Voucher::TYPES as $type)
                                        <option value="{{ $type }}">@lang('accounting::vouchers.types.' . $type)</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col">
                                    <label>@lang('accounting::global.amount')</label>
                                    <div class="input-group">
                                        <input type="number" id="amount" name="amount" class="form-control" value="0"placeholder="@lang('accounting::global.amount')" required>
                                        <select name="currency" id="currency" class="form-control" required>
                                            <option value="ريال">ريال</option>
                                            <option value="دولار">دولار</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col">
                                    <label>@lang('accounting::global.date')</label>
                                    <input class="form-control" autocomplete="off" type="date" name="voucher_date" placeholder="@lang('accounting::global.date')" value="{{ date('Y-m-d') }}" required />
                                </div>
                            </div>
                            <div class="form-group">
                                <label>@lang('accounting::global.details')</label>
                                <textarea class="form-control" name="details" placeholder="@lang('accounting::global.details')"></textarea>
                            </div>
                        @endslot
                        @slot('footer')
                            <button type="submit" class="btn btn-primary">@lang('accounting::global.submit')</button>
                        @endslot
                    @endcomponent
                </div>
                <div class="col">
                    @component('accounting::components.widget')
                        @slot('noTitle', true)
                        @slot('noPadding', true)
                        @slot('title')
                            <i class="fas fa-paperclip"></i>
                            <span>@lang('accounting::global.attachments')</span>
                        @endslot
                        @slot('body')
                            @component('accounting::components.attachments-uploader')@endcomponent
                        @endslot
                        @slot('footer')
                            <button type="submit" class="btn btn-primary">@lang('accounting::global.submit')</button>
                        @endslot
                    @endcomponent
                </div>
            </div>
        </form>
    </section>
@endpush