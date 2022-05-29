@extends('smartTT.layouts.guest')

@section('content')
    <div class="col-md-6">
        <div class="card mb-4 mx-4">
            <div class="card-body p-4">
                <h1>{{ __('Reset Password') }}</h1>

                <form action="{{ route('password.update') }}" method="POST">
                    @include('partials.error-alert')
                    @csrf
                    <input type="email" name="email" value="{{ request()->get('email') }}" hidden>
                    <input type="text" name="token" value="{{ request()->token }}" hidden>
                    <div class="input-group mb-4"><span class="input-group-text">
                            <svg class="icon">
                                <use xlink:href="{{ asset('icons/coreui.svg#cil-lock-locked') }}"></use>
                            </svg></span>
                        <input class="form-control @error('password') is-invalid @enderror" type="password" id="password"
                            name="password" placeholder="{{ __('Password') }}">
                        @error('password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="input-group mb-4"><span class="input-group-text">
                            <svg class="icon">
                                <use xlink:href="{{ asset('icons/coreui.svg#cil-lock-locked') }}"></use>
                            </svg></span>
                        <input class="form-control @error('password_confirmation') is-invalid @enderror" type="password"
                            id="password_confirmation" name="password_confirmation"
                            placeholder="{{ __('Confirm Password') }}">
                        @error('password_confirmation')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <button class="btn btn-block btn-success" type="submit">{{ __('Reset Password') }}</button>
                </form>

            </div>
        </div>
    </div>
@endsection
