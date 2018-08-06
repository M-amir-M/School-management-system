@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-1">
            </div>
            <div class="col-md-10">
                <div class="container">

                    <!-- Page header -->
                    <div class="page-header">
                        <h2>با پرتال<span class="text-info"> آسا</span> یک مدرسه ی <span
                                    class="text-success"> هوشمند </span> داشته باشید.</h2>

                    </div>
                    <!-- /Page header -->
                    <div>

                        <a class="btn btn-info" style="margin-bottom: 5px;" href="#dashboard">داشبورد</a>
                        <a class="btn btn-info" style="margin-bottom: 5px;" href="#teachers">دبیران</a>
                        <a class="btn btn-info" style="margin-bottom: 5px;" href="#parents">اولیا</a>
                        <a class="btn btn-info" style="margin-bottom: 5px;" href="#students">دانش آموزان</a>
                        <a class="btn btn-info" style="margin-bottom: 5px;" href="#charts">نمودار ها</a>
                        <a class="btn btn-info" style="margin-bottom: 5px;" href="#workbooks">کارنامه ها</a>
                        <a class="btn btn-info" style="margin-bottom: 5px;" href="#promote">سیستم ارتقا</a>
                        <a class="btn btn-info" style="margin-bottom: 5px;" href="#class_schedules">برنامه کلاسی</a>
                        <a class="btn btn-info" style="margin-bottom: 5px;" href="#attendances">حضور غیاب</a>
                        <a class="btn btn-info" style="margin-bottom: 5px;" href="#onlineexams">امتحان آنلاین</a>
                        <a class="btn btn-info" style="margin-bottom: 5px;" href="#assignments">تکالیف</a>
                        <a class="btn btn-info" style="margin-bottom: 5px;" href="#library">کتابخانه</a>
                        <a class="btn btn-info" style="margin-bottom: 5px;" href="#newses">اخبار</a>
                        <a class="btn btn-info" style="margin-bottom: 5px;" href="#polls">سیستم نظرسنجی</a>
                    </div>
                    <!-- Timeline -->
                    <div class="timeline">

                        <!-- Line component -->
                        <div class="line text-muted"></div>

                        <!-- Separator -->
                        <div class="separator text-muted">
                            <time>امکانات پرتال مدرسه آسا</time>
                        </div>
                        <!-- /Separator -->

                        <!-- Dashboard -->
                        <article id="dashboard" class="panel panel-primary">

                            <div class="panel-heading icon hvr-buzz-out">
                                <i class="fa fa-dashboard"></i>
                            </div>

                            <div class="panel-heading">
                                <h2 class="panel-title hvr-grow">داشبورد</h2>
                            </div>

                            <div class="panel-body">
                                <img class="img-thumbnail img-responsive" src="/images/homeimages/dashboard.png"
                                     alt="داشبورد">
                            </div>

                            <div class="panel-footer">
                                <small></small>
                            </div>

                        </article>
                        <!-- /Dashboard -->

                        <!-- Teachers -->
                        <article id="teachers" class="panel panel-success">

                            <div class="panel-heading icon hvr-buzz-out">
                                <i class="fa fa-briefcase"></i>
                            </div>

                            <div class="panel-heading">
                                <h2 class="panel-title">دبیران</h2>
                            </div>

                            <div class="panel-body">
                                <img class="img-thumbnail img-responsive" src="/images/homeimages/teachers.png"
                                     alt="دبیران">
                            </div>

                            <div class="panel-footer">
                                <small></small>
                            </div>

                        </article>
                        <!-- /Teachers -->

                        <!-- Parents -->
                        <article id="parents" class="panel panel-default">

                            <div class="panel-heading icon hvr-buzz-out">
                                <i class="fa fa-user"></i>
                            </div>

                            <div class="panel-heading">
                                <h2 class="panel-title">اولیا</h2>
                            </div>

                            <div class="panel-body">
                                <img class="img-thumbnail img-responsive" src="/images/homeimages/parents.png"
                                     alt="اولیا">
                            </div>

                            <div class="panel-footer">
                                <small></small>
                            </div>

                        </article>
                        <!-- /Parents -->

                        <!-- Students -->
                        <article id="students" class="panel panel-danger">

                            <div class="panel-heading icon hvr-buzz-out">
                                <i class="fa fa-users"></i>
                            </div>

                            <div class="panel-heading">
                                <h2 class="panel-title">دانش آموزان</h2>
                            </div>

                            <div class="panel-body">
                                <img class="img-thumbnail img-responsive" src="/images/homeimages/students.png"
                                     alt="دانش آموزان">

                            </div>

                            <div class="panel-footer">
                                <small></small>
                            </div>

                        </article>
                        <!-- /Students -->

                        <!-- Charts -->
                        <article id="charts" class="panel panel-warning">

                            <div class="panel-heading icon hvr-buzz-out">
                                <i class="fa fa-book"></i>
                            </div>

                            <div class="panel-heading">
                                <h2 class="panel-title">نمودار نمرات</h2>
                            </div>

                            <div class="panel-body">
                                <img class="img-thumbnail img-responsive" src="/images/homeimages/monthchart.png"
                                     alt="دانش آموزان">
                                <img class="img-thumbnail img-responsive" src="/images/homeimages/yearchart.png"
                                     alt="دانش آموزان">
                            </div>

                            <div class="panel-footer">
                                <small></small>
                            </div>

                        </article>
                        <!-- /Charts -->

                        <!-- Workbooks -->
                        <article id="workbooks" class="panel panel-info">

                            <div class="panel-heading icon hvr-buzz-out">
                                <i class="fa fa-building"></i>
                            </div>

                            <div class="panel-heading">
                                <h2 class="panel-title">کارنامه ماهانه و سالانه</h2>
                            </div>

                            <div class="panel-body">
                                <img class="img-thumbnail img-responsive" src="/images/homeimages/monthwb.png"
                                     alt="دانش آموزان">
                                <img class="img-thumbnail img-responsive" src="/images/homeimages/yearwb.png"
                                     alt="دانش آموزان">

                            </div>

                            <div class="panel-footer">
                                <small></small>
                            </div>

                        </article>
                        <!-- /Workbooks -->

                        <!-- Promote -->
                        <article id="promote" class="panel panel-info">

                            <div class="panel-heading icon hvr-buzz-out">
                                <i class="fa fa-building"></i>
                            </div>

                            <div class="panel-heading">
                                <h2 class="panel-title">سیستم ارتقا دانش آموزان</h2>
                            </div>

                            <div class="panel-body">
                                <img class="img-thumbnail img-responsive" src="/images/homeimages/promote.png"
                                     alt="دانش آموزان">

                            </div>

                            <div class="panel-footer">
                                <small></small>
                            </div>

                        </article>
                        <!-- /Promote -->

                        <!-- Class Schedules -->
                        <article id="class_schedules" class="panel panel-primary">

                            <div class="panel-heading icon hvr-buzz-out">
                                <i class="fa fa-calendar"></i>
                            </div>

                            <div class="panel-heading">
                                <h2 class="panel-title">برنامه کلاسی</h2>
                            </div>

                            <div class="panel-body">
                                <img class="img-thumbnail img-responsive" src="/images/homeimages/classSchedules.png"
                                     alt="برنامه کلاسی">
                            </div>

                            <div class="panel-footer">
                                <small></small>
                            </div>

                        </article>
                        <!-- /Class Schedules -->


                        <!-- Attendances -->
                        <article id="attendances" class="panel panel-success">

                            <div class="panel-heading icon hvr-buzz-out">
                                <i class="fa fa-check"></i>
                            </div>

                            <div class="panel-heading">
                                <h2 class="panel-title">حضور غیاب</h2>
                            </div>

                            <div class="panel-body">
                                <img class="img-thumbnail img-responsive" src="/images/homeimages/attendance.png"
                                     alt="حضور غیاب">
                            </div>

                            <div class="panel-footer">
                                <small></small>
                            </div>

                        </article>
                        <!-- /Attendances -->


                        <!-- Online Exams -->
                        <article id="onlineexams" class="panel panel-default">

                            <div class="panel-heading icon hvr-buzz-out">
                                <i class="fa fa-file-text-o"></i>
                            </div>

                            <div class="panel-heading">
                                <h2 class="panel-title">امتحان آنلاین</h2>
                            </div>

                            <div class="panel-body">
                                <img class="img-thumbnail img-responsive" src="/images/homeimages/onlineexam.png"
                                     alt="امتحان آنلاین">
                            </div>

                            <div class="panel-footer">
                                <small></small>
                            </div>

                        </article>
                        <!-- /Online Exams -->


                        <!-- Assignments -->
                        <article id="assignments" class="panel panel-danger">

                            <div class="panel-heading icon hvr-buzz-out">
                                <i class="fa fa-pencil"></i>
                            </div>

                            <div class="panel-heading">
                                <h2 class="panel-title">تکالیف</h2>
                            </div>

                            <div class="panel-body">
                                <img class="img-thumbnail img-responsive" src="/images/homeimages/assignments.png"
                                     alt="تکالیف">
                            </div>

                            <div class="panel-footer">
                                <small></small>
                            </div>

                        </article>
                        <!-- /Assignments -->


                        <!-- Library -->
                        <article id="library" class="panel panel-warning">

                            <div class="panel-heading icon hvr-buzz-out">
                                <i class="fa fa-building-o"></i>
                            </div>

                            <div class="panel-heading">
                                <h2 class="panel-title">کتابخانه</h2>
                            </div>

                            <div class="panel-body">
                                <img class="img-thumbnail img-responsive" src="/images/homeimages/library.png"
                                     alt="کتابخانه">
                            </div>

                            <div class="panel-footer">
                                <small></small>
                            </div>

                        </article>
                        <!-- /Library -->


                        <!-- Newses -->
                        <article id="newses" class="panel panel-info">

                            <div class="panel-heading icon hvr-buzz-out">
                                <i class="fa fa-newspaper-o"></i>
                            </div>

                            <div class="panel-heading">
                                <h2 class="panel-title">اخبار</h2>
                            </div>

                            <div class="panel-body">
                                <img class="img-thumbnail img-responsive" src="/images/homeimages/news.png" alt="اخبار">
                            </div>

                            <div class="panel-footer">
                                <small></small>
                            </div>

                        </article>
                        <!-- /Newses -->


                        <!-- Polls -->
                        <article id="polls" class="panel panel-success">

                            <div class="panel-heading icon hvr-buzz-out">
                                <i class="fa fa-tasks"></i>
                            </div>

                            <div class="panel-heading">
                                <h2 class="panel-title">سیستم نظرسنجی</h2>
                            </div>

                            <div class="panel-body">
                                <img class="img-thumbnail img-responsive" src="/images/homeimages/polls.png"
                                     alt="نظرسنجی">

                            </div>

                            <div class="panel-footer">
                                <small></small>
                            </div>

                        </article>
                        <!-- /Polls -->


                        {{--<!-- Separator -->--}}
                        {{--<div class="separator text-muted">--}}
                        {{--<time>25. 3. 2015</time>--}}
                        {{--</div>--}}
                        {{--<!-- /Separator -->--}}

                        {{--<!-- Panel -->--}}
                        {{--<article class="panel panel-success">--}}

                        {{--<!-- Icon -->--}}
                        {{--<div class="panel-heading icon">--}}
                        {{--<i class="glyphicon glyphicon-plus"></i>--}}
                        {{--</div>--}}
                        {{--<!-- /Icon -->--}}

                        {{--<!-- Heading -->--}}
                        {{--<div class="panel-heading">--}}
                        {{--<h2 class="panel-title">New content added</h2>--}}
                        {{--</div>--}}
                        {{--<!-- /Heading -->--}}

                        {{--<!-- Body -->--}}
                        {{--<div class="panel-body">--}}
                        {{--Anything you can do with <code>.panel</code>, can be done in timeline too!--}}
                        {{--</div>--}}
                        {{--<!-- /Body -->--}}

                        {{--<!-- List group -->--}}
                        {{--<ul class="list-group">--}}
                        {{--<li class="list-group-item">Like</li>--}}
                        {{--<li class="list-group-item">list</li>--}}
                        {{--<li class="list-group-item">groups</li>--}}
                        {{--<li class="list-group-item">and</li>--}}
                        {{--<li class="list-group-item">tables</li>--}}
                        {{--</ul>--}}

                        {{--</article>--}}
                        {{--<!-- /Panel -->--}}

                        {{--<!-- Panel -->--}}
                        {{--<article class="panel panel-info panel-outline">--}}

                        {{--<!-- Icon -->--}}
                        {{--<div class="panel-heading icon">--}}
                        {{--<i class="glyphicon glyphicon-info-sign"></i>--}}
                        {{--</div>--}}
                        {{--<!-- /Icon -->--}}

                        {{--<!-- Body -->--}}
                        {{--<div class="panel-body">--}}
                        {{--That is all.--}}
                        {{--</div>--}}
                        {{--<!-- /Body -->--}}

                        {{--</article>--}}
                        {{--<!-- /Panel -->--}}

                    </div>
                    <!-- /Timeline -->

                </div>
            </div>
        </div>
    </div>
    </div>
@stop