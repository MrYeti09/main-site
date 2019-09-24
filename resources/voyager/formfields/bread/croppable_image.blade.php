<div style="max-width:300px;display:flex;flex-direction: column;">
@if(isset($dataTypeContent->{$row->field}))
    @php
        if(property_exists($dataTypeContent,'id') == null)
        {
            $dataTypeContent->id = $row->field;
        }
    @endphp
    @if(strlen($dataTypeContent->{$row->field}) > 0)

        <div data-field-name="{{ $row->field }}" >
            <img class="img-upload-croppie" src="@if( !filter_var($dataTypeContent->{$row->field}, FILTER_VALIDATE_URL)){{ Voyager::image( $dataTypeContent->{$row->field} ) }}@else{{ $dataTypeContent->{$row->field} }}@endif"
                 data-file-name="{{ $dataTypeContent->{$row->field} }}" data-id="{{ $dataTypeContent->id }}"
                 style="max-width:200px; height:auto; clear:both; display:block; padding:2px; border:1px solid #ddd; margin-bottom:10px;"
                @if(isset($options->crop))
                 @if(isset($options->crop->width))
                 data-width="{{$options->crop->width}}"
                         @else
                 data-width="200"
                 @endif
                 @if(isset($options->crop->height))
                 data-height="{{$options->crop->height}}"
                 @else
                 data-height="200"
                 @endif
                 @if(isset($options->crop->type))
                         data-type="{{$options->crop->type}}"
                 @else
                 data-type="square"
                 @endif
                         @else
                 data-width="200"
                 data-height="200"
                 data-type="square"
                         @endif
                 data-block-id="{{$row->id}}" data-field-name="{{ $row->field }}"
            >
            @if(isset($block))
                <a href="{{route('voyager.page-blocks.remove-image',['id' => $dataTypeContent->id,"blockid" => $row->id])}}">Remover imagem</a>
            @endif
        </div>

        @else
            <img class="img-upload-croppie" data-block-id="{{$row->id}}" data-field-name="{{ $row->field }}">
    @endif

@else


        <img class="img-upload-croppie" data-block-id="{{$row->id}}" data-field-name="{{ $row->field }}">

@endif
<input class="hiddenContent" type="hidden" data-block-id="{{$row->id}}" data-field-name="{{ $row->field }}" @if($row->required == 1 && !isset($dataTypeContent->{$row->field})) required @endif name="{{ $row->field }}">
    <div class="btn btn-primary upload-result" style="" data-block-id="{{$row->id}}" data-field-name="{{ $row->field }}">CORTAR</div>
<input class="img-uploader" data-block-id="{{$row->id}}" data-field-name="{{ $row->field }}" type="file"  accept="image/*">
</div>
