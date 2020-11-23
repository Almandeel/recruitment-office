@extends('layouts.master', [
    'datatable' => true, 
    'title' => 'تعديل ' . 'عهدة رقم: ' . $custody->id,
    'datatable' => true,
    'summernote' => true,
    'crumbs' => [
        [route('accounting.custodies.index'), 'العهد'],
        [route('accounting.custodies.show', $custody), 'عهدة رقم: ' . $custody->id],
        ['#', 'تعديل'],
    ]
])

@push('head')
    
@endpush

@section('content')
    <section class="content">
        <form class="form" action="{{ route('accounting.custodies.update', $custody) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col">
                    @component('components.widget')
                        @slot('title')
                            <i class="fas fa-list"></i>
                            <span>بيانات العهدة</span>
                        @endslot
                        @slot('body')
                            <div class="form-group">
                                <label for="debt_account">@lang('accounting::global.account')</label>
                                <div>
                                    <div id="exist-account-form">
                                        <div class="input-group">
                                            <select name="debt_account" id="debt_account" class="form-control" required>
                                                @foreach ($secondary_accounts as $account)
                                                    <option value="{{ $account->id }}" {{ !is_null($debt_account) ? ($debt_account->id == $account->id ? 'selected' : '') : '' }}>{{ $account->display() }}</option>
                                                @endforeach
                                            </select>
                                            <span class="input-group-btn">
                                                <button type="button" id="btn-add-account" class="btn btn-primary">
                                                    <i class="fa fa-plus"></i>
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                    <div id="new-account-form" class="form-inline" style="display: none;">
                                        <div class="form-group mr-2">
                                            <label>
                                                <i class="fa fa-plus"></i>
                                                <span>إنشاء حساب</span>
                                            </label>
                                        </div>
                                        <div class="form-group mr-2">
                                            <label for="main_account">@lang('accounting::accounts.main')</label>
                                            <select name="main_account" id="main_account" class="form-control">
                                                @foreach ($primary_accounts as $account)
                                                    <option value="{{ $account->id }}">{{ $account->display() }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group mr-2">
                                            <label for="account_name">@lang('accounting::accounts.name')</label>
                                            <input type="text" name="account_name" id="account_name" class="form-control">
                                        </div>
                                        <div class="form-group mr-2">
                                            <button type="button" id="btn-hide-new-account" class="btn btn-warning">
                                                <i class="fa fa-times"></i>
                                                <span>إلغاء</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="credit_account">الحساب الدائن</label>
                                <select name="credit_account" id="credit_account" class="form-control" required>
                                    @foreach ($secondary_accounts as $account)
                                        <option value="{{ $account->id }}" {{ !is_null($credit_account) ? ($credit_account->id == $account->id ? 'selected' : '') : '' }}>{{ $account->display() }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group row">
                                <div class="col">
                                    <label for="amount">@lang('accounting::global.amount')</label>
                                    <div class="input-group">
                                        <input type="number" id="amount" name="amount" value="{{ $custody->amount }}" class="form-control" placeholder="@lang('accounting::global.amount')">
                                        <label class="form-control">ريال</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>التفاصيل</label>
                                <textarea class="form-control" autocomplete="off" name="details" placeholder="التفاصيل">{{ $custody->details }}</textarea>
                            </div>
                            <div class="form-group">
                                <label>كلمة المرور الحالية</label>
                                <input type="password" class="form-control" name="password" placeholder="كلمة المرور الحالية" required>
                            </div>
                        @endslot
                        @slot('footer')
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save"></i>
                                <span>@lang('accounting::global.save_changes')</span>
                            </button>
                        @endslot
                    @endcomponent
                </div>
            </div>
        </form>
    </section>
@endsection