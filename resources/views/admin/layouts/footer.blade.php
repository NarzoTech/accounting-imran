<footer class="content-footer footer bg-footer-theme">
    <div class="container-fluid">
        <div class="footer-container">
            <div class="text-body text-center w-100">
                @php
                    $copyright = $setting->copyright_text;
                    $copyright = str_replace('{year}', date('Y'), $copyright);
                @endphp
                <b>{{ $copyright }}</b>
            </div>
        </div>
    </div>
</footer>
