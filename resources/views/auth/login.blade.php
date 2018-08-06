@extends('layouts.auth')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 m-x-auto pull-xs-none vamiddle">
                <div class="card-group ">
                    <div class="card p-a-2">
                        <div class="card-block">
                            <form class="form-horizontal" role="form" method="POST" action="{{ route('login') }}">
                                <h1>ورود</h1>
                                <p class="text-muted">وارد حساب کاربری خود شوید</p>
                                {{ csrf_field() }}

                                <div class="input-group m-b-1 {{ $errors->has('username') ? ' has-error' : '' }}">
                                <span class="input-group-addon"><i class="icon-user"></i>
                                </span>
                                    <input type="text" name="username" value="{{ old('username') }}" required autofocus
                                           class="form-control en login-inputs" placeholder="نام کاربری ">
                                    @if ($errors->has('username'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('username') }}</strong>
                                    </span>
                                    @endif
                                </div>

                                <div class="input-group m-b-2 {{ $errors->has('password') ? ' has-error' : '' }}">
                                <span class="input-group-addon"><i class="icon-lock"></i>
                                </span>
                                    <input name="password"
                                           required
                                           type="password" class="form-control en login-inputs" placeholder="رمز ورود">
                                    @if ($errors->has('password'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-4">
                                        <div class="checkbox checkbox-remember">
                                            <label class="switch switch-text switch-pill switch-primary-outline-alt">
                                                <input type="checkbox"
                                                       {{ old('remember') ? 'checked' : '' }} class="switch-input"
                                                       name="remember">
                                                <span class="switch-label" data-on="بله" data-off="خیر"></span>
                                                <span class="switch-handle"></span>
                                            </label>
                                            <span>مرا بخاطر بیاور</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xs-6">
                                        <button type="submit" class="btn btn-primary p-x-2">
                                            <i class="icon-login"></i>
                                            ورود
                                        </button>
                                    </div>
                                    {{--<div class="col-xs-6 text-xs-right">--}}
                                        {{--<a class="btn btn-link p-x-0" href="{{ route('password.request') }}">فراموشی رمز--}}
                                            {{--ورود</a>--}}
                                    {{--</div>--}}
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card card-inverse card-primary p-y-3 hidden-xs" style="width:44%">
                        <div class="card-block text-xs-center">
                            <div>
                                <h2>ثبت نام</h2>
                                <p>اگر در سامانه عضو نیستید به راحتی می توانید همین الان نام نویسی کنید.</p>
                                @if (Auth::guest())
                                    <a class="btn btn-primary active m-t-1" href="{{ route('register') }}">عضویت
                                        رایگان</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
