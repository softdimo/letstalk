@extends('layouts.layout')
@section('title', 'BD Conection')
@section('css')
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
@stop

{{-- ===================================== --}}

@section('content')
    <h3 style="margin-bottom: 1em;">Data Base Connection State</h3>
    <p style="font-size: 18px">It wasn't possible to connect to the database.</p>
@stop
