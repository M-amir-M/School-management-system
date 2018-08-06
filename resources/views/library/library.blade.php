@extends('layouts.dashboard')
@section('title')
        <title>

    کتاب خانه
        </title>

@stop
@section('content')

    <section>
        <library-component auth-user="{{ $auth_user }}"></library-component>
    </section>
@stop