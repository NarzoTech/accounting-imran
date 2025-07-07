<div class="single_agent">
    <div class="single_agent_img">
        <img loading="lazy" src="{{ $agent->image_url }}" alt="{{ $agent->name }}" class="img-fluid w-100">
        <div class="single_agent_overly">
            <p>{{ $agent->properties->count() }} {{ __('Properties') }}</p>
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
    <div class="agent_text">
        <div class="agent_name">
            <a class="item_title" href="{{ route('website.agent-details', $agent->slug) }}">
                {{ $agent->name }} </a>
            <p>{{ $agent->designation }}</p>
        </div>
        <ul class="agent_contact">
            <li><a href="callto:{{ $agent->phone }}"><i class="fas fa-phone-alt"></i>{{ $agent->phone }}</a>
            </li>
            <li><a href="mailto:{{ $agent->email }}"><i class="fas fa-envelope"></i>{{ $agent->email }}</a></li>
        </ul>
    </div>
</div>
