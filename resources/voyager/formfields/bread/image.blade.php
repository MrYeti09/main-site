<div style="display:flex;flex-direction: column;">
@if(isset($dataTypeContent->{$row->field}))
    @php
        if(property_exists($dataTypeContent,'id') == null)
        {
            $dataTypeContent->id = $row->field;
        }
    @endphp
    @if(strlen($dataTypeContent->{$row->field}) > 0)

        <div data-field-name="{{ $row->field }}" >
            <img src="@if( !filter_var($dataTypeContent->{$row->field}, FILTER_VALIDATE_URL)){{ Voyager::image( $dataTypeContent->{$row->field} ) }}@else{{ $dataTypeContent->{$row->field} }}@endif"
                 data-file-name="{{ $dataTypeContent->{$row->field} }}" data-id="{{ $dataTypeContent->id }}"
                 style="max-width:200px; height:auto; clear:both; display:block; padding:2px; border:1px solid #ddd; margin-bottom:10px;">
        </div>
        @if(isset($block))
            <a href="{{route('voyager.page-blocks.remove-image',['id' => $dataTypeContent->id,"blockid" => $block->id])}}">Remover imagem</a>
        @endif

    @endif
@else



@endif
<input @if($row->required == 1 && !isset($dataTypeContent->{$row->field})) required @endif class="" type="file" name="{{ $row->field }}" accept="image/*">
</div>