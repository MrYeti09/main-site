<div class="hidden-xs">
    <nav class="navbar-default navbar-fixed-top navbar-top container-fluid"
         style="background:#2687e9;width: 100%;z-index: 800;height:50px;">
        <div class="container-fluid" style="height: 100%;display: flex;align-items: center;">
            <div style="display:flex;height:100%;" class="hidden-sm hidden-xs hidden-md">
                <div class=""
                     style="box-shadow: inset -2px 0px 2px 0px rgba(0,0,0,0.05);height: 100%;width: 60px;background: #22a7f0;">
                    <div class="" style="height: 100%;width: 100%;">
                        <a class="" href="{{ route('voyager.dashboard') }}"
                           style="height: 100%;width: 100%;display:flex;padding:13px;">
                            <div class="" style="width: 100%;">
                                <?php $admin_logo_img = Voyager::setting('admin.icon_image', ''); ?>
                                @if($admin_logo_img == '')
                                    <img style="opacity: 0.7;height: 100%;width: 100%;object-fit: scale-down"
                                         src="{{ Voyager::image('admin/apple-touch-icon.png') }}" alt="Logo Icon">
                                @else
                                    <img style="opacity: 0.7;height: 100%;width: 100%;object-fit: scale-down"
                                         src="{{ Voyager::image('admin/apple-touch-icon.png') }}" alt="Logo Icon">
                                @endif
                            </div>
                        </a>
                    </div>

                </div>
            </div>
            <div class="grid-container main-grid main-header" style="height:100%;">
                @foreach(adminMenu('admin', '_json') as $key => $menu)
                    <div style="display: flex;height:100%;">

                        <div class=" @if(sizeof($menu->children)) dropdown @endif menu-icon-item"
                             style="height:100%;display:flex;">
                            <a @if(sizeof($menu->children)) class="dropdown-toggle" @endif
                            @if(strlen($menu->route) and \Illuminate\Support\Facades\Route::has($menu->route)) href="{{route($menu->route)}}"
                               @elseif(strlen($menu->url)) href="{{url($menu->url)}}" @endif
                               style="padding:5px;display: flex;align-items: center;flex-direction:column;justify-content: center;@if($menu->active) color:#323232; @elseif(strlen($menu->color) and $menu->color != "#000000") color:{{$menu->color}}; @else color:#323232; @endif">


                                <div class="menu-title" style="">
                                    {{ $menu->title }}
                                    @if(sizeof($menu->children))
                                        <span class="caret"></span>
                                    @endif
                                </div>
                            </a>
                            @if(sizeof($menu->children))
                                <ul class="dropdown-menu" style="">
                                    <div style="flex-direction: column;align-items: center;display: flex;width: 100%;">
                                        @php
                                            $childrens = $menu->children;
                                        @endphp
                                        @foreach($childrens as $key_c => $menu)
                                            <a
                                                    @if(strlen($menu->route) and \Illuminate\Support\Facades\Route::has($menu->route)) href="{{route($menu->route)}}"
                                                    @elseif(strlen($menu->url)) href="{{url($menu->url)}}" @endif
                                                    style="font-family: 'Lato', sans-serif;@if($menu->active) color:#323232; @elseif(strlen($menu->color)
                                     and $menu->color != "#000000") color:{{$menu->color}}; @else color:#323232; @endif width:100%;">
                                                <li @if(sizeof($menu->children)) class="dropdown-submenu" tabindex="-1"
                                                    href="#"
                                                    @endif style="white-space: nowrap;width: 100%;padding:8px 20px;align-self: flex-start;display:flex;align-items: center;@if($menu->active) color:#323232; @elseif(strlen($menu->color)  and $menu->color != "#000000") color:{{$menu->color}}; @else color:#323232; @endif">
                                                    <i class="{{$menu->icon_class}}"
                                                       style="font-size:22px;display: flex;margin-right:6px;color:#2687e9"></i>
                                                    {{$menu->title}}
                                                </li>
                                            </a>
                                            @if($key_c+1 < sizeof($childrens))
                                                <div
                                                        style="width:80%;height:1px;background:rgba(255,255,255,0.11);"></div>
                                            @endif
                                        @endforeach
                                    </div>
                                </ul>
                            @endif
                        </div>
                    </div>

                    @if($key+1 < sizeof(adminMenu('admin', '_json')))
                        <div
                                style="height:10px;background: rgba(251,246,255,0.2);width: 1px;margin-left:6px;margin-right:6px;"></div>
                    @endif
                @endforeach
            </div>
            <div style="display:flex;visibility: hidden;" class="hidden-sm hidden-xs hidden-md">
                <div class="" style="height: 100%;width: 60px;background: #22a7f0;">
                    <div class="" style="height: 100%;width: 100%;">
                        <a class="" href="{{ route('voyager.dashboard') }}"
                           style="height: 100%;width: 100%;display:flex;padding:13px;">
                            <div class="" style="width: 100%;">
                                <?php $admin_logo_img = Voyager::setting('admin.icon_image', ''); ?>
                                @if($admin_logo_img == '')
                                    <img style="height: 100%;width: 100%;object-fit: scale-down"
                                         src="{{ voyager_asset('images/logo-icon-light.png') }}" alt="Logo Icon">
                                @else
                                    <img style="height: 100%;width: 100%;object-fit: scale-down"
                                         src="{{ Voyager::image($admin_logo_img) }}" alt="Logo Icon">
                                @endif
                            </div>
                        </a>
                    </div>

                </div>
                <div class="hidden-md"
                     style="width:60px;background-image:url({{ Voyager::image( Voyager::setting('admin.bg_image'), voyager_asset('images/bg.jpg') ) }}); background-size: cover; background-position: 0px;">

                    <div class="panel-content" style="padding:8px;display: flex;
align-content: center;
align-items: center;
justify-content: center;
height: 100%;">
                        <a href="{{route(config('voyager.dashboard.navbar_items')['voyager::generic.profile']['route'])}}">
                            <img src="{{ $user_avatar }}" class="avatar"
                                 alt="{{ app('VoyagerAuth')->user()->name }} avatar">
                        </a>

                    </div>
                </div>
            </div>
            <div style="display:flex;height:100%;align-items: center;">
                @if(\Illuminate\Support\Facades\Auth::check())
                    @php
                        //create_notification('Teste de mensagem privada para = '.\Illuminate\Support\Facades\Auth::user()->name,\Illuminate\Support\Facades\Auth::user()->id,[],["color" => '#38b0f1']);
                        //create_notification('Teste de notificação privado.','/tickets/52',[10],["color" => '#38b0f1']);
                            $notifications = \Viaativa\Viaroot\Models\Notification::all();
                            $display_notifications = [];
                            foreach($notifications as $notification)
                            {

                                if(is_null($notification->seen) or (!is_null($notification->seen) and !in_array(\Illuminate\Support\Facades\Auth::user()->id,json_decode($notification->seen))))
                                {
                                    if($notification->for == null or sizeof(json_decode($notification->for)) == 0)
                                    {
                                    array_push($display_notifications,$notification);
                                    } elseif(in_array(\Illuminate\Support\Facades\Auth::user()->id,json_decode($notification->for)))
                                    {
                                    array_push($display_notifications,$notification);
                                    }
                                }
                            }


                    @endphp
                    <div class="notification-bell" style="display:flex;flex-direction: column;position: relative;">
                        <div class="bell-bg"
                             style="position: relative;height:35px;width:35px;border-radius: 50%;margin-left:10px;margin-right:10px;display: flex;align-items: center;background:white;display: flex;align-items: center;justify-content: center;">
                            <i class="fas fa-bell" style="color:#2687e9;font-size:18px;"></i>
                            @if(sizeof($display_notifications))
                                <div id="notification-alerter"
                                     style="position: absolute;width: 10px;height:10px;background:#f71d07;right:0;top:0;border-radius:50%;box-shadow: 1px 1px 2px 0px rgba(0,0,0,0.33);"></div>
                            @endif
                        </div>
                        <div class="notification-dropdown"
                             style="position: absolute;top:100%;display: flex;flex-direction: column;right:36%">
                            @if(sizeof($display_notifications))
                                <div
                                        style="align-self: flex-end;margin-left:auto;width:8px;border-style: solid;border-width: 0 8px 8px 8px;border-color: transparent transparent #fff transparent;"></div>
                                <div id="notification-scroll"
                                     style="background:white;box-shadow: 2px 2px 2px 0px #1113;display: flex;flex-direction: column;min-width: 250px;max-height:300px;overflow-x: hidden;overflow-y: auto;">
                                    @foreach($display_notifications as $key => $notification)

                                        <div class="notification" data-id="{{$notification->id}}"
                                             @if(strlen($notification->url)) data-url="{{url($notification->url)}}"
                                             @endif style="width: 100%;">

                                            <div class="notification-bg-item" style="width:100%;padding:6px;display:flex;align-items: center">
                                                @if(isset($notification->extra) and isset(json_decode($notification->extra)->color))
                                                    <div
                                                            style="height:30px;width:3px;background:{{ json_decode($notification->extra)->color }};margin-right:8px;"></div>
                                                @endif
                                                {{$notification->text}}
                                            </div>
                                            @if($key < sizeof($display_notifications)-1)
                                                <div
                                                        style="width:100%;height:1px;background:rgba(0,0,0,0.1);margin-top:5px;"></div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                @if(\Illuminate\Support\Facades\Auth::user()->role()->first()->name == "admin")
                    <div class="config-modal"
                         style="height:35px;width:35px;border-radius: 50%;margin-right:10px;display: flex;align-items: center;background:white;display: flex;align-items: center;justify-content: center;">
                        <i class="fas fa-cog" style="color:#2687e9;font-size:18px;"></i>
                    </div>
                @endif
            </div>
            {{--        <div style="position: absolute;right:80px;margin-left: auto;margin-top:7px;margin-bottom:10px;margin-right:10px;">--}}

            {{--            @section('breadcrumbs')--}}
            {{--                <ol class="breadcrumb hidden-xs" style="margin-top:0px;">--}}
            {{--                    @php--}}
            {{--                        $segments = array_filter(explode('/', str_replace(route('voyager.dashboard'), '', Request::url())));--}}
            {{--                        $url = route('voyager.dashboard');--}}
            {{--                    @endphp--}}
            {{--                    @if(count($segments) == 0)--}}
            {{--                        <li class="active"><i class="voyager-boat"></i> {{ __('voyager::generic.dashboard') }}</li>--}}
            {{--                    @else--}}
            {{--                        <li class="active">--}}
            {{--                            <a href="{{ route('voyager.dashboard')}}"><i--}}
            {{--                                    class="voyager-boat"></i> {{ __('voyager::generic.dashboard') }}</a>--}}
            {{--                        </li>--}}
            {{--                        @foreach ($segments as $segment)--}}
            {{--                            @php--}}
            {{--                                $url .= '/'.$segment;--}}
            {{--                            @endphp--}}
            {{--                            @if ($loop->last)--}}
            {{--                                <li>{{ ucfirst($segment) }}</li>--}}
            {{--                            @else--}}
            {{--                                <li>--}}
            {{--                                    <a href="{{ $url }}">{{ ucfirst($segment) }}</a>--}}
            {{--                                </li>--}}
            {{--                            @endif--}}
            {{--                        @endforeach--}}
            {{--                    @endif--}}
            {{--                </ol>--}}
            {{--            @show--}}
            {{--        </div>--}}

            <div
                    style="width:60px;background-image:url({{ Voyager::image( Voyager::setting('admin.bg_image'), voyager_asset('images/bg.jpg') ) }}); background-size: cover; background-position: 0px;">

                <div class="panel-content hidden-md" style="padding:8px;display: flex;

align-content: center;

align-items: center;

justify-content: center;

height: 100%;">
                    <a style="display:flex;width: 100%;height:100%;justify-content: center;"
                       href="{{route(config('voyager.dashboard.navbar_items')['voyager::generic.profile']['route'])}}">
                        <img style="object-fit: cover;border-radius: 50%;width: 34px;" src="{{ $user_avatar }}"
                             class="avatar" alt="{{ app('VoyagerAuth')->user()->name }} avatar">
                    </a>

                </div>
            </div>
        </div>
    </nav>
</div>
<div class="hidden-sm hidden-md hidden-lg hidden-xl">
    <nav class="navbar navbar-default navbar-fixed-top navbar-top">
        <div class="container-fluid">
            <div class="navbar-header">
                <button class="hamburger btn-link">
                    <span class="hamburger-inner"></span>
                </button>
                @section('breadcrumbs')
                    <ol class="breadcrumb hidden-xs">
                        @php
                            $segments = array_filter(explode('/', str_replace(route('voyager.dashboard'), '', Request::url())));
                            $url = route('voyager.dashboard');
                        @endphp
                        @if(count($segments) == 0)
                            <li class="active"><i class="voyager-boat"></i> {{ __('voyager::generic.dashboard') }}</li>
                        @else
                            <li class="active">
                                <a href="{{ route('voyager.dashboard')}}"><i
                                            class="voyager-boat"></i> {{ __('voyager::generic.dashboard') }}</a>
                            </li>
                            @foreach ($segments as $segment)
                                @php
                                    $url .= '/'.$segment;
                                @endphp
                                @if ($loop->last)

                                    @if(config('breadcrumbs'))
                                    @endif
                                    <li>{{ ucfirst($segment) }}</li>
                                @else
                                    <li>
                                        <a href="{{ $url }}">{{ ucfirst($segment) }}</a>
                                    </li>
                                @endif
                            @endforeach
                        @endif
                    </ol>
                @show
            </div>
            <ul class="nav navbar-nav @if (config('voyager.multilingual.rtl')) navbar-left @else navbar-right @endif">
                <li class="dropdown profile">
                    <a href="#" class="dropdown-toggle text-right" data-toggle="dropdown" role="button"
                       aria-expanded="false"><img src="{{ $user_avatar }}" class="profile-img"> <span
                                class="caret"></span></a>
                    <ul class="dropdown-menu dropdown-menu-animated">
                        <li class="profile-img">
                            <img src="{{ $user_avatar }}" class="profile-img">
                            <div class="profile-body">
                                <h5>{{ app('VoyagerAuth')->user()->name }}</h5>
                                <h6>{{ app('VoyagerAuth')->user()->email }}</h6>
                            </div>
                        </li>
                        <li class="divider"></li>
                        <?php $nav_items = config('voyager.dashboard.navbar_items'); ?>
                        @if(is_array($nav_items) && !empty($nav_items))
                            @foreach($nav_items as $name => $item)
                                <li {!! isset($item['classes']) && !empty($item['classes']) ? 'class="'.$item['classes'].'"' : '' !!}>
                                    @if(isset($item['route']) && $item['route'] == 'voyager.logout')
                                        <form action="{{ route('voyager.logout') }}" method="POST">
                                            {{ csrf_field() }}
                                            <button type="submit" class="btn btn-danger btn-block">
                                                @if(isset($item['icon_class']) && !empty($item['icon_class']))
                                                    <i class="{!! $item['icon_class'] !!}"></i>
                                                @endif
                                                {{__($name)}}
                                            </button>
                                        </form>
                                    @else
                                        <a href="{{ isset($item['route']) && Route::has($item['route']) ? route($item['route']) : (isset($item['route']) ? $item['route'] : '#') }}" {!! isset($item['target_blank']) && $item['target_blank'] ? 'target="_blank"' : '' !!}>
                                            @if(isset($item['icon_class']) && !empty($item['icon_class']))
                                                <i class="{!! $item['icon_class'] !!}"></i>
                                            @endif
                                            {{__($name)}}
                                        </a>
                                    @endif
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </li>
            </ul>
        </div>
    </nav>

</div>
<div style="height: 60px;"></div>



