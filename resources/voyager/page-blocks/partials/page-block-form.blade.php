<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
    </button>

    <h4 class="modal-title" id="blockeditmodal">{{$template->name}}</h4>
</div>


    @php

        if(!property_exists($template,'tabs'))
        {
        $tabs = (object)['1' => (object)['name' => 'Principal']];
        $template->tabs = $tabs;
            foreach($template->fields as $key => $field)
            {
                if(!property_exists($field,'tab'))
                {
                $template->fields->{$key}->tab = '1';
                }
            }
        }
            $item_qnt = 0;
        $clones = json_decode($block->clones);
    @endphp
    <ul class="nav nav-tabs" id="tabContent">
        @foreach($template->tabs as $key => $i)
            <li @if($key == 1) class="active" @endif><a href="#tab{{$key}}" data-toggle="tab">{{$i->name}}</a></li>
        @endforeach
        @if(is_array($clones) and sizeof($clones))
        @foreach($clones as $key => $i)
            <li><a href="#tab{{$key+1+sizeof((array)$template->tabs)}}" data-toggle="tab">Item {{$i+1}}</a></li>
        @endforeach
            @endif
    </ul>

<div class="modal-body">

    <div style="">
        <form role="form" action="{{ route('voyager.page-blocks.update', $block->id) }}" method="POST"
              enctype="multipart/form-data" data-block-id="{{$block->id}}">
            {{ method_field("PUT") }}
            {{ csrf_field() }}
            <input type="hidden" name="custom_id"
                   @if(property_exists($block,'data')) @if(property_exists($block->data,'custom_id')) value="{{$block->data->custom_id}}" @endif @endif >
            <div class="row label-no-wrap">
                @php
                    $groups = [];
                @endphp
                @foreach(collect($template->fields)->groupBy('tab') as $template_key => $template_group)
                    @if(isset($template_key))
                        <div class="tab-pane @if($template_key == 1) active @endif" id="tab{{$template_key}}">
                            @endif
                            @foreach($template_group as $key => $row)
                                @php $options = $row;
                        if(is_array($dataTypeContent)) { $dataTypeContent = (object)$dataTypeContent; }
                                @endphp
                                <div style="@if (property_exists($row,"style")){{$row->style}}@endif"
                                     class="@if(property_exists($row,"width")) {{$row->width}} @else @if(strpos($row->partial, 'rich_text_box') !== false) col-md-8 @else col-md-6  @endif @endif">
                                    <div style="display:flex;flex-direction: column;@if(property_exists($row,"mb")) @else margin-bottom: 22px; @endif">
                                        @if($row->partial == "icon")
                                            @php
                                                /* For 'multiple images' field - pass through the ID to identify the specific field */
                                                $dataTypeContent->id = $row->field;
                                            @endphp
                                            @if(property_exists($row,'display_name'))
                                                <label style="margin-top: 0px;"><label>{{ $row->display_name }}
                                                    </label>
                                                    <div style="display:inline-flex;">


                                                    </div>
                                                </label>
                                            @endif
                                        @else
                                            @if(property_exists($row,'display_name'))
                                                <label>{{ $row->display_name }}</label>

                                            @endif
                                            @php
                                                /* For 'multiple images' field - pass through the ID to identify the specific field */
                                                $dataTypeContent->id = $row->field;
                                            @endphp
                                            <div style="display:inline-flex;">
                                                @if($row->partial != 'break')
                                                @include("viaativa-".$row->partial)
                                                @endif
                                                @if(property_exists($row,'child'))
                                                    <div style="position: relative;display:flex;align-items: center;padding-left:5px;">
                                                        <div data-route="{{route('voyager.page-blocks.settings-modal',['blockid' => $block->id,'id' => $key])}}"
                                                             onclick="show_settings_modal($(this),'{{$row->display_name}}')">
                                                            @if($row->partial == "voyager::formfields.hidden")
                                                                <div class="btn btn-primary"
                                                                     style="display:flex;margin-top:0px;margin-bottom:0px;">
                                                                    Configurar {{ $row->display_name }}
                                                                    @endif
                                                                    <i class="fa fa-cog @if($row->partial != "voyager::formfields.hidden") item-cog-config @endif"
                                                                       style="@if($row->partial == "voyager::formfields.hidden") margin-left:8px; @endif font-size: 22px"></i>
                                                                    @if($row->partial == "voyager::formfields.hidden")
                                                                </div>
                                                            @endif
                                                            <div class="has-child" data-id="{{$key}}"
                                                                 style="position: absolute;width:0px;height:0px;visibility: hidden;">
                                                                @foreach($row->child as $child_key => $child_row)
                                                                    <div class="item-child">
                                                                        <label>{{ $child_row->display_name }}</label>
                                                                        @if($row->partial != 'break')
                                                                        @include("viaativa-".$child_row->partial,['child' => true, "row" => $child_row, "options" => $child_row])
                                                                            @endif
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>



                                        @endif
                                    </div>
                                </div>
                            @endforeach
                            @if(isset($template_key))
                        </div>
                    @endif
                @endforeach

                @if(property_exists($template,'clonable') and property_exists($template,'fields') and (is_object($clones) or is_array($clones)))

                    @foreach($clones as $key => $i)
                        <div class="tab-pane" id="tab{{$key+1+sizeof((array)$template->tabs)}}">
                            @foreach($template->clonable->fields as $key => $row)
                                @php
                                    $temp = $row;
                                    $temp_name = "cloned_".$key."_".$i;
                                    $temp->field = $temp_name;
                                @endphp
                                @php $options = $temp;
                    if(is_array($dataTypeContent)) { $dataTypeContent = (object)$dataTypeContent; }
                                @endphp
                                <div style="@if (property_exists($row,"style")){{$row->style}}@endif"
                                     class="@if(property_exists($temp,"width")) {{$temp->width}} @else @if(strpos($temp->partial, 'rich_text_box') !== false) col-md-8 @else col-md-6  @endif @endif">
                                    <div style="display:flex;flex-direction: column;@if(property_exists($row,"mb")) @else margin-bottom: 22px; @endif">
                                        @if($row->partial == "icon")
                                            @php
                                                /* For 'multiple images' field - pass through the ID to identify the specific field */
                                                $dataTypeContent->id = $temp->field;
                                            @endphp
                                            @if(property_exists($temp,'display_name'))
                                                <label style="margin-top: 0px;"><label>{{$temp->display_name}} do
                                                        Item {{$i}}
                                                    </label>
                                                    <div style="display:inline-flex;">


                                                    </div>
                                                </label>
                                            @endif
                                        @else
                                            @if(property_exists($row,'display_name'))
                                                <label>{{$temp->display_name}} do Item {{$i}}</label>

                                            @endif
                                            @php
                                                /* For 'multiple images' field - pass through the ID to identify the specific field */
                                                $dataTypeContent->id = $temp->field;
                                            @endphp
                                            <div style="display:inline-flex;">
                                                @include($temp->partial,["row" => $row, "options" => $temp])
                                                @if(property_exists($temp,'child'))
                                                    <div style="position: relative;display:flex;align-items: center;padding-left:5px;">
                                                        <div data-route="{{route('voyager.page-blocks.settings-modal',['blockid' => $block->id,'id' => $key])}}"
                                                             onclick="show_settings_modal($(this),'{{$temp->display_name}}')">
                                                            @if($temp->partial == "voyager::formfields.hidden")
                                                                <div class="btn btn-primary"
                                                                     style="display:flex;margin-top:0px;margin-bottom:0px;">
                                                                    Configurar {{ $temp->display_name }}
                                                                    @endif
                                                                    <i class="fa fa-cog @if($temp->partial != "voyager::formfields.hidden") item-cog-config @endif"
                                                                       style="@if($row->partial == "voyager::formfields.hidden") margin-left:8px; @endif font-size: 22px"></i>
                                                                    @if($temp->partial == "voyager::formfields.hidden")
                                                                </div>
                                                            @endif
                                                            <div class="has-child" data-id="{{$key}}"
                                                                 style="position: absolute;width:0px;height:0px;visibility: hidden;">
                                                                @foreach($temp->child as $child_key => $child_row)
                                                                    @php
                                                                        $temp_child = $child_row;
                                                                        $temp_child_name = "cloned_".$child_key."_".$i;
                                                                        $temp_child->field = $temp_child_name;
                                                                    @endphp
                                                                    <div class="item-child">
                                                                        <label>{{ $temp_child->display_name }}</label>
                                                                        @include($temp_child->partial,['child' => true, "row" => $temp_child, "options" => $temp_child])
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>


                                    </div>
                                        @endif
                                    </div>
                                    @endforeach
                                </div>
                            @endforeach
                            @endif

                        <div style="display:none;position: absolute;height: 0px;">
                            <select name="cache_ttl" id="cache_ttl" class="form-control">
                                <option value="0" {{ $block->cache_ttl === 0 ? 'selected="selected"' : '' }}>
                                    None / Off
                                </option>
                                <option value="5" {{ $block->cache_ttl === 5 ? 'selected="selected"' : '' }}>
                                    5 mins
                                </option>
                                <option value="30" {{ $block->cache_ttl === 30 ? 'selected="selected"' : '' }}>
                                    30 mins
                                </option>
                                <option value="60" {{ $block->cache_ttl === 60 ? 'selected="selected"' : '' }}>
                                    1 Hour
                                </option>
                                <option value="240" {{ $block->cache_ttl === 240 ? 'selected="selected"' : '' }}>
                                    4 Hours
                                </option>
                                <option value="1440" {{ $block->cache_ttl === 1440 ? 'selected="selected"' : '' }}>
                                    1 Day
                                </option>
                                <option value="10080" {{ $block->cache_ttl === 10080 ? 'selected="selected"' : '' }}>
                                    7 Days
                                </option>
                            </select>
                            <input
                                    type="checkbox"
                                    name="is_hidden"
                                    id="is_hidden"
                                    data-name="is_hidden"
                                    class="toggleswitch"
                                    value="1"
                                    data-on="Yes"
                                    {{ $block->is_hidden ? 'checked="checked"' : '' }}
                                    data-off="No"
                            />
                            <input
                                    type="checkbox"
                                    name="is_delete_denied"
                                    id="is_delete_denied"
                                    data-name="is_delete_denied"
                                    class="toggleswitch"
                                    value="1"
                                    data-on="Yes"
                                    {{ $block->is_delete_denied ? 'checked="checked"' : '' }}
                                    data-off="No"
                            />
                        </div>
            </div>

                        <div class="modal-footer" style="position: absolute;">
                            <button block-id="{{$block->id}}"
                                    data-save-block-btn
                                    style="float:left;margin-top: 0px;background:#0f447a;color:White;padding: 10px 16px 10px 16px;"
                                    type="submit"
                                    class="btn btn-success btn-lg save save-this-block"
                            ><i class="fas fa-save"></i> {{ __('voyager::generic.save-block') }}</button>
                        </div>
        </form>

        @if (!$block->is_delete_denied)
            <div class="modal-footer">
            <form method="POST" action="{{ route('voyager.page-blocks.destroy', $block->id) }}">
                {{ method_field("DELETE") }}
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <span class="btn-group-xs">
                        <button
                                data-delete-block-btn
                                type="submit"
                                style="float:right;font-size:14px; margin-top:0px;padding: 10px 16px 10px 16px;background-color: #ff4c5f;color:white;"
                                class="btn  btn-xs delete"
                        ><i class="fas fa-trash"></i> {{ __('voyager::generic.delete') }}</button>
                    </span>
            </form>
            </div>
        @endif
    </div>
    <script>

        $('.select2').select2();
        var selectEl = $('select.select2-sortable').select2();
        selectEl.next().children().children().children().sortable({
            containment: 'parent', stop: function (event, ui) {
                ui.item.parent().children('[title]').each(function () {
                    var title = $(this).attr('title');
                    var original = $( 'option:contains(' + title + ')', selectEl ).first();
                    original.detach();
                    selectEl.append(original)
                });
                selectEl.change();
            }
        });

        $('.awesome-colorpicker').spectrum({
            showInput: true,
            preferredFormat: 'rgb',
            showAlpha: true,
            showInitial: true,
            showPalette: true,
            palette: colors,
            appendTo: '#blockeditmodal'
        })

        $('.toggleswitch').bootstrapToggle();


        $(function() {
            tinymce.remove();
            tinymce.init({
                menubar: false,
                selector:'textarea.betterRichTextBox',
                min_height: 600,
                resize: 'vertical',

                plugins: 'link, image, code, table, lists',
                extended_valid_elements : 'input[id|name|value|type|class|style|required|placeholder|autocomplete|onclick]',
                file_browser_callback: function(field_name, url, type, win) {
                    if(type =='image'){
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
        })

    </script>

