@php
    /** @var \App\Models\User $user */
@endphp
@extends('smartTT.layouts.app')
@section('title')
    {{__('Profile')}}
@endsection

@section('content')
    @include('smartTT.partials.error-alert')
    <div class="card mb-4">
        <div class="card-header">
            {{ __('My profile') }}
        </div>
        <form action="{{ route('profile.update') }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card-body">
                @if ($message = Session::get('success'))
                    <div class="alert alert-success" role="alert">{{ $message }}</div>
                @endif
                <div class="input-group mb-3">
                    <span class="input-group-text">
                        <svg class="icon">
                            <use xlink:href="{{ asset('icons/coreui.svg#cil-user') }}"></use>
                        </svg>
                    </span>
                    <input class="form-control" type="text" name="name" placeholder="{{ __('Name') }}"
                           value="{{ old('name', auth()->user()->name) }}" required>
                    @error('name')
                    <span class="invalid-feedback">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
                <div class="input-group mb-3">
                    <span class="input-group-text">
                        <svg class="icon">
                            <use xlink:href="{{ asset('icons/coreui.svg#cil-envelope-open') }}"></use>
                        </svg>
                    </span>
                    <input class="form-control" type="text" name="email" placeholder="{{ __('Email') }}"
                           value="{{ old('email', auth()->user()->email) }}" required>
                    @error('email')
                    <span class="invalid-feedback">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <div class="input-group mb-3">
                    <span class="input-group-text">
                        <svg class="icon">
                            <use xlink:href="{{ asset('icons/coreui.svg#cil-lock-locked') }}"></use>
                        </svg>
                    </span>
                    <input class="form-control @error('password') is-invalid @enderror" type="password" name="password"
                           placeholder="{{ __('New password') }}" required>
                    @error('password')
                    <span class="invalid-feedback">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
                <div class="input-group mb-4">
                    <span class="input-group-text">
                        <svg class="icon">
                            <use xlink:href="{{ asset('icons/coreui.svg#cil-lock-locked') }}"></use>
                        </svg>
                    </span>
                    <input class="form-control @error('password_confirmation') is-invalid @enderror" type="password"
                           name="password_confirmation" placeholder="{{ __('New password confirmation') }}" required>
                </div>
            </div>
            <div class="card-footer">
                <button class="btn btn-sm btn-primary" type="submit">{{ __('Submit') }}</button>
            </div>
        </form>
    </div>
    <div class="card">
        <div class="card-header">
            {{ __('Connected accounts') }}
        </div>
        <div class="card-body">
            <div class="list-group list-group-flush">
                <div class="list-group-item list-group-item-action">
                    <div class="d-flex w-100 justify-content-between">
                        <p class="my-auto">
                            <svg class="icon">
                                <use xlink:href="{{ asset('icons/brand.svg#cib-microsoft') }}"></use>
                            </svg>
                            {{__('Microsoft')}}
                            <span class="my-auto">
                                @if(auth()->user()->msOauth()->exists())
                                    <span class="badge bg-success">{{__('Connected')}}</span>
                                @else
                                    <span class="badge bg-success">{{__('Not connected')}}</span>
                                @endif
                            </span>
                        </p>
                        <div class="my-auto flex-grow">
                            @if(auth()->user()->msOauth()->exists())
                                <a href="{{ route('msOAuth.disconnect') }}"
                                   class="btn btn-sm btn-danger rounded-pill">
                                    {{ __('Disconnect') }}
                                </a>
                            @else
                                <a href="{{ route('msOAuth.signin') }}" class="btn btn-sm btn-success rounded-pill">
                                    {{ __('Connect') }}
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
