@extends('externaloffice::layouts.master', [
    'title' => 'Returns',
    'datatable' => true, 
    'modals' => [],
    'crumbs' => [
        ['#', 'Returns']
    ]
])
@section('content')
    @component('components.widget')
        @slot('title', '')
        @slot('tools')
            <form action="" method="GET" class="form-inline guide-advanced-search">
                @csrf
                <div class="form-group mr-2">
                    <i class="fa fa-filter"></i>
                    <label>Filter</label>
                </div>
                <div class="form-group mr-2">
                    <label for="type">Type</label>
                    <select name="type" id="type" class="form-control">
                        <option value="all" {{ $type == 'all' ? 'selected' : ''}}>All</option>
                        <option value="free" {{ $type == 'free' ? 'selected' : ''}}>Free</option>
                        <option value="payed" {{ $type == 'payed' ? 'selected' : ''}}>Payed</option>
                    </select>
                </div>
                <div class="form-group mr-2">
                    <label for="from-date">From</label>
                    <input type="date" name="from_date" id="from-date" value="{{ $from_date }}" class="form-control">
                </div>
                <div class="form-group mr-2">
                    <label for="to-date">To</label>
                    <input type="date" name="to_date" id="to-date" value="{{ $to_date }}" class="form-control">
                </div>
                <button type="submit" class="btn btn-primary">
                    <span>Search</span>
                    <i class="fa fa-search"></i>
                </button>
            </form>
        @endslot
        @slot('body')
            <table class="table table-bordered table-striped text-center">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Cv</th>
                        <th>Payments</th>
                        <th>Damages</th>
                        <th>Total</th>
                        <th>Options</th>
                    </tr>
                <tbody>
                    @foreach ($returns as $index=>$return)
                    <tr>
                        <td>{{ $return->id }}</td>
                        <td>{{ $return->cv->name }}</td>
                        <td>{{ $return->money('payed') }}</td>
                        <td>{{ $return->money('damages') }}</td>
                        <td>{{ $return->totalAmount() }}</td>
                        <td>
                            @permission('return-read')
                            <a href="{{ route('office.returns.show', $return) }}" class="btn btn-info btn-sm"><i class="fa fa-eye"></i> Show</a>
                            @endpermission
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <th colspan="2">Total</th>
                    <th>{{ money_formats($returns->sum('payed')) }}</th>
                    <th>{{ money_formats($returns->sum('damages')) }}</th>
                    <th>{{ money_formats($returns->sum('payed') + $returns->sum('damages')) }}</th>
                    <th></th>
                </tfoot>
            </table>
        @endslot
    @endcomponent
@endsection
