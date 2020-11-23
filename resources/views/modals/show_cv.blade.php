<div class="modal fade modal-lg" id="showCvModal" tabindex="-1" role="dialog" aria-labelledby="taskLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content modal-big">
            <div class="modal-header">
                <h5 class="modal-title pull-left" id="taskLabel"></h5>
                <button type="button" class="close pull-right" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-header">
                        <div class="col-md-12 row">
                            <div class="col-md-6">
                                <h5 style="font-weight: bold; color: #032cc3;">  المعرف :{{ $contract->cv()->reference_number }}</h5>
                            </div>
                            <div class="col-md-6">
                                <h3 class="card-title"  style="float: left; ">تاريخ الانشاء:
                                    {{ $contract->cv()->created_at->format('Y-m-d') }}
                                </h3>
                                <h6  class="gg" style="float: left; padding-left: 34px;">الحالة   :
                                    <span class="badge badge-info">
                                        @if (! $contract->cv()->pull)
                                            <p style="margin-bottom: 2px;" class="{{ $contract->cv()->accepted ? '' : 'text-warnying' }}">
                                                {{-- {{ $contract->cv()->accepted ? 'تمت الموافقة' : 'في الانتظار' }} --}}
                                                @if (!$contract->cv()->pull)
                                                    @if($contract->cv()->status == Modules\ExternalOffice\Models\Cv::STATUS_CONTRACTED)
                                                        <span class="">
                                                            تم عمل عقد
                                                        </span>
                                                    @endif
                                                    @if($contract->cv()->status == Modules\ExternalOffice\Models\Cv::STATUS_ACCEPTED)
                                                        <span class="">
                                                            تمت الموافقة
                                                        </span>
                                                    @endif
                                                    @if($contract->cv()->status == Modules\ExternalOffice\Models\Cv::STATUS_WAITING)
                                                        <span class="text-warning">
                                                            في الانتظار
                                                        </span>
                                                    @endif
                                                @elseif ($contract->cv()->status == Modules\ExternalOffice\Models\Cv::STATUS_PULLED)
                                                    <p class="text-info">تم تقديم طلب سحب</p>
                                                @elseif ($contract->cv()->status == Modules\ExternalOffice\Models\Cv::STATUS_PULLED)
                                                    <p class="text-danger">تم السحب</p>
                                                @endif
                                            </p>
                                        @elseif ($contract->cv()->pull && ! $contract->cv()->pull->confirmed)
                                            <p >تم تقديم طلب إرجاع</p>
                                        @elseif ($contract->cv()->pull && $contract->cv()->pull->confirmed)
                                            <p class="text-muted">تم الإرجاع</p>
                                        @endif
                                    </span>
                                </h6>
                            </div> 
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 row">
                                <div class="col-md-6">
                                    <h5 style="font-weight: bold;">البيانات الاساسية</h5>
                                </div>
                                <div class="col-md-9 row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="name">العامل \ العاملة</label>
                                            <h3>{{ $contract->cv()->name }}</h3>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <h5>المهنة : {{ $contract->cv()->profession->name }}</h5>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <h5>الديانه : {{ $contract->cv()->religion }}</h5>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <h5>النوع : {{ $contract->cv()->gender == 1 ? 'ذكر' : 'أنثى' }}</h5>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <h5>الدوله : {{ $contract->cv()->country->name ?? '' }}</h5>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <h5>مكان الميلاد : {{ $contract->cv()->birthplace ?? '' }}</h5>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <h5>تاريخ الميلاد : {{ $contract->cv()->birth_date ?? '' }}</h5>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <h5>الحالة الاجتماعية : {{ $contract->cv()->marital_status }}</h5>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <h5>عدد الاطفال : {{ $contract->cv()->children }}</h5>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <h5>الجوال : {{ $contract->cv()->phone }}</h5>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <h5>المستوي الدراسي : {{ $contract->cv()->qualification }}</h5>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <h5>اللغه الانجليزية : {{ $contract->cv()->english_speaking_level }}</h5>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <h5>الخبرة في الخارج : {{ $contract->cv()->experince }}</h5>
                                        </div>
                                    </div>
                                </div>
                
                                <div class="col-md-3 pic">
                                    <div class="left-block">
                                        <img src="{{ asset('cvs_data/' . $contract->cv()->photo) }}" width="100%" height="200" />
                                        <h6 style="text-align: center; font-weight: bold; padding-top: 6px;">الصورة الشخصية</h6>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <br />
                                    <h5 style="font-weight: bold;">بيانات اضافية</h5>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <h5>الوزن : {{ $contract->cv()->weight }}</h5>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <h5>الطول : {{ $contract->cv()->height }}</h5>
                                    </div>
                                </div>
                                <div class="col-md-8"></div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <h5>الخياطه : {{ $contract->cv()->sewing ? 'Yes' : 'No' }}</h5>
                                    </div>
                                </div>
                
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <h5>الديكور : {{ $contract->cv()->decor ? 'Yes' : 'No' }}</h5>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <h5>النتظيف : {{ $contract->cv()->cleaning ? 'Yes' : 'No' }}</h5>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <h5>الغسيل : {{ $contract->cv()->washing ? 'Yes' : 'No' }}</h5>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <h5>الطبخ : {{ $contract->cv()->cooking ? 'Yes' : 'No' }}</h5>
                                    </div>
                                </div>
                
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <h5>تربية الاطفال : {{ $contract->cv()->babysitting ? 'Yes' : 'No' }}</h5>
                                    </div>
                                </div>
                                <br />
                                <br />
                
                                <div class="col-md-12">
                                    <br />
                                    <br />
                                    <h5 style="font-weight: bold;">بيانات الجواز</h5>
                                </div>
                
                                <div class="col-md-12 row">
                                    <div class="col-md-9 row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <h5>رقم الجواز :{{ $contract->cv()->passport }}</h5>
                                            </div>
                                        </div>
                
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <h5>مكان الاصدار : {{ $contract->cv()->passport_place_of_issue }}</h5>
                                            </div>
                                        </div>
                
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <h5 style="margin-top: -59px;">تاريخ الاصدار : {{ $contract->cv()->passport_issuing_date }}</h5>
                                            </div>
                                        </div>
                
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <h5 style="margin-top: -59px;">تاريخ الانتهاء : {{ $contract->cv()->passport_expiration_date }}</h5>
                                            </div>
                                        </div>
                                    </div>
                
                                    <div class="col-md-3 pic">
                                        <div class="left-block">
                                            <img style="margin-top: -59px;" src="{{ asset('cvs_data/' . $contract->cv()->passport_photo) }}" width="100%" height="200" />
                                            <h6 style="text-align: center; font-weight: bold; padding-top: 6px;">صورة الجواز</h6>
                                        </div>
                                    </div>
                                </div>
                
                                <div class="col-md-12">
                                    <h5 style="font-weight: bold;">
                                        تفاصيل العقد
                                    </h5>
                                </div>
                
                                <div class="col-md-12 row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <h5>القيمة : {{ number_format($contract->cv()->amount, 2) }}</h5>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <h5>مدة العقد : {{ $contract->cv()->contract_period }}</h5>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <h5>الراتب : {{ $contract->cv()->contract_salary }}</h5>
                                        </div>
                                    </div>
                                </div>
                
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <label for="procedure">نبذه مختصرة وملاحظات </label>
                
                                        <br />
                                        <span>
                                            {{ $contract->cv()->bio ?? '' }}
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <h5 style="font-weight: bold;">الاجراء :</h5>
                
                                        <h5>
                                            {{ $contract->cv()->procedure ?? '' }}
                                        </h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>