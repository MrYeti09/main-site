<div style="display:flex;flex-direction: column">

    <a data-open="mediapickerModal" class="btn-media-picker" onclick="openmodal_media('{{$row->field}}')" data-target-field="{{$row->field}}" >

        <div style="position: relative;padding:1px;border: 2px dashed rgba(0,0,0,0.34);
                max-width:256px;
                min-width:86px;
        @if(!isset($dataTypeContent->{$row->field}))
                min-height:86px;
        @elseif(strlen($dataTypeContent->{$row->field}) == 0)
                min-height:86px;
        @endif
                display:flex;align-items: center">
            <input type="hidden" data-target-field="{{$row->field}}" @if(isset($dataTypeContent->{$row->field})) value="{{$dataTypeContent->{$row->field} }}" @else value="" @endif id="{{$row->field}}" name="{{$row->field}}">
            <img class="img-media" @if(isset($dataTypeContent->{$row->field}) and strlen($dataTypeContent->{$row->field})) src="{{Voyager::image($dataTypeContent->{$row->field})}}" @endif
            style="width: 100%;">
            <div class="img-container" style="position: absolute;top: 0;left:0;right:0;bottom:0;display: flex;align-items: center;justify-content: center;color:black;
">

                <div class="select-btn-media-picker" style="">Selecione</div>
            </div>
        </div>
    </a>

    @if(isset($block) and isset($dataTypeContent->{$row->field}) and strlen($dataTypeContent->{$row->field}))
        <a class="btn btn-danger" href="{{route('voyager.page-blocks.remove-image',['id' => $dataTypeContent->id,"blockid" => $block->id])}}">&#10005; Remover imagem</a>
    @endif

</div>
