@if(isset($options->blockData))

@endif
@foreach($items as $menu_item)
    @if(count($menu_item->children) > 0)<ul class="dropdown menu" data-dropdown-menu>@endif
        <li>
            @php
            $wanted = null;
            $scrolltg = $menu_item->link();
                if (strpos($menu_item->link(), '@') !== false) {
                    $wanted = substr($menu_item->link(), strpos($menu_item->link(), "@") + 1);
                    $scrolltg = str_replace("@".$wanted,"",$scrolltg);
                }
            @endphp
            @if(substr($menu_item->link(),0,1) == "#")
            <a class="scroller-item" @if($wanted != null) data-go-to="{{$wanted}}" @endif data-scroll-to="{{ substr($scrolltg,1) }}">{{ $menu_item->title }}</a>
            @else
                <a href="{{ $menu_item->link() }}">{{ $menu_item->title }}</a>
                @endif
            @if(count($menu_item->children) > 0)
                <ul class="dropdown menu" data-dropdown-menu>
                    @foreach($menu_item->children as $menu_item_2)
                        @if (count($menu_item->children) > 0)
                            @include('voyager-frontend::partials.menu-left', ['items' => $menu_item->children])
                        @else
                            <li>
                                @if(substr($menu_item_2->link(),0,1) == "#")
                                    <a>{{ $menu_item_2->title }}</a>
                                    @else
                                <a href="{{ $menu_item_2->link() }}">{{ $menu_item_2->title }}</a>
                                    @endif
                            </li>
                        @endif
                    @endforeach
                </ul>
            @endif
        </li>
    @if(count($menu_item->children) > 0)</ul>@endif
@endforeach
