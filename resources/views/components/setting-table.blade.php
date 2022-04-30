@php
    $mode = $attributes->get('mode');
    $settings = $attributes->get('settings');
@endphp

<table id="indexTable" class="table table-bordered">
    <thead>
    <tr>
        <th>{{ __('Name') }}</th>
        <th>{{ __('Value') }}</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($settings[$mode]->originalValues->keys()->toArray() as $key)
        <tr>
            <td>{{ trans('setting.' . $mode . '.' . $key) }}</td>
            <td>
                @if (is_array($settings[$mode]->$key))
                    {{ collect($settings[$mode]->$key)->map(fn($v) => is_bool($v) ? ($v ? __('Yes') : __('No')) : $v)->implode(', ') }}
                @elseif($settings[$mode]->$key instanceof DateTimeZone)
                    {{ $settings[$mode]->$key->getName() }}
                @elseif(is_bool($settings[$mode]->$key))
                    {{ $settings[$mode]->$key ? __('Yes') : __('No') }}
                @else
                    {{ $settings[$mode]->$key }}
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
