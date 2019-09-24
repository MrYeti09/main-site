{{-- If this is a relationship and the method does not exist, show a warning message --}}
@if(isset($options->relationship) && !method_exists( $dataType->model_name, camel_case($row->field) ) )
    <p class="label label-warning"><i class="voyager-warning"></i> {{ __('voyager::form.field_select_dd_relationship', ['method' => camel_case($row->field).'()', 'class' => $dataType->model_name]) }}</p>
@endif
<select class="form-control select2" name="{{ $row->field }}[]" multiple>

    @if(isset($options->options) and isset($options->options->model) and isset($options->options->column))
        @php
        $items = app($options->options->model)->all()->groupBy($options->options->column);
        $values = explode(',',$dataTypeContent->{$row->field});
        @endphp
        @foreach($items as $key => $item)
            <option @if(in_array($key,$values)) selected="selected" @endif value="{{$key}}">{{$key}}</option>

        @endforeach
    @endif
</select>
