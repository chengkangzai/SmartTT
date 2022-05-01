@if ($attributes->get('active'))
    <span class="badge rounded-pill bg-success" {{ $attributes }}>
        {{ __('Active') }}
    </span>
@else
    <span class="badge rounded-pill bg-danger" {{ $attributes }}>
        {{ __('Inactive') }}
    </span>
@endif
