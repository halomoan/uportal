@extends('layouts.app')

@section('content')
<div class="d-flex align-items-center justify-content-center vh-100">

    <div class="row">
        <div class="col-lg-6 col-sm-6 p-0">
            <img src="{{ asset('img/USQ.jpg') }}" width="100%" alt="">
        </div>
        <div class="col-lg-6 col-sm-6 border border-gray">
            <div class="d-flex align-items-center flex-column h-100">
                <div class="p-6">&nbsp;</div>
                <div class="d-flex align-items-center flex-column ">
                    <div class="row">
                        <div class="col-12">
                            <img src="{{ asset('img/logo.jpeg') }}" alt="">
                        </div>
                    </div>
                    <div class="p-6">&nbsp;</div>
                    <div class="p-6">&nbsp;</div>

                    <div class="row p-2">
                        <div class="col-12 text-bold">
                            ACCOUNT LOGIN
                        </div>
                    </div>
                    <div class="row w-100">
                        <div class="col-12">
                            <form method="POST" action="{{ route('login') }}">
                                @csrf
                                <div class="form-group">
                                    <input id="email" type="email" placeholder="Email Address"
                                        class="form-control @error('email') is-invalid @enderror" name="email"
                                        value="{{ old('email') }}" required autocomplete="email" autofocus>
                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <input id="password" type="password" placeholder="Password"
                                        class="form-control @error('password') is-invalid @enderror" name="password"
                                        required autocomplete="current-password">

                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-block bg-gradient-success mt-2">{{ __('Login') }}</button>
                            </form>
                        </div>
                    </div>

                    <div class="d-inline mt-2"> @if (Route::has('password.request'))
                        <a class="btn btn-link" href="{{ route('password.request') }}">
                            {{ __('Forgot Your Password?') }}
                        </a>
                        @endif
                    </div>

                </div>


            </div>
        </div>
    </div>
    @endsection