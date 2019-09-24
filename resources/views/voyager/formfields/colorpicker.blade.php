@php
    $df = "";
    //dd($dataTypeContent);

    if(is_object($dataTypeContent) and property_exists($dataTypeContent,$row->field))
    {
        if(isset($dataTypeContent->{$row->field}) and $dataTypeContent->{$row->field} != "")
        {
        $df = old($row->field, $dataTypeContent->{$row->field});
        } else
        {
            if(property_exists($row,"default"))
            {

            $df = $row->default;
            } else
            {
            $df = "#000000";
            }
        }
    }
        $blocks = 0;
if(isset($block->id))
{
$blocks = $block->id;
}
@endphp

<br>
<div class="colorpicker" style="max-height:34px;">
    <input style="margin-left: 10px;" type='text' id="{{ $row->field }}-{{$row->display_name }}-{{$blocks}}"
           name="{{ $row->field }}"
           class="awesome-colorpicker"
           data-name="{{ $row->display_name }}"
           step="any"
           @if(isset($dataTypeContent->{$row->field}) and $dataTypeContent->{$row->field} != "")

           value="{{$dataTypeContent->{$row->field} }}"
           @else
           @if(isset($row->default))
           value="{{$row->default}}"
           @else
                @if(isset($row->field) and (strpos($row->field,'bg') !== false or strpos($row->field,'background') !== false or strpos($row->field,'back') !== false))
                   value="rgba(0,0,0,0)"
                   @endif
           @endif
           @endif

           style="width:168px;"
    />

</div>
