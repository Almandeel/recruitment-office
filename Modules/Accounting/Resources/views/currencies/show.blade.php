@extends('accounting::layouts.master')
@section('title', __('accounting::global.currencies') . ': ' . $currency->name)
@section('page_title')
    <i class="icon-expenses"></i>
    <span>@lang('accounting::global.currencies')</span>
@endsection
@push('content')
    @component('accounting::components.widget')
        @slot('id', '')
        @slot('title')
            <i class="fa fa-currency"></i>
            <span>@lang('accounting::global.currency'): {{ $currency->name }}</span>
        @endslot
        @slot('content')
            <div class="table-wrapper">
                <table class="table table-bordered table-condensed table-striped">
                    <tr>
                        <th style="width: 130px;">@lang('accounting::global.id')</th>
                        <td>{{ $currency->id }}</td>
                    </tr>
                    <tr>
                        <th>@lang('accounting::global.sample')</th>
                        <td>{{ $currency->sample }}</td>
                    </tr>
                    <tr>
                        <th>@lang('accounting::global.name')</th>
                        <td>{{ $currency->name }}</td>
                    </tr>
                    <tr>
                        <th>@lang('accounting::global.name_short')</th>
                        <td>{{ $currency->short }}</td>
                    </tr>
                    <tr>
                        <th>@lang('accounting::global.options')</th>
                        <td>
                            @can('update-currency')
                                <a href="{{ route('currencies.edit', $currency->id) }}" class="btn btn-warning btn-xs" data-toggle="tooltip" data-placement="top" title="" data-original-title="@lang('accounting::global.edit')">
                                    <i class="fa fa-pencil"></i> 
                                    <span class="table-text">@lang('accounting::global.edit')</span>
                                </a>
                            @endcan
                            @can('delete-currency')
                                <form action="{{ route('currencies.destroy', $currency) }}" method="post" style="display: inline;">
                                    @method('DELETE')
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-xs delete" data-toggle="tooltip" data-placement="top" title="" data-original-title="@lang('accounting::global.edit')">
                                        <i class="fa fa-trash"></i> 
                                        <span class="table-text">@lang('accounting::global.delete')</span>
                                    </button>
                                </form>
                            @endcan
                        </td>
                    </tr>
                </table>
            </div>
        @endslot
    @endcomponent
@endpush