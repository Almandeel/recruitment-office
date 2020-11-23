@extends('accounting::layouts.master')
@section('title', __('accounting::global.currencies'))
@section('page_title')
    <i class="icon-expenses"></i>
    <span>@lang('accounting::global.currencies')</span>
@endsection
@push('content')        
    @component('accounting::components.widget')
        @slot('id', 'currencies-widget')
        @slot('title')
            <h4 class="clearfix">
                <span class="">
                    <i class="fa fa-list"></i>
                    <span>@lang('accounting::currencies.list')</span>
                </span>
            </h4>
        @endslot
        @slot('content')
            <div class="table-wrapper">
                <table id="currencies-table" class="table table-bordered table-hover table-striped">
                    <thead>
                        <tr>
                            <th class="wd-15p text-center">@lang('accounting::global.id')</th>
                            <th class="wd-15p text-center">@lang('accounting::global.sample')</th>
                            <th class="wd-15p text-center">@lang('accounting::global.name')</th>
                            <th class="wd-15p text-center">@lang('accounting::global.name_short')</th>
                            <th class="wd-15p text-center">@lang('accounting::global.options')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($currencies as $currency)
                            <tr>
                                <td>{{ $currency->id }}</td>
                                <td>{{ $currency->sample }}</td>
                                <td>{{ $currency->name }}</td>
                                <td>{{ $currency->short }}</td>
                                <td style="width: 200px; text-align: center;">
                                    @can('read-currency')
                                        <a href="{{ route('currencies.show', $currency->id) }}" class="btn btn-info btn-xs" data-toggle="tooltip" data-placement="top" data-original-title="@lang('accounting::global.show')">
                                            <i class="fa fa-eye"></i> 
                                            <span class="table-text">@lang('accounting::global.show')</span>
                                        </a>
                                    @endcan
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
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endslot
     @endcomponent

@endpush


@push('foot')
    <script>
        $(function() {
            'use strict';
            $('#currencies-table').DataTable({
                responsive: true,
                pageLength: 10,
                language: {
                    searchPlaceholder: 'Search...',
                    sSearch: '',
                    lengthMenu: '_MENU_ items/page',
                }
            });
        });
    </script>
@endpush