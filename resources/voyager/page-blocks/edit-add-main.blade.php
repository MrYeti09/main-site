@extends('voyager::master')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
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

        .item-cog-config:hover {
            cursor: pointer;
            color: #8aacff;
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
            @foreach($pageBlocks as $key => $pageBlock)
                @php
                    $template = $templates[$pageBlock->path];
                @endphp
                @if(array_key_exists('type',$template))
                    @if($template['type'] == "header" or $template['type'] == "footer")
                        <div class="">
                            <div class="panel panel-bordered panel-primary">
                                <div class="panel-heading" style="@if(isset($template['color']))background-color: {{$template['color']}};@else background-color:#303841; @endif">
                                    <h3 class="panel-title">{{ucfirst($template['type']) }}</h3>
                                    <div class="panel-actions">
                                    </div>

                                    @if(array_key_exists('tabs',$template))
                                        <div id="tabs" style="flex-wrap: wrap;@if(isset($template['color']))background-color: {{$template['color']}};@else background-color:#57c7d4; @endif ;filter: brightness(90%);width:100%;display:inline-flex;position:relative;">
                                            @foreach($template['tabs'] as $key => $tab)
                                                <div class="tab-button tooltip-controller" style="border: 1px solid rgba(0,0,0,0.07);margin-right:0px;border-right:0px;margin-left:11px;flex-direction: row;justify-content: center;display:flex;align-items:center;font-size:17px;white-space:nowrap;font-weight: 800;"
                                                     tab-btn-id="{{$key}}_{{$pageBlock->id}}" contained-tab="{{$key}}"
                                                     block_id="{{$pageBlock->id}}">
                                                    {{$tab['name']}}
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                                <div class="panel-body">
                                    <form role="form" action="{{ route('voyager.page-blocks.update', $pageBlock->id) }}" method="POST"
                                          enctype="multipart/form-data">
                                        {{ method_field("PUT") }}
                                        {{ csrf_field() }}
                                        <div class="row">
                                            @foreach($template['fields'] as $field_key => $block)
                                                @include('viaativa-voyager::page-blocks.partials.page-blocks-sorting')
{{--                                                @php--}}
{{--                                                    if(array_key_exists('options',$field))--}}
{{--                                                    {--}}
{{--                                                    $opt = (object)$field;--}}
{{--                                                    } else {--}}
{{--                                                    $opt = null;--}}
{{--                                                    }--}}
{{--                                                @endphp--}}
{{--                                                @if($field['partial'] == "break")--}}
{{--                                                    <div class="col-md-12 input-tab" @if(array_key_exists('tab',$field)) tab-id="{{$field['tab']}}_{{$pageBlock->id}}" @endif style="height:1px;background: rgba(43,43,43,0.23);margin-bottom:12px;"><label>{{$field['display_name']}}</label></div>--}}
{{--                                                @else--}}
{{--                                                    <div style=" @if($field['partial'] == "voyager::formfields.icon") max-height:61px; @endif @if(array_key_exists('width',$field)) @if($field['width'] == 'none') visibility:hidden;position: absolute;width: 0px;height:0px; @endif @endif" class="@if(array_key_exists('width',$field)) {{$field['width']}} @else col-md-4 @endif " @if(array_key_exists('tab',$field)) tab-id="{{$field['tab']}}_{{$pageBlock->id}}" @endif>--}}
{{--                                                        <label style="width: 100%;display:flex;flex-direction: column">--}}
{{--                                                            {{$field['display_name']}}</label>--}}
{{--                                                        <div style="display: flex;">--}}
{{--                                                            @include($field['partial'],['row' => (object)$field,'options' => (object)$field,"dataTypeContent" => $pageBlock->data,"block" => $pageBlock])--}}
{{--                                                            @php--}}
{{--                                                                $row = (object)$field;--}}

{{--                                                            @endphp--}}
{{--                                                            @if(property_exists($row,'child'))--}}
{{--                                                                <div style="position: relative;display:flex;align-items: center;padding-left:5px;">--}}
{{--                                                                    <div data-route="{{route('voyager.page-blocks.settings-modal',['blockid' => $pageBlock->id,'id' => $key])}}"--}}
{{--                                                                         onclick="show_settings_modal($(this),'{{$row->display_name}}')">--}}
{{--                                                                        @if($row->partial == "voyager::formfields.hidden")--}}
{{--                                                                            <div class="btn btn-primary" style="display:flex;margin-top:0px;margin-bottom:30px;">--}}
{{--                                                                                Configurar {{ $row->display_name }}--}}
{{--                                                                                @endif--}}
{{--                                                                                <i class="fa fa-cog @if($row->partial != "voyager::formfields.hidden") item-cog-config @endif" style="@if($row->partial == "voyager::formfields.hidden") margin-left:8px; @endif font-size: 22px"></i>--}}
{{--                                                                                @if($row->partial == "voyager::formfields.hidden")--}}
{{--                                                                            </div>--}}
{{--                                                                        @endif--}}
{{--                                                                        <div class="has-child" data-id="{{$key}}"--}}
{{--                                                                             style="position: absolute;width:0px;height:0px;visibility: hidden;">--}}
{{--                                                                            @foreach($row->child as $child_key => $row)--}}
{{--                                                                                @php--}}
{{--                                                                                    $row = (object)$row;--}}

{{--                                                                                @endphp--}}
{{--                                                                                <div class="item-child">--}}
{{--                                                                                    <label>{{ $row->display_name }}</label>--}}
{{--                                                                                    @include($row->partial,['child' => true,"options" => $row,"dataTypeContent" => $pageBlock->data])--}}
{{--                                                                                </div>--}}
{{--                                                                            @endforeach--}}
{{--                                                                        </div>--}}
{{--                                                                    </div>--}}
{{--                                                                </div>--}}

{{--                                                            @endif--}}
{{--                                                        </div>--}}

{{--                                                    </div>--}}
{{--                                                @endif--}}

                                            @endforeach
                                        </div>
                                        <div style="visibility: hidden;position: absolute;height: 0px;">
                                            <div class="col-mg-6 col-lg-4">
                                                <div class="form-group">
                                                    <label for="cache_ttl">Cache Time</label>
                                                    <select name="cache_ttl" id="cache_ttl" class="form-control">
                                                        <option value="0" {{ $pageBlock->cache_ttl === 0 ? 'selected="selected"' : '' }}>
                                                            None / Off
                                                        </option>
                                                        <option value="5" {{ $pageBlock->cache_ttl === 5 ? 'selected="selected"' : '' }}>
                                                            5 mins
                                                        </option>
                                                        <option value="30" {{ $pageBlock->cache_ttl === 30 ? 'selected="selected"' : '' }}>
                                                            30 mins
                                                        </option>
                                                        <option value="60" {{ $pageBlock->cache_ttl === 60 ? 'selected="selected"' : '' }}>
                                                            1 Hour
                                                        </option>
                                                        <option value="240" {{ $pageBlock->cache_ttl === 240 ? 'selected="selected"' : '' }}>
                                                            4 Hours
                                                        </option>
                                                        <option value="1440" {{ $pageBlock->cache_ttl === 1440 ? 'selected="selected"' : '' }}>
                                                            1 Day
                                                        </option>
                                                        <option value="10080" {{ $pageBlock->cache_ttl === 10080 ? 'selected="selected"' : '' }}>
                                                            7 Days
                                                        </option>
                                                    </select>
                                                </div> <!-- /.form-group -->
                                            </div> <!-- /.col -->

                                            <div class="col-mg-6 col-lg-8">
                                                <label>Options</label>

                                                <div class="row">
                                                    <div class="col-md-6 col-lg-5">
                                                        <div class="form-group">
                                                            <input
                                                                    type="checkbox"
                                                                    name="is_hidden"
                                                                    id="is_hidden"
                                                                    data-name="is_hidden"
                                                                    class="toggleswitch"
                                                                    value="1"
                                                                    data-on="Yes"
                                                                    {{ $pageBlock->is_hidden ? 'checked="checked"' : '' }}
                                                                    data-off="No"
                                                            />
                                                            <label for="is_hidden"> &nbsp;Hide Block</label>

                                                        </div> <!-- /.form-group -->
                                                    </div> <!-- /.col -->

                                                    <div class="col-md-6 col-lg-5">
                                                        <div class="form-group">
                                                            <input
                                                                    type="checkbox"
                                                                    name="is_delete_denied"
                                                                    id="is_delete_denied"
                                                                    data-name="is_delete_denied"
                                                                    class="toggleswitch"
                                                                    value="1"
                                                                    data-on="Yes"
                                                                    {{ $pageBlock->is_delete_denied ? 'checked="checked"' : '' }}
                                                                    data-off="No"
                                                            />
                                                            <label for="is_delete_denied"> &nbsp;Prevent
                                                                Deletion</label>
                                                        </div> <!-- /.form-group -->
                                                    </div> <!-- /.col -->
                                                </div> <!-- /.row -->
                                            </div> <!-- /.col -->
                                        </div>
                                        <button block-id="-1"
                                                data-save-block-btn
                                                style="float:left;margin-top: 0px;"
                                                type="submit"
                                                class="btn btn-success btn-lg save save-this-block"
                                        ><i class="fas fa-save"></i> {{ __('voyager::generic.save-block') }}</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif
            @endforeach
@endif
        </div>
    </div>
@stop

@section('javascript')
    <script>

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
            // active_parent = t.find('.has-child');
            // t.find('.item-child').each(function () {
            //     var $this = $(this)
            //     $('.modal-config').append(this);
            //     if ($this.find('input').first().hasClass('awesome-colorpicker')) {
            //         $this.find('input').first().spectrum('destroy')
            //         $this.find('input').first().spectrum({
            //             appendTo: '.modal-config',
            //             showInput: true,
            //             preferredFormat: 'rgb',
            //             showAlpha: true,
            //             showInitial: true,
            //             showPalette: true,
            //             palette: colors,
            //         })
            //     }
            //     var $input = $this.find('input').first()
            //     if ($input == null || $input == undefined || $input.length == 0) {
            //         $input = $this.find('select').first()
            //     }
            //     $this.closest('.item-child').attr('last-val', $input.val())
            // })
            // $('#blocksettingsmodal').modal('show');
        }

        $(function() {
            $('.toggleswitch').each(function() {
                $(this).bootstrapToggle();
            })
        })

        $(function () {




            // $('#mainModal').on('hidden.bs.modal', function () {
            //     $(this).find('.modal-body').children('.item-child').each(function () {
            //         $(this).find('select').each(function () {
            //             var $this = $(this)
            //             if ($this.is('select')) {
            //                 $this.val($this.parent().attr('last-val')).change();
            //
            //             }
            //         })
            //         $(this).find('input').each(function () {
            //             var $this = $(this)
            //             if ($this.hasClass('awesome-colorpicker')) {
            //                 $this.spectrum('set', $this.val())
            //             }
            //             $this.val($this.closest('.item-child').attr('last-val'));
            //         })
            //         current_parent.append(this);
            //     })
            // });

        });
    </script>
@endsection
