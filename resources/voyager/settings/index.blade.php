@extends('voyager::master')

@section('page_title', __('voyager::generic.viewing').' '.__('voyager::generic.settings'))

@section('css')

    <style>
        .panel-actions .voyager-trash {
            cursor: pointer;
        }

        .panel-actions .voyager-trash:hover {
            color: #e94542;
        }

        .settings .panel-actions {
            right: 0px;
        }

        .panel hr {
            margin-bottom: 10px;
        }

        .panel {
            padding-bottom: 15px;
        }

        .sort-icons {
            font-size: 21px;
            color: #ccc;
            position: relative;
            cursor: pointer;
        }

        .sort-icons:hover {
            color: #37474F;
        }

        .voyager-sort-desc {
            margin-right: 10px;
        }

        .voyager-sort-asc {
            top: 10px;
        }

        .page-title {
            margin-bottom: 0;
        }

        .panel-title code {
            border-radius: 30px;
            padding: 5px 10px;
            font-size: 11px;
            border: 0;
            position: relative;
            top: -2px;
        }

        .modal-open .settings .select2-container {
            z-index: 9 !important;
            width: 100% !important;
        }

        .new-setting {
            text-align: center;
            width: 100%;
            margin-top: 20px;
        }

        .new-setting .panel-title {
            margin: 0 auto;
            display: inline-block;
            color: #999fac;
            font-weight: lighter;
            font-size: 13px;
            background: #fff;
            width: auto;
            height: auto;
            position: relative;
            padding-right: 15px;
        }

        .settings .panel-title {
            padding-left: 0px;
            padding-right: 0px;
        }

        .new-setting hr {
            margin-bottom: 0;
            position: absolute;
            top: 7px;
            width: 96%;
            margin-left: 2%;
        }

        .new-setting .panel-title i {
            position: relative;
            top: 2px;
        }

        .new-settings-options {
            display: none;
            padding-bottom: 10px;
        }

        .new-settings-options label {
            margin-top: 13px;
        }

        .new-settings-options .alert {
            margin-bottom: 0;
        }

        #toggle_options {
            clear: both;
            float: right;
            font-size: 12px;
            position: relative;
            margin-top: 15px;
            margin-right: 5px;
            margin-bottom: 10px;
            cursor: pointer;
            z-index: 9;
            -webkit-touch-callout: none;
            -webkit-user-select: none;
            -khtml-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        .new-setting-btn {
            margin-right: 15px;
            position: relative;
            margin-bottom: 0;
            top: 5px;
        }

        .new-setting-btn i {
            position: relative;
            top: 2px;
        }

        textarea {
            min-height: 120px;
        }

        textarea.hidden {
            display: none;
        }

        .voyager .settings .nav-tabs {
            background: none;
            border-bottom: 0px;
        }

        .voyager .settings .nav-tabs .active a {
            border: 0px;
        }

        .select2 {
            width: 100% !important;
            border: 1px solid #f1f1f1;
            border-radius: 3px;
        }

        .voyager .settings input[type=file] {
            width: 100%;
        }

        .settings .select2 {
            margin-left: 10px;
        }

        .settings .select2-selection {
            height: 32px;
            padding: 2px;
        }

        .voyager .settings .nav-tabs > li {
            margin-bottom: -1px !important;
        }

        .voyager .settings .nav-tabs a {
            text-align: center;
            background: #f8f8f8;
            border: 1px solid #f1f1f1;
            position: relative;
            top: -1px;
            border-bottom-left-radius: 0px;
            border-bottom-right-radius: 0px;
        }

        .voyager .settings .nav-tabs a i {
            display: block;
            font-size: 22px;
        }

        .tab-content {
            background: #ffffff;
            border: 1px solid transparent;
        }

        .tab-content > div {
            padding: 10px;
        }

        .settings .no-padding-left-right {
            padding-left: 0px;
            padding-right: 0px;
        }

        .nav-tabs > li.active > a, .nav-tabs > li.active > a:focus, .nav-tabs > li.active > a:hover {
            background: #fff !important;
            color: #62a8ea !important;
            border-bottom: 1px solid #fff !important;
            top: -1px !important;
        }

        .nav-tabs > li a {
            transition: all 0.3s ease;
        }


        .nav-tabs > li.active > a:focus {
            top: 0px !important;
        }

        .voyager .settings .nav-tabs > li > a:hover {
            background-color: #fff !important;
        }
    </style>
@stop

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-settings"></i> {{ __('voyager::generic.settings') }}
    </h1>
@stop

@section('content')
    <div class="container-fluid">
        @include('voyager::alerts')
        @if(config('voyager.show_dev_tips'))
            <div class="alert alert-info">
                <strong>{{ __('voyager::generic.how_to_use') }}:</strong>
                <p>{{ __('voyager::settings.usage_help') }} <code>setting('group.key')</code></p>
            </div>
        @endif
    </div>

    <div class="page-content settings container-fluid">

        <form id="save-settings-menu" action="{{ route('voyager.settings.update') }}" method="POST"
              enctype="multipart/form-data">
            {{ method_field("PUT") }}
            {{ csrf_field() }}
            <input type="hidden" name="setting_tab" class="setting_tab" value="{{ $active }}"/>
            <div class="panel">

                <div class="page-content settings container-fluid" style="padding-left:0px;">
                            <ul class="nav nav-tabs">
                                @foreach($settings as $group => $setting)
                                    <li @if($group == $active) class="active" @endif>
                                        <a data-toggle="tab"
                                           href="#{{ \Illuminate\Support\Str::slug($group) }}">{{ $group }}</a>
                                    </li>
                                @endforeach
                            </ul>

                            <div class="tab-content">
                                @foreach($settings as $group => $group_settings)
                                    <div id="{{ \Illuminate\Support\Str::slug($group) }}"
                                         class="tab-pane fade in @if($group == $active) active @endif">
                                        @php
                                            $index = 0;
                                        @endphp
                                        @foreach($group_settings as $setting)
                                            @php($index+=1)

                                            <div class="panel-heading">
                                                <h3 class="panel-title">
                                                    {{ $setting->display_name }} @if(config('voyager.show_dev_tips'))
                                                        <code>setting('{{ $setting->key }}')</code>@endif
                                                </h3>
                                                <div class="panel-actions">
                                                    @if($index > 1)
                                                        <a class="move-pos-button"
                                                           href="{{ route('voyager.settings.move_up', $setting->id) }}">
                                                            <i class="sort-icons voyager-sort-asc"
                                                               style="font-size:32px;"></i>
                                                        </a>
                                                    @endif
                                                    @if($index < sizeof($group_settings))
                                                        <a class="move-pos-button"
                                                           href="{{ route('voyager.settings.move_down', $setting->id) }}">
                                                            <i class="sort-icons voyager-sort-desc"
                                                               style="font-size:32px;"></i>
                                                        </a>
                                                    @endif
                                                    <i class="voyager-trash" style="font-size:32px;" data-delete-setting
                                                       data-id="{{ $setting->id }}"
                                                       data-display-key="{{ $setting->key }}"
                                                       data-display-name="{{ $setting->display_name }}"></i>
                                                </div>
                                            </div>

                                            <div class="panel-body no-padding-left-right">
                                                <div class="col-md-10 no-padding-left-right">
                                                    @if(substr( $setting->type, 0, 7 ) == "voyager")
                                                        @include("viaativa-".$setting->type,["setting" => $setting])
                                                    @else
                                                        @include("viaativa-voyager::formfields.settings.{$setting->type}",["setting" => $setting])
                                                    @endif
                                                </div>
                                                <div class="col-md-2 no-padding-left-right">
                                                    <select class="form-control group_select"
                                                            name="{{ $setting->key }}_group">
                                                        @foreach($groups as $group)
                                                            <option value="{{ $group }}" {!! $setting->group == $group ? 'selected' : '' !!}>{{ $group }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            @if(!$loop->last)
                                                <hr>
                                            @endif
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                    </div>

                </div>
                <button type="submit" data-save-setting
                        class="btn btn-primary pull-right">{{ __('voyager::settings.save') }}</button>
        </form>

        <div style="clear:both"></div>

        @can('add', Voyager::model('Setting'))
            <div class="panel" style="margin-top:10px;">
                <div class="panel-heading new-setting">
                    <hr>
                    <h3 class="panel-title"><i class="voyager-plus"></i> {{ __('voyager::settings.new') }}</h3>
                </div>
                <div class="panel-body">
                    <form id="add-new-setting" action="{{ route('voyager.settings.store') }}" method="POST">
                        {{ csrf_field() }}
                        <input type="hidden" name="setting_tab" class="setting_tab" value="{{ $active }}"/>
                        <div class="col-md-3">
                            <label for="display_name">{{ __('voyager::generic.name') }}</label>
                            <input type="text" class="form-control" name="display_name"
                                   placeholder="{{ __('voyager::settings.help_name') }}" required="required">
                        </div>
                        <div class="col-md-3">
                            <label for="key">{{ __('voyager::generic.key') }}</label>
                            <input type="text" class="form-control" name="key"
                                   placeholder="{{ __('voyager::settings.help_key') }}" required="required">
                        </div>
                        <div class="col-md-3">
                            <label for="type">{{ __('voyager::generic.type') }}</label>
                            <select name="type" class="form-control" required="required">
                                <option value="">{{ __('voyager::generic.choose_type') }}</option>
                                @foreach(Viaativa\Viaroot\Models\SettingsFormfields::where('type',null)->get() as $key => $val)
                                    <option value="{{$val->route}}">{{$val->name}}</option>
                                @endforeach

                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="group">{{ __('voyager::settings.group') }}</label>
                            <select class="form-control group_select group_select_new" name="group">
                                @foreach($groups as $group)
                                    <option value="{{ $group }}">{{ $group }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12">
                            <a id="toggle_options"><i
                                        class="voyager-double-down"></i> {{ mb_strtoupper(__('voyager::generic.options')) }}
                            </a>
                            <div class="new-settings-options">
                                <label for="options">{{ __('voyager::generic.options') }}
                                    <small>{{ __('voyager::settings.help_option') }}</small>
                                </label>
                                <div id="options_editor" class="form-control min_height_200" data-language="json"></div>
                                <textarea id="options_textarea" name="details" class="hidden"></textarea>
                                <div id="valid_options" class="alert-success alert"
                                     style="display:none">{{ __('voyager::json.valid') }}</div>
                                <div id="invalid_options" class="alert-danger alert"
                                     style="display:none">{{ __('voyager::json.invalid') }}</div>
                            </div>
                        </div>
                        <div style="clear:both"></div>
                        <button data-add-new-setting type="submit" class="btn btn-primary pull-right new-setting-btn">
                            <i class="voyager-plus"></i> {{ __('voyager::settings.add_new') }}
                        </button>
                        <div style="clear:both"></div>
                    </form>
                </div>
            </div>
        @endcan
    </div>

    <div class="modal modal-danger fade" tabindex="-1" id="delete_modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"
                            aria-label="{{ __('voyager::generic.close') }}">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">
                        <i class="voyager-trash"></i> {!! __('voyager::settings.delete_question', ['setting' => '<span id="delete_setting_title"></span>']) !!}
                    </h4>
                </div>

                <div class="modal-footer">
                    <form action="#" id="delete_form" method="POST">
                        {{ method_field("DELETE") }}
                        {{ csrf_field() }}
                        <input type="submit" class="btn btn-danger pull-right delete-confirm"
                               value="{{ __('voyager::settings.delete_confirm') }}">
                    </form>
                    <button type="button" class="btn btn-default pull-right"
                            data-dismiss="modal">{{ __('voyager::generic.cancel') }}</button>
                </div>
            </div>
        </div>
    </div>

@stop

@section('javascript')
    <script>

        $("[data-delete-setting]").on('click', function (e) {
            e.preventDefault();
            //var result = dialog("Are you sure you want to duplicate this block?", function(){}, function(){});
            swal({
                title: "Você tem certeza?",
                text: "Apos remover esta configuração, não sera possivel recupera-la!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
                .then((willDelete) => {
                    if (willDelete) {
                        swal("Configuração removido com sucesso!\nA página será atualizada, aguarde.", {
                            icon: "success",
                            buttons: false
                        });
                        $('#delete_form')[0].action = '{{ route('voyager.settings.delete', [ 'id' => '__id' ]) }}'.replace('__id', $(this).data('id'));
                        $('#delete_form').submit()
                    } else {
                    }
                });
            //if (result) $(this).closest('form').submit();
        });


        $('.move-pos-button').each(function () {
            $(this).on('click', function (e) {
                //e.preventDefault();
                swal("Operação realizada com sucesso!\nA página será atualizada, aguarde.", {
                    icon: "success",
                    buttons: false
                });
            })
        })
        $("[data-add-new-setting]").on('click', function (e) {
            //swal("teste");
            e.preventDefault();
            //var result = dialog("Are you sure you want to duplicate this block?", function(){}, function(){});
            swal("Configuração adicionada com sucesso!\nA página será atualizada, aguarde.", {
                icon: "success",
                buttons: false
            });
            $("#add-new-setting").submit()
            //if (result) $(this).closest('form').submit();
        });


        $("[data-save-setting]").on('click', function (e) {
            //swal("teste");
            e.preventDefault();
            //var result = dialog("Are you sure you want to duplicate this block?", function(){}, function(){});
            swal("Configuração salva com sucesso!\nA página será atualizada, aguarde.", {
                icon: "success",
                buttons: false
            });
            $("#save-settings-menu").submit()
            //if (result) $(this).closest('form').submit();
        });

        $('document').ready(function () {
            $('#toggle_options').click(function () {
                $('.new-settings-options').toggle();
                if ($('#toggle_options .voyager-double-down').length) {
                    $('#toggle_options .voyager-double-down').removeClass('voyager-double-down').addClass('voyager-double-up');
                } else {
                    $('#toggle_options .voyager-double-up').removeClass('voyager-double-up').addClass('voyager-double-down');
                }
            });

            /*$('.panel-actions .voyager-trash').click(function () {
                var display = $(this).data('display-name') + '/' + $(this).data('display-key');

                $('#delete_setting_title').text(display);

                $('#delete_form')[0].action = '{{ route('voyager.settings.delete', [ 'id' => '__id' ]) }}'.replace('__id', $(this).data('id'));
                $('#delete_modal').modal('show');
            });*/

            $('.toggleswitch').bootstrapToggle();

            $('[data-toggle="tab"]').click(function () {
                $(".setting_tab").val($(this).html());
            });

            $('.delete_value').click(function (e) {
                e.preventDefault();
                $(this).closest('form').attr('action', $(this).attr('href'));
                $(this).closest('form').submit();
            });
        });
    </script>
    <script type="text/javascript">
        $(".group_select").not('.group_select_new').select2({
            tags: true,
            width: 'resolve'
        });
        $(".group_select_new").select2({
            tags: true,
            width: 'resolve',
            placeholder: '{{ __("voyager::generic.select_group") }}'
        });
        $(".group_select_new").val('').trigger('change');
    </script>
    <iframe id="form_target" name="form_target" style="display:none"></iframe>
    <form id="my_form" action="{{ route('voyager.upload') }}" target="form_target" method="POST"
          enctype="multipart/form-data" style="width:0;height:0;overflow:hidden">
        {{ csrf_field() }}
        <input name="image" id="upload_file" type="file" onchange="$('#my_form').submit();this.value='';">
        <input type="hidden" name="type_slug" id="type_slug" value="settings">
    </form>

    <script>
        var options_editor = ace.edit('options_editor');
        options_editor.getSession().setMode("ace/mode/json");

        var options_textarea = document.getElementById('options_textarea');
        options_editor.getSession().on('change', function () {
            options_textarea.value = options_editor.getValue();
        });
    </script>
@stop
