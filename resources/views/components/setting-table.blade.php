@php
$mode = $attributes->get('mode');
$settings = $attributes->get('settings');
@endphp


<div class="row">
    <div class="col-12 my-2">
        <a href="{{ route('settings.edit', $mode) }}" class="btn btn-outline-primary float-end">
            {{ __('Edit') }}
        </a>
    </div>
    <div class="col-12">
        <table id="{{ $mode }}Table" class="table table-bordered">
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
                                {{ collect($settings[$mode]->$key)->map(fn($v) => is_bool($v) ? ($v ? __('Active') : __('Inactive')) : $v)->implode(', ') }}
                            @elseif($settings[$mode]->$key instanceof DateTimeZone)
                                {{ $settings[$mode]->$key->getName() }}
                            @elseif(is_bool($settings[$mode]->$key))
                                {{ $settings[$mode]->$key ? __('Active') : __('Inactive') }}
                            @else
                                {{ $settings[$mode]->$key }}
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@push('script')
    <script>
        $('#{{ $mode }}Table').DataTable();
    </script>
@endpush
