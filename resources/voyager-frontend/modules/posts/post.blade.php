@extends('voyager-frontend::layouts.default')
@section('meta_title', $post->title)
@section('meta_description', $post->meta_description)
@section('meta_image',Voyager::image($post->image))
@section('page_title', $post->title)
@section('page_subtitle', 'Posted // ' . $post->created_at->format('jS M. Y'))

@section('content')

    @include(setting('admin.blog_post'))

@endsection
