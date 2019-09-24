<form style="" method="POST"
      action="{{ route('voyager.page-blocks.custom-slug')}}">
    <div class="modal-header">
        <h5 class="modal-title" id="mainModalLabel">{{$template->name}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        @php
            if($block->extra != null)
            {
            if(is_string($block->extra))
            {
            $block->extra = json_decode($block->extra);
            }
            }
        @endphp
        {{ method_field("POST") }}
        @csrf

        <input type="hidden" name="block_id" value="{{$block->id}}">

        <div class="form-group">
            <label for="large">
                Largura ( grande )
            </label>
            <input type="number" id="large" class="form-control" name="large" placeholder="6"
                   @if($block->extra != null) @if(property_exists($block->extra,'large')) value="{{$block->extra->large}}" @endif @endif >
        </div>

        <div class="form-group">
            <label for="medium">
                Largura ( medium )
            </label>
            <input type="number" id="medium" class="form-control" name="medium" placeholder="6"
                   @if($block->extra != null) @if(property_exists($block->extra,'medium')) value="{{$block->extra->medium}}" @endif @endif >
        </div>

        <div class="form-group">
            <label for="small">
                Largura ( small )
            </label>
            <input type="number" id="small" class="form-control" name="small" placeholder="6"
                   @if($block->extra != null) @if(property_exists($block->extra,'small')) value="{{$block->extra->small}}" @endif @endif >
        </div>


        <div class="form-group">
            <label for="customname">
                Nome customizado
            </label>
            <input type="text" id="customname" class="form-control" name="custom_name" placeholder="Custom name"
                   @if($block->extra != null) @if(property_exists($block->extra,'name')) value="{{$block->extra->name}}" @endif @endif>
        </div>

        <div class="form-group">
            <label for="customcolor">
                Cor customizada
            </label><br>
            <input type="text" id="customcolor" class="awesome-colorpicker form-control" name="custom_color" placeholder="Custom color"
                   @if($block->extra != null) @if(property_exists($block->extra,'color')) value="{{$block->extra->color}}" @endif @endif >
        </div>

        <div class="form-group">
        <label for="customslug">
            Slug customizado
        </label>
        <input type="text" id="customslug" class="form-control" name="id" placeholder="Custom slug"
               @if(property_exists($block->data,'custom_id')) value="{{$block->data->custom_id}}" @endif >
        </div>
    </div>


    <div class="modal-footer">
        <button type="button" class="btn btn-secondary dont-save">Close</button>
        <input type="submit" class="btn btn-primary" value="Save">
    </div>
</form>

<script>
    $('.awesome-colorpicker').spectrum({
        showInput: true,
        preferredFormat: 'rgb',
        showAlpha: true,
        showInitial: true,
        showPalette: true,
        palette: colors,
    })
</script>
