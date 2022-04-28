@if ($attributes->get('active'))
    <span class="badge rounded-pill bg-success">
        {{ __('Active') }}
    </span>
@else
    <span class="badge rounded-pill bg-danger">
        {{ __('Inactive') }}
    </span>
@endif
