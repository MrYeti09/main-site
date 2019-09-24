
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

        @if(!config('viaativa-site')['blockLoading']['advanced'])
            <div style="color:#ed2327">Modo avançado de blocos está desativado!</div>
            <div style="font-size:11px;opacity: 0.7">As opções de tamanho não terão efeito!</div>
            <hr>
        @endif
        <h4>
            Tamanhos:
        </h4>
        <div class="form-group">
            <label for="large">
                Grande
            </label>
            <input type="number" id="large" class="form-control" name="large"
                   @if($block->extra != null) @if(property_exists($block->extra,'large')) value="{{$block->extra->large}}" @endif @endif >
        </div>

        <div class="form-group">
            <label for="medium">
                Médio
            </label>
            <input type="number" id="medium" class="form-control" name="medium"
                   @if($block->extra != null) @if(property_exists($block->extra,'medium')) value="{{$block->extra->medium}}" @endif @endif >
        </div>

        <div class="form-group">
            <label for="small">
                Pequeno
            </label>
            <input type="number" id="small" class="form-control" name="small"
                   @if($block->extra != null) @if(property_exists($block->extra,'small')) value="{{$block->extra->small}}" @endif @endif >
        </div>

        <div class="form-group">
            <label for="fluid">
                Largura completa ?
            </label><br>
            <input type="checkbox" id="fluid" class="toggle" data-toggle="toggle" name="fluid"
                   @if($block->extra != null) @if(property_exists($block->extra,'fluid') and $block->extra->fluid == "off") @else checked @endif @else checked @endif >
        </div>

        <div class="form-group">
            <label for="padding">
                Distancia extra das bordas ?
            </label><br>
            <input type="checkbox" id="padding" class="toggle" data-toggle="toggle" name="padding"
                   @if($block->extra != null) @if(property_exists($block->extra,'padding') and $block->extra->padding == "off") @else checked @endif @endif >
        </div>
        <div class="form-group">
            <label for="mobile">
                Exibir no celular ?
            </label><br>
            <input type="checkbox" id="mobile" class="toggle" data-toggle="toggle" name="mobile"
                   @if($block->extra != null) @if(property_exists($block->extra,'mobile') and $block->extra->mobile == "off") @else checked @endif @else checked @endif >
        </div>

        @if(!config('viaativa-site')['blockLoading']['advanced'])
            <hr>
        @endif



        <div class="form-group">
            <label for="customname">
                Nome customizado
            </label>
            <input type="text" id="customname" class="form-control" name="custom_name" placeholder="Nome customizado"
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
            ID Customizado
        </label>
        <input type="text" id="customslug" class="form-control" name="id" placeholder="ID Customizado"
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

    $(function() {
        $('.toggle').bootstrapToggle();
    })
</script>
