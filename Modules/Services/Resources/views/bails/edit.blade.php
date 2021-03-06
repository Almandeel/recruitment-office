@extends('layouts.master', [
    'title' => 'تعديل كفالة: ' . $contract->id,
    'datatable' => true,
    'modals' => ['customer', 'marketer'],
    'crumbs' => [
        [route('bails.index'), 'نقل الكفالة'],
        [route('bails.show', $bail), 'كفالة رقم: ' . $bail->id],
        ['#', 'تعديل'],
    ]
])
@section('content')
    <form action="{{ route('bails.update', $bail) }}" method="post">
        @csrf
        @method('PUT')
        @component('components.widget')
            @slot('noPadding', null)
            @slot('body')
                <table class="table table-bordered mb-2">
                    <thead>
                        <tr>
                            <td colspan="4">
                                <div class="form-inlinee">
                                    <div class="input-group">
                                        <label class="input-group-append">
                                            <span>بيانات العميل الاول</span>
                                        </label>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th>الإسم</th>
                            <th>رقم الهوية</th>
                            <th>رقم الهاتف</th>
                            <th>رقم التأشيرة</th>
                        </tr>
                    </thead>
                    <tbody>
                        <td style="padding: 0px;"><input class="form-control" type="text" value="{{ $x_customer->name }}" style="border-radius: 0;" disabled></td>
                        <td style="padding: 0px;">
                            <input type="text" style="border-radius: 0;" class="form-control" disabled value="{{ $x_customer->id_number }}">
                            <input type="hidden" name="x_customer_id" value="{{ $x_customer->id }}">
                            <input type="hidden" name="x_contract_id" value="{{ $x_contract->id }}">
                            <input type="hidden" name="cv_id" value="{{ $cv->id }}">
                        </td>
                        <td style="padding: 0px;"><input type="text" style="border-radius: 0;" class="form-control" disabled value="{{ $x_customer->phones }}"></td>
                        <td style="padding: 0px;"><input type="number" style="border-radius: 0;" class="form-control" name="visa" value="{{ $x_contract->visa }}" readonly placeholder="التأشيرة"></td>
                    </tbody>
                </table>
                <table class="table table-bordered mb-2">
                    <thead>
                        <tr>
                            <td colspan="4">
                                <div class="form-inlinee">
                                    <div class="input-group">
                                        <label for="customers" class="input-group-append">
                                            <span>بيانات العميل الثاني</span>
                                        </label>
                                        <select class="form-control select2 custom-select" id="customers">
                                            <option value="create">إنشاء عميل</option>
                                            @foreach($customers as $cust)
                                                <option value="{{ $cust->id }}" data-id="{{ $cust->id }}" data-name="{{ $cust->name }}" data-id-number="{{ $cust->id_number }}" data-phones="{{ $customer->phones }}" data-address="{{ $customer->address }}" {{ $cust->id == $customer->id ? 'selected' : '' }}>{{ $cust->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th>الإسم</th>
                            <th>رقم الهوية</th>
                            <th>رقم الهاتف</th>
                            <th>العنوان</th>
                        </tr>
                    </thead>
                    <tbody>
                        <td style="padding: 0px;"><input type="text" style="border-radius: 0;" class="form-control" name="customer_name" required placeholder="الإسم"></td>
                        <td style="padding: 0px;">
                            <input type="text" style="border-radius: 0;" class="form-control" name="customer_id_number" placeholder="رقم الهوية">
                            <input type="hidden" name="customer_id">
                        </td>
                        <td style="padding: 0px;"><input type="number" style="border-radius: 0;" class="form-control" name="customer_phones" placeholder="رقم الهاتف"></td>
                        <td style="padding: 0px;"><input type="text" style="border-radius: 0;" class="form-control" name="customer_address" placeholder="العنوان"></td>
                    </tbody>
                </table>
                <table class="table table-bordered mb-2">
                    <thead>
                        <tr>
                            <td colspan="5">
                                <div class="form-inlinee">
                                    <div class="input-group">
                                        <label class="input-group-append">
                                            <span>بيانات العامل{{ $cv->gender == 2 ? 'ة' : '' }}</span>
                                        </label>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th>المكتب</th>
                            <th>الإسم</th>
                            <th>المهنة</th>
                            <th>الجنس</th>
                            <th>رقم الجواز</th>
                        </tr>
                    </thead>
                    <tbody>
                        <td style="padding: 0px;"><input type="text" style="border-radius: 0;" class="form-control" disabled value="{{ $cv->office->name }}"></td>
                        <td style="padding: 0px;"><input type="text" style="border-radius: 0;" class="form-control" disabled value="{{ $cv->name }}"></td>
                        <td style="padding: 0px;"><input type="text" style="border-radius: 0;" class="form-control" disabled value="{{ $cv->profession->name }}"></td>
                        <td style="padding: 0px;"><input type="text" style="border-radius: 0;" class="form-control" disabled value="{{ $cv->displayGender() }}"></td>
                        <td style="padding: 0px;"><input type="text" style="border-radius: 0;" class="form-control" disabled value="{{ $cv->passport }}"></td>
                    </tbody>
                </table>
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="bail_date">تاريخ نقل الكفالة</label>
                            <input required type="date" class="form-control" name="bail_date" value="{{ $bail->bail_date }}" placeholder="تاريخ نقل الكفالة" step="0.01" value="0">
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="amount">قيمة نقل الكفالة</label>
                            <input required type="number" class="form-control" name="amount" value="{{ $bail->amount }}" placeholder="قيمة نقل الكفالة" step="0.01" value="0">
                        </div>
                    </div>
                    <div class="col">
                        <label for="marketer_id">المسوق</label>
                        <div class="input-group">
                            <select class="form-control editable" name="marketer_id">
                                {{--  <option selected disabled value="">المسوق</option>  --}}
                                @foreach($marketers as $marketer)
                                <option value="{{ $marketer->id }}" {{ $contract->marketer_id == $marketer->id ? 'selected' : '' }}>{{ $marketer->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="marketing_ratio">نسبة المسوق</label>
                            <input type="number" class="form-control" name="marketing_ratio" value="{{ $contract->marketing_ratio }}" placeholder="نسبة المسوق">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="status">حالة الكفالة</label>
                            <select class="form-control" name="status">
                            @foreach (__('bails.statuses') as $key => $value)
                                    <option value="{{ $key }}" {{ $key == $bail->status ? 'selected' : '' }}>{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col">
                        <div class="form-group">
                            <label for="trail_date">بداية التجربة</label>
                            <input type="date" class="form-control" name="trail_date" value="{{ $bail->trail_date }}">
                        </div>
                    </div>

                    <div class="col">
                        <div class="form-group">
                            <label for="trail_period">مدة التجربة</label>
                            <input type="number" class="form-control" name="trail_period" max="1000" value="{{ $bail->trail_period }}" placeholder="مدة التجربة">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="notes">الملاحظات</label>
                    <textarea name="notes" id="notes" rows="5" class="form-control" placeholder="الملاحظات">{{ $bail->notes }}</textarea>
                </div>
            @endslot
            @slot('footer')
                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> اكمال العملية</button>
            @endslot
        @endcomponent
    </form>
@endsection
@push('head')
    <style>
        .bg-warning{
            color: #1f2d3d;
        }
        .bg-warning:hover{
            background-color: #e0a800 !important;
        }
        #table-cvs tbody tr:hover{
            cursor: pointer;
        }
        #table-cvs tbody tr.bg-warning:hover{
            cursor: default;
        }
        #age_min, #age_max{max-width: 80px;}
        .form-control{border-radius: 0;}
    </style>
@endpush
@push('foot')
    <script>
        $(function(){
            selectCustomer()
            $(document).on('change', 'select#customers', function(){
                selectCustomer()
            })

        })
        
        function selectCustomer(){
            let selected_option = $('select#customers option:selected');
            let field_customer_name = $('input[name=customer_name]');
            let field_customer_id_number = $('input[name=customer_id_number]');
            let field_customer_id = $('input[name=customer_id]');
            let field_customer_phones = $('input[name=customer_phones]');
            let field_customer_address = $('input[name=customer_address]');
            if(selected_option.val() == 'create'){
                field_customer_phones.val('')
                field_customer_address.val('')
                field_customer_name.val('')
                field_customer_id_number.val('')

                field_customer_id.removeAttr('value')
                field_customer_name.removeAttr('readonly')
                field_customer_id_number.removeAttr('readonly')
                field_customer_phones.removeAttr('readonly')
                field_customer_address.removeAttr('readonly')

                field_customer_name.attr('required', true)
                field_customer_id_number.attr('required', true)
                field_customer_phones.attr('required', true)
                field_customer_address.attr('required', true)
                field_customer_id.attr('readonly', true)
            }else{
                field_customer_name.val(selected_option.data('name'))
                field_customer_id.val(selected_option.data('id'))
                field_customer_phones.val(selected_option.data('phones'))
                field_customer_address.val(selected_option.data('address'))
                field_customer_id.attr('value', selected_option.data('id'))
                field_customer_id_number.val(selected_option.data('id-number'))

                field_customer_name.attr('readonly', true)
                field_customer_id_number.attr('readonly', true)
                field_customer_phones.attr('readonly', true)
                field_customer_address.attr('readonly', true)
                
                field_customer_id.removeAttr('readonly')
                field_customer_name.removeAttr('required')
                field_customer_id_number.removeAttr('required')
                field_customer_phones.removeAttr('required')
                field_customer_address.removeAttr('required')

                $('input.parsley-error[name="customer_name"]')
                    .siblings('ul.parsley-errors-list').remove()
                $('input[name="customer_name"]').removeClass('parsley-error')
            }
        }
    </script>
@endpush
