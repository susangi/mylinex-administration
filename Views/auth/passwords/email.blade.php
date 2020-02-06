@extends('layouts.login')

@section('content')
{{--<div class="container"  style="margin-top: 100px">--}}
{{--    <div class="row justify-content-center">--}}
{{--        <div class="col-md-8">--}}
{{--            <div class="card">--}}
{{--                <div class="card-header">{{ __('Reset Password') }}</div>--}}

{{--                <div class="card-body">--}}
{{--                    @if (session('status'))--}}
{{--                        <div class="alert alert-success" role="alert">--}}
{{--                            {{ session('status') }}--}}
{{--                        </div>--}}
{{--                    @endif--}}

{{--                    <form method="POST" action="{{ route('password.email') }}">--}}
{{--                        @csrf--}}

{{--                        <div class="form-group row">--}}
{{--                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>--}}

{{--                            <div class="col-md-6">--}}
{{--                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>--}}

{{--                                @error('email')--}}
{{--                                    <span class="invalid-feedback" role="alert">--}}
{{--                                        <strong>{{ $message }}</strong>--}}
{{--                                    </span>--}}
{{--                                @enderror--}}
{{--                            </div>--}}
{{--                        </div>--}}

{{--                        <div class="form-group row mb-0">--}}
{{--                            <div class="col-md-6 offset-md-4">--}}
{{--                                <button type="submit" class="btn btn-primary">--}}
{{--                                    {{ __('Send Password Reset Link') }}--}}
{{--                                </button>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </form>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-5 pa-0">
            <div class="auth-cover-img overlay-wrap" style="background-image:url(/images/bg-1.jpg);">

            </div>
        </div>
        <div class="col-xl-7 pa-0">
            <div class="auth-form-wrap py-xl-0 py-50">
                <div class="auth-form w-xxl-55 w-xl-75 w-sm-90 w-xs-100">
                    @if (session('status'))
                                            <div class="alert alert-success" role="alert">
                                                {{ session('status') }}
                                            </div>
                                        @endif
                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf
                        <h1 class="display-5 mb-10">Forgot Your Password ? </h1>
                        <p class="mb-30">Enter your email we will send a password reset link to your email.</p>
                        <div class="form-group">
                            <input placeholder="Email" id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                   name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                            @enderror
                        </div>

                        <button class="btn btn-primary-blue btn-block" type="submit">
                            {{ __('Send Password Reset Link') }}
                        </button>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
