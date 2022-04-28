<div class="table-responsive">
    <table class="table">
        <thead>
        <tr>
            <th> {{ __('Date Time') }} </th>
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
                <td> {{ $log->causer->name ?? 'System' }} </td>
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
