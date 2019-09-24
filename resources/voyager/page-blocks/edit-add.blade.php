@extends('voyager::master')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>

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

        .tab-pane {
            display: none;
        }

        .tab-pane.active {
            display: block;
        }
    </style>
@stop

@section('page_title', 'Edit Page Content')

@section('page_header')
    <h1 id="page-title"
        style="display: inline-block;font-size: 18px;height: 100px;line-height: 43px;margin-top: 3px;padding-top: 28px;    padding-left: 75px;    position: relative;margin-bottom: 0;font-weight: 700;margin-right: 20px;">
        <i class="voyager-file-text"
           style="font-size: 36px;    position: absolute;    top: 30px;    left: 25px;margin-right: 10px;"></i>
        Edit Page {{$page->title}}'s Contents
    </h1>
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
        @endphp
        <div class="page-content edit-add container-fluid">
            <div class="row">
                <div class="col-md-12" style="padding:15px;">
                    <div class="panel panel-bordered panel-primary">
                        <div class="panel-body" style="display:flex;align-items: center;padding:20px;">
                            <form role="form" action="{{ route('voyager.page-blocks.store', $page->id) }}" method="POST"
                                  style="display:flex;align-items: center;margin-right:30px;"
                                  enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="form-group" style="margin:0;margin-right:5px;">
                                    <select class="block-type-select-1 form-control" name="type" id="type">
                                        <option value="">Adicione um bloco</option>
                                        <optgroup label="Developer Tools">
                                            <option value="include">Developer Controller</option>
                                        </optgroup>

                                        @foreach ($templates as $path => $template)

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
                                        @endforeach

                                        <optgroup label="Blocks" style="font-size: 12px;margin-left:0px;">
                                        </optgroup>
                                    </select>
                                </div> <!-- /.form-group -->

                                <input type="hidden" name="page_id" value="{{ $page->id }}"/>
                                <button type="submit"
                                        style="margin:0;"
                                        class="btn btn-via btn-sm">{{ __('voyager::generic.add') }}</button>
                            </form>
                            <a class="btn btn-via" style="margin:0;margin-right:30px;"
                               href="{{route('voyager.page-blocks.main-settings')}}">Configurar Header/Footer</a>
                            @php
                                $blogPage = \Viaativa\Viaroot\Models\Page::where('slug','blog')->first();
                            @endphp
                            @if(isset($blogPage))
                                <a class="btn btn-via" style="margin:0;margin-right:30px;"
                                   href="{{route('voyager.page-blocks.edit',['pageBlock' => $blogPage->id])}}">Configurar
                                    Blog</a>
                            @endif
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
                        <div class="draggable-blocks row" style="margin:0;list-style: none;width: 100%;">

                            @foreach($pageBlocks as $key => $block)
                                @php
                                    $template = $block->template();
                                    $dataTypeContent = $block->data;
                                $extras = (array)json_decode($block->extra);
                                if(!isset($extras['small'] )) { $extras['small']  = 12; }
                                if(!isset($extras['medium'] )) { $extras['medium']  = 12; }
                                if(!isset($extras['large'] )) { $extras['large']  = 12; }
                                @endphp
                                <div
                                    class="col-sm-{{$extras['small'] }} col-md-{{$extras['medium']}} col-lg-{{$extras['large']}}"
                                    style="padding:0;margin-bottom:0px;" data-id="{{$block->id}}">
                                    @include('viaativa-voyager::page-blocks.partials.page-blocks-sorting')
                                </div>

                            @endforeach

                        </div>
                    </div> <!-- /.dd -->
                </div> <!-- /.col -->

            </div>
        </div> <!-- /.page-content -->
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

        var $sortableList = $('.draggable-blocks');
        var sortEventHandler = function (event, ui) {
            var listElements = $sortableList.children();
            var listValues = [];

            $(listElements).each(function (element) {
                listValues.push({id: $(this).data('id')});
            });

            $.ajax({
                url: '{{ route('voyager.page-blocks.sort-custom') }}',
                method: 'post',
                data: {
                    order: JSON.stringify(listValues),
                    _token: '{{ csrf_token() }}',
                },
                error: function (data) {
                    //console.log(data)
                },
                success: function (data) {
                    toastr.success("Block order saved");
                }
            })
        };

        $sortableList.sortable({
            stop: sortEventHandler,
        })

        @foreach ($templates as $path => $template)

            @if(array_key_exists("group", $template))
                $("optgroup[label=\"{{$template["group"]}}\"]").append('<option style="font-size: 14px;padding-left:4px;"   group="{{$template["group"]}}" value="template|{{ $path }}">{{ $template["name"] }}@if(app("VoyagerAuth")->user()->role_id == "3")  {{"{".$path."}"}}@endif</option>')
            @else
            @if(substr($template['template'],0,7) != "voyager")
                @if(array_key_exists("type", $template))
                    @if($template['type'] != "footer" and $template['type'] != "header")
                    $("optgroup[label=\"Blocks\"]").append('<option style="font-size: 14px;padding-left:4px;"  value="template|{{ $path }}">{{ $template["name"] }}@if(app("VoyagerAuth")->user()->role_id == "3")  {{"{".$path."}"}}@endif</option>')
                    @endif
                @else
                    $("optgroup[label=\"Blocks\"]").append('<option style="font-size: 14px;padding-left:4px;"  value="template|{{ $path }}">{{ $template["name"] }}@if(app("VoyagerAuth")->user()->role_id == "3")  {{"{".$path."}"}}@endif</option>')
                    @endif
                @endif
            @endif
        @endforeach
    </script>
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
    <script>

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
                swal({
                    title: "Você tem certeza?",
                    text: "Apos remover este bloco, não sera possivel recupera-lo!",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                    .then((willDelete) => {
                        if (willDelete) {
                            swal("Bloco removido com sucesso!\nA página será atualizada, aguarde.", {
                                icon: "success",
                                buttons: false
                            });
                            $(this).closest('form').submit()
                        } else {
                        }
                    });
            });

            $("[data-duplicate-block-btn]").on('click', function (e) {
                //swal("teste");
                e.preventDefault();
                //var result = dialog("Are you sure you want to duplicate this block?", function(){}, function(){});
                swal({
                    title: "Você tem certeza?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                    .then((willDelete) => {
                        if (willDelete) {
                            swal("Bloco duplicado com sucesso!\nA página será atualizada, aguarde.", {
                                icon: "success",
                                buttons: false
                            });
                            $(this).closest('form').submit()
                        } else {
                        }
                    });
                //if (result) $(this).closest('form').submit();
            });

            $("[data-save-block-btn]").on('click', function (e) {
                //swal("teste");
                e.preventDefault();
                //var result = dialog("Are you sure you want to duplicate this block?", function(){}, function(){});
                swal("Bloco salvo com sucesso!\nA página será atualizada, aguarde.", {
                    icon: "success",
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
            $('.dd').nestable({handleClass: 'order-handle', maxDepth: 1});

            // Close all panels when dragging
            $('.order-handle').on('mousedown', function () {
                $('.dd').addClass('dd-dragging');
            });

            // Fire request when drag complete
            $('.dd').on('change', function (e) {
                // Only when it's a result of drag and drop
                // -- Otherwise this triggers on every form change within .dd
                if ($('.dd').hasClass('dd-dragging')) {
                    // And reopen panels once drag has finished
                    $('.dd').removeClass('dd-dragging');

                    // Post the request
                    $.post('{{ route('voyager.page-blocks.sort') }}', {
                        order: JSON.stringify($('.dd').nestable('serialize')),
                        _token: '{{ csrf_token() }}'
                    }, function (data) {
                        toastr.success("Block order saved");
                    });
                }
            });
        });
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


    </script>
@endsection
