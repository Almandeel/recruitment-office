@extends('accounting::layouts.master' ,[
    'title' => __('accounting::transfers.create'),
    'accounting_modals' => ['transfer', 'safe'], 
    'datatable' => true,
    'crumbs' => [
        [route('transfers.index'), __('accounting::global.transfers')],
        ['#', __('accounting::transfers.create')],
    ],
])
@push('content')
    <form class="transferForm" action="{{ route('transfers.store') }}" method="POST">
        @csrf
        @component('accounting::components.widget')
            @slot('title', __('accounting::transfers.details'))
            @slot('body')
                <div class="alert alert-warning">
				  <i class="fa fa-exclamation-triangle"></i>
				  <strong>@lang('accounting::warnings.trsnafer_not_entry')</strong>
			  </div>
			  <table class="table">
				  <tr>
					  <th>@lang('accounting::global.from')</th>
					  <td>
					  <select id="transferModalToId" name="to_id" class="form-control" required>
						  @foreach (accounts(true, true) as $account)
							  <option value="{{ $account->id }}">{{ $account->number . '-' . $account->name }}</option>
						  @endforeach
					  </select>
					  </td>
				  </tr>
				  <tr>
					<th>@lang('accounting::global.to')</th>
					<td>
					  <select id="transferModalFromId" name="from_id" class="form-control" required>
						  @foreach (accounts(true, true) as $account)
							  <option value="{{ $account->id }}">{{ $account->number . '-' . $account->name }}</option>
						  @endforeach
					  </select>
				  	</td>
				  </tr>
				  <tr>
					  <th>@lang('accounting::global.amount')</th>
					  <td>
					  <input id="transferModalAmount"  class="form-control amount" autocomplete="off" type="text" name="amount" placeholder="@lang('accounting::global.amount')">
					  </td>
				  </tr>
				  <tr>
					  <th>@lang('accounting::global.details')</th>
					  <td>
					  <textarea id="transferModalDetails"  class="form-control details" autocomplete="off" type="text" name="details" placeholder="@lang('accounting::global.details')"></textarea>
					  </td>
				  </tr>
			  </table>
            @endslot
            @slot('footer')
                <button type="submit" class="btn btn-primary">@lang('accounting::global.save')</button>
                {!! cancelButton() !!}
            @endslot
        @endcomponent
    </form>
@endpush

@push('foot')
@endpush