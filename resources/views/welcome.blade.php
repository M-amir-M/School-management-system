@extends('layouts.app')

@section('content')
    <header id="header">
        <nav id="main-menu" class="navbar navbar-default navbar-fixed-top" role="banner">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="/"><img src="images/logo.png" alt="asasite" width="70"
                                                          height="70"></a>
                </div>

                <div class="collapse navbar-collapse navbar-right">
                    <ul class="nav navbar-nav">
                        @if (Auth::guest())
                            <li class="scroll"><a href="/login">ورود</a></li>
                        @else
                            <li class="scroll"><a href="/dashboard">داشبورد</a></li>
                        @endif
                        <li class="scroll"><a href="#get-in-touch">تماس با ما</a></li>
                        <li class="scroll"><a href="#about">درباره ما</a></li>
                        <li class="scroll"><a href="#portfolio">تصاویر</a></li>
                        <li class="scroll"><a href="#services"> امکانات</a></li>
                        <li class="scroll"><a href="#features">ویژگی ها</a></li>
                        <li class="scroll active"><a href="#home">خانه</a></li>
                    </ul>
                </div>
            </div><!--/.container-->
        </nav><!--/nav-->
    </header><!--/header-->

    <section id="main-slider">
        <div class="owl-carousel">
            <div class="item" style="background-image: url(images/slider/bg1.jpg);">
                <div class="slider-inner">
                    <div class="container">
                        <div class="row">
                            {{--<div class="col-sm-6"></div>--}}
                            <div class="col-sm-6">
                                <div class="carousel-content">
                                    <h2>سیستم جامع مدیریت هوشمند مدرسه</h2>
                                    <p>پرتال آسا جهت هوشمند سازی مدارس طراحی شده که به صورت اتوماسیون و هوشمند امکانات
                                        بی نظیر مدیریتی را برای مدیریت , دبیران , اولیا و دانش آموزان قرار می دهد</p>

                                    @if (Auth::guest())
                                        <a class="btn btn-primary btn-lg" href="/login">ورود به پرتال دمو</a>
                                    @else
                                        <a class="btn btn-primary btn-lg" href="/dashboard">داشبورد</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!--/.item-->
            <div class="item" style="background-image: url(images/slider/bg2.jpg);">
                <div class="slider-inner">
                    <div class="container">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="carousel-content">
                                    <h2 style="color: #000;">اپلیکیشن اندروید</h2>
                                    <p style="color: #000">
                                        نرم افزار موبایل سامانه آسا با هدف استفاده بر روی گوشی‌های هوشمند و تبلت‌ها طراحی گردیده و
                                        برای سیستم عامل اندروید موجود می باشد
                                        که امکان دسترسی به قابلیت‌های منحصر به فرد سامانه آسا
                                        را در هر لحظه و در هرجا برای دانش آموزان و والدین آنها فراهم می کند </p>
                                    {{--<a class="btn btn-primary btn-lg" href="#">Read More</a>--}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!--/.item-->
        </div><!--/.owl-carousel-->
    </section><!--/#main-slider-->

    <section id="features">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title text-center wow fadeInDown">ویژگی های برجسته نسبت به بقیه سیستم ها</h2>
                <p class="first-p  text-center wow fadeInDown">« قطعا پرتال آسا ویژگی های کم نظیری نسبت به سایر سیستم
                    های مدیریت مدارس دارد که مهمترین آنها به شرح زیر است »</p>
            </div>
            <div class="row">
                <div class="col-sm-6 wow fadeInLeft">
                    <img class="img-responsive" src="images/main-feature.png" alt="">
                </div>
                <div class="col-sm-6">
                    <div class="media service-box wow fadeInRight">
                        <div class="pull-left">
                            <i class="fa fa-laptop"></i>
                        </div>
                        <div class="media-body">
                            <h4 class="media-heading">تجربه کاربری راحت</h4>
                            <p>پرتال آسا طوری طراحی شده که هرنوع کاربری که شناخت کافی به پرتال نداشته باشد بتواند به
                                راحتی با پرتال رابطه برقرار کند</p>
                        </div>
                    </div>

                    <div class="media service-box wow fadeInRight">
                        <div class="pull-left">
                            <i class="fa fa-paint-brush"></i>
                        </div>
                        <div class="media-body">
                            <h4 class="media-heading">ظاهر زیبا</h4>
                            <p>بر خلاف تصور بعضی ها شکیل بودن بسیار خصیصه مهم هست که باعث علاقه هرچه بیشتر کاربر برای
                                استفاده از سیستم میشود.</p>
                        </div>
                    </div>

                    <div class="media service-box wow fadeInRight">
                        <div class="pull-left">
                            <i class="fa fa-headphones"></i>
                        </div>
                        <div class="media-body">
                            <h4 class="media-heading">پشتیبانی و بروزرسانی</h4>
                            <p>تیم آسا پشتیبانی همیشگی از پرتال را دارد و همیشه برای افزودن ویژگی های هوشمند جدید و
                                بروزرسانی در تلاش است</p>
                        </div>
                    </div>

                    <div class="media service-box wow fadeInRight">
                        <div class="pull-left">
                            <i class="fa fa-tasks"></i>
                        </div>
                        <div class="media-body">
                            <h4 class="media-heading">امکانات کاربردی</h4>
                            <p>امکانات بسیار کاربردی جهت هوشمندسازی هرچه بیشتر مدرسه و تحلیل روند پیشرفت دانش آموزان</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="cta2">
        <div class="container">
            <div class="text-center">
                <h2 class="wow fadeInUp" data-wow-duration="300ms" data-wow-delay="0ms">اپلیکیشن موبایل</h2>
                <p class="wow fadeInUp" data-wow-duration="300ms" data-wow-delay="100ms"> اپلیکیشن آسا برای ایجاد رابطه بهتر با سامانه ساخته شده</p>
                <p class="wow fadeInUp" data-wow-duration="300ms" data-wow-delay="200ms">تا اولیا و دانش آموزان به راحتی با مدرسه تعامل داشته باشند</p>
                <img class="img-responsive wow fadeIn" src="images/cta2/cta2-img.png" alt="" data-wow-duration="300ms"
                     data-wow-delay="300ms">
            </div>
        </div>
    </section>

    <section id="services">
        <div class="container">

            <div class="section-header">
                <h2 class="section-title text-center wow fadeInDown">امکانات پرتال آسا</h2>
                <p style="text-align: center" class=" first-p text-center wow fadeInDown">امکاناتی که پرتال آسا در اختیار کاربران خود قرار می دهد
                    به شرح زیر میباشد.</p>
            </div>

            <div class="row">
                <div class="features">

                    <div class="col-md-4 col-sm-6 wow fadeInUp" data-wow-duration="300ms" data-wow-delay="0ms">
                        <div class="media service-box">
                            <div class="pull-left">
                                <i class="fa fa-dashboard"></i>
                            </div>
                            <div class="media-body">
                                <h4 class="media-heading">داشبورد</h4>
                                <p>داشبورد پرتال شامل امکانات کاربردی مثل دسترسی به پروفایل , اخبار و رویداد تازه , پیام
                                    ها و ... برای هر نوع کاربر می باشد</p>
                            </div>
                        </div>
                    </div><!--/.col-md-4-->

                    <div class="col-md-4 col-sm-6 wow fadeInUp" data-wow-duration="300ms" data-wow-delay="100ms">
                        <div class="media service-box">
                            <div class="pull-left">
                                <i class="fa fa-briefcase"></i>
                            </div>
                            <div class="media-body">
                                <h4 class="media-heading">دبیران</h4>
                                <p>به این قسمت مدیریت دسترسی دارد که شامل مشاهده اطلاعات اساتید و افزودن دبیر و ویرایش
                                    اطلاعات آنها می باشد.</p>
                            </div>
                        </div>
                    </div><!--/.col-md-4-->

                    <div class="col-md-4 col-sm-6 wow fadeInUp" data-wow-duration="300ms" data-wow-delay="200ms">
                        <div class="media service-box">
                            <div class="pull-left">
                                <i class="fa fa-users"></i>
                            </div>
                            <div class="media-body">
                                <h4 class="media-heading">اولیا</h4>
                                <p>به این قسمت مدیریت دسترسی دارد که شامل مشاهده اطلاعات اولیا و افزودن ولی و ویرایش
                                    اطلاعات آنها می باشد.</p>
                            </div>
                        </div>
                    </div><!--/.col-md-4-->

                    <div class="col-md-4 col-sm-6 wow fadeInUp" data-wow-duration="300ms" data-wow-delay="300ms">
                        <div class="media service-box">
                            <div class="pull-left">
                                <i class="fa fa-mortar-board"></i>
                            </div>
                            <div class="media-body">
                                <h4 class="media-heading">دانش آموزان</h4>
                                <p>دبیران به اطلاعات همه دانش آموزان اعم از نمرات و کارنامه و ... دبیران به اطلاعات دانش
                                    آموزان کلاس خود و اولیا به اطلاعات فرزندان خود دسترسی دارند</p>
                            </div>
                        </div>
                    </div><!--/.col-md-4-->

                    <div class="col-md-4 col-sm-6 wow fadeInUp" data-wow-duration="300ms" data-wow-delay="400ms">
                        <div class="media service-box">
                            <div class="pull-left">
                                <i class="fa fa-line-chart"></i>
                            </div>
                            <div class="media-body">
                                <h4 class="media-heading">نمودار پیشرفت تحصیلی</h4>
                                <p>نمودار های پیشرفت تحصیلی برای دانش آموز با توجه به عملکرد دانش آموز در کارنامه ماهانه
                                    و سالانه نمایش داده میشود</p>
                            </div>
                        </div>
                    </div><!--/.col-md-4-->

                    <div class="col-md-4 col-sm-6 wow fadeInUp" data-wow-duration="300ms" data-wow-delay="500ms">
                        <div class="media service-box">
                            <div class="pull-left">
                                <i class="fa fa-file-text-o"></i>
                            </div>
                            <div class="media-body">
                                <h4 class="media-heading">کارنامه</h4>
                                <p>یکی از امکانات مهم پرتال آسا محاسبه و صدور آنی کارنامه ماهانه و صدور کارنامه سالانه
                                    با تایید مدیریت می باشد و جایگاه دانش آموز در کلاس را نمایش میدهد.</p>
                            </div>
                        </div>
                    </div><!--/.col-md-4-->

                    <div class="col-md-4 col-sm-6 wow fadeInUp" data-wow-duration="300ms" data-wow-delay="510ms">
                        <div class="media service-box">
                            <div class="pull-left">
                                <i class="fa fa-table"></i>
                            </div>
                            <div class="media-body">
                                <h4 class="media-heading">برنامه کلاسی</h4>
                                <p>در این بخش مدیریت میتواند برای کلاس ها برنامه کلاسی تدوین کند و دبیران و دانش آموزان
                                    به آنها دسترسی داشته باشند</p>
                            </div>
                        </div>
                    </div><!--/.col-md-4-->

                    <div class="col-md-4 col-sm-6 wow fadeInUp" data-wow-duration="300ms" data-wow-delay="520ms">
                        <div class="media service-box">
                            <div class="pull-left">
                                <i class="fa fa-check"></i>
                            </div>
                            <div class="media-body">
                                <h4 class="media-heading">سیستم حضورغیاب</h4>
                                <p>در این بخش دبیران میتوانند حضور غیاب دانش آموزان را انجام دهند و یا گزارشی از آنها
                                    دریافت کنند</p>
                            </div>
                        </div>
                    </div><!--/.col-md-4-->

                    <div class="col-md-4 col-sm-6 wow fadeInUp" data-wow-duration="300ms" data-wow-delay="530ms">
                        <div class="media service-box">
                            <div class="pull-left">
                                <i class="fa fa-file-text"></i>
                            </div>
                            <div class="media-body">
                                <h4 class="media-heading">امتحان آنلاین</h4>
                                <p>یکی از ویژگی های منحصر به فرد پرتال آسا برگزاری امتحان آنلاین برای دانش آموزان و صحیح
                                    کردن آنی آن می باشد</p>
                            </div>
                        </div>
                    </div><!--/.col-md-4-->

                    <div class="col-md-4 col-sm-6 wow fadeInUp" data-wow-duration="300ms" data-wow-delay="540ms">
                        <div class="media service-box">
                            <div class="pull-left">
                                <i class="fa fa-file-pdf-o"></i>
                            </div>
                            <div class="media-body">
                                <h4 class="media-heading">تکالیف</h4>
                                <p>دبیران می توانند برای دانش آموزان کلاس خود در سیستم تکلیف تعریف کند که دانش آموزان به
                                    صورت آنلاین آنها را ارسال کنند</p>
                            </div>
                        </div>
                    </div><!--/.col-md-4-->

                    <div class="col-md-4 col-sm-6 wow fadeInUp" data-wow-duration="300ms" data-wow-delay="550ms">
                        <div class="media service-box">
                            <div class="pull-left">
                                <i class="fa fa-book"></i>
                            </div>
                            <div class="media-body">
                                <h4 class="media-heading">کتابخانه آنلاین</h4>
                                <p>کتابخانه و سرویس امانت کتاب پرتال آسا جهت تسهیل و بالا بردن مطالعه دانش آموزان طراحی
                                    شده است</p>
                            </div>
                        </div>
                    </div><!--/.col-md-4-->

                    <div class="col-md-4 col-sm-6 wow fadeInUp" data-wow-duration="300ms" data-wow-delay="560ms">
                        <div class="media service-box">
                            <div class="pull-left">
                                <i class="fa fa-bullhorn"></i>
                            </div>
                            <div class="media-body">
                                <h4 class="media-heading">اخبار و اعلانات</h4>
                                <p>در این بخش مدیریت می تواند اخبار مدرسه را منتشر کند تا دانش آموزان و اولیا و دبیران
                                    به آنها دسترسی داشته باشند.</p>
                            </div>
                        </div>
                    </div><!--/.col-md-4-->

                    <div class="col-md-4 col-sm-6 wow fadeInUp" data-wow-duration="300ms" data-wow-delay="570ms">
                        <div class="media service-box">
                            <div class="pull-left">
                                <i class="fa fa-tasks"></i>
                            </div>
                            <div class="media-body">
                                <h4 class="media-heading">سیستم نظرسنجی</h4>
                                <p>سیستم نظرسنجی برای تسهیل در نظرسنجی بین اعضای مدرسه اعم از اولیا و دبیران و دانش
                                    آموزان طراحی شده است</p>
                            </div>
                        </div>
                    </div><!--/.col-md-4-->

                    <div class="col-md-4 col-sm-6 wow fadeInUp" data-wow-duration="300ms" data-wow-delay="580ms">
                        <div class="media service-box">
                            <div class="pull-left">
                                <i class="fa fa-credit-card"></i>
                            </div>
                            <div class="media-body">
                                <h4 class="media-heading">پرداخت ها</h4>
                                <p>در این بخش پرداختی ها و شهریه های دانش آموزان ثبت و نمایش داده میشود</p>
                            </div>
                        </div>
                    </div><!--/.col-md-4-->

                    <div class="col-md-4 col-sm-6 wow fadeInUp" data-wow-duration="300ms" data-wow-delay="590ms">
                        <div class="media service-box">
                            <div class="pull-left">
                                <i class="fa fa-tasks"></i>
                            </div>
                            <div class="media-body">
                                <h4 class="media-heading">سیستم ارتقا </h4>
                                <p>این بخش جهت ارتقا سطحی دانش آموزان به سال تحصیلی جدید و یا کلاس جدید طراحی شده
                                    است</p>
                            </div>
                        </div>
                    </div><!--/.col-md-4-->

                </div>
            </div><!--/.row-->
        </div><!--/.container-->
    </section><!--/#services-->

    <section id="portfolio">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title text-center wow fadeInDown">تصاویر پرتال</h2>
                <p class="text-center wow fadeInDown">قسمت های مختلف پرتال در تصاویر زیر قابل مشاهده می باشد</p>
            </div>

            <div class="text-center">
                <ul class="portfolio-filter">
                    <li><a class="active" href="#" data-filter="*">همه تصاویر</a></li>
                    <li><a href="#" data-filter=".admin">مدیریت</a></li>
                    <li><a href="#" data-filter=".teacher">دبیر</a></li>
                    <li><a href="#" data-filter=".student">دانش آموز</a></li>
                </ul><!--/#portfolio-filter-->
            </div>

            <div class="portfolio-items">
                <div class="portfolio-item admin teacher student">
                    <div class="portfolio-item-inner">
                        <img class="img-responsive" src="images/homeimages/dashboard.png" alt="">
                        <div class="portfolio-info">
                            <h3>داشبورد</h3>مشاهده تصویر داشبورد
                            <a class="preview" href="images/homeimages/dashboard.png" rel="prettyPhoto"><i
                                        class="fa fa-eye"></i></a>
                        </div>
                    </div>
                </div><!--/.portfolio-item-->

                <div class="portfolio-item admin">
                    <div class="portfolio-item-inner">
                        <img class="img-responsive" src="images/homeimages/teachers.png" alt="">
                        <div class="portfolio-info">
                            <h3>دبیران</h3>مشاهده تصویر بخش دبیران
                            <a class="preview" href="images/homeimages/teachers.png" rel="prettyPhoto"><i
                                        class="fa fa-eye"></i></a>
                        </div>
                    </div>
                </div><!--/.portfolio-item-->

                <div class="portfolio-item admin">
                    <div class="portfolio-item-inner">
                        <img class="img-responsive" src="images/homeimages/parents.png" alt="">
                        <div class="portfolio-info">
                            <h3>اولیا</h3>مشاهده تصویر بخش اولیا
                            <a class="preview" href="images/homeimages/parents.png" rel="prettyPhoto"><i
                                        class="fa fa-eye"></i></a>
                        </div>
                    </div>
                </div><!--/.portfolio-item-->

                <div class="portfolio-item admin teacher">
                    <div class="portfolio-item-inner">
                        <img class="img-responsive" src="images/homeimages/students.png" alt="">
                        <div class="portfolio-info">
                            <h3>دانش آموزان</h3>مشاهده تصویر بخش دانش آموزان
                            <a class="preview" href="images/homeimages/students.png" rel="prettyPhoto"><i
                                        class="fa fa-eye"></i></a>
                        </div>
                    </div>
                </div><!--/.portfolio-item-->

                <div class="portfolio-item admin teacher student">
                    <div class="portfolio-item-inner">
                        <img class="img-responsive" src="images/homeimages/marks.png" alt="">
                        <div class="portfolio-info">
                            <h3>نمرات</h3>مشاهده تصویر بخش نمرات
                            <a class="preview" href="images/homeimages/marks.png" rel="prettyPhoto"><i
                                        class="fa fa-eye"></i></a>
                        </div>
                    </div>
                </div><!--/.portfolio-item-->

                <div class="portfolio-item admin teacher student">
                    <div class="portfolio-item-inner">
                        <img class="img-responsive" src="images/homeimages/monthwb.png" alt="">
                        <div class="portfolio-info">
                            <h3>کارنامه</h3>مشاهده تصویر کارنامه ماهانه
                            <a class="preview" href="images/homeimages/monthwb.png" rel="prettyPhoto"><i
                                        class="fa fa-eye"></i></a>
                        </div>
                    </div>
                </div><!--/.portfolio-item-->

                <div class="portfolio-item admin teacher student">
                    <div class="portfolio-item-inner">
                        <img class="img-responsive" src="images/homeimages/yearwb.png" alt="">
                        <div class="portfolio-info">
                            <h3>کارنامه</h3>مشاهده تصویر کارنامه سالانه
                            <a class="preview" href="images/homeimages/yearwb.png" rel="prettyPhoto"><i
                                        class="fa fa-eye"></i></a>
                        </div>
                    </div>
                </div><!--/.portfolio-item-->

                <div class="portfolio-item admin teacher student">
                    <div class="portfolio-item-inner">
                        <img class="img-responsive" src="images/homeimages/monthchart.png" alt="">
                        <div class="portfolio-info">
                            <h3>نمودار</h3>مشاهده تصویر نمودار پیشرفت تحصیلی
                            <a class="preview" href="images/homeimages/monthchart.png" rel="prettyPhoto"><i
                                        class="fa fa-eye"></i></a>
                        </div>
                    </div>
                </div><!--/.portfolio-item-->

                <div class="portfolio-item admin teacher student">
                    <div class="portfolio-item-inner">
                        <img class="img-responsive" src="images/homeimages/yearchart.png" alt="">
                        <div class="portfolio-info">
                            <h3>نمودار</h3>مشاهده تصویر نمودار پیشرفت تحصیلی
                            <a class="preview" href="images/homeimages/yearchart.png" rel="prettyPhoto"><i
                                        class="fa fa-eye"></i></a>
                        </div>
                    </div>
                </div><!--/.portfolio-item-->

                <div class="portfolio-item admin teacher student">
                    <div class="portfolio-item-inner">
                        <img class="img-responsive" src="images/homeimages/classSchedules.png" alt="">
                        <div class="portfolio-info">
                            <h3>برنامه کلاسی</h3>مشاهده تصویر برنامه کلاسی
                            <a class="preview" href="images/homeimages/classSchedules.png" rel="prettyPhoto"><i
                                        class="fa fa-eye"></i></a>
                        </div>
                    </div>
                </div><!--/.portfolio-item-->

                <div class="portfolio-item admin teacher">
                    <div class="portfolio-item-inner">
                        <img class="img-responsive" src="images/homeimages/attendance.png" alt="">
                        <div class="portfolio-info">
                            <h3>حضورغیاب</h3>مشاهده تصویر حضورغیاب
                            <a class="preview" href="images/homeimages/attendance.png" rel="prettyPhoto"><i
                                        class="fa fa-eye"></i></a>
                        </div>
                    </div>
                </div><!--/.portfolio-item-->

                <div class="portfolio-item admin teacher student">
                    <div class="portfolio-item-inner">
                        <img class="img-responsive" src="images/homeimages/onlineexam.png" alt="">
                        <div class="portfolio-info">
                            <h3>امتحان آنلاین</h3>مشاهده تصویر امتحان آنلاین
                            <a class="preview" href="images/homeimages/onlineexam.png" rel="prettyPhoto"><i
                                        class="fa fa-eye"></i></a>
                        </div>
                    </div>
                </div><!--/.portfolio-item-->

                <div class="portfolio-item admin teacher student">
                    <div class="portfolio-item-inner">
                        <img class="img-responsive" src="images/homeimages/assignments.png" alt="">
                        <div class="portfolio-info">
                            <h3>تکالیف</h3>مشاهده تصویر تکالیف
                            <a class="preview" href="images/homeimages/assignments.png" rel="prettyPhoto"><i
                                        class="fa fa-eye"></i></a>
                        </div>
                    </div>
                </div><!--/.portfolio-item-->

                <div class="portfolio-item admin teacher student">
                    <div class="portfolio-item-inner">
                        <img class="img-responsive" src="images/homeimages/library.png" alt="">
                        <div class="portfolio-info">
                            <h3>کتابخانه</h3>مشاهده تصویر کتابخانه
                            <a class="preview" href="images/homeimages/library.png" rel="prettyPhoto"><i
                                        class="fa fa-eye"></i></a>
                        </div>
                    </div>
                </div><!--/.portfolio-item-->

                <div class="portfolio-item admin teacher student">
                    <div class="portfolio-item-inner">
                        <img class="img-responsive" src="images/homeimages/news.png" alt="">
                        <div class="portfolio-info">
                            <h3>اخبار</h3>مشاهده تصویر اخبار
                            <a class="preview" href="images/homeimages/news.png" rel="prettyPhoto"><i
                                        class="fa fa-eye"></i></a>
                        </div>
                    </div>
                </div><!--/.portfolio-item-->

                <div class="portfolio-item admin teacher student">
                    <div class="portfolio-item-inner">
                        <img class="img-responsive" src="images/homeimages/polls.png" alt="">
                        <div class="portfolio-info">
                            <h3>نظرسنجی</h3>مشاهده تصویر نظرسنجی
                            <a class="preview" href="images/homeimages/polls.png" rel="prettyPhoto"><i
                                        class="fa fa-eye"></i></a>
                        </div>
                    </div>
                </div><!--/.portfolio-item-->

                <div class="portfolio-item admin">
                    <div class="portfolio-item-inner">
                        <img class="img-responsive" src="images/homeimages/promote.png" alt="">
                        <div class="portfolio-info">
                            <h3>سیستم ارتقا</h3>مشاهده تصویر سیستم ارتقا
                            <a class="preview" href="images/homeimages/promote.png" rel="prettyPhoto"><i
                                        class="fa fa-eye"></i></a>
                        </div>
                    </div>
                </div><!--/.portfolio-item-->


            </div>
        </div><!--/.container-->
    </section><!--/#portfolio-->

    <section id="about">
        <div class="container">

            <div class="section-header">
                <h2 class="section-title text-center wow fadeInDown">درباره ما</h2>
                <p class="text-center wow fadeInDown">آسا یک شرکت طراحی سایت می باشد.که پرتال آسا یکی از محصولات این
                    شرکت می باشد که مدت طولانی را صرف تحلیل و طراحی سیستم جامع مدیریت مدارس نموده است.</p>
            </div>

            <div class="row">
                <div class="col-sm-6 wow fadeInLeft">
                    <h3 class="column-title">ویدیوی پرتال</h3>
                    <!-- 16:9 aspect ratio -->
                    <div class="embed-responsive embed-responsive-16by9">
                        <video controls>
                            <source src="http://g1.asset.aparat.com/flv_video_new/2740/e19b2c4f606c1356e04a1c6846bf79308218495-360p.apt?direct=1"
                                    type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    </div>
                </div>

                <div class="col-sm-6 wow fadeInRight">
                    <h3 class="column-title">نکات بیزینسی آسا</h3>
                    <p> شرکت آسا خود را پایبند و متعهد به اجرای چند نکته می داند که به عقیده این شرکت برای اعتماد
                        مشتریان بسیار ضروری هست و در این قسمت برای کاربران شرح داده میشود</p>

                    <div class="row">
                        <div class="col-sm-6">
                            <ul class="nostyle">
                                <li><i class="fa fa-check-square"></i> صداقت با مشتریان</li>
                                <li><i class="fa fa-check-square"></i> پشتیبانی از محصول</li>
                            </ul>
                        </div>

                        <div class="col-sm-6">
                            <ul class="nostyle">
                                <li><i class="fa fa-check-square"></i> ارتقا محصول و نه فقط تجارت</li>
                                <li><i class="fa fa-check-square"></i> ساخت محصول با توجه به نیاز مشتری</li>
                            </ul>
                        </div>
                    </div>

                    {{--<a class="btn btn-primary" href="#">Learn More</a>--}}

                </div>
                <hr>

            </div>
        </div>
    </section><!--/#about-->

    {{--<section id="work-process">--}}
    {{--<div class="container">--}}
    {{--<div class="section-header">--}}
    {{--<h2 class="section-title text-center wow fadeInDown">Our Process</h2>--}}
    {{--<p class="text-center wow fadeInDown">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut <br> et dolore magna aliqua. Ut enim ad minim veniam</p>--}}
    {{--</div>--}}

    {{--<div class="row text-center">--}}
    {{--<div class="col-md-2 col-md-4 col-xs-6">--}}
    {{--<div class="wow fadeInUp" data-wow-duration="400ms" data-wow-delay="0ms">--}}
    {{--<div class="icon-circle">--}}
    {{--<span>1</span>--}}
    {{--<i class="fa fa-coffee fa-2x"></i>--}}
    {{--</div>--}}
    {{--<h3>MEET</h3>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--<div class="col-md-2 col-md-4 col-xs-6">--}}
    {{--<div class="wow fadeInUp" data-wow-duration="400ms" data-wow-delay="100ms">--}}
    {{--<div class="icon-circle">--}}
    {{--<span>2</span>--}}
    {{--<i class="fa fa-bullhorn fa-2x"></i>--}}
    {{--</div>--}}
    {{--<h3>PLAN</h3>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--<div class="col-md-2 col-md-4 col-xs-6">--}}
    {{--<div class="wow fadeInUp" data-wow-duration="400ms" data-wow-delay="200ms">--}}
    {{--<div class="icon-circle">--}}
    {{--<span>3</span>--}}
    {{--<i class="fa fa-image fa-2x"></i>--}}
    {{--</div>--}}
    {{--<h3>DESIGN</h3>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--<div class="col-md-2 col-md-4 col-xs-6">--}}
    {{--<div class="wow fadeInUp" data-wow-duration="400ms" data-wow-delay="300ms">--}}
    {{--<div class="icon-circle">--}}
    {{--<span>4</span>--}}
    {{--<i class="fa fa-heart fa-2x"></i>--}}
    {{--</div>--}}
    {{--<h3>DEVELOP</h3>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--<div class="col-md-2 col-md-4 col-xs-6">--}}
    {{--<div class="wow fadeInUp" data-wow-duration="400ms" data-wow-delay="400ms">--}}
    {{--<div class="icon-circle">--}}
    {{--<span>5</span>--}}
    {{--<i class="fa fa-shopping-cart fa-2x"></i>--}}
    {{--</div>--}}
    {{--<h3>TESTING</h3>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--<div class="col-md-2 col-md-4 col-xs-6">--}}
    {{--<div class="wow fadeInUp" data-wow-duration="400ms" data-wow-delay="500ms">--}}
    {{--<div class="icon-circle">--}}
    {{--<span>6</span>--}}
    {{--<i class="fa fa-space-shuttle fa-2x"></i>--}}
    {{--</div>--}}
    {{--<h3>LAUNCH</h3>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</section><!--/#work-process-->--}}

    {{--<section id="meet-team">--}}
    {{--<div class="container">--}}
    {{--<div class="section-header">--}}
    {{--<h2 class="section-title text-center wow fadeInDown">Meet The Team</h2>--}}
    {{--<p class="text-center wow fadeInDown">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut <br> et dolore magna aliqua. Ut enim ad minim veniam</p>--}}
    {{--</div>--}}

    {{--<div class="row">--}}
    {{--<div class="col-sm-6 col-md-3">--}}
    {{--<div class="team-member wow fadeInUp" data-wow-duration="400ms" data-wow-delay="0ms">--}}
    {{--<div class="team-img">--}}
    {{--<img class="img-responsive" src="images/team/01.jpg" alt="">--}}
    {{--</div>--}}
    {{--<div class="team-info">--}}
    {{--<h3>Bin Burhan</h3>--}}
    {{--<span>Co-Founder</span>--}}
    {{--</div>--}}
    {{--<p>Backed by some of the biggest names in the industry, Firefox OS is an open platform that fosters greater</p>--}}
    {{--<ul class="social-icons">--}}
    {{--<li><a href="#"><i class="fa fa-facebook"></i></a></li>--}}
    {{--<li><a href="#"><i class="fa fa-twitter"></i></a></li>--}}
    {{--<li><a href="#"><i class="fa fa-google-plus"></i></a></li>--}}
    {{--<li><a href="#"><i class="fa fa-linkedin"></i></a></li>--}}
    {{--</ul>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--<div class="col-sm-6 col-md-3">--}}
    {{--<div class="team-member wow fadeInUp" data-wow-duration="400ms" data-wow-delay="100ms">--}}
    {{--<div class="team-img">--}}
    {{--<img class="img-responsive" src="images/team/02.jpg" alt="">--}}
    {{--</div>--}}
    {{--<div class="team-info">--}}
    {{--<h3>Jane Man</h3>--}}
    {{--<span>Project Manager</span>--}}
    {{--</div>--}}
    {{--<p>Backed by some of the biggest names in the industry, Firefox OS is an open platform that fosters greater</p>--}}
    {{--<ul class="social-icons">--}}
    {{--<li><a href="#"><i class="fa fa-facebook"></i></a></li>--}}
    {{--<li><a href="#"><i class="fa fa-twitter"></i></a></li>--}}
    {{--<li><a href="#"><i class="fa fa-google-plus"></i></a></li>--}}
    {{--<li><a href="#"><i class="fa fa-linkedin"></i></a></li>--}}
    {{--</ul>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--<div class="col-sm-6 col-md-3">--}}
    {{--<div class="team-member wow fadeInUp" data-wow-duration="400ms" data-wow-delay="200ms">--}}
    {{--<div class="team-img">--}}
    {{--<img class="img-responsive" src="images/team/03.jpg" alt="">--}}
    {{--</div>--}}
    {{--<div class="team-info">--}}
    {{--<h3>Pahlwan</h3>--}}
    {{--<span>Designer</span>--}}
    {{--</div>--}}
    {{--<p>Backed by some of the biggest names in the industry, Firefox OS is an open platform that fosters greater</p>--}}
    {{--<ul class="social-icons">--}}
    {{--<li><a href="#"><i class="fa fa-facebook"></i></a></li>--}}
    {{--<li><a href="#"><i class="fa fa-twitter"></i></a></li>--}}
    {{--<li><a href="#"><i class="fa fa-google-plus"></i></a></li>--}}
    {{--<li><a href="#"><i class="fa fa-linkedin"></i></a></li>--}}
    {{--</ul>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--<div class="col-sm-6 col-md-3">--}}
    {{--<div class="team-member wow fadeInUp" data-wow-duration="400ms" data-wow-delay="300ms">--}}
    {{--<div class="team-img">--}}
    {{--<img class="img-responsive" src="images/team/04.jpg" alt="">--}}
    {{--</div>--}}
    {{--<div class="team-info">--}}
    {{--<h3>Nasir uddin</h3>--}}
    {{--<span>UI/UX</span>--}}
    {{--</div>--}}
    {{--<p>Backed by some of the biggest names in the industry, Firefox OS is an open platform that fosters greater</p>--}}
    {{--<ul class="social-icons">--}}
    {{--<li><a href="#"><i class="fa fa-facebook"></i></a></li>--}}
    {{--<li><a href="#"><i class="fa fa-twitter"></i></a></li>--}}
    {{--<li><a href="#"><i class="fa fa-google-plus"></i></a></li>--}}
    {{--<li><a href="#"><i class="fa fa-linkedin"></i></a></li>--}}
    {{--</ul>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}

    {{--<div class="divider"></div>--}}

    {{--<div class="row">--}}
    {{--<div class="col-sm-4">--}}
    {{--<h3 class="column-title">Our Skills</h3>--}}
    {{--<strong>GRAPHIC DESIGN</strong>--}}
    {{--<div class="progress">--}}
    {{--<div class="progress-bar progress-bar-primary" role="progressbar" data-width="85">85%</div>--}}
    {{--</div>--}}
    {{--<strong>WEB DESIGN</strong>--}}
    {{--<div class="progress">--}}
    {{--<div class="progress-bar progress-bar-primary" role="progressbar" data-width="70">70%</div>--}}
    {{--</div>--}}
    {{--<strong>WORDPRESS DEVELOPMENT</strong>--}}
    {{--<div class="progress">--}}
    {{--<div class="progress-bar progress-bar-primary" role="progressbar" data-width="90">90%</div>--}}
    {{--</div>--}}
    {{--<strong>JOOMLA DEVELOPMENT</strong>--}}
    {{--<div class="progress">--}}
    {{--<div class="progress-bar progress-bar-primary" role="progressbar" data-width="65">65%</div>--}}
    {{--</div>--}}
    {{--</div>--}}

    {{--<div class="col-sm-4">--}}
    {{--<h3 class="column-title">Our History</h3>--}}
    {{--<div role="tabpanel">--}}
    {{--<ul class="nav main-tab nav-justified" role="tablist">--}}
    {{--<li role="presentation" class="active">--}}
    {{--<a href="#tab1" role="tab" data-toggle="tab" aria-controls="tab1" aria-expanded="true">2010</a>--}}
    {{--</li>--}}
    {{--<li role="presentation">--}}
    {{--<a href="#tab2" role="tab" data-toggle="tab" aria-controls="tab2" aria-expanded="false">2011</a>--}}
    {{--</li>--}}
    {{--<li role="presentation">--}}
    {{--<a href="#tab3" role="tab" data-toggle="tab" aria-controls="tab3" aria-expanded="false">2013</a>--}}
    {{--</li>--}}
    {{--<li role="presentation">--}}
    {{--<a href="#tab4" role="tab" data-toggle="tab" aria-controls="tab4" aria-expanded="false">2014</a>--}}
    {{--</li>--}}
    {{--</ul>--}}
    {{--<div id="tab-content" class="tab-content">--}}
    {{--<div role="tabpanel" class="tab-pane fade active in" id="tab1" aria-labelledby="tab1">--}}
    {{--<p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.</p>--}}
    {{--<p>The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters readable English.</p>--}}
    {{--</div>--}}
    {{--<div role="tabpanel" class="tab-pane fade" id="tab2" aria-labelledby="tab2">--}}
    {{--<p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.</p>--}}
    {{--<p>The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters readable English.</p>--}}
    {{--</div>--}}
    {{--<div role="tabpanel" class="tab-pane fade" id="tab3" aria-labelledby="tab3">--}}
    {{--<p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.</p>--}}
    {{--<p>The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters readable English.</p>--}}
    {{--</div>--}}
    {{--<div role="tabpanel" class="tab-pane fade" id="tab4" aria-labelledby="tab3">--}}
    {{--<p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.</p>--}}
    {{--<p>The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters readable English.</p>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}

    {{--<div class="col-sm-4">--}}
    {{--<h3 class="column-title">Faqs</h3>--}}
    {{--<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">--}}
    {{--<div class="panel panel-default">--}}
    {{--<div class="panel-heading" role="tab" id="headingOne">--}}
    {{--<h4 class="panel-title">--}}
    {{--<a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">--}}
    {{--Enim eiusmod high life accusamus--}}
    {{--</a>--}}
    {{--</h4>--}}
    {{--</div>--}}
    {{--<div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">--}}
    {{--<div class="panel-body">--}}
    {{--Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum.--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--<div class="panel panel-default">--}}
    {{--<div class="panel-heading" role="tab" id="headingTwo">--}}
    {{--<h4 class="panel-title">--}}
    {{--<a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">--}}
    {{--Nihil anim keffiyeh helvetica--}}
    {{--</a>--}}
    {{--</h4>--}}
    {{--</div>--}}
    {{--<div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">--}}
    {{--<div class="panel-body">--}}
    {{--Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum.--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--<div class="panel panel-default">--}}
    {{--<div class="panel-heading" role="tab" id="headingThree">--}}
    {{--<h4 class="panel-title">--}}
    {{--<a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">--}}
    {{--Vegan excepteur butcher vice lomo--}}
    {{--</a>--}}
    {{--</h4>--}}
    {{--</div>--}}
    {{--<div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">--}}
    {{--<div class="panel-body">--}}
    {{--Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum.--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}

    {{--</div>--}}
    {{--</div>--}}
    {{--</section><!--/#meet-team-->--}}

    {{--<section id="animated-number">--}}
    {{--<div class="container">--}}
    {{--<div class="section-header">--}}
    {{--<h2 class="section-title text-center wow fadeInDown">Fun Facts</h2>--}}
    {{--<p class="text-center wow fadeInDown">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut <br> et dolore magna aliqua. Ut enim ad minim veniam</p>--}}
    {{--</div>--}}

    {{--<div class="row text-center">--}}
    {{--<div class="col-sm-3 col-xs-6">--}}
    {{--<div class="wow fadeInUp" data-wow-duration="400ms" data-wow-delay="0ms">--}}
    {{--<div class="animated-number" data-digit="2305" data-duration="1000"></div>--}}
    {{--<strong>CUPS OF COFFEE CONSUMED</strong>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--<div class="col-sm-3 col-xs-6">--}}
    {{--<div class="wow fadeInUp" data-wow-duration="400ms" data-wow-delay="100ms">--}}
    {{--<div class="animated-number" data-digit="1231" data-duration="1000"></div>--}}
    {{--<strong>CLIENT WORKED WITH</strong>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--<div class="col-sm-3 col-xs-6">--}}
    {{--<div class="wow fadeInUp" data-wow-duration="400ms" data-wow-delay="200ms">--}}
    {{--<div class="animated-number" data-digit="3025" data-duration="1000"></div>--}}
    {{--<strong>PROJECT COMPLETED</strong>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--<div class="col-sm-3 col-xs-6">--}}
    {{--<div class="wow fadeInUp" data-wow-duration="400ms" data-wow-delay="300ms">--}}
    {{--<div class="animated-number" data-digit="1199" data-duration="1000"></div>--}}
    {{--<strong>QUESTIONS ANSWERED</strong>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</section><!--/#animated-number-->--}}

    {{--<section id="pricing">--}}
    {{--<div class="container">--}}
    {{--<div class="section-header">--}}
    {{--<h2 class="section-title text-center wow fadeInDown">Pricing Table</h2>--}}
    {{--<p class="text-center wow fadeInDown">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut <br> et dolore magna aliqua. Ut enim ad minim veniam</p>--}}
    {{--</div>--}}

    {{--<div class="row">--}}
    {{--<div class="col-sm-6 col-md-3">--}}
    {{--<div class="wow zoomIn" data-wow-duration="400ms" data-wow-delay="0ms">--}}
    {{--<ul class="pricing">--}}
    {{--<li class="plan-header">--}}
    {{--<div class="price-duration">--}}
    {{--<span class="price">--}}
    {{--$39--}}
    {{--</span>--}}
    {{--<span class="duration">--}}
    {{--per month--}}
    {{--</span>--}}
    {{--</div>--}}

    {{--<div class="plan-name">--}}
    {{--Starter--}}
    {{--</div>--}}
    {{--</li>--}}
    {{--<li><strong>1</strong> DOMAIN</li>--}}
    {{--<li><strong>100GB</strong> DISK SPACE</li>--}}
    {{--<li><strong>UNLIMITED</strong> BANDWIDTH</li>--}}
    {{--<li>SHARED SSL CERTIFICATE</li>--}}
    {{--<li><strong>10</strong> EMAIL ADDRESS</li>--}}
    {{--<li><strong>24/7</strong> SUPPORT</li>--}}
    {{--<li class="plan-purchase"><a class="btn btn-primary" href="#">ORDER NOW</a></li>--}}
    {{--</ul>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--<div class="col-sm-6 col-md-3">--}}
    {{--<div class="wow zoomIn" data-wow-duration="400ms" data-wow-delay="200ms">--}}
    {{--<ul class="pricing featured">--}}
    {{--<li class="plan-header">--}}
    {{--<div class="price-duration">--}}
    {{--<span class="price">--}}
    {{--$69--}}
    {{--</span>--}}
    {{--<span class="duration">--}}
    {{--per month--}}
    {{--</span>--}}
    {{--</div>--}}

    {{--<div class="plan-name">--}}
    {{--Business--}}
    {{--</div>--}}
    {{--</li>--}}
    {{--<li><strong>3</strong> DOMAIN</li>--}}
    {{--<li><strong>300GB</strong> DISK SPACE</li>--}}
    {{--<li><strong>UNLIMITED</strong> BANDWIDTH</li>--}}
    {{--<li>SHARED SSL CERTIFICATE</li>--}}
    {{--<li><strong>30</strong> EMAIL ADDRESS</li>--}}
    {{--<li><strong>24/7</strong> SUPPORT</li>--}}
    {{--<li class="plan-purchase"><a class="btn btn-default" href="#">ORDER NOW</a></li>--}}
    {{--</ul>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--<div class="col-sm-6 col-md-3">--}}
    {{--<div class="wow zoomIn" data-wow-duration="400ms" data-wow-delay="400ms">--}}
    {{--<ul class="pricing">--}}
    {{--<li class="plan-header">--}}
    {{--<div class="price-duration">--}}
    {{--<span class="price">--}}
    {{--$99--}}
    {{--</span>--}}
    {{--<span class="duration">--}}
    {{--per month--}}
    {{--</span>--}}
    {{--</div>--}}

    {{--<div class="plan-name">--}}
    {{--Pro--}}
    {{--</div>--}}
    {{--</li>--}}
    {{--<li><strong>5</strong> DOMAIN</li>--}}
    {{--<li><strong>500GB</strong> DISK SPACE</li>--}}
    {{--<li><strong>UNLIMITED</strong> BANDWIDTH</li>--}}
    {{--<li>SHARED SSL CERTIFICATE</li>--}}
    {{--<li><strong>50</strong> EMAIL ADDRESS</li>--}}
    {{--<li><strong>24/7</strong> SUPPORT</li>--}}
    {{--<li class="plan-purchase"><a class="btn btn-primary" href="#">ORDER NOW</a></li>--}}
    {{--</ul>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--<div class="col-sm-6 col-md-3">--}}
    {{--<div class="wow zoomIn" data-wow-duration="400ms" data-wow-delay="600ms">--}}
    {{--<ul class="pricing">--}}
    {{--<li class="plan-header">--}}
    {{--<div class="price-duration">--}}
    {{--<span class="price">--}}
    {{--$199--}}
    {{--</span>--}}
    {{--<span class="duration">--}}
    {{--per month--}}
    {{--</span>--}}
    {{--</div>--}}

    {{--<div class="plan-name">--}}
    {{--Ultra--}}
    {{--</div>--}}
    {{--</li>--}}
    {{--<li><strong>10</strong> DOMAIN</li>--}}
    {{--<li><strong>1000GB</strong> DISK SPACE</li>--}}
    {{--<li><strong>UNLIMITED</strong> BANDWIDTH</li>--}}
    {{--<li>SHARED SSL CERTIFICATE</li>--}}
    {{--<li><strong>100</strong> EMAIL ADDRESS</li>--}}
    {{--<li><strong>24/7</strong> SUPPORT</li>--}}
    {{--<li class="plan-purchase"><a class="btn btn-primary" href="#">ORDER NOW</a></li>--}}
    {{--</ul>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</section><!--/#pricing-->--}}

    {{--<section id="testimonial">--}}
    {{--<div class="container">--}}
    {{--<div class="row">--}}
    {{--<div class="col-sm-8 col-sm-offset-2">--}}

    {{--<div id="carousel-testimonial" class="carousel slide text-center" data-ride="carousel">--}}
    {{--<!-- Wrapper for slides -->--}}
    {{--<div class="carousel-inner" role="listbox">--}}
    {{--<div class="item active">--}}
    {{--<p><img class="img-circle img-thumbnail" src="images/testimonial/01.jpg" alt=""></p>--}}
    {{--<h4>Louise S. Morgan</h4>--}}
    {{--<small>Treatment, storage, and disposal (TSD) worker</small>--}}
    {{--<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut et dolore magna aliqua. Ut enim ad minim veniam</p>--}}
    {{--</div>--}}
    {{--<div class="item">--}}
    {{--<p><img class="img-circle img-thumbnail" src="images/testimonial/01.jpg" alt=""></p>--}}
    {{--<h4>Louise S. Morgan</h4>--}}
    {{--<small>Treatment, storage, and disposal (TSD) worker</small>--}}
    {{--<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut et dolore magna aliqua. Ut enim ad minim veniam</p>--}}
    {{--</div>--}}
    {{--</div>--}}

    {{--<!-- Controls -->--}}
    {{--<div class="btns">--}}
    {{--<a class="btn btn-primary btn-sm" href="#carousel-testimonial" role="button" data-slide="prev">--}}
    {{--<span class="fa fa-angle-left" aria-hidden="true"></span>--}}
    {{--<span class="sr-only">Previous</span>--}}
    {{--</a>--}}
    {{--<a class="btn btn-primary btn-sm" href="#carousel-testimonial" role="button" data-slide="next">--}}
    {{--<span class="fa fa-angle-right" aria-hidden="true"></span>--}}
    {{--<span class="sr-only">Next</span>--}}
    {{--</a>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</section><!--/#testimonial-->--}}

    {{--<section id="blog">--}}
    {{--<div class="container">--}}
    {{--<div class="section-header">--}}
    {{--<h2 class="section-title text-center wow fadeInDown">Latest Blogs</h2>--}}
    {{--<p class="text-center wow fadeInDown">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut <br> et dolore magna aliqua. Ut enim ad minim veniam</p>--}}
    {{--</div>--}}

    {{--<div class="row">--}}
    {{--<div class="col-sm-6">--}}
    {{--<div class="blog-post blog-large wow fadeInLeft" data-wow-duration="300ms" data-wow-delay="0ms">--}}
    {{--<article>--}}
    {{--<header class="entry-header">--}}
    {{--<div class="entry-thumbnail">--}}
    {{--<img class="img-responsive" src="images/blog/01.jpg" alt="">--}}
    {{--<span class="post-format post-format-video"><i class="fa fa-film"></i></span>--}}
    {{--</div>--}}
    {{--<div class="entry-date">25 November 2014</div>--}}
    {{--<h2 class="entry-title"><a href="#">While now the fated Pequod had been so long afloat this</a></h2>--}}
    {{--</header>--}}

    {{--<div class="entry-content">--}}
    {{--<P>With a blow from the top-maul Ahab knocked off the steel head of the lance, and then handing to the mate the long iron rod remaining, bade him hold it upright, without its touching off the steel head of the lance, and then handing to the mate the long iron rod remaining. without its touching off the steel without its touching off the steel</P>--}}
    {{--<a class="btn btn-primary" href="#">Read More</a>--}}
    {{--</div>--}}

    {{--<footer class="entry-meta">--}}
    {{--<span class="entry-author"><i class="fa fa-pencil"></i> <a href="#">Victor</a></span>--}}
    {{--<span class="entry-category"><i class="fa fa-folder-o"></i> <a href="#">Tutorial</a></span>--}}
    {{--<span class="entry-comments"><i class="fa fa-comments-o"></i> <a href="#">15</a></span>--}}
    {{--</footer>--}}
    {{--</article>--}}
    {{--</div>--}}
    {{--</div><!--/.col-sm-6-->--}}
    {{--<div class="col-sm-6">--}}
    {{--<div class="blog-post blog-media wow fadeInRight" data-wow-duration="300ms" data-wow-delay="100ms">--}}
    {{--<article class="media clearfix">--}}
    {{--<div class="entry-thumbnail pull-left">--}}
    {{--<img class="img-responsive" src="images/blog/02.jpg" alt="">--}}
    {{--<span class="post-format post-format-gallery"><i class="fa fa-image"></i></span>--}}
    {{--</div>--}}
    {{--<div class="media-body">--}}
    {{--<header class="entry-header">--}}
    {{--<div class="entry-date">01 December 2014</div>--}}
    {{--<h2 class="entry-title"><a href="#">BeReviews was a awesome envent in dhaka</a></h2>--}}
    {{--</header>--}}

    {{--<div class="entry-content">--}}
    {{--<P>With a blow from the top-maul Ahab knocked off the steel head of the lance, and then handing to the steel</P>--}}
    {{--<a class="btn btn-primary" href="#">Read More</a>--}}
    {{--</div>--}}

    {{--<footer class="entry-meta">--}}
    {{--<span class="entry-author"><i class="fa fa-pencil"></i> <a href="#">Campbell</a></span>--}}
    {{--<span class="entry-category"><i class="fa fa-folder-o"></i> <a href="#">Tutorial</a></span>--}}
    {{--<span class="entry-comments"><i class="fa fa-comments-o"></i> <a href="#">15</a></span>--}}
    {{--</footer>--}}
    {{--</div>--}}
    {{--</article>--}}
    {{--</div>--}}
    {{--<div class="blog-post blog-media wow fadeInRight" data-wow-duration="300ms" data-wow-delay="200ms">--}}
    {{--<article class="media clearfix">--}}
    {{--<div class="entry-thumbnail pull-left">--}}
    {{--<img class="img-responsive" src="images/blog/03.jpg" alt="">--}}
    {{--<span class="post-format post-format-audio"><i class="fa fa-music"></i></span>--}}
    {{--</div>--}}
    {{--<div class="media-body">--}}
    {{--<header class="entry-header">--}}
    {{--<div class="entry-date">03 November 2014</div>--}}
    {{--<h2 class="entry-title"><a href="#">Play list of old bangle  music and gajal</a></h2>--}}
    {{--</header>--}}

    {{--<div class="entry-content">--}}
    {{--<P>With a blow from the top-maul Ahab knocked off the steel head of the lance, and then handing to the steel</P>--}}
    {{--<a class="btn btn-primary" href="#">Read More</a>--}}
    {{--</div>--}}

    {{--<footer class="entry-meta">--}}
    {{--<span class="entry-author"><i class="fa fa-pencil"></i> <a href="#">Ruth</a></span>--}}
    {{--<span class="entry-category"><i class="fa fa-folder-o"></i> <a href="#">Tutorial</a></span>--}}
    {{--<span class="entry-comments"><i class="fa fa-comments-o"></i> <a href="#">15</a></span>--}}
    {{--</footer>--}}
    {{--</div>--}}
    {{--</article>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}

    {{--</div>--}}
    {{--</section>--}}


    <section id="get-in-touch">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title text-center wow fadeInDown">تماس با ما</h2>
                <p class="text-center wow fadeInDown">شما می توانید از راه ها ارتباطی زیر با ما در تماس باشید</p>
            </div>
        </div>
    </section><!--/#get-in-touch-->

    <section id="contact">
        <div id="google-map" style="height:650px" data-latitude="35.325766" data-longitude="51.646244"></div>
        <div class="container-wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-sm-4 col-sm-offset-8">
                        <div class="contact-form">
                            <h3>اطلاعات تماس</h3>

                            <address>
                                <strong>تهران, ورامین</strong><br>پاساژ اندیشه<br>طبقه اول پلاک 17<br>
                                <abbr title="Phone">همراه:</abbr> 9651 350 903 98
                            </address>

                            <form id="main-contact-form" name="contact-form" method="post" action="#">
                                <div class="form-group">
                                    <input type="text" name="name" class="form-control" placeholder="نام" required>
                                </div>
                                <div class="form-group">
                                    <input type="email" name="email" class="form-control" placeholder="ایمیل" required>
                                </div>
                                <div class="form-group">
                                    <input type="text" name="subject" class="form-control" placeholder="عنوان" required>
                                </div>
                                <div class="form-group">
                                    <textarea name="message" class="form-control" rows="8" placeholder="پیام"
                                              required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">ارسال پیام</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section><!--/#bottom-->

    <footer id="footer">
        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    تمامی حقوق این سایت متعلق به آسا سایت می باشد&copy;
                </div>
                <div class="col-sm-6">
                    <ul class="social-icons">
                        <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                        <li><a href="https://t.me/asa_portal"><i class="fa fa-telegram"></i></a></li>
                        <li><a href="https://www.instagram.com/asa_site/"><i class="fa fa-instagram"></i></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer><!--/#footer-->
@stop