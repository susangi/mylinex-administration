@extends('layouts.app')
@section('title','Profile Settings')
@section('content')
    <div class="tab-pane fade show active" role="tabpanel">
        <div class="container">
            <div class="hk-row">
                <div class="col-lg-4">
                    <div class="card card-profile-feed">
                        <div class="card-header card-header-action">
                            <div class="media align-items-center">
                                <div class="media-img-wrap d-flex mr-10">
                                    <div class="avatar avatar-sm">
                                        <img
                                            src="{{asset('images/profile/'.(!empty($user->image)?$user->image:'default.jpg'))}}"
                                            alt="user" class="avatar-img rounded-circle">
                                    </div>
                                </div>
                                <div class="media-body">
                                    <div class="text-capitalize font-weight-500 text-dark">{{$user->name}}</div>
                                    <div class="font-13">{{$user->roles[0]->name}}</div>
                                </div>
                            </div>
                        </div>
                        <div class="row text-center">
                            <div class="col-12 border-right pr-0">
                                <div class="pa-15">
                                    <span
                                        class="d-block display-6 text-dark mb-5">{{($user->hasRole('Super Admin')||$user->hasRole('Admin'))?'unlimited':$permissions->count()}}</span>
                                    <span class="d-block text-capitalize font-14">Permissions</span>
                                </div>
                            </div>
                        </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><span><i
                                        class="ion ion-md-calendar font-18 text-danger mr-10"></i><span>Last Login:</span></span><span
                                    class="ml-5 text-dark">{{\Carbon\Carbon::parse($user->last_login)->diffForHumans()}}</span>
                            </li>
                            {{--                            <li class="list-group-item"><span><i--}}
                            {{--                                        class="ion ion-md-briefcase font-18 text-danger mr-10"></i><span>Worked at:</span></span><span--}}
                            {{--                                    class="ml-5 text-dark">Companey</span></li>--}}
                            {{--                            <li class="list-group-item"><span><i--}}
                            {{--                                        class="ion ion-md-home font-18 text-danger mr-10"></i><span>Lives in:</span></span><span--}}
                            {{--                                    class="ml-5 text-dark">San Francisco, CA</span></li>--}}
                            {{--                            <li class="list-group-item"><span><i--}}
                            {{--                                        class="ion ion-md-pin font-18 text-danger mr-10"></i><span>From:</span></span><span--}}
                            {{--                                    class="ml-5 text-dark">Settle, WA</span></li>--}}
                        </ul>
                        <div class="row text-left">
                            <div class="col-12 border-right pr-0">
                                <div class="row pa-15">
                                    @if (($user->hasRole('Super Admin')||$user->hasRole('Admin')))
                                        <div class="col-6">
                                            <span
                                                class="text-capitalize font-14">unlimited permissions</span>
                                        </div>
                                    @else
                                        @foreach($permissions as $permission)
                                            <div class="col-6">
                                            <span
                                                class="text-capitalize font-14">{{$permission}}</span>
                                            </div>
                                        @endforeach
                                    @endif

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">
                    <div class="card card-profile-feed">
                        <div class="card-header card-header-action">
                            <h5>Edit Primary Data</h5>
                        </div>
                        {!! Form::open(['method' => 'post', 'url'=>route('users.update',$user->id),'id' => 'userResetForm','class'=>'needs-validation','files'=>true]) !!}
                        <div class="card-body">
                            @include('Administration::users.form-user')
                        </div>
                        <div class="card-footer justify-content-between">
                            <button type="submit" class="btn btn-outline-primary-blue float-right">Update
                            </button>
                        </div>
                        {!! Form::close() !!}
                    </div>

                    <div class="card card-profile-feed">
                        <div class="card-header card-header-action">
                            <h5>Change Password</h5>
                        </div>
                        {!! Form::open(['method' => 'post','url'=>route('users.password',$user->id), 'id' => 'updatePassword','class'=>'needs-validation']) !!}
                        <div class="card-body">
                            @include('Administration::users.form-change-password')
                        </div>
                        <div class="card-footer text-right">
                            <button type="submit" class="btn btn-outline-primary-blue float-right">Change Password
                            </button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>


            </div>
        </div>
    </div>
@endsection
@push('styles')

@endpush

@push('scripts')
    <script>
        var xds = false;
        function checkRecentlyUsed(value) {
            self.xds = xds;
            $.ajax({
                url: '/recently_used_pw',
                type: 'POST',
                async: false,
                data: {
                    _token: "{{ csrf_token() }}",
                    password: value,
                },
                dataType: 'JSON',
                success: function (data) {
                    self.xds = data;
                }
            });
            return self.xds;
        }

        $.validator.addMethod(
            "checkRecentlyUsed",
            function (value , element){
                return checkRecentlyUsed(value);
            },
            "Not allowed to add 24 previous passwords as new password."
        );

        $.validator.addMethod(
            "regex",
            function (value, element, regexp) {
                var re = new RegExp(regexp);
                return this.optional(element) || re.test(value);
            },
            "Password must be of 8-14 characters in length and have one or more special character and one or more number and one or more uppercase character."
        );

        $("#updatePassword").validate({
            rules: {
                password: {
                    regex: "^(?=.*[0-9])(?=.*[!@#$%^&*])(?=.*[A-Z])(?=.*[a-z])[a-zA-Z0-9!@#$%^&*]{8,14}$",
                    minlength: 8,
                    checkRecentlyUsed: true
                },
                password_confirmation: {
                    regex: "^(?=.*[0-9])(?=.*[!@#$%^&*])(?=.*[A-Z])(?=.*[a-z])[a-zA-Z0-9!@#$%^&*]{8,14}$",
                    equalTo: "#password",
                    minlength: 8
                }
            }
        });
    </script>
@endpush
