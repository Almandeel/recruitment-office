<div class="card card-{{ isset($type) ? $type : 'primary' }} {{ isset($collapsed) ? 'collapsed-card' : '' }} {{ isset($not_outlined) ? '' : 'card-outline' }} {{ isset($sticky) ? ' p-sticky t-15  ' : '' }} {{ isset($classes) ? $classes : '' }}">
    @isset($title)
        <div class="card-header">
            <h3 class="card-title">{{$title}}</h3>

            <div class="card-tools">
                @isset($tools)
                    {!! $tools !!}
                @endisset
                @isset($widgets)
                @if(in_array('maximize', $widgets)) <button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i></button> @endif
                @if(in_array('collapse', $widgets)) <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-{{ isset($collapsed) ? 'plus' : 'minus' }}"></i></button> @endif
                @if(in_array('remove', $widgets)) <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button> @endif
                @endisset
            </div>
            <!-- /.card-tools -->
        </div>
        <!-- /.card-header -->
    @endisset
    @isset($extra)
    <div class="card-extra clearfix">
        {!! $extra !!}
    </div>
    <!-- /.card-extra -->
    @endisset
    @isset($body)
    <div class="card-body {{ isset($noPadding) ? 'p-0' : '' }}">
        {!! $body !!}
    </div>
    @endisset
    <!-- /.card-body -->
    @isset($footer)
    <div class="card-footer">
        {!! $footer !!}
    </div>
    <!-- /.card-footer -->        
    @endisset
</div>