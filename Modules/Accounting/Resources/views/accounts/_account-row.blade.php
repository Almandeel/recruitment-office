<tr>
    <td>{{ $account->number }}</td>
    <td>{{ $account->name }}</td>
    <td>{{ $account->balance() }}</td>
    <td>
        <div class="btn-group">
            @permission('accounts-read')
                <a href="{{ route('accounts.show', $account) }}" class="btn btn-primary btn-xs">
                    <i class="fa fa-eye"></i>
                    <span class="d-sm-none d-lg-inline">عرض</span>
                </a>
            @endpermission
            @if ($account->isSecondary())
                @permission('accounts-read')
                    <a href="{{ route('accounts.show', ['account' => $account, 'view' => 'statement']) }}" class="btn btn-default btn-xs">
                        <i class="fa fa-list"></i>
                        <span class="d-sm-none d-lg-inline">@lang('accounting::accounts.statement')</span>
                    </a>
                @endpermission
            @endif
            {{--  @permission('accounts-read')
                <button class="btn btn-info btn-xs show-modal-account"
                    data-id="{{ $account->id }}"
                    data-name="{{ $account->name }}"
                    data-view="preview"
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
                    <i class="fa fa-list"></i>
                    <span class="d-sm-none d-lg-inline">ملخص</span>
                </button>
            @endpermission  --}}
            @permission('accounts-update')
                {{--  <a href="{{ route('accounts.edit', $account) }}" class="btn btn-warning btn-xs">
                    <i class="fa fa-edit"></i>
                    <span class="d-sm-none d-lg-inline">تعديل</span>
                </a>  --}}
                <button class="btn btn-warning btn-xs show-modal-account"
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
                <button class="btn btn-danger btn-xs show-modal-account"
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
    </td>
</tr>
@foreach ($account->children->sortBy('number') as $child)
    @component('accounting::accounts._account-row')
        @slot('counter', $loop->index + 1)
        @slot('account', $child)
    @endcomponent
@endforeach