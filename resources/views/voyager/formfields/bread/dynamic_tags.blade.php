@php
    $tags = app($dataType->model_name)->where($row->field,'<>',null)->get()->groupBy($row->field);

@endphp
<select class="form-control select2-tags" name="{{ $row->field }}">
    <option value="">Nenhum</option>
    @foreach($tags as $key => $tag)
        <option @if($dataTypeContent->{$row->field} == $key ) selected="true" @endif >{{$key }}</option>
    @endforeach
</select>

