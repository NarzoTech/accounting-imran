@php
    if (cache()->has('nav_menu')) {
        $mainMenu = cache('nav_menu');
    } else {
        $mainMenu = Cache::rememberForever('nav_menu', function () {
            return \Modules\CustomMenu\app\Models\Menu::with('items')->where('slug', 'main-menu')->first();
        });
    }

    $currentPath = request()->path();
    $query = request()->query();
    if ($query) {
        $currentPath .= '?' . http_build_query($query);
    }

    $currentPath = explode('/', $currentPath);
    $currentPath = end($currentPath);

@endphp
<ul class="navbar-nav m-auto">
    @foreach ($mainMenu->items as $item)
        @php
            $currLink = explode('/', $item->link);
            $currLink = end($currLink);
            $isActive = $currLink === $currentPath;

            // Check if any child is active
            $hasActiveChild = false;
            if ($item->child->count()) {
                foreach ($item->child as $child) {
                    $childLink = explode('/', $child->link);
                    $childLink = end($childLink);
                    if ($childLink === $currentPath) {
                        $hasActiveChild = true;
                        break;
                    }
                }
            }

            $isActive = $isActive || $hasActiveChild;

            $link = url($item->link);
        @endphp
        @if ($item->child->count())
            <li class="nav-item">
                <a class="nav-link {{ $isActive ? 'active' : '' }}" aria-current="page"
                    href="{{ $link }}">{{ $item->label }} <i class="far fa-angle-down"></i></a>
                <ul class="droap_menu">
                    @foreach ($item->child as $child)
                        @php
                            $childLink = last(explode('/', $child->link));
                            $isChildActive = $childLink === $currentPath;
                        @endphp
                        <li>
                            <a class="{{ $isChildActive ? 'active' : '' }}" aria-current="page"
                                href="{{ url($child->link) }}">{{ $child->label }}</a>
                        </li>
                    @endforeach
                </ul>
            </li>
        @else
            <li class="nav-item">
                <a class="nav-link {{ $isActive ? 'active' : '' }}" aria-current="page"
                    href="{{ $link }}">{{ $item->label }}</a>
            </li>
        @endif
    @endforeach
</ul>
