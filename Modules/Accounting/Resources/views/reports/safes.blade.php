@extends('layouts.print', [
    'title' => 'حركة الخزن',
    'heading' => 'حركة الخزن' . '<br>' . date('Y/m/d', strtotime($from_date)) . ' - ' . date('Y/m/d', strtotime($to_date)),
    'auto_print' => false,
])
@push('content')
    <table class="table table-bordered table-striped datatable">
        <thead>
        <tr>
            <th>#</th>
            <th>الخزنة</th>
            <th>داخل</th>
            <th>خارج</th>
            {{--  <th>الترصيد</th>  --}}
            <th>الرصيد</th>
        </tr>
        </thead>
        <tbody>
            @php
                $total = 0; $totalDebts = 0; $totalCredits = 0; $balances = 0;
            @endphp
            @foreach ($safes as $safe => $entries)
                @php
                    $debts = $entries['debts'];
                    $credits = $entries['credits'];
                @endphp
                @if ($debts->sum('amount') || $credits->sum('amount'))
                    @php
                        $amount = abs($debts->sum('amount') - $credits->sum('amount'));
                        $total += $amount;
                        $totalDebts += $debts->sum('amount');
                        $totalCredits += $credits->sum('amount');
                        // $balances += $amount;
                    @endphp
                    <tr>
                        <td>{{ $loop->index + 1 }}</td>
                        <td>{{ $safe }}</td>
                        <th>{{ number_format($debts->sum('amount'), 2) }}</th>
                        <th>{{ number_format($credits->sum('amount'), 2) }}</th>
                        <th>{{ number_format(($amount), 2) }}</th>
                        {{--  <th>{{ number_format($balances, 2) }}</th>  --}}
                    </tr>
                @endif
            @endforeach
        </tbody>
        <tfoot>
        <th colspan="2">الاجمالي</th>
        <th>{{ number_format($totalDebts, 2) }}</th>
        <th>{{ number_format($totalCredits, 2) }}</th>
        <th>{{ number_format(($total), 2) }}</th>
        {{--  <th>{{ number_format(($balances), 2) }}</th>  --}}
        </tfoot>
    </table>
@endpush
