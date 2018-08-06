@extends('layouts.auth')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-5 m-x-auto pull-xs-none vamiddle">
                <div class="panel panel-primary">
                    <div class="panel-heading"><h3 align="center">ثبت نام</h3></div>
                    <div class="panel-body panel-form">
                        <form class="" role="form" method="POST" action="{{ url('/register') }}">
                            @include('auth.form')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
