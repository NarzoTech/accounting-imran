<tr>
    <td colspan="{{ $colspan }}" class="py-5 text-center">
        <h4 class="py-2">{{ $message }}</h4>
        @if ($create == 'yes')
            <a href="{{ route($route) }}" class="btn btn-success ">{{ __('Add New') }} {{ $name }}</a>
        @endif
    </td>
</tr>
