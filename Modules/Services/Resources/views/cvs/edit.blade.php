@extends('layouts.master', [
    'title' => ' cv  تعديل ' . $cv->name,
    'modals' => ['customer', 'attachment'],
    'crumbs' => [
        [route('servicescvs.index'), 'cvs'],
        ['#', ' تعديل ' . $cv->name],
    ]
])

@section('content')
    <section class="content">
        <form action="{{ route('servicescvs.update', $cv) }}" method="post">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">تعديل {{ $cv->name }}</h3>
                    <input type="text" class="float-right" name="reference_number" value="{{ $cv->reference_number }}">
                </div>

                <div class="card-body">
                        @csrf
                        @method('PUT')
                    <div class="row">
                        <div class="col-md-12 row">
                            <div class="col-md-6">
                                <h5> البيانات الاساسية</h5>
                            </div>
                        </div>
                            <br><br>  
            <div class="col-md-9 row">
            
                    
                <div class="col-md-12">
                            <div class="form-group">
                                <label for="name">العامل \ العاملة</label>
                            <input type="text" class="form-control" name="name" placeholder="الاسم" required value="{{ $cv->name }}">
                            </div>
                        </div>
                <div class="col-md-3">
                            <div class="form-group">
                                <label for="gender">النوع</label>
                            <select class="custom-select" name="gender" required>
                                        <option @if ($cv->gender == 1) selected @endif value="1">ذكر</option>
                                        <option @if ($cv->gender == 2) selected @endif value="2">انثي</option>
                                    </select>
                            </div>
                        </div>
                <div class="col-md-3">
                            <div class="form-group">
                                <label for="passport">الديانة</label>
                                <input type="text" class="form-control" name="religion" placeholder="الديانة" required value="{{ $cv->religion }}">
                            </div>
                        </div>
                
                <div class="col-md-3">
                            <div class="form-group">
                                <label for="profession_id">المهنة</label>
                                <select class="custom-select" name="profession_id" required>
                                        <option selected disabled value="">المهنة </option>
                                        @foreach($professions as $profession)
                                            <option @if ($cv->profession_id == $profession->id) selected @endif value="{{ $profession->id }}">{{ $profession->name }}</option>
                                        @endforeach
                                    </select>
                            </div>
                        </div>

                            <div class="col-md-3">
                            <div class="form-group">
                                <label for="country_id">الدولة</label>
                            <select class="custom-select" name="country_id" required>
                                        <option selected disabled value="">الدولة </option>
                                        @foreach($countries as $country)
                                            <option @if ($cv->country_id == $country->id) selected @endif value="{{ $country->id }}">{{ $country->name }}</option>
                                        @endforeach
                                    </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="passport">مكان الميلاد</label>
                                <input type="text" class="form-control" name="birthplace" placeholder=" مكان الميلاد" required="" value="{{ $cv->birthplace }}">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="birth_date">تاريخ الميلاد</label>
                                <input type="date" class="form-control" name="birth_date" placeholder="تاريخ الميلاد" required value="{{ $cv->birth_date }}">
                            </div>
                        </div>

                <div class="col-md-3">
                            <div class="form-group">
                                <label for="passport">الحالة الاجتماعية</label>
                                        <input type="text" class="form-control" name="marital_status" placeholder="الحالة الاجتماعية" requir value="{{ $cv->marital_status }}">
                            </div>
                        </div>
    <div class="col-md-3">
                            <div class="form-group">
                                <label for="passport"> عدد الاطفال</label>
                                <input type="number" class="form-control" name="children" placeholder="عدد الأطفال" required min="0" value="{{ $cv->children }}">
                            </div>
                        </div>
                
                <div class="col-md-3">
                            <div class="form-group">
                                <label for="passport"> رقم   الاتصال</label>
                                <input type="number" class="form-control" name="phone" placeholder=" رقم الاتصال" required="" value="{{ $cv->phone }}">
                            </div>
                        </div>
                <div class="col-md-3">
                            <div class="form-group">
                                <label for="passport"> المستوي الدراسي</label>
                                <input type="text" class="form-control" name="qualification" placeholder=" المستوي الدراسي" required="" value="{{ $cv->qualification }}">
                            </div>
                        </div>
                    <div class="col-md-3">
                            <div class="form-group">
                                <label for="passport">  اللغه الانجليزية</label>
                                <input type="text" class="form-control" name="english_speaking_level" placeholder="  اللغه الانجليزية" required="" value="{{ $cv->english_speaking_level }}">
                            </div>
                        </div>
                <div class="col-md-3">
                            <div class="form-group">
                                <label for="passport">   الخبرة ف الخارج</label>
                                <input type="text" class="form-control" name="experince" placeholder="  اللغه الانجليزية" required="" value="{{ $cv->experince }}">
                            </div>
                        </div>
                        </div>
            <div class="col-md-2 addphoto">
                    <div class="left-block">
        
                    
    <div class="img"></div>
    <div class="input-file">
        <input id="add-photo" type="file" name="photo">
        <label for="add-photo"> اضافه الصورة الشخصية</label>
    </div>
    </div>
    <style>
        .addphoto .img {

        height: 233px}
        .addphoto .left-block {
        width: 154%;}
    </style>
            
                
                </div>
                        <div class="col-md-12">
                            <h5>بيانات اضافية</h5>
                            
                        </div>
                        <br><br> 
                            <div class="col-md-1">
                            <div class="form-group">
                                <label for="passport"> الوزن</label>
                                <input type="number" class="form-control" name="weight" placeholder="الوزن " required="" value="{{ $cv->weight }}">
                            </div>
                        </div>
                            <div class="col-md-1">
                            <div class="form-group">
                                <label for="passport"> الطول</label>
                                <input type="number" class="form-control" name="height" placeholder="الطول " required="" value="{{ $cv->height }}">
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label for="passport"> الخياطه</label>
                                <select class="custom-select" name="sewing" required="">
                                    <option selected="" disabled="" value="">____ </option>
                                    <option @if($cv->sewing == true) selected @endif value="1">Yes</option>
                                    <option @if($cv->sewing == false) selected @endif value="0">No</option>
                            
                                </select>
                            </div>
                        </div>
                            <div class="col-md-1">
                            <div class="form-group">
                                <label for="passport"> الديكور</label>
                                    
                                <select class="custom-select" name="decor" required="">
                                    <option selected="" disabled="" value="">____ </option>
                                    <option @if($cv->decor == true) selected @endif value="1">Yes</option>
                                    <option @if($cv->decor == false) selected @endif value="0">No</option>
                            
                                </select>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label for="passport"> التنظيف</label>
                                    
                                <select class="custom-select" name="cleaning" required="">
                                    <option selected="" disabled="" value="">____ </option>
                                    <option @if($cv->cleaning == true) selected @endif value="1">Yes</option>
                                    <option @if($cv->cleaning == false) selected @endif value="0">No</option>
                            
                                </select>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label for="passport"> الغسيل</label>
                                    
                                <select class="custom-select" name="washing" required="">
                                    <option selected="" disabled="" value="">____ </option>
                                    <option @if($cv->washing == true) selected @endif value="1">Yes</option>
                                    <option @if($cv->washing == false) selected @endif value="0">No</option>
                            
                                </select>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label for="passport"> الطبخ</label>
                                    <select class="custom-select" name="cooking" required="">
                                    <option selected="" disabled="" value="">____ </option>
                                    <option @if($cv->cooking == true) selected @endif value="1">Yes</option>
                                    <option @if($cv->cooking == false) selected @endif value="0">No</option>
                            
                                </select>
                            </div>
                        </div>
                            <div class="col-md-2">
                            <div class="form-group">
                                <label for="passport"> تربية الاطفال </label>
                                    
                                <select class="custom-select" name="babysitting" required="">
                                    <option selected="" disabled="" value="">____ </option>
                                    <option @if($cv->babysitting == true) selected @endif value="1">Yes</option>
                                    <option @if($cv->babysitting == false) selected @endif value="0">No</option>
                            
                                </select>
                            </div>
                        </div>
                        
                                
                        <div class="col-md-12">
                            <br><br>
                        <h5 
    
    > بيانات الجواز</h5>  
                    </div>
                    
                    
                    <br><br>
                    
                    <div class="col-md-12 row">
                    
            <div class="col-md-9 row" style="
        padding-top: 50px;
    ">
        <div class="col-md-3">
                            <div class="form-group">
                                <label for="passport">رقم الجواز</label>
                            <input type="text" class="form-control" name="passport" placeholder="رقم الجواز" required value="{{ $cv->passport }}">
                            </div>
                        </div>
                <div class="col-md-3">
                            <div class="form-group">
                                <label for="passport"> مكان الاصدار</label>
                                <input type="text" class="form-control" name="passport_place_of_issue" placeholder="رقم الجواز" required="" value="{{ $cv->passport_place_of_issue }}">
                            </div>
                        </div>
                <div class="col-md-3">
                            <div class="form-group">
                                <label for="passport">  تاريخ الاصدار</label>
                                <input type="date" class="form-control" name="passport_issuing_date" placeholder=" تاريخ الاصدار" required="" value="{{ $cv->passport_issuing_date }}">
                            </div>
                        </div>
                <div class="col-md-3">
                            <div class="form-group">
                                <label for="passport">  تاريخ الانتهاء</label>
                                <input type="date" class="form-control" name="passport_expiration_date" placeholder=" تاريخ الانتهاء" required=""  value="{{ $cv->passport_expiration_date }}">
                            </div>
                        </div>
                    </div>
                                <div class="col-md-2 addphoto">
                    <div class="left-block">
        
                    
                    <div class="img">
                    </div>
                    <div class="input-file">
                        <input type="file" name="passport_photo" >
                        <label> اضافة صورة الجواز</label>
                    </div>
            
                
                </div>  
                        
                </div>        

                    
                
    <div  class="col-md-12">
        <h5>
                        
                        تفاصيل العقد
                        </h5>
                    </div>
                    
                    <div class="col-md-12 row">

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="amount">القيمة</label>                                
                                <input type="number" class="form-control" name="amount" placeholder="القيمة" required value="{{ $cv->amount }}">
                            </div>
                        </div>
                        

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="procedure">مده العقد</label>
                                <input type="text" class="form-control" name="contract_period"   placeholder="مده العقد" required=""  value="{{ $cv->contract_period }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="procedure"> الراتب</label>
                                <input type="number" class="form-control" name="contract_salary"   placeholder="مده العقد" required=""  value="{{ $cv->contract_salary }}">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="procedure">نبذه مختصرة وملاحظات </label>
                            <br>
                        <textarea rows="3" cols="100" name="bio">{{ $cv->bio }}</textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="procedure"> الاجراء</label>
                            <textarea name="procedure" rows="3" cols="100"  >{{ $cv->procedure }}</textarea>
                        </div>
                    </div>
                        
                        </div></div></div>
                            <div class="col-md-12">
                                @component('components.widget')
                                    @slot('noTitle', true)
                                    @slot('title')
                                        <i class="fas fa-paperclip"></i>
                                        <span>المرفقات</span>
                                    @endslot
                                    @slot('body')
                                        @component('components.attachments-uploader')
                                        @endcomponent
                                    @endslot
                                @endcomponent
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </div>
        </form>
    </section>
@endsection

@push('foot')
<script>
    $('.add-photo').change(function() {
        readURL(this);
    });

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                var templImg = e.target.result;
                $('.img').css("background-image", "url(" + e.target.result + ")");
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>    
@endpush