@extends('layouts.master', [
    'title' => 'تعديل عقد: ' . $contract->id,
    'datatable' => true,
    'modals' => ['customer', 'marketer'],
    'crumbs' => [
        [route('contracts.index'), 'العقود'],
        [route('contracts.show', $contract), 'عقد رقم: ' . $contract->id],
        ['#', 'تعديل'],
    ]
])
@section('content')
    <form action="{{ route('contracts.update', $contract) }}" method="post">
        @csrf
        @method('PUT')
        @component('components.widget')
            @slot('noPadding', null)
            @slot('extra')
                <div class="form-inline">
                    <div class="form-group mr-2">
                        <label>
                            <i class="fa fa-search"></i>
                            <span>فرز</span>
                        </label>
                    </div>
                    <div class="form-group mr-2">
                        <label for="country_id">الدولة</label>
                        <select name="country_id" id="country_id" class="form-control">
                            <option value="all" {{ $country_id == 'all' ? 'selected' : '' }}>الكل</option>
                            @foreach ($countries as $country)
                                <option value="{{ $country->id }}" {{ $country->id == $country_id ? 'selected' : '' }}>{{ $country->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mr-2">
                        <label for="office_id">المكتب</label>
                        <select name="office_id" id="office_id" class="form-control">
                            <option value="all" {{ $office_id == 'all' ? 'selected' : '' }}>الكل</option>
                            @foreach ($offices as $office)
                                <option value="{{ $office->id }}" {{ $office->id == $office_id ? 'selected' : '' }}>{{ $office->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mr-2">
                        <label for="profession_id">المهنة</label>
                        <select name="profession_id" id="profession_id" class="form-control">
                            <option value="all" {{ $profession_id == 'all' ? 'selected' : '' }}>الكل</option>
                            @foreach ($professions as $profession)
                                <option value="{{ $profession->id }}" {{ $profession->id == $profession_id ? 'selected' : '' }}>{{ $profession->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>متوسط العمر</label>
                    </div>
                    <div class="form-group mr-2">
                        <label for="age_min">من</label>
                        <input type="number" name="age_min" id="age_min" class="form-control" value="0">
                    </div>
                    <div class="form-group mr-2">
                        <label for="age_max">الى</label>
                        <input type="number" name="age_max" id="age_max" class="form-control" value="0">
                    </div>
                    <button type="button" class="btn btn-primary btn-filter">
                        <i class="fa fa-refresh"></i>
                        <span>تحديث</span>
                    </button>
                </div>
            @endslot
            @slot('body')
                <div class="table-container">
                    <table id="table-cvs" class="table {{ is_null($cv) ? 'table-striped' : '' }}">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>الدولة</th>
                                <th>المكتب</th>
                                <th>المهنة</th>
                                <th>CV</th>
                                <th>العمر</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (is_null($cv))
                                @foreach ($cvs as $c)
                                <tr class="row-cv">
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $c['country_name'] }}</td>
                                    <td>{{ $c['office_name'] }}</td>
                                    <td>{{ $c['profession_name'] }}</td>
                                    <td>{{ $c['name'] }}
                                    <input type="hidden" name="cv_id" data-payed="{{ $c['payed'] }}" data-age="{{ $c['age'] }}" data-gender="{{ $c['gender'] }}" data-passport="{{ $c['passport'] }}" value="{{ $c['id'] }}"
                                    data-country-id="{{ $c['country_id'] }}" data-office-id="{{ $c['office_id'] }}" data-profession-id="{{ $c['profession_id'] }}"></td>
                                    <td>{{ $c['age'] }}</td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td>{{ 1 }}</td>
                                    <td>{{ $cv->country->name }}</td>
                                    <td>{{ $cv->office->name ?? '-' }}</td>
                                    <td>{{ $cv->profession->name }}</td>
                                    <td>{{ $cv->name }}
                                        <input type="hidden" name="cv_id" data-payed="{{ $cv->payed() }}" value="{{ $cv->id }}"></td>
                                    <td>{{ $cv->age() }}</td>
                                </tr>
                                <tr>
                                    <td colspan="6" style="padding: 0;">
                                        <table class="table table-bordered" style="margin: 0;">
                                            <thead>
                                                <tr>
                                                    <th>الجنس</th>
                                                    <th>الجواز</th>
                                                    <th>العمر</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>{{ $cv->displayGender() }}</td>
                                                    <td>{{ $cv->passport }}</td>
                                                    <td>{{ $cv->age() }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            @endif
                            
                        </tbody>
                    </table>
                </div>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <td colspan="3">
                                <div class="form-inlinee">
                                    <div class="input-group">
                                        <label for="customers" class="input-group-append">
                                            <span>العملاء</span>
                                        </label>
                                        <select class="form-control select2 custom-select" id="customers">
                                            <option value="create">إنشاء عميل</option>
                                            @foreach($customers as $ctmr)
                                            <option value="{{ $ctmr->id }}" {{ $ctmr->id == $contract->customer_id ? 'selected' : '' }} data-id="{{ $ctmr->id }}" data-name="{{ $ctmr->name }}" data-id-number="{{ $ctmr->id_number }}">{{ $ctmr->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th>الإسم</th>
                            <th>رقم الهوية</th>
                            <th>رقم التأشيرة</th>
                        </tr>
                    </thead>
                    <tbody>
                        <td style="padding: 0px;"><input type="text" style="border-radius: 0;" class="form-control" name="customer_name" required placeholder="الإسم"></td>
                        <td style="padding: 0px;">
                            <input type="text" style="border-radius: 0;" class="form-control" name="customer_id_number" placeholder="رقم الهوية">
                            <input type="hidden" name="customer_id">
                        </td>
                        <td style="padding: 0px;"><input type="text" style="border-radius: 0;" class="form-control" name="visa" value="{{ $contract->visa }}" placeholder="التأشيرة"></td>
                    </tbody>
                </table>
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="amount">قيمة العقد</label>
                            <input required type="number" class="form-control" name="amount" value="{{ $contract->amount }}" placeholder="Amount" step="0.01" value="0">
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="marketing_ratio">نسبة المسوق</label>
                            <input type="number" class="form-control" name="marketing_ratio" value="{{ $contract->marketing_ratio }}" placeholder="نسبة المسوق">
                        </div>
                    </div>
                    <div class="col">
                        <label for="marketer_id">المسوق</label>
                        <div class="input-group">
                            <select class="form-control editable" name="marketer_id">
                                {{--  <option selected disabled value="">المسوق</option>  --}}
                                @foreach($marketers as $marketer)
                                <option value="{{ $marketer->id }}" {{ $marketer->id == $contract->marketer_id ? 'selected' : '' }}>{{ $marketer->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="destination">جهة الوصول</label>
                            <input type="text" class="form-control" name="destination" value="{{ $contract->destination }}" placeholder="جهة الوصول ">
                        </div>
                    </div>
                    
                    <div class="col">
                        <div class="form-group">
                            <label for="date_arrival">تاريخ الوصول</label>
                            <input type="date" class="form-control" name="date_arrival" value="{{ $contract->date_arrival }}" placeholder="تاريخ الوصول ">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="arrival_airport">مطار الوصول</label>
                            <input type="text" class="form-control" name="arrival_airport" value="{{ $contract->arrival_airport }}" placeholder="مطار الوصول ">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="status">حالة العقد</label>
                            <select class="form-control" name="status">
                                @foreach ($statuses as $value => $status)
                                    <option value="{{ $value }}" {{ $value == $contract->status ? 'selected' : '' }}>@lang('contracts.statuses.' . $status)</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col">
                        <div class="form-group">
                            <label for="start_date">بداية التقديم</label>
                            <input type="date" class="form-control" name="start_date" value="{{ $contract->start_date }}">
                        </div>
                    </div>
                    
                    <div class="col">
                        <div class="form-group">
                            <label for="ex_date">مدة التقديم</label>
                            <input type="number" class="form-control" name="ex_date" value="{{ $contract->ex_date }}" placeholder="مدة التقديم">
                        </div>
                    </div>
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
        let cvs = @json($cvs);
        $(function(){
            $(document).on('change, keyup', 'select#country_id, select#office_id, select#profession_id, #age_min, #age_max', function(){
                filter();
            })
            $(document).on('click', '.btn-filter', function(){
                filter();
            })
            selectCustomer();
            $(document).on('change', 'select#customers', function(){
                selectCustomer();
            })
            $(document).on('click', '#table-cvs > tbody > tr.row-cv', function(){
                $(this).removeClass('row-cv')
                $(this).addClass('bg-warning')
                let field_cv_id = $(this).find('input[name=cv_id]')
                if(field_cv_id.length) {
                    field_cv_id.prop('checked', true)
                }
                let rows = '<tr>' + $(this).html() + '</tr>';
                rows += `
                    <tr>
                        <td colspan="6" style="padding: 0;">
                            <table class="table table-bordered" style="margin: 0;">
                                <thead>
                                    <tr>
                                        <th>الجنس</th>
                                        <th>الجواز</th>
                                        <th>العمر</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>` + field_cv_id.data('gender') + `</td>
                                        <td>` + field_cv_id.data('passport') + `</td>
                                        <td>` + field_cv_id.data('age') + `</td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                `;
                $('#country_id').val(field_cv_id.data('country-id'))
                $('#office_id').val(field_cv_id.data('office-id'))
                $('#profession_id').val(field_cv_id.data('profession-id'))
                $('#table-cvs').removeClass('table-striped')
                $('#table-cvs').addClass('table-bordered')
                $('#table-cvs tbody').html(rows)
            })

        })
        function selectCustomer(){
            let selected_option = $('select#customers option:selected');
            let field_customer_name = $('input[name=customer_name]');
            let field_customer_id_number = $('input[name=customer_id_number]');
            let field_customer_id = $('input[name=customer_id]');
            if(selected_option.val() == 'create'){
                field_customer_name.val('')
                field_customer_id_number.val('')
                field_customer_id.removeAttr('value')
                field_customer_name.removeAttr('disabled')
                field_customer_id_number.removeAttr('disabled')
                field_customer_name.attr('required', true)
                field_customer_id.attr('disabled', true)
            }else{
                field_customer_name.val(selected_option.data('name'))
                field_customer_id.val(selected_option.data('id'))
                field_customer_id.attr('value', selected_option.data('id'))
                field_customer_id_number.val(selected_option.data('id-number'))
                field_customer_name.attr('disabled', true)
                field_customer_id_number.attr('disabled', true)
                field_customer_id.removeAttr('disabled')
                field_customer_name.removeAttr('required')
                $('input.parsley-error[name="customer_name"]')
                    .siblings('ul.parsley-errors-list').remove()
                $('input[name="customer_name"]').removeClass('parsley-error')
            }
        }
        function filter(){
            let country_id = $('select#country_id').val()
            let office_id = $('select#office_id').val()
            let profession_id = $('select#profession_id').val()
            let min_age = $('input#age_min').val()
            let max_age = $('input#age_max').val()
            let filtered_cvs = cvs.filter(function(cv, index){
                let condition = true;
                if(country_id != 'all') condition = condition && (cv.country_id == country_id);
                if(office_id != 'all') condition = condition && (cv.office_id == office_id);
                if(profession_id != 'all') condition = condition && (cv.profession_id == profession_id);
                var min = parseInt($('input#age_min').val(), 10);
                var max = parseInt($('input#age_max').val(), 10);
                var age = parseInt(cv.age);
                if(!(min == 0 && max == 0)){
                    condition = condition && ( ( isNaN( min ) && isNaN( max ) ) ||
                        ( isNaN( min ) && age <= max ) ||
                        ( min <= age   && isNaN( max ) ) ||
                        ( min <= age   && age <= max ) 
                    );
                }
                return condition;
            });

            // let cvs_options = (filtered_cvs.length > 0) ? `` : `<option>لا يوجد</option>`;
            let cvs_rows = (filtered_cvs.length > 0) ? `` : `<tr><td colspan="6">لا يوجد</td></tr>`;
            filtered_cvs.forEach(function(cv, index){
                /*
                cvs_options += `
                    <option value="` + cv.id + `">` + cv.passport + `-` + cv.name + `</option>
                `;
                */

                cvs_rows += `
                    <tr class="row-cv">
                        <td>` +  (index + 1) + `</td>
                        <td>` +  cv.country_name + `</td>
                        <td>` +  cv.office_name + `</td>
                        <td>` +  cv.profession_name + `</td>
                        <td>
                            ` +  cv.name + `
                            <input type="hidden" name="cv_id" data-age="` + cv.age + `" data-gender="` + cv.gender + `" data-passport="` + cv.passport + `" data-country-id="` + cv.country_id + `" data-office-id="` + cv.office_id + `" data-profession-id="` + cv.profession_id + `" data-payed="` + cv.payed + `" value="` + cv.id + `">
                        </td>
                        <td>` +  cv.age + `</td>
                    </tr>
                `;
            })
            $('#table-cvs').addClass('table-striped')
            $('#table-cvs').removeClass('table-bordered')
            $('table#table-cvs tbody').html(cvs_rows);
        }
    </script>
@endpush
