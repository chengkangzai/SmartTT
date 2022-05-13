@php
/** @var \App\Models\Tour $tour */
/** @var \App\Models\TourDescription $des */
@endphp

@extends('layouts.app')
@section('title')
    {{ __('Tour Management') }}
@endsection

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('tours.index') }}">{{ __('Tour') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Show') }}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-2">
            <img src="{{ $tour->getFirstMedia('thumbnail')?->getUrl() ?? '#' }}" alt="{{ $tour->name }}"
                class="img-fluid">
        </div>
        <div class="col-lg-10 ">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('Tour Information') }}</h3>
                    <div class="float-end">
                        @can('Edit Tour')
                            <a href="{{ route('tours.edit', $tour) }}" class="btn btn-outline-primary">
                                {{ __('Edit') }}
                            </a>
                        @endcan
                        @can('Delete Tour')
                            <form action="{{ route('tours.destroy', $tour) }}" method="POST" class="d-inline">
                                @method('DELETE')
                                @csrf
                                <input class="btn btn-outline-danger" type="submit" value="{{ __('Delete') }}" />
                            </form>
                        @endcan
                        @can('Audit Tour')
                            <a href="{{ route('tours.audit', $tour) }}" class="btn btn-outline-primary">
                                {{ __('Audit Trail') }}
                            </a>
                        @endcan
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>{{ __('ID') }}</th>
                                    <th>{{ __('Tour Name') }}</th>
                                    <th>{{ __('Tour Code') }}</th>
                                    <th>{{ __('Destination') }}</th>
                                    <th>{{ __('Category') }}</th>
                                    <th>{{ __('Active') }}</th>
                                    <th>{{ __('Itinerary') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ $tour->id }}</td>
                                    <td>{{ $tour->name }}</td>
                                    <td>{{ $tour->tour_code }}</td>
                                    <td>
                                        <ul>
                                            @foreach ($tour->countries as $country)
                                                <li>{{ $country->name }}</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td>{{ $tour->category }}</td>
                                    <td>{{ $tour->is_active ? __('Yes') : __('No') }}</td>
                                    <td>
                                        <a href="{{ $tour->getFirstMedia('itinerary')?->getUrl() ?? '#' }}"
                                            target="_blank" class="btn btn-outline-info">
                                            {{ __('View') }}
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card mt-2">
                <div class="card-header">
                    <h3 class="card-title">{{ __('Packages') }}</h3>
                    <div class="float-end">
                        @can('Create Package')
                            <a href="{{ route('packages.create') }}" class="btn btn-outline-primary">
                                {{ __('Add') }}
                            </a>
                        @endcan
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>{{ __('ID') }}</th>
                                    <th>{{ __('Departure') }}</th>
                                    <th>{{ __('Tour') }}</th>
                                    <th>{{ __('Airline') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($packages as $package)
                                    <tr>
                                        <td>{{ $package->id }}</td>
                                        <td>{{ $package->depart_time->format(config('app.date_format')) }}</td>
                                        <td>{{ $tour->name }}</td>
                                        <td>
                                            <ol>
                                                @foreach ($package->flight as $flight)
                                                    <li>{{ $flight->airline->name }}</li>
                                                @endforeach
                                            </ol>
                                        </td>
                                        <td>
                                            @can('View Package')
                                                <a href="{{ route('packages.show', $package) }}"
                                                    class="btn btn-outline-info">
                                                    {{ __('Show') }}
                                                </a>
                                            @endcan
                                            @can('Edit Package')
                                                <a href="{{ route('packages.edit', $package) }}"
                                                    class="btn btn-outline-primary">
                                                    {{ __('Edit') }}
                                                </a>
                                            @endcan
                                            @can('Delete Package')
                                                <form action="{{ route('packages.destroy', $package) }}"
                                                    class="d-inline" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <input class="btn btn-outline-danger" type="submit"
                                                        value="{{ __('Delete') }}" />
                                                </form>
                                            @endcan
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">
                                            {{ __('No data') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        {{ $packages->links() }}
                    </div>
                </div>
            </div>

            <div class="card mt-2">
                <div class="card-header">
                    <h3 class="card-title">{{ __('Tour Description') }}</h3>
                    <div class="float-end">
                        @can('Create Tour Description')
                            <a href="#" class="btn btn-outline-success" data-coreui-toggle="modal"
                                data-coreui-target="#addTourDescriptionModal">
                                {{ __('Add') }}
                            </a>
                        @endcan
                    </div>
                </div>
                <div class="card-body row gap-1">
                    @forelse($tourDes as $des)
                        <div class="card col pb-4 pt-1 px-1">
                            <div class="card-body">
                                <h3 class="card-title">{{ $des->place }}</h3>
                                <p class="card-text">{{ $des->description }}</p>
                                @canany(['Edit Tour Description', 'Audit Tour Description', 'Delete Tour Description'])
                                    <div class="border my-2"></div>
                                @endcanany
                                <div class="float-end">
                                    @can('Edit Tour Description')
                                        <a href="{{ route('tourDescriptions.edit', $des) }}" class="btn btn-outline-primary">
                                            {{ __('Edit') }}
                                        </a>
                                    @endcan
                                    @can('Audit Tour Description')
                                        <a href="{{ route('tourDescriptions.audit', $des) }}" class="btn btn-outline-info">
                                            {{ __('Audit Trail') }}
                                        </a>
                                    @endcan
                                    @can('Delete Tour Description')
                                        <form class="d-inline" method="POST"
                                            action="{{ route('tourDescriptions.destroy', $des) }}">
                                            @csrf
                                            @method('DELETE')
                                            <input type="submit" class="btn btn-outline-danger" value="{{ __('Delete') }}" />
                                        </form>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-center">{{ __('Nothing to show') }}</p>
                    @endforelse
                </div>
                <div class="card-footer">
                    {{ $tourDes->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modal')
    @can('Create Tour Description')
        <div class="modal fade" tabindex="-1" id="addTourDescriptionModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ __('Add Tour Description to Tour :') }} {{ $tour->name }}</h4>
                        <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('tourDescriptions.attach', $tour->id) }}" method="POST"
                            id="addTourDescription">
                            @csrf
                            @method('POST')
                            <div class="mb-3">
                                <label for="place" class="form-label"> {{ __('Description Place') }} </label>
                                <input class="form-control select2" id="place" name="place" required
                                    placeholder="{{ __('Enter the name of the Place') }}" />
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label"> {{ __('Place Description') }} </label>
                                <textarea name="description" id="description" class="form-control" rows="5" required
                                    placeholder="{{ __('Enter the description for the place above') }}"></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-coreui-dismiss="modal">
                            {{ __('Close') }}
                        </button>
                        <input form="addTourDescription" type="submit" class="btn btn-outline-primary"
                            value="{{ __('Submit') }}" />
                    </div>
                </div>
            </div>
        </div>
    @endcan
@endsection
