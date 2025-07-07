@php
    $languages = allLanguages()->where('status', '1');

    if (!cache()->has('siteContent')) {
        $contents = Modules\SiteAppearance\app\Models\SiteSettings::with('translation')->get();
        cache()->forever('siteContent', $contents);
    }

    if (cache()->has('siteContent')) {
        $contents = cache()->get('siteContent');
        $footerContents = $contents->where('page_name', 'contactpage')->first();
    }

    $offProperties = Modules\Property\app\Models\Property::where('status', 1)->where('is_featured', 1)->limit(3)->get();
@endphp

<div class="menu_offcanvas offcanvas {{ $isRTL ? 'offcanvas-start' : 'offcanvas-end' }}" tabindex="-1" id="offcanvasRight"
    style="background: url({{ asset('website/assets/images/offcanvas_bg.jpg') }});">
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"><i
            class="far fa-times"></i></button>
    <div class="offcanvas-body">
        <ul class="switcher d-flex flex-wrap align-items-center">
            <li>
                <form id="setLanguageHeader" action="{{ route('set-language') }}" onchange="$(this).trigger('submit')">
                    <select class="select_js language" name="code">
                        @forelse ($languages as $language)
                            <option value="{{ $language->code }}"
                                {{ getSessionLanguage() == $language->code ? 'selected' : '' }}>
                                {{ $language->name }}</option>
                        @empty
                            <option value="en" {{ getSessionLanguage() == 'en' ? 'selected' : '' }}>
                                {{ __('English') }}</option>
                        @endforelse
                    </select>
                </form>
            </li>
            @if (Module::isEnabled('Currency') &&
                    Route::has('set-currency') &&
                    allCurrencies()?->where('status', 'active')->count() > 1)
                <li>
                    <form id="setCurrencyHeader" action="{{ route('set-currency') }}"
                        onchange="$(this).trigger('submit')">
                        <select class="select_js" name="currency">
                            @foreach (allCurrencies()?->where('status', 'active') as $currency)
                                <option value="{{ $currency->currency_code }}"
                                    {{ getSessionCurrency() == $currency->currency_code ? 'selected' : '' }}>
                                    {{ $currency->currency_name }}</option>
                            @endforeach
                        </select>
                    </form>
                </li>
            @endif
        </ul>
        <div class="offcanvas_blog">
            <h3>{{ __('Featured Listings') }}</h3>
            <ul>
                @foreach ($offProperties as $featuredProperty)
                    <li>
                        <div class="img">
                            <img loading="lazy" src="{{ asset($featuredProperty->thumbnail_image) }}"
                                alt="{{ $featuredProperty->title }}" class="img-fluid w-100">
                        </div>
                        <div class="text">
                            <p><i class="far fa-clock"></i>{{ $featuredProperty->created_at->format('d M Y') }}</p>
                            <a class="title"
                                href="{{ route('website.property-details', $featuredProperty->slug) }}">{{ $featuredProperty->title }}</a>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="offcanvas_contact">
            <h3>{{ __('Contact Information') }}</h3>
            <a class="call"
                href="callto:{{ $footerContents->others['phone'] ?? '' }}">{{ $footerContents->others['phone'] ?? '' }}</a>
            <a class="mail"
                href="mailto:{{ $footerContents->others['email'] ?? '' }}">{{ $footerContents->others['email'] ?? '' }}</a>
            <ul class="d-flex flex-wrap">
                @foreach ($socialLinks as $sLink)
                    <li><a class="facebook" href="{{ $sLink->link }}"><i class="{{ $sLink->icon }}"></i></a></li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
