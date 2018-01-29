@if( count($posts) == 0 )
    <div class="post-preview">
        <h2 class="post-title">
            哎呀，尚無貼文呢 :/
        </h2>
    </div>
@endif
@foreach($posts as $post)
    <div class="post-preview">
        <a href="{{route('web.post', $post->slug)}}">
            <h2 class="post-title">
                {{ $post->title }}
            </h2>
            <h3 class="post-subtitle">
                {{ $post->description }}
            </h3>
        </a>
        <p class="post-meta">貼文: <a href="#">{{ $post->owner->name }}</a> {{ $post->created_at->diffForHumans() }}</p>
    </div>
    <hr>
@endforeach

{{ $posts->render() }}