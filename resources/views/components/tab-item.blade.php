@php
    $activated = (request()->has('active_tab') || request()->session()->has('active_tab')) ? (request()->active_tab == $id || request()->session()->get('active_tab') == $id) : isset($active);
@endphp
<li class="nav-item">
    <a class="nav-link {{ $activated ? 'active' : '' }}" id="tabs-{{ $id }}-tab" data-toggle="pill" href="#tabs-{{ $id }}" role="tab"
        aria-controls="tabs-{{ $id }}" aria-selected="true">{{ $title }}</a>
</li>