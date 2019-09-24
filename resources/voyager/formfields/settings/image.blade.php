@if(isset( $setting->value ) && !empty( $setting->value ) && Storage::disk(config('voyager.storage.disk'))->exists($setting->value))
    <div class="img_settings_container">
        <a href="{{ route('voyager.settings.delete_value', $setting->id) }}" class="voyager-x delete_value"></a>
        <img src="{{ Voyager::image($setting->value) }}" style="width:200px; height:auto; padding:2px; border:1px solid #ddd; margin-bottom:10px;">
    </div>
    <div class="clearfix"></div>
@elseif($setting->type == "file" && isset( $setting->value ))
    <div class="fileType">{{ $setting->value }}</div>
@endif
<input type="file" name="{{ $setting->key }}">