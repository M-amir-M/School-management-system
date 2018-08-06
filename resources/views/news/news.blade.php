@extends('layouts.dashboard')
@section('style')
    <script src="//cdn.ckeditor.com/4.6.2/full/ckeditor.js"></script>
@stop
@section('title')
        <title>

    اخبار
        </title>

@stop
@section('content')
    <news-component auth-user="{{ $auth_user }}"></news-component>
@stop
@section('script')
@stop