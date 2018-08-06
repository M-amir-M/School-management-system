@extends('layouts.dashboard')
@section('title')
        <title>

    امتحان آنلاین
        </title>

@stop
@section('content')
    <section>
        <onlineexam-component auth-user="{{ $auth_user }}"></onlineexam-component>
    </section>
@stop