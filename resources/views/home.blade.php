@section('title')
    {{ __('Home') }}
@endsection
@extends('smartTT.layouts.app')

@section('content')
    <div class="container-lg">
        <div class="row">
            <div class="col-sm-6 col-lg-3">
                <div class="card mb-4 text-white bg-primary">
                    <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                        <div>
                            <div class="fs-4 fw-semibold">{{ $userCount }}</div>
                            <div>{{ __('Users') }}</div>
                        </div>
                    </div>
                    <div class="c-chart-wrapper mt-3 mx-3" style="height:70px;">
                        <canvas id="card-chart1"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3">
                <div class="card mb-4 text-white bg-info">
                    <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                        <div>
                            <div class="fs-4 fw-semibold">{{ $bookingCount }}</div>
                            <div>{{ __('Ongoing Bookings') }}</div>
                        </div>
                    </div>
                    <div class="c-chart-wrapper mt-3 mx-3" style="height:70px;">
                        <canvas id="card-chart2"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3">
                <div class="card mb-4 text-white bg-warning">
                    <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                        <div>
                            <div class="fs-4 fw-semibold">{{ $tourCount }}</div>
                            <div>{{ __('Active Tour') }}</div>
                        </div>
                    </div>
                    <div class="c-chart-wrapper mt-3" style="height:70px;">
                        <canvas id="card-chart3"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3">
                <div class="card mb-4 text-white bg-danger">
                    <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                        <div>
                            <div class="fs-4 fw-semibold">{{ $packageCount }}</div>
                            <div>{{ __('Active Pacakges') }}</div>
                        </div>
                    </div>
                    <div class="c-chart-wrapper mt-3 mx-3" style="height:70px;">
                        <canvas id="card-chart4"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="card bg-white">
            <div class="card-header">
                <h2>{{ __('Recent Bookings') }}</h2>
            </div>
            <div class="card-body">
                <table class="table table-sm table-responsive table-bordered table-striped text-center">
                    <thead>
                    <tr>
                        <th> {{ __('Date Time') }} </th>
                        <th> {{ __('Subject') }} </th>
                        <th> {{ __('Performed By') }} </th>
                        <th> {{ __('Action') }} </th>
                        <th>{{ __('Price') }}</th>
                        <th>{{ __('Tour') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr>
                                <td> {{ \Carbon\Carbon::parse($log->created_at)->translatedFormat(config('app.date_format')) }} </td>
                                <td> {{ trans('constant.model.' . $log->subject_type) }} </td>
                                <td> {{ $log->causer->name ?? __('System') }}
                                    {{ $log->causer?->email ? '<' . $log->causer->email . '>' : '' }}
                                </td>
                                <td> {{ trans('constant.activity.' . $log->description) }} </td>
                                <td> {{ number_format($logData->find($log->subject_id)?->total_price ?? 0, 2) }}
                                </td>
                                <td> {{ $logData->find($log->subject_id)?->package?->tour?->name ?? '' }} </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">{{ __('No logs found') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $logs->links() }}
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{ asset('js/chart.js') }}"></script>
    <script>
        Chart.defaults.color = getComputedStyle(document.documentElement).getPropertyValue('--cui-body-color');

        const options = {
            plugins: {
                legend: {
                    display: false
                }
            },
            maintainAspectRatio: false,
            scales: {
                x: {
                    grid: {
                        display: false,
                        drawBorder: false
                    },
                    ticks: {
                        display: false
                    }
                },
                y: {
                    display: false,
                    grid: {
                        display: false
                    },
                    ticks: {
                        display: false
                    }
                }
            },
            elements: {
                line: {
                    borderWidth: 1,
                    tension: 0.4
                },
                point: {
                    radius: 4,
                    hitRadius: 10,
                    hoverRadius: 4
                }
            }
        }

        const cardChart1 = new Chart($('#card-chart1'), {
            type: 'line',
            data: {
                labels: @json($userData['label']),
                datasets: [{
                    label: 'Users',
                    backgroundColor: 'transparent',
                    borderColor: 'rgba(255,255,255,.55)',
                    pointBackgroundColor: getComputedStyle(document.documentElement)
                        .getPropertyValue('--cui-primary'),
                    data: @json($userData['data']),
                }]
            },
            options
        });

        const cardChart2 = new Chart($('#card-chart2'), {
            type: 'line',
            data: {
                labels: @json($bookingData['label']),
                datasets: [{
                    backgroundColor: 'transparent',
                    borderColor: 'rgba(255,255,255,.55)',
                    pointBackgroundColor: getComputedStyle(document.documentElement)
                        .getPropertyValue('--cui-info'),
                    data: @json($bookingData['data']),
                }]
            },
            options
        });
    </script>
@endpush
