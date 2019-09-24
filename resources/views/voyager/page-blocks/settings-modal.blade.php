<form style="" method="POST"
      action="{{ route('voyager.page-blocks.custom-slug')}}">
    <div class="modal-header">
        <button type="button" class="close" onclick="dont_save($('.modal-config .modal-body'))" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <h2 class="modal-title" id="mainModalLabel">Editando {{$row}}</h2>
    </div>
    <div class="modal-body">

        {{ method_field("POST") }}
        @csrf

        <input type="hidden" name="block_id" value="">

    </div>


    <div class="modal-footer">
        <button type="button" class="btn btn-secondary dont-save" padding: 5px 5px 0px 5px; onclick="dont_save($('#mainModal .modal-body'))">Fechar</button>
        <input type="button" class="btn btn-primary save-configs" style="background:#0f447a;color:White;" data-dismiss="modal" onclick="close_modal($('#mainModal .modal-body'))" value="Salvar">
    </div>
</form>
