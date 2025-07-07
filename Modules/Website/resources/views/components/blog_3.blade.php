<div class="col-xl-4 col-md-6 wow fadeInUp" data-wow-duration="1.5s">
    <div class="single_blog_3 single_blog_2">
        <a href="{{ route('website.blog-details', $blog->slug) }}" class="single_blog_3_img single_blog_2_img">
            <img loading="lazy" src="{{ asset($blog->image) }}" alt="blog" class="img-fluid w-100">
        </a>
        @include('website::components.blog-item')
    </div>
</div>
