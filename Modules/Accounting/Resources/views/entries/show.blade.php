@extends('accounting::layouts.master',[
    'title', __('accounting::entries.show') . ': ' . $entry->id,
    'crumbs' => [
        [route('entries.index'), __('accounting::global.entries')],
        ['#', 'قيد رقم: ' . $entry->id],
    ],
])
@push('content')
    @component('accounting::components.widget')
        @slot('id', '')
        @slot('title')
            <i class="fa fa-eye"></i>
            <span>@lang('accounting::entries.show'): {{ $entry->id }}</span>
        @endslot
        @slot('body')
            <div class="table-wrapper">
                <table class="table table-bordered table-condensed table-striped table-hover">
                    <thead>
                        <tr>
                            <th style="width: 130px;">@lang('accounting::entries.id')</th>
                            <th style="width: 130px;">@lang('accounting::global.type')</th>
                            <th>@lang('accounting::global.from')</th>
                            <th>@lang('accounting::global.to')</th>
                            {{-- <th>@lang('accounting::global.debt')</th>
                            <th>@lang('accounting::global.credit')</th> --}}
                            <th>@lang('accounting::global.details')</th>
                            <th style="width: 130px;">@lang('accounting::entries.date')</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $entry->id }}</td>
                            <td>@lang('accounting::entries.types.' . $entry->type)</td>
                            <td>
                                @if($entry->debts()->count() > 1)<div style="color: transparent;">.</div>@endif
                                {{--  @if($entry->credits()->count() > 1)<div style="color: transparent;">.</div>@endif  --}}
                                @foreach($entry->debts() as $amount)
                                    <div>{{ $amount->pivot->amount}}</div>
                                @endforeach
                                @foreach($entry->credits() as $credit)
                                    <div style="color: transparent;">.</div>
                                @endforeach
                            </td>
                            <td>
                                @if($entry->debts()->count() > 1)<div style="color: transparent;">.</div>@endif
                                @if($entry->credits()->count() > 1)<div style="color: transparent;">.</div>@endif
                                @foreach($entry->debts() as $debt)
                                    <div style="color: transparent;">.</div>
                                @endforeach
                                @foreach($entry->credits() as $amount)
                                    <div>{{ $amount->pivot->amount}}</div>
                                @endforeach
                            </td>
                            <td>
                                <div>
                                    @if(count($entry->debts()) == 1)
                                        @lang('accounting::entries.from_account') {{ $entry->debts()->first()->name }}
                                    @else
                                        <div>@lang('accounting::entries.from_accounts')</div>
                                        @foreach($entry->debts() as $amount)
                                            <div>@lang('accounting::global.account') {{ $amount->name}}</div>
                                        @endforeach
                                    @endif
                                </div>
                                <div>
                                    @if(count($entry->credits()) == 1)
                                        @lang('accounting::entries.to_account') {{ $entry->credits()->first()->name }}
                                    @else
                                        <div>@lang('accounting::entries.to_accounts')</div>
                                        @foreach($entry->credits() as $amount)
                                            <div class="entry_to">@lang('accounting::global.account') {{ $amount->name}}</div>
                                        @endforeach
                                    @endif
                                </div>
                                <div class="text-center"><strong>{{ $entry->details }}</strong></div>
                            </td>
                            <td>{{ $entry->entry_date }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        @endslot
    @endcomponent
@endpush