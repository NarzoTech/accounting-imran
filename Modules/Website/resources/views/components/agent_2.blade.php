<div class="single_agent_2">
    <div class="agent_2_img">
        <img loading="lazy" src="{{ $agent->image_url }}" alt="{{ $agent->name }}" class="img-fluid w-100">
        <div class="agent_img_overly">
            <ul class="d-flex flex-wrap">
                @if ($agent->facebook)
                    <li>
                        <a href="{{ $agent->facebook }}"><i class="fab fa-facebook-f" aria-hidden="true"></i></a>
                    </li>
                @endif
                @if ($agent->twitter)
                    <li>
                        <a href="{{ $agent->twitter }}"><i class="fab fa-twitter" aria-hidden="true"></i></a>
                    </li>
                @endif
                @if ($agent->linkedin)
                    <li>
                        <a href="{{ $agent->linkedin }}"><i class="fab fa-linkedin-in" aria-hidden="true"></i></a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
    <div class="agent_2_text d-flex flex-wrap justify-content-between">
        <div class="agent_2_text_left">
            <a class="item_title" href="{{ route('website.agent-details', $agent->slug) }}">
                {{ $agent->name }} </a>
            <p>{{ $agent->designation }}</p>
        </div>
        <div class="agent_2_text_right">
            <a href="{{ route('website.agent-details', $agent->slug) }}"> <i class="fas fa-comment-dots"></i>
            </a>
        </div>
    </div>
</div>
