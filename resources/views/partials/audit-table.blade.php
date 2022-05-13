@php
/** @var \Spatie\Activitylog\Models\Activity $log */
@endphp
<div>
    <table class="table table-responsive table-striped">
        <thead>
            <tr>
                <th> {{ __('Date Time') }} </th>
                <th> {{ __('Subject') }} </th>
                <th> {{ __('Performed By') }} </th>
                <th> {{ __('Action') }} </th>
                <th> {{ __('Previous Value') }} </th>
                <th> {{ __('New Value') }} </th>
            </tr>
        </thead>
        <tbody>
            @forelse($logs as $log)
                <tr>
                    <td> {{ \Carbon\Carbon::parse($log->created_at)->diffForHumans() }} </td>
                    <td> {{ (new ReflectionClass($log->subject_type))->getShortName() }} </td>
                    <td> {{ $log->causer->name ?? __('System') }} <br>
                        {{ $log->causer?->email ? '<' . $log->causer->email . '>' : '' }}
                    </td>
                    <td> {{ $log->description }} </td>
                    <td>
                        @forelse((collect($log->changes)->get('old') ?? []) as $key => $value)
                            <span>{{ $key }}: {{ $value }}</span><br>
                        @empty
                            {{ __('N/A') }}
                        @endforelse
                    </td>
                    <td>
                        @forelse((collect($log->changes)->get('attributes') ?? []) as $key => $value)
                            <span>{{ $key }}: {{ $value }}</span><br>
                        @empty
                            {{ __('N/A') }}
                        @endforelse
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">{{ __('No logs found') }}</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
