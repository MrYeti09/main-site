@if ($template != null)
    @php
        $su = "-5";
        if(property_exists($template,"tabs"))
        {
            foreach($template->tabs as $k => $n)
            {
            if($n->name == "Superadmin")
            {
            $su = $k;
            }
            }
        }
        $displayed = false;
        $type = "";
        if ($block->is_minimized == 1)
        {
        $displayed = true;
        }
        $fieldSize = 0;
        if(property_exists($template,"fields"))
        {
        $fieldSize = sizeof((array)$template->fields);
        }

        if(property_exists($template,"type"))
        {
            if($template->type == "module")
            {
            $displayed = true;
            $type = "module";
            //dd($template->type);
            }
        }
        if($block->extra != null)
        {
            if(is_string($block->extra))
            {
            $block->extra = json_decode($block->extra);
            }
        }
    @endphp


    <div class="" data-id="{{ $block->id }}" id="block-id-{{ $block->id }}" tabs="">

        <div class="panel panel-bordered panel-info @if ($block->is_minimized == 1) panel-collapsed @endif"
             style="border-radius: 5px;overflow: hidden;">
            <div class="panel-heading"
                 style="flex-wrap: wrap;background-color:#ffffff;border-radius: 5px 0px 0px 0px;display:flex;align-items: center;">
                <i class="order-handle"
                   style="height:100%;@if($block->extra != null) @if(property_exists($block->extra,'color') and strlen($block->extra->color)) background-color:{{$block->extra->color}}; @else @if(isset($template->color))background-color: {{$template->color}};@else background-color:#62a8ea; @endif @endif @else @if(!empty($template->color)) background-color:{{$template->color}}; @else background-color:#62a8ea; @endif @endif border-radius: 5px 0px 0px 0px;"><i
                            class="fas fa-sort"></i></i>
                <div style="display:inline-flex;align-items: center;margin-left:40px;">
                    <h3 class="panel-title" style="white-space: nowrap;@if (!empty($template->description)) max-width:480px;; @endif">

                        @if($fieldSize > 0)
                            <a
                                    class="panel-action"
                                    data-toggle="block-collapse"
                                    style="cursor:pointer"
                            >
                                @endif
                                @if($block->extra != null)
                                    @if(property_exists($block->extra,'name'))
                                        {{$block->extra->name}}
                                    @else
                                        {{ $template->name }}
                                    @endif
                                @else
                                    {{ $template->name }}
                                @endif
                            </a>
                    </h3>
                    @if($block->extra != null)
                        @if(property_exists($block->extra,'name'))
                            <div style="height: 30px;width:1px;background-color: white;opacity: 0.7;margin-right:22px;"></div>
                            <div style="color:#232525;opacity: 0.6;font-size:13px;line-height:1;max-width:30%;margin-right:22px;">
                                {{$template->name}}
                            </div>
                        @endif
                    @endif
                    @if (!empty($template->description))
                        <div style="height: 30px;width:1px;background-color: white;opacity: 0.7;margin-right:22px;"></div>
                        <div style="color:white;opacity: 0.6;font-size:13px;line-height:1;max-width:30%;">
                            {{$template->description}}
                        </div>
                    @endif
                    @if(app('VoyagerAuth')->user()->hasPermission('browse_superadmin'))
                        <div style="height: 30px;width:1px;background-color: white;opacity: 0.7;margin-right:22px;"></div>
                        <div style="color:white;opacity: 0.6;font-size:13px;line-height:1;max-width:30%;">{{$block->path}}</div>
                    @endif
                </div>
                <div class="" style="display:inline-flex;margin-left:auto;padding:10px;padding-right:0px;">

                    <div style="display:flex;align-items: center;color:white;margin-right:12px;width:44px;justify-content: center;background-color: whitesmoke;border-radius:8px;"
                         class="cog-icon"
                         data-route="{{route('voyager.page-blocks.block-modal',['blockid' => $block->id])}}"
                         onclick="editblock($(this),{{$block->id}})"><i class="fas fa-th"
                                                                        style="display:flex;align-items: center;"></i>
                    </div>

                    <div style="display:flex;align-items: center;color:white;margin-right:12px;width:44px;justify-content: center;background-color: whitesmoke;border-radius:8px;"
                         class="cog-icon"
                         data-route="{{route('voyager.page-blocks.block-modal',['blockid' => $block->id])}}"
                         onclick="showmodal($(this),{{$block->id}})"><i class="fas fa-pencil-alt"
                                                                        style="display:flex;align-items: center;"></i>
                    </div>
                    @if($page->slug != 'blog')

                        <div style="display:flex;align-items: center;color:white;margin-right:12px;width:44px;justify-content: center;background-color: whitesmoke;border-radius:8px;"
                             class="cog-icon" onclick="show_options({{ $block->id }})"><i class="fas fa-cog"
                                                                                          style="display:flex;align-items: center;"></i>
                        </div>
                        <form method="POST" action="{{ route('voyager.page-blocks.duplicate-to')}}" class="form-options"
                              data-id="{{ $block->id }}"
                              style="display:inline-flex;margin-right:12px;width:0px;overflow: hidden;align-items: center">
                            {{ method_field("POST") }}
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="id" value="{{$block->id}}">

                            <span class="btn-group-xs">
                        <button
                                data-duplicate-block-btn
                                type="submit"
                                style="margin-left:0px;float:right;padding: 8px;margin-right:12px;"
                                class="btn btn-warning btn-xs delete"
                        ><i class="fas fa-clone"></i> Duplicar para...</button>
                    </span>
                            <select class="select2" name="target_page">
                                @foreach(\Pvtl\VoyagerPages\Page::all() as $k => $v)

                                    <option value="{{$v->id}}">{{$v->title}}</option>
                                @endforeach
                            </select>
                        </form>
                    @endif

                </div>
            </div>
            <div class="panel-body" style="padding: 0px; @if ($block->is_minimized == 1) display:none; @endif " data-block-id="{{$block->id}}">
{{--                @include('viaativa-voyager::page-blocks.partials.page-block-form')--}}
            </div> <!-- /.panel-body -->
        </div> <!-- /.panel -->
    </div> <!-- /.dd-item -->

@else
    <li class="dd-item" data-id="{{ $block->id }}" id="block-id-{{ $block->id }}">
        <i class="order-handle"><i class="fas fa-sort"></i></i>
        <div class="panel panel-bordered panel-info @if ($block->is_minimized == 1) panel-collapsed @endif">
            <div class="panel-heading" style="background-color:#f39192;">
                <h3 class="panel-title">
                    Block { {{ $block->path }} } not found
                    {{--                        @if (!$block->is_delete_denied)--}}
                    <form method="POST" action="{{ route('voyager.page-blocks.destroy', $block->id) }}">
                        {{ method_field("DELETE") }}
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        <span class="btn-group-xs">
                        <button
                                data-delete-block-btn
                                type="submit"
                                style="float:right;font-size:14px; margin-top:-32px;padding: 10px 16px 10px 16px;background-color: #ff4c5f;color:white;"
                                class="btn  btn-xs delete"
                        ><i class="fas fa-trash"></i> {{ __('voyager::generic.delete') }}</button>
                    </span>
                    </form>
                    {{--                        @endif--}}
                    {{--                    @if (!empty($template->description)) <span class="panel-desc"> {{ $template->description }}</span>@endif--}}
                    </a>
                </h3>
            </div>
        </div>
    </li>

@endif
