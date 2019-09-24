@extends('voyager::master')

@section('page_title', __('voyager::generic.viewing').' '.$dataType->display_name_plural)

@section('page_header')
    <div class="container-fluid">
        <h1 class="page-title">
            <i class="{{ $dataType->icon }}"></i> {{ $dataType->display_name_plural }}
        </h1>
        @can('add', app($dataType->model_name))
            <a href="{{ route('voyager.'.$dataType->slug.'.create') }}" class="btn btn-success btn-add-new">
                <i class="voyager-plus"></i> <span>{{ __('voyager::generic.add_new') }}</span>
            </a>
        @endcan
        @can('delete',app($dataType->model_name))
            <a class="btn btn-danger delete-this" style="margin-top:2px;margin-bottom:5px;" data-href="{{route('voyager.page-categories.delete-page-category',['page_category' => $breadcrumbs[sizeof($breadcrumbs)-1]['text']])}}">
                <i class="voyager-trash"></i> <span class="hidden-xs hidden-sm">{{ __('voyager::generic.delete') }}</span>
            </a>
            <script>
                $('.delete-this').click(function() {
                    Swal.fire({
                        title: "ATENÇÃO!",
                        text: "Você está deletando esta categoria, as paginas não serão deletadas, mas tudo relacionado a esta categoria será movido para nenhuma categoria, digite 'DELETAR' para confirmar",
                        input: 'text',
                        type: 'warning',
                        showCancelButton: true
                    }).then((result) => {
                        if (result.value) {
                            if (result.value == "DELETAR") {
                                toastr.warning('Deletando a categoria, por favor aguarde.')
                                window.location.href = $(this).data('href');
                            } else {
                                Swal.fire({
                                    title: "Ação cancelada por erro de digitação"
                                })
                            }
                        }
                    });
                })
            </script>
        @endcan
        @can('edit', app($dataType->model_name))
            @if(isset($dataType->order_column) && isset($dataType->order_display_column))
                <a href="{{ route('voyager.'.$dataType->slug.'.order') }}" class="btn btn-primary">
                    <i class="voyager-list"></i> <span>{{ __('voyager::bread.order') }}</span>
                </a>
            @endif
        @endcan
        @include('voyager::multilingual.language-selector')
    </div>
@stop

@section('content')
    <div class="page-content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-heading">
                        <p class="panel-title" style="color:#777">{{ __('voyager::menu_builder.drag_drop_info') }}</p>
                    </div>

                    <div class="panel-body" style="padding:30px;">
                        <div class="dd">
                            <ol class="dd-list" id="sortable">
                                @php
                                    $dataTypeContent = $dataTypeContent->sortBy('order');
                                @endphp
                                @foreach ($dataTypeContent as $item)

                                    <li class="dd-item" data-id="{{ $item->id }}">
                                        <div class="pull-right item_actions" style="display:inline-flex;">
                                            @can('edit', $item)
                                                <a href="{{ route('voyager.page-blocks.edit', $item->{$item->getKeyName()}) }}"
                                                   title="Blocos"
                                                   class="btn btn-sm btn-primary pull-right edit">
                                                    <i class="voyager-edit"></i>
                                                </a>
                                                <form id="duplicate_form-{{$item->id}}" method="post"
                                                      action="{{ route('voyager.pages.duplicate') }}">
                                                    @csrf
                                                    <input type="hidden" name="page_id" value="{{$item->id}}">
                                                    <a onclick="document.getElementById('duplicate_form-{{$item->id}}').submit(); return false;"
                                                       title="Duplicar"
                                                       style="background-color: #5bc0de;"
                                                       class="btn btn-sm btn-primary pull-right edit">
                                                        <i class="voyager-documentation"></i>
                                                    </a>
                                                </form>

                                                <a href="{{ route('voyager.pages.edit', $item->{$item->getKeyName()}) }}"
                                                   title="{{ __('voyager::generic.settings') }}"
                                                   class="btn btn-sm btn-warning pull-right edit">
                                                    <i class="voyager-settings"></i>
                                                </a>
                                            @endcan

                                            @can('delete', $item)
                                                <a href="javascript:;" title="{{ __('voyager::generic.delete') }}"
                                                   class="btn btn-sm btn-primary pull-right delete"
                                                   style="background:red;"
                                                   data-id="{{ $item->{$item->getKeyName()} }}"
                                                   id="delete-{{ $item->{$item->getKeyName()} }}">
                                                    <i class="voyager-trash"></i>
                                                </a>
                                            @endcan
                                        </div>
                                        <div class="dd-handle">
                                            @if(isset($options) and $options->isModelTranslatable)
                                                @include('voyager::multilingual.input-hidden', [
                                                    'isModelTranslatable' => true,
                                                    '_field_name'         => 'title'.$item->id,
                                                    '_field_trans'        => json_encode($item->getTranslationsOf('title'))
                                                ])
                                            @endif
                                            <span>{{ $item->title }}</span>
                                            <small class="url">/{{ $item->slug }}</small>
                                            <small class="url">{{ $item->url }}</small>
                                        </div>
                                    </li>

                                @endforeach

                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal modal-danger fade" tabindex="-1" id="delete_modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"
                            aria-label="{{ __('voyager::generic.close') }}"><span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title"><i
                                class="voyager-trash"></i> {{ __('voyager::generic.delete_question') }} {{ strtolower($dataType->display_name_singular) }}
                        ?</h4>
                </div>
                <div class="modal-footer">
                    <form action="#" id="delete_form" method="POST">
                        {{ method_field('DELETE') }}
                        {{ csrf_field() }}
                        <input type="submit" class="btn btn-danger pull-right delete-confirm"
                               value="{{ __('voyager::generic.delete_confirm') }}">
                    </form>
                    <button type="button" class="btn btn-default pull-right"
                            data-dismiss="modal">{{ __('voyager::generic.cancel') }}</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
@stop

@section('css')
    @if(!$dataType->server_side && config('dashboard.data_tables.responsive'))
        <link rel="stylesheet" href="{{ voyager_asset('lib/css/responsive.dataTables.min.css') }}">
    @endif
@stop

@section('javascript')
    <!-- DataTables -->
@section('javascript')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.js"
            integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30=" crossorigin="anonymous"></script>
    <script>
        $(function () {

            var $sortableList = $("#sortable");


            var sortEventHandler = function (event, ui) {
                ////console.log("New sort order!");
                var listElements = $sortableList.children();
                var listValues = [];
                ////console.log(listElements)
                $(listElements).each(function (i) {
                    var $this = $(this);
                    listValues.push({"pageid": $this.data('id'), "order": i})

                });
                ////console.log(listValues)
                $.post('{{ route('voyager.pages.sort-item') }}', {
                    order: JSON.stringify(listValues),
                    _token: '{{ csrf_token() }}'
                }, function (data) {
                    toastr.success("Ordem atualizada com sucesso!");
                });
            };


            $sortableList.sortable({
                stop: sortEventHandler
            });
            $("#sortable").disableSelection();
        });

        var deleteFormAction;
        $('div').on('click', '.delete', function (e) {
            $('#delete_form')[0].action = '{{ route('voyager.'.$dataType->slug.'.destroy', ['id' => '__id']) }}'.replace('__id', $(this).data('id'));
            $('#delete_modal').modal('show');
        });
    </script>
@stop
@stop
