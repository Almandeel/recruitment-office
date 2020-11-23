@extends('accounting::layouts.master',[
    'title' => 'الحسابات',
    'datatable' => true,
    'accounting_modals' => [
        'account'
    ],
    'crumbs' => [
        ['#', 'الحسابات'],
    ],
])

@push('content')
@endpush