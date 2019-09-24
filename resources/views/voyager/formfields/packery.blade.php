
<style>
    .grid-item {width:100px;height:100px; background: transparent;background-size: cover;outline: 1px solid whitesmoke }

    .grid .w-1 {
        width:100px;
    }
    .grid .h-1 {
        height:100px;
    }
    .grid .w-2 {
        width:200px;
    }
    .grid .h-2 {
        height:200px;
    }
    .grid .w-3 {
        width:300px;
    }
    .grid .h-3 {
        height:300px;
    }
    .grid .w-4 {
        width:400px;
    }
    .grid .h-4 {
        height:400px;
    }
    .grid .w-5 {
        width:500px;
    }
    .grid .w-6 {
        width:600px;
    }
    .grid .w-7 {
        width:700px;
    }
    .grid .w-8 {
        width:800px;
    }
    .grid .w-9 {
        width:900px;
    }
    .grid .w-10 {
        width:1000px;
    }
    .grid .w-11 {
        width:1100px;
    }
    .grid .w-12 {
        width:1200px;
    }

    .packery-drop-placeholder {
        outline: 3px dashed hsla(0, 0%, 0%, 0.5);
        outline-offset: -6px;
        -webkit-transition: -webkit-transform 0.2s;
        transition: transform 0.2s;
    }


    .custom-class {
        max-width: 66px;
        min-height: 66px;
        width:66px;
        object-fit: cover;
        padding: 2px !important;
    }

    .image_picker_selector li {
        height: 66px;
    }
</style>

<select class="imagepicker" data-id="{{$block->id}}">
        @if(file_exists(storage_path().'/app/public/Galeria'))
    @foreach(File::files(storage_path().'/app/public/Galeria') as $key => $file)
        <option
                data-img-src='{{Voyager::image("/Galeria/".basename($file))}}'
                value='{{basename($file)}}'
                data-img-class="custom-class"
        >
        @endforeach
                @endif
</select>

<div style="margin-bottom:12px;margin-top:8px;">
<input type="number" class="width" placeholder="largura (0-10)" data-id="{{$block->id}}">
<input type="number" class="height" placeholder="altura (0-4)" data-id="{{$block->id}}">
<input type="button" class="add-new-gallery" value="Adicionar" data-id="{{$block->id}}" data-url="{{Voyager::image('Galeria/')}}">
</div>
<input type="hidden"
       name="{{ $row->field }}" value="{{ old($row->field, $dataTypeContent->{$row->field} ?? $options->default ?? '') }}" class="grid-val" data-url="{{Voyager::image('Galeria/')}}" data-id="{{$block->id}}">
Use CTRL+Click para remover imagens.
<div style="overflow: auto">
<div class="grid" data-id="{{$block->id}}" style="width:1200px;background:rgba(0,0,0,0.08);">
</div>
</div>