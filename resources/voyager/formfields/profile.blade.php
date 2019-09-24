@php
    $items = \App\Profiles::all();
@endphp
<?php $selected_value = (isset($dataTypeContent->{$row->field}) && !is_null(old($row->field, $dataTypeContent->{$row->field}))) ? old($row->field, $dataTypeContent->{$row->field}) : old($row->field); ?>
<select data-target="{{ str_replace('_profile','',$row->field)}}" @if(isset($pageBlock) and isset($pageBlock->id)) data-block-id="{{$pageBlock->id}}" @else data-block-id="{{$block->id}}" @endif class="form-control profile-selector" name="{{ $row->field }}">
    <option value="Nenhum">Nenhum</option>
    <?php $default = (isset($options->default) && !isset($dataTypeContent->{$row->field})) ? $options->default : null; ?>
    @foreach($items as $key => $val)
        <option
                data-font="{{$val->text_font}}"
                data-size="{{$val->text_size}}"
                data-weight="{{$val->text_fontweight}}"
                data-height="{{$val->text_lineheight}}"
                data-space="{{$val->text_letterspacing}}"
                data-color="{{$val->text_color}}" value="{{ $val->id }}" @if($default == $key && $selected_value === NULL){{ 'selected="selected"' }}@endif @if($selected_value == $val->name ){{ 'selected="selected"' }}@endif>{{ $val->name }}</option>
    @endforeach
</select>