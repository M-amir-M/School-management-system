@extends('layouts.dashboard')
@section('title')
        <title>

    نظرسنجی ها
        </title>

@stop
@section('content')
    <section>
        <poll-component auth-user="{{ $auth_user }}"></poll-component>
    </section>
@stop