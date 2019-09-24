@php
    $fonts = \Viaativa\Viaroot\Models\Fonts::all();
@endphp
<?php $selected_value = (isset($dataTypeContent->{$row->field}) && !is_null(old($row->field, $dataTypeContent->{$row->field}))) ? old($row->field, $dataTypeContent->{$row->field}) : old($row->field); ?>
<select class="form-control select2" name="{{ $row->field }}">
    <?php $default = (isset($options->default) && !isset($dataTypeContent->{$row->field})) ? $options->default : null; ?>
        @foreach($fonts as $key => $option)

            <option value="{{ $option->font_family }}" @if($default == $key && $selected_value === NULL){{ 'selected="selected"' }}@endif  @if($selected_value == $option->font_family){{ 'selected="selected"' }}@endif>{{ $option->font_name }}</option>
        @endforeach
</select>
