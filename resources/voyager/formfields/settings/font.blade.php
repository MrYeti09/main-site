@php
    $fonts = \Viaativa\Viaroot\Models\Fonts::all();
@endphp
<?php $options = json_decode($setting->details); ?>
<?php $selected_value = (isset($setting->value) && !empty($setting->value)) ? $setting->value : NULL; ?>
<select class="form-control select2" name="{{ $setting->key }}">
    <?php $default = (isset($options->default)) ? $options->default : NULL; ?>
        @foreach($fonts as $key => $option)
            <option value="{{ $option->font_family }}" @if($default == $key && $selected_value === NULL){{ 'selected="selected"' }}@endif  @if($selected_value == $option->font_family){{ 'selected="selected"' }}@endif>{{ $option->font_name }}</option>
        @endforeach
</select>