@extends('voyager::master')

@section('content')

    <div class="container-fluid" style="margin-top:15px;">
        <div class="well" style="background: white;">
            <h2 id="aviso">AVISO !</h2>
            <p>O otimizador é um processo demorado e que requer muito uso do servidor, tenha consciência que o site
                poderá sair fora do ar por algum tempo !</p>
            <hr>
            <a class="btn btn-success verifybtn" onclick="verify()">Otimizar</a>
        </div>
    </div>
    <script>
        $('.verifybtn').click(function () {
            var $this = $(this);
            var swals = Swal.fire({
                title: 'Otimizando',
                text: 'Por favor, aguarde.',
                type: 'success',
                allowOutsideClick: false,
                showCloseButton: false,
                showConfirmButton: false,
                allowEscapeKey: false
            })
            Swal.showLoading();
            $this.hide();
            $.ajax({
                url: '{{route('optimize')}}',
                method: 'POST',
                data: {
                    _token: '{{csrf_token()}}'
                }, success: function (data) {
                    toastr.success('Imagens otimizadas com sucesso!')
                    swals.close()
                    $('.verifybtn').show()
                    Swal.hideLoading()
                }, error: function(data) {
                    console.error(data);
                    toastr.error('Erro desconhecido!')
                    swals.close()
                    $('.verifybtn').show()
                    Swal.hideLoading()

                }
            })
        })
    </script>
@stop