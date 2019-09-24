@extends('voyager::master')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>

        .import-card {
            display: flex;
            align-items: center;
            padding: 6px;
            background: #f5f5f5;
            border: 1px solid rgba(0, 0, 0, 0.16);
            margin-top: 5px;
            margin-bottom: 5px;
            border-radius: 3px;
            transition: 0.2s all;
        }

        .swal2-container {
            z-index: 300000 !important;
        }

        .import-card:hover {
            background: #edf5f5;
        }

        .load-template i {
            opacity: 0.5 !important;
            transition: 0.2s all;
        }

        .load-template:hover i {
            opacity: 0.8 !important;
            cursor: pointer;
        }

        #blockeditmodal .modal-config .modal-body {
            padding: 20px !important;;
        }

        #blockeditmodal .modal-footer {
            bottom: 0
        }

        #blockeditmodal .modal-body .row {
            margin: 0;
            margin-top: 15px;
        }

        #blocksettingsmodal {
            z-index: 100002;
        }


        .select2-container {
            border: 1px solid #80808026;
        }

        .label-no-wrap label {
            white-space: nowrap;
            font-size: 12px;
        }

        .cog-icon i {
            color: #2b2b2b;
            cursor: pointer;
        }

        .cog-icon:hover i {
            color: #4a5c62;
            cursor: pointer;
        }

        .cog-icon:hover {
            color: #4a5c62;
            cursor: pointer;
        }

        .tooltip-controller:hover {

        }

        .tooltip-controller .tooltiptext {
            visibility: hidden;
            position: absolute;
            top: 38px;
            line-height: 1;
            background-color: #313942;
            padding: 9px;
            font-size: 14px;
            color: white;
            border-radius: 6px;
        }

        .tooltip-controller:hover .tooltiptext {
            visibility: visible;
            opacity: 1;
        }

        .item-cog-config:hover {
            cursor: pointer;
            color: #8aacff;
        }

    </style>

    <style type="text/css">
        /* Image field type */
        .vpb-image-group label {
            display: block;
        }

        .vpb-image-group img {
            float: left;
            width: 28% !important;
            margin-right: 2%;
        }

        .vpb-image-group input[type=file] {
            float: left;
            width: 70%;
        }

        /* Toggle Button */
        .toggle.btn {
            box-shadow: 0 5px 9px -3px rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(0, 0, 0, 0.2) !important;
        }

        /* Collapsed Panel */
        .panel-collapsed .panel-body {
            /* display: none; */
        }

        .panel-collapsed .panel-collapse-icon {
            transform: rotate(180deg);
        }

        /* Make Inputs a 'lil more visible */
        select,
        input[type="text"],
        .panel-body .select2-selection {
            border: 1px solid rgba(0, 0, 0, 0.17)
        }

        /* Reorder */
        .dd .dd-placeholder {
            max-height: 61px;
            margin-bottom: 22px;
        }

        .dd h3.panel-title,
        .dd-dragel h3.panel-title {
            padding-left: 55px;
        }

        .dd-dragel .panel-body,
        .dd-dragging .panel-body {
            display: none !important;
        }

        .order-handle {
            z-index: 1;
            position: absolute;
            padding: 20px 15px;
            background: rgba(255, 255, 255, 0.2);
            font-size: 15px;
            color: #fff;
            line-height: 20px;
            box-shadow: inset -2px 0px 2px rgba(0, 0, 0, 0.1);
            cursor: move;
        }

        .btn.btn-via:hover {
            background: #275687 !important;
        }

        .btn.btn-via {
            background: #0f447a !important;
            color: white;
        }

        .ui-sortable-placeholder {
            height: 67px;
            background: rgba(0, 0, 0, 0.03);
            border: 3px dashed rgba(0, 0, 0, 0.58);
            visibility: visible !important;
            margin-bottom: 22px;
        }
    </style>
@stop

@section('page_title', 'Edit Page Content')

@section('page_header')
    <h1 id="page-title"
        style="display: inline-block;font-size: 18px;height: 100px;line-height: 43px;margin-top: 3px;padding-top: 28px;    padding-left: 75px;    position: relative;margin-bottom: 0;font-weight: 700;margin-right: 20px;">
        <i class="voyager-params"
           style="font-size: 36px;    position: absolute;    top: 30px;    left: 25px;margin-right: 10px;"></i>
        Editando configurações principais
    </h1>
    @php
        $templates = config('page-blocks');
        $hasHeader = false;
        $hasFooter = false;
        $all = Viaativa\Viaroot\Models\PageBlock::where('page_id','-1')->get();
        foreach($all as $block)
        {
            if(isset($templates[$block->path]['type']) and $templates[$block->path]['type'] == "header")
            {
            $hasHeader = true;
            }
            if(isset($templates[$block->path]['type']) and $templates[$block->path]['type'] == "footer")
            {
            $hasFooter = true;
            }
        }
    @endphp
    @if($hasHeader == false and sizeof(collect($templates)->where('type','header')))

        <div style="display:inline-flex;align-items: center">
            <form method="POST" action="{{route('voyager.page-blocks.main-settings.add')}}" style="display:inline-flex;align-items: center">
                @csrf
                <select name="id" class="form-control" style="max-width:128px;">
                    @foreach(collect($templates)->where('type','header') as $key => $block)
                        <option value="{{$key}}">{{$block['name']}}</option>
                    @endforeach
                </select>
                <input type="submit" style="margin-left:8px;" class="btn btn-primary" value="Adicionar Header">
            </form>
        </div>
    @endif
    @if($hasFooter == false and sizeof(collect($templates)->where('type','footer')))

        <div style="display:inline-flex;align-items: center">
            <form method="POST" action="{{route('voyager.page-blocks.main-settings.add')}}" style="display:inline-flex;align-items: center">
                @csrf
                <select name="id" class="form-control" style="max-width:128px;">
                    @foreach(collect($templates)->where('type','footer') as $key => $block)
                        <option value="{{$key}}">{{$block['name']}}</option>
                    @endforeach
                </select>
                <input type="submit" style="margin-left:8px;" class="btn btn-primary" value="Adicionar Footer">
            </form>
        </div>
    @endif
    @include('voyager::multilingual.language-selector')
@stop

@section('content')
    @php
        $groups = [];
    @endphp
    <div class="page-content edit-add container-fluid">
        <div class="row">
            @if($hasHeader or $hasFooter)
                @foreach($pageBlocks as $key => $block)
                    @php
                        $template = $templates[$block->path];
                    @endphp
                    @include('viaativa-voyager::page-blocks.partials.page-blocks-sorting')
{{--                    @if(array_key_exists('type',$template))--}}
{{--                        @if($template['type'] == "header" or $template['type'] == "footer")--}}
{{--                            <div class="">--}}
{{--                                <div class="panel panel-bordered panel-primary">--}}
{{--                                    <div class="panel-heading" style="@if(isset($template['color']))background-color: {{$template['color']}};@else background-color:#303841; @endif">--}}
{{--                                        <h3 class="panel-title">{{ucfirst($template['type']) }}</h3>--}}
{{--                                        <div class="panel-actions">--}}
{{--                                        </div>--}}

{{--                                        @if(array_key_exists('tabs',$template))--}}
{{--                                            <div id="tabs" style="flex-wrap: wrap;@if(isset($template['color']))background-color: {{$template['color']}};@else background-color:#57c7d4; @endif ;filter: brightness(90%);width:100%;display:inline-flex;position:relative;">--}}
{{--                                                @foreach($template['tabs'] as $key => $tab)--}}
{{--                                                    <div class="tab-button tooltip-controller" style="border: 1px solid rgba(0,0,0,0.07);margin-right:0px;border-right:0px;margin-left:11px;flex-direction: row;justify-content: center;display:flex;align-items:center;font-size:17px;white-space:nowrap;font-weight: 800;"--}}
{{--                                                         tab-btn-id="{{$key}}_{{$pageBlock->id}}" contained-tab="{{$key}}"--}}
{{--                                                         block_id="{{$pageBlock->id}}">--}}
{{--                                                        {{$tab['name']}}--}}
{{--                                                    </div>--}}
{{--                                                @endforeach--}}
{{--                                            </div>--}}
{{--                                        @endif--}}
{{--                                    </div>--}}
{{--                                    <div class="panel-body">--}}
{{--                                        <form role="form" action="{{ route('voyager.page-blocks.update', $pageBlock->id) }}" method="POST"--}}
{{--                                              enctype="multipart/form-data">--}}
{{--                                            {{ method_field("PUT") }}--}}
{{--                                            {{ csrf_field() }}--}}
{{--                                            <div class="row">--}}
{{--                                                @foreach($template['fields'] as $field_key => $block)--}}
{{--                                                    --}}
{{--                                                    --}}{{--                                                @php--}}
{{--                                                    --}}{{--                                                    if(array_key_exists('options',$field))--}}
{{--                                                    --}}{{--                                                    {--}}
{{--                                                    --}}{{--                                                    $opt = (object)$field;--}}
{{--                                                    --}}{{--                                                    } else {--}}
{{--                                                    --}}{{--                                                    $opt = null;--}}
{{--                                                    --}}{{--                                                    }--}}
{{--                                                    --}}{{--                                                @endphp--}}
{{--                                                    --}}{{--                                                @if($field['partial'] == "break")--}}
{{--                                                    --}}{{--                                                    <div class="col-md-12 input-tab" @if(array_key_exists('tab',$field)) tab-id="{{$field['tab']}}_{{$pageBlock->id}}" @endif style="height:1px;background: rgba(43,43,43,0.23);margin-bottom:12px;"><label>{{$field['display_name']}}</label></div>--}}
{{--                                                    --}}{{--                                                @else--}}
{{--                                                    --}}{{--                                                    <div style=" @if($field['partial'] == "voyager::formfields.icon") max-height:61px; @endif @if(array_key_exists('width',$field)) @if($field['width'] == 'none') visibility:hidden;position: absolute;width: 0px;height:0px; @endif @endif" class="@if(array_key_exists('width',$field)) {{$field['width']}} @else col-md-4 @endif " @if(array_key_exists('tab',$field)) tab-id="{{$field['tab']}}_{{$pageBlock->id}}" @endif>--}}
{{--                                                    --}}{{--                                                        <label style="width: 100%;display:flex;flex-direction: column">--}}
{{--                                                    --}}{{--                                                            {{$field['display_name']}}</label>--}}
{{--                                                    --}}{{--                                                        <div style="display: flex;">--}}
{{--                                                    --}}{{--                                                            @include($field['partial'],['row' => (object)$field,'options' => (object)$field,"dataTypeContent" => $pageBlock->data,"block" => $pageBlock])--}}
{{--                                                    --}}{{--                                                            @php--}}
{{--                                                    --}}{{--                                                                $row = (object)$field;--}}

{{--                                                    --}}{{--                                                            @endphp--}}
{{--                                                    --}}{{--                                                            @if(property_exists($row,'child'))--}}
{{--                                                    --}}{{--                                                                <div style="position: relative;display:flex;align-items: center;padding-left:5px;">--}}
{{--                                                    --}}{{--                                                                    <div data-route="{{route('voyager.page-blocks.settings-modal',['blockid' => $pageBlock->id,'id' => $key])}}"--}}
{{--                                                    --}}{{--                                                                         onclick="show_settings_modal($(this),'{{$row->display_name}}')">--}}
{{--                                                    --}}{{--                                                                        @if($row->partial == "voyager::formfields.hidden")--}}
{{--                                                    --}}{{--                                                                            <div class="btn btn-primary" style="display:flex;margin-top:0px;margin-bottom:30px;">--}}
{{--                                                    --}}{{--                                                                                Configurar {{ $row->display_name }}--}}
{{--                                                    --}}{{--                                                                                @endif--}}
{{--                                                    --}}{{--                                                                                <i class="fa fa-cog @if($row->partial != "voyager::formfields.hidden") item-cog-config @endif" style="@if($row->partial == "voyager::formfields.hidden") margin-left:8px; @endif font-size: 22px"></i>--}}
{{--                                                    --}}{{--                                                                                @if($row->partial == "voyager::formfields.hidden")--}}
{{--                                                    --}}{{--                                                                            </div>--}}
{{--                                                    --}}{{--                                                                        @endif--}}
{{--                                                    --}}{{--                                                                        <div class="has-child" data-id="{{$key}}"--}}
{{--                                                    --}}{{--                                                                             style="position: absolute;width:0px;height:0px;visibility: hidden;">--}}
{{--                                                    --}}{{--                                                                            @foreach($row->child as $child_key => $row)--}}
{{--                                                    --}}{{--                                                                                @php--}}
{{--                                                    --}}{{--                                                                                    $row = (object)$row;--}}

{{--                                                    --}}{{--                                                                                @endphp--}}
{{--                                                    --}}{{--                                                                                <div class="item-child">--}}
{{--                                                    --}}{{--                                                                                    <label>{{ $row->display_name }}</label>--}}
{{--                                                    --}}{{--                                                                                    @include($row->partial,['child' => true,"options" => $row,"dataTypeContent" => $pageBlock->data])--}}
{{--                                                    --}}{{--                                                                                </div>--}}
{{--                                                    --}}{{--                                                                            @endforeach--}}
{{--                                                    --}}{{--                                                                        </div>--}}
{{--                                                    --}}{{--                                                                    </div>--}}
{{--                                                    --}}{{--                                                                </div>--}}

{{--                                                    --}}{{--                                                            @endif--}}
{{--                                                    --}}{{--                                                        </div>--}}

{{--                                                    --}}{{--                                                    </div>--}}
{{--                                                    --}}{{--                                                @endif--}}

{{--                                                @endforeach--}}
{{--                                            </div>--}}
{{--                                            <div style="visibility: hidden;position: absolute;height: 0px;">--}}
{{--                                                <div class="col-mg-6 col-lg-4">--}}
{{--                                                    <div class="form-group">--}}
{{--                                                        <label for="cache_ttl">Cache Time</label>--}}
{{--                                                        <select name="cache_ttl" id="cache_ttl" class="form-control">--}}
{{--                                                            <option value="0" {{ $pageBlock->cache_ttl === 0 ? 'selected="selected"' : '' }}>--}}
{{--                                                                None / Off--}}
{{--                                                            </option>--}}
{{--                                                            <option value="5" {{ $pageBlock->cache_ttl === 5 ? 'selected="selected"' : '' }}>--}}
{{--                                                                5 mins--}}
{{--                                                            </option>--}}
{{--                                                            <option value="30" {{ $pageBlock->cache_ttl === 30 ? 'selected="selected"' : '' }}>--}}
{{--                                                                30 mins--}}
{{--                                                            </option>--}}
{{--                                                            <option value="60" {{ $pageBlock->cache_ttl === 60 ? 'selected="selected"' : '' }}>--}}
{{--                                                                1 Hour--}}
{{--                                                            </option>--}}
{{--                                                            <option value="240" {{ $pageBlock->cache_ttl === 240 ? 'selected="selected"' : '' }}>--}}
{{--                                                                4 Hours--}}
{{--                                                            </option>--}}
{{--                                                            <option value="1440" {{ $pageBlock->cache_ttl === 1440 ? 'selected="selected"' : '' }}>--}}
{{--                                                                1 Day--}}
{{--                                                            </option>--}}
{{--                                                            <option value="10080" {{ $pageBlock->cache_ttl === 10080 ? 'selected="selected"' : '' }}>--}}
{{--                                                                7 Days--}}
{{--                                                            </option>--}}
{{--                                                        </select>--}}
{{--                                                    </div> <!-- /.form-group -->--}}
{{--                                                </div> <!-- /.col -->--}}

{{--                                                <div class="col-mg-6 col-lg-8">--}}
{{--                                                    <label>Options</label>--}}

{{--                                                    <div class="row">--}}
{{--                                                        <div class="col-md-6 col-lg-5">--}}
{{--                                                            <div class="form-group">--}}
{{--                                                                <input--}}
{{--                                                                        type="checkbox"--}}
{{--                                                                        name="is_hidden"--}}
{{--                                                                        id="is_hidden"--}}
{{--                                                                        data-name="is_hidden"--}}
{{--                                                                        class="toggleswitch"--}}
{{--                                                                        value="1"--}}
{{--                                                                        data-on="Yes"--}}
{{--                                                                        {{ $pageBlock->is_hidden ? 'checked="checked"' : '' }}--}}
{{--                                                                        data-off="No"--}}
{{--                                                                />--}}
{{--                                                                <label for="is_hidden"> &nbsp;Hide Block</label>--}}

{{--                                                            </div> <!-- /.form-group -->--}}
{{--                                                        </div> <!-- /.col -->--}}

{{--                                                        <div class="col-md-6 col-lg-5">--}}
{{--                                                            <div class="form-group">--}}
{{--                                                                <input--}}
{{--                                                                        type="checkbox"--}}
{{--                                                                        name="is_delete_denied"--}}
{{--                                                                        id="is_delete_denied"--}}
{{--                                                                        data-name="is_delete_denied"--}}
{{--                                                                        class="toggleswitch"--}}
{{--                                                                        value="1"--}}
{{--                                                                        data-on="Yes"--}}
{{--                                                                        {{ $pageBlock->is_delete_denied ? 'checked="checked"' : '' }}--}}
{{--                                                                        data-off="No"--}}
{{--                                                                />--}}
{{--                                                                <label for="is_delete_denied"> &nbsp;Prevent--}}
{{--                                                                    Deletion</label>--}}
{{--                                                            </div> <!-- /.form-group -->--}}
{{--                                                        </div> <!-- /.col -->--}}
{{--                                                    </div> <!-- /.row -->--}}
{{--                                                </div> <!-- /.col -->--}}
{{--                                            </div>--}}
{{--                                            <button block-id="-1"--}}
{{--                                                    data-save-block-btn--}}
{{--                                                    style="float:left;margin-top: 0px;"--}}
{{--                                                    type="submit"--}}
{{--                                                    class="btn btn-success btn-lg save save-this-block"--}}
{{--                                            ><i class="fas fa-save"></i> {{ __('voyager::generic.save-block') }}</button>--}}
{{--                                        </form>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        @endif--}}
{{--                    @endif--}}
                @endforeach
            @endif
        </div>
    </div>
    <style>
        .tab-pane {
            display: none;
        }

        .tab-pane.active {
            display: block;
        }
    </style>

    <div class="modal fade" id="blockloadlayoutmodal" tabindex="-1" role="dialog"
         aria-labelledby="blockloadlayoutmodal"
         aria-hidden="true">
        <div class="modal-dialog modal-xl">

            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h2 class="modal-title" id="mainModalLabel">Importando Template</h2>

                </div>
                <div class="modal-body">
                    @foreach(\Viaativa\Viaroot\Models\Template::all() as $layoutTemplate)
                        <div class="import-card" data-id="{{$layoutTemplate->id}}">
                            <div class="delete-menu" style="margin-right:8px;">
                                <button style="float:left;" type="button" data-id="{{$layoutTemplate->id}}"
                                        class="close remove-template" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div>
                                    <span class="template-name"
                                          style="font-weight: bold;">{{$layoutTemplate->name}}</span>
                                @if(isset($layoutTemplate->data) and is_array($layoutTemplate->data) and sizeof($layoutTemplate->data) > 1)
                                    <span style="font-size:12px;opacity:0.7">- {{sizeof($layoutTemplate->data)}} items</span> @endif
                            </div>
                            <div class="action" style="margin-left:auto;margin-right:10px;">
                                <a class="load-template edit-template-name" data-id="{{$layoutTemplate->id}}">
                                    <i class="fas fa-cog"
                                       style="color: black;opacity: 0.5;font-size: 28px;padding: 5px;"></i>
                                </a>
                            </div>
                            <div class="action" style="">
                                <a class="load-template"
                                   href="{{route('voyager.page-blocks.add-template',['page' => $page->id,'template' => $layoutTemplate->id])}}">
                                    <i class="fas fa-level-down-alt"
                                       style="color: black;opacity: 0.5;font-size: 28px;padding: 5px;"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="blocklayoutmodal" tabindex="-1" role="dialog" aria-labelledby="blocklayoutmodal"
         aria-hidden="true">
        <div class="modal-dialog modal-xl">

            <form method="get" action="{{route('voyager.page-blocks.create-layout')}}" class="modal-content">
                @csrf
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h2 class="modal-title" id="mainModalLabel">Criando Template</h2>

                </div>
                <div class="modal-body">
                    <input name="block_id" id="layout_block_id" type="hidden">
                    <input name="page_id" id="layout_page_id" type="hidden">
                    <div class="form-group">
                        <label>Nome do template</label>
                        <input class="form-control" name="layout_name" type="text">
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="submit" class="btn btn-primary save-configs"
                           style="background:#0f447a;color:White;"
                           value="Salvar">
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="blockeditmodal" tabindex="-1" role="dialog" aria-labelledby="blockeditmodal"
         aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">


            </div>
        </div>
    </div>

    <div class="modal fade" id="blocksettingsmodal" tabindex="-1" role="dialog" aria-labelledby="blocksettingsmodal"
         aria-hidden="true">
        <div class="modal-dialog modal-md configs">
            <div class="modal-content ">
                <div class="modal-header">
                    <button type="button" class="close" onclick="dont_save($('.modal-config'))" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h2 class="modal-title" id="mainModalLabel">Editando</h2>
                </div>
                <div class="modal-body modal-config">


                </div>


                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary dont-save"
                            onclick="dont_save($('.modal-config'))">Fechar
                    </button>
                    <input type="button" class="btn btn-primary save-configs"
                           style="background:#0f447a;color:White;" onclick="close_modal($('.modal-config'))"
                           value="Salvar">
                </div>
            </div>
        </div>
    </div>
@stop

@section('javascript')
    <script>

        var largest = 0;
        $('.panel-title').each(function () {
            if ($(this).width() > largest) {
                largest = $(this).width();
            }
        }).promise().done(function () {
            $('.panel-title').each(function () {
                $(this).width(largest)
            });
            console.log('done')
        })


        $('.loader-icon').hide()

        function load_block_info(id, t) {
            $.ajax({
                url: $(t).data('route'),
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    blockId: id
                },
                success: function (data) {
                    ////console.log(data)
                },
                error: function (data) {
                    //console.log(data)
                }
            });
        }


        function layoutmodal(t, id, type) {
            $('#blocklayoutmodal').modal('show');
            if (type == "block") {
                $('#layout_block_id').val(id)
                $('#layout_page_id').val("")
            }
            if (type == "page") {
                if (selected_blocks.length == 0) {
                    $('#layout_block_id').val("")
                    $('#layout_page_id').val(id)
                } else {
                    $('#layout_page_id').val("")
                }
            }

        }

        function show_tab_options(id) {
            $('.tab-more-options').each(function () {
                var $this = $(this);
                if ($this.data('tab-config-id') == id) {
                    $this.css('visibility', 'visible')
                    if ($this.css('max-height') == "300px") {
                        $this.css('max-height', '0px')
                    } else {
                        $this.css('max-height', '300px')
                    }
                } else {
                    $this.css('visibility', 'visible')
                    $this.css('max-height', '0px')
                }
            })
        }


        var current_parent = null;

        function show_options(id) {
            $('.form-options').each(function () {
                var $this = $(this);
                if ($this.data('id') == id) {
                    if ($this.css('width') == "320px") {
                        $this.animate({
                            width: '0px'
                        });
                    } else {
                        $this.animate({
                            width: '320px'
                        });
                    }
                }
            })
        }

        function editblock(t, block_id) {
            $savedclass = $(t).find('i').attr('class')
            $('.loader-icon').show()
            $(t).find('i').attr('class', 'fas fa-spinner fa-spin')
            $.ajax({
                url: '{{route('voyager.page-blocks.block-form')}}',
                data: {
                    block: block_id,
                },
                error: function (data) {
                    console.log(data)
                    toastr.error("Erro desconhecido");
                    $(t).find('i').attr('class', $savedclass)
                    $('.loader-icon').hide()
                },
                success: function (data) {
                    // console.log(data)
                    $('#blockeditmodal').find('.modal-content').first().html(data)
                    $("#blockeditmodal").modal()
                    $(t).find('i').attr('class', $savedclass)
                    $('.loader-icon').hide()
                }
            })
        }

        var active_parent = null;

        function close_modal(modal) {
            modal.children('.item-child').each(function () {
                active_parent.append(this);
            })
            $('#blocksettingsmodal').modal('hide');

        }

        var active_ajax = null;
        var active_ajax_obj = null;

        function show_settings_modal(t, $input) {
            active_parent = t.find('.has-child');
            t.find('.item-child').each(function () {
                var $this = $(this)
                $('.modal-config').append(this);
                if ($this.find('input').first().hasClass('awesome-colorpicker')) {
                    $this.find('input').first().spectrum('destroy')
                    $this.find('input').first().spectrum({
                        appendTo: '.modal-config',
                        showInput: true,
                        preferredFormat: 'rgb',
                        showAlpha: true,
                        showInitial: true,
                        showPalette: true,
                        palette: colors,
                    })
                }
                var $input = $this.find('input').first()
                if ($input == null || $input == undefined || $input.length == 0) {
                    $input = $this.find('select').first()
                }
                $this.closest('.item-child').attr('last-val', $input.val())
            })
            $('#blocksettingsmodal').modal('show');
        }


        function dont_save(modal) {
            $('#blockeditmodal').modal('show')
            $('#blocksettingsmodal').modal('hide');
            $('.modal-config .item-child').each(function () {
                $(this).find('select').each(function () {
                    var $this = $(this)
                    if ($this.is('select')) {
                        $this.val($this.parent().attr('last-val')).change();
                    }
                })
                $(this).find('input').each(function () {
                    var $this = $(this)
                    if ($this.hasClass('awesome-colorpicker')) {
                        $this.spectrum('set', $this.val())
                    }
                    $this.val($this.closest('.item-child').attr('last-val'));
                })
                active_parent.append(this);
            })
        }

        function showmodal(t, $input) {
            ////console.log(t);
            $savedclass = $(t).find('i').attr('class')
            $('.loader-icon').show()
            $(t).find('i').attr('class', 'fas fa-spinner fa-spin')
            jQuery.ajax({
                url: t.attr('data-route'),
                method: 'post',
                data: {
                    _token: '{{csrf_token()}}',
                    block: $input
                },
                success: function (data) {
                    $(t).find('i').attr('class', $savedclass)
                    $('#mainModal').modal('show')
                    $('#mainModal .modal-content').empty();
                    $('#mainModal .modal-title').html("Editing Block " + $input)
                    $('#mainModal .modal-content').append(data)
                    $('.loader-icon').hide()

                },
                error: function (data) {
                    $('.loader-icon').hide()
                }
            })
        }

        $(function () {


            $('#blockeditmodal').on('hide.bs.modal', function () {
                $('body').css('overflow', 'auto')
            })

            $('#blockeditmodal').on('show.bs.modal', function () {
                $('body').css('overflow', 'hidden')
            })

            $('#blocksettingsmodal').on('show.bs.modal', function () {
                $('.modal-backdrop.fade.in').css('z-index', '100001')
            })

            $('#blocksettingsmodal').on('hidden.bs.modal', function () {
                $('body').css('overflow', 'hidden')
                $('.modal-backdrop.fade.in').css('z-index', '100000')
                $('#blockeditmodal').css('overflow', 'auto')


                $('.modal-config .item-child').each(function () {
                    $(this).find('select').each(function () {
                        var $this = $(this)
                        if ($this.is('select')) {
                            $this.val($this.parent().attr('last-val')).change();
                        }
                    })
                    $(this).find('input').each(function () {
                        var $this = $(this)
                        if ($this.hasClass('awesome-colorpicker')) {
                            $this.spectrum('set', $this.val())
                        }
                        $this.val($this.closest('.item-child').attr('last-val'));
                    })
                    active_parent.append(this);
                })
            });
        });
    </script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swal-forms@0.5.0/swal-forms.min.js"></script>
    <script>

        $('document').ready(function () {

            /**
             * Enable CHECKBOX toggle component
             */
            $('.toggleswitch').bootstrapToggle();

            /**
             * Make TINYMCE a 'lil smaller, height-wise
             */
            setTimeout(function () {
                $('.mce-tinymce').each(function () {
                    $(this).find('iframe').css({'height': 250, 'min-height': 250});
                });
            }, 1000);

            /**
             * IMAGE fields types
             */
            $('input[type=file]').each(function () {
                $(this).closest('.form-group').addClass('vpb-image-group');
            });

            /**
             * MULTIPLE-IMAGES Delete function
             */
            /*$(".remove-multi-image").on('click', function(e){
                e.preventDefault();
                var result = confirm("Are you sure you want to delete this image?");
                if (result) {
                    $.post('{{-- route('voyager.page-blocks.delete-multiple-image') --}}', {
                        field: $(this).data('id'),
                        file_name: $(this).data('file-name'),
                        _token: '{{ csrf_token() }}'
                    });
                }
            });*/

            /**
             * Confirm DELETE block
             */

            function dialog(message, yesCallback, noCallback) {
                $('.title').html(message);
                var dialog = $('#modal_dialog').dialog();

                $('#btnYes').click(function () {
                    dialog.dialog('close');
                    yesCallback();
                });
                $('#btnNo').click(function () {
                    dialog.dialog('close');
                    noCallback();
                });
            }


            $("[data-delete-block-btn]").on('click', function (e) {
                e.preventDefault();
                //var result = dialog("Are you sure you want to duplicate this block?", function(){}, function(){});
                Swal.fire({
                    title: "Você tem certeza?",
                    text: "Apos remover este bloco, não sera possivel recupera-lo!",
                    type: "warning",
                    buttons: true,
                    confirmButtonText: 'Sim',
                    cancelButtonColor: '#d33',
                    showCancelButton: true,
                    dangerMode: true,
                })
                    .then((willDelete) => {
                        if (willDelete.value) {
                            Swal.fire("Bloco removido com sucesso!\nA página será atualizada, aguarde.", {
                                type: "success",
                                buttons: false
                            });
                            $(this).closest('form').submit()
                        } else {
                        }
                    });
            });

            $("[data-duplicate-block-btn]").on('click', function (e) {
                //Swal.fire("teste");
                e.preventDefault();
                //var result = dialog("Are you sure you want to duplicate this block?", function(){}, function(){});
                Swal.fire({
                    title: "Você tem certeza?",
                    type: "warning",
                    buttons: true,
                    confirmButtonText: 'Sim',
                    confirmButtonColor: "#d33",
                    cancelButtonColor: '#a3a3a3',
                    showCancelButton: true,
                    dangerMode: true,
                })
                    .then((willDelete) => {
                        if (willDelete.value) {
                            Swal.fire({
                                title: 'Bloco duplicado com sucesso!',
                                text: 'A página será atualizada, aguarde.',
                                type: "success",
                                showConfirmButton: false,
                                allowOutsideClick: false
                            });
                            $(this).closest('form').submit()
                        } else {
                        }
                    });
                //if (result) $(this).closest('form').submit();
            });

            $("[data-save-block-btn]").on('click', function (e) {
                //Swal.fire("teste");
                e.preventDefault();
                //var result = dialog("Are you sure you want to duplicate this block?", function(){}, function(){});
                Swal.fire("Bloco salvo com sucesso!\nA página será atualizada, aguarde.", {
                    type: "success",
                    buttons: false
                });
                $(this).closest('form').submit()
                //if (result) $(this).closest('form').submit();
            });

            /**
             * COLLAPSE blocks
             */
            // Init
            $(document).on('click', '.panel-heading [data-toggle="block-collapse"]', function (e) {
                e.preventDefault();

                $(this).parents('.panel').toggleClass('panel-collapsed');
                $(this).parents('.panel').find('.panel-body').slideToggle();

                var minimized = 0;
                if ($(this).parents('.panel').hasClass('panel-collapsed')) {
                    minimized = 1;
                }

                $.post('{{ route('voyager.page-blocks.minimize') }}', {
                    id: $(this).parents('li').data('id'),
                    is_minimized: minimized,
                    _token: '{{ csrf_token() }}'
                });
            });

            /**
             * ORDER blocks
             */
                // Init drag 'n drop
                // $('.dd').nestable({handleClass: 'order-handle', maxDepth: 1});
                //
                // // Close all panels when dragging
                // $('.order-handle').on('mousedown', function () {
                //     $('.dd').addClass('dd-dragging');
                // });

                // Fire request when drag complete
                    {{--$('.dd').on('change', function (e) {--}}
                    {{--    // Only when it's a result of drag and drop--}}
                    {{--    // -- Otherwise this triggers on every form change within .dd--}}
                    {{--    if ($('.dd').hasClass('dd-dragging')) {--}}
                    {{--        // And reopen panels once drag has finished--}}
                    {{--        $('.dd').removeClass('dd-dragging');--}}

                    {{--        // Post the request--}}

                    {{--    }--}}
                    {{--});--}}

            var selected_blocks = [];
            $('.draggable-blocks2 div').first().click(function (e) {
                if (e.shiftKey) {
                    $(this).data('id')
                    //$(this).find('.panel-heading').first().css('background', '#d3eeff')
                }
            })



            $('.draggable-blocks2').sortable({
                update: function (event, ui) {
                    update_order();

                },
            })

        });
        var toast = null;
        var ajaxes = 0;
        var current_ajax = null;

        function update_order() {
            var ids = [];

            $('.draggable-blocks2').children('div').each(function () {
                ids.push($(this).data('id'));
            })
            ajaxes += 1;
            if (toast == null) {
                toast = toastr.info("Existem requisições sendo executadas, por favor aguarde", "Page Blocks", {
                    timeOut: 0,
                    extendedTimeOut: 0
                });
            }
            if (current_ajax != null) {
                current_ajax.abort()
            }


            current_ajax = $.ajax({
                url: '{{ route('voyager.page-blocks.sort-custom') }}',
                data: {
                    ids: JSON.stringify(ids)
                },
                success: function (data) {
                    toastr.success("Ordem dos blocos salva!");
                    current_ajax = null;
                    toast.remove()
                },
                error: function (data) {
                    if (data.status != 0) {
                        console.error(data);
                        toastr.error('Erro desconhecido')
                        current_ajax = null;
                        toast.remove()
                    }
                }
            })
        }

        $('.remove-template').click(function () {
            var $this = $(this);
            var $thisId = $this.data('id')
            var mainActions = $('.import-card[data-id="' + $thisId + '"] .action');
            var main = $('.import-card[data-id="' + $thisId + '"]');
            var mainEl = $('.import-card[data-id="' + $thisId + '"] .delete-menu');
            var loader = '<i class="fas fa-spinner fa-pulse" style="color:black;opacity:0.5;"></i>';

            Swal.fire({
                title: "Deseja mesmo remover esse template ?",
                text: "Essa ação não poderá ser desfeita.",
                type: "warning",
                confirmButtonText: 'Sim',
                confirmButtonColor: "#d33",
                cancelButtonColor: '#a3a3a3',
                showCancelButton: true,
                buttons: [
                    'Cancelar',
                    'Confirmar'
                ],
                dangerMode: true,
            }).then(function (isConfirm) {
                if (isConfirm.value) {

                    $this.hide();
                    mainActions.hide();
                    mainEl.append(loader)
                    $.ajax({
                        url: '{{route('voyager.page-blocks.remove-template')}}',
                        data: {
                            id: $thisId
                        },
                        success: function (data) {
                            toastr.success("Template removido com sucesso!")
                            main.remove();
                            $(loader).remove();
                            update_order()
                        },
                        error: function (data) {
                            $this.show()
                            mainActions.show();
                            $(mainEl).find('.fa-spinner').remove();
                            console.error(data)
                        }
                    })
                }
            })


        })


        var editingEl = null;
        var editingId = null;
        $('.edit-template-name').click(function () {
            var $this = $(this);
            var $thisId = $this.data('id');
            var mainEl = $('.import-card[data-id="' + $thisId + '"] .template-name');
            var old_input = mainEl.html()
            if (editingId != $thisId) {
                if (editingEl != null) {
                    if (editingEl.data('last') != "") {
                        editingEl.html(editingEl.data('last'))
                        editingEl.removeAttr('data-last')
                    }
                }

                editingEl = mainEl;
                editingId = $thisId;

                var input = '<input type="text" value="' + old_input + '">';
                mainEl.attr('data-last', old_input)
                mainEl.empty();
                mainEl.append(input);
                $('.template-name').find('input').on('keypress', function (e) {
                    if (e.which === 13) {
                        var val = $(this).val();
                        mainEl.html(val)
                        editingEl = null;
                        editingId = null;
                        if (val != mainEl.data('last')) {
                            $.ajax({
                                url: '{{route('voyager.page-blocks.edit-template-name')}}',
                                data: {
                                    id: $thisId,
                                    name: val
                                },
                                success: function (data) {
                                    toastr.success("Nome atualizado com sucesso!")

                                },
                                error: function (data) {
                                    console.error(data)
                                }
                            })
                        }
                        mainEl.removeAttr('data-last')
                    }
                })
            }
        })
        $('.duplicate-item').attr('disabled', 'true')
        var selected_blocks = [];
        $(document).on('click', '.draggable-blocks2 .block', function (e) {
            var id = $(this).first().data('id');
            if (e.shiftKey) {
                var $this = $(this)
                if (selected_blocks.includes(id)) {
                    position = selected_blocks.indexOf(id);
                    if (~position) selected_blocks.splice(position, 1);
                    $this.find('.panel-heading').css('background', $this.find('.panel-heading').data('bg'))
                } else {
                    $this.find('.panel-heading').attr('data-bg', $this.css('background-color'))
                    $this.find('.panel-heading').css('background', '#d6e5ff')

                    selected_blocks.push(id)

                }
                if (selected_blocks.length > 0) {
                    $('.duplicate-item').removeAttr('disabled')
                    $('.dupe-to').animate({width: '230px'}, 100)
                    $('#duplicate-blocks-ids').val(JSON.stringify(selected_blocks))
                    $('.delete-selected').show()
                } else {
                    $('.duplicate-item').attr('disabled', 'true')
                    $('.dupe-to').animate({width: '0px'}, 100)
                    $('.delete-selected').hide()
                }
                $('#layout_block_id').val(JSON.stringify(selected_blocks))
            }
        })


        $('.delete-selected').hide()

        $(function () {

            $('.delete-selected').click(function () {
                Swal.fire({
                    title: "ATENÇÃO!",
                    text: "Você está deletando os blocos selecionados, digite 'DELETAR' para confirmar",
                    input: 'text',
                    type: 'warning',
                    showCancelButton: true
                }).then((result) => {
                    if (result.value) {
                        if (result.value == "DELETAR") {
                            toastr.warning('Deletando os blocos, por favor aguarde.')
                            $.ajax({
                                url: '{{route('voyager.page-blocks.delete-blocks')}}',
                                data: {
                                    _token: '{{csrf_token()}}',
                                    blocks: selected_blocks
                                },
                                method: 'POST',
                                success: function (data) {
                                    // console.log(data)
                                    toastr.success('Blocos deletados com sucesso.')
                                    $('.block').each(function () {
                                        if (selected_blocks.includes($(this).data('id'))) {
                                            $(this).remove();
                                        }
                                    })
                                    selected_blocks = [];
                                    $('.duplicate-item').attr('disabled', 'true')
                                    $('.dupe-to').animate({width: '0px'}, 100)
                                    $('.delete-selected').hide()
                                    update_order()
                                },
                                error: function (data) {
                                    console.error(data)
                                    toastr.error('Erro desconhecido.')
                                    selected_blocks = [];
                                    $('.duplicate-item').attr('disabled', 'true')
                                    $('.dupe-to').animate({width: '0px'}, 100)
                                    $('.delete-selected').hide()
                                }
                            })
                        } else {
                            Swal.fire({
                                title: "Ação cancelada por erro de digitação"
                            })
                        }
                    }
                });
            })
        })


        $('.select-all').click(function () {
            $('.block').each(function () {
                var $this = $(this)
                var id = $this.data('id');
                if (selected_blocks.includes(id)) {
                    // position = selected_blocks.indexOf(id);
                    // if (~position) selected_blocks.splice(position, 1);
                    // $this.find('.panel-heading').css('background', $this.find('.panel-heading').data('bg'))
                } else {
                    $this.find('.panel-heading').attr('data-bg', $this.css('background-color'))
                    $this.find('.panel-heading').css('background', '#d6e5ff')

                    selected_blocks.push(id)

                }

            })
            if (selected_blocks.length > 0) {
                $('.duplicate-item').removeAttr('disabled')
                $('.dupe-to').animate({width: '230px'}, 100)
                $('#duplicate-blocks-ids').val(JSON.stringify(selected_blocks))
                $('.delete-selected').show()
            } else {
                $('.duplicate-item').attr('disabled', 'true')
                $('.dupe-to').animate({width: '0px'}, 100)
                $('.delete-selected').hide()
            }
            $('#layout_block_id').val(JSON.stringify(selected_blocks))
            console.log(selected_blocks);
        })

        $('.load-template').click(function () {
            Swal.fire({
                title: "Importando template",
                text: 'Por favor, aguarde.',
                type: 'success',
                showConfirmButton: false,
                allowOutsideClick: false
            })
        })

        $(function () {
            $('.vo-pra-onde').on('change', function () {
                var getUrl = window.location;
                $('.vai-pra-la').attr('href', getUrl.protocol + "//" + getUrl.host + "/admin/page-blocks/" + $(this).val() + "/edit")
            })
        })


    </script>
    <script>
        $("select")
            .change(function () {
                var str = "";
                $("select option:selected").each(function () {
                    str += $(this).text() + " ";
                });
                //$( "div" ).text( str );
            })
            .trigger("change");

        $('.submit-new').click(function () {
            var form = $('.choose-block');
            // console.log($('.block-type-select-1').val().length);
            if ($('.block-type-select-1').val().length == 0) {
                //Swal.fire({text: 'PINTO'})
            } else {
                $('.submit-new').prepend('<i class="fas fa-spinner fa-spin spinner-loader-add" style="margin-right:8px;"></i>')
                var options = new FormData(form.get(0));
                var swals = Swal.fire({
                    title: 'Bloco adicionado!',
                    text: 'Por favor, aguarde.',
                    type: 'success',
                    allowOutsideClick: false,
                    showCloseButton: false,
                    showConfirmButton: false,
                    allowEscapeKey: false
                })
                Swal.showLoading();
                $.ajax({
                    url: form.attr('action'),
                    method: 'POST',
                    data: options,
                    processData: false,
                    contentType: false,
                    success: function (data) {
                        // console.log(data);
                        // $(data).hide()
                        swals.close()
                        $('.spinner-loader-add').remove()
                        Swal.hideLoading()
                        var new_item = $(data).slideUp();
                        $('.draggable-blocks2').append(new_item)
                        new_item.slideDown()
                        $('.draggable-blocks2').sortable("refresh");
                        $("html, body").animate({scrollTop: $(document).height()}, 600);
                        update_order()
                    },
                    error: function (error) {
                        $('.spinner-loader-add').remove()
                        swals.close()
                        Swal.hideLoading()
                        console.error(error)

                    }
                })
            }
        })
    </script>
@endsection