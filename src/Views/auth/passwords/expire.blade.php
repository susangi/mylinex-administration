@extends('layouts.login')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-5 pa-0">
                <div class="auth-cover-img overlay-wrap" style="background-image:url(/images/bg-2.jpg);">

                </div>
            </div>
            <div class="col-xl-7 pa-0">
                <div class="auth-form-wrap py-xl-0 py-50">
                    <div class="auth-form w-xxl-55 w-xl-75 w-sm-90 w-xs-100">

                        <form method="POST" action="{{ route('password.post_expired') }}" id="pw_form">
                            @csrf

                            <div class="form-group @error('current_password') is-invalid @enderror">
                                <input type="password" class="form-control" placeholder="{{ __('Current Password') }}" name="current_password" required autocomplete="new-password">
                                @if ($errors->has('current_password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('current_password') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control {{ $errors->has('password') ? ' has-error' : '' }}" placeholder="{{ __('New Password') }}" name="password" id="password" required autocomplete="new-password">
                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group {{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                                <input type="password" class="form-control" placeholder="{{ __('Confirm Password') }}" name="password_confirmation" required autocomplete="new-password" >
                                @if ($errors->has('password_confirmation'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <button type="submit" class="btn btn-hgc btn-block">
                                {{ __('Update Password') }}
                            </button>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{asset('plugins/jquery-validation/jquery.validate.js')}}"></script>
    <script src="{{asset('plugins/jquery-validation/additional-methods.min.js')}}"></script>

<script>
    $(document).ready(function(){

    $.validator.addMethod(
        "regex",
        function (value, element, regexp) {
            var re = new RegExp(regexp);
            return this.optional(element) || re.test(value);
        },
        "Password must be of 8-14 characters in length and have one or more special character and one or more number and one or more uppercase character."
    );
    $("#pw_form").validate({
        rules: {
            current_password: {
                required: true,
            },
            password: {
                required: true,
                regex: /^(?=.*[0-9])(?=.*[!@#$%^&*])(?=.*[a-z])(?=.*[A-Z])[a-zA-Z0-9!@#$%^&*]{8,14}$/,
                minlength: 8
            },
            password_confirmation: {
                required: true,
                regex: /^(?=.*[0-9])(?=.*[!@#$%^&*])(?=.*[a-z])(?=.*[A-Z])[a-zA-Z0-9!@#$%^&*]{8,14}$/,
                equalTo: "#password",
                minlength: 8
            }
        }
    });
    });
</script>
@endpush
