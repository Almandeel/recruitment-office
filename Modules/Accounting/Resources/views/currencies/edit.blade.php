@extends('accounting::layouts.master')
@section('title', __('accounting::currencies.edit'))
@section('page_title')
    <i class="icon-expenses"></i>
    <span>@lang('accounting::global.currencies')</span>
@endsection
@push('content')
    @include('partials._errors')
    @component('accounting::components.widget')
        @slot('id', '')
        @slot('title')
            <i class="fa fa-pencil"></i>
            <span>@lang('accounting::currencies.edit')</span>
        @endslot
        @slot('content')
            <form action="{{ route('currencies.update', $currency) }}" method="post" data-parsley-validate class="form-horizontal">
                @csrf
                @method('PUT')
                <fieldset>
                    <div class="form-group">
                        <label class="col-sm-4 col-md-2 control-label" for="name">
                            @lang('accounting::global.name')
                        </label>
                        <div class="col-sm-8 col-md-10 reset">
                            @foreach(config('translatable.locales') as $locale)
                                <div class="col-sm-12 col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-addon">@lang('accounting::global.' . $locale)</span>
                                        <input id="name" name="name_{{ $locale }}" class="form-control input-transparent" size="16" type="text" {{ $locale == app()->getLocale() ? 'required' : '' }} value="{{ array_key_exists($locale, $currency->getTranslations('name')) ? $currency->getTranslations('name')[$locale] : '' }}" />
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 col-md-2 control-label" for="short">
                            @lang('accounting::global.name_short')
                        </label>
                        <div class="col-sm-8 col-md-10 reset">
                            @foreach(config('translatable.locales') as $locale)
                                <div class="col-sm-12 col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-addon">@lang('accounting::global.' . $locale)</span>
                                        <input id="short" name="short_{{ $locale }}" class="form-control input-transparent" size="16" type="text" {{ $locale == app()->getLocale() ? 'required' : '' }} value="{{ array_key_exists($locale, $currency->getTranslations('short')) ? $currency->getTranslations('short')[$locale] : '' }}" />
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 col-md-2 control-label" for="sample">
                            @lang('accounting::global.sample')
                        </label>
                        <div class="col-sm-8 col-md-10">
                            <input id="sample" name="sample" class="form-control input-transparent" type="text" value="{{ $currency->sample }}" />
                        </div>
                    </div>
                </fieldset>
                <fieldset>
                    <legend>@lang('accounting::global.options')</legend>
                    <div class="form-group">
                        <div class="col-sm-12 col-md-12">
                            <div class="radio radio-default">
                                <input type="radio" name="next" id="save_edit" value="save_edit" checked>
                                <label for="save_edit" class="text-default">
                                    @lang('accounting::global.save_edit')
                                </label>
                            </div>
                            <div class="radio radio-default">
                                <input type="radio" name="next" id="save_show" value="save_show">
                                <label for="save_show" class="text-default">
                                    @lang('accounting::global.save_show')
                                </label>
                            </div>
                            <div class="radio radio-default">
                                <input type="radio" name="next" id="save_list" value="save_list">
                                <label for="save_list" class="text-default">
                                    @lang('accounting::global.save_list')
                                </label>
                            </div>
                            <button class="btn btn-success">
                                <i class="fa fa-save"></i>
                                <span>@lang('accounting::global.save_changes')</span>
                            </button>
                        </div>
                    </div>
                </fieldset>
            </form>
        @endslot
    @endcomponent
@endpush
@push('foot')
    
<script src="{{ asset('lib/bootstrap-select/dist/js/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('lib/select2/select2.min.js') }}"></script>
<script src="{{ asset('lib/select2/select2_locale_'.app()->getLocale().'.js') }}"></script>
<script>
    $(document).ready(function(){
        $("#select_group").select2({
            dir: "{{ direction() }}"
        });
    })
</script>
@endpush