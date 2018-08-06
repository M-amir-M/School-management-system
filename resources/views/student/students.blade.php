@extends('layouts.dashboard')
@section('title')
    <title>
        دانش آموزان
    </title>

@stop
@section('content')
    <section>
        <student-component auth-user="{{ $auth_user }}"></student-component>
    </section>
@stop
