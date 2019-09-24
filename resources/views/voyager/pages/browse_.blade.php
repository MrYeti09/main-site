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

@section('javascript')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.js" integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30=" crossorigin="anonymous"></script>
    <script>
        $( function() {

            var $sortableList = $("#sortable");


            var sortEventHandler = function(event, ui){
                //console.log("New sort order!");
                var listElements = $sortableList.children();
                var listValues = [];
                //console.log(listElements)
                $(listElements).each(function(i){
                    var $this = $(this).children();
                    listValues.push({"pageid": $this.data('item-id'),"order": i})

                });
                //console.log(listValues)
                $.post('{{ route('voyager.pages.sort-item') }}', {
                    order: JSON.stringify(listValues),
                    _token: '{{ csrf_token() }}'
                }, function (data) {
                    //console.log(data)
                    toastr.success("Atualizado com sucesso!");
                });
                //console.log(listValues);
            };




            $sortableList.sortable({
                stop: sortEventHandler
            });
            $( "#sortable" ).disableSelection();
        } );
    </script>
    @stop

@section('css')
    <style>
        .panel.widget .main-i {
            font-size: 48px;
            background: rgba(0,0,0,.3);
            border-radius: 50%;
            width: 100px;
            height: 100px;
            display: block;
            margin: 0 auto;
            color: #eee;
            line-height: 110px;
        }

        .panel.widget i {
            font-size: inherit;
            margin: 0 0;
            color: #eee;
            border-radius: 0;
            width: auto;
            height: auto;
            background: transparent;
            line-height: 1;

        }

        .panel.widget .btn {
            display: flex;
            flex-direction: row;
            align-items: center;
            margin: 2px;
            opacity: 1;
        }

        .panel.widget .btn:hover {
            display: flex;
            flex-direction: row;
            align-items: center;
            margin: 2px;
            opacity: 1;
            filter: brightness(110%);
        }


    </style>
    @endsection

@section('content')
    <div class="page-content browse container-fluid">
        @include('voyager::alerts')
        <ul class="row" id="sortable" style="padding-left:0px;">
            @php
                $dataTypeContent = $dataTypeContent->sortBy('order');
            @endphp
            @foreach($dataTypeContent as $data)
                <div class="col-md-4 col-sm-6 col-lg-2">
            <li data-item-id="{{$data->id}}" class="panel widget center bgimage" style="padding:30px 10px;max-height: 330px;margin-bottom:0;overflow:hidden;@if(strlen($data->image)) background-image:url('{{Voyager::image($data->image)}}'); @else background-image:url('https://placeimg.com/640/480/tech?t={{rand(0,50000)}}') @endif">
                <div class="dimmer"></div>
                <div class="panel-content">

                    <h3 style="color:white;text-overflow: ellipsis;
white-space: nowrap;
overflow: hidden;">{{$data->title}}</h3>
                    <small style="color:white;">/{{$data->slug}}</small>
                    <p>{{$data->description}}</p>
                    <div style="display: flex;flex-direction: column;
                    align-items: center;
                    justify-content: center;
            flex-wrap: wrap;"
                         id="bread-actions">
                        <div style="display:inline-flex">
                    @can('delete', $data)
                        <a href="javascript:;" title="{{ __('voyager::generic.delete') }}"
                           class="btn btn-sm btn-primary pull-right delete"
                           style="background:red;"
                           data-id="{{ $data->{$data->getKeyName()} }}"
                           id="delete-{{ $data->{$data->getKeyName()} }}">
                            <i class="voyager-trash"></i>
                        </a>
                    @endcan
                    @can('edit', $data)
                        <form id="duplicate_form-{{$data->id}}" method="post" action="{{ route('voyager.pages.duplicate') }}">
                            @csrf
                            <input type="hidden" name="page_id" value="{{$data->id}}">
                            <a onclick="document.getElementById('duplicate_form-{{$data->id}}').submit(); return false;"
                               title="Duplicar"
                               style="background-color: #5bc0de;"
                               class="btn btn-sm btn-primary pull-right edit">
                                <i class="voyager-documentation"></i>
                            </a>
                        </form>

                        <a href="{{ route('voyager.pages.edit', $data->{$data->getKeyName()}) }}"
                           title="{{ __('voyager::generic.settings') }}"
                           class="btn btn-sm btn-warning pull-right edit">
                            <i class="voyager-settings"></i>
                        </a>

                    @endcan
                        @can('edit',$data)
                            <a href="{{ route('voyager.page-blocks.edit', $data->{$data->getKeyName()}) }}"
                               title="Blocos"
                               class="btn btn-sm btn-primary pull-right edit">
                                <i class="voyager-edit"></i>
                            </a>
                            @endcan
                        </div>
{{--                    @can('read', $data)--}}
{{--                        <a href="{{ route('voyager.pages.show', $data->{$data->getKeyName()}) }}"--}}
{{--                           title="{{ __('voyager::generic.view') }}"--}}
{{--                           class="btn btn-sm btn-warning pull-right">--}}
{{--                            <i class="voyager-eye"></i> <span--}}
{{--                                    class="hidden-xs hidden-sm">{{ __('voyager::generic.view') }}</span>--}}
{{--                        </a>--}}
{{--                    @endcan--}}
                    </div>
                </div>
            </li>
                </div>
            @endforeach
        </ul>

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
    @if(!$dataType->server_side && config('dashboard.data_tables.responsive'))
        <script src="{{ voyager_asset('lib/js/dataTables.responsive.min.js') }}"></script>
    @endif
    <script>
        $(document).ready(function () {
                    @if (!$dataType->server_side)
            var table = $('#dataTable').DataTable({!! json_encode(
                    array_merge([
                        "order" => [],
                        "language" => __('voyager::datatable'),
                        "columnDefs" => [['targets' => -1, 'searchable' =>  false, 'orderable' => false]],
                    ],
                    config('voyager.dashboard.data_tables', []))
                , true) !!});
            @else
            $('#search-input select').select2({
                minimumResultsForSearch: Infinity
            });
            @endif

            @if ($isModelTranslatable)
            $('.side-body').multilingual();
            //Reinitialise the multilingual features when they change tab
            $('#dataTable').on('draw.dt', function () {
                $('.side-body').data('multilingual').init();
            })
            @endif
            $('.select_all').on('click', function (e) {
                $('input[name="row_id"]').prop('checked', $(this).prop('checked'));
            });
        });


        var deleteFormAction;
        $('div').on('click', '.delete', function (e) {
            $('#delete_form')[0].action = '{{ route('voyager.'.$dataType->slug.'.destroy', ['id' => '__id']) }}'.replace('__id', $(this).data('id'));
            $('#delete_modal').modal('show');
        });
    </script>
@stop
