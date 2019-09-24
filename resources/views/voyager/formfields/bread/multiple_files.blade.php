<br>
@if(isset($dataTypeContent->{$row->field}))
    <?php $images = json_decode($dataTypeContent->{$row->field}); ?>
    <input type="hidden" name="files" value="{{ $dataTypeContent->{$row->field} }}">
    @if($images != null and (is_object($images) or is_array($images)))
        <div style="display: flex;
flex-direction: column;
justify-content: flex-start;
align-items: flex-start;">
            @foreach($images as $image)
                <div style="padding:2px 10px;background:rgba(255,255,255,0.06);border-radius:2px;margin-bottom:2px;border: 1px solid #91baff;">
                    {{explode('/',$image)[sizeof(explode('/',$image))-1]}}
                </div>
            @endforeach
        </div>
    @endif
@endif
<div class="clearfix"></div>
<input @if($row->required == 1 && !isset($dataTypeContent->{$row->field})) required @endif type="file"
       name="{{ $row->field }}[]" multiple="multiple" accept="*">
