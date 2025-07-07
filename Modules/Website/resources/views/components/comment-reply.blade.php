@foreach ($replies as $reply)
    <div class="blog_comment blog_comment_reply d-flex flex-wrap">
        <div class="blog_comment_img">
            <img loading="lazy" src="{{ asset($setting->default_avatar) }}" alt="img" class="img-fluid w-100">
        </div>
        <div class="blog_comment_text">
            <h4>{{ $reply->name }}</h4>
            <span>{{ $comment->created_at->format('M d, Y') }} {{ __('at') }}
                {{ $comment->created_at->format('i:h A') }}</span>
            <p>{{ $reply->comment }}
            </p>
            <a href="#blogCommentForm" data-id="{{ $reply->id }}" class="reply">{{ __('Reply') }}</a>
        </div>
        @if ($reply->children->count() > 0)
            @include('website::components.comment-reply', ['replies' => $reply->children])
        @endif
    </div>
@endforeach
