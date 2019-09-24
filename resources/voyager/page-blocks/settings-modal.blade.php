<form style="" method="POST"
      action="{{ route('voyager.page-blocks.custom-slug')}}">
    <div class="modal-header">
        <h5 class="modal-title" id="mainModalLabel">Editando {{$row}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">

        {{ method_field("POST") }}
        @csrf

        <input type="hidden" name="block_id" value="">

    </div>


    <div class="modal-footer">
        <button type="button" class="btn btn-secondary dont-save" >Close</button>
        <input type="button" class="btn btn-primary" data-dismiss="modal" onclick="close_modal($('.modal-body'))" value="Save">
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
        appendTo: '#mainModalLabel'
    })
</script>
