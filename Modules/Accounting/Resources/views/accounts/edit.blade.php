@extends('accounting::layouts.master',[
    'title' => $account->name,
    'datatable' => true,
    'crumbs' => $crumbs,
])

@push('content')
    <form class="accountForm" action="{{ route('accounts.update', $account) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card card-primary card-outline">
            <div class="card-header">
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label>الاسم</label>
                    <input class="form-control name" autocomplete type="text" value="{{ $account->name }}" name="name" id="name" placeholder="الاسم"
                        required>
                </div>
                {{--  @if ($account->isCustomer())
                    <div class="form-group">
                        <label>@lang('accounting::global.address')</label>
                        <input class="form-control name" autocomplete="off" type="text" id="address" name="address"
                            placeholder="@lang('accounting::global.address')" required />
                    </div>
            
                    <div class="form-group">
                        <label>@lang('accounting::global.phone')</label>
                        <input class="form-control" autocomplete="off" type="number" id="phones" name="phones"
                            placeholder="@lang('accounting::global.phone')" required />
                    </div>
                @endif  --}}
                <div class="row form-group">
                    <div class="col">
                        <label>النوع</label>
                        <select class="form-control type" name="type" id="type" required>
                            @foreach (array_keys(\Modules\Accounting\Models\Account::TYPES) as $type)
                            <option value="{{ $type }}" {{ $account->type == $type ? 'selected' : '' }}>{{ \Modules\Accounting\Models\Account::TYPES[$type] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col">
                        <label>الجانب</label>
                        <select class="form-control side" name="side" id="side" required>
                            @foreach (array_keys(\Modules\Accounting\Models\Account::SIDES) as $side)
                            <option value="{{ $side }}" {{ $account->side == $side ? 'selected' : '' }}>{{ \Modules\Accounting\Models\Account::SIDES[$side] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row form-group">
                    @if (!$account->isRoot())
                        <div class="col">
                            <label>الحساب الرئيسي</label>
                            <select class="form-control select main_account" name="main_account" id="main_account" required>
                                @foreach (roots(true) as $root)
                                @component('accounting::accounts._options')
                                @slot('account', $root)
                                @endcomponent
                                @endforeach
                            </select>
                        </div>
                    @endif
                    {{-- <div class="col">
                                <label>الحساب الختامي</label>
                                <select  class="form-control select2 final_account" name="final_account" id="final_account" required>
                                        <option>لا يوجد</option>
                                        @component('accounting::accounts._options')
                                            @slot('account', finalAccount())
                                        @endcomponent
                                </select>
                            </div>  --}}
                </div>
                {{-- <div class="form-group">
                          <label>الرصيد الافتتاحي</label>
                          <input  class="form-control" type="number" name="opening_balance" placeholder="الرصيد الافتتاحي" />
                      </div>  --}}
            </div>
            <div class="card-footer">
                <a href="{{ url()->previous() }}" class="btn btn-secondary">تراجع</a>
                <button type="submit" class="btn btn-primary">@lang('accounting::global.save_changes')</button>
            </div>
        </div>
    </form>
@endpush
@push('foot')
    <script>
        $(function(){
            @if (!$account->isRoot())
                $('select[name="main_account"]').val({{ $account->main_account }})
            @endif
            @if (!$account->isRoot())
                $('select[name="final_account"]').val({{ $account->final_account }})
            @endif
        })
    </script>
@endpush