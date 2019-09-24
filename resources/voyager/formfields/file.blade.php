<div style="display:flex;flex-direction: column">

    <a data-open="mediapickerModal" class="btn-media-picker btn btn-primary" value="" onclick="openmodal_media('{{$row->field}}')" data-target-field="{{$row->field}}" >
        Selecionar um arquivo

            <input type="hidden" data-target-field="{{$row->field}}" @if(isset($dataTypeContent->{$row->field})) value="{{$dataTypeContent->{$row->field} }}" @else value="" @endif id="{{$row->field}}" name="{{$row->field}}">


    </a>

    @if(isset($block) and isset($dataTypeContent->{$row->field}) and strlen($dataTypeContent->{$row->field}))
        <a class="btn btn-success" href="{{ Voyager::image($dataTypeContent->{$row->field}) }}" target="_blank">Visualizar arquivo</a>
        <a class="btn btn-danger" href="{{route('voyager.page-blocks.remove-image',['id' => $dataTypeContent->id,"blockid" => $block->id])}}">Remover arquivo</a>
    @endif

</div>
