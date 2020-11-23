<select id="{{ $id }}" name="{{ $name }}" data-placeholder="@lang('accounting::global.assets')" class="select2 form-control input-transparent">
    <optgroup label="@lang('accounting::global.assets')">
        <option value="0" selected>@lang('accounting::accounts.choose')</option>
        @component('accounting::years._options')
            @slot('group', branch()->assets())
        @endcomponent
    </optgroup>
    <optgroup label="@lang('accounting::global.liabilities')">
        @component('accounting::years._options')
            @slot('group', branch()->liabilities())
        @endcomponent
    </optgroup>
    <optgroup label="@lang('accounting::global.owners')">
        @component('accounting::years._options')
            @slot('group', branch()->owners())
        @endcomponent
    </optgroup>
    <optgroup label="@lang('accounting::global.revenues')">
        @component('accounting::years._options')
            @slot('group', branch()->revenues())
        @endcomponent
    </optgroup>
    <optgroup label="@lang('accounting::global.expenses')">
        @component('accounting::years._options')
            @slot('group', branch()->expenses())
        @endcomponent
    </optgroup>
</select>