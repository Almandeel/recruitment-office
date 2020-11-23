@extends('accounting::layouts.master',[
    'title' => 'شجرة الحسابات',
    'accounting_modals' => [
        'account'
    ],
    'select2' => true,
    'treeview' => true,
    'crumbs' => $crumbs,
])

@push('content')
    <div class="row">
        <div class="col col-lg-4">
            @component('accounting::components.widget')
                @slot('widgets', ['maximize'])
                @slot('title', 'شجرة الحسابات')
                @slot('body')
                    @component('accounting::components.accounting-tree')
                        @slot('accounts', $roots)
                    @endcomponent
                @endslot
            @endcomponent
        </div>
        <div class="col">
            @component('accounting::components.widget')
                @slot('sticky', true)
                @slot('tools')
                    <button class="btn btn-primary show-modal-account" 
                    data-main-type="{{ $account->type }}" data-main-id="{{ $account->id }}">
                        <i class="fa fa-plus"></i>
                        <span>اضافة حساب</span>
                    </button>
                @endslot
                @slot('widgets', ['maximize'])
                @slot('title', $account->number . '-' . $account->name)
                @slot('body')
                    <table class="table table-striped">
                        <tr>
                            <th style="width: 20%;">المعرف</th>
                            <td>{{ $account->id }}</td>
                        </tr>
                        <tr>
                            <th>النوع</th>
                            <td>{{ $account->getType() }}</td>
                        </tr>
                        <tr>
                            <th>الجانب</th>
                            <td>{{ $account->getSide() }}</td>
                        </tr>
                        <tr>
                            <th>الحساب الرئيسي</th>
                            <td>{{ $account->parent ? $account->parent->name : 'لا يوجد' }}</td>
                        </tr>
                        {{--  <tr>
                            <th>الحساب الختامي</th>
                            <td>{{ $account->final ? $account->final->name : 'لا يوجد' }}</td>
                        </tr>  --}}
                        <tr>
                            <th>الرصيد</th>
                            <td>{{ $account->balance(true) }}</td>
                        </tr>
                    </table>
                @endslot
                @slot('footer')
                    {{--  <div class="text-center">
                        @permission('accounts-read')
                            <a href="{{ route('accounts.show', $account) }}" class="btn btn-primary">
                                <i class="fa fa-eye"></i>
                                <span class="d-sm-none d-lg-inline">عرض</span>
                            </a>
                            {!! accountButton($account, 'preview') !!}
                        @endpermission
                        @if ($account->isSecondary())
                            @permission('accounts-update')
                                {!! accountButton($account, 'update', route('accounts.update', $account), 'PUT') !!}
                            @endpermission
                            @permission('accounts-delete')
                                {!! accountButton($account, 'confirm-delete', route('accounts.destroy', $account), 'DELETE') !!}
                            @endpermission
                        @endif
                    </div>  --}}
                    <div class="btn-group text-center">
                        @if ($account->isSecondary())
                            @permission('accounts-read')
                                <a href="{{ route('accounts.show', ['account' => $account, 'view' => 'statement']) }}" class="btn btn-default">
                                    <i class="fa fa-list"></i>
                                    <span class="d-sm-none d-lg-inline">@lang('accounting::accounts.statement')</span>
                                </a>
                            @endpermission
                        @endif
                        @permission('accounts-read')
                            <a href="{{ route('accounts.show', $account) }}" class="btn btn-info">
                                <i class="fa fa-eye"></i>
                                <span class="d-sm-none d-lg-inline">@lang('accounting::accounts.show')</span>
                            </a>
                        @endpermission
                        @permission('accounts-update')
                            {{--  <a href="{{ route('accounts.edit', $account) }}" class="btn btn-warning">
                                <i class="fa fa-edit"></i>
                                <span class="d-sm-none d-lg-inline">تعديل</span>
                            </a>  --}}
                            <button class="btn btn-warning show-modal-account"
                                data-action="{{ route('accounts.update', $account) }}"
                                data-method="PUT"
                                data-id="{{ $account->id }}"
                                data-name="{{ $account->name }}"
                                data-view="update"
                                data-type="{{ $account->type }}"
                                data-side="{{ $account->side }}"
                                @if ($account->main_account)
                                data-main-id="{{ $account->main_account }}"
                                data-main-name="{{ $account->parent->name }}"
                                @else
                                data-main-name="لا يوجد"
                                @endif
                                @if ($account->final_account)
                                data-final-id="{{ $account->final_account }}"
                                data-final-name="{{ $account->final->name }}"
                                @else
                                data-final-name="لا يوجد"
                                @endif
                            >
                                <i class="fa fa-edit"></i>
                                <span class="d-sm-none d-lg-inline">تعديل</span>
                            </button>
                        @endpermission
                        @permission('accounts-delete')
                            <button class="btn btn-danger show-modal-account"
                                data-action="{{ route('accounts.destroy', $account) }}"
                                data-method="DELETE"
                                data-id="{{ $account->id }}"
                                data-name="{{ $account->name }}"
                                data-view="confirm-delete"
                                data-type="{{ $account->type }}"
                                data-side="{{ $account->side }}"
                                @if ($account->main_account)
                                data-main-id="{{ $account->main_account }}"
                                data-main-name="{{ $account->parent->name }}"
                                @else
                                data-main-name="لا يوجد"
                                @endif
                                @if ($account->final_account)
                                data-final-id="{{ $account->final_account }}"
                                data-final-name="{{ $account->final->name }}"
                                @else
                                data-final-name="لا يوجد"
                                @endif
                            >
                                <i class="fa fa-trash"></i>
                                <span class="d-sm-none d-lg-inline">حذف</span>
                            </button>
                        @endpermission
                    </div>
                @endslot
            @endcomponent
        </div>
    </div>
@endpush
