@extends('voyager::master')

@section('page_title', __('voyager::generic.viewing').' '.$dataType->display_name_plural)

@section('page_header')
    <div class="container-fluid">
        <h1 class="page-title">
            <i class="{{ $dataType->icon }}"></i> {{ $dataType->display_name_plural }}
        </h1>
        @can('add', app($dataType->model_name))
            <a href="{{ route('voyager.'.$dataType->slug.'.create') }}" class="btn btn-success btn-add-new">
                <i class="voyager-plus"></i> <span>{{ __('voyager::generic.add_new') }} nova Categoria</span>
            </a>
            <a href="{{ route('voyager.pages.create') }}" class="btn btn-primary btn-add-new">
                <i class="voyager-plus"></i> <span>{{ __('voyager::generic.add_new') }} nova Pagina</span>
            </a>
        @endcan
        @can('delete', app($dataType->model_name))
            @include('voyager::partials.bulk-delete')
        @endcan
        @can('edit', app($dataType->model_name))
            @if(isset($dataType->order_column) && isset($dataType->order_display_column))
                <a href="{{ route('voyager.'.$dataType->slug.'.order') }}" class="btn btn-primary btn-add-new">
                    <i class="voyager-list"></i> <span>{{ __('voyager::bread.order') }}</span>
                </a>
            @endif
        @endcan
        @can('delete', app($dataType->model_name))
            @if($usesSoftDeletes)
                <input type="checkbox" @if ($showSoftDeleted) checked @endif id="show_soft_deletes" data-toggle="toggle" data-on="{{ __('voyager::bread.soft_deletes_off') }}" data-off="{{ __('voyager::bread.soft_deletes_on') }}">
            @endif
        @endcan
        @foreach(Voyager::actions() as $action)
            @if (method_exists($action, 'massAction'))
                @include('voyager::bread.partials.actions', ['action' => $action, 'data' => null])
            @endif
        @endforeach
        @include('voyager::multilingual.language-selector')
    </div>
@stop

@section('content')
    @php
        $color = 0;
        $colors = ['#5cc15a','#ffc507','#8a4be7','#fb604b',"","","","","","","","","","","","","","","","","","","","","","","","","","","","","",""]
    @endphp
    <div class="page-content browse container-fluid">
        @include('voyager::alerts')
        <div class="row">
            <div class="col-md-12 " style="padding-left:8px;display:flex;flex-direction: row;">
                <div class="panel" style="width:100%;">
                    <div class="panel-body" >
                        @php
                            $filterBy = 'name';
                            $searchBy = app($dataType->model_name)->all();

                            $fields = [];
                            $details = $dataType->rows()->where('field',$filterBy)->first();

                            foreach($searchBy as $key => $item)
                            {

                             if($details == null or !is_object($details->details))
                             {
                                 if(!in_array(["key" => $item->{$filterBy},'name' => $item->{$filterBy}],$fields))
                                    {
                                    array_push($fields,["key" => $item->{$filterBy},'name' => $item->{$filterBy}]);
                                    }
                             } else
                                 {
                                 if(!in_array($item,$fields))
                                    {
                                    array_push($fields,["key" => $key,'name' => $item]);
                                    }
                                    }

                            }


                        @endphp
                        <form action="{{route('voyager.'.$dataType->slug.'.index')}}" style="display:flex;align-items: center;">
                            <div class="form-group" style="margin-bottom:0px;">
                                <select class="form-control" name="s">
                                    <option value="">Todos</option>
                                    @foreach($fields as $key => $field)
                                        <option @if(request()->has('s') and request('s') == $field['name']['id']) selected="selected" @endif value="{{$field['name']['id']}}">{{$field['name']['name']}}</option>
                                        {{--                    <div style="width:100%;padding: 10px;height:128px;">--}}
                                        {{--                        <a class="filter-card"--}}
                                        {{--                           href="{{route('voyager.'.$dataType->slug.'.index',['key' => $filterBy,'filter' => 'contains','s' => $field['name']['name']])}}"--}}
                                        {{--                           style="font-family: 'Poppins', sans-serif;font-weight: 400;font-size:20px;--}}
                                        {{--                           @if(request()->has('s') and request('s') == $field['key']) background:white; @else background:rgba(255,255,255,0.41); @endif;width:100%;height:100%;box-shadow: 0 2px 10px rgba(0,0,0,.05);display: flex;flex-direction: column;">--}}
                                        {{--                            <div class="filter-color"--}}
                                        {{--                                 style="width:100%;height:4px; background:{{$colors[$key]}}; @if(request()->has('s') and request('s') == $field['key']) opacity: 1; @else opacity: 0; @endif"></div>--}}

                                        {{--                            <div style="width:100%;height: 100%;display:flex;align-items: center;justify-content: center;flex-direction: column;">--}}
                                        {{--                                <div style="color:#555555">--}}
                                        {{--                                    {{ ucfirst($field['name']['name']) }}--}}
                                        {{--                                </div>--}}

                                        {{--                                <div style="color:{{$colors[$key]}};font-size:23px;font-weight: 600">--}}
                                        {{--                                    {{sizeof(app('\Viaativa\Viaroot\Models\Page')->where('page_category_id',$field['key']+1)->get())}}--}}
                                        {{--                                </div>--}}
                                        {{--                            </div>--}}
                                        {{--                        </a>--}}
                                        {{--                    </div>--}}
                                    @endforeach
                                </select>
                            </div>
                            <input type="submit" class="btn btn-primary" style="margin:0px;margin-left:10px" value="Filtrar">
                        </form>
                    </div>
                </div>
            </div>
            @foreach($dataTypeContent as $pageCategoryKey => $pageCategory)
                @php
                    $pages = \Viaativa\Viaroot\Models\Page::where('page_category_id',$pageCategory->id)->get();
                    $subDataType = \TCG\Voyager\Models\DataType::where('slug','pages')->first();
                @endphp

                @if(((!request()->has('s') or request('s') == $pageCategory->id) or request('s') == ""))
                    <div class="col-md-12">
                        <div class="panel panel-bordered">
                            <div class="panel-body">
                                <div style="display:flex;align-items: center;">
                                <h3>
                                    @php
                                        $categoryBread = $pageCategory->breadcrumbs('array')
                                    @endphp
                                @foreach($categoryBread as $key => $crumb)
                                {{$crumb->name}} @if($key < sizeof($categoryBread)-1) <i class="fas fa-angle-right"></i> @endif
                                @endforeach
                                </h3>
                                    <div style="margin-left:auto;">
                                    <a class="btn btn-via" style="background:#0f447a;padding: 4px 12px;color:white;" href="{{route('voyager.page-categories.edit',['id' => $pageCategory->id])}}">Editar</a>
                                    <a class="btn btn-via" style="background:#0f447a;padding: 4px 12px;color:white;" href="{{route('voyager.page-categories.show',['id' => $pageCategory->id])}}">Informações</a>
                                    </div>
                                </div>
                                <hr>
                                @if ($isServerSide)
                                    <form method="get" class="form-search">
                                        <div id="search-input">
                                            <select id="search_key" name="key">
                                                @foreach($searchable as $key)
                                                    <option value="{{ $key }}" @if($search->key == $key || (empty($search->key) && $key == $defaultSearchKey)){{ 'selected' }}@endif>{{ ucwords(str_replace('_', ' ', $key)) }}</option>
                                                @endforeach
                                            </select>
                                            <select id="filter" name="filter">
                                                <option value="contains" @if($search->filter == "contains"){{ 'selected' }}@endif>contains</option>
                                                <option value="equals" @if($search->filter == "equals"){{ 'selected' }}@endif>=</option>
                                            </select>
                                            <div class="input-group col-md-12">
                                                <input type="text" class="form-control" placeholder="{{ __('voyager::generic.search') }}" name="s" value="{{ $search->value }}">
                                                <span class="input-group-btn">
                                            <button class="btn btn-info btn-lg" type="submit">
                                                <i class="voyager-search"></i>
                                            </button>
                                        </span>
                                            </div>
                                        </div>
                                        @if (Request::has('sort_order') && Request::has('order_by'))
                                            <input type="hidden" name="sort_order" value="{{ Request::get('sort_order') }}">
                                            <input type="hidden" name="order_by" value="{{ Request::get('order_by') }}">
                                        @endif
                                    </form>
                                @endif
                                <div class="table-responsive">
                                    <table id="dataTable" class="table table-hover">
                                        <thead>
                                        <tr>
                                            @can('delete',app($subDataType->model_name))
                                                <th>
                                                    <input type="checkbox" class="select_all">
                                                </th>
                                            @endcan
                                            @foreach($subDataType->browseRows as $row)
                                                <th>
                                                    @if ($isServerSide)
                                                        <a href="{{ $row->sortByUrl($orderBy, $sortOrder) }}">
                                                            @endif
                                                            {{ $row->display_name }}
                                                            @if ($isServerSide)
                                                                @if ($row->isCurrentSortField($orderBy))
                                                                    @if ($sortOrder == 'asc')
                                                                        <i class="voyager-angle-up pull-right"></i>
                                                                    @else
                                                                        <i class="voyager-angle-down pull-right"></i>
                                                                    @endif
                                                                @endif
                                                        </a>
                                                    @endif
                                                </th>
                                            @endforeach
                                            <th class="actions text-right">{{ __('voyager::generic.actions') }}</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        @foreach($pages as $data)
                                            <tr>
                                                @can('delete',app($subDataType->model_name))
                                                    <td>
                                                        <input type="checkbox" name="row_id" id="checkbox_{{ $data->getKey() }}" value="{{ $data->getKey() }}">
                                                    </td>
                                                @endcan
                                                @foreach($subDataType->browseRows as $row)
                                                    @php
                                                        if ($data->{$row->field.'_browse'}) {
                                                            $data->{$row->field} = $data->{$row->field.'_browse'};
                                                        }
                                                    @endphp
                                                    <td row="{{$row->type}}">
                                                        @if (isset($row->details->view))
                                                            @include($row->details->view, ['row' => $row, 'dataType' => $subDataType, 'dataTypeContent' => $subDataTypeContent, 'content' => $data->{$row->field}, 'action' => 'browse'])
                                                        @elseif($row->type == 'image')
                                                            <img src="@if( !filter_var($data->{$row->field}, FILTER_VALIDATE_URL)){{ Voyager::image( $data->{$row->field} ) }}@else{{ $data->{$row->field} }}@endif" style="width:100px">
                                                        @elseif($row->type == 'relationship')
                                                            @include('voyager::formfields.relationship', ['view' => 'browse','options' => $row->details])
                                                        @elseif($row->type == 'select_multiple')
                                                            @if(property_exists($row->details, 'relationship'))

                                                                @foreach($data->{$row->field} as $item)
                                                                    {{ $item->{$row->field} }}
                                                                @endforeach

                                                            @elseif(property_exists($row->details, 'options'))
                                                                @if (!empty(json_decode($data->{$row->field})))
                                                                    @foreach(json_decode($data->{$row->field}) as $item)
                                                                        @if (@$row->details->options->{$item})
                                                                            {{ $row->details->options->{$item} . (!$loop->last ? ', ' : '') }}
                                                                        @endif
                                                                    @endforeach
                                                                @else
                                                                    {{ __('voyager::generic.none') }}
                                                                @endif
                                                            @endif

                                                        @elseif($row->type == 'multiple_checkbox' && property_exists($row->details, 'options'))
                                                            @if (@count(json_decode($data->{$row->field})) > 0)
                                                                @foreach(json_decode($data->{$row->field}) as $item)
                                                                    @if (@$row->details->options->{$item})
                                                                        {{ $row->details->options->{$item} . (!$loop->last ? ', ' : '') }}
                                                                    @endif
                                                                @endforeach
                                                            @else
                                                                {{ __('voyager::generic.none') }}
                                                            @endif

                                                        @elseif(($row->type == 'select_dropdown' || $row->type == 'radio_btn'))
                                                            @if($row->field == "page_category_id")
                                                                {{\Viaativa\Viaroot\Models\PageCategory::where('id',$data->{$row->field})->first()->name}}
                                                            @else
                                                                {!! $row->details->options->{$data->{$row->field}} ?? '' !!}
                                                            @endif

                                                        @elseif($row->type == 'date' || $row->type == 'timestamp')
                                                            {{ property_exists($row->details, 'format') ? \Carbon\Carbon::parse($data->{$row->field})->formatLocalized($row->details->format) : $data->{$row->field} }}
                                                        @elseif($row->type == 'checkbox')
                                                            @if(property_exists($row->details, 'on') && property_exists($row->details, 'off'))
                                                                @if($data->{$row->field})
                                                                    <span class="label label-info">{{ $row->details->on }}</span>
                                                                @else
                                                                    <span class="label label-primary">{{ $row->details->off }}</span>
                                                                @endif
                                                            @else
                                                                {{ $data->{$row->field} }}
                                                            @endif
                                                        @elseif($row->type == 'color')
                                                            <span class="badge badge-lg" style="background-color: {{ $data->{$row->field} }}">{{ $data->{$row->field} }}</span>
                                                        @elseif($row->type == 'text')
                                                            @include('voyager::multilingual.input-hidden-bread-browse')
                                                            <div>{{ mb_strlen( $data->{$row->field} ) > 200 ? mb_substr($data->{$row->field}, 0, 200) . ' ...' : $data->{$row->field} }}</div>
                                                        @elseif($row->type == 'text_area')
                                                            @include('voyager::multilingual.input-hidden-bread-browse')
                                                            <div>{{ mb_strlen( $data->{$row->field} ) > 200 ? mb_substr($data->{$row->field}, 0, 200) . ' ...' : $data->{$row->field} }}</div>
                                                        @elseif($row->type == 'file' && !empty($data->{$row->field}) )
                                                            @include('voyager::multilingual.input-hidden-bread-browse')
                                                            @if(json_decode($data->{$row->field}) !== null)
                                                                @foreach(json_decode($data->{$row->field}) as $file)
                                                                    <a href="{{ Storage::disk(config('voyager.storage.disk'))->url($file->download_link) ?: '' }}" target="_blank">
                                                                        {{ $file->original_name ?: '' }}
                                                                    </a>
                                                                    <br/>
                                                                @endforeach
                                                            @else
                                                                <a href="{{ Storage::disk(config('voyager.storage.disk'))->url($data->{$row->field}) }}" target="_blank">
                                                                    Download
                                                                </a>
                                                            @endif
                                                        @elseif($row->type == 'rich_text_box')
                                                            @include('voyager::multilingual.input-hidden-bread-browse')
                                                            <div>{{ mb_strlen( strip_tags($data->{$row->field}, '<b><i><u>') ) > 200 ? mb_substr(strip_tags($data->{$row->field}, '<b><i><u>'), 0, 200) . ' ...' : strip_tags($data->{$row->field}, '<b><i><u>') }}</div>
                                                        @elseif($row->type == 'coordinates')
                                                            @include('voyager::partials.coordinates-static-image')
                                                        @elseif($row->type == 'multiple_images')
                                                            @php $images = json_decode($data->{$row->field}); @endphp
                                                            @if($images)
                                                                @php $images = array_slice($images, 0, 3); @endphp
                                                                @foreach($images as $image)
                                                                    <img src="@if( !filter_var($image, FILTER_VALIDATE_URL)){{ Voyager::image( $image ) }}@else{{ $image }}@endif" style="width:50px">
                                                                @endforeach
                                                            @endif
                                                        @elseif($row->type == 'media_picker')
                                                            @php
                                                                if (is_array($data->{$row->field})) {
                                                                    $files = $data->{$row->field};
                                                                } else {
                                                                    $files = json_decode($data->{$row->field});
                                                                }
                                                            @endphp
                                                            @if ($files)
                                                                @if (property_exists($row->details, 'show_as_images') && $row->details->show_as_images)
                                                                    @foreach (array_slice($files, 0, 3) as $file)
                                                                        <img src="@if( !filter_var($file, FILTER_VALIDATE_URL)){{ Voyager::image( $file ) }}@else{{ $file }}@endif" style="width:50px">
                                                                    @endforeach
                                                                @else
                                                                    <ul>
                                                                        @foreach (array_slice($files, 0, 3) as $file)
                                                                            <li>{{ $file }}</li>
                                                                        @endforeach
                                                                    </ul>
                                                                @endif
                                                                @if (count($files) > 3)
                                                                    {{ __('voyager::media.files_more', ['count' => (count($files) - 3)]) }}
                                                                @endif
                                                            @elseif (is_array($files) && count($files) == 0)
                                                                {{ trans_choice('voyager::media.files', 0) }}
                                                            @elseif ($data->{$row->field} != '')
                                                                @if (property_exists($row->details, 'show_as_images') && $row->details->show_as_images)
                                                                    <img src="@if( !filter_var($data->{$row->field}, FILTER_VALIDATE_URL)){{ Voyager::image( $data->{$row->field} ) }}@else{{ $data->{$row->field} }}@endif" style="width:50px">
                                                                @else
                                                                    {{ $data->{$row->field} }}
                                                                @endif
                                                            @else
                                                                {{ trans_choice('voyager::media.files', 0) }}
                                                            @endif
                                                        @else
                                                            @include('voyager::multilingual.input-hidden-bread-browse')
                                                            <span>{{ $data->{$row->field} }}</span>
                                                        @endif
                                                    </td>
                                                @endforeach
                                                <td class="no-sort no-click" id="bread-actions">
                                                    @if(check_permission('delete','pages',false))
                                                        <a href="javascript:;" style="background:#0f447a;padding: 4px 12px;" title="{{ __('voyager::generic.delete') }}" class="btn btn-sm btn-danger pull-right delete" data-id="{{ $data->{$data->getKeyName()} }}" id="delete-{{ $data->{$data->getKeyName()} }}">
                                                            <i class="voyager-trash" style="color:white;font-size:16px;"></i>
                                                        </a>
                                                    @endif
                                                    @if(check_permission('edit','pages',false))
                                                        <a href="{{ route('voyager.page-blocks.edit', $data->{$data->getKeyName()}) }}"  style="background:#0f447a;padding: 4px 12px;" title="Editar Blocos" class="btn btn-sm btn-primary pull-right edit">
                                                            <i class="voyager-edit" style="color:white;font-size:16px;" ></i>
                                                        </a>
                                                        <a href="{{ route('voyager.pages.edit', $data->{$data->getKeyName()}) }}" style="background:#0f447a;padding: 4px 12px;" title="{{ __('voyager::generic.settings') }}" class="btn btn-sm btn-primary pull-right edit">
                                                            <i class="voyager-settings" style="color:white;font-size:16px;" ></i>
                                                        </a>
                                                    @endif
                                                    @if(check_permission('read','pages',false))
                                                        <a href="{{ route('voyager.pages.show', $data->{$data->getKeyName()}) }}" style="background:#0f447a;padding: 4px 12px;" title="{{ __('voyager::generic.view') }}" class="btn btn-sm btn-warning pull-right">
                                                            <i class="voyager-eye" style="color:white;font-size:16px;" ></i>
                                                        </a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @if ($isServerSide)
                                    <div class="pull-left">
                                        <div role="status" class="show-res" aria-live="polite">{{ trans_choice(
                                    'voyager::generic.showing_entries', $subDataTypeContent->total(), [
                                        'from' => $subDataTypeContent->firstItem(),
                                        'to' => $subDataTypeContent->lastItem(),
                                        'all' => $subDataTypeContent->total()
                                    ]) }}</div>
                                    </div>
                                    <div class="pull-right">
                                        {{ $subDataTypeContent->appends([
                                            's' => $search->value,
                                            'filter' => $search->filter,
                                            'key' => $search->key,
                                            'order_by' => $orderBy,
                                            'sort_order' => $sortOrder,
                                            'showSoftDeleted' => $showSoftDeleted,
                                        ])->links() }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
            @php
                $pages = \Viaativa\Viaroot\Models\Page::where('page_category_id',null)->get();
                $subDataType = \TCG\Voyager\Models\DataType::where('slug','pages')->first();
            @endphp
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body">
                        <h3>Sem Categoria</h3>
                        <hr>
                        @if ($isServerSide)
                            <form method="get" class="form-search">
                                <div id="search-input">
                                    <select id="search_key" name="key">
                                        @foreach($searchable as $key)
                                            <option value="{{ $key }}" @if($search->key == $key || (empty($search->key) && $key == $defaultSearchKey)){{ 'selected' }}@endif>{{ ucwords(str_replace('_', ' ', $key)) }}</option>
                                        @endforeach
                                    </select>
                                    <select id="filter" name="filter">
                                        <option value="contains" @if($search->filter == "contains"){{ 'selected' }}@endif>contains</option>
                                        <option value="equals" @if($search->filter == "equals"){{ 'selected' }}@endif>=</option>
                                    </select>
                                    <div class="input-group col-md-12">
                                        <input type="text" class="form-control" placeholder="{{ __('voyager::generic.search') }}" name="s" value="{{ $search->value }}">
                                        <span class="input-group-btn">
                                            <button class="btn btn-info btn-lg" type="submit">
                                                <i class="voyager-search"></i>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                                @if (Request::has('sort_order') && Request::has('order_by'))
                                    <input type="hidden" name="sort_order" value="{{ Request::get('sort_order') }}">
                                    <input type="hidden" name="order_by" value="{{ Request::get('order_by') }}">
                                @endif
                            </form>
                        @endif
                        <div class="table-responsive">
                            <table id="dataTable" class="table table-hover">
                                <thead>
                                <tr>
                                    @can('delete',app($subDataType->model_name))
                                        <th>
                                            <input type="checkbox" class="select_all">
                                        </th>
                                    @endcan
                                    @foreach($subDataType->browseRows as $row)
                                        <th>
                                            @if ($isServerSide)
                                                <a href="{{ $row->sortByUrl($orderBy, $sortOrder) }}">
                                                    @endif
                                                    {{ $row->display_name }}
                                                    @if ($isServerSide)
                                                        @if ($row->isCurrentSortField($orderBy))
                                                            @if ($sortOrder == 'asc')
                                                                <i class="voyager-angle-up pull-right"></i>
                                                            @else
                                                                <i class="voyager-angle-down pull-right"></i>
                                                            @endif
                                                        @endif
                                                </a>
                                            @endif
                                        </th>
                                    @endforeach
                                    <th class="actions text-right">{{ __('voyager::generic.actions') }}</th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($pages as $data)
                                    <tr>
                                        @can('delete',app($subDataType->model_name))
                                            <td>
                                                <input type="checkbox" name="row_id" id="checkbox_{{ $data->getKey() }}" value="{{ $data->getKey() }}">
                                            </td>
                                        @endcan
                                        @foreach($subDataType->browseRows as $row)
                                            @php
                                                if ($data->{$row->field.'_browse'}) {
                                                    $data->{$row->field} = $data->{$row->field.'_browse'};
                                                }
                                            @endphp
                                            <td row="{{$row->type}}">
                                                @if (isset($row->details->view))
                                                    @include($row->details->view, ['row' => $row, 'dataType' => $subDataType, 'dataTypeContent' => $subDataTypeContent, 'content' => $data->{$row->field}, 'action' => 'browse'])
                                                @elseif($row->type == 'image')
                                                    <img src="@if( !filter_var($data->{$row->field}, FILTER_VALIDATE_URL)){{ Voyager::image( $data->{$row->field} ) }}@else{{ $data->{$row->field} }}@endif" style="width:100px">
                                                @elseif($row->type == 'relationship')
                                                    @include('voyager::formfields.relationship', ['view' => 'browse','options' => $row->details])
                                                @elseif($row->type == 'select_multiple')
                                                    @if(property_exists($row->details, 'relationship'))

                                                        @foreach($data->{$row->field} as $item)
                                                            {{ $item->{$row->field} }}
                                                        @endforeach

                                                    @elseif(property_exists($row->details, 'options'))
                                                        @if (!empty(json_decode($data->{$row->field})))
                                                            @foreach(json_decode($data->{$row->field}) as $item)
                                                                @if (@$row->details->options->{$item})
                                                                    {{ $row->details->options->{$item} . (!$loop->last ? ', ' : '') }}
                                                                @endif
                                                            @endforeach
                                                        @else
                                                            {{ __('voyager::generic.none') }}
                                                        @endif
                                                    @endif

                                                @elseif($row->type == 'multiple_checkbox' && property_exists($row->details, 'options'))
                                                    @if (@count(json_decode($data->{$row->field})) > 0)
                                                        @foreach(json_decode($data->{$row->field}) as $item)
                                                            @if (@$row->details->options->{$item})
                                                                {{ $row->details->options->{$item} . (!$loop->last ? ', ' : '') }}
                                                            @endif
                                                        @endforeach
                                                    @else
                                                        {{ __('voyager::generic.none') }}
                                                    @endif

                                                @elseif(($row->type == 'select_dropdown' || $row->type == 'radio_btn'))
                                                    @if($row->field == "page_category_id")
                                                        -
                                                    @else
                                                        {!! $row->details->options->{$data->{$row->field}} ?? '' !!}
                                                    @endif

                                                @elseif($row->type == 'date' || $row->type == 'timestamp')
                                                    {{ property_exists($row->details, 'format') ? \Carbon\Carbon::parse($data->{$row->field})->formatLocalized($row->details->format) : $data->{$row->field} }}
                                                @elseif($row->type == 'checkbox')
                                                    @if(property_exists($row->details, 'on') && property_exists($row->details, 'off'))
                                                        @if($data->{$row->field})
                                                            <span class="label label-info">{{ $row->details->on }}</span>
                                                        @else
                                                            <span class="label label-primary">{{ $row->details->off }}</span>
                                                        @endif
                                                    @else
                                                        {{ $data->{$row->field} }}
                                                    @endif
                                                @elseif($row->type == 'color')
                                                    <span class="badge badge-lg" style="background-color: {{ $data->{$row->field} }}">{{ $data->{$row->field} }}</span>
                                                @elseif($row->type == 'text')
                                                    @include('voyager::multilingual.input-hidden-bread-browse')
                                                    <div>{{ mb_strlen( $data->{$row->field} ) > 200 ? mb_substr($data->{$row->field}, 0, 200) . ' ...' : $data->{$row->field} }}</div>
                                                @elseif($row->type == 'text_area')
                                                    @include('voyager::multilingual.input-hidden-bread-browse')
                                                    <div>{{ mb_strlen( $data->{$row->field} ) > 200 ? mb_substr($data->{$row->field}, 0, 200) . ' ...' : $data->{$row->field} }}</div>
                                                @elseif($row->type == 'file' && !empty($data->{$row->field}) )
                                                    @include('voyager::multilingual.input-hidden-bread-browse')
                                                    @if(json_decode($data->{$row->field}) !== null)
                                                        @foreach(json_decode($data->{$row->field}) as $file)
                                                            <a href="{{ Storage::disk(config('voyager.storage.disk'))->url($file->download_link) ?: '' }}" target="_blank">
                                                                {{ $file->original_name ?: '' }}
                                                            </a>
                                                            <br/>
                                                        @endforeach
                                                    @else
                                                        <a href="{{ Storage::disk(config('voyager.storage.disk'))->url($data->{$row->field}) }}" target="_blank">
                                                            Download
                                                        </a>
                                                    @endif
                                                @elseif($row->type == 'rich_text_box')
                                                    @include('voyager::multilingual.input-hidden-bread-browse')
                                                    <div>{{ mb_strlen( strip_tags($data->{$row->field}, '<b><i><u>') ) > 200 ? mb_substr(strip_tags($data->{$row->field}, '<b><i><u>'), 0, 200) . ' ...' : strip_tags($data->{$row->field}, '<b><i><u>') }}</div>
                                                @elseif($row->type == 'coordinates')
                                                    @include('voyager::partials.coordinates-static-image')
                                                @elseif($row->type == 'multiple_images')
                                                    @php $images = json_decode($data->{$row->field}); @endphp
                                                    @if($images)
                                                        @php $images = array_slice($images, 0, 3); @endphp
                                                        @foreach($images as $image)
                                                            <img src="@if( !filter_var($image, FILTER_VALIDATE_URL)){{ Voyager::image( $image ) }}@else{{ $image }}@endif" style="width:50px">
                                                        @endforeach
                                                    @endif
                                                @elseif($row->type == 'media_picker')
                                                    @php
                                                        if (is_array($data->{$row->field})) {
                                                            $files = $data->{$row->field};
                                                        } else {
                                                            $files = json_decode($data->{$row->field});
                                                        }
                                                    @endphp
                                                    @if ($files)
                                                        @if (property_exists($row->details, 'show_as_images') && $row->details->show_as_images)
                                                            @foreach (array_slice($files, 0, 3) as $file)
                                                                <img src="@if( !filter_var($file, FILTER_VALIDATE_URL)){{ Voyager::image( $file ) }}@else{{ $file }}@endif" style="width:50px">
                                                            @endforeach
                                                        @else
                                                            <ul>
                                                                @foreach (array_slice($files, 0, 3) as $file)
                                                                    <li>{{ $file }}</li>
                                                                @endforeach
                                                            </ul>
                                                        @endif
                                                        @if (count($files) > 3)
                                                            {{ __('voyager::media.files_more', ['count' => (count($files) - 3)]) }}
                                                        @endif
                                                    @elseif (is_array($files) && count($files) == 0)
                                                        {{ trans_choice('voyager::media.files', 0) }}
                                                    @elseif ($data->{$row->field} != '')
                                                        @if (property_exists($row->details, 'show_as_images') && $row->details->show_as_images)
                                                            <img src="@if( !filter_var($data->{$row->field}, FILTER_VALIDATE_URL)){{ Voyager::image( $data->{$row->field} ) }}@else{{ $data->{$row->field} }}@endif" style="width:50px">
                                                        @else
                                                            {{ $data->{$row->field} }}
                                                        @endif
                                                    @else
                                                        {{ trans_choice('voyager::media.files', 0) }}
                                                    @endif
                                                @else
                                                    @include('voyager::multilingual.input-hidden-bread-browse')
                                                    <span>{{ $data->{$row->field} }}</span>
                                                @endif
                                            </td>
                                        @endforeach
                                        <td class="no-sort no-click" id="bread-actions">
                                            @if(check_permission('delete','pages',false))
                                                <a href="javascript:;" style="background:#0f447a;padding: 4px 12px;" title="{{ __('voyager::generic.delete') }}" class="btn btn-sm btn-danger pull-right delete" data-id="{{ $data->{$data->getKeyName()} }}" id="delete-{{ $data->{$data->getKeyName()} }}">
                                                    <i class="voyager-trash" style="color:white;font-size:16px;"></i>
                                                </a>
                                            @endif
                                            @if(check_permission('edit','pages',false))
                                                <a href="{{ route('voyager.page-blocks.edit', $data->{$data->getKeyName()}) }}"  style="background:#0f447a;padding: 4px 12px;" title="Editar Blocos" class="btn btn-sm btn-primary pull-right edit">
                                                    <i class="voyager-edit" style="color:white;font-size:16px;" ></i>
                                                </a>
                                                <a href="{{ route('voyager.pages.edit', $data->{$data->getKeyName()}) }}" style="background:#0f447a;padding: 4px 12px;" title="{{ __('voyager::generic.settings') }}" class="btn btn-sm btn-primary pull-right edit">
                                                    <i class="voyager-settings" style="color:white;font-size:16px;" ></i>
                                                </a>
                                            @endif
                                            @if(check_permission('read','pages',false))
                                                <a href="{{ route('voyager.pages.show', $data->{$data->getKeyName()}) }}" style="background:#0f447a;padding: 4px 12px;" title="{{ __('voyager::generic.view') }}" class="btn btn-sm btn-warning pull-right">
                                                    <i class="voyager-eye" style="color:white;font-size:16px;" ></i>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if ($isServerSide)
                            <div class="pull-left">
                                <div role="status" class="show-res" aria-live="polite">{{ trans_choice(
                                    'voyager::generic.showing_entries', $subDataTypeContent->total(), [
                                        'from' => $subDataTypeContent->firstItem(),
                                        'to' => $subDataTypeContent->lastItem(),
                                        'all' => $subDataTypeContent->total()
                                    ]) }}</div>
                            </div>
                            <div class="pull-right">
                                {{ $subDataTypeContent->appends([
                                    's' => $search->value,
                                    'filter' => $search->filter,
                                    'key' => $search->key,
                                    'order_by' => $orderBy,
                                    'sort_order' => $sortOrder,
                                    'showSoftDeleted' => $showSoftDeleted,
                                ])->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Single delete modal --}}
    <div class="modal modal-danger fade" tabindex="-1" id="delete_modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('voyager::generic.close') }}"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="voyager-trash"></i> {{ __('voyager::generic.delete_question') }} {{ strtolower($dataType->display_name_singular) }}?</h4>
                </div>
                <div class="modal-footer">
                    <form action="#" id="delete_form" method="POST">
                        {{ method_field('DELETE') }}
                        {{ csrf_field() }}
                        <input type="submit" class="btn btn-danger pull-right delete-confirm" value="{{ __('voyager::generic.delete_confirm') }}">
                    </form>
                    <button type="button" class="btn btn-default pull-right" data-dismiss="modal">{{ __('voyager::generic.cancel') }}</button>
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
            var table = $('.col-md-12 table').each(function() {
                    $(this).DataTable({!! json_encode(
                    array_merge([
                        "order" => $orderColumn,
                        "language" => __('voyager::datatable'),
                        "columnDefs" => [['targets' => -1, 'searchable' =>  false, 'orderable' => false]],
                    ],
                    config('voyager.dashboard.data_tables', []))
                , true) !!})
                });
            @else
            $('#search-input select').select2({
                minimumResultsForSearch: Infinity
            });
            @endif

            @if ($isModelTranslatable)
            $('.side-body').multilingual();
            //Reinitialise the multilingual features when they change tab
            $('#dataTable').on('draw.dt', function(){
                $('.side-body').data('multilingual').init();
            })
            @endif
            $('.select_all').on('click', function(e) {
                $('input[name="row_id"]').prop('checked', $(this).prop('checked'));
            });
        });


        var deleteFormAction;
        $('td').on('click', '.delete', function (e) {
            $('#delete_form')[0].action = '{{ route('voyager.pages.destroy', ['id' => '__id']) }}'.replace('__id', $(this).data('id'));
            $('#delete_modal').modal('show');
        });

        @if($usesSoftDeletes)
        @php
            $params = [
                's' => $search->value,
                'filter' => $search->filter,
                'key' => $search->key,
                'order_by' => $orderBy,
                'sort_order' => $sortOrder,
            ];
        @endphp
        $(function() {
            $('#show_soft_deletes').change(function() {
                if ($(this).prop('checked')) {
                    $('#dataTable').before('<a id="redir" href="{{ (route('voyager.'.$dataType->slug.'.index', array_merge($params, ['showSoftDeleted' => 1]), true)) }}"></a>');
                }else{
                    $('#dataTable').before('<a id="redir" href="{{ (route('voyager.'.$dataType->slug.'.index', array_merge($params, ['showSoftDeleted' => 0]), true)) }}"></a>');
                }

                $('#redir')[0].click();
            })
        })
        @endif
        $('input[name="row_id"]').on('change', function () {
            var ids = [];
            $('input[name="row_id"]').each(function() {
                if ($(this).is(':checked')) {
                    ids.push($(this).val());
                }
            });
            $('.selected_ids').val(ids);
        });
    </script>
@stop
