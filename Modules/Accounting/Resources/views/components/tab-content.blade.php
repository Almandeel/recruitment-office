@php
    $activated = (request()->active_tab == $id || request()->session()->get('active_tab') == $id);
    $activated = !$activated && isset($active) ? true : $activated;
@endphp
<div class="tab-pane fade {{ $activated ? 'active show' : '' }}" id="tabs-{{ $id }}" role="tabpanel" aria-labelledby="tabs-{{ $id }}-tab">
    {!! $content !!}
</div>