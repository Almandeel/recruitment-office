<tr>
    <td style="width: 150px;"><input type="number" class="form-control value-number {{ $name . '_value' }}" value="0"  name="{{ $name . '_value_' .$id }}" /></td>
    <td>
        <select id="{{ $name . '_account_' .$id }}" data-placeholder="@lang('accounting::global.assets')" class="select2 form-control input-transparent {{ $name . '_account' }}" data-id="{{ $id }}" data-name="{{ $name }}" name="{{ $name . '_account_' .$id }}">
            @foreach ($roots as $root)
                <optgroup label="{{ $root->number . '-' . $root->name }}">
                    <option value="0" selected>-</option>
                    @component('accounting::entries._options')
                        @slot('account', $root)
                    @endcomponent
                </optgroup>
            @endforeach
        </select>
    </td>
</tr>