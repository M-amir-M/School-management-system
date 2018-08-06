@extends('layouts.dashboard')
@section('title')
        <title>

    برنامه کلاسی
        </title>

@stop
@section('content')
    <section>
        <classschedule-component auth-user="{{ $auth_user }}"></classschedule-component>
    </section>
@stop