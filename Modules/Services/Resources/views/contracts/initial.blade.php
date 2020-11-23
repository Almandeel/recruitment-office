@extends('layouts.' . $layout, $crumbs)
@push('content')
    @if (is_null($contract))
        <div class="p-4">
            <form action="{{ route('contracts.store') }}" method="post">
                @csrf
                <input type="hidden" name="type" value="initial"/>
                <h2 class="text-primary text-center">{{ config('app.name') }}</h2>
                <h3 class="text-primary text-center">
                    <span>نظام الخدمة الذاتية</span>
                </h3>
                <h4 class="text-secondary text-center">
                    <span>إنشاء عقد مبدئي</span>
                </h4>
                @include('partials.messages_in_card')
                @component('components.widget')
                    @slot('noPadding', null)
                    @slot('extra')
                        <div class="form-inline">
                            <div class="form-group mr-2">
                                <label>
                                    <i class="fa fa-filter"></i>
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
                                <span>فرز</span>
                            </button>
                        </div>
                    @endslot
                    @slot('body')
                        <div class="table-container">
                            <table id="table-cvs" class="table table-bordered hidden">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>الدولة</th>
                                        <th>المهنة</th>
                                        <th>العامل / العاملة</th>
                                        <th>العمر</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (!is_null($cv))
                                        <tr>
                                            <td colspan="6" class="hiddenRow">
                                                <div class="accordian-body collapse show p-3" id="cv-{{ $cv->id }}">
                                                    <div class="row">
                                                        <div class="col">
                                                            <p><strong>الدولة</strong> : <span>{{ $cv->country->name }}</span></p>
                                                        </div>
                                                        <div class="col">
                                                            <p><strong>المهنة</strong> : <span>{{ $cv->profession->name }}</span></p>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col">
                                                            <p><strong>الرقم</strong> : <span>{{ $cv->id }}</span></p>
                                                            <input type="hidden" name="cv_id" value="{{ $cv->id }}" 
                                                                data-age="{{ $cv->age() }}" data-gender="{{ $cv->displayGender() }}" 
                                                                data-passport="{{ $cv->passport }}" data-payed="{{ $cv->payed }}"
                                                                data-country-id="{{ $cv->country_id }}" 
                                                                data-profession-id="{{ $cv->profession_id }}"/>
                                                        </div>
                                                        <div class="col">
                                                            <p><strong>الإسم</strong> : <span>{{ $cv->name }}</span></p>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col">
                                                            <p><strong>الجنس</strong> : <span>{{ $cv->displayGender() }}</span></p>
                                                        </div>
                                                        <div class="col">
                                                            <p><strong>العمر</strong> : <span>{{ $cv->age() }}</span></p>
                                                        </div>
                                                        <div class="col">
                                                            <p><strong>عدد الاطفال</strong> : <span>{{ $cv->children }}</span></p>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col">
                                                            <p><strong>الديانة</strong> : <span>{{ $cv->religion }}</span></p>
                                                        </div>
                                                        <div class="col">
                                                            <p><strong>الجنسية</strong> : <span>{{ $cv->nationality }}</span></p>
                                                        </div>
                                                    </div>
                                                    <div class="row col text-center">
                                                        <button class="btn btn-secondary btn-select-cv">
                                                            <i class="fa fa-check"></i>
                                                            إختيار {{ $cv->displayGender() == 'ذكر' ? 'العامل' : 'العاملة' }}
                                                        </button>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                    
                                </tbody>
                            </table>
                        </div>
                        <table id="cv-details" class="table hidden">
                            <thead></thead>
                            <tbody></tbody>
                        </table>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>
                                        <p>بيانات العميل</p>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="tr-gray">
                                        <div class="form-group row p-3">
                                            <div class="col">
                                                <label for="customer_name">الإسم</label>
                                                <input type="text" class="form-control" id="customer_name" name="customer_name" required placeholder="الإسم">
                                            </div>
                                            <div class="col">
                                                <label for="customer_phones">رقم الهاتف</label>
                                                <input type="text" class="form-control" id="customer_phones" name="customer_phones" required placeholder="رقم الهاتف">
                                            </div>
                                            <div class="col">
                                                <label for="customer_id_number">رقم الهوية</label>
                                                <input type="text" class="form-control" id="customer_id_number" name="customer_id_number"
                                                    placeholder="رقم الهوية">
                                            </div>
                                            <div class="col">
                                                <label for="visa">رقم التأشيرة</label>
                                                <input type="text" class="form-control" id="visa" name="visa" placeholder="التأشيرة">
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    @endslot
                    @slot('footer')
                        <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> اكمال العملية</button>
                        <a href="{{ route('home') }}" class="btn btn-secondary">
                            <i class="fa fa-home"></i>
                            <span>عودة إلى الرئيسية</span>
                        </a>
                    @endslot
                @endcomponent
            </form>
        </div>
    @else
        <div class="p-4">
            <section class="invoice p-4">
                {{--  <h1 class="text-center" style="text-decoration: underline">عقد مبدئي</h1>  --}}
                <fieldset>
                    <legend>بيانات العقد</legend>
                    <div class="row">
                        <div class="col">
                            <p><strong>رقم العقد</strong> : <span>{{ $contract->id }}</span></p>
                        </div>
                        <div class="col">
                            <p><strong>تاريخ الإنشاء</strong> : <span>{{ $contract->created_at->format('Y/m/d') }}</span></p>
                        </div>
                    </div>
                </fieldset>
                <fieldset>
                    <legend>بيانات العميل</legend>
                    <div class="row">
                        <div class="col">
                            <p><strong>الإسم</strong> : <span>{{ $contract->customer->name }}</span></p>
                        </div>
                        <div class="col">
                            <p><strong>رقم الهاتف</strong> : <span>{{ $contract->customer->phones }}</span></p>
                        </div>
                    </div>
                </fieldset>
                <fieldset>
                    <legend>بيانات العامل{{ $contract->cv()->displayGender() == 'ذكر' ? '' : 'ة' }}</legend>
                    <div class="row">
                        <div class="col">
                            <p><strong>الدولة</strong> : <span>{{ $contract->cv()->country->name }}</span></p>
                        </div>
                        <div class="col">
                            <p><strong>المهنة</strong> : <span>{{ $contract->cv()->profession->name }}</span></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <p><strong>الرقم</strong> : <span>{{ $contract->cv()->id }}</span></p>
                        </div>
                        <div class="col">
                            <p><strong>الإسم</strong> : <span>{{ $contract->cv()->name }}</span></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <p><strong>الجنس</strong> : <span>{{ $contract->cv()->displayGender() }}</span></p>
                        </div>
                        <div class="col">
                            <p><strong>العمر</strong> : <span>{{ $contract->cv()->age() }}</span></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <p><strong>الديانة</strong> : <span>{{ $contract->cv()->religion }}</span></p>
                        </div>
                        <div class="col">
                            <p><strong>الجنسية</strong> : <span>{{ $contract->cv()->nationality }}</span></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <p><strong>عدد الاطفال</strong> : <span>{{ $contract->cv()->children }}</span></p>
                        </div>
                    </div>
                </fieldset>
            </section>
        </div>
    @endif
@endpush
@push('head')
    <style>
        @if (is_null($contract))
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
        .table tr {
            cursor: pointer;
        }
        .table tr.p td{
            cursor: default;
        }
        .table tr.p:hover{
            cursor: default;
            background-color: transparent;
        }
        .hidden{
            display: none;
        }
        .table tr.p td p{
            border: 1px solid #cccccc;
            padding: 10px 15px;
            background-color: #e0e0e0;
        }
        .table tr.p td p strong{
            color: #666666;
        }
        .table{
            background-color: #fff !important;
        }
        .hedding h1{
            color:#fff;
            font-size:25px;
        }
        .main-section{
            margin-top: 120px;
        }
        .hiddenRow, .tr-gray{
            padding: 0 4px !important;
            background-color: #f7f7f7;
            font-size: 13px;
        }
        .accordian-body span{
            color:#777777 !important;
        }

        .table > tbody > tr:hover{
            background-color: #dddddd;
        }

        .table > tbody > tr.accordion-toggled{
            background-color: #dddddd;
        }

        .table > tbody > tr.accordion-toggled:hover{
            cursor: default;
        }
        .table {
            border: 1px solid #ddd;
            margin-bottom: 30px !important;
        }
        @endif
    </style>
@endpush
@push('foot')
    <script>
        @if (is_null($contract))
        jQuery.fn.outerHTML = function() {
            let tag_name = $(this).prop('tagName').toLowerCase()
            return jQuery('<'+tag_name+' />').append(this.eq(0).clone()).html();
        };
        let cvs = @json($cvs);
        $(function(){
            @if (is_null($cv))
                filter()
            @endif
            $('.accordion-toggle').click(function(){
                // $('.accordion-toggle').removeClass('accordion-toggled')
                // $(this).
                // $('.hiddenRow').hide();
                let index = $(this).data('cv-index')
                let next_hidden_row = $('tr.p[data-cv-index="' + index + '"] .hiddenRow')
                $('.hiddenRow').hide()
                next_hidden_row.show()
            });
            $(document).on('change, keyup', 'select#country_id, select#profession_id, #age_min, #age_max', function(){
                filter();
            })
            $(document).on('click', '.btn-filter', function(){
                filter();
            })

            $(document).on('click', '#table-cvs tbody  tr .btn-select-cv', function(){
                let tbody_row= $(this).closest('tr.p')
                $(this).remove()
                let cv = cvs[tbody_row.data('cv-index')]
                let thead_row = `<tr>
                    <th>بيانات ` + (cv.gender == 'ذكر' ? 'العامل' : 'العاملة') + `
                </tr>`;

                tbody_row.removeAttr('colspan')
                $('#cv-details thead').html(thead_row)
                $('#cv-details tbody').html(tbody_row)
                $('#table-cvs tbody').html('')

                $('#table-cvs').hide()
                $('#cv-details').fadeIn()
            })

        })

        function filter(){
            $('#cv-details').fadeOut()
            let country_id = $('select#country_id').val()
            let profession_id = $('select#profession_id').val()
            let min_age = $('input#age_min').val()
            let max_age = $('input#age_max').val()
            let filtered_cvs = cvs.filter(function(cv, index){
                let condition = true;
                if(country_id != 'all') condition = condition && (cv.country_id == country_id);
                if(profession_id != 'all') condition = condition && (cv.profession_id == profession_id);
                var min = parseInt($('input#age_min').val(), 10);
                var max = parseInt($('input#age_max').val(), 10);
                var age = parseInt(cv.age);
                if(min > 0){
                    condition = condition && age >= min;
                }
                if(max > 0 && max > min){
                    condition = condition && age <= max;
                }
                return condition;
            });
            let thead_row = `
                <tr>
                    <th>#</th>
                    <th>الدولة</th>
                    <th>المهنة</th>
                    <th>العامل / العاملة</th>
                    <th>العمر</th>
                </tr>
            `;
            let cvs_rows = (filtered_cvs.length > 0) ? `` : `<tr><td colspan="6">لا يوجد</td></tr>`;
            filtered_cvs.forEach(function(cv, index){
                /*
                cvs_options += `
                    <option value="` + cv.id + `">` + cv.passport + `-` + cv.name + `</option>
                `;
                */

                cvs_rows += `
                    <tr colspan="6" data-cv-index="` + index + `" data-toggle="collapse" data-target="#cv-` + (index + 1) + `" class="accordion-toggle">
                        <td>` + (index + 1) + `</td>
                        <td>` + cv.country_name + `</td>
                        <td>` + cv.profession_name + `</td>
                        <td>
                            <span>` + cv.name + `</span>
                            </td>
                        <td>` + cv.age + `</td>
                    </tr>
                    <tr class="p" data-cv-index="` + index + `">
                        <td colspan="6" class="hiddenRoww">
                            <div class="accordian-body collapse p-3" id="cv-` + (index + 1) + `">
                                <div class="row">
                                    <div class="col">
                                        <p><strong>المهنة</strong> : <span>` + cv.profession_name + `</span></p>
                                    </div>
                                    <div class="col">
                                        <p><strong>الديانة</strong> : <span>` + cv.religion + `</span></p>
                                    </div>
                                    <div class="col">
                                    <p><strong>النوع</strong> : <span>` + cv.gender + `</span></p>
                                    </div>
                                    
                                    <div class="col">
                                        <p><strong>الدولة</strong> : <span>` + cv.country_name + `</span></p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <p><strong>مكان الميلاد</strong> : <span>` + cv.birthplace + `</span></p>
                                    </div>
                                    <div class="col">
                                        <p><strong>تاريخ الميلاد</strong> : <span>` + cv.birth_date + `</span></p>
                                    </div>
                                    
                                    <div class="col">
                                        <p><strong>الحالة الاجتماعية</strong> : <span>` + cv.marital_status + `</span></p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <p><strong>عدد الاطفال</strong> : <span>` + cv.children + `</span></p>
                                    </div>
                                    <div class="col">
                                        <p><strong>الجوال</strong> : <span>` + cv.phone + `</span></p>
                                    </div>
                                    
                                    <div class="col">
                                        <p><strong>المستوي الدراسي</strong> : <span>` + cv.qualification + `</span></p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <p><strong>اللغة الانجليزية</strong> : <span>` + cv.english_speaking_level + `</span></p>
                                    </div>
                                    <div class="col">
                                        <p><strong>الخبرة في الخارج</strong> : <span>` + cv.experince + `</span></p>
                                    </div>
                                
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <p><strong>الوزن</strong> : <span>` + cv.weight + `</span></p>
                                    </div>
                                    <div class="col">
                                        <p><strong> الطول</strong> : <span>` + cv.height + `</span></p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <p><strong>الخياطه</strong> : <span>` + cv.sewing + `</span></p>
                                    </div>
                                    <div class="col">
                                        <p><strong> الديكور</strong> : <span>` + cv.decor + `</span></p>
                                    </div>
                                    <div class="col">
                                        <p><strong> النتظيف</strong> : <span>` + cv.cleaning + `</span></p>
                                    </div>
                                    <div class="col">
                                        <p><strong> الغسيل</strong> : <span>` + cv.washing + `</span></p>
                                    </div>
                                    <div class="col">
                                        <p><strong> الطبخ</strong> : <span>` + cv.cooking + `</span></p>
                                    </div>
                                    <div class="col">
                                        <p><strong> تربية الاطفال </strong> : <span>` + cv.babysitting + `</span></p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <p><strong>رقم الجواز</strong> : <span>` + cv.passport + `</span></p>
                                    </div>
                                    <div class="col">
                                        <p><strong> مكان الاصدار </strong> : <span>` + cv.passport_place_of_issue + `</span></p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <p><strong>تاريخ الاصدار </strong> : <span>` + cv.passport_issuing_date + `</span></p>
                                    </div>
                                    <div class="col">
                                        <p><strong> تاريخ الانتهاء  </strong> : <span>` + cv.passport_expiration_date + `</span></p>
                                    </div>
                                </div>
                                
                                
                                <div class="row">
                                    <div class="col">
                                        <input type="hidden" name="cv_id" value="` + cv.id + `" 
                                            data-age="` + cv.age + `" data-gender="` + cv.gender + `" 
                                            data-passport="` + cv.passport + `" data-payed="` + cv.payed + `"
                                            data-country-id="` + cv.country_id + `" 
                                            data-profession-id="` + cv.profession_id + `"/>
                                    </div>
                                    
                                </div>
                                
                            </div>
                        </td>
                    </tr>
                `;
            })
            // $('#table-cvs').addClass('table-striped')
            // $('#table-cvs').removeClass('table-bordered')
            $('table#table-cvs thead').html(thead_row)
            $('table#table-cvs tbody').html(cvs_rows);
            $('#table-cvs').fadeIn()
        }
        @endif
    </script>
@endpush
