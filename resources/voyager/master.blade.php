<!DOCTYPE html>
<html lang="{{ config('app.locale') }}" dir="{{ __('voyager::generic.is_rtl') == 'true' ? 'rtl' : 'ltr' }}">
<head>
    <title>@yield('page_title', setting('admin.title') . " " . setting('admin.description'))</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <meta name="assets-path" content="{{ route('voyager.voyager_assets') }}"/>

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/fontawesome-iconpicker/3.2.0/css/fontawesome-iconpicker.css"/>
    <link rel="stylesheet" type="text/css"
          href="https://cdnjs.cloudflare.com/ajax/libs/spectrum/1.8.0/spectrum.min.css">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/jquery-tagsinput/1.3.6/jquery.tagsinput.min.css"/>
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/overlayscrollbars/1.9.1/css/OverlayScrollbars.css"/>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet">

    @if(class_exists("Viaativa\Viaroot\Models\Icon"))
        @foreach(\Viaativa\Viaroot\Models\Icon::all() as $icon)
            <link rel="stylesheet" type="text/css" href="{{ url($icon->path) }}">
        @endforeach
    @endif

    <!-- Favicon -->
    <?php $admin_favicon = Voyager::setting('admin.favicon_image', ''); ?>
    @if($admin_favicon == '')
        <link rel="shortcut icon" href="{{ voyager_asset('images/logo-icon.png') }}" type="image/png">
    @else
        <link rel="shortcut icon" href="{{ Voyager::image($admin_favicon) }}" type="image/png">
    @endif
    @if(json_decode(setting('admin.dash_pos'))[0] == 0)
        <link href="https://fonts.googleapis.com/css?family=Poppins&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Lato&display=swap" rel="stylesheet">
    @endif

<!-- App CSS -->
    <link rel="stylesheet" href="{{ voyager_asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ url('css/viaativa-admin.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.4/croppie.css"/>

    @yield('css')
    @if(config('voyager.multilingual.rtl'))
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-rtl/3.4.0/css/bootstrap-rtl.css">
        <link rel="stylesheet" href="{{ voyager_asset('css/rtl.css') }}">
    @endif

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>

    <!-- Few Dynamic Styles -->
    <style type="text/css">


        .delete-row button {
            background: #d60000;

            border: none;

            margin-left: 3px;

            color: white;

            border-radius: 3px;

            font-size: 12px;

            font-weight: bold;
        }

        .delete-column button {
            background: #d60000;

            border: none;

            margin-left: 3px;

            color: white;

            border-radius: 3px;

            font-size: 12px;

            font-weight: bold;
        }

        .table-wrapper .delete-row {
            display:flex;
            align-items: center;
            padding-bottom:0px;
        }

        .t-row .cell {
            max-width:150px;
            width:150px;
        }

        .t-row .cell input {
            max-width:150px;
            width:150px;
            padding:3px;
            border-radius:2px;
            margin:2px;
        }


        .tox-silver-sink {
            z-index: 100003 !important;
        }

        .tox-tiered-menu {
            z-index: 100005 !important;;
        }

        .tox-selected-menu {
            z-index: 100006 !important;;
        }

        .tox-menu.tox-collection {
            z-index: 100004 !important;;
        }

        .tox-insert-table-picker {
            z-index: 100007 !important;;
        }

        .tox-fancymenuitem {
            z-index: 100007 !important;;
        }


        .pills-wrapper {
            padding: 10px;
            background: #f5f5f5 !important;
        }

        .pills-wrapper .btn {
            margin-right:6px;

        }

        .pills .btn {
            background: #265686;
        }

        .pills .btn-danger {
            background: #bf2525 !important;
        }

        .pill-line input {
            margin-right:6px;
        }

        .pill-line {
            display:flex;
            align-items: center;
            margin-bottom:6px;
        }

        .pill-line .btn {
            margin: 0px !important;;
        }


        .nav.nav-tabs a {
            border-radius: 10px 10px 0px 0px !important;
            background: #eeeeee !important;
            min-height: 41px !important;
            max-height: 41px !important;
            transition: 0.2s all !important;
            border: none !important;
        }

        .nav.nav-tabs li.active a {
            background: cornflowerblue !important;
            border: none !important;
        }

        .nav.nav-tabs {
            background: none !important;
        }


        .select2.select2-container.select2-container--default {
            width: 100% !important;
        }


        .tag {
            padding: 1px 5px !important;
            background: none !important;
            border-color: cornflowerblue !important;
            color: #253757 !important;
            display: flex !important;
            align-items: center !important;

        }


        .config-modal:hover {
            color: black !important;
            cursor: pointer;
        }

        .tag a {
            color: indianred !important;
            padding: 0px 3px !important;
            background: transparent;
            transition: 0.2s all;

        }

        .tag a:hover {
            background: rgba(255, 0, 0, 0.55);
            border-radius: 2px;
            color: white !important;
            padding: 0px 3px !important;

        }

        .tagsinput div input {
            height: auto !important;
            padding: 1px 5px !important;
        }

        .tagsinput {
            height: auto !important;
            min-height: auto !important;
            min-width: 100% !important;
            width: 100% !important;

        }

        .darkmode--activated p, .darkmode--activated li, .darkmode--activated h1 {
            color: #e9e9e9;
        }

        .darkmode--activated .panel.widget.center.bgimage {
            box-shadow: 4px 4px 5px 0px rgba(0, 0, 0, 0.15);
        }

        .darkmode--activated .side-menu.sidebar-inverse {
            background: #323232;
            color: white !important;
        }

        .darkmode--activated .nav.navbar-nav li a {
            background: #323232 !important;
            color: white !important;
        }

        .darkmode--activated .nav.navbar-nav li.dropdown .panel-collapse a {
            background: #212121 !important;
            color: white !important;
        }

        .darkmode--activated .nav.navbar-nav li a:hover {
            background: #242424 !important;
            color: white !important;
        }

        .darkmode--activated .navbar.navbar-default.navbar-fixed-top.navbar-top {
            background: hsla(0, 0%, 20%, 0.9);
        }

        .darkmode--activated .navbar.navbar-default.navbar-fixed-top.navbar-top .container-fluid {
            border: none;
        }

        .darkmode--activated .navbar.navbar-default.navbar-fixed-top.navbar-top .container-fluid:after {
            border: none;
        }

        .darkmode--activated .dropdown.profile .caret {
            color: white;
        }

        .darkmode--activated .item-cog-config {
            color: #76838f;
        }

        .darkmode--activated .row.label-no-wrap label {
            color: black;
        }

        .darkmode--activated.voyager, .darkmode--activated.voyager .app-container {
            background: #2b2b2b;
        }

        .darkmode--activated .dropdown.profile.open .dropdown-menu.dropdown-menu-animated {
            background: #2b2b2b;
        }

        .darkmode--activated .dropdown.profile.open .dropdown-menu.dropdown-menu-animated a {
            background: #060606;
        }

        .darkmode--activated .select2-results__option {
            color: #464646 !important;
        }

        .sp-input {
            background: white !important;
        }

        .sp-replacer.sp-light {
            background: white;
            border-radius: 2px;
            box-shadow: 1px 1px 3px 0px rgba(0, 0, 0, 0.15);
        }

        .darkmode-toggle {
            z-index: 20;
        }

        .darkmode--activated tbody {
            background: #f7f7f7;
        }

        .tox-tinymce {
            width: 100%;
        }

        .container-fluid {
            padding-left: 60px;
            padding-right: 60px;
        }

        .tox-notifications-container {
            display: none;
        }

        @media (min-width: 768px) {
            .modal-xl {
                width: 90%;
                max-width: 1200px;
            }
        }

        .btn-media-picker:hover {
            cursor: pointer;
        }

        .settings-tab-counter .tab-i.active {
            background: #2687e9 !important;
            color: white !important;
        }

        .settings-tab-counter .tab-i:hover {
            cursor: pointer;
        }

        .settings-tab-counter .tab-i {
            border: 2px solid #2687e9;
            background: transparent !important;
            font-weight: 600;
            background: #2687e9;
            color: #2687e9 !important;
            height: 40px;
            min-width: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: 0.2s all;
        }


        table.dataTable.no-footer {
            font-family: 'Poppins', sans-serif;
            letter-spacing: 0.3px;
        }

        .voyager .table thead tr th {
            border-color: #eaeaea;
            border-top-color: rgb(234, 234, 234);
            background: #f1f4f9;
            background-image: none;
            color: #3b3d44;
            font-weight: 400;
        }

        .voyager .table td {
            vertical-align: middle;
        }

        .voyager .table td p {
            margin-bottom: 0px;
        }

        .voyager .table .even {
            background: #fcfcfc;
        }


        .select-btn-media-picker {
            border: 1px solid #0000004a;
            padding: 3px 7.5px;
            background: rgba(255, 255, 255, 0.78);
            border-radius: 6px;
            transition: 0.1s all;
        }

        .btn-media-picker:hover .select-btn-media-picker {
            background: #0f94dd !important;
            color: white;
        }

        .btn-media-picker:hover .img-container {
            background: rgba(255, 255, 255, 0.23);
        }

        .voyager .side-menu .navbar-header {
            background: linear-gradient(to right,{{ setting('site.primary_color', '#22A7F0') }} 0%, {{ setting('site.secondary_color', '#22A7F0') }} 100%);
            border-color: {{ config('voyager.primary_color','#22A7F0') }};
        }

        .voyager .logo-icon-container img {
            object-fit: scale-down;
        }

        .widget .btn-primary {
            border-color: {{ config('voyager.primary_color','#22A7F0') }};
        }

        .widget .btn-primary:focus, .widget .btn-primary:hover, .widget .btn-primary:active, .widget .btn-primary.active, .widget .btn-primary:active:focus {
            background: {{ config('voyager.primary_color','#22A7F0') }};
        }

        .voyager .breadcrumb a {
            color: {{ config('voyager.primary_color','#22A7F0') }};
        }

        .app-container {
            background: #f1f4f9;
        }

        .hidden-table {
            display: none !important;
        }

    </style>
    @if(json_decode(setting('admin.dash_pos'))[0] == 0)
        <style>


            .main-header .menu-icon-item:hover {
                /*background: #ffffff !important;*/

            }

            .menu-title {
                transition: 0s all;
            }

            .main-header .menu-icon-item:hover .menu-title {
                /*color: #323232 !important;*/
            }


            .main-header .dropdown-menu {
                margin-top: 0px;
                background: #ffffff;
                color: #323232;
                border-radius: 0px;
                border: none;
                padding: 0px;
            }

            .main-header .notification-bell:hover {
                cursor: pointer;
            }

            .main-header .notification {
                color: #2b2b2b;
                font-family: 'Poppins' sans-serif;
            }

            .main-header .notification:hover {
                cursor: pointer;
                background: #f9f6f6;
            }

            .main-header .dropdown-menu li {
                margin: 0px !important;
            }

            .main-header .navbar-default a:hover {
                cursor: pointer;
            }

            .main-header .dropdown-menu li a {
                margin: 0px !important;
            }

            .main-header .dropdown-menu li:hover {
                background: #2687e9;

                color: #ffffff !important;;
                cursor: pointer;
            }

            .main-header .dropdown-menu li:hover i {
                color: #ffffff !important;;
                cursor: pointer;
            }

            .main-header .menu-icon-item:hover {
                background: #2687e9;
                cursor: pointer;
            }

            .main-header .menu-title {
                color: #ffffff;
                font-size: 14px;
                opacity: 1;
                font-family: 'Lato', sans-serif;
                display: flex;
                align-items: center;;
            }

            .dropdown:hover .dropdown-menu {
                display: flex;
                visibility: visible;
                opacity: 1;
            }

            .main-grid {
                display: flex;
                width: 100%;
                margin-left: 10px;
                align-items: center;
            }
        </style>
    @endif
    @if(!empty(config('voyager.additional_css')))<!-- Additional CSS -->
    @foreach(config('voyager.additional_css') as $css)
        <link rel="stylesheet" type="text/css" href="{{ asset($css) }}">@endforeach
    @endif

    @yield('head')
</head>

<body class="voyager @if(isset($dataType) && isset($dataType->slug)){{ $dataType->slug }}@endif">

<div id="voyager-loader" style="z-index:60000">
    <?php $admin_loader_img = Voyager::setting('admin.loader', ''); ?>
    @if($admin_loader_img == '')
        <img src="{{ voyager_asset('images/logo-icon.png') }}" alt="Voyager Loader">
    @else
        <img src="{{ Voyager::image($admin_loader_img) }}" alt="Voyager Loader">
    @endif
</div>

<?php
if (starts_with(app('VoyagerAuth')->user()->avatar, 'http://') || starts_with(app('VoyagerAuth')->user()->avatar, 'https://')) {
    $user_avatar = app('VoyagerAuth')->user()->avatar;
} else {
    $user_avatar = Voyager::image(app('VoyagerAuth')->user()->avatar);
}
?>

<div class="app-container">
    <div class="fadetoblack visible-xs"></div>
    <div class="row content-container">
        @if(setting('admin.dash_pos') == null or json_decode(setting('admin.dash_pos'))[0] == 1)

            @include('voyager::dashboard.navbar')
            @include('voyager::dashboard.sidebar')
        @elseif(json_decode(setting('admin.dash_pos'))[0] == 0)
            @include('voyager::dashboard.navbar_top')
            @include('voyager::dashboard.sidebar_top')
        @endif
        <script>
            (function () {
                var appContainer = document.querySelector('.app-container'),
                    sidebar = appContainer.querySelector('.side-menu'),
                    navbar = appContainer.querySelector('nav.navbar.navbar-top'),
                    loader = document.getElementById('voyager-loader'),
                    hamburgerMenu = document.querySelector('.hamburger'),
                    sidebarTransition = sidebar.style.transition,
                    navbarTransition = navbar.style.transition,
                    containerTransition = appContainer.style.transition;

                sidebar.style.WebkitTransition = sidebar.style.MozTransition = sidebar.style.transition =
                    appContainer.style.WebkitTransition = appContainer.style.MozTransition = appContainer.style.transition =
                        navbar.style.WebkitTransition = navbar.style.MozTransition = navbar.style.transition = 'none';

                if (window.innerWidth > 768 && window.localStorage && window.localStorage['voyager.stickySidebar'] == 'true') {
                    appContainer.className += ' expanded no-animation';
                    loader.style.left = (sidebar.clientWidth / 2) + 'px';
                    hamburgerMenu.className += ' is-active no-animation';
                }

                navbar.style.WebkitTransition = navbar.style.MozTransition = navbar.style.transition = navbarTransition;
                sidebar.style.WebkitTransition = sidebar.style.MozTransition = sidebar.style.transition = sidebarTransition;
                appContainer.style.WebkitTransition = appContainer.style.MozTransition = appContainer.style.transition = containerTransition;
            })();
        </script>
        <!-- Main Content -->
        <div class="container-fluid">
            <div
                    class="@if(setting('admin.dash_pos') == null or json_decode(setting('admin.dash_pos'))[0] == 1) side-body @endif padding-top">
                <div class="container-fluid">
                    @if(env('APP_DEBUG') == true)
                    <div class="alert alert-warning alert-name-missing-storage-symlink" style="width:100%;margin-bottom:4px;">
                        O site está em modo <strong>DEBUG</strong> peça para o sua equipe de desenvolvimento ou responsável pelo site atualiza-lo para produção!
                        <br>

                    </div>
                    @endif
                    <ol class="breadcrumb hidden-xs" style="margin-top:0px;border-radius: 0px;">
                        @php
                            $segments = array_filter(explode('/', str_replace(route('voyager.dashboard'), '', Request::url())));
                            $url = route('voyager.dashboard');
                        @endphp
                        @if(count($segments) == 0)
                            <li class="active"> {{ __('voyager::generic.dashboard') }}</li>
                        @else
                            <li class="active">
                                <a href="{{ route('voyager.dashboard')}}"> {{ __('voyager::generic.dashboard') }}</a>
                            </li>
                            @foreach ($segments as $segment)
                                @php
                                    $url .= '/'.$segment;
                                @endphp
                                @if ($loop->last)
                                    <li>{{ ucfirst($segment) }}</li>
                                @else
                                    <li>
                                        <a href="{{ $url }}">{{ ucfirst($segment) }}</a>
                                    </li>
                                @endif
                            @endforeach
                        @endif
                    </ol>

                    @if(config('viaativa-site.widgets.'.end($breadcrumbs)['text']) != null)
                        <div style="float:right;margin-top:3px;">
                            <div style="">
                                <label><strong>Widget:</strong></label>
                            <input name="{{end($breadcrumbs)['text']}}" @if(in_array(end($breadcrumbs)['text'],json_decode(Auth::user()->widgets))) checked @endif type="checkbox" class="activate-widget" class="toggle" data-toggle="toggle">
                            </div>
                        </div>
                        @endif
                </div>

                @yield('page_header')

                <div id="voyager-notifications"></div>
                @yield('content')
            </div>
        </div>

    </div>
    <div style="height:98px;"></div>
    <div
            style="position: absolute;bottom:30px;display:flex;width: 100%;align-items: center;justify-content: center;flex-direction: column">
        @include('voyager::dashboard.footer')
    </div>
    <div class="modal fade" id="mediapickerModal" tabindex="-1" role="dialog" aria-labelledby="mediapickerModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content" style="padding: 15px;">

                <div class="panel">
                    <div class="page-content settings container-fluid">
                        <div class="media-picker-controller" id="media_picker_modal">

                            <media-manager
                                    ref="media_picker_modal"
                                    base-path="{{ config('voyager.media.path', '/') }}"
                                    :show-folders="{{ config('voyager.media.show_folders', true) ? 'true' : 'false' }}"
                                    :allow-upload="{{ config('voyager.media.allow_upload', true) ? 'true' : 'false' }}"
                                    :allow-move="{{ config('voyager.media.allow_move', true) ? 'true' : 'false' }}"
                                    :allow-delete="{{ config('voyager.media.allow_delete', true) ? 'true' : 'false' }}"
                                    :allow-create-folder="{{ config('voyager.media.allow_create_folder', true) ? 'true' : 'false' }}"
                                    :allow-rename="{{ config('voyager.media.allow_rename', true) ? 'true' : 'false' }}"
                                    :allow-crop="{{ config('voyager.media.allow_crop', true) ? 'true' : 'false' }}"
                                    :details="{{ json_encode(['thumbnails' => config('voyager.media.thumbnails', []), 'watermark' => config('voyager.media.watermark', (object)[])]) }}"
                            ></media-manager>

                        </div>
                    </div>
                </div>
                <button onclick="save_media_modal()" class="btn btn-primary">Selecionar</button>
                @push('javascript')
                    <script>
                        media_manager = new Vue({
                            el: '#media_picker_modal'
                        });
                    </script>
                @endpush


            </div>
        </div>
    </div>
    <div class="modal fade" id="mainModal" tabindex="-1" role="dialog" aria-labelledby="mainModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            </div>
        </div>
    </div>
    <div class="modal fade" id="layoutModal" tabindex="-1" role="dialog"
         aria-labelledby="layoutModalLabel"
         aria-hidden="true" style="font-family: 'Lato', sans-serif">
        <div class="modal-dialog" style="background:white;" role="document">
            <div class="modal-header settings-tab-counter"
                 style="display: flex;align-items: center;padding:60px; padding-bottom: 10px;">
                <div class="tab-i active" id="settings-c-1" data-tab="1">
                    1
                </div>
                <div style="width:100%;height:2px;background:rgba(0,0,0,0.11);margin: 0px 20px;"></div>
                <div class="tab-i" id="settings-c-2" data-tab="2">
                    2
                </div>
                <div style="width:100%;height:2px;background:rgba(0,0,0,0.11);margin: 0px 20px;"></div>
                <div class="tab-i" id="settings-c-3" data-tab="3">
                    3
                </div>
            </div>
            <form action="{{route('voyager.setup')}}" enctype="multipart/form-data" method="POST">
                @csrf
                <div class="modal-content" style="padding:60px; padding-top: 10px;">

                    <div id="settings-tab-1">
                        <div class="form-group">
                            <label>Mensagem de boas vindas</label>
                            <input name="welcome" class="form-control" type="text">
                        </div>
                        <div class="form-group">
                            <label>Logo da página de login</label>
                            <input name="login_logo" type="file">
                        </div>
                        <div class="form-group">
                            <label>Fundo da página de login</label>
                            <input name="login_bg" type="file">
                        </div>
                    </div>
                    <div id="settings-tab-2">
                        <div class="form-group">
                            <label>Ícone da pagina de administração</label>
                            <input name="dash_icon" type="file">
                        </div>
                        <div class="form-group">
                            <label>Ícone de carregamento da pagina de administração</label>
                            <input name="dash_load" type="file">
                        </div>
                    </div>
                    <div id="settings-tab-3"
                         style="display:flex;justify-content: center;flex-direction: column;align-items: center;">
                        <div
                                style="width: 100%;display:flex;justify-content: center;flex-direction: column;margin-bottom:20px;">
                            <span style="font-weight: 800;font-size:19px;margin-bottom:15px;">
                            Posição do painel
                                </span>
                            <div style="display:flex;width: 100%;">
                                <div style="display:flex;flex-direction: column;">
                                    <div>
                                        <input type="radio" name="dash_pos" value="0"> Topo
                                    </div>
                                    <img style="width:160px;height:160px;object-fit: cover;object-position: left;"
                                         src="{{Voyager::image('admin/top_menu.png')}}">
                                </div>
                                <div style="display:flex;flex-direction: column;margin-left:40px;">
                                    <div>
                                        <input type="radio" name="dash_pos" value="1"> Esquerda
                                    </div>
                                    <img style="width:160px;height:160px;object-fit: cover;object-position: left;"
                                         src="{{Voyager::image('admin/left_menu.png')}}">
                                </div>
                            </div>
                        </div>
                        <div style="width: 100%;display:flex;justify-content: center;flex-direction: column;">
                            <span style="font-weight: 800;font-size:19px;margin-bottom:15px;">
                            Layout
                            </span>
                            <div style="display:flex;width: 100%;">
                                <div style="display:flex;flex-direction: column;">
                                    <div>
                                        <input type="radio" name="dash_layout" value="0"> Claro
                                    </div>
                                    <img style="width:160px;height:160px;object-fit: cover;object-position: left;"
                                         src="{{Voyager::image('admin/light.png')}}">
                                </div>
                                <div style="display:flex;flex-direction: column;margin-left:40px;">
                                    <div>
                                        <input type="radio" name="dash_layout" value="1"> Escuro
                                    </div>
                                    <img style="width:160px;height:160px;object-fit: cover;object-position: left;"
                                         src="{{Voyager::image('admin/dark.png')}}">
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <div class="btn btn-primary" id="settings-continue">Continuar</div>
                    <input type="submit" id="settings-confirm" class="btn btn-success" value="Salvar">
                </div>
            </form>
        </div>
    </div>


</div>
<span id="default-colors-for-spectrum" data-colors="{{json_encode(Voyager::setting('admin.colors', ''))}}"></span>


<!-- Javascript Libs -->

<script type="text/javascript" src="{{ voyager_asset('js/app.js') }}"></script>
<script type="text/javascript" src="{{url('js/viaativa-admin.js')}}"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/overlayscrollbars/1.9.1/js/OverlayScrollbars.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/spectrum/1.8.0/spectrum.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.4/croppie.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fontawesome-iconpicker/3.2.0/js/fontawesome-iconpicker.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
<script src="https://cdn.tiny.cloud/1/224o7i7l772zjvzihwzk02605s1odj5lhfmf35qey2c45bvg/tinymce/5/tinymce.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-tagsinput/1.3.6/jquery.tagsinput.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/darkmode-js@1.4.0/lib/darkmode-js.min.js"></script>
<script>


    $('.activate-widget').change(function() {
        var val = $(this).prop('checked');
        var name = $(this).attr('name');
        $('.activate-widget').bootstrapToggle('disable')
        console.log(name)
        $.ajax({
            url: '{{route('toggle-widget')}}',
            method: 'GET',
            data: {
                name: name
            },
            success: function(data) {
                var widgets = JSON.parse(data);
                if(widgets.includes(name)) {
                    toastr.success("Widget ativado!")
                } else
                {
                    toastr.success("Widget desativado!")
                }
                $('.activate-widget').bootstrapToggle('enable')
            },
            error: function(data) {
                toastr.error("Erro ao ativar Widget!")
                $('.activate-widget').bootstrapToggle('enable')
                console.error(data)
            }
        })
    })

    var media_manager = [];

    @if(\Illuminate\Support\Facades\Auth::user()->role()->first()->name == "admin")
    @if(setting('admin.dash_pos') == null)

    $('#layoutModal').modal({backdrop: 'static', keyboard: false})
            @endif
    var tab = 1;

    $('.config-modal').click(function () {
        $('#layoutModal').modal('show')
    })

    $('#settings-tab-2').hide()
    $('#settings-tab-3').hide()
    $('#settings-confirm').hide()


    $('.tab-i').click(function () {
        tab = $(this).data('tab');
        if (tab == 3) {
            $('#settings-continue').hide()
            $('#settings-confirm').show();
        } else {
            $('#settings-continue').show()
            $('#settings-confirm').hide();
        }
        $('#settings-tab-' + (tab - 2)).hide()
        $('#settings-tab-' + (tab - 1)).hide()
        $('#settings-tab-' + (tab + 1)).hide()
        $('#settings-tab-' + (tab + 2)).hide()
        $('#settings-c-' + (tab - 1)).removeClass('active')
        $('#settings-c-' + (tab - 2)).removeClass('active')
        $('#settings-c-' + (tab + 1)).removeClass('active')
        $('#settings-c-' + (tab + 2)).removeClass('active')
        $('#settings-c-' + tab).addClass('active')
        $('#settings-tab-' + tab).show()
    })

    $('#settings-continue').click(function () {
        tab += 1
        if (tab == 3) {
            $('#settings-continue').hide()
            $('#settings-confirm').show();
        }
        $('#settings-tab-' + (tab - 1)).hide()
        $('#settings-c-' + (tab - 1)).removeClass('active')
        $('#settings-c-' + tab).addClass('active')
        $('#settings-tab-' + tab).show()
    })


    $(document).on('hover','.tox-collection__group',function() {
        $(this).css('z-index','100008')
    })


    @endif

    $(document).on('focusin', function(e) {
        if ($(e.target).closest(".tox-tinymce-aux, .moxman-window, .tam-assetmanager-root").length) {
            e.stopImmediatePropagation();
        }
    });



    $(function () {
        $(".select2-tags").select2({
            tags: true
        });
    })

    tinymce.init({
        menubar: false,
        selector: 'textarea.betterRichTextBox',
        min_height: 600,
        resize: 'vertical',

        plugins: 'link, image, code, table, lists',
        extended_valid_elements: 'input[id|name|value|type|class|style|required|placeholder|autocomplete|onclick]',
        file_browser_callback: function (field_name, url, type, win) {
            if (type == 'image') {
                $('#upload_file').trigger('click');
            }
        },
        toolbar: 'styleselect |  | bold italic underline | forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image table | code',
        convert_urls: false,
        image_caption: true,
        image_title: true,
        init_instance_callback: function (editor) {
            if (typeof tinymce_init_callback !== "undefined") {
                tinymce_init_callback(editor);
            }
        },
        setup: function (editor) {
            if (typeof tinymce_setup_callback !== "undefined") {
                tinymce_setup_callback(editor);
            }
        }
    });


    $(function () {
        $('.tagify').tagsInput();
    })


    function setCookie(name,value,days) {
        var expires = "";
        if (days) {
            var date = new Date();
            date.setTime(date.getTime() + (days*24*60*60*1000));
            expires = "; expires=" + date.toUTCString();
        }
        document.cookie = name + "=" + (value || "")  + expires + "; path=/";
    }

    function getCookie(cname) {
        var name = cname + "=";
        var decodedCookie = decodeURIComponent(document.cookie);
        var ca = decodedCookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') {
                c = c.substring(1);
            }
            if (c.indexOf(name) == 0) {
                return c.substring(name.length, c.length);
            }
        }
        return "";
    }

    const darkmode = new Darkmode({
        time: 0,
        autoMatchOsTheme: false
    });
    @if(setting('admin.dash_layout') != null and json_decode(setting('admin.dash_layout'))[0] == 1)
    darkmode.toggle();
    @endif
</script>
<script>
    $(function () {
        $('.table-filter').each(function () {
            var cookie = getCookie('{{$breadcrumbs[sizeof($breadcrumbs)-1]['text']}}-'+$(this).data('table'));
            if(cookie != undefined && cookie.length > 0 && cookie == "false") {
                $(this).prop("checked", false);
            } else {
                $(this).prop('checked',true)
            }
            $val = $(this).prop('checked')
            $('.table-' + $(this).data('table')).each(function () {
                if (!$val) {
                    $(this).addClass('hidden-table')
                } else {
                    $(this).removeClass('hidden-table')
                }
            })
        })
    })


    $('.table-filter').change(function () {
        $val = $(this).prop('checked')
        if($val == true) {
            setCookie('{{$breadcrumbs[sizeof($breadcrumbs)-1]['text']}}-' + $(this).data('table'), 'true', 15)
        } else {
            setCookie('{{$breadcrumbs[sizeof($breadcrumbs)-1]['text']}}-' + $(this).data('table'), 'false', 15)
        }
        $('.table-' + $(this).data('table')).each(function () {
            if (!$val) {
                $(this).addClass('hidden-table')
            } else {
                $(this).removeClass('hidden-table')
            }
        })
    })
</script>
<script>
            @if(Session::has('alerts'))
    let alerts = {!! json_encode(Session::get('alerts')) !!};
    helpers.displayAlerts(alerts, toastr);
    @endif

    @if(Session::has('message'))

    // TODO: change Controllers to use AlertsMessages trait... then remove this
    var alertType = {!! json_encode(Session::get('alert-type', 'info')) !!};
    var alertMessage = {!! json_encode(Session::get('message')) !!};
    var alerter = toastr[alertType];

    if (alerter) {
        alerter(alertMessage);
    } else {
        toastr.error("toastr alert-type " + alertType + " is unknown");
    }
            @endif

    var $uploadCrop = null;
    var colors = JSON.parse($('#default-colors-for-spectrum').data('colors')).split(",")

    function popupResult(result) {
        var html;
        ////console.log(result)
        if (result.html) {
            html = result.html;
            ////console.log(html)
        }
        if (result.src) {
            html = '<img src="' + result.src + '" />';
        }
    }

    function openmodal_media(val) {
        $('#mediapickerModal').modal();
        $('#mediapickerModal').data('target-field', val)
    }


    function save_media_modal() {
        $field = $('#mediapickerModal').data('target-field');
        $('input[type=hidden]').each(function () {
            if ($(this).data('target-field') == $field) {
                $(this).val(media_manager.$refs.media_picker_modal.selected_files[0].relative_path.replace('public/',''))
                $(this).siblings('img').attr('src', media_manager.$refs.media_picker_modal.selected_files[0].path)
            }
        })
        $('#mediapickerModal').modal("hide");
    }

    function readFile(input, block_id, field_name) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                // $('.img-upload-croppie').addClass('ready');
                var $uploadCropFind = find_cropper(block_id, field_name);
                ////console.log($uploadCropFind)
                if ($uploadCropFind != null) {
                    $uploadCropFind.croppie('bind', {
                        url: e.target.result
                    }).then(function (blob) {
                        ////console.log(blob)


                    });
                }
            }

            reader.readAsDataURL(input.files[0]);
        } else {
            alert("Sorry - you're browser doesn't support the FileReader API");
        }
    }


    var croppies = [];

    class cropper_field {
        constructor(block_id, field_name, croppie) {
            this.block_id = block_id;
            this.field = field_name;
            this.croppie = croppie
            croppies.push(this);
        }
    }


    function find_cropper(block_id, field_name) {
        for (var i = 0; i < croppies.length; i++) {
            var current = croppies[i];
            if (current.block_id == block_id && current.field == field_name) {
                return current.croppie;
            }
        }
        return null;
    }

    $(document).ready(function () {
        $('.awesome-colorpicker').spectrum({
            showInput: true,
            preferredFormat: 'rgb',
            showAlpha: true,
            showInitial: true,
            showPalette: true,
            palette: colors,
        })


        $('.upload-result').on('click', function (ev) {
            $this = $(this)
            var c = find_cropper($this.data('block-id'), $this.data('field-name'))
            c.croppie('result', {
                type: 'canvas',
                size: 'viewport'
            }).then(function (resp) {

                $(c).siblings('.cr-boundary').hide()
                $(c).siblings('.cr-slider-wrap').hide()
                $(c).parent().parent().find('.upload-result').hide();
                $(c).parent().parent().siblings('.upload-result').hide();
                $('.hiddenContent').each(function () {
                    if ($(this).data('block-id') == $this.data('block-id') && $(this).data('field-name') == $this.data('field-name')) {
                        $(this).val(btoa(resp));
                    }
                })
            });
        });


        $('.img-uploader').change(function () {

            $this = $(this)
            nthis = this
            block_id = $this.data('block-id')
            field_name = $this.data('field-name')
            $('.img-upload-croppie').each(function () {
                ////console.log($(this))

                if ($(this).data('block-id') == block_id && $(this).data('field-name') == field_name && find_cropper(block_id, field_name) == null) {
                    var wd = $(this).data('width')
                    var ht = $(this).data('height')
                    var type = $(this).data('type')
                    var c = $(this).croppie({
                        enableExif: true,
                        viewport: {
                            width: wd,
                            height: ht,
                            type: type
                        },
                        boundary: {
                            width: wd * 1.5,
                            height: ht * 1.5
                        }
                    });
                    var obj = new cropper_field($(this).data('block-id'), $(this).data('field-name'), c)
                    $(c).parent().parent().find('.upload-result').show();
                    $(c).parent().parent().siblings('.upload-result').show();

                }
                ////console.log(obj);
            })
            setTimeout(function () {
                block_id = $this.data('block-id')
                field_name = $this.data('field-name')
                readFile(nthis, block_id, field_name);
            }, 100)

        })

        $('.upload-result').hide();


        $(document).on('change', '.profile-selector', function () {
            var $main = $(this);
            var $blockid = $main.data('block-id');
            var $val = $("option:selected", this);
            ////console.log($val.color,$val['color'],$val)
            ////console.log($val);
            var target = $(this).data('target');


            ////console.log($(this).find('input'))
            $('.modal-config select').each(function () {
                var $this = $(this);
                var fullname = (this.name).toString().split('_')
                var type = fullname.pop()
                ////console.log($this)
                ////console.log((this.name).toString().split('_')[0],target);
                if (fullname.join("_") == target) {

                    //var type = (this.name).toString().split('_')[1];

                    var val = $val.data(type);

                    if (type != undefined && val != undefined) {
                        ////console.log(type,val)
                        $this.val(val)
                        $this.change()
                    }
                }
            })


            $('.modal-body input').each(function () {
                var $this = $(this);
                var fullname = (this.name).toString().split('_')
                var type = fullname.pop()
                ///.log(target,type,fullname.join("_"))
                ////console.log($this)
                ////console.log((this.name).toString().split('_')[0],target);
                ////console.log(this.name)
                if (fullname.join("_") == target) {
                    ////console.log((this.name).toString().split('_')[0],(this.name).toString().split('_')[1])
                    ////console.log((this.name).toString().split('_'))
                    // var type = (this.name).toString().split('_')[1];
                    var val = $val.data(type);
                    ////console.log("type", type, "val", val)
                    if (type != undefined) {
                        $this.val(val)
                    }
                    if (type == "color") {
                        $this.spectrum('set', $this.val())
                    }
                }
            })

        })

    });


</script>
<script>
    (function($){
        $.fn.pillsHere = function( options ){
            var self = this;
            var opts = $.extend( {}, $.fn.pillsHere.defaults, options );
            this.each(function() {
                addCategoryPillsButton(this, opts);
            });

            return {
                getData : function(stringify, specify){
                    return getData(self, stringify, specify)
                },
                setData : function(data, stringify){
                    setData(self, data, stringify, opts)
                }
            }

        }

        function getData(self, stringify, specify){
            var stringify = stringify == undefined ? false : stringify;
            var data = [];
            var element = self;
            if(specify !== undefined){
                element = $(self).not(specify);
            }
            $(element).each(function(i){
                data[i] = [];
                $(this).find('.pills-wrapper-container').each(function(j){
                    data[i][j] = [];
                    $(this).find('.pill-line').each(function(k){
                        data[i][j][k] = [];
                        $(this).find('input').each(function(){
                            data[i][j][k].push(this.value);
                        })
                    })
                })
            });

            if(element.length == 1){
                data = data[0];
            }

            if(stringify){
                return JSON.stringify(data);
            }
            return data;

        }


        function setData(self, data, stringify, opts){
            var stringify = stringify == undefined ? false : stringify;
            if(stringify){
                data = JSON.parse(data);
            }

            $(self).find('.pills-wrapper-container').remove();

            var allData = [];
            if(self.length == 1){
                allData[0] = data;
            }else{
                allData = data;
            }

            $(self).each(function(i, pillEls){
                $(allData[i]).each(function(i, data){
                    var pw = pillsWrapper(pillEls, opts);
                    $(data).each(function(i, v){
                        pillLine(pw, opts, v)
                    });
                });
            });
        }


        $.fn.pillsHere.defaults = {

            inputs: {
                attr: {
                    style: "width: 33%;display:inline-block;",
                    class: "form-control"
                }
            },
            buttons: {
                addCategoryPills: {
                    text: "Adicionar nova categoria",
                    attr: {
                        class: "btn btn-default"
                    }
                },
                toggleCollapse: {
                    text: "<span class='glyphicon glyphicon-triangle-top'></span> ",
                    textClosed: "<span class='glyphicon glyphicon-triangle-bottom'></span>",
                    attr: {
                        class: "btn btn-primary"
                    }
                },
                wrapperAddPillsLine: {
                    text: "Adicionar nova linha",
                    attr: {
                        class: "btn btn-success"
                    }
                },
                wrapperDeletePillsWrapper: {
                    text: "Remover categoria",
                    attr: {
                        class: "btn btn-danger"
                    }
                },
                remPillLineBtn: {
                    text: "<i class='fas fa-minus'></i>",
                    attr: {
                        class: "btn btn-danger"
                    }
                }
            },
            wrapper: {
                attr: {
                    style: "",
                    class: "panel panel-default collapse in"
                }
            },
            pills: {
                qty: 2
            }
        };


        // Add Category Pills Button
        function addCategoryPillsButton(el, opt){
            renderCategoryPillsButton(el, opt);
            addEventListenerInAddCategoryPillsButton(el, opt);
        }
        function createAddCategoryPillsButton(opt){
            var btn = document.createElement('div');
            $(btn).attr(opt.buttons.addCategoryPills.attr).addClass('add-category').html(opt.buttons.addCategoryPills.text);
            return btn;
        }
        function renderCategoryPillsButton(el, opt){
            var pillsBtn = createAddCategoryPillsButton(opt);
            $(el).append(pillsBtn);
        }
        function addEventListenerInAddCategoryPillsButton(el, opt){
            $(el).on('click', '.add-category', function(){
                pillsWrapper(el, opt)
            })

        }


        // Pills Wrapper
        function pillsWrapper(el, opt){
            var pw = renderPillsWrapper(el, opt);
            addEventListenerInDeletePillsWrapperButton(pw, opt);
            addEventListenerInAddPillsLineButton(pw, opt);
            addEventListenerInAToggleButton(pw, opt);
            return pw;
        }
        function createPillsWrapper(opt){
            var wrpContainer = document.createElement('div');
            var wrp = document.createElement('div');
            var toggleCollapse = createToggleCollapseButton(opt);
            var btnAdd = createAddPillsLineButton(opt);
            var btnRem = createDeletePillsWrapperButton(opt);
            var pillLineWrapper = createPillLineWrapper();

            $(wrp)
                .append(btnAdd)
                .append(btnRem)
                .append(pillLineWrapper).attr(opt.wrapper.attr).addClass('pills-wrapper');

            $(wrpContainer).append(toggleCollapse).append(wrp).addClass('pills-wrapper-container').prepend('<hr>');


            return wrpContainer;
        }

        function createToggleCollapseButton(opt){
            var btn = document.createElement('button');
            $(btn).attr(opt.buttons.toggleCollapse.attr).addClass('collapse-category').html(opt.buttons.toggleCollapse.text);
            return btn;
        }

        function createDeletePillsWrapperButton(opt){
            var btn = document.createElement('button');
            $(btn).attr(opt.buttons.wrapperDeletePillsWrapper.attr).addClass('rem-category').html(opt.buttons.wrapperDeletePillsWrapper.text);
            return btn;
        }
        function createAddPillsLineButton(opt){
            var btn = document.createElement('button');
            $(btn).attr(opt.buttons.wrapperAddPillsLine.attr).addClass('add-pill-line').html(opt.buttons.wrapperAddPillsLine.text);
            return btn;
        }
        function createPillLineWrapper(){
            var pillLineWrapper = document.createElement('div');
            $(pillLineWrapper).addClass('pills-line-wrapper');
            return pillLineWrapper;
        }
        function renderPillsWrapper(el, opt){
            var pillsWrapper = createPillsWrapper(opt);
            $(el).append(pillsWrapper);
            return pillsWrapper;
        }
        function addEventListenerInDeletePillsWrapperButton(el, opt){
            $(el).on('click', '.rem-category', function(){

                var pill = $(this).parents('.pills-wrapper-container').parent();
                $(this).parents('.pills-wrapper-container').remove();

                if (opt.onDeletePills !== undefined) {
                    var data = {
                        getData : function(stringify, specify){
                            return getData($(pill), stringify, specify)
                        }
                    };
                    opt.onDeletePills(data);
                }
            });
        }
        function addEventListenerInAddPillsLineButton(el, opt){
            $(el).on('click', '.add-pill-line', function(){
                pillLine($(this).parent('.pills-wrapper'), opt)
            });
        }
        function addEventListenerInAToggleButton(el, opt){
            $(el).on('click', '.collapse-category', function(){
                var self = this;
                var sibling = $(this).siblings('.pills-wrapper');

                sibling.collapse('toggle');

                sibling.on('shown.bs.collapse',function(){
                    $(self).html(opt.buttons.toggleCollapse.text);
                })

                sibling.on('hidden.bs.collapse',function(){
                    $(self).html(opt.buttons.toggleCollapse.textClosed);
                })

            });
        }

        // Pills
        function pillLine(el, opt, values){
            renderPillLine(el, opt, values);
            addEventListenerInRemPillLineButton(el, opt);
        }

        function createPillLine(self, opt, values){

            var pillLineWrapper = document.createElement('div');
            var remPillLineBtn = document.createElement('div');

            $(pillLineWrapper).addClass('pill-line');
            $(remPillLineBtn).attr(opt.buttons.remPillLineBtn.attr).addClass('rem-pill-line').html(opt.buttons.remPillLineBtn.text);

            for(var i = 0; i < opt.pills.qty; i++){
                var input = document.createElement('input');
                $(input).attr(opt.inputs.attr).attr('type', 'text');
                if(values !== undefined && values[i] !== undefined){
                    input.value = values[i];
                }
                $(pillLineWrapper).append(input);

                if (opt.onInputChange !== undefined) {
                    $(input).on('change keydown paste input', function(){
                        var data = {
                            getData : function(stringify, specify){
                                return getData($(self).parents('.pills-wrapper-container').parent(), stringify, specify)
                            }
                        };
                        opt.onInputChange(data);
                    });
                }

            }
            pillLineWrapper.append(remPillLineBtn);

            return pillLineWrapper;
        }

        function renderPillLine(el, opt, values){
            var pillsLineWrapper = createPillLine(el, opt, values);
            $(el).find('.pills-line-wrapper').append(pillsLineWrapper);
        }

        function addEventListenerInRemPillLineButton(el, opt){
            $(el).on('click', '.rem-pill-line', function(){
                $(this).parent('.pill-line').remove();

                var pill = $(this).parents('.pills-wrapper-container').parent();
                if (opt.onDeletePill !== undefined) {
                    var data = {
                        getData : function(stringify, specify){
                            return getData($(pill), stringify, specify)
                        }
                    };
                    opt.onDeletePill(data);
                }
            });

        }

    })(jQuery)

    $(".pills").each(function(i){
        var self = $(this);
        var pillsEl = [];
        pillsEl[i] = self.pillsHere({
            onInputChange: function(el){
                var inputName = $(self).data('input-name');
                $('input[name="'+inputName+'"]').val(el.getData(true));
            },
            onDeletePills: function(el){
                var inputName = $(self).data('input-name');
                $('input[name="'+inputName+'"]').val(el.getData(true));
            },
            onDeletePill: function(el){
                var inputName = $(self).data('input-name');
                $('input[name="'+inputName+'"]').val(el.getData(true));
            }
        });
        var inputName = $(self).data('input-name');
        var value = $('input[name="'+inputName+'"]').val();
        pillsEl[i].setData(value,true)
    })

    (function($) {

        $.fn.viaTable = function() {
            var self = this;
            $(this).each(function(){
                if($(this).is('input')){
                    initInput(this);
                }
            });
        };

        function initInput(element){
            var table = $('<div />')
                .addClass('input-sibling')
                .addClass('well')
                .css('background','white')
                .css('width', '100%')
                .css('overflow', 'auto');
            $(element).after(table);
            var tableWrapper = createTableWrapper(table);
            createButtonsWrapper(table, tableWrapper);
            setInputData(element, tableWrapper);
        }

        function createButtonsWrapper(element, tableWrapper){
            var mainButtonsWrapper = $('<div />')
                .addClass('buttons-wrapper');

            $(mainButtonsWrapper).append(addRowButton(element, tableWrapper));
            $(mainButtonsWrapper).append(addColumnButton(element, tableWrapper));

            $(element).prepend(mainButtonsWrapper.append("<hr>"));
        }
        function addColumnButton(element, tableWrapper){
            var btn = $("<button />")
                .attr('type', 'button')
                .text("Adicionar Coluna").addClass('btn btn-success');

            $(btn).on('click', function(){
                createColumn(tableWrapper);
                renderDeleteButtons(tableWrapper);
                updateInputData(tableWrapper);
            });

            return btn;
        }
        function addRowButton(element, tableWrapper){
            var btn = $("<button />")
                .attr('type', 'button')
                .text("Adicionar Linha").addClass('btn btn-success').css('margin-right','6px');

            $(btn).on('click', function(){
                createRow(tableWrapper);
                renderDeleteButtons(tableWrapper);
                updateInputData(tableWrapper);
            });

            return btn;
        }

        function createTableWrapper(element){
            var tableWrapper = $('<div />')
                .css('display', 'flex')
                .css('flex-direction', 'column')
                .addClass('table-wrapper')
                .data('rows', 0)
                .data('columns', 0)

            $(element).append(tableWrapper);
            return tableWrapper;

        }

        function createCell(tableWrapper, cellID){
            var input = $('<input />')
                .attr('type', 'text');

            $(input).on('keyup change paste input', function(){
                updateInputData(tableWrapper);
            });


            return $('<div />')
                .addClass('cell')
                .css('min-width', '150px')
                .append(input);
        }
        function createColumn(tableWrapper){
            var colLength = $(tableWrapper).data('columns') + 1;

            $(tableWrapper).find('.t-row').each(function(){
                $(this).append(createCell(tableWrapper, colLength));
            });

            $(tableWrapper).data('columns', colLength);
        }
        function createRow(tableWrapper){
            var rowLength = $(tableWrapper).data('rows') + 1;
            var colLength = $(tableWrapper).data('columns');

            if(!colLength){
                colLength++;
                $(tableWrapper).data('columns', colLength);
            }

            var row = $('<div />')
                .css('display', 'flex')
                .css('flex-direction', 'row')
                .addClass('t-row')
                .addClass('t-row-'+rowLength)
                .data('rowID', rowLength);

            for(var i = 1; i <= colLength; i++){
                row.append(createCell(tableWrapper, i));
            }

            $(tableWrapper).append(row);
            $(tableWrapper).data('rows', rowLength);

        }

        function renderDeleteButtons(tableWrapper){
            renderRowsDelete(tableWrapper);
            renderColumnsDelete(tableWrapper);
        }
        function renderRowsDelete(tableWrapper){
            $(tableWrapper).find('.delete-row').remove();
            $(tableWrapper).find('.t-row').each(function(){
                var rowID = $(this).data('rowID');
                var btn = createRowDeleteButton(tableWrapper, rowID);
                var div = $('<div />')
                    .addClass('delete-row')
                    .css('min-width', '150px')
                    .append(btn);
                $(this).append(div);
            });
        }
        function renderColumnsDelete(tableWrapper){
            $(tableWrapper).find('.delete-column').remove();
            var colLength = $(tableWrapper).data('columns');
            var rowDeleteColumn = $('<div />')
                .css('display', 'flex')
                .css('flex-direction', 'row')
                .addClass('delete-column');

            for(var i = 1; i <= colLength; i++){
                var div = $('<div />')
                    .css('min-width', '150px')
                    .css('text-align', 'center');
                div.append(createColumnDeleteButton(tableWrapper, i));

                rowDeleteColumn.append(div);
            }

            $(tableWrapper).prepend(rowDeleteColumn);
        }
        function createRowDeleteButton(tableWrapper, rowID){
            var btn = $('<button />')
                .attr('type', 'button')
                .data('rowID', rowID)
                .text('X');

            $(btn).on('click', function(){
                $(tableWrapper).find('.t-row-' + $(this).data('rowID')).remove();
                updateInputData(tableWrapper);
            })

            return btn;
        }
        function createColumnDeleteButton(tableWrapper, columnID){
            var btn = $('<button />')
                .attr('type', 'button')
                .data('columnID', columnID)
                .text('X');

            $(btn).on('click', function(){
                $(tableWrapper).find('.t-row').each(function(){
                    $(this).find('.cell')
                        .get(columnID - 1)
                        .remove();
                })

                var colLength = $(tableWrapper).data('columns') - 1;
                colLength = colLength < 0 ? 0 : colLength;
                $(tableWrapper).data('columns', colLength);
                renderColumnsDelete(tableWrapper);
                updateInputData(tableWrapper);
            })

            return btn;
        }

        function getData(tableWrapper){
            var data = [];
            $(tableWrapper).find('.t-row').each(function(i){
                data[i] = [];
                $(this).find('input').each(function(j){
                    data[i][j] = $(this).val();
                })
            });
            return data;
        }

        function updateInputData(tableWrapper){
            var inputSibling = $(tableWrapper).parents('.input-sibling').get(0)
            var input = $(inputSibling).siblings('input').get(0);
            $(input).val(JSON.stringify(getData(tableWrapper)));
        }

        function setInputData(input, tableWrapper){
            var inputVal = $(input).val();
            if(inputVal.length){
                var data = JSON.parse(inputVal);
                var row = data.length;
                var column = data[0].length;

                for(var i = 0; i < column; i++){
                    createColumn(tableWrapper);
                }

                for(var i = 0; i < row; i++){
                    createRow(tableWrapper);
                }

                $(tableWrapper).find('.t-row').each(function(i){
                    $(this).find('input').each(function(j){
                        $(this).val(data[i][j]);
                    })
                });

                renderDeleteButtons(tableWrapper);
            }
        }

    }( jQuery ));



</script>

@include('voyager::media.manager')
@yield('javascript')
@stack('javascript')
@if(!empty(config('voyager.additional_js')))<!-- Additional Javascript -->
@foreach(config('voyager.additional_js') as $js)
    <script type="text/javascript" src="{{ asset($js) }}"></script>@endforeach
@endif

@if(json_decode(setting('admin.dash_pos'))[0] == 0)
    <script src="https://cdnjs.cloudflare.com/ajax/libs/foundation/6.5.3/js/foundation.js"
            integrity="sha256-7WVbN/J2vA6l4tJnRTx1Yh3RGQUcNRAYLo0OV9qsL+k=" crossorigin="anonymous"></script>
    @if(auth()->check())
        <script>
            var notifications = $('.notification').length
            $('#notification-scroll').overlayScrollbars({});
            $('.notification-dropdown').hide()
            $('.notification-bell').click(function () {
                $('.notification-dropdown').toggle()
            })
            $('.notification').click(function () {
                $this = $(this);
                $data = $this.data('url')
                $(this).hide('slow')

                $.ajax({
                    url: '{{route('notification-view')}}',
                    data: {
                        'id': $this.data('id')
                    },
                    success: function (data) {
                        // console.log(data)
                        notifications -= 1
                        if (notifications <= 0) {
                            $('#notification-alerter').hide()
                        }
                        if ($data != undefined) {
                            $('#voyager-loader').css('display', 'block').css('opacity', '0').animate({opacity: 1}, 150);

                            setTimeout(function () {
                                window.location.href = $data;
                            }, 250)
                        }
                    },
                    error: function (data) {
                        console.log(data)
                    }
                })


            })
        </script>
    @endif
@endif


</body>
</html>
