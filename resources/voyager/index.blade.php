@extends('voyager::master')
@php
$widgets = json_decode(\Auth::user()->widgets);
$widgetsConfig = config('viaativa-site.widgets');
@endphp

@section('content')

    <style>
        .add-widget {
            transition: 0.2s all;
        }
        .add-widget:hover {
            background: rgba(248, 248, 248, 0.94) !important;
            cursor: pointer;
        }
    </style>
    <div class="page-content container-fluid">

        @include('voyager::alerts')
        @include('voyager::dimmers')
{{--        @include('voyager::analytics')--}}
    </div>

    <div class="page-content container-fluid">
    <div class="row" style="padding:15px;">
        @if(isset($widgets) and isset($widgetsConfig))
        @foreach($widgets as $key => $widget)
            <div class="@if(isset($widgetsConfig[$widget]['width'])) col-md-{{$widgetsConfig[$widget]['width']}} @else col-md-4 col-sm-6 @endif">
                <div class="panel panel-default">
                    <div class="panel-heading" style="background: #2687e9;color:white;font-weight:800;padding:5px 15px;">{{$widgetsConfig[$widget]['name']}}</div>
                    <div class="panel-body" style="padding:15px;">
                        @include($widgetsConfig[$widget]['view'])
                    </div>
                </div>

            </div>

        @endforeach
        @endif
{{--        <div class="col-md-4 col-sm-6 add-widget">--}}
{{--            <div class="" style="background:rgba(248,248,248,0.42);border-radius:4px;border:4px dashed rgba(128,128,128,0.65);padding:30px;display: flex;align-items: center;justify-content: center;">--}}
{{--                <div style="padding-top:100%;"></div>--}}
{{--                    <i class="fas fa-plus" style="font-size:48px;color: rgba(128,128,128,0.65);"></i>--}}
{{--            </div>--}}
{{--        </div>--}}
    </div>
    </div>
@stop

@section('javascript')


@stop
