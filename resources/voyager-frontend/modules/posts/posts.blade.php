@extends('voyager-frontend::layouts.default')
@section('meta_title', 'Blog Posts')
@section('meta_description', 'Blog Posts')
@section('page_title', 'Blog Posts')

@section('content')
    @php
        $mainBlockData = null;
        if(isset($blocks))
        {
        foreach($blocks as $key => $block)
        {
            if($block->template_raw->blog->as == "main")
            {
            foreach($block->template_raw->fields as $key_field => $field)
            {
            if(!property_exists($block->data,$key_field))
                {
                $block->data->$key_field = "";
                }
            }
            unset($blocks[$key]);
            $mainBlock = $block;
            $mainBlockData = $block->data;
            $blockTypes = (new BlockTypesData($mainBlockData, $block->path,['bg_image']));
            }
        }
        }
    $paginator = new \Viaativa\Viaroot\Http\Controllers\PageViaController();
    @endphp
    @if(isset($blocks))
    {!! \Viaativa\Viaroot\Http\Controllers\PageViaController::printBlogView($mainBlock,['posts'=>$posts,"blockData" => $mainBlockData,"blocks" => $blocks]) !!}
{{--        {{$paginator->getPage('blog',['posts'=>$posts,"blockData" => $mainBlockData,"blocks" => $blocks])}}--}}
    @endif
{{--    @include('voyager-frontend::partials.page-title')--}}

{{--    <div class="vspace-2"></div>--}}
{{--    @if ($featuredPost)--}}
{{--        <div class="grid-container">--}}
{{--            <div class="grid-x grid-padding-x">--}}
{{--                <div class="cell small-12">--}}
{{--                    <div class="block-image-text">--}}
{{--                        @if (!empty($featuredPost->image))--}}
{{--                            <a href="{{ route('voyager-frontend.posts.post', ['slug' => $featuredPost->slug]) }}" class="block-image-text-img">--}}
{{--                                <img src="{{ imageUrl($featuredPost->image, 585, 390) }}">--}}
{{--                            </a> <!-- /.block-image-text-img -->--}}
{{--                        @endif--}}

{{--                        <div class="block-image-text-content">--}}
{{--                            @if (!empty($featuredPost->title))--}}
{{--                                    <h4>{{ $featuredPost->title or '' }}</h4>--}}
{{--                            @endif--}}

{{--                            @if (!empty($featuredPost->excerpt))--}}
{{--                                    <p>{{ $featuredPost->excerpt or '' }}</p>--}}
{{--                            @endif--}}

{{--                            <a href="{{ route('voyager-frontend.posts.post', ['slug' => $featuredPost->slug]) }}" class="button round">--}}
{{--                                    Read Post--}}
{{--                            </a>--}}
{{--                        </div> <!-- /.block-image-text-content -->--}}
{{--                    </div> <!-- /.block-image-text -->--}}
{{--                </div> <!-- /.cell -->--}}
{{--            </div> <!-- /.grid-x -->--}}
{{--        </div> <!-- /.grid-container -->--}}
{{--    @endif--}}

{{--    @include('voyager-frontend::modules.posts.posts-grid')--}}
@endsection
