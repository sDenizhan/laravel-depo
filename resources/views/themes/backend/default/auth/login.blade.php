@extends('themes.backend.default.layouts.login')

@section('content')
<div class="auth-fluid-form-box">
    <div class="align-items-center d-flex h-100">
        <div class="p-3">

            <div class="auth-brand text-center text-lg-start">
                <div class="auth-brand">
                    <a href="{{ url('/') }}" class="logo logo-dark text-center">
                        <span class="logo-lg">
                            <img src="{{ asset('logos/logo-dark.png') }}" alt="" height="75">
                        </span>
                    </a>

                    <a href="{{ url('/') }}" class="logo logo-light text-center">
                        <span class="logo-lg">
                            <img src="{{ asset('logos/logo-white.png') }}" alt="" height="75">
                        </span>
                    </a>
                </div>
            </div>

            <!-- title-->
            <h4 class="mt-0">{{ __('Login') }}</h4>
            <p class="text-muted mb-4">Enter your email address and password to access account.</p>


            <!-- form -->
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-3">
                    <label for="emailaddress" class="form-label">Email address</label>
                    <input class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" type="email" id="email" name="email" required autofocus placeholder="Enter your email">
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">{{ __('Password') }}</label>
                    <div class="input-group input-group-merge">
                        <input type="password" id="password" name="password" required autocomplete="current-password" class="form-control  @error('password') is-invalid @enderror" placeholder="Enter your password">

                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="text-center d-grid">
                    <button class="btn btn-primary" type="submit">
                        {{ __('Login') }}
                    </button>
                </div>
            </form>
            <!-- end form-->

        </div> <!-- end .card-body -->
    </div> <!-- end .align-items-center.d-flex.h-100-->
</div>
@endsection
