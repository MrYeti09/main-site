@if(isset($dataTypeContent->{$row->field}))
    @php
        if(property_exists($dataTypeContent,'id') == null)
        {
            $dataTypeContent->id = $row->field;
        }
    @endphp
    <div data-field-name="{{ $row->field }}" style="display:inline-flex;">

    @if(strlen($dataTypeContent->{$row->field}))
        <img src="@if( !filter_var($dataTypeContent->{$row->field}, FILTER_VALIDATE_URL)){{ Voyager::image( $dataTypeContent->{$row->field} ) }}@else{{ $dataTypeContent->{$row->field} }}@endif"
             data-file-name="{{ $dataTypeContent->{$row->field} }}" data-id="{{ $dataTypeContent->id }}"
             style="max-width:48px;height:48px;max-height:48px;width:48px; clear:both; display:block; padding:2px; border:1px solid #ddd; margin-bottom:10px;">
        <a style="-webkit-text-stroke-width: 0.5px;
   -webkit-text-stroke-color: black;font-weight:600;position: relative;left:-12px;color:red;" href="{{route('voyager.page-blocks.remove-image',['id' => $dataTypeContent->id,"blockid" => $block->id])}}">X</a>
        @endif
        @endif
        <label class="custom-file-upload" style="white-space: nowrap">
            Selecionar Imagem
            <input @if($row->required == 1 && !isset($dataTypeContent->{$row->field})) required @endif  class="hide" type="file" name="{{ $row->field }}" accept="image/*">

        </label>
        @if(isset($dataTypeContent->{$row->field}))
    </div>
@endif



{{--@if(isset($dataTypeContent->{$row->field}))--}}
{{--    <div data-field-name="{{ $row->field }}">--}}
{{--        <a href="#" class="voyager-x remove-single-image" style="position:absolute;"></a>--}}
{{--        <img src="@if( !filter_var($dataTypeContent->{$row->field}, FILTER_VALIDATE_URL)){{ Voyager::image( $dataTypeContent->{$row->field} ) }}@else{{ $dataTypeContent->{$row->field} }}@endif"--}}
{{--             data-file-name="{{ $dataTypeContent->{$row->field} }}" data-id="{{ $dataTypeContent->id }}"--}}
{{--             style="max-width:200px; height:auto; clear:both; display:block; padding:2px; border:1px solid #ddd; margin-bottom:10px;">--}}
{{--    </div>--}}
{{--@endif--}}
{{--<input @if($row->required == 1 && !isset($dataTypeContent->{$row->field})) required @endif type="file" name="{{ $row->field }}" accept="image/*">--}}
