@extends('themes.clean-blog.layouts.default')

@section('header')
    <header class="intro-header" style="background-image: url({{ $post->image_url or 'http://lorempixel.com/400/200'}})">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                    <div class="post-heading">
                        <h1>{{ $post->title }}</h1>
                        <h2 class="subheading">{{ $post->description }}</h2>
                        <span class="meta">貼文: <a href="#">{{ $post->owner->name }}</a> {{ $post->created_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </header>

@endsection

@section('content')
    <!-- Post Content -->
    <article>
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                    {!! ParsedownExtra::instance()->parse($post->content) !!}
                </div>
            </div>
        </div>
    </article>
    @endsection