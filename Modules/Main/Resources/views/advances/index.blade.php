@extends('layouts.master', ['datatable' => true, 'modals' => ['advance']])

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card text-center">
                    <div class="card-header">
                        <h3 class="card-title">السلفيات</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table class="table table-bordered table-striped datatable">
                            <thead>
                                <tr>
                                    <th>المعرف</th>
                                    <th>المكتب الخارجي</th>
                                    <th>القيمة</th>
                                    <th>المدفوع</th>
                                    <th>المتبقي</th>
                                    <th>الحالة</th>
                                    <th>التاريخ</th>
                                    <th>الخيارات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($advances as $advance)
                                    <tr>
                                        <td>{{ $advance->id }}</td>
                                        <td>{{ $advance->office->name }}</td>
                                        <td>{{ number_format($advance->amount, 2) }}</td>
                                        <td>{{ $advance->payed(true) }}</td>
                                        <td>{{ $advance->remain(true) }}</td>
                                        <td>{{ $advance->displayStatus() }}</td>
                                        {{--  <td>
                                            @if ($advance->status == 0)
                                                <span class="text-warning">قيد الانتظار</span>
                                            @elseif ($advance->status == 1)
                                                <span class="text-success">تم تأكيد السلفية</span>
                                            @elseif ($advance->status == 2)
                                                <span class="text-danger">تم إلغاء السلفية</span>
                                            @endif
                                        </td>  --}}
                                        <td>{{ $advance->created_at->format('Y-m-d') }}</td>
                                        <td>
                                            @if ($advance->hasReturn())
                                                لا توجد
                                            @else
                                                {{--  <a class="btn btn-info btn-sm" href="{{ route('mainadvances.show', $advance) }}"><i class="fa fa-eye"></i> عرض</a>  --}}
                                                <div class="btn-group">
                                                    <a href="{{ route('show.advance', $advance->id) }}" class="btn btn-info">
                                                        <i class="fa fa-eye"></i>
                                                        <span>عرض</span>
                                                    </a>
                                                    @if (!$advance->isVouched())
                                                    @permission('advances-update')
                                                    <button type="submit" class="btn btn-success"
                                                        data-toggle="confirm" data-form="#confirm-form-{{ $advance->id }}"
                                                        data-title="انشاء سند" data-text="سوف يتم انشاء سند للسلفيه استمرار؟"
                                                    >
                                                        <i class="fa fa-plus"></i>
                                                        <span>انشاء سند</span>
                                                    </button>
                                                    @endpermission
                                                    @endif
                                                    @if (!$advance->isVouched())
                                                    @permission('advances-update|vouchers-delete')
                                                    <button type="button" class="btn btn-danger"
                                                        data-toggle="confirm" data-form="#delete-form-{{ $advance->id }}"
                                                        data-title="إلغاء السلفية" data-text="سوف يتم إلغاء السلفية استمرار؟"
                                                    >
                                                        <i class="fa fa-times"></i>
                                                        <span>إلغاء</span>
                                                    </button>
                                                    @endpermission
                                                    @endif
                                                    {{--  <button class="btn btn-warning btn-sm advance update"
                                                        data-action="{{ route('mainadvances.update', $advance->id) }}"
                                                        data-id="{{ $advance->id }}" 
                                                        data-office="{{ $advance->office->name }}" 
                                                        data-status="{{ $advance->status }}"
                                                        data-toggle="modal"
                                                        data-target="#advanceModal"
                                                    ><i class="fa fa-edit"></i> تحديث حالة السلفية</button>  --}}
                                                </div>
                                                @permission('advances-update')
                                                @if (!$advance->isVouched())
                                                    <form id="confirm-form-{{ $advance->id }}" action="{{ route('vouchers.store') }}" method="post">
                                                        @csrf
                                                        <input type="hidden" name="advance_id" value="{{ $advance->id }}">
                                                        <input type="hidden" name="type" value="1">
                                                        <input type="hidden" name="amount" value="{{ $advance->amount }}">
                                                        <input type="hidden" name="currency" value="دولار">
                                                    </form>
                                                @endif
                                                @endpermission
                                                @if (!$advance->isVouched())
                                                    {{--  @permission('advances-update')
                                                    <form id="cancel-form-{{ $advance->id }}" method="post" action="{{ route('vouchers.destroy', $advance->voucher()) }}">
                                                        @csrf
                                                    </form>
                                                    @endpermission  --}}
                                                    @permission('advances-delete')
                                                    <form id="delete-form-{{ $advance->id }}" method="post" action="{{ route('offices.advances.destroy', $advance) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                    @endpermission
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
@endsection
