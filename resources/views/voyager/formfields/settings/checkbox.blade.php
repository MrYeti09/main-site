<?php $options = json_decode($setting->details); ?>
                                        <?php $checked = (isset($setting->value) && $setting->value == "on") ? true : false; ?>
@if (isset($options->on) && isset($options->off))
    <input type="checkbox" name="{{ $setting->key }}" class="toggleswitch" @if($checked) checked @endif data-on="{{ $options->on }}" data-off="{{ $options->off }}">
@else
    <input type="checkbox" name="{{ $setting->key }}" @if($checked) checked @endif class="toggleswitch">
@endif