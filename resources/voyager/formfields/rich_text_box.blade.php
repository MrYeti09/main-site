
<textarea class="form-control betterRichTextBox" name="{{ $row->field }}" id="richtext-{{ $row->field }}@if(isset($block))-{{$block->id}}@endif">
    {{ old($row->field, $dataTypeContent->{$row->field} ?? '') }}
</textarea>