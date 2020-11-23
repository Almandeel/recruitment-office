@extends('layouts.master', [
    'title' => 'اضافة cv',
    'modals' => ['customer'],
    'crumbs' => [
        [route('servicescvs.index'), 'cvs'],
        ['#', 'اضافة'],
    ]
])

@section('content')
    <section class="content">
        <div class="card">
                <form action="{{ route('servicescvs.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
            <div class="card-header">
                <h3 class="card-title">اضافة</h3>
                <input type="text" class="float-right" name="reference_number" placeholder="الرقم المرجعي">
            </div>
            <div class="card-body">
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
                           <input type="text" class="form-control" name="name" placeholder="الاسم" required>
                        </div>
                     </div>
               <div class="col-md-3">
                         <div class="form-group">
                             <label for="gender">النوع</label>
                          <select class="custom-select" name="gender" required>
                                    <option value="1">ذكر</option>
                                    <option value="2">انثي</option>
                                </select>
                         </div>
                     </div>
               <div class="col-md-3">
                         <div class="form-group">
                            <label for="passport">الديانة</label>
                            <input type="text" class="form-control" name="religion" placeholder="الديانة" required >
                         </div>
                     </div>
            
             <div class="col-md-3">
                         <div class="form-group">
                             <label for="profession_id">المهنة</label>
                               <select class="custom-select" name="profession_id" required>
                                    <option selected disabled value="">المهنة </option>
                                    @foreach($professions as $profession)
                                        <option value="{{ $profession->id }}">{{ $profession->name }}</option>
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
                                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                                    @endforeach
                                </select>
                         </div>
                     </div>
                      <div class="col-md-3">
                         <div class="form-group">
                             <label for="passport">مكان الميلاد</label>
                             <input type="text" class="form-control" name="birthplace" placeholder=" مكان الميلاد" required="">
                         </div>
                     </div>
     
                      
                 
                     <div class="col-md-3">
                         <div class="form-group">
                             <label for="birth_date">تاريخ الميلاد</label>
                            <input type="date" class="form-control" name="birth_date" placeholder="تاريخ الميلاد" required>
                         </div>
                     </div>

            
             <div class="col-md-3">
                         <div class="form-group">
                             <label for="passport">الحالة الاجتماعية</label>
                                    <input type="text" class="form-control" name="marital_status" placeholder="الحالة الاجتماعية" required>
                         </div>
                     </div>
  <div class="col-md-3">
                         <div class="form-group">
                             <label for="passport"> عدد الاطفال</label>
                             <input type="number" class="form-control" name="children" placeholder="عدد الأطفال" required min="0">
                         </div>
                     </div>
             
             <div class="col-md-3">
                         <div class="form-group">
                             <label for="passport"> رقم   الاتصال</label>
                             <input type="number" class="form-control" name="phone" placeholder=" رقم الاتصال" required="">
                         </div>
                     </div>
               <div class="col-md-3">
                         <div class="form-group">
                             <label for="passport"> المستوي الدراسي</label>
                             <input type="text" class="form-control" name="qualification" placeholder=" المستوي الدراسي" required="">
                         </div>
                     </div>
                <div class="col-md-3">
                         <div class="form-group">
                             <label for="passport">  اللغه الانجليزية</label>
                             <input type="text" class="form-control" name="english_speaking_level" placeholder="  اللغه الانجليزية" required="">
                         </div>
                     </div>
              <div class="col-md-3">
                         <div class="form-group">
                             <label for="passport">   الخبرة ف الخارج</label>
                             <input type="text" class="form-control" name="experince" placeholder="  اللغه الانجليزية" required="">
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
                             <input type="number" class="form-control" name="weight" placeholder="الوزن " required="">
                         </div>
                     </div>
                         <div class="col-md-1">
                         <div class="form-group">
                             <label for="passport"> الطول</label>
                             <input type="number" class="form-control" name="height" placeholder="الطول " required="">
                         </div>
                     </div>
                      <div class="col-md-1">
                         <div class="form-group">
                             <label for="passport"> الخياطه</label>
                         
                             
                             <select class="custom-select" name="sewing" required="">
                                 <option selected="" disabled="" value="">____ </option>
                                 <option value="1">Yes</option>
                                 <option value="0">No</option>
                           
                             </select>
                         </div>
                     </div>
                         <div class="col-md-1">
                         <div class="form-group">
                             <label for="passport"> الديكور</label>
                                  
                             <select class="custom-select" name="decor" required="">
                                 <option selected="" disabled="" value="">____ </option>
                                 <option value="1">Yes</option>
                                 <option value="0">No</option>
                           
                             </select>
                         </div>
                     </div>
                       <div class="col-md-1">
                         <div class="form-group">
                             <label for="passport"> التنظيف</label>
                                 
                             <select class="custom-select" name="cleaning" required="">
                                 <option selected="" disabled="" value="">____ </option>
                                 <option value="1">Yes</option>
                                 <option value="0">No</option>
                           
                             </select>
                         </div>
                     </div>
                       <div class="col-md-1">
                         <div class="form-group">
                             <label for="passport"> الغسيل</label>
                                  
                             <select class="custom-select" name="washing" required="">
                                 <option selected="" disabled="" value="">____ </option>
                                 <option value="1">Yes</option>
                                 <option value="0">No</option>
                           
                             </select>
                         </div>
                     </div>
                       <div class="col-md-1">
                         <div class="form-group">
                             <label for="passport"> الطبخ</label>
                                 <select class="custom-select" name="cooking" required="">
                                 <option selected="" disabled="" value="">____ </option>
                                 <option value="1">Yes</option>
                                 <option value="0">No</option>
                           
                             </select>
                         </div>
                     </div>
                        <div class="col-md-2">
                         <div class="form-group">
                             <label for="passport"> تربية الاطفال </label>
                                
                             <select class="custom-select" name="babysitting" required="">
                                 <option selected="" disabled="" value="">____ </option>
                                 <option value="1">Yes</option>
                                 <option value="0">No</option>
                           
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
                           <input type="text" class="form-control" name="passport" placeholder="رقم الجواز" required>
                         </div>
                     </div>
             <div class="col-md-3">
                         <div class="form-group">
                             <label for="passport"> مكان الاصدار</label>
                             <input type="text" class="form-control" name="passport_place_of_issue" placeholder="رقم الجواز" required="">
                         </div>
                     </div>
              <div class="col-md-3">
                         <div class="form-group">
                             <label for="passport">  تاريخ الاصدار</label>
                             <input type="date" class="form-control" name="passport_issuing_date" placeholder=" تاريخ الاصدار" required="">
                         </div>
                     </div>
               <div class="col-md-3">
                         <div class="form-group">
                             <label for="passport">  تاريخ الانتهاء</label>
                             <input type="date" class="form-control" name="passport_expiration_date" placeholder=" تاريخ الانتهاء" required="" >
                         </div>
                     </div>
                 </div>
                               <div class="col-md-2 addphoto">
                   <div class="left-block">
    
                    <div class="img"></div>
                    <div class="input-file">
                        <input type="file" name="passport_photo">
                        <label for="add-photo"> اضافة صورة الجواز</label>
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
                            <input type="number" class="form-control" name="amount" placeholder="القيمة" required>
                         </div>
                     </div>
                    

                     <div class="col-md-3">
                         <div class="form-group">
                             <label for="procedure">مده العقد</label>
                             <input type="text" class="form-control" name="contract_period"   placeholder="مده العقد" required="">
                         </div>
                     </div>
                       <div class="col-md-3">
                         <div class="form-group">
                             <label for="procedure"> الراتب</label>
                             <input type="number" class="form-control" name="contract_salary"   placeholder="مده العقد" required="">
                         </div>
                     </div>
                 </div>

                 <div class="col-md-12">
                    <div class="form-group">
                        <label for="procedure">نبذه مختصرة وملاحظات </label>
                        <br>
                    <textarea rows="3" cols="100" name="bio"></textarea>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="procedure"> الاجراء</label>
                        <textarea name="procedure" rows="3" cols="100">
                    
                    </textarea>
                    </div>
                </div>
                     
                     </div></div></div>
                </div>
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </section>
@endsection

@push('foot')
{{-- <script>
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
</script>     --}}
@endpush