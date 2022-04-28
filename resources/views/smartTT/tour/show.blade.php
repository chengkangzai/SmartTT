@php
/** @var \App\Models\Tour $tour */
/** @var \App\Models\TourDescription $des */
@endphp

@extends('layouts.app')
@section('title')
    {{ __('Tour Management') }} - {{ config('app.name') }}
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
            <img src="{{ $tour->getFirstMedia('thumbnail')?->getUrl() ?? '#' }}" alt="" class="img-responsive img-thumbnail image">
        </div>
        <div class="col-lg-10 ">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('Tour Information') }}</h3>
                    <div class="pull-right">
                        <a href="{{ route('tours.edit', $tour) }}" class="btn btn-primary">{{ __('Edit') }}</a>
                        <form action="{{ route('tours.destroy', $tour) }}" method="POST" class="d-inline">
                            @method('DELETE')
                            @csrf
                            <input class="btn btn-danger" type="submit" value="Delete" />
                        </form>
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
                                    <td>
                                        <a href="{{ $tour->getFirstMedia('itinerary')?->getUrl() ?? '#' }}" class="btn btn-info">{{ __('View') }}</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card mt-2">
                <div class="card-header">
                    <h3 class="card-title">{{ __('Tour Description') }}</h3>
                    <div class="pull-right">
                        <a href="#" class="btn btn-success" data-coreui-toggle="modal"
                            data-coreui-target="#addTourDescriptionModal">
                            {{ __('Add') }}
                        </a>
                    </div>
                </div>
                <div class="card-body row gap-1">
                    @forelse($tourDes as $des)
                        <div class="card col pb-4 pt-1 px-1">
                            <div class="card-body">
                                <h3 class="card-title">{{ $des->place }}</h3>
                                <p class="card-text text-truncate">{{ $des->description }}</p>
                                <div class="border my-2"></div>
                                <div class="float-end">
                                    <a href="{{ route('tourDescriptions.edit', $des) }}" class="btn btn-primary">
                                        {{ __('Edit') }}
                                    </a>
                                    <form class="d-inline" method="POST"
                                        action="{{ route('tourDescriptions.destroy', $des) }}">
                                        @csrf
                                        @method('DELETE')
                                        <input type="submit" class="btn btn-danger" value="Delete">
                                    </form>
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
                    <button type="button" class="btn btn-default pull-left"
                        data-dismiss="modal">{{ __('Close') }}</button>
                    <input form="addTourDescription" type="submit" class="btn btn-primary" value="{{ __('Submit') }}">
                </div>
            </div>
        </div>
    </div>
@endsection
