<!doctype html>
<html lang="en" class="no-js">

<head>
    @if(Schema::hasTable('fonts'))
    @php
        $fonts = \Viaativa\Viaroot\Models\Fonts::class;

        if(class_exists($fonts))
        {
            $all = $fonts::all();
            $fontNames = [];

            foreach($all as $key => $val)
            {
            if(strlen($val->font_name))
            {
                $var = $val->font_name;
                $imp = "";
                if(strlen($val->font_weights) > 0)
                {
                $imp = implode(json_decode($val->font_weights), ',');
                }
                if(strlen($imp) > 0)
                {
                $var .= ":".(string)$imp;
                }

                array_push($fontNames,str_replace(' ', '+',$var));

            }
            }

            $res = implode("|",$fontNames);

            $res = str_replace(' ', '+', $res);
            //echo '<link href="https://fonts.googleapis.com/css?family='.$res.'&display=swap" rel="stylesheet"> ';

        }
    @endphp
    @foreach($fontNames as $font)
        <link href="https://fonts.googleapis.com/css?family={{$font}}&display=swap" rel="stylesheet">
    @endforeach
    @endif
    <title>@yield('meta_title', setting('site.title'))</title>
    <meta name="description"
          content="@yield('meta_description', setting('site.description')) - {{ setting('site.title') }}">
    <meta name="viewport" content="width=device-width, height=device-height,initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Open Graph -->
    <meta property="og:site_name" content="{{ setting('site.title') }}"/>
    <meta property="og:title" content="@yield('meta_title')"/>
    <meta property="og:type" content="website"/>
    <meta property="og:url" content="{{ Request::url() }}"/>
    <meta property="og:image" content="@yield('meta_image', url('/') . '/images/apple-touch-icon.png')"/>
    <meta property="og:description" content="@yield('meta_description', setting('site.description'))"/>


    <!-- Icons -->
    <meta name="msapplication-TileImage" content="{{ url('/') }}/ms-tile-icon.png"/>
    <meta name="msapplication-TileColor" content="#8cc641"/>
    <link rel="shortcut icon" href="{{  Voyager::image(setting('site.favicon')) }}"/>
    <link rel="apple-touch-icon-precomposed" href="{{ Voyager::image(setting('site.favicon')) }}"/>

    @if(class_exists("Viaativa\Viaroot\Models\Icon"))
        @foreach(\Viaativa\Viaroot\Models\Icon::all() as $icon)
            <link rel="stylesheet" type="text/css" href="{{ url($icon->path) }}">
        @endforeach
    @endif

    <!-- Styles -->

    <link rel="stylesheet" type="text/css" href="{{ url('/') }}/css/app.css">
        @if(strpos( $_SERVER['HTTP_ACCEPT'], 'image/webp' ) !== false)
            <link rel="stylesheet" type="text/css" href="{{ url('/') }}/css/blocks-webp.css">
            @else

            <link rel="stylesheet" type="text/css" href="{{ url('/') }}/css/blocks.css">
        @endif
    <!-- Custom CSS -->
    <link rel="stylesheet" type="text/css" href="{{ url('/') }}/css/viaativa-blocks.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.css" integrity="sha256-PF6MatZtiJ8/c9O9HQ8uSUXr++R9KBYu4gbNG5511WE=" crossorigin="anonymous" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/overlayscrollbars/1.9.1/css/OverlayScrollbars.css"/>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
@if (setting('site.google_analytics_tracking_id'))
    <!-- Google Analytics (gtag.js) -->
        <script async
                src="https://www.googletagmanager.com/gtag/js?id={{ setting('site.google_analytics_tracking_id') }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];

            function gtag() {
                dataLayer.push(arguments);
            }

            gtag('js', new Date());

            gtag('config', '{{ setting('site.google_analytics_tracking_id') }}');
        </script>
    @endif

    @if (setting('admin.google_recaptcha_site_key'))
        <script src='https://www.google.com/recaptcha/api.js' async defer></script>
        <script>
            function setFormId(formId) {
                window.formId = formId;
            }

            function onSubmit(token) {
                document.getElementById(window.formId).submit();
            }
        </script>
    @endif

</head>


<body>
