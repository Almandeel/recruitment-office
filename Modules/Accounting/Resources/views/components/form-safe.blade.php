@php
    $state = isset($state) ? $state : 'in';
    $layout = isset($layout) ? $layout : 'block';
    $account_id = isset($account_id) ? $account_id : null;
@endphp
@if (!$account_id)
    <div class="row">
        <div class="form-group col col-xs-12 {{ $layout == 'block' ? 'col-lg-12' : '' }}">
            <label for="safe_id">@lang('accounting::global.safe')</label>
            <select name="safe_id" id="safe_id" class="form-control select2">
                <option value="default">@lang('accounting::global.default')</option>
                @foreach (safes() as $safe)
                    <option value="{{ $safe->id }}">{{ $safe->account->number . '-' . $safe->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col col-xs-12 {{ $layout == 'block' ? 'col-lg-12' : '' }}">
            <label for="account_id">@lang('accounting::global.account')</label>
            <select name="account_id" id="account_id" class="form-control select2">
                <option value="default">@lang('accounting::global.default')</option>
                @foreach (accounts(true, true) as $account)
                    <option value="{{ $account->id }}">{{ $account->number . '-' . $account->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
@else
    <div class="form-group">
        <label for="safe_id">@lang('accounting::global.safe')</label>
        <select name="safe_id" id="safe_id" class="form-control select2">
            <option value="default">@lang('accounting::global.default')</option>
            @foreach (safes() as $safe)
            <option value="{{ $safe->id }}">{{ $safe->account->number . '-' . $safe->name }}</option>
            @endforeach
        </select>
    </div>
    <input name="account_id" type="hidden" value="{{ $account->id }}"/>
@endif
@push('foot')
    <script>
        $(function(){
            let state = '{{ $state }}';
        })
    </script>
@endpush