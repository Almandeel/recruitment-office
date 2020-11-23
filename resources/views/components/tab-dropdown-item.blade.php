<a href="{{ $href??'#' }}" class="dropdown-item {{ isset($classes) ? $classes : '' }}"
@isset($attributes)
    {!! $attributes !!}
@endisset
>
    {!! $content !!}
</a>
@isset($extra)
    {!! $extra !!}
@endisset