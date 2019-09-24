@extends('voyager::master')
@section('content')
    @php
        $routes = Route::getRoutes()->getRoutesByMethod();

    @endphp
    <style>
        .container {
            margin-top: 15px;
            width: 100%;
        }

        .bar {
            width: 100%;
            height: 20px;
            border: 1px solid #2980b9;
            border-radius: 3px;
            background-image: repeating-linear-gradient(
                    -45deg,
                    #2980b9,
                    #2980b9 11px,
                    #eee 10px,
                    #eee 20px /* determines size */
            );
            background-size: 28px 28px;
            animation: move 1s linear infinite;
        }

        @keyframes move {
            0% {
                background-position: 0 0;
            }
            100% {
                background-position: 28px 0;
            }
        }
    </style>
    <div class="container">
        <div class="well">
            <h2 id="aviso">AVISO !</h2>
            <p>O verificador é um processo demorado e que requer muito uso do servidor, tenha consciência que o site
                poderá sair fora do ar por algum tempo !</p>
            <hr>
            <a class="btn btn-success verifybtn" onclick="verify()">Verificar</a>
            <a class="btn btn-primary" onclick="generate_log()">Gerar Log</a>
        </div>
        <div style="margin-bottom:5px;" class="title-verify"></div>
        <span class="counter"></span>
        <div class="loading bar" style="width: 0%;margin-bottom: 10px;display:none;align-self: flex-start"></div>
        <div class="row" style="width: 100%">
            <div class="errors col-md-12"
                 style="display: flex;flex-direction:column;overflow-y: scroll;max-height: 500px;width: 100%;"></div>
        </div>


        @foreach(\Viaativa\Viaroot\Models\MenuItem::all() as $key => $menu)
            @if(isset($menu->route) and Route::has($menu->route))
                <div data-route="{{route($menu->route)}}"
                     data-name="{{$menu->route}}"
                     data-link="<a href='{{route($menu->route)}}'>{{route($menu->route)}}</a>"
                     class="router"></div>
            @endif
        @endforeach
        @foreach($routes['GET'] as $key => $menu)
            @if(sizeof($menu->parameterNames()) <= 0 and $menu->hasParameters() == false and Route::has($menu->getName()))
                <div data-route="{{url($key)}}"
                     data-name="{{$menu->getName()}}"
                     data-link="<a href='{{url($key)}}'>{{url($key)}}</a>"
                     class="router"></div>
            @endif
        @endforeach

    </div>
    <script>
        var width = 0;
        var counter = -1;
        var actual = 0;
        var errors = 0;
        var array_errors = [];

        function generate_log() {
            $.ajax({
                    url: '{{route('save-log')}}',
                    method: 'post',
                    data: {
                        _token: '{{csrf_token()}}',
                        info: array_errors
                    },
                    success: function (data) {
                        //console.log(data)
                    },
                    error: function (data) {
                        //console.log(data)
                    }
                }
            );
        }


        function verify() {
            $('.loading').show();
            $('.verifybtn').hide();
            $('.title-verify').text('Verificando... aguarde.')
            $('.router').each(function () {
                counter += 1;
                var name = $(this).data('name');
                var route = $(this).data('route');
                var link = $(this).data('link');

                var $new = "<div class='query' style='width:100%;margin-bottom:2px;color:#50a6d2;padding:5px;background:#323232;border-radius:2px;border: 2px dashed rgba(255,255,255,0.32);'>Quering for " + name + "</div>";
                $('.errors').append($new)
                $($new).show('slow');
                $('.errors').animate({
                    scrollTop: $('.errors').get(0).scrollHeight
                }, 10);
                $.ajax({
                    url: route,
                    method: "get",
                    success: function (data) {
                        actual += 1;
                        width += 100 / counter;
                        $('.counter').html(actual + "/" + counter)
                        $('.loading').animate({
                            "width": width + "%"
                        }, 100)
                        var $new = "<div class='working' style='width:100%;margin-bottom:2px;color:#37d25e;padding:5px;background:#323232;border-radius:2px;border: 2px dashed rgba(255,255,255,0.32);'><span style='font-weight: bold;color:white;'>" + name +" - "+ link + "</span><br> is working correctly. </div>";
                        $('.errors').append($new)
                        $($new).show('slow');
                        $('.errors').animate({
                            scrollTop: $('.errors').get(0).scrollHeight
                        }, 10);
                        if (actual >= counter) {
                            var $new = "<div style='width:100%;margin-bottom:2px;color:#37d25e;padding:5px;background:#323232;border-radius:2px;border: 2px dashed rgba(255,255,255,0.32);'>Foi finalizada a verificação, foram encontrados " + errors + " erros.</div>";
                            $('.errors').append($new)
                            $($new).show('slow');
                            $('.loading').hide("slow");
                            $(".working").hide("slow");
                            $(".title-verify").hide("slow");
                            $(".counter").hide("slow");
                            generate_log();
                            $('.verifybtn').show();
                        }
                    },
                    error: function (data, status, error) {
                        var err = eval("(" + data.responseText + ")");
                        actual += 1;
                        errors += 1;
                        width += 100 / counter;
                        $('.counter').html(actual + "/" + counter)
                        $('.loading').animate({
                            "width": width + "%"
                        }, 100)
                        array_errors.push({"name": name, "msg": err.message})
                        var $new = "<div style='width:100%;margin-bottom:2px;color:#db3a35;padding:5px;background:#323232;border-radius:2px;border: 2px dashed rgba(255,255,255,0.32);'><span style='color:white;font-weight:bold;'>" + name +" - "+ link + "</span><br>" + err.message + "</div>";
                        $('.errors').append($new)
                        $($new).show('slow')
                        if (actual >= counter) {
                            var $new = "<div style='width:100%;margin-bottom:2px;color:#37d25e;padding:5px;background:#323232;border-radius:2px;border: 2px dashed rgba(255,255,255,0.32);'>Foi finalizada a verificação, foram encontrados " + errors + " erros.</div>"
                            $('.errors').append($new)
                            $($new).show('slow');
                            $('.loading').hide("slow");
                            $(".working").hide("slow");
                            $(".title-verify").hide("slow");
                            $(".counter").hide("slow");
                            generate_log();
                            $('.verifybtn').show();
                        }
                        $('.errors').animate({
                            scrollTop: $('.errors').get(0).scrollHeight
                        }, 10);
                    }
                })
            })

            $('.counter').html(actual + "/" + counter)
        }
    </script>
@stop
