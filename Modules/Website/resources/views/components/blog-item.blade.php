<div class=" {{ THEME == 1 ? 'blog_text' : 'single_blog_2_text' }}">
    <ul class="d-flex flex-wrap">
        <li>
            <span><img loading="lazy" src="{{ asset('website/') }}/assets/images/calender.png" alt="icon"
                    class="img-fluid w-100"></span>
            {{ $blog->updated_at->format('F j, Y') }}
        </li>
        <li>
            <span><img loading="lazy" src="{{ asset('website/') }}/assets/images/massage.png" alt="icon"
                    class="img-fluid w-100"></span>
            {{ $blog->comments->count() }} {{ __('Comments') }}
        </li>
    </ul>
    <a class="item_title" href="{{ route('website.blog-details', $blog->slug) }}">{{ $blog->title }}</a>
    <a class="read_btn" href="{{ route('website.blog-details', $blog->slug) }}">{{ __('Read More') }}<i
            class="fas fa-arrow-right" aria-hidden="true"></i></a>
</div>
