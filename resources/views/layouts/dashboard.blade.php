<!DOCTYPE html>
<html lang="IR-fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @yield('title')

    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Main styles for this application -->
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Roboto:300,400,500,700,400italic">
    <link rel="stylesheet" href="//fonts.googleapis.com/icon?family=Material+Icons">

    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.6.10/sweetalert2.min.css">
    <link rel="stylesheet" href="https://unpkg.com/vue-multiselect@2.0.0/dist/vue-multiselect.min.css">

    <!-- Include a polyfill for ES6 Promises (optional) for IE11, UC Browser and Android browser support -->
@yield('style')

<!-- Font Awesome -->
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->


    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/vendor.css') }}" rel="stylesheet">
    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>
</head>

<body class="navbar-fixed sidebar-nav fixed-nav">


<!-- Modal year -->
<div id="changeYearModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">تغییر سال تحصیلی</h4>
            </div>
            <div class="modal-body">
                <form method="post" action="{{route('year.change')}}">
                    {{ csrf_field() }}
                    <select name="year" class="form-control">
                        @foreach(years() as $year)
                            <option value="{{$year->id}}">
                                {{$year->year}}@if($year->id == \Illuminate\Support\Facades\Auth::user()->year_id)-در
                                این سال هستید@endif
                            </option>
                        @endforeach
                    </select>
                    <br>
                    <button type="submit" class="btn btn-success">تغییر سال</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">بستن</button>
            </div>
        </div>

    </div>
</div>

<div id="app">

    <div v-show="!loading">

        <audio id="noty_audio">
            <source src="{{ asset("audio/notify.mp3") }}">
            <source src="{{ asset("audio/notify.ogg") }}">
        </audio>
        <header class="navbar">
            <div class="container-fluid">
                <button class="navbar-toggler mobile-toggler hidden-lg-up" type="button"><span class="fa fa-bars"></span></button>
                <a class="pull-left" href="http://asasite.com/dashboard">
                    <img src="https://scontent-frt3-1.cdninstagram.com/t51.2885-19/s150x150/14280568_1768782533359911_1406417411_a.jpg" alt="asasite.com" width="50" height="50" ></a>

                <ul class="nav navbar-nav hidden-md-down">
                    <li class="nav-item">
                        <a class="nav-link navbar-toggler layout-toggler" href="#"><span class="fa fa-bars"></span></a>
                    </li>

                    <!--<li class="nav-item p-x-1">
                        <a class="nav-link" href="#">داشبورد</a>
                    </li>
                    <li class="nav-item p-x-1">
                        <a class="nav-link" href="#">Users</a>
                    </li>
                    <li class="nav-item p-x-1">
                        <a class="nav-link" href="#">Settings</a>
                    </li>-->
                </ul>
                <ul class="nav navbar-nav pull-left hidden-md-down">
                    <!-- Trigger the modal with a button -->

                    <li class="nav-item top-icon">
                        <a class="nav-link" href="#changeYearModal" data-toggle="modal"
                           data-target="#changeYearModal"><i
                                    class="fa fa-calendar-check-o"></i></a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link aside-toggle" href="#"><i class="icon-bell"></i><span
                                    class="tag tag-pill tag-danger">5</span></a>
                    </li>
                    <li class="nav-item top-icon">
                        <a class="nav-link" href="#"><i class="icon-location-pin"></i></a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle nav-link" data-toggle="dropdown" href="#" role="button"
                           aria-haspopup="true" aria-expanded="false">
                            <img src="/images/avatars/{{ Auth::user()->photo }}" class="img-avatar"
                                 alt="admin@bootstrapmaster.com">
                            @if(Auth::user()->role == 'admin')
                                <span class="hidden-md-down">مدیر</span>
                            @endif
                            @if(Auth::user()->role == 'student')
                                <span class="hidden-md-down">دانش آموز</span>
                            @endif
                            @if(Auth::user()->role == 'teacher')
                                <span class="hidden-md-down">دبیر</span>
                            @endif
                            @if(Auth::user()->role == 'parent')
                                <span class="hidden-md-down">ولی</span>
                            @endif
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <div class="dropdown-header text-xs-center">
                                <strong>تنظیمات</strong>
                            </div>
                            <a class="dropdown-item" href="#"><i class="fa fa-user"></i> پروفایل</a>
                            <a class="dropdown-item aside-toggle" href="#"><i class="fa fa-wrench"></i> تنظیمات</a>
                            <!--<a class="dropdown-item" href="#"><i class="fa fa-usd"></i> Payments<span class="tag tag-default">42</span></a>-->
                            <div class="divider"></div>
                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                <i class="fa fa-lock"></i> خروج</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                  style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link navbar-toggler aside-toggle" href="#">&#9776;</a>
                    </li>

                </ul>
            </div>
        </header>
        <div class="sidebar">
            <nav class="sidebar-nav">
                <ul class="nav">
                    <li class="nav-item">
                        <a href="/dashboard" class="nav-link">
                            <i class="fa fa-dashboard"></i> <span>داشبورد</span>
                        </a>
                    </li>
                    @if(Auth::user()->role == 'admin')
                        <li class="nav-item">
                            <a href="/teachers" class="nav-link">
                                <i class="fa fa-suitcase"></i>
                                <span>معلمین</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/parents" class="nav-link">
                                <i class="fa fa-group"></i>
                                <span>والدین</span>
                            </a>
                        </li>
                    @endif
                    @if(getAuth()->role != 'student')
                        <li class="nav-item">
                            <a href="/students" class="nav-link">
                                <i class="fa fa-group"></i>
                                <span>دانش آموزان</span>
                            </a>
                        </li>
                    @endif
                    @if(Auth::user()->role == 'admin')
                        <li class="nav-item">
                            <a href="/subjects" class="nav-link">
                                <i class="fa fa-book"></i>
                                <span>درس ها</span>
                            </a>
                        </li>
                    @endif
                    @if(Auth::user()->role == 'student')
                        <li class="nav-item">
                            <a href="/students" class="nav-link">
                                <i class="fa fa-sitemap"></i>
                                <span>نمرات و کارنامه</span>
                            </a>
                        </li>
                    @endif
                    @if(Auth::user()->role == 'admin')
                        <li class="nav-item">
                            <a href="/clases" class="nav-link">
                                <i class="fa fa-sitemap"></i>
                                <span>کلاس ها</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/fields" class="nav-link">
                                <i class="fa fa-sitemap"></i>
                                <span>رشته ها</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/grades" class="nav-link">
                                <i class="fa fa-sitemap"></i>
                                <span>پایه ها</span>
                            </a>
                        </li>
                    @endif
                    @if(Auth::user()->role != 'parent')

                    <li class="nav-item">
                        <a href="/class-schedules" class="nav-link">
                            <i class="fa fa-list"></i>
                            <span>برنامه کلاسی</span>
                        </a>
                    </li>
                    @endif
                    <li class="nav-item nav-dropdown">
                        <a class="nav-link nav-dropdown-toggle" href="#"><i class="fa fa-bar-chart"></i> حضور غیاب</a>
                        <ul class="nav-dropdown-items">
                            @if(Auth::user()->role == 'admin'|| Auth::user()->role == 'teacher')
                                <li class="nav-item">
                                    <a href="/attendances" class="nav-link">
                                        <i class="fa fa-check"></i> حضور غیاب
                                    </a>
                                </li>
                            @endif
                            <li class="nav-item">
                                <a href="" class="nav-link">
                                    <i class="fa fa-bar-chart"></i> گزارش حضور غیاب
                                </a>
                            </li>
                        </ul>
                    </li>
                    @if(Auth::user()->role != 'parent')
                    <li class="nav-item">
                        <a href="/online-exams" class="nav-link">
                            <i class="fa fa-file-text-o"></i>
                            <span>امتحان آنلاین</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/assignments" class="nav-link">
                            <i class="fa fa-file-pdf-o"></i>
                            <span>تکالیف</span>
                        </a>
                    </li>
                    @endif
                    <li class="nav-item">
                        <a href="/library" class="nav-link">
                            <i class="fa fa-folder-open"></i>
                            <span>کتابخانه</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/news" class="nav-link">
                            <i class="fa fa-bullhorn"></i>
                            <span>اخبار</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/polls" class="nav-link">
                            <i class="fa fa-tasks"></i>
                            <span>نظر سنجی</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fa fa-film"></i>
                            <span>گالری (نسخه بعدی)</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fa fa-credit-card"></i>
                            <span>پرداخت ها (نسخه بعدی)</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fa fa-inbox"></i>
                            <span>پیام ها (نسخه بعدی)</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fa fa-inbox"></i>
                            <span>پیام ها (نسخه بعدی)</span>
                        </a>
                    </li>
                    @if(Auth::user()->role == 'admin')

                        <li class="nav-item nav-dropdown">
                            <a class="nav-link nav-dropdown-toggle" href="#"><i class="fa fa-bar-chart"></i>وظایف مدیریت</a>
                            <ul class="nav-dropdown-items">
                                <li class="nav-item">
                                    <a href="/years" class="nav-link">
                                        <i class="fa fa-calendar-check-o"></i>سال تحصیلی
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="/students/make-workbooks" class="nav-link">
                                        <i class="fa fa-calendar-check-o"></i>صدور کارنامه
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="/promotion" class="nav-link">
                                        <i class="fa fa-arrow-up"></i>
                                        <span>  ارتقا دانش آموزان </span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="fa fa-gear"></i>
                                        <span>تنظیمات (نسخه بعدی)</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </nav>
        </div>
        <!-- Main content -->
        <main class="main">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">خانه</li>
                <li class="breadcrumb-item"><a href="#">مدیریت</a></li>
                <li class="breadcrumb-item active">داشبورد</li>
                <!-- Breadcrumb Menu-->
                <li class="breadcrumb-menu">
                    <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                        <a class="btn btn-secondary" href="#"><i class="icon-speech"></i></a>
                        <a class="btn btn-secondary" href="/dashboard"><i class="icon-graph"></i> &nbsp;داشبورد</a>
                        <a class="btn btn-secondary" href="#"><i class="icon-settings"></i> &nbsp;تنظیمات</a>
                    </div>
                </li>
            </ol>
            <div class="row">

                <div class="container-fluid">

                    <div class="animated fadeIn">
                        @yield('content')
                    </div>

                </div>
                <!--/.container-fluid-->
            </div>
        </main>

        <aside class="aside-menu">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#timeline" role="tab"><i
                                class="icon-list"></i></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#messages" role="tab"><i class="icon-speech"></i></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#settings" role="tab"><i class="icon-settings"></i></a>
                </li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
                <div class="tab-pane active" id="timeline" role="tabpanel">
                    <div class="text-center">نسخه بعدی</div>
                </div>
                <div class="tab-pane p-a-1" id="messages" role="tabpanel">
                    <div class="text-center">پیام ها</div>

                </div>
                <div class="tab-pane p-a-1" id="settings" role="tabpanel">
                    <h6>تنظیمات</h6>
                </div>
            </div>
        </aside>

        <footer class="footer">
        <span class="text-left">
            <span>همه حقوق این پرتال برای <a href="http://asasite.com">آسا سایت</a> محفوظ می باشد.</span>
        </span>
            <span class="pull-left">
            <a href="http://asasite.com">آسا سایت</a> &copy; 2016 .
        </span>
        </footer>
    </div>
    <div v-show="loading" class="text-center">

        <div class="total-spinner">
            <md-spinner :md-stroke="1" :md-size="150" md-indeterminate></md-spinner>
        </div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.6.10/sweetalert2.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/core-js/2.4.1/core.js"></script>

<script src="/js/app.js"></script>
<!-- CoreUI main scripts -->

<script src="/admin/js/app.js"></script>

@yield('script')

</body>

</html>
