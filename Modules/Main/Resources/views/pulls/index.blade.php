@extends('layouts.master', [
    'title' => 'المسحوبات',
    'datatable' => true, 
    'modals' => [],
    'crumbs' => [
        [route('offices.index'), 'المكاتب الخارجية'],
        ['#', 'المسحوبات']
    ]
])
@section('content')
    @component('components.widget')
        @slot('title', '')
        @slot('tools')
        @endslot
        @slot('extra')
            <form action="" method="GET" class="form-inline guide-advanced-search">
                @csrf
                <div class="form-group mr-2">
                    <i class="fa fa-filter"></i>
                    <span>@lang('global.filter')</span>
                </div>
                <div class="form-group mr-2">
                    <label for="office_id">@lang('global.office')</label>
                    <select name="office_id" id="office_id" class="form-control">
                        <option value="all" {{ $office_id == 'all' ? 'selected' : ''}}>@lang('global.all')</option>
                        @foreach ($offices as $office)
                            <option value="{{ $office->id }}" {{ $office_id == $office->id ? 'selected' : ''}}>{{ $office->name }}</option>
                        @endforeach
                    </select>
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
                        @foreach (__('pulls.statuses') as $key => $value)
                        <option value="{{ $key }}" {{ $key == $status ? 'selected' : '' }}>
                            {{ $value }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group mr-2">
                    <label for="from-date">@lang('accounting::global.from')</label>
                    <input type="date" name="from_date" id="from-date" value="{{ $from_date }}" class="form-control">
                </div>
                <div class="form-group mr-2">
                    <label for="to-date">@lang('accounting::global.to')</label>
                    <input type="date" name="to_date" id="to-date" value="{{ $to_date }}" class="form-control">
                </div>
                <button type="submit" class="btn btn-primary">
                    <span>@lang('accounting::global.search')</span>
                    <i class="fa fa-search"></i>
                </button>
            </form>
        @endslot
        @slot('body')
            <table class="table table-bordered table-striped text-center">
                <thead>
                    <tr>
                        <th>@lang('global.id')</th>
                        <th>@lang('global.office')</th>
                        <th>@lang('global.worker')</th>
                        <th>@lang('global.gender')</th>
                        <th>@lang('global.status')</th>
                        <th>@lang('global.options')</th>
                    </tr>
                <tbody>
                    @foreach ($pulls as $index=>$pull)
                    <tr>
                        <td>{{ $pull->id }}</td>
                        <td>{{ $pull->office->name }}</td>
                        <td>{{ $pull->cv->name }}</td>
                        <td>{{ $pull->cv->displayGender() }}</td>
                        <td>{!! $pull->displayStatus() !!}</td>
                        <td>
                            <div class="btn-group">
                                @permission('pulls-read')
                                    <a href="{{ route('offices.pulls.show', $pull) }}" class="btn btn-info">
                                        <i class="fa fa-eye"></i>
                                        <span>@lang('global.show')</span>
                                    </a>
                                @endpermission
                                @if ($pull->isWaiting())
                                @permission('pulls-update')
                                    <button type="button" class="btn btn-danger" 
                                        data-toggle="confirm" data-form="#cancel-form-{{ $pull->id }}"
                                        data-title="إلغاء السحب" data-text="سوف يتم إلغاء طلب سحب السيرة استمرار؟"
                                    >
                                        <i class="fa fa-times"></i>
                                        <span>@lang('global.cancel')</span>
                                    </button>
                                @endpermission
                                @endif
                            </div>
                            @if ($pull->isWaiting())
                                @permission('pulls-update')
                                    <form id="cancel-form-{{ $pull->id }}" action="{{ route('offices.pulls.update', $pull) }}" method="post">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="operation" value="cancel">
                                    </form>
                                @endpermission
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endslot
    @endcomponent
@endsection
