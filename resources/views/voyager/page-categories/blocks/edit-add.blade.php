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
    <div class="container-fluid" style="">
        <div style="display:flex;align-items: center;padding:10px 0px;padding-bottom: 0px;">
            <div>
                <i class="voyager-file-text"
                   style="font-size: 36px;margin-right: 10px;"></i>
            </div>
            <div>
                <h1 id="page-title" style="font-size: 18px;margin: 0">
                    Editando Categoria {{$page->name}}
                </h1>
            </div>
            <div style="margin-left:12px;">

                <a class="page-link" href="{{route('voyager.page-categories.edit',['id' => $page->id])}}" target="_blank">
                    <i class="fas fa-cog"></i>
                </a>
            </div>

        </div>
        <div>
{{--            <a class="page-link" href="{{url($page->breadcrumbs().$page->slug)}}" target="_blank">--}}
{{--                <i class="fas fa-external-link-alt" style="margin-right:3px;"></i>--}}
{{--                <span style="font-size:11px;">--}}
{{--                    {{url($page->breadcrumbs().$page->slug)}}--}}
{{--                </span>--}}
{{--            </a>--}}
        </div>
    </div>
    @include('voyager::multilingual.language-selector')
@stop

@section('content')
    <div class="loader-icon"
         style="position: fixed;z-index: 5000;left:0px;right:0px;top:0px;bottom:0px;background:rgba(0,0,0,0.24);display:flex;align-items: center;justify-content: center;">
        <i class="fas fa-cog fa-spin" style="font-size:64px;color:white;"></i>
    </div>
    @if($page->slug != "blog")
        @php $templates = config('page-blocks');
    $groups = [];
        $allPages = \Pvtl\VoyagerPages\Page::all();
        @endphp
        <div class="page-content edit-add container-fluid">
            <div class="row">
                <div class="col-md-12" style="padding:15px;">
                    <div class="panel panel-bordered panel-primary">
                        <div class="panel-body" style="display:flex;align-items: center;padding:20px;">
                            <form role="form" action="{{ route('voyager.page-categories.blocks.store', $page->id) }}"
                                  class="choose-block"
                                  method="POST"
                                  style="display:flex;align-items: center;margin-right:30px;"
                                  enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="form-group" style="margin:0;margin-right:5px;">
                                    {{--                                    <label for="type">{{__('voyager::generic.block')}}</label>--}}
                                    <select class="block-type-select-1 form-control" name="type" id="type"

                                    >
                                        <option value="">Adicione um bloco</option>
                                        {{--                                        <optgroup label="Developer Tools">--}}
                                        {{--                                            <option value="include">Developer Controller</option>--}}
                                        {{--                                        </optgroup>--}}
                                        @php
                                            $result = array();
                                            foreach ($templates as $key => $element) {
                                                if(!isset($element['group']))
                                                {
                                                $element['group'] = "";
                                                }
                                                $result[$element['group']][$key] = $element;
                                            }
                                        @endphp
                                        @foreach ($result as $groupkey => $group)
                                            {{--                                            @if(array_key_exists("group", $template))--}}
                                            {{--                                                @if(!in_array($template['group'],$groups))--}}
                                            {{--                                                    @php--}}
                                            {{--                                                        array_push($groups,$template['group'])--}}
                                            {{--                                                    @endphp--}}
                                            @php
                                                if(strlen($groupkey) == 0)
                                                {
                                                $groupkey = "Blocos";
                                                }
                                            @endphp
                                            <optgroup label="{{$groupkey}}"
                                                      group-name="{{$groupkey}}"
                                                      style="font-size: 12px;margin-left:0px;">
                                                @foreach($group as $key=>$template)
                                                    @if($template != null and substr($template['template'],0,7) != "voyager")
                                                        <option value="template|{{$key}}"
                                                                style="font-size: 14px;padding-left:4px;">{{$template['name']}}</option>
                                                    @endif
                                                @endforeach
                                            </optgroup>
                                            {{--                                                @endif--}}
                                            {{--                                            @endif--}}
                                        @endforeach
                                    </select>
                                </div> <!-- /.form-group -->

                                <input type="hidden" name="page_id" value="{{ $page->id }}"/>
                                <button type="button"
                                        style="margin:0;"
                                        class="btn btn-via btn-sm submit-new">{{ __('voyager::generic.add') }}</button>
                            </form>
                            <a class="btn btn-via" style="margin:0;margin-right:30px;"
                               href="{{route('voyager.page-blocks.main-settings')}}">Configurar Header/Footer</a>
                            <div style="display:flex;">
                                <a class="btn btn-via vai-pra-la" href="#" style="margin:0;margin-right:10px;"
                                >Ir para</a>
                                <select class="select2 vo-pra-onde" style="width:160px;min-width:160px;" name="page">
                                    @foreach($allPages as $k => $v)
                                        <option value="{{$v->id}}">{{$v->title}}</option>
                                    @endforeach
                                </select>

                            </div>
                            {{--                            <a class="btn btn-via" style="margin:0;margin-right:30px;"--}}
                            {{--                               href="{{route('voyager.page-blocks.main-settings')}}">Configurar Header/Footer</a>--}}
                            @php
                                $blogPage = \Viaativa\Viaroot\Models\Page::where('slug','blog')->first();
                            @endphp
                            @if(isset($blogPage))
                                <a class="btn btn-via" style="margin:0;margin-right:30px;"
                                   href="{{route('voyager.page-blocks.edit',['pageBlock' => $blogPage->id])}}">Configurar
                                    Blog</a>
                            @endif
                            <div style="margin-left:auto;display:flex;align-items: center;">

                                <a class="btn btn-via delete-selected"
                                   style="background: red !important;margin:0;margin-right:4px;"
                                   title="Deletar Selecionados"
                                        {{--                               href="{{route('voyager.page-blocks.save-page-template',['page' => $page->id])}}"--}}
                                ><i class="fas fa-trash"></i></a>

                                <a class="btn btn-via select-all"
                                   style="margin:0;margin-right:4px;"
                                   title="Selecionar Todos"
                                        {{--                               href="{{route('voyager.page-blocks.save-page-template',['page' => $page->id])}}"--}}
                                ><i class="fas fa-check-double"></i></a>
                                <a class="btn btn-via" style="margin:0;margin-right:4px;"
                                   title="Salvar Template"
                                   {{--                               href="{{route('voyager.page-blocks.save-page-template',['page' => $page->id])}}"--}}
                                   onclick="layoutmodal($(this),{{$page->id}},'page')"
                                ><i class="fas fa-save"></i></a>

                                <a class="btn btn-via" style="margin:0;margin-right:4px;"
                                   data-toggle="modal"
                                   title="Importar Template"
                                   data-target="#blockloadlayoutmodal"><i class="fas fa-level-down-alt"></i></a>


                                <form method="POST" action="{{ route('voyager.page-blocks.duplicate-to')}}"
                                      class="form-options"
                                      style="display:inline-flex;margin-right:12px;align-items: center">
                                    {{ method_field("POST") }}
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" id="duplicate-blocks-ids" name="id" value="">

                                    <span class="btn-group-xs">
                                <button
                                        disabled
                                        data-duplicate-block-btn
                                        type="submit"
                                        title="Duplicar"
                                        style="margin-left:0px;float:right;padding: 6px 15px;margin-right:12px;font-size: 14px !important;"
                                        class="btn btn-via duplicate-item"
                                ><i class="fas fa-copy" style="font-size:14px !important;"></i></button></span>
                                    <div class="dupe-to" style="width:0px;overflow: hidden;">
                                        <select class="select2" style="min-width:160px;" name="target_page">
                                            @foreach($allPages as $k => $v)

                                                <option value="{{$v->id}}">{{$v->title}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-lg-12" style="padding:15px;">
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div style="margin-bottom: 10px;overflow: auto;height: auto;">
                        <div class="draggable-blocks2 row"
                             style="margin:0;list-style: none;width: 100%;margin-left:-6px;margin-right:-6px;">
                            @php
                                $blocksSorted = $pageBlocks->sortBy('order');
                                $arrItems = $blocksSorted->toArray();
                                $allOrders = collect([]);
                                for($i=1;$i<=sizeof($blocksSorted)+1;$i++)
                                {
                                $desired_object = $blocksSorted->filter(function($item) use ($i) {
                                    return $item->order == $i;
                                    })->first();
                                    if(!$desired_object == null)
                                    {
                                    $allOrders->push($desired_object);
                                    } else
                                    {
                                    $allOrders->push("page");
                                    }
                                }
                            @endphp
                            @foreach($allOrders as $key => $block)

                                @if($block == "page")
                                    <div class="col-sm-12 col-md-12 col-lg-12 block"
                                         style="padding:0px 6px;margin-bottom:0px;">
                                        <div class="" id="block-id--1" tabs="">
                                            <div class="panel panel-bordered panel-info"
                                                 style="border-radius: 5px;overflow: hidden; ">
                                                <div class="panel-heading" style="background:#2687e9;">
                                                    <div style="width:100%;height:3px;background:#ffffff;"></div>
                                                    <div style="display:inline-flex;align-items: center;">
                                                        <h3 class="panel-title" style="white-space: nowrap;">
                                                            Página
                                                        </h3>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    @include('viaativa-voyager::page-blocks.partials.page-blocks-sorting')
                                @endif
                            @endforeach


                        </div>
                    </div> <!-- /.dd -->
                </div> <!-- /.col -->

            </div>
        </div> <!-- /.page-content -->
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
                                       href="{{route('voyager.page-categories.blocks.add-template',['page' => $page->id,'template' => $layoutTemplate->id])}}">
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
    @else
        @php $templates = config('page-blocks');
    $groups = [];
        @endphp
        <div class="page-content edit-add container-fluid">
            <div class="row">
                <div class="col-md-3 col-lg-2">
                    <div class="panel panel-bordered panel-primary">
                        <div class="panel-heading" style="background: #313942;">
                            <h3 class="panel-title">{{__('voyager::generic.add-block')}}</h3>
                            <div class="panel-actions">
                                <a class="panel-collapse-icon voyager-angle-down" data-toggle="block-collapse"
                                   aria-hidden="true"></a>
                            </div> <!-- /.panel-actions -->
                        </div> <!-- /.panel-heading -->
                        <div class="panel-body">
                            <form role="form" action="{{ route('voyager.page-blocks.store', $page->id) }}" method="POST"
                                  enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <label for="type">{{__('voyager::generic.block')}}</label>
                                    <select class="block-type-select-1 form-control" name="type" id="type">
                                        <option value="">{{__('voyager::generic.select-block')}}</option>
                                        {{--                                    <optgroup label="Developer Tools">--}}
                                        {{--                                        <option value="include">Developer Controller</option>--}}
                                        {{--                                    </optgroup>--}}

                                        @foreach ($templates as $path => $template)
                                            @if(array_key_exists('type',$template))
                                                @if($template['type'] == "blog")
                                                    @if(array_key_exists("group", $template))
                                                        @if(!in_array($template['group'],$groups))
                                                            @php
                                                                array_push($groups,$template['group'])
                                                            @endphp
                                                            <optgroup label="{{$template['group']}}"
                                                                      group-name="{{$template['group']}}"
                                                                      style="font-size: 12px;margin-left:0px;">
                                                            </optgroup>
                                                            @endif
                                                            @endif
                                                            @endif
                                                            @endif
                                                            @endforeach
                                                            </optgroup>
                                    </select>
                                </div> <!-- /.form-group -->

                                <input type="hidden" name="page_id" value="{{ $page->id }}"/>
                                <button type="submit"
                                        class="btn btn-via btn-sm">{{ __('voyager::generic.add') }}</button>
                            </form>
                        </div> <!-- /.panel-body -->
                    </div> <!-- /.panel -->

                    <div class="panel panel-bordered panel-primary">
                        <div class="panel-heading" style="background: #313942;">
                            <h3 class="panel-title">Header/Footer</h3>
                            <div class="panel-actions">
                                <a class="btn btn-via" href="{{route('voyager.page-blocks.main-settings')}}">Ir</a>
                            </div> <!-- /.panel-actions -->
                        </div> <!-- /.panel-heading -->
                    </div>
                </div> <!-- /.col -->

                <div class="col-md-9 col-lg-10">
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div style="margin-bottom: 10px;overflow: auto;height: auto;">
                        <div class="dd">
                            <ol class="dd-list">

                                @foreach($pageBlocks as $block)
                                    @php
                                        $template = $block->template();
                                        $dataTypeContent = $block->data;
                                    @endphp

                                    @if ($block->type === 'template')
                                        @include('page-blocks')
                                    @else
                                        @include('voyager::page-blocks.partials.include')

                                    @endif
                                @endforeach
                            </ol> <!-- /.dd-list -->
                        </div>
                    </div> <!-- /.dd -->
                </div> <!-- /.col -->
            </div> <!-- /.row -->
        </div> <!-- /.page-content -->




        <!-- Modal -->




        <script>

            @foreach ($templates as $path => $template)

            @if(array_key_exists('type',$template))
            @if($template['type'] == "blog")
            @if(array_key_exists("group", $template))
            $("optgroup[label=\"{{$template["group"]}}\"]").append('<option style="font-size: 14px;padding-left:4px;"   group="{{$template["group"]}}" value="template|{{ $path }}">{{ $template["name"] }}@if(app("VoyagerAuth")->user()->role_id == "3")  {{"{".$path."}"}}@endif</option>')
            @else
            $("optgroup[label=\"Blocks\"]").append('<option style="font-size: 14px;padding-left:4px;"  value="template|{{ $path }}">{{ $template["name"] }}@if(app("VoyagerAuth")->user()->role_id == "3")  {{"{".$path."}"}}@endif</option>')
            @endif
            @endif
            @endif
            @endforeach
        </script>
    @endif
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


            var toast = null;
            var ajaxes = 0;
            var current_ajax = null;
            $('.draggable-blocks2').sortable({
                update: function (event, ui) {
                    var ids = [];

                    $(this).children('div').each(function () {
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

                },
            })

        });


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
            if(id != undefined) {
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
                if(id != undefined) {
                    if (selected_blocks.includes(id)) {
                        // position = selected_blocks.indexOf(id);
                        // if (~position) selected_blocks.splice(position, 1);
                        // $this.find('.panel-heading').css('background', $this.find('.panel-heading').data('bg'))
                    } else {
                        $this.find('.panel-heading').attr('data-bg', $this.css('background-color'))
                        $this.find('.panel-heading').css('background', '#d6e5ff')

                        selected_blocks.push(id)

                    }
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
