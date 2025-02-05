@extends('layouts.layout')
@section('title', 'Trainer`s Login')
@section('content')

<div class="limiter">
    <div class="container-login100">
        <div class="wrap-login100">
            <form class="login100-form validate-form" method="post" action="{{route('login.store')}}" autocomplete="off">
            @csrf
                <span class="login100-form-title p-b-26">
                    Welcome
                </span>

                <div class="wrap-input100 validate-input" data-validate="Username is required">
                    <input class="input100" type="text" name="username" id="username">
                    <span class="focus-input100" data-placeholder="Username"></span>
                </div>

                <div class="wrap-input100 validate-input" data-validate="Password is required">
                    <span class="btn-show-pass">
                        <i class="zmdi zmdi-eye"></i>
                    </span>
                    <input class="input100" type="password" name="pass" id="pass">
                    <span class="focus-input100" data-placeholder="Password"></span>
                </div>

                {{-- <div class="text-right">
                    <span class="txt1">
                        <a class="txt2" href="{{route('reset_password')}}">
                            Forgot Password
                        </a>
                    </span>
                </div> --}}

                <div class="container-login100-form-btn">
                    <div class="wrap-login100-form-btn">
                        <div class="login100-form-bgbtn"></div>
                        <button class="login100-form-btn"  type="submit">
                            Access
                        </button>
                    </div>
                </div>

                <div class="validate-input mt-5" style="margin-top: 4rem" data-validate="">
                    <a href="{{route('recovery_password')}}">Password Recovery</a>
                </div>

                <div class="text-left p-t-50">
                    <span class="txt1">
                        <a class="txt2 text-white btn btn-primary" href="/">
                            <i class="fa fa-arrow-left" aria-hidden="true"></i>&nbsp;Back
                        </a>
                    </span>
                </div>
            </form>
        </div>
    </div>
</div>

@stop
@section('scripts')
<script>

    $( document ).ready(function() {

        $("#username").trigger('focus');
    });

</script>
@endsection
