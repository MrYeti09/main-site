{{--@dd($setting)--}}
<input type="text" class="form-control" name="{{ $setting->key }}"
        placeholder="@if($setting->display_name) {{$setting->display_name }} @endif"
       value="{{ $setting->value }}">
