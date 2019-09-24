@php
    $edit = !is_null($dataTypeContent->getKey());
    $add  = is_null($dataTypeContent->getKey());

@endphp

@extends('voyager::master')

@section('page_title', __('voyager::generic.'.(isset($dataTypeContent->id) ? 'edit' : 'add')).' '.$dataType->display_name_singular)

@section('css')
    <style>
        .panel .mce-panel {
            border-left-color: #fff;
            border-right-color: #fff;
        }

        .panel .mce-toolbar,
        .panel .mce-statusbar {
            padding-left: 20px;
        }

        .panel .mce-edit-area,
        .panel .mce-edit-area iframe,
        .panel .mce-edit-area iframe html {
            padding: 0 10px;
            min-height: 350px;
        }

        .mce-content-body {
            color: #555;
            font-size: 14px;
        }

        .panel.is-fullscreen .mce-statusbar {
            position: absolute;
            bottom: 0;
            width: 100%;
            z-index: 200000;
        }

        .panel.is-fullscreen .mce-tinymce {
            height: 100%;
        }

        .panel.is-fullscreen .mce-edit-area,
        .panel.is-fullscreen .mce-edit-area iframe,
        .panel.is-fullscreen .mce-edit-area iframe html {
            height: 100%;
            position: absolute;
            width: 99%;
            overflow-y: scroll;
            overflow-x: hidden;
            min-height: 100%;
        }
    </style>
@stop

@section('page_header')
    <h1 class="page-title">
        <i class="{{ $dataType->icon }}"></i>
        {{ __('voyager::generic.'.(isset($dataTypeContent->id) ? 'edit' : 'add')).' '.$dataType->display_name_singular }}
    </h1>
    @include('voyager::multilingual.language-selector')
@stop

@section('content')

    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    <div class="page-content container-fluid">
        <form class="form-edit-add" role="form"
              action="{{ $edit ? route('voyager.'.$dataType->slug.'.update', $dataTypeContent->getKey()) : route('voyager.'.$dataType->slug.'.store') }}"
              method="POST" enctype="multipart/form-data">
            <!-- PUT Method if we are editing -->
            @php
                $dataTypeRows = $dataType->{($edit ? 'editRows' : 'addRows' )};
                    $rows = $dataTypeRows->toArray();
                    foreach($rows as $rowKey => $row)
                    {
                        if($row['options'] != null)
                        {
                        $options = json_decode($row['options']);
                            foreach($options as $key => $option)
                            {
                            $row["opt_".$key] = $option;
                            }
                        }
                        $rows[$rowKey] = $row;
                    }
                    $rowGroups = collect($rows)->groupBy('opt_type');
            @endphp
            @if(isset($dataTypeContent->id))
                {{ method_field("PUT") }}
            @endif
            {{ csrf_field() }}

            <div class="row">
                <div class="col-md-8">
                    <!-- ### TITLE ### -->
                    @if(isset($rowGroups['main']))
                        @foreach($rowGroups['main'] as $row)
                            <div class="panel">

                                <div class="panel-heading">
                                    <h3 class="panel-title">
                                        <i class="voyager-character"></i> {{$row['display_name']}}
                                        <span class="panel-desc"></span>
                                    </h3>
                                    <div class="panel-actions">
                                        <a class="panel-action voyager-angle-down" data-toggle="panel-collapse"
                                           aria-hidden="true"></a>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    @include('viaativa-voyager::formfields.bread.'.$row['type'],["row" => (object)$row,"dataType" => $dataType, 'dataTypeContent' => $dataTypeContent,"options" => $row['details']])
                                </div>

                            </div>
                        @endforeach
                    @endif





                    @if(isset($rowGroups['secondary']))
                        @foreach($rowGroups['secondary'] as $row)
                            <div class="panel">
                                <div class="panel-heading">
                                    <h3 class="panel-title">{{$row['display_name']}} </h3>
                                    <div class="panel-actions">
                                        <a class="panel-action voyager-angle-down" data-toggle="panel-collapse"
                                           aria-hidden="true"></a>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    @include('viaativa-voyager::formfields.bread.'.$row['type'],["row" => (object)$row,"dataType" => $dataType, 'dataTypeContent' => $dataTypeContent,"options" => $row['details']])
                                    {{--                                <textarea class="form-control" name="excerpt">{{ $dataTypeContent->excerpt ?? '' }}</textarea>--}}
                                </div>
                            </div>
                        @endforeach
                    @endif



                    @if(isset($rowGroups['groups']))
                        @php
                            $rowGroup = collect($rowGroups['groups'])->groupBy('opt_menu');
                        @endphp
                        @foreach($rowGroup as $key => $rowInGroup)
                            <div class="panel">
                                <div class="panel-heading">
                                    <h3 class="panel-title">{{$key}}</h3>
                                    <div class="panel-actions">
                                        <a class="panel-action voyager-angle-down" data-toggle="panel-collapse"
                                           aria-hidden="true"></a>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    <div class="form-group row">
                                    @foreach($rowInGroup as $row)
                                        <div class="col-md-{{$row['opt_width']}}">
                                            <label for="slug">{{$row['display_name']}}</label>
                                            @include('viaativa-voyager::formfields.bread.'.$row['type'],["row" => (object)$row,"dataType" => $dataType, 'dataTypeContent' => $dataTypeContent,"options" => $row['details']])
                                        </div>
                                    @endforeach
                                    </div>
                                </div>
                            </div>

                        @endforeach
                    @endif


                    @if(isset($rowGroups['']))
                        @foreach($rowGroups[''] as $row)
                            <div class="panel">
                                <div class="panel-heading">
                                    <h3 class="panel-title">{{$row['display_name']}}</h3>
                                    <div class="panel-actions">
                                        <a class="panel-action voyager-angle-down" data-toggle="panel-collapse"
                                           aria-hidden="true"></a>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    @include('viaativa-voyager::formfields.bread.'.$row['type'],["row" => (object)$row,"dataType" => $dataType, 'dataTypeContent' => $dataTypeContent,"options" => $row['details']])
                                    {{--                                <textarea class="form-control" name="excerpt">{{ $dataTypeContent->excerpt ?? '' }}</textarea>--}}
                                </div>
                            </div>
                        @endforeach
                    @endif


                </div>
                <div class="col-md-4">
                    <!-- ### DETAILS ### -->

                    @if(isset($rowGroups['side']))
                        @php
                            $rowGroup = collect($rowGroups['side'])->groupBy('opt_menu');
                        @endphp
                        @foreach($rowGroup as $key => $rowInGroup)
                            <div class="panel panel panel-bordered panel-warning">
                                <div class="panel-heading">
                                    <h3 class="panel-title"><i class="icon wb-clipboard"></i>{{$key}}</h3>
                                    <div class="panel-actions">
                                        <a class="panel-action voyager-angle-down" data-toggle="panel-collapse"
                                           aria-hidden="true"></a>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        @foreach($rowInGroup as $row)
                                            <label for="slug">{{$row['display_name']}}</label>
                                            @include('viaativa-voyager::formfields.bread.'.$row['type'],["row" => (object)$row,"dataType" => $dataType, 'dataTypeContent' => $dataTypeContent,"options" => $row['details']])
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif

                <!-- ### IMAGE ### -->
                    {{--                    <div class="panel panel-bordered panel-primary">--}}
                    {{--                        <div class="panel-heading">--}}
                    {{--                            <h3 class="panel-title"><i class="icon wb-image"></i>Imagem da Página</h3>--}}
                    {{--                            <div class="panel-actions">--}}
                    {{--                                <a class="panel-action voyager-angle-down" data-toggle="panel-collapse" aria-hidden="true"></a>--}}
                    {{--                            </div>--}}
                    {{--                        </div>--}}
                    {{--                        <div class="panel-body">--}}
                    {{--                            @if(isset($dataTypeContent->image))--}}
                    {{--                                <img src="{{ filter_var($dataTypeContent->image, FILTER_VALIDATE_URL) ? $dataTypeContent->image : Voyager::image( $dataTypeContent->image ) }}" style="width:100%" />--}}
                    {{--                            @endif--}}
                    {{--                            <input type="file" name="image">--}}
                    {{--                        </div>--}}
                    {{--                    </div>--}}

                    {{--                    <!-- ### SEO CONTENT ### -->--}}
                    {{--                    <div class="panel panel-bordered panel-info">--}}
                    {{--                        <div class="panel-heading">--}}
                    {{--                            <h3 class="panel-title"><i class="icon wb-search"></i> {{ __('voyager::post.seo_content') }}</h3>--}}
                    {{--                            <div class="panel-actions">--}}
                    {{--                                <a class="panel-action voyager-angle-down" data-toggle="panel-collapse" aria-hidden="true"></a>--}}
                    {{--                            </div>--}}
                    {{--                        </div>--}}
                    {{--                        <div class="panel-body">--}}
                    {{--                            <div class="form-group">--}}
                    {{--                                <label for="meta_description">Meta description</label>--}}
                    {{--                                @include('voyager::multilingual.input-hidden', [--}}
                    {{--                                    '_field_name'  => 'meta_description',--}}
                    {{--                                    '_field_trans' => get_field_translations($dataTypeContent, 'meta_description')--}}
                    {{--                                ])--}}
                    {{--                                <textarea class="form-control" name="meta_description">{{ $dataTypeContent->meta_description ?? '' }}</textarea>--}}
                    {{--                            </div>--}}
                    {{--                        </div>--}}
                    {{--                    </div>--}}
                </div>
            </div>

            <button type="submit" class="btn btn-primary pull-right">
                @if(isset($dataTypeContent->id))Salvar @else <i class="fas fa-save"></i> Criar @endif
            </button>
        </form>

        <iframe id="form_target" name="form_target" style="display:none"></iframe>
        <form id="my_form" action="{{ route('voyager.upload') }}" target="form_target" method="post"
              enctype="multipart/form-data" style="width:0px;height:0;overflow:hidden">
            {{ csrf_field() }}
            <input name="image" id="upload_file" type="file" onchange="$('#my_form').submit();this.value='';">
            <input type="hidden" name="type_slug" id="type_slug" value="{{ $dataType->slug }}">
        </form>
    </div>
    <div class="modal fade modal-danger" id="confirm_delete_modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><i class="voyager-warning"></i> {{ __('voyager::generic.are_you_sure') }}
                    </h4>
                </div>
                <div class="modal-body">
                    <h4>{{ __('voyager::generic.are_you_sure_delete') }} '<span class="confirm_delete_name"></span>'
                    </h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default"
                            data-dismiss="modal">{{ __('voyager::generic.cancel') }}</button>
                    <button type="button" class="btn btn-danger"
                            id="confirm_delete">{{ __('voyager::generic.delete_confirm') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
@stop

@section('javascript')
    <script>
        $('document').ready(function () {
            $('#slug').slugify();

            @if ($isModelTranslatable)
            $('.side-body').multilingual({"editing": true});
            @endif

            $('.side-body input[data-slug-origin]').each(function (i, el) {
                $(el).slugify();
            });
            $('.form-group').on('click', '.remove-multi-image', function (e) {
                e.preventDefault();
                $image = $(this).siblings('img');
                params = {
                    slug: '{{ $dataType->slug }}',
                    image: $image.data('image'),
                    id: $image.data('id'),
                    field: $image.parent().data('field-name'),
                    _token: '{{ csrf_token() }}'
                }
                $('.confirm_delete_name').text($image.data('image'));
                $('#confirm_delete_modal').modal('show');
            });
            $('#confirm_delete').on('click', function () {
                $.post('{{ route('voyager.media.remove') }}', params, function (response) {
                    if (response
                        && response.data
                        && response.data.status
                        && response.data.status == 200) {
                        toastr.success(response.data.message);
                        $image.parent().fadeOut(300, function () {
                            $(this).remove();
                        })
                    } else {
                        toastr.error("Error removing image.");
                    }
                });
                $('#confirm_delete_modal').modal('hide');
            });
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@stop
