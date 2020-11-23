@extends('layouts.master', [
    'datatable' => true, 
    'title' => 'اضافة عهدة',
    'datatable' => true,
    'summernote' => true,
    'crumbs' => [
        [route('accounting.custodies.index'), 'العهد'],
        ['#', 'اضافة عهدة'],
    ]
])

@push('head')

@endpush

@section('content')
    <section class="content">
        <form class="form" action="{{ route('accounting.custodies.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
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
                                                    <option value="{{ $account->id }}" {{ $loop->index == 0 ? 'selected' : '' }}>{{ $account->display() }}</option>
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
                                        <option value="{{ $account->id }}">{{ $account->display() }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group row">
                                <div class="col">
                                    <label for="amount">@lang('accounting::global.amount')</label>
                                    <div class="input-group">
                                        <input type="number" id="amount" name="amount" class="form-control" placeholder="@lang('accounting::global.amount')">
                                        <label class="form-control">ريال</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>التفاصيل</label>
                                <textarea class="form-control" autocomplete="off" name="details" placeholder="التفاصيل"></textarea>
                            </div>
                            <div class="form-group">
                                <label>كلمة المرور الحالية</label>
                                <input type="password" class="form-control" name="password" placeholder="كلمة المرور الحالية" required>
                            </div>
                        @endslot
                    @endcomponent
                </div>
            </div>
            <div class="row">
                <div class="col">
                    @component('components.widget')
                        @slot('noTitle', true)
                        @slot('sticky', true)
                        @slot('title')
                            <i class="fas fa-paperclip"></i>
                            <span>المرفقات</span>
                        @endslot
                        @slot('body')
                            @component('components.attachments-uploader')
                            @endcomponent
                        @endslot
                        @slot('footer')
                            <button type="submit" class="btn btn-primary">اكمال العملية</button>
                        @endslot
                    @endcomponent
                </div>
            </div>
        </form>
    </section>
@endsection
@push('foot')
    <script>
        $(function(){
            $('#btn-hide-new-account').click(function(){
                $('#exist-account-form').show()

                $('#new-account-form').hide()
                $('#new-account-form select#main_account').val($('#new-account-form select#main_account option:nth-child(1)').val());
                $('#new-account-form #account_name').val('');
                $('#new-account-form #account_name').removeAttr('required');
            })
            $('#btn-add-account').click(function(){
                let new_account_form = $('#new-account-form');
                let exist_account_form = $('#exist-account-form');
                let main_account = $('#new-account-form #main_account');
                let account_name = $('#new-account-form #account_name');
                account_name.val('')
                account_name.prop('required', true)
                new_account_form.show()
                exist_account_form.hide()
            })
        })
    </script>
@endpush