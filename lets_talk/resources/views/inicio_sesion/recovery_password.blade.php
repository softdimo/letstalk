@extends('layouts.layout')
@section('title', 'Recovery Password')

@section('content')
    <div class="limiter">
        <div class="container-login100">
            <div class="wrap-login100">
                <form class="login100-form validate-form" method="post"
                        action="{{route('recovery_password_email')}}" autocomplete="off">
                @csrf
                    <span class="login100-form-title p-b-26">Password Recovery</span>

                    <div class="wrap-input100 validate-input" data-validate="Email is required">
                        <input class="input100" type="email" name="pass_recovery" id="pass_recovery">
                        <span class="focus-input100" data-placeholder="Email"></span>
                    </div>

                    <div class="wrap-input100 validate-input" data-validate="Document Id is required">
                        <span class="btn-show-pass">
                            <i class="zmdi zmdi-eye"></i>
                        </span>
                        <input class="input100" type="text" name="numero_documento" id="numero_documento">
                        <span class="focus-input100" data-placeholder="Document Id"></span>
                    </div>

                    <div class="container-login100-form-btn">
                        <div class="wrap-login100-form-btn">
                            <div class="login100-form-bgbtn"></div>
                            <button class="login100-form-btn"  type="submit">
                                Send
                            </button>
                        </div>
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
            $("#pass_recovery").trigger('focus');
        });
    </script>
@endsection
