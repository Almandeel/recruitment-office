<div class="card card-primary card-outline card-outline-tabs">
    <div class="card-header p-0 border-bottom-0 {{ isset($sticky) ? 'sticky' : '' }}">
        @isset($header)
            <div style="padding: 15px;">
                {!! $header !!}
            </div>
        @endisset
        @isset($tools)
            <div class="card-tools">
                {!! $tools !!}
            </div>
        @endisset
        <ul class="nav nav-tabs" id="tabs-tab" role="tablist">
            {!! $items !!}
        </ul>
    </div>
    <div class="card-body">
        <div class="tab-content" id="tabs-tabContent">
            {!! $contents !!}
        </div>
    </div>
</div>
@push('styles')
    .card.card-outline-tabs .card-header.sticky{
        position: sticky;
        top: 0;
        z-index: 9;
        background-color: inherit;
    }
@endpush