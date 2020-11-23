@extends('externaloffice::layouts.master', [
    'title' => 'New cv',
    'crumbs' => [
        [route('cvs.index'), 'cvs'],
        ['#', 'New'],
    ]
])

@push('head')
    <style>
        .addphoto .img {
            height: 233px;
        }
        .addphoto .left-block {
            width: 154%;
        }
    </style>
@endpush

@section('content')
    <section class="content">
        <form action="{{ route('cvs.store') }}" method="post" enctype="multipart/form-data">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title float-left">Add New</h3>
                    <input type="text" class="float-right" name="reference_number" placeholder="REF. NO" required>
                </div>

                <div class="card-body">
                    @csrf
                    <div class="row">
                        <div class="col-md-12 pb-3"><h5>Basic Information</h5></div>

                        <div class="col-md-9 row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="name">Name of Candidate</label>
                                    <input type="text" class="form-control" name="name" placeholder="Name" required="" />
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="gender">Gender</label>
                                    <select class="custom-select" name="gender" required="">
                                        <option value="1">Male</option>
                                        <option value="2">Female</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="passport">RELIGION </label>
                                    <input type="text" class="form-control" name="religion" placeholder="RELIGION" required="" />
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="profession_id">Job Title</label>
                                    <select class="custom-select" name="profession_id" required>
                                        @foreach($professions as $profession)
                                            <option value="{{ $profession->id }}">{{ $profession->name_en }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="birthplace">PLACE OF BIRTH </label>
                                    <input type="text" class="form-control" name="birthplace" placeholder="PLACE OF BIRTH " required="">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="birth_date">DATE OF BIRTH </label>
                                    <input type="date" class="form-control" name="birth_date" placeholder="DATE OF BIRTH " required="" />
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="marital_status">Marital Status </label>
                                    <input type="text" class="form-control" name="marital_status" placeholder=" Marital Status" required="" />
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="children"> NO. of CHILDREN </label>
                                    <input type="number" class="form-control" name="children" placeholder=" NO.CHILDREN " required="" />
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="phone"> CONTACT NUMBER </label>
                                    <input type="number" class="form-control" name="phone" placeholder=" CONTACT No. " required="" />
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="qualifications">Qualifications </label>
                                    <input type="text" class="form-control" name="qualifications" placeholder=" Qualifications " required="" />
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="english_speaking_level"> ENGLISH SPEAKING </label>
                                    <input type="text" class="form-control" name="english_speaking_level" placeholder=" ENGLISH SPEAKING  " required="" />
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="experince"> Exp. yrs in country </label>
                                    <input type="text" class="form-control" name="experince" placeholder=" Exp. yrs in country  " required="" />
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2 addphoto">
                            <div class="left-block">
                                <div class="img"></div>
                                <div class="input-file">
                                    <input id="add-photo" type="file" name="photo"/>
                                    <label for="add-photo"> Avatar Photo </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 pb-3"><h5>Additional Info</h5></div>

                    <div class="row">
                        <div class="col-md-1">
                            <div class="form-group">
                                <label for="weight"> WEIGHT</label>
                                <input type="number" class="form-control" name="weight" placeholder="WEIGHT " required="" />
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label for="height"> HEIGHT</label>
                                <input type="number" class="form-control" name="height" placeholder="HEIGHT " required="" />
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label for="passport"> SEWING</label>
                                <select class="custom-select" name="sewing" required="">
                                    <option selected="" disabled="" value="">____ </option>
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label for="passport"> DECOR </label>
                                <select class="custom-select" name="decor" required="">
                                    <option selected="" disabled="" value="">____ </option>
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label for="passport"> CLEANING</label>
                                <select class="custom-select" name="cleaning" required="">
                                    <option selected="" disabled="" value="">____ </option>
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label for="passport"> WASHING</label>
                                <select class="custom-select" name="washing" required="">
                                    <option selected="" disabled="" value="">____ </option>
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label for="passport"> COOKING</label>
                                <select class="custom-select" name="cooking" required="">
                                    <option selected="" disabled="" value="">____ </option>
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="passport"> BABYSITTING </label>
                                <select class="custom-select" name="babysitting" required="">
                                    <option selected="" disabled="" value="">____ </option>
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 py-3"><h5>Passport Data</h5></div>

                    <div class="col-md-12 row">
                        <div class="col-md-9 row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="passport"> PASSPORT NUMBER </label>
                                    <input type="text" class="form-control" name="passport" placeholder="PASSPORT NUMBER" required="" />
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="passport_place_of_issue"> PLACE OF ISSUE </label>
                                    <input type="text" class="form-control"  name="passport_place_of_issue" placeholder="PLACE OF ISSUE" required=""/>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="passport_issuing_date"> DATE OF ISSUE </label>
                                    <input type="date" class="form-control" name="passport_issuing_date" placeholder=" DATE OF ISSUE" required=""/>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="passport_expiration_date"> DATE OF EXPIRY </label>
                                    <input type="date" class="form-control" name="passport_expiration_date" placeholder=" DATE OF EXPIRY" required=""/>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 addphoto">
                            <div class="left-block">
                                <div class="img"></div>
                                <div class="input-file">
                                    <input id="" type="file" name="passport_photo" />
                                    <label for="add-photo"> Passport Photo </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 py-3"><h5>Contract details</h5></div>

                    <div class="col-md-12 row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="amount">HWs Fees</label>
                                <input type="number" class="form-control" name="amount" placeholder="HWs Fees" required="" />
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="contract_period">CONTRACT </label>
                                <input type="text" class="form-control" name="contract_period" placeholder=" CONTRACT" required="" />
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="contract_salary"> SALARY</label>
                                <input type="number" class="form-control" name="contract_salary" placeholder="SALARY " required="" />
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="d-block" for="bio">PROFILE SUMMARY And REMARKS</label>
                                <textarea rows="3" cols="100" name="bio"></textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="d-block" for="procedure">Procedure Status</label>
                                <textarea rows="3" cols="100" name="procedure"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        @component('components.widget')
                            @slot('noTitle', true)
                            @slot('title')
                                <i class="fas fa-paperclip"></i>
                                <span>Attachments</span>
                            @endslot
                            @slot('body')
                                @component('accounting::components.attachments-uploader')@endcomponent
                            @endslot
                        @endcomponent
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