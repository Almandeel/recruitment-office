@extends('externaloffice::layouts.master', [
    'title' => 'Show cv: ' . $cv->name,
    'confirm_status' => true, 
    'modals' => ['attachment'],
    'crumbs' => [
        [route('cvs.index'), 'cvs'],
        ['#', $cv->name ],
    ]
])

@section('content')
<section class="content">
        @component('components.tabs')
            @slot('items')
                @component('components.tab-item')
                    @slot('active', true)
                    @slot('id', 'details')
                    @slot('title', 'Cv Data')
                @endcomponent
                @component('components.tab-item')
                    @slot('id', 'attachments')
                    @slot('title', 'Attachments')
                @endcomponent
                @component('components.tab-item')
                    @if (session('active_tab') == 'pulls')
                        @slot('active', true)
                    @endif
                    @slot('id', 'pulls')
                    @slot('title', 'Pull Requests')
                @endcomponent
            @endslot
            @slot('contents')
                @component('components.tab-content')
                        @slot('active', true)
                    @slot('id', 'details')
                    @slot('content')
                        <div class="card">
                            <div class="card-header">
                                <div class="col-md-12 row">
                                    <div class="col-md-6">
                                        <h5 style="font-weight: bold; color: #032cc3;">
                                            REF. NO :{{ $cv->reference_number }}
                                        </h5>
                                    </div>
                                    <div class="col-md-6">
                                        <h3 class="card-title" style="float: left;">Create Date : {{ $cv->created_at->format('Y-m-d') }}</h3>
                                        <h6 class="gg" style="float: left; padding-left: 34px;">
                                            Status :
                                            <span class="badge badge-info">
                                                @if (! $cv->pull)
                                                <p style="margin-bottom: 2px;" class="{{ $cv->accepted ? '' : 'text-warnying' }}">
                                                    @if (!$cv->pull) @if($cv->status == Modules\ExternalOffice\Models\Cv::STATUS_CONTRACTED)
                                                    <span class=" text-capitalize">
                                                        contracted
                                                    </span>
                                                    @endif @if($cv->status == Modules\ExternalOffice\Models\Cv::STATUS_ACCEPTED)
                                                    <span class=" text-capitalize">
                                                        accepted
                                                    </span>
                                                    @endif @if($cv->status == Modules\ExternalOffice\Models\Cv::STATUS_WAITING)
                                                    <span class="text-warning" text-capitalize>
                                                        waiting
                                                    </span>
                                                    @endif
                                                </p>
                                                @elseif ($cv->status == Modules\ExternalOffice\Models\Cv::STATUS_RETURNED)
                                                    <p class="text-capitalize text-info">returned</p>
                                                @elseif ($cv->status == Modules\ExternalOffice\Models\Cv::STATUS_PULLED)
                                                    <p class="text-capitalize text-danger">pulled</p>
                                                @endif @elseif ($cv->pull && ! $cv->pull->confirmed)
                                                    <p class="text-capitalize">Request for return</p>
                                                @elseif ($cv->pull && $cv->pull->confirmed)
                                                    <p class="text-muted text-capitalize">Returned</p>
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
                                            <h5 style="font-weight: bold;">Basic Information</h5>
                                        </div>

                                        <div class="col-md-9 row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="name">Name of Candidate</label>
                                                    <h3>
                                                        {{ $cv->name }}
                                                    </h3>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <h5>Job Title : {{ $cv->profession->name }}</h5>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <h5>Religion : {{ $cv->religion }}</h5>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <h5>Gender : {{ $cv->gender == 1 ? 'Male' : 'Female' }}</h5>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <h5>PLACE OF BIRTH : {{ $cv->birthplace ?? '' }}</h5>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <h5>DATE OF BIRTH : {{ $cv->birth_date ?? '' }}</h5>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <h5>
                                                        Marital Status : {{ $cv->marital_status }}
                                                    </h5>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <h5>NO. of CHILDREN : {{ $cv->children }}</h5>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <h5>CONTACT NUMBER : {{ $cv->phone }}</h5>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <h5>Qualifications : {{ $cv->qualification }}</h5>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <h5>ENGLISH SPEAKING : {{ $cv->english_speaking_level }}</h5>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <h5>Exp. yrs in country : {{ $cv->experince }}</h5>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3 pic">
                                            <div class="left-block">
                                                <img src="{{ asset('cvs_data/' . $cv->photo) }}" width="100%" height="200" />
                                                <h6 style="text-align: center; font-weight: bold; padding-top: 6px;">Avatar Photo</h6>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <br />
                                            <h5 style="font-weight: bold;">Additional Info</h5>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <h5>WEIGHT : {{ $cv->weight }}</h5>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <h5>HEIGHT : {{ $cv->height }}</h5>
                                            </div>
                                        </div>
                                        <div class="col-md-8"></div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <h5>SEWING : {{ $cv->sewing ? 'Yes' : 'No' }}</h5>
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <h5>DECOR : {{ $cv->decor ? 'Yes' : 'No' }}</h5>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <h5>CLEANING : {{ $cv->cleaning ? 'Yes' : 'No' }}</h5>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <h5>WASHING : {{ $cv->washing ? 'Yes' : 'No' }}</h5>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <h5>COOKING : {{ $cv->cooking ? 'Yes' : 'No' }}</h5>
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <h5>BABYSITTING : {{ $cv->babysitting ? 'Yes' : 'No' }}</h5>
                                            </div>
                                        </div>

                                        <br />
                                        <br />

                                        <div class="col-md-12">
                                            <br />
                                            <br />
                                            <h5 style="font-weight: bold;">Passport Data</h5>
                                        </div>

                                        <div class="col-md-12 row">
                                            <div class="col-md-9 row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <h5>PASSPORT NUMBER :{{ $cv->passport }}</h5>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <h5>PLACE OF ISSUE : {{ $cv->passport_place_of_issue }}</h5>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <h5 style="margin-top: -59px;">DATE OF ISSUE : {{ $cv->passport_issuing_date }}</h5>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <h5 style="margin-top: -59px;">DATE OF EXPIRY : {{ $cv->passport_expiration_date }}</h5>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-3 pic">
                                                <div class="left-block">
                                                    <img style="margin-top: -59px;" src="{{ asset('cvs_data/' . $cv->passport_photo) }}" width="100%" height="200" />
                                                    <h6 style="text-align: center; font-weight: bold; padding-top: 6px;">Passport Photo</h6>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <h5 style="font-weight: bold;">
                                                Contract details
                                            </h5>
                                        </div>

                                        <div class="col-md-12 row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <h5>HWS Fees : {{ number_format($cv->amount, 2) }}</h5>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <h5>CONTRACT : {{ $cv->contract_period }}</h5>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <h5>SALARY : {{ $cv->contract_salary }}</h5>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-9">
                                            <div class="form-group">
                                                <label for="procedure">PROFILE SUMMARY And REMARKS </label>

                                                <br />
                                                <span>
                                                    {{ $cv->bio ?? '' }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <h5 style="font-weight: bold;">Procedure Status :</h5>

                                                <h5>
                                                    {{ $cv->procedure ?? '' }}
                                                </h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endslot
                @endcomponent
                @component('components.tab-content')
                    @slot('id', 'attachments')
                    @slot('content')
                        @component('components.attachments-viewer')
                            @slot('attachable', $cv)
                            @slot('canAdd', true)
                            @slot('view', 'timeline')
                        @endcomponent
                    @endslot
                @endcomponent
                @component('components.tab-content')
                    @if (session('active_tab') == 'pulls')
                        @slot('active', true)
                    @endif
                    @slot('id', 'pulls')
                    @slot('content')
                        @if ($cv->pull)
                            <div class="card">
                                <div class="card-header">
                                    <h4>Request for Pull</h4>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered table-striped text-center">
                                        <tbody>
                                            <tr>
                                                <th>Cause</th>
                                                <td>{{ $cv->pull->cause }}</td>
                                            </tr>
                                            <tr>
                                                <th>Damages</th>
                                                <td>{{ $cv->pull->damages }}</td>
                                            </tr>
                                            <tr>
                                                <th>Note</th>
                                                <td>{{ $cv->pull->notes }}</td>
                                            </tr>
                                            <tr>
                                                <th>Request Status</th>
                                                <td>{{ $cv->pull->confirmed ? 'Confirmed' : 'Waiting for confirmatoin' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Employee </th>
                                                <td>{{ $cv->pull->user->name }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @else
                            There's no pull requests
                        @endif
                    @endslot
                @endcomponent
            @endslot
        @endcomponent
</section>
@endsection
