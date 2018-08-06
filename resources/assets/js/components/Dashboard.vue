<template>

    <div>
        <!--DASHBOARD-->
        <div v-show="status == 'dashboard'">
            <div class="row">

                <div class="col-lg-3 col-xs-6">

                    <div class="card">
                        <div class="card-block p-a-0 clearfix">
                            <i class="fa fa-home bg-primary p-a-2 font-2xl m-r-1 pull-left"></i>
                            <div class="h5 text-info m-b-0 p-t-1 text-center" v-text="headinfo.classes"></div>
                            <div class="text-muted text-uppercase font-weight-bold font-xs text-center">کلاس</div>
                        </div>
                    </div>


                </div>
                <div class="col-lg-3 col-xs-6">

                    <div class="card">
                        <div class="card-block p-a-0 clearfix">
                            <i class="fa fa-graduation-cap bg-info p-a-2 font-2xl m-r-1 pull-left"></i>
                            <div class="h5 text-info m-b-0 p-t-1 text-center" v-text="headinfo.students"></div>
                            <div class="text-muted text-uppercase font-weight-bold font-xs text-center">دانش آموز</div>
                        </div>
                    </div>

                </div>
                <div class="col-lg-3 col-xs-6">

                    <div class="card">
                        <div class="card-block p-a-0 clearfix">
                            <i class="fa fa-user bg-success p-a-2 font-2xl m-r-1 pull-left"></i>
                            <div class="h5 text-info m-b-0 p-t-1 text-center" v-text="headinfo.teachers"></div>
                            <div class="text-muted text-uppercase font-weight-bold font-xs text-center">معلم</div>
                        </div>
                    </div>

                </div>
                <div class="col-lg-3 col-xs-6">

                    <div class="card">
                        <div class="card-block p-a-0 clearfix">
                            <i class="fa fa-inbox bg-danger p-a-2 font-2xl m-r-1 pull-left"></i>
                            <div class="h5 text-info m-b-0 p-t-1 text-center">0</div>
                            <div class="text-muted text-uppercase font-weight-bold font-xs text-center">پیام</div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="row">
                <section class="col-lg-8">

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title"> اخبار و رویداد ها<span class="fa fa-bullhorn pull-left"></span>
                            </h3>
                        </div>
                        <div class="panel-body">
                            <ul class="todo-list">
                                <li v-for="news in newses">
                                    <small class="label label-danger">خبر</small>
                                    <span>
                                        <a href="#" @click="ShowNews(news)">{{news.title}}
                                        </a>
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div v-if="polls.length>0" class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title"> نظرسنجی<span class="fa fa-comments-o pull-left"></span></h3>
                        </div>
                        <div class="panel-body">

                            <div id="pollSlider" class="carousel slide" data-ride="carousel">
                                <!-- Indicators -->
                                <ol class="carousel-indicators">
                                    <li v-for="(poll,index) in polls" data-target="#pollSlider" :data-slide-to="index"
                                        :class="index == 0 ? 'active':''"></li>
                                </ol>

                                <!-- Wrapper for slides -->
                                <div class="carousel-inner">
                                    <div v-for="(poll,index) in polls" class="item" :class="index == 0 ? 'active':''">
                                        <div class="row">
                                            <div class="col-sm-2"></div>
                                            <div class="col-sm-8">
                                                <br>
                                                <md-card>
                                                    <md-card-content>
                                                        <div>عنوان نظرسنجی : <span class="text-primary"
                                                                                   v-text="poll.title"></span></div>
                                                        <div>تعداد کل آرا : <span class="text-primary"
                                                                                  v-text="poll.count"></span></div>
                                                        <br>
                                                        <div>
                                                            <div v-for="(option,index) in poll.options">

                                                                <span class="label "
                                                                      :class="'label-'+colors[index%colors.length]">{{option.option}}
                                                                    <span class="badge pull-left"
                                                                          :class="'bg-'+colors[index%colors.length]">
                                            {{ option.percent }} %
                                        </span>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </md-card-content>
                                                </md-card>
                                                <br>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Left and right controls -->
                                <a class="left carousel-control" href="#pollSlider" data-slide="prev">
                                    <span class="glyphicon glyphicon-chevron-left"></span>
                                    <span class="sr-only">قبلی</span>
                                </a>
                                <a class="right carousel-control" href="#pollSlider" data-slide="next">
                                    <span class="glyphicon glyphicon-chevron-right"></span>
                                    <span class="sr-only">بعدی</span>
                                </a>
                            </div>


                        </div>
                    </div>

                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h3 class="panel-title"> دسترسی سریع<span class="fa fa-link pull-left"></span></h3>
                        </div>
                        <div class="panel-body">
                            <div class="row" style="margin-bottom: 10px;">
                                <div class="col-sm-2"></div>
                                <div class="col-sm-8">
                                    <div v-if="headinfo.auth.role == 'admin'" class="col-md-3 col-xs-6">
                                        <md-card md-with-hover style="margin-bottom: 10px;">
                                            <md-card-media>
                                                <a href="/teachers">
                                                <img class="img-thumbnail" src="images/icons/teacher.png" alt="">
                                                </a>
                                            </md-card-media>
                                            <md-tooltip md-delay="100" md-direction="bottom">دبیران</md-tooltip>
                                            <h3 class="text-center">دبیران</h3>
                                        </md-card>
                                    </div>
                                    <div v-if="headinfo.auth.role == 'admin'" class="col-md-3 col-xs-6">
                                        <md-card md-with-hover style="margin-bottom: 10px;">
                                            <md-card-media>
                                                <a href="/parents">
                                                <img class="img-thumbnail" src="images/icons/parent.png" alt="">
                                                </a>
                                            </md-card-media>
                                            <md-tooltip md-delay="100" md-direction="bottom">اولیا</md-tooltip>
                                            <h3 class="text-center">اولیا</h3>
                                        </md-card>
                                    </div>
                                    <div v-if="headinfo.auth.role != 'student'"  class="col-md-3 col-xs-6">
                                        <md-card md-with-hover style="margin-bottom: 10px;">
                                            <md-card-media>
                                                <a href="/students">
                                                <img class="img-thumbnail" src="images/icons/student.png" alt="">
                                                </a>
                                            </md-card-media>
                                            <md-tooltip md-delay="100" md-direction="bottom">دانش آموزان</md-tooltip>
                                            <h3 class="text-center">دانش آموزان</h3>
                                        </md-card>
                                    </div>
                                    <div class="col-md-3 col-xs-6">
                                        <md-card md-with-hover style="margin-bottom: 10px;">
                                            <md-card-media>
                                                <a href="/class-schedules">
                                                <img class="img-thumbnail" src="images/icons/schedule.png" alt="">
                                                </a>
                                            </md-card-media>
                                            <md-tooltip md-delay="100" md-direction="bottom">برنامه کلاسی</md-tooltip>
                                            <h3 class="text-center">برنامه کلاسی</h3>
                                        </md-card>
                                    </div>
                                    <div class="col-md-3 col-xs-6">
                                        <md-card md-with-hover style="margin-bottom: 10px;">
                                            <md-card-media>
                                                <a href="#">
                                                <img class="img-thumbnail" src="images/icons/message.png" alt="">
                                                </a>
                                            </md-card-media>
                                            <md-tooltip md-delay="100" md-direction="bottom">پیام ها</md-tooltip>
                                            <h3 class="text-center">پیام ها</h3>
                                        </md-card>
                                    </div>
                                    <div  v-if="headinfo.auth.role == 'teacher'"  class="col-md-3 col-xs-6">
                                        <md-card md-with-hover style="margin-bottom: 10px;">
                                            <md-card-media>
                                                <a href="/attendances">
                                                <img class="img-thumbnail" src="images/icons/attendance.png" alt="">
                                                </a>
                                            </md-card-media>
                                            <md-tooltip md-delay="100" md-direction="bottom">حضورغیاب</md-tooltip>
                                            <h3 class="text-center">حضورغیاب</h3>
                                        </md-card>
                                    </div>
                                    <div class="col-md-3 col-xs-6">
                                        <md-card md-with-hover style="margin-bottom: 10px;">
                                            <md-card-media>
                                                <a href="/online-exams">
                                                <img class="img-thumbnail" src="images/icons/exam.png" alt="">
                                                </a>
                                            </md-card-media>
                                            <md-tooltip md-delay="100" md-direction="bottom">امتحان آنلاین</md-tooltip>
                                            <h3 class="text-center">امتحان </h3>
                                        </md-card>
                                    </div>
                                    <div class="col-md-3 col-xs-6">
                                        <md-card md-with-hover style="margin-bottom: 10px;">
                                            <md-card-media>
                                                <a href="/assignments">
                                                <img class="img-thumbnail" src="images/icons/assignment.png" alt="">
                                                </a>
                                            </md-card-media>
                                            <md-tooltip md-delay="100" md-direction="bottom">تکالیف</md-tooltip>
                                            <h3 class="text-center">تکالیف</h3>
                                        </md-card>
                                    </div>
                                    <div class="col-md-3 col-xs-6">
                                        <md-card md-with-hover style="margin-bottom: 10px;">
                                            <md-card-media>
                                                <a href="/library">
                                                <img class="img-thumbnail" src="images/icons/library.png" alt="">
                                                </a>
                                            </md-card-media>
                                            <md-tooltip md-delay="100" md-direction="bottom">کتابخانه</md-tooltip>
                                            <h3 class="text-center">کتابخانه</h3>
                                        </md-card>
                                    </div>
                                    <div class="col-md-3 col-xs-6">
                                        <md-card md-with-hover style="margin-bottom: 10px;">
                                            <md-card-media>
                                                <a href="/news">
                                                <img class="img-thumbnail" src="images/icons/news.png" alt="">
                                                </a>
                                            </md-card-media>
                                            <md-tooltip md-delay="100" md-direction="bottom">اخبار</md-tooltip>
                                            <h3 class="text-center">اخبار</h3>
                                        </md-card>
                                    </div>
                                    <div class="col-md-3 col-xs-6">
                                        <md-card md-with-hover style="margin-bottom: 10px;">
                                            <md-card-media>
                                                <a href="/polls">
                                                <img class="img-thumbnail" src="images/icons/poll.png" alt="">
                                                </a>
                                            </md-card-media>
                                            <md-tooltip md-delay="100" md-direction="bottom">نظرسنجی</md-tooltip>
                                            <h3 class="text-center">نظرسنجی</h3>
                                        </md-card>
                                    </div>
                                    <div class="col-md-3 col-xs-6">
                                        <md-card md-with-hover style="margin-bottom: 10px;">
                                            <md-card-media>
                                                <a href="#">
                                                <img class="img-thumbnail" src="images/icons/gallery.png" alt="">
                                                </a>
                                            </md-card-media>
                                            <md-tooltip md-delay="100" md-direction="bottom">گالری</md-tooltip>
                                            <h3 class="text-center">گالری</h3>
                                        </md-card>
                                    </div>
                                    <div class="col-md-3 col-xs-6">
                                        <md-card md-with-hover style="margin-bottom: 10px;">
                                            <md-card-media>
                                                <a href="#">
                                                <img class="img-thumbnail" src="images/icons/credit-card.png" alt="">
                                                </a>
                                            </md-card-media>
                                            <md-tooltip md-delay="100" md-direction="bottom">پرداخت ها</md-tooltip>
                                            <h3 class="text-center">پرداخت ها</h3>
                                        </md-card>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </section>

                <section class="col-lg-4">

                    <div class="pcard hoverpcard">
                        <div class="pcardheader">

                        </div>
                        <div class="avatar">
                            <img alt="" :src="'/images/avatars/'+headinfo.auth.photo">
                        </div>
                        <div class="info">
                            <div class="title">
                                <h3>{{ headinfo.auth.fname+" "+headinfo.auth.lname }}</h3>
                            </div>
                            <div class="desc">برنامه نویس لاراول</div>
                            <div class="desc">برنامه نویس ویو جی اس</div>
                            <div class="desc">مدیر</div>
                        </div>
                        <div class="bottom">
                            <a class="btn btn-primary btn-twitter btn-sm" href="#">
                                <i class="fa fa-twitter"></i>
                            </a>
                            <a class="btn btn-danger btn-sm" rel="publisher"
                               href="#">
                                <i class="fa fa-google-plus"></i>
                            </a>
                            <a class="btn btn-primary btn-sm" rel="publisher"
                               href="https://www.facebook.com/ASAsite.com.page/">
                                <i class="fa fa-facebook"></i>
                            </a>
                            <a class="btn btn-warning btn-sm" rel="publisher"
                               href="https://www.instagram.com/asa_site/">
                                <i class="fa fa-instagram"></i>
                            </a>
                        </div>
                    </div>

                    <div class="dashboard-chat">
                        <div class="panel panel-info">
                            <div class="panel-heading top-bar">
                                <div class="col-md-8 col-xs-8">
                                    <h3 class="panel-title"><span class="glyphicon glyphicon-comment"></span> پیام </h3>
                                </div>

                            </div>
                            <div class="panel-body msg_container_base">

                                <div class="msg_container_padding">

                                    <div style="width:100%">
                                        <div class="msj macro">
                                            <div class="avatar"><img class="img-circle" style="width:100%;"
                                                                     src="https://avatars1.githubusercontent.com/u/20580199?v=3&s=460">
                                            </div>
                                            <div class="text text-l"><p>سلام خسته نباشید</p>
                                                <p>
                                                    <small>8:29 ب.ظ</small>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div style="width:100%;">
                                        <div class="msj-rta macro">
                                            <div class="text text-r"><p>سلام آقای محمدی . ممنون.</p>
                                                <p>
                                                    <small>8:29 ب.ظ</small>
                                                </p>
                                            </div>
                                            <div class="avatar" style="padding:0px 0px 0px 10px !important"><img
                                                    class="img-circle" style="width:100%;"
                                                    :src="'/images/avatars/'+headinfo.auth.photo">
                                            </div>
                                        </div>
                                    </div>
                                    <div style="width:100%">
                                        <div class="msj macro">
                                            <div class="avatar"><img class="img-circle" style="width:100%;"
                                                                     src="https://avatars1.githubusercontent.com/u/20580199?v=3&s=460">
                                            </div>
                                            <div class="text text-l"><p>امتحان چه روزی برگزار میشه استاد؟</p>
                                                <p>
                                                    <small>8:29 ب.ظ</small>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div style="width:100%;">
                                        <div class="msj-rta macro">
                                            <div class="text text-r"><p>دوشنبه</p>
                                                <p>
                                                    <small>8:29 ب.ظ</small>
                                                </p>
                                            </div>
                                            <div class="avatar" style="padding:0px 0px 0px 10px !important"><img
                                                    class="img-circle" style="width:100%;"
                                                    :src="'/images/avatars/'+headinfo.auth.photo">
                                            </div>
                                        </div>
                                    </div>
                                    <div style="width:100%">
                                        <div class="msj macro">
                                            <div class="avatar"><img class="img-circle" style="width:100%;"
                                                                     src="https://avatars1.githubusercontent.com/u/20580199?v=3&s=460">
                                            </div>
                                            <div class="text text-l"><p>خیلی ممنون استاد.</p>
                                                <p>
                                                    <small>8:29 ب.ظ</small>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div style="width:100%;">
                                        <div class="msj-rta macro">
                                            <div class="text text-r"><p>خواهش میکنم.</p>
                                                <p>
                                                    <small>8:29 ب.ظ</small>
                                                </p>
                                            </div>
                                            <div class="avatar" style="padding:0px 0px 0px 10px !important"><img
                                                    class="img-circle" style="width:100%;"
                                                    :src="'/images/avatars/'+headinfo.auth.photo">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-footer">
                                <div class="input-group">
                                    <input id="btn-input" type="text" class="form-control input-sm chat_input"
                                           placeholder="پیام خود را بنویسید..."/>
                                    <span class="input-group-btn">
                        <button class="btn btn-primary btn-sm" id="btn-chat">ارسال</button>
                        </span>
                                </div>
                            </div>
                        </div>
                    </div>


                </section>
            </div>

        </div>

        <!--SHOW NEWSES-->
        <div v-show="status == 'show-news'">
            <div class="box col-xs-12">
                <md-button class="md-primary md-raised" id="add_clase_modal" @click.native="status = 'dashboard'">
                    برگشت
                </md-button>
                <br>
                <md-card>
                    <md-card-content v-html="news.text">

                    </md-card-content>
                </md-card>
            </div>
        </div>
    </div>

</template>

<script>
    export default {
        data() {
            return {
                newses: [],
                news: {},
                polls: [],
                headinfo: [],
                colors: ['success', 'primary', 'danger', 'info', 'warning'],
                searchBox: '',
                status: 'dashboard',
                sortType: 'asc',
                sortBy: 'name',
            }
        },
        created: function () {
            this.getHeadInfo();
            this.getNews();
            this.getPoll();
        },
        methods: {
            getNews() {
                var vm = this;
                axios.get('/api/dashboard/get-news').then(function (response) {
                    Vue.set(vm.$data, 'newses', response.data);
                });
            },
            getPoll() {
                var vm = this;
                axios.get('/api/dashboard/get-poll').then(function (response) {
                    Vue.set(vm.$data, 'polls', response.data);
                    $('.carousel').carousel({
                        interval: false
                    });
                });
            },
            getHeadInfo() {
                var vm = this;
                axios.get('/api/dashboard/get-headinfo').then(function (response) {
                    Vue.set(vm.$data, 'headinfo', response.data);
                });
            },
            ShowNews(news) {
                Vue.set(this.$data, 'news', news);
                this.status = 'show-news';
            },
        }
    }
</script>
