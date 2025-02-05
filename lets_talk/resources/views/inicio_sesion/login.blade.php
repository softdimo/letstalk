@extends('layouts.layout')
@section('title', 'Login')
@section('css')
<link rel="stylesheet" href="{{ asset('css/custom.css') }}">
@stop
@section('content')

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <h1 class="text-center text-uppercase">Welcome</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12" style="height: 30px;">
            &nbsp;
        </div>
    </div>

    <div class="row">
        <div class="col-xs-4 col-xs-offset-3 col-sm-4 col-sm-offset-3 col-md-3 col-md-offset-3">
            <div class="panel panel-primary text-uppercase text-center">
                <div class="panel-body">
                    <a href="{{route('login.index')}}" style="color: #337AB7"><b>Trainer</b></a>
                </div>
            </div>
        </div>

        <div class="col-xs-4 col-sm-4 col-md-3 text-uppercase text-center">
            <div class="panel panel-primary">
                <div class="panel-body">
                    <a href="{{route('login_estudiante')}}" style="color: #337AB7"><b>Estudiante</b></a>
                </div>
            </div>
        </div>
    </div>

@stop
@section('scripts')
<script>

</script>
@stop
