@extends('layouts.print', [
    'title' => 'حركة خزنة: ' . $safe->name,
    'heading' => 'حركة خزنة: ' . $safe->name . '<br>' . date('Y/m/d', strtotime($from_date)) . ' - ' . date('Y/m/d', strtotime($to_date)),
])
@push('content')
    <div class="box box-primary">
        <div class="box-footer">
            <table class="table table-bordered table-striped datatable">
                <thead>
                    <tr>
                        <th>الموظف</th>
                        <th>التاريخ</th>
                        <th>البيان</th>
                        <th>داخل</th>
                        <th>خارج</th>
                        <th>الرصيد</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $total = $opening_balance;
                        $totalDebts = $debts->sum('amount');
                        $totalCredits = $credits->sum('amount');
                    @endphp
                    <tr>
                        <td></td>
                        <td></td>
                        <th>رصيد مرحل</th>
                        <td></td>
                        <td></td>
                        <td>{{ number_format($total, 2) }}</td>
                    </tr>
                    @foreach ($debts as $debt)
                        @if ($debts->sum('amount'))
                            <tr>
                                <td>{{ $debt->auth()->name }}</td>
                                <td>{{ $debt->created_at->format('Y/m/d') }}</td>
                                <th>{{ $debt->details }}</th>
                                <th>{{ number_format(($debt->amount), 2) }}</th>
                                <th></th>
                                <th>{{ number_format(($total), 2) }}</th>
                            </tr>
                            @php $total += $debt->amount @endphp
                        @endif
                    @endforeach
                    @foreach ($credits as $credit)
                        @if ($credits->sum('amount'))
                            <tr>
                                <td>{{ $credit->auth()->name }}</td>
                                <td>{{ $credit->created_at->format('Y-m-d') }}</td>
                                <th>{{ $credit->details }}</th>
                                <th></th>
                                <th>{{ number_format(($credit->amount), 2) }}</th>
                                <th>{{ number_format(($total), 2) }}</th>
                            </tr>
                            @php $total -= $credit->amount @endphp
                        @endif
                    @endforeach
                </tbody>
                <tfoot>
                    <th colspan="3">الاجمالي</th>
                    <th>{{ number_format($totalDebts, 2) }}</th>
                    <th>{{ number_format($totalCredits, 2) }}</th>
                    <th>{{ number_format(($total), 2) }}</th>
                </tfoot>
            </table>
        </div>
    </div>
@endpush
