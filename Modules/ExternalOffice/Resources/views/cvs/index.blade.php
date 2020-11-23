@extends('externaloffice::layouts.master', [
    'title' => 'Cvs list',
    'datatable' => true,
    'external_office_modals' => ['pull_cv'],
    'crumbs' => [
        [route('cvs.index'), 'cvs'],
    ]
])

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Cvs list</h3>
                        @permission('cv-read')
                        <a class=" btn btn-primary btn-sm float-left" href="{{ route('cvs.create') }}"><i class="fa fa-plus"></i> New</a>
                        @endpermission
                    </div>
                    <!-- /.card-header -->
                    <div class="card-extra clearfix">
                        <form action="" method="GET" class="form-inline guide-advanced-search">
                            @csrf
                            <div class="form-group mr-2">
                                <i class="fa fa-filter"></i>
                                <span>@lang('global.filter')</span>
                            </div>
                            <div class="form-group mr-2">
                                <label for="gender">@lang('global.gender')</label>
                                <select class="form-control type" name="gender" id="gender">
                                    <option value="all" {{ ($gender == 'all') ? 'selected' : '' }}>@lang('global.all')</option>
                                    <option value="male" {{ ($gender == 'male') ? 'selected' : '' }}>@lang('global.gender_male')</option>
                                    <option value="female" {{ ($gender == 'female') ? 'selected' : '' }}>@lang('global.gender_female')</option>
                                </select>
                            </div>
                            <div class="form-group mr-2">
                                <label for="status">@lang('global.status')</label>
                                <select class="form-control type" name="status" id="status">
                                    <option value="all" {{ ($status == 'all') ? 'selected' : '' }}>@lang('global.all')
                                    </option>
                                    @foreach (__('cvs.statuses') as $sts)
                                    <option value="{{ $sts }}" {{ $sts == $status ? 'selected' : '' }}>
                                        @lang('cvs.statuses.' . $sts)
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group mr-2">
                                <label for="from-date">@lang('global.from')</label>
                                <input type="date" name="from_date" id="from-date" value="{{ $from_date }}"
                                    class="form-control">
                            </div>
                            <div class="form-group mr-2">
                                <label for="to-date">@lang('global.to')</label>
                                <input type="date" name="to_date" id="to-date" value="{{ $to_date }}"
                                    class="form-control">
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <span>@lang('global.search')</span>
                                <i class="fa fa-search"></i>
                            </button>
                        </form>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped datatable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Passport Number</th>
                                    <th>Gender</th>
                                    <th>Status</th>
                                    <th>Current Procedure</th>
                                    <th>Created At</th>
                                    <th>Options</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cvs as $cv)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $cv->name }}</td>
                                        <td>{{ $cv->passport }}</td>
                                        <td>{{ $cv->displayGender() }}</td>
                                        <td>
                                            @if (!$cv->pull)
                                                @if($cv->status == Modules\ExternalOffice\Models\Cv::STATUS_CONTRACTED)
                                                    <span class="text-capitalize text-success">
                                                        contracted
                                                    </span>
                                                @endif
                                                @if($cv->status == Modules\ExternalOffice\Models\Cv::STATUS_ACCEPTED)
                                                    <span class="text-capitalize text-success">
                                                        accepted
                                                    </span>
                                                @endif
                                                @if($cv->status == Modules\ExternalOffice\Models\Cv::STATUS_WAITING)
                                                    <span class="text-capitalize text-warning">
                                                        waiting
                                                    </span>
                                                @endif
                                            @elseif ($cv->status == Modules\ExternalOffice\Models\Cv::STATUS_RETURNED)
                                                <p class="text-capitalize text-info">returned</p>
                                            @elseif ($cv->status == Modules\ExternalOffice\Models\Cv::STATUS_PULLED)
                                                <p class="text-capitalize text-danger">pulled</p>
                                            @endif
                                        </td>
                                        <td>{{ \Str::limit($cv->procedure, 30) }}</td>
                                        <td>{{ $cv->created_at->format('Y/m/d') }}</td>
                                        <td>
                                            @permission('cv-read')
                                            <a class="btn btn-info btn-sm" href="{{ route('cvs.show', $cv->id) }}"><i class="fa fa-eye"></i> Show</a>
                                            @endpermission
                                            @permission('cv-update')
                                            <a class="btn btn-warning btn-sm cvs update" href="{{ route('cvs.edit', $cv->id) }}"><i class="fa fa-edit"></i> Edit</a>
                                            @endpermission
                                            @if($cv->isAccepted())
                                            @permission('pulls-create')
                                            <a class="btn btn-danger btn-sm pull text-white"
                                                data-action="{{ route('cvs.pull', $cv->id) }}"
                                                data-toggle="modal"
                                                data-target="pullModal"
                                            ><i class="fa fa-reply"></i> Pull</a>
                                            @endpermission
                                            @endif
                                            @if ($cv->isDeleteable())
                                            @permission('cv-delete')
                                            <form class="d-inline-block" action="{{ route('cvs.destroy', $cv) }}" method="POST">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-secondary btn-sm delete" type="submit"><i class="fa fa-trash"></i> Delete</button>
                                            </form>
                                            @endpermission
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
