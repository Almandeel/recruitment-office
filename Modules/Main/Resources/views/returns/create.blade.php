@extends('layouts.master', [
    'title' => 'إرجاع cv',
    'datatable' => true, 
    'modals' => [],
    'crumbs' => [
        [route('offices.index'), 'المكاتب الخارجية'],
        ['#', 'إرجاع cv']
    ]
])
@section('content')
    <form action="{{ route('offices.returns.store') }}" method="post">
        @csrf
        @component('components.widget')
            @slot('noPadding', true)
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
                    {{--  <div class="form-group">
                        <label for="cv_id">cv</label>
                        <select name="cv_id" id="cv_id" class="form-control" required>
                            <option>إختر cv</option>
                            @foreach ($cvs as $cv)
                                <option value="{{ $cv['id'] }}">{{ $cv['passport'] . '-' . $cv['name'] }}</option>
                            @endforeach
                        </select>
                    </div>  --}}
                    <button type="button" class="btn btn-primary btn-filter">
                        <i class="fa fa-refresh"></i>
                        <span>تحديث</span>
                    </button>
                </div>
            @endslot
            @slot('body')
                <div class="table-container">
                    <table id="table-cvs" class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>الدولة</th>
                                {{--  <th>
                                    <div class="input-group">
                                        <div class="input-group-append">الدولة</div>
                                        <select name="country_id" id="country_id" class="form-control">
                                            <option value="all">الكل</option>
                                            @foreach ($countries as $country)
                                            <option value="{{ $country->id }}">{{ $country->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </th>  --}}
                                <th>المكتب</th>
                                {{--  <th>
                                    <div class="input-group">
                                        <div class="input-group-append">المكتب</div>
                                        <select name="office_id" id="office_id" class="form-control">
                                            <option value="all">الكل</option>
                                            @foreach ($offices as $office)
                                            <option value="{{ $office->id }}">{{ $office->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </th>  --}}
                                <th>المهنة</th>
                                {{--  <th>
                                    <div class="input-group">
                                        <div class="input-group-append">المهنة</div>
                                        <select name="profession_id" id="profession_id" class="form-control">
                                            <option value="all">الكل</option>
                                            @foreach ($professions as $profession)
                                            <option value="{{ $profession->id }}">{{ $profession->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </th>  --}}
                                <th>
                                    <strong>CV</strong>
                                </th>
                                {{--  <th class="text-center"><input type="radio" disabled checked></th>  --}}
                            </tr>
                        </thead>
                        <tbody>
                            @if (is_null($cv))
                                @foreach ($cvs as $c)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $c['country_name'] }}</td>
                                    <td>{{ $c['office_name'] }}</td>
                                    <td>{{ $c['profession_name'] }}</td>
                                    <td>{{ $c['name'] }}</td>
                                    <input type="hidden" name="cv_id" data-payed="{{ $c['payed'] }}" value="{{ $c['id'] }}"></td>
                                </tr>
                                @endforeach
                            @else
                                <tr class="bg-warning">
                                    <td>{{ 1 }}</td>
                                    <td>{{ $cv->country->name }}</td>
                                    <td>{{ $cv->office->name }}</td>
                                    <td>{{ $cv->profession->name }}</td>
                                    <td>{{ $cv->name }}</td>
                                    <input type="hidden" name="cv_id" data-payed="{{ $cv->payed() }}" value="{{ $cv->id }}"></td>
                                </tr>
                            @endif
                            
                        </tbody>
                    </table>
                </div>
                @component('components.attachments-uploader')
                @endcomponent
            @endslot
            @slot('footer')
                <div class="form-inline">
                    <div class="form-group mr-2">
                        <label for="payed">إجمالي المدفوع</label>
                        <input type="number" id="payed" name="payed" min="0" class="form-control" value="{{ is_null($cv) ? 0 : $cv->payed() }}">
                    </div>
                    <div class="form-group mr-2">
                        <label for="damages">إجمالي الاضرار</label>
                        <input type="number" id="damages" name="damages" min="0" class="form-control" value="0">
                    </div>
                    <button type="submit" class="btn btn-warning">
                        <i class="fa fa-reply"></i>
                        <span>إرجاع</span>
                    </button>
                </div>
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
    </style>
@endpush
@push('foot')
    <script>
        let cvs = @json($cvs);
        $(function(){
            $(document).on('change', 'select#country_id, select#office_id, select#profession_id', function(){
                filter();
            })
            $(document).on('click', '.btn-filter', function(){
                filter();
            })
            $(document).on('click', '#table-cvs tbody tr', function(){
                // $('#table-cvs tbody tr').removeClass('bg-warning')
                $(this).addClass('bg-warning')
                let field_payed = $('input[name=payed]');
                let field_damages = $('input[name=damages]');
                let field_cv_id = $(this).find('input[name=cv_id]')
                if(field_cv_id.length) {
                    field_cv_id.prop('checked', true)
                    field_payed.val(field_cv_id.data('payed'))
                }

                $('#table-cvs tbody').html(this)
            })

        })
        function filter(){
            let field_payed = $('input[name=payed]');
            let field_damages = $('input[name=damages]');

            let country_id = $('select#country_id').val()
            let office_id = $('select#office_id').val()
            let profession_id = $('select#profession_id').val()
            let filtered_cvs = cvs.filter(function(cv, index){
                let condition = true;
                if(country_id != 'all') condition = condition && (cv.country_id == country_id);
                if(office_id != 'all') condition = condition && (cv.office_id == office_id);
                if(profession_id != 'all') condition = condition && (cv.profession_id == profession_id);

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
                    <tr>
                        <td>` +  (index + 1) + `</td>
                        <td>` +  cv.country_name + `</td>
                        <td>` +  cv.office_name + `</td>
                        <td>` +  cv.profession_name + `</td>
                        <td>` +  cv.name + `</td>
                            <input type="hidden" name="cv_id" data-payed="` + cv.payed + `" value="` + cv.id + `"></td>
                    </tr>
                `;
            })
            field_payed.val(0)
            field_damages.val(0)
            // $('select#cv_id').html(cvs_options);
            $('table#table-cvs tbody').html(cvs_rows);
        }
    </script>
@endpush
