<p>
    @php
        $reviewQty = $property->reviews->where('status', 1)->count();
        $totalReview = $property->reviews->where('status', 1)->sum('rating');
        $average = $reviewQty > 0 ? $totalReview / $reviewQty : 0;

        $intAverage = intval($average);

        $nextValue = $intAverage + 1;
        $reviewPoint = $intAverage;
        $halfReview = false;
        if ($intAverage < $average && $average < $nextValue) {
            $reviewPoint = $intAverage + 0.5;
            $halfReview = true;
        }
    @endphp
    @for ($i = 1; $i <= 5; $i++)
        @if ($i <= $reviewPoint)
            <i class="fas fa-star"></i>
        @elseif ($i > $reviewPoint)
            @if ($halfReview == true)
                <i class="fas fa-star-half-alt"></i>
                @php
                    $halfReview = false;
                @endphp
            @else
                <i class="fal fa-star"></i>
            @endif
        @endif
    @endfor

    <span>({{ $average }})</span>
</p>
