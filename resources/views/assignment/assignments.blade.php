@extends('layouts.dashboard')
@section('title')
    <title>

        تکالیف
    </title>

@stop
@section('content')

    <section>
        <assignment-component auth-user="{{ $auth_user }}"></assignment-component>
    </section>
@stop