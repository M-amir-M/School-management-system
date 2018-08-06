<template>
    <div>
        <!--LIST STUDENT-->
        <div v-if="status == 'list'">
            <div class="box col-xs-12">
                <div v-if="auth.role == 'admin'">
                    <md-button class="md-primary md-raised" id="add_student_modal"
                               @click.native="AddStudent() ">
                        افزودن
                    </md-button>
                    <a @click="status = 'wait'" class=" btn btn-info pull-left">در انتظار</a>
                    <a href="javascript:window.print()" class=" btn btn-success pull-left">چاپ</a>
                </div>
            </div>
            <br><br>
            <div class="box col-xs-12">
                <md-table-card>
                    <md-toolbar>

                        <h1 class="md-title">لیست دانش آموزان</h1>
                        <div class="col-sm-8 form-inline">
                            <div class="pull-left">
                                <div class="form-group has-success has-feedback">
                                    <label for="name">کلاس : </label>
                                    <select v-model="searchClase" class="form-control">
                                        <option value="">فیلتر بر اساس کلاس</option>
                                        <option v-for="clase in classes" :value="clase.id">{{clase.clase}}</option>
                                    </select>
                                </div>
                                &nbsp;
                                <div class="form-group has-success ">
                                    <label for="name">جستجو : </label>
                                    <input placeholder=" نام , نام کاربری" type="text" v-model="searchBox"
                                           class="form-control">
                                </div>
                            </div>

                        </div>

                    </md-toolbar>
                    <div class="table-responsive">
                        <table class="table">
                            <md-table md-sort="fullname" md-sort-type="asc" @sort="sortOrder">
                                <md-table-header>
                                    <md-table-row>
                                        <md-table-head md-sort-by="fullname">اسم</md-table-head>
                                        <md-table-head md-sort-by="username">نام کاربری</md-table-head>
                                        <md-table-head md-sort-by="phone">شماره تماس</md-table-head>
                                        <md-table-head>
                                            <span>عملیات</span>
                                        </md-table-head>
                                    </md-table-row>
                                </md-table-header>

                                <md-table-body>
                                    <md-table-row v-for="(stu, stuIndex) in orderedStudents" :key="stuIndex"
                                                  :md-item="stu">
                                        <md-table-cell>
                                            <div class="profile-cursor" @click="openDialog('profile');student = stu;">
                                                <md-avatar>
                                                    <img :src="'images/avatars/'+stu.photo" alt="Avatar">
                                                </md-avatar>
                                                {{ stu.fullname }}
                                                <md-tooltip md-direction="top"> اطلاعات {{ stu.fullname }}</md-tooltip>
                                            </div>

                                        </md-table-cell>
                                        <md-table-cell>{{ stu.username }}</md-table-cell>
                                        <md-table-cell>{{ stu.phone }}</md-table-cell>
                                        <md-table-cell>
                                            <md-theme v-if="auth.role == 'admin'" md-name="blue">
                                                <md-button class="md-fab md-mini md-primary"
                                                           @click.native="EditStudent(stu)">
                                                    <md-icon>edit</md-icon>
                                                    <md-tooltip md-direction="top">ویرایش اطلاعات {{ stu.fullname }}
                                                    </md-tooltip>
                                                </md-button>
                                            </md-theme>
                                            <md-theme md-name="green">
                                                <md-button class="md-fab md-mini  md-primary" id="edit_student_modal"
                                                           @click.native="student = stu; CumputMonthRate();CumputYearRate()">
                                                    <md-icon md-iconset="fa fa-file-text"
                                                             class="md-size-2x md-primary"></md-icon>
                                                    <md-tooltip md-direction="top">کارنامه و نمرات {{ stu.fullname }}
                                                    </md-tooltip>
                                                </md-button>
                                            </md-theme>
                                            <md-theme v-if="auth.role == 'admin'" md-name="red">
                                                <md-button class="md-fab md-mini  md-primary"
                                                           @click.native="DelStudent(stu.id,stuIndex)">
                                                    <md-icon md-iconset="fa fa-trash"
                                                             class="md-size-2x md-primary"></md-icon>
                                                    <md-tooltip md-direction="top">حذف {{ stu.fullname }}</md-tooltip>
                                                </md-button>
                                            </md-theme>

                                        </md-table-cell>
                                    </md-table-row>
                                </md-table-body>
                            </md-table>

                        </table>
                        <div v-if="students.length < 1" class="panel panel-default empty-table-msg">
                            <div class="panel-body">
                                <p class="text-center"><span class="text-info"> در این سال هیچ دانش آموزی ثبت نام نشده است.</span>
                                </p>
                            </div>
                        </div>


                    </div>

                </md-table-card>
            </div>

        </div>

        <!--ADD STUDENT-->
        <div v-show="status == 'add-student'">
            <div class="col-xs-12">
                <button @click="status = 'list'" class="btn btn-danger pull-left btn-sm  hvr-icon-back">بازگشت</button>
            </div>
            <br><br>
            <div class="col-xs-12">
                <md-card>
                    <md-card-header>
                        <div class="md-title">افزودن دانش آموز</div>
                    </md-card-header>
                    <md-card-content>
                        <div class="col-sm-2"></div>
                        <div class="col-sm-8">
                            <form @submit.prevent="StoreStudent()" novalidate>
                                <div class="form-group"
                                     :class="errors.has('username')?'has-error':''"
                                >
                                    <label for="" class="control-label">* نام کاربری</label>
                                    <input
                                            name="username"
                                            type="text"
                                            placeholder="نام کاربری"
                                            class="form-control"
                                            @blur="CheckUsername()"
                                            v-model="addStudent.username"
                                            v-validate.initial="'required'"
                                    >

                                    <div>
                                        <span v-show="errors.has('username')" class="help-block">{{ errors.first('username')
                                        }}
                                    </span>
                                        <span v-show="studentErrors.username == false && addStudent.username != ''"
                                              class="help-block text-danger">نام کاربری قبلا ثبت شده است.</span>

                                    </div>
                                </div>
                                <div class="form-group" :class="errors.has('email')?'has-error':''">
                                    <label for="" class="control-label">* ایمیل</label>
                                    <input
                                            name="email"
                                            type="email"
                                            placeholder="آدرس ایمیل"
                                            class="form-control"
                                            v-model="addStudent.email"
                                            v-validate.initial="'required|email'"
                                    >
                                    <div v-show="errors.has('email')" class="help-block">{{ errors.first('email') }}
                                    </div>

                                </div>
                                <div class="form-group" :class="errors.has('password')?'has-error':''">
                                    <label for="" class="control-label">* رمز عبور</label>
                                    <input
                                            name="password"
                                            type="password"
                                            placeholder="رمز عبور"
                                            class="form-control"
                                            v-model="addStudent.password"
                                            v-validate.initial="'required|min:6'"
                                    >
                                    <div v-show="errors.has('password')" class="help-block">{{ errors.first('password')
                                        }}
                                    </div>

                                </div>
                                <div class="form-group" :class="errors.has('fname')?'has-error':''">
                                    <label for="" class="control-label">* نام </label>
                                    <input
                                            name="fname"
                                            type="text"
                                            placeholder="نام"
                                            class="form-control"
                                            v-model="addStudent.fname"
                                            v-validate.initial="'required'"
                                    >
                                    <div v-show="errors.has('fname')" class="help-block">{{ errors.first('fname') }}
                                    </div>

                                </div>
                                <div class="form-group" :class="errors.has('lname')?'has-error':''">
                                    <label for="" class="control-label">* نام خانوادگی</label>
                                    <input
                                            name="lname"
                                            type="text"
                                            placeholder="نام خانوادگی"
                                            class="form-control"
                                            v-model="addStudent.lname"
                                            v-validate.initial="'required'"
                                    >
                                    <div v-show="errors.has('lname')" class="help-block">{{ errors.first('lname') }}
                                    </div>

                                </div>
                                <div class="form-group " :class="errors.has('clase')?'has-error':''">
                                    <div class="">
                                        <label for="" class="control-label">* کلاس </label>
                                        <select name="clase"
                                                class="form-control"
                                                v-model="addStudent.clase"
                                                v-validate.initial="'required'"
                                        >
                                            <option v-for="clase in classes" :value="clase.id">{{ clase.clase }} {{
                                                clase.field_name }}
                                            </option>

                                        </select>
                                        <div v-show="errors.has('clase')" class="help-block">{{ errors.first('clase')
                                            }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group" :class="errors.has('birthday')?'has-error':''">
                                    <label for="" class="control-label"> تاریخ تولد</label>
                                    <input
                                            name="birthday"
                                            v-mask="'####/##/##'"
                                            type="text"
                                            placeholder="تاریخ تولد"
                                            class="form-control"
                                            v-model="addStudent.birthday"
                                            v-validate.initial="'date_format:YYYY/MM/DD'"
                                    >
                                    <div v-show="errors.has('birthday')" class="help-block">{{ errors.first('birthday')
                                        }}
                                    </div>
                                </div>
                                <div class="form-group" :class="errors.has('address')?'has-error':''">
                                    <label for="" class="control-label"> آدرس</label>
                                    <textarea
                                            name="address"
                                            type="text"
                                            placeholder="آدرس"
                                            class="form-control"
                                            v-model="addStudent.address"
                                            v-validate.initial="'max:255'"
                                    ></textarea>
                                    <div v-show="errors.has('address')" class="help-block">{{ errors.first('address')
                                        }}
                                    </div>
                                </div>
                                <div class="form-group" :class="errors.has('phone')?'has-error':''">
                                    <label for="" class="control-label"> شماره تلفن</label>
                                    <input
                                            name="phone"
                                            type="text"
                                            placeholder="شماره تلفن"
                                            class="form-control"
                                            v-model="addStudent.phone"
                                            v-validate.initial="'numeric|max:15'"
                                    >
                                    <div v-show="errors.has('phone')" class="help-block">{{ errors.first('phone') }}
                                    </div>
                                </div>
                                <div class="form-group" :class="errors.has('mobile')?'has-error':''">
                                    <label for="" class="control-label"> شماره همراه</label>
                                    <input
                                            name="mobile"
                                            v-mask="'09#########'"
                                            type="text"
                                            placeholder=" شماره همراه 09123456789"
                                            class="form-control"
                                            v-model="addStudent.mobile"
                                            v-validate.initial="'min:11'"
                                    >
                                    <div v-show="errors.has('mobile')" class="help-block">{{ errors.first('mobile') }}
                                    </div>
                                </div>
                                <div class="form-group" :class="errors.has('photo')?'has-error':''">
                                    <label for="" class="control-label"> عکس</label>
                                    <input
                                            name="photo"
                                            type="file"
                                            class="form-control"
                                            id="add_photo"
                                            v-validate.initial="'mimes:image/jpeg,image/png'"
                                    >
                                    <div v-show="errors.has('photo')" class="help-block">{{ errors.first('photo') }}
                                    </div>
                                </div>

                                <input type="submit" class="btn btn-success pull-left" value="ذخیره">

                            </form>
                        </div>

                    </md-card-content>

                </md-card>
            </div>
        </div>

        <!--EDIT STUDENT-->
        <div v-show="status == 'edit-student'">
            <div class="col-xs-12">
                <button @click="status = 'list'" class="btn btn-danger pull-left btn-sm  hvr-icon-back">بازگشت</button>
            </div>
            <br><br>
            <div class="col-xs-12">
                <md-card>
                    <md-card-header>
                        <div class="md-title">ویرایش دانش آموز</div>
                    </md-card-header>
                    <md-card-content>
                        <div class="col-sm-2"></div>
                        <div class="col-sm-8">
                            <form @submit.prevent="UpdateStudent()" novalidate>
                                <div class="form-group" :class="errors.has('edit_username')?'has-error':''">
                                    <label for="" class="control-label">* نام کاربری</label>
                                    <input
                                            name="edit_username"
                                            type="text"
                                            placeholder="نام کاربری"
                                            class="form-control"
                                            @blur="CheckUsername()"
                                            v-model="student.username"
                                            v-validate.initial="'required'"
                                    >
                                    <div v-show="errors.has('edit_username')" class="help-block">{{
                                        errors.first('edit_username') }}
                                    </div>

                                    <span v-show="studentErrors.username == false && addStudent.username != ''"
                                          class="help-block text-danger">نام کاربری قبلا ثبت شده است.</span>

                                </div>
                                <div class="form-group" :class="errors.has('edit_email')?'has-error':''">
                                    <label for="" class="control-label">* ایمیل</label>
                                    <input
                                            name="edit_email"
                                            type="email"
                                            placeholder="آدرس ایمیل"
                                            class="form-control"
                                            v-model="student.email"
                                            v-validate.initial="'required|email'"
                                    >
                                    <div v-show="errors.has('email')" class="help-block">{{ errors.first('edit_email')
                                        }}
                                    </div>

                                </div>
                                <div class="form-group" :class="errors.has('edit_password')?'has-error':''">
                                    <label for="" class="control-label">* رمز عبور</label>
                                    <input
                                            name="edit_password"
                                            type="password"
                                            placeholder="رمز عبور"
                                            class="form-control"
                                            v-model="student.password"
                                            v-validate.initial="'required|min:6'"
                                    >
                                    <div v-show="errors.has('edit_password')" class="help-block">{{
                                        errors.first('edit_password') }}
                                    </div>

                                </div>
                                <div class="form-group" :class="errors.has('edit_fname')?'has-error':''">
                                    <label for="" class="control-label">* نام </label>
                                    <input
                                            name="edit_fname"
                                            type="text"
                                            placeholder="نام"
                                            class="form-control"
                                            v-model="student.fname"
                                            v-validate.initial="'required'"
                                    >
                                    <div v-show="errors.has('edit_fname')" class="help-block">{{
                                        errors.first('edit_fname') }}
                                    </div>

                                </div>
                                <div class="form-group" :class="errors.has('edit_lname')?'has-error':''">
                                    <label for="" class="control-label">* نام خانوادگی</label>
                                    <input
                                            name="edit_lname"
                                            type="text"
                                            placeholder="نام خانوادگی"
                                            class="form-control"
                                            v-model="student.lname"
                                            v-validate.initial="'required'"
                                    >
                                    <div v-show="errors.has('edit_lname')" class="help-block">{{
                                        errors.first('edit_lname') }}
                                    </div>

                                </div>
                                <div class="form-group " :class="errors.has('edit_clase')?'has-error':''">
                                    <div class="">
                                        <label for="" class="control-label">* کلاس </label>
                                        <select name="edit_clase"
                                                class="form-control"
                                                v-model="student.clase_id"
                                                v-validate.initial="'required'"
                                        >
                                            <option v-for="clase in classes" :selected="student.clase_id == clase.id"
                                                    :value="clase.id">{{clase.clase }} {{ clase.field_name }}
                                            </option>

                                        </select>
                                        <div v-show="errors.has('edit_clase')" class="help-block">{{
                                            errors.first('edit_clase') }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group" :class="errors.has('edit_birthday')?'has-error':''">
                                    <label for="" class="control-label"> تاریخ تولد</label>
                                    <input
                                            name="edit_birthday"
                                            v-mask="'####/##/##'"
                                            type="text"
                                            placeholder="تاریخ تولد"
                                            class="form-control"
                                            v-model="student.birthday"
                                            v-validate.initial="'date_format:YYYY/MM/DD'"
                                    >
                                    <div v-show="errors.has('edit_birthday')" class="help-block">{{
                                        errors.first('edit_birthday') }}
                                    </div>
                                </div>
                                <div class="form-group" :class="errors.has('address')?'has-error':''">
                                    <label for="" class="control-label"> آدرس</label>
                                    <textarea
                                            name="edit_address"
                                            type="text"
                                            placeholder="آدرس"
                                            class="form-control"
                                            v-model="student.address"
                                            v-validate.initial="'max:255'"
                                    ></textarea>
                                    <div v-show="errors.has('edit_address')" class="help-block">{{
                                        errors.first('edit_address') }}
                                    </div>
                                </div>
                                <div class="form-group" :class="errors.has('edit_phone')?'has-error':''">
                                    <label for="" class="control-label"> شماره تلفن</label>
                                    <input
                                            name="edit_phone"
                                            type="text"
                                            placeholder="شماره تلفن"
                                            class="form-control"
                                            v-model="student.phone"
                                            v-validate.initial="'numeric|max:15'"
                                    >
                                    <div v-show="errors.has('edit_phone')" class="help-block">{{
                                        errors.first('edit_phone') }}
                                    </div>
                                </div>
                                <div class="form-group" :class="errors.has('edit_mobile')?'has-error':''">
                                    <label for="" class="control-label"> شماره همراه</label>
                                    <input
                                            name="edit_mobile"
                                            v-mask="'09#########'"
                                            type="text"
                                            placeholder=" شماره همراه 09123456789"
                                            class="form-control"
                                            v-model="student.mobile"
                                            v-validate.initial="'min:11'"
                                    >
                                    <div v-show="errors.has('edit_mobile')" class="help-block">{{
                                        errors.first('edit_mobile') }}
                                    </div>
                                </div>
                                <div class="form-group" :class="errors.has('edit_photo')?'has-error':''">
                                    <label for="" class="control-label"> عکس</label>
                                    <input
                                            name="edit_photo"
                                            type="file"
                                            class="form-control"
                                            id="edit_photo"
                                            v-validate.initial="'mimes:image/jpeg,image/png'"
                                    >
                                    <div v-show="errors.has('edit_photo')" class="help-block">{{
                                        errors.first('edit_photo') }}
                                    </div>
                                </div>

                                <input type="submit" class="btn btn-success pull-left" value="ذخیره">

                            </form>
                        </div>

                    </md-card-content>

                </md-card>
            </div>
        </div>

        <!--BAR CHART-->
        <div v-if="status == 'bar-chart'">
            <div class="col-xs-12">
                <button @click="status = backStatus" class="btn btn-danger pull-left btn-sm  hvr-icon-back">بازگشت
                </button>
            </div>
            <br><br>
            <div class="col-xs-12">
                <md-card>
                    <md-card-header>
                        <div class="md-title"></div>
                    </md-card-header>
                    <md-card-content>
                        <div class="col-sm-2"></div>
                        <div class="col-sm-8">
                            <bar-chart
                                    :data="chartData.dataCollection"
                                    :options="chartData.options"
                                    :width="400"
                                    :height="200"
                            ></bar-chart>
                        </div>

                    </md-card-content>

                </md-card>
            </div>
        </div>

        <!--LINE CHART-->
        <div v-if="status == 'line-chart'">
            <div class="col-xs-12">
                <button @click="status = backStatus" class="btn btn-danger pull-left btn-sm  hvr-icon-back">بازگشت
                </button>
            </div>
            <br><br>
            <div class="col-xs-12">
                <md-card>
                    <md-card-header>
                        <div class="md-title"></div>
                    </md-card-header>
                    <md-card-content>
                        <div class="col-sm-2"></div>
                        <div class="col-sm-8">
                            <line-chart
                                    :data="chartData.dataCollection"
                                    :options="chartData.options"
                                    :width="400"
                                    :height="200"
                            ></line-chart>
                        </div>

                    </md-card-content>

                </md-card>
            </div>
        </div>

        <!--WAIT-->
        <div v-if="status == 'wait'">

            <div class=" col-xs-12">
                <button @click="status = 'list'" class="btn btn-success pull-left btn-sm  hvr-icon-back">بازگشت</button>
                <br><br>
                <md-table-card>
                    <md-toolbar>

                        <h1 class="md-title">لیست دانش آموزان</h1>
                        <div class="form-group has-success has-feedback">
                            <input placeholder="جستجو..." type="text" v-model="searchBox" class="form-control input-sm">
                            <span class="fa fa-search form-control-feedback" aria-hidden="true"></span>
                        </div>
                    </md-toolbar>

                    <div class="table-responsive">
                        <table class="table">
                            <md-table md-sort="lname" md-sort-type="asc" @sort="sortOrder">
                                <md-table-header>
                                    <md-table-row>
                                        <md-table-head md-sort-by="lname">اسم</md-table-head>
                                        <md-table-head md-sort-by="username">نام کاربری</md-table-head>
                                        <md-table-head md-sort-by="phone">شماره تماس</md-table-head>
                                        <md-table-head>
                                            <span>عملیات</span>
                                        </md-table-head>

                                    </md-table-row>
                                </md-table-header>

                                <md-table-body>
                                    <md-table-row v-for="(stu, stuIndex) in orderedWaits" :key="stuIndex"
                                                  :md-item="stu">
                                        <md-table-cell>{{ stu.lname }}</md-table-cell>
                                        <md-table-cell>{{ stu.username }}</md-table-cell>
                                        <md-table-cell>{{ stu.phone }}</md-table-cell>
                                        <md-table-cell>
                                            <a href="/teacher/edit/" type="button" class="btn btn-info btn-sm"
                                               title="Edit" tooltip="">ویرایش</a>
                                            <button @click="waitOk(wait,index)" type="button"
                                                    class="btn btn-success btn-sm"
                                                    title="" tooltip="">تائید
                                            </button>
                                            <a href="/teacher/wait/" type="button"
                                               class="btn btn-danger btn-sm" title="" tooltip="">حذف</a>
                                        </md-table-cell>
                                    </md-table-row>
                                </md-table-body>
                            </md-table>
                        </table>

                        <div v-if="marks.length < 1" class="panel panel-default empty-table-msg">
                            <div class="panel-body">
                                <p class="text-center"><span class="text-info"> هیچ دانش آموزی ثبت نام نکرده است.</span>
                                </p>
                            </div>
                        </div>

                    </div>
                </md-table-card>
            </div>
        </div>

        <!--MARKS-->
        <div v-if="status == 'marks'">
            <div v-if="auth.role != 'student'" class="col-xs-12">
                <button @click="status = 'list'" class="btn btn-danger pull-left btn-sm  hvr-icon-back">بازگشت</button>
            </div>
            <br><br>
            <div class=" col-xs-12">
                <md-card>
                    <md-card-header>
                        <div class="md-title"></div>
                    </md-card-header>
                    <md-card-content>
                        <div class="col-sm-3"></div>
                        <div class="col-sm-6">
                            <div class="row">
                                <div class="col-md-3 col-xs-6">
                                    <md-card md-with-hover style="margin-bottom: 10px;">
                                        <md-card-media>
                                            <a href="#" @click="getClassMarks()">
                                                <img class="img-thumbnail" src="images/icons/classi.png" alt="">
                                            </a>
                                        </md-card-media>
                                        <md-tooltip md-delay="100" md-direction="bottom">نمرات کلاسی</md-tooltip>
                                        <h3 class="text-center">نمرات کلاسی</h3>
                                    </md-card>
                                </div>
                                <div class="col-md-3 col-xs-6">
                                    <md-card md-with-hover style="margin-bottom: 10px;">
                                        <md-card-media>
                                            <a href="#" @click="getPayaniMarks()">
                                                <img class="img-thumbnail" src="images/icons/payani.png" alt="">
                                            </a>
                                        </md-card-media>
                                        <md-tooltip md-delay="100" md-direction="bottom">نمرات پایانی</md-tooltip>
                                        <h3 class="text-center">نمرات پایانی</h3>
                                    </md-card>
                                </div>

                                <div class="col-md-3 col-xs-6">
                                    <md-card md-with-hover style="margin-bottom: 10px;">
                                        <md-card-media>
                                            <a href="#" @click="getTotalMonthWb()">
                                                <img class="img-thumbnail" src="images/icons/monthwb.png" alt="">
                                            </a>
                                        </md-card-media>
                                        <md-tooltip md-delay="100" md-direction="bottom">کارنامه ماهانه</md-tooltip>
                                        <h3 class="text-center">کارنامه ماهانه</h3>
                                    </md-card>
                                </div>
                                <div class="col-md-3 col-xs-6">
                                    <md-card md-with-hover style="margin-bottom: 10px;">
                                        <md-card-media>
                                            <a href="#" @click="getYearWb()">
                                                <img class="img-thumbnail" src="images/icons/yearwb.png" alt="">
                                            </a>
                                        </md-card-media>
                                        <md-tooltip md-delay="100" md-direction="bottom">کارنامه سالانه</md-tooltip>
                                        <h3 class="text-center">کارنامه سالانه</h3>
                                    </md-card>
                                </div>

                            </div>
                        </div>
                    </md-card-content>

                </md-card>
            </div>
        </div>

        <!--ADD PAYANI MARKS-->
        <div v-if="status == 'add-payani-mark'">

            <div class="box col-xs-12">
                <a @click="status = 'payani-mark'" class="btn btn-danger pull-left btn-sm  hvr-icon-back">بازگشت</a>
                <br><br>

                <md-card>
                    <md-card-header>
                        <div class="md-title">افزودن نمره پایانی</div>
                    </md-card-header>
                    <md-card-content>
                        <div class="col-sm-2"></div>
                        <div class="col-sm-8">
                            <form @submit.prevent="StorePayaniMark()" novalidate class="form-horizontal">
                                <div class="form-group " :class="errors.has('add_psubject')?'has-error':''">
                                    <label for="add_psubject" class="col-sm-2 control-label">درس </label>
                                    <div class="col-sm-10">
                                        <select class="form-control"
                                                v-model="addMark.subject_id"
                                                v-validate.initial="'required|numeric'"
                                                name="add_psubject"
                                        >
                                            <option v-for="(ucs,index) in userClassSubjects" :selected="index == 0"
                                                    :value="ucs.id"
                                                    v-text="ucs.subject"></option>
                                        </select>
                                        <div v-show="errors.has('add_psubject')" class="help-block">{{
                                            errors.first('add_psubject') }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group " :class="errors.has('add_pstatus')?'has-error':''">
                                    <label for="add_pstatus" class="col-sm-2 control-label">نوع </label>
                                    <div class="col-sm-10">
                                        <select name="add_pstatus"
                                                class="form-control"
                                                v-model="addMark.status"
                                                v-validate.initial="'required'"
                                        >
                                            <option value="term1">دی ماه</option>
                                            <option value="term2">خرداد ماه</option>
                                            <option value="term3">شهریور ماه</option>
                                        </select>
                                        <div v-show="errors.has('add_pstatus')" class="help-block">{{
                                            errors.first('add_pstatus') }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group" :class="errors.has('add_pmark')?'has-error':''">
                                    <label for="add_pmark" class="col-sm-2 control-label">نمره </label>
                                    <div class="col-sm-10">
                                        <input
                                                v-validate.initial="'required|decimal|max:20|min:0'"
                                                name="add_pmark"
                                                v-model="addMark.mark"
                                                class="form-control"
                                                placeholder="نمره"
                                                type="text"
                                        >
                                        <div v-show="errors.has('add_pmark')" class="help-block">{{
                                            errors.first('add_pmark') }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group" :class="errors.has('add_pdate')?'has-error':''">
                                    <label for="add_pdate" class="col-sm-2 control-label">تاریخ</label>
                                    <div class="col-sm-10">
                                        <input
                                                v-validate.initial="'required|date_format:YYYY/MM/DD'"
                                                name="add_pdate"
                                                @click="SetDate('payani-mark-date-input')"
                                                v-model="addMark.date"
                                                id="payani-mark-date-input"
                                                placeholder="تاریخ"
                                                class="form-control"
                                                type="text">
                                        <div v-show="errors.has('add_pdate')" class="help-block">{{
                                            errors.first('add_pdate') }}
                                        </div>
                                    </div>
                                </div>
                                <input type="submit" class="btn btn-success pull-left" value="ذخیره">
                            </form>
                        </div>
                    </md-card-content>
                </md-card>

            </div>
        </div>

        <!--ADD CLASS MARKS-->
        <div v-if="status == 'add-class-mark'">

            <div class="box col-xs-12">
                <a @click="status = 'class-mark'" class="btn btn-danger pull-left btn-sm  hvr-icon-back">بازگشت</a>
                <br><br>

                <md-card>
                    <md-card-header>
                        <div class="md-title">افزودن نمره کلاسی</div>
                    </md-card-header>
                    <md-card-content>
                        <div class="col-sm-2"></div>
                        <div class="col-sm-8">
                            <form @submit.prevent="StoreClassMark()" novalidate class="form-horizontal">
                                <div class="form-group " :class="errors.has('add_csubject')?'has-error':''">
                                    <label for="add_csubject" class="col-sm-2 control-label">درس </label>
                                    <div class="col-sm-10">
                                        <select class="form-control"
                                                v-model="addMark.subject_id"
                                                v-validate.initial="'required|numeric'"
                                                data-vv-name="add_csubject"
                                        >
                                            <option v-for="(ucs,index) in userClassSubjects" :selected="index == 0"
                                                    :value="ucs.id"
                                                    v-text="ucs.subject"></option>
                                        </select>
                                        <div v-show="errors.has('add_csubject')" class="help-block">{{
                                            errors.first('add_csubject') }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group" :class="errors.has('add_cmark')?'has-error':''">
                                    <label for="add_cmark" class="col-sm-2 control-label">نمره </label>
                                    <div class="col-sm-10">
                                        <input
                                                v-validate.initial="'required|decimal|max_value:20|min_value:0'"
                                                data-vv-name="add_cmark"
                                                v-model="addMark.mark"
                                                class="form-control"
                                                placeholder="نمره"
                                                type="text"
                                        >
                                        <div v-show="errors.has('add_cmark')" class="help-block">{{
                                            errors.first('add_cmark') }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group" :class="errors.has('add_cdate')?'has-error':''">
                                    <label for="add_cdate" class="col-sm-2 control-label">تاریخ</label>
                                    <div class="col-sm-10">
                                        <input
                                                v-validate.initial="'required|date_format:YYYY/MM/DD'"
                                                name="add_cdate"
                                                @click="SetDate('class-mark-date-input')"
                                                v-model="addMark.date"
                                                id="class-mark-date-input"
                                                placeholder="تاریخ"
                                                class="form-control"
                                                type="text">
                                        <div v-show="errors.has('add_cdate')" class="help-block">{{
                                            errors.first('add_cdate') }}
                                        </div>
                                    </div>
                                </div>
                                <input type="submit" class="btn btn-success pull-left" value="ذخیره">
                            </form>

                        </div>
                    </md-card-content>
                </md-card>


            </div>
        </div>

        <!--CLASS MARKS-->
        <div v-if="status == 'class-mark'">
            <div class="box col-xs-12">
                <button @click="status = 'marks'" class="btn btn-success  pull-left btn-sm  hvr-icon-back">بازگشت
                </button>
                <button v-if="auth.role == 'teacher' || auth.role == 'admin'" @click="AddClassMark()"
                        class="btn btn-success  pull-right">افزودن نمره کلاسی
                </button>
                <br><br>
            </div>
            <div class="box col-xs-12">
                <md-table-card>
                    <md-toolbar>

                        <h1 class="md-title">نمرات کلاسی</h1>
                        <div class="form-group has-success has-feedback">
                            <input placeholder="جستجو..." type="text" v-model="searchBox" class="form-control input-sm">
                            <span class="fa fa-search form-control-feedback" aria-hidden="true"></span>
                        </div>
                    </md-toolbar>

                    <div class="table-responsive">
                        <table class="table">
                            <md-table md-sort="created_at" md-sort-type="asc" @sort="sortOrder">
                                <md-table-header>
                                    <md-table-row>
                                        <md-table-head md-sort-by="subject_name">درس</md-table-head>
                                        <md-table-head md-sort-by="mark"> نمره</md-table-head>
                                        <md-table-head md-sort-by="date"> تاریخ</md-table-head>
                                        <md-table-head v-if="auth.role == 'teacher'||auth.role == 'admin'">
                                            <span>عملیات</span>
                                        </md-table-head>

                                    </md-table-row>
                                </md-table-header>

                                <md-table-body>
                                    <md-table-row v-for="(cmark, markIndex) in orderedCMarks" :key="markIndex"
                                                  :md-item="cmark">
                                        <md-table-cell>{{ cmark.subject_name }}</md-table-cell>
                                        <md-table-cell>{{ cmark.mark }}</md-table-cell>
                                        <md-table-cell>{{ cmark.date }}</md-table-cell>
                                        <md-table-cell v-if="auth.role == 'teacher'||auth.role == 'admin'">
                                            <md-theme md-name="blue">
                                                <md-button class="md-fab md-mini md-primary"
                                                           @click.native="EditClassMark(cmark)">
                                                    <md-icon>edit</md-icon>
                                                    <md-tooltip md-direction="top">ویرایش نمره
                                                    </md-tooltip>
                                                </md-button>
                                            </md-theme>
                                            <md-theme md-name="red">
                                                <md-button class="md-fab md-mini  md-primary"
                                                           @click.native="DelMark(cmark.id,markIndex)">
                                                    <md-icon md-iconset="fa fa-trash"
                                                             class="md-size-2x md-primary"></md-icon>
                                                    <md-tooltip md-direction="top">حذف نمره
                                                    </md-tooltip>
                                                </md-button>
                                            </md-theme>

                                        </md-table-cell>
                                    </md-table-row>
                                </md-table-body>
                            </md-table>
                        </table>

                        <div v-if="marks.length < 1" class="panel panel-default empty-table-msg">
                            <div class="panel-body">
                                <p class="text-center"><span class="text-info"> هیچ نمره ای ثبت نشده است.</span>
                                </p>
                            </div>
                        </div>

                    </div>
                </md-table-card>
            </div>
        </div>

        <!--PAYANI MARKS-->
        <div v-if="status == 'payani-mark'">
            <div class="box col-xs-12">
                <button @click="status = 'marks'" class="btn btn-success  pull-left btn-sm  hvr-icon-back">بازگشت
                </button>
                <button v-if="auth.role!='student'" @click="AddPayaniMark()" class="btn btn-success  pull-right">افزودن
                    نمره پایانی
                </button>
                <br><br>
            </div>
            <div class="box col-xs-12">
                <md-table-card>
                    <md-toolbar>

                        <h1 class="md-title">نمرات پایانی</h1>
                        <div class="form-group has-success has-feedback">
                            <input placeholder="جستجو..." type="text" v-model="searchBox" class="form-control input-sm">
                            <span class="fa fa-search form-control-feedback" aria-hidden="true"></span>
                        </div>
                    </md-toolbar>

                    <div class="table-responsive">
                        <table class="table">
                            <md-table md-sort="subject_name" md-sort-type="asc" @sort="sortOrder">
                                <md-table-header>
                                    <md-table-row>
                                        <md-table-head md-sort-by="subject_name">درس</md-table-head>
                                        <md-table-head md-sort-by="mark"> نمره</md-table-head>
                                        <md-table-head md-sort-by="mark_type"> نوع</md-table-head>
                                        <md-table-head md-sort-by="date"> تاریخ</md-table-head>
                                        <md-table-head v-if="auth.role != 'student'">
                                            <span>عملیات</span>
                                        </md-table-head>

                                    </md-table-row>
                                </md-table-header>

                                <md-table-body>
                                    <md-table-row v-for="(pmark, markIndex) in orderedPMarks" :key="markIndex"
                                                  :md-item="pmark">
                                        <md-table-cell>{{ pmark.subject_name }}</md-table-cell>
                                        <md-table-cell>{{ pmark.mark }}</md-table-cell>
                                        <md-table-cell>{{ pmark.mark_type }}</md-table-cell>
                                        <md-table-cell>{{ pmark.date }}</md-table-cell>
                                        <md-table-cell v-if="auth.role != 'student'">
                                            <md-theme md-name="blue">
                                                <md-button class="md-fab md-mini md-primary"
                                                           @click.native="EditPayaniMark(pmark)">
                                                    <md-icon>edit</md-icon>
                                                    <md-tooltip md-direction="top">ویرایش نمره
                                                    </md-tooltip>
                                                </md-button>
                                            </md-theme>
                                            <md-theme md-name="red">
                                                <md-button class="md-fab md-mini  md-primary"
                                                           @click.native="DelMark(pmark.id,markIndex)">
                                                    <md-icon md-iconset="fa fa-trash"
                                                             class="md-size-2x md-primary"></md-icon>
                                                    <md-tooltip md-direction="top">حذف نمره
                                                    </md-tooltip>
                                                </md-button>
                                            </md-theme>
                                        </md-table-cell>
                                    </md-table-row>
                                </md-table-body>
                            </md-table>
                        </table>

                        <div v-if="marks.length < 1" class="panel panel-default empty-table-msg">
                            <div class="panel-body">
                                <p class="text-center"><span class="text-info"> هیچ نمره ای ثبت نشده است.</span>
                                </p>
                            </div>
                        </div>

                    </div>
                </md-table-card>
            </div>
        </div>

        <!--MONTH WORKBOOK-->
        <div v-if="status == 'total-month-workbook'">
            <button @click="status = 'marks'" class="btn btn-danger btn-sm pull-left btn-sm  hvr-icon-back">بازگشت
            </button>
            <br><br>
            <div class="box col-xs-12">
                <div class="box-header">
                    <h3 class="box-title ng-binding">اضافه کردن</h3>
                </div>
                <div class="box-body table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>نام درس / ماه</th>
                            <th>
                                <button class="btn btn-link" @click="getMonthChart(0)"> مهر</button>
                            </th>
                            <th>
                                <button class="btn btn-link" @click="getMonthChart(1)"> آبان</button>
                            </th>
                            <th>
                                <button class="btn btn-link" @click="getMonthChart(2)"> آذر</button>
                            </th>
                            <th>
                                <button class="btn btn-link" @click="getMonthChart(3)"> دی</button>
                            </th>
                            <th>
                                <button class="btn btn-link" @click="getMonthChart(4)"> بهمن</button>
                            </th>
                            <th>
                                <button class="btn btn-link" @click="getMonthChart(5)"> اسفند</button>
                            </th>
                            <th>
                                <button class="btn btn-link" @click="getMonthChart(6)"> فروردین</button>
                            </th>
                            <th>
                                <button class="btn btn-link" @click="getMonthChart(7)"> اردیبهشت</button>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(subject,sub_ind) in monthWBMark">
                            <td>
                                <button @click="getSubjectChart(monthWBMark[sub_ind],userClassSubjects[sub_ind])"
                                        class="btn btn-link" v-text="userClassSubjects[sub_ind].subject"></button>
                            </td>
                            <td v-for="(month,mon_ind) in subject">

                                <div v-text="monthWBMark[sub_ind][mon_ind]"></div>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <button @click="getMonthAvgChart()" class="btn btn-link">میانگین نمرات دانش آموز
                                </button>
                            </td>
                            <td v-for="mark in monthAVGMark" v-text="mark"></td>
                        </tr>
                        <tr>
                            <td>رتبه دانش آموز در کلاس</td>
                            <td v-text="monthRate.meh"></td>
                            <td v-text="monthRate.aba"></td>
                            <td v-text="monthRate.aza"></td>
                            <td v-text="monthRate.dey"></td>
                            <td v-text="monthRate.bah"></td>
                            <td v-text="monthRate.esf"></td>
                            <td v-text="monthRate.far"></td>
                            <td v-text="monthRate.ord"></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!--YEAR WORKBOOK-->
        <div v-if="status == 'year-workbook'">
            <div class="box col-xs-12">
                <button @click="status = 'marks'" class="btn btn-danger btn-sm pull-left hvr-icon-back"> بازگشت</button>
                <br><br>
                <div class="box-header">
                    <h3 class="box-title ng-binding">کارنامه سالانه</h3>
                </div>
                <div class="box-body table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>کد درس</th>
                            <th>نام درس</th>
                            <th>واحد</th>
                            <th>
                                <button class="btn btn-link" @click="getYearChart(0)">مستمر1</button>
                            </th>
                            <th>
                                <button class="btn btn-link" @click="getYearChart(1)">پایانی1</button>
                            </th>
                            <th>
                                <button class="btn btn-link" @click="getYearChart(2)">نوبت اول</button>
                            </th>
                            <th>
                                <button class="btn btn-link" @click="getYearChart(3)">مستمر2</button>
                            </th>
                            <th>
                                <button class="btn btn-link" @click="getYearChart(4)">پایانی2</button>
                            </th>
                            <th>
                                <button class="btn btn-link" @click="getYearChart(5)">سالانه</button>
                            </th>
                            <th>
                                <button class="btn btn-link" @click="getMinAvgMaxYearChart(0)">کمترین کلاس</button>
                            </th>
                            <th>
                                <button class="btn btn-link" @click="getMinAvgMaxYearChart(1)">میانگین کلاس</button>
                            </th>
                            <th>
                                <button class="btn btn-link" @click="getMinAvgMaxYearChart(2)">بیشترین کلاس</button>
                            </th>
                            <th>
                                <button class="btn btn-link">رتبه در کلاس</button>
                            </th>

                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(colData,col_ind) in yearWBMark">
                            <td v-for="col in colData" v-text="col"></td>
                            <td v-for="(val,val_ind) in yearMinAvgMaxRank[col_ind]"
                                v-text="round(yearMinAvgMaxRank[val_ind][col_ind])"></td>
                        </tr>
                        </tbody>
                        <tfoot>
                        <tr>
                            <td colspan="3" align="center">معدل</td>
                            <td v-text="yearAvg.mos1"></td>
                            <td v-text="yearAvg.pay1"></td>
                            <td v-text="yearAvg.nobat1"></td>
                            <td v-text="yearAvg.mos2"></td>
                            <td v-text="yearAvg.pay2"></td>
                            <td v-text="yearAvg.year"></td>
                            <td v-text="yearAvg.min"></td>
                            <td v-text="yearAvg.avg"></td>
                            <td v-text="yearAvg.max"></td>
                            <td v-text="yearAvg.rate"></td>

                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!--PROFILE-->
        <md-dialog md-open-from="#profile" md-close-to="#profile" ref="profile">
            <md-dialog-title> اطلاعات <span v-text="student.fullname"></span></md-dialog-title>
            <md-dialog-content>
                <div class="text-center">
                    <md-avatar class="md-large">
                        <img :src="'images/avatars/'+student.photo" alt="Avatar">
                    </md-avatar>
                </div>
                <table class="table table-responsive table-bordered">
                    <tbody>
                    <tr>
                        <td>نام کاربری</td>
                        <td v-text="student.username"></td>
                    </tr>
                    <tr>
                        <td>آدرس ایمیل</td>
                        <td v-text="student.email"></td>
                    </tr>
                    <tr>
                        <td>نام</td>
                        <td v-text="student.fname"></td>
                    </tr>
                    <tr>
                        <td>نام خانوادگی</td>
                        <td v-text="student.lname"></td>
                    </tr>
                    <tr>
                        <td>تاریخ تولد</td>
                        <td v-text="student.birthday"></td>
                    </tr>
                    <tr>
                        <td>آدرس</td>
                        <td v-text="student.address"></td>
                    </tr>
                    <tr>
                        <td>شماره تلفن</td>
                        <td v-text="student.phone"></td>
                    </tr>
                    <tr>
                        <td>شماره همراه</td>
                        <td v-text="student.mobile"></td>
                    </tr>
                    </tbody>
                </table>
            </md-dialog-content>
            <md-dialog-actions>
                <md-button class="md-primary md-raised " @click.native="closeDialog('profile')">بستن</md-button>
            </md-dialog-actions>

        </md-dialog>

        <!--EDIT CLASS MARK MODAL-->
        <md-dialog md-open-from="#edit_classmark_modal" md-close-to="#edit_classmark_modal" ref="edit_classmark_modal">
            <md-dialog-title>ویرایش نمره کلاسی</md-dialog-title>
            <md-dialog-content>
                <form @submit.prevent="UpdateMark()" novalidate class="form-horizontal" id="edit_classmark_form">
                    <div class="form-group " :class="errors.has('eidt_csubject')?'has-error':''">
                        <label for="eidt_csubject" class="col-sm-2 control-label">درس </label>
                        <div class="col-sm-10">
                            <select class="form-control"
                                    v-model="mark.subject_id"
                                    v-validate.initial="'required|numeric'"
                                    data-vv-name="eidt_csubject"
                            >
                                <option v-for="(ucs,index) in userClassSubjects" :selected="index == 0" :value="ucs.id"
                                        v-text="ucs.subject"></option>
                            </select>
                            <div v-show="errors.has('eidt_csubject')" class="help-block">{{
                                errors.first('eidt_csubject') }}
                            </div>
                        </div>
                    </div>
                    <div class="form-group" :class="errors.has('eidt_cmark')?'has-error':''">
                        <label for="eidt_cmark" class="col-sm-2 control-label">نمره </label>
                        <div class="col-sm-10">
                            <input
                                    v-validate.initial="'required|decimal|max_value:20|min_value:0'"
                                    data-vv-name="eidt_cmark"
                                    v-model="mark.mark"
                                    class="form-control"
                                    placeholder="نمره"
                                    type="text"
                            >
                            <div v-show="errors.has('eidt_cmark')" class="help-block">{{ errors.first('eidt_cmark') }}
                            </div>
                        </div>
                    </div>
                    <div class="form-group" :class="errors.has('eidt_cdate')?'has-error':''">
                        <label for="edit_cdate" class="col-sm-2 control-label">تاریخ</label>
                        <div class="col-sm-10">
                            <input
                                    v-validate.initial="'required|date_format:YYYY/MM/DD'"
                                    data-vv-name="eidt_cdate"
                                    @click="SetDate('edit-class-mark-date-input')"
                                    v-model="mark.date"
                                    id="edit-class-mark-date-input"
                                    placeholder="تاریخ"
                                    class="form-control"
                                    type="text">
                            <div v-show="errors.has('eidt_cdate')" class="help-block">{{ errors.first('eidt_cdate') }}
                            </div>
                        </div>
                    </div>
                </form>
            </md-dialog-content>

            <md-dialog-actions>
                <md-button class="md-primary" @click.native="closeDialog('edit_classmark_modal')">لغو</md-button>
                <md-button type="submit" form="edit_classmark_form" class="md-primary"
                           @click.native="closeDialog('edit_classmark_modal')">ذخیره
                </md-button>
            </md-dialog-actions>

        </md-dialog>

        <!--EDIT PAYANI MARK MODAL-->
        <md-dialog md-open-from="#edit_payanimark_modal" md-close-to="#edit_payanimark_modal"
                   ref="edit_payanimark_modal">
            <md-dialog-title>ویرایش نمره کلاسی</md-dialog-title>
            <md-dialog-content>
                <form @submit.prevent="UpdateMark()" novalidate class="form-horizontal" id="edit_payanimark_form">
                    <div class="form-group " :class="errors.has('edit_psubject')?'has-error':''">
                        <label for="edit_psubject" class="col-sm-2 control-label">درس </label>
                        <div class="col-sm-10">
                            <select class="form-control"
                                    v-model="mark.subject_id"
                                    v-validate.initial="'required|numeric'"
                                    name="edit_psubject"
                            >
                                <option v-for="(ucs,index) in userClassSubjects" :selected="index == 0" :value="ucs.id"
                                        v-text="ucs.subject"></option>
                            </select>
                            <div v-show="errors.has('edit_psubject')" class="help-block">{{
                                errors.first('edit_psubject') }}
                            </div>
                        </div>
                    </div>
                    <div class="form-group " :class="errors.has('edit_pstatus')?'has-error':''">
                        <label for="edit_pstatus" class="col-sm-2 control-label">نوع </label>
                        <div class="col-sm-10">
                            <select name="edit_pstatus"
                                    class="form-control"
                                    v-model="mark.status"
                                    v-validate.initial="'required'"
                            >
                                <option value="term1">دی ماه</option>
                                <option value="term2">خرداد ماه</option>
                                <option value="term3">شهریور ماه</option>
                            </select>
                            <div v-show="errors.has('edit_pstatus')" class="help-block">{{ errors.first('edit_pstatus')
                                }}
                            </div>
                        </div>
                    </div>
                    <div class="form-group" :class="errors.has('edit_pmark')?'has-error':''">
                        <label for="edit_pmark" class="col-sm-2 control-label">نمره </label>
                        <div class="col-sm-10">
                            <input
                                    v-validate.initial="'required|decimal|max_value:20|min_value:0'"
                                    name="edit_pmark"
                                    v-model="mark.mark"
                                    class="form-control"
                                    placeholder="نمره"
                                    type="text"
                            >
                            <div v-show="errors.has('edit_pmark')" class="help-block">{{ errors.first('edit_pmark') }}
                            </div>
                        </div>
                    </div>
                    <div class="form-group" :class="errors.has('edit_pdate')?'has-error':''">
                        <label for="pdate" class="col-sm-2 control-label">تاریخ</label>
                        <div class="col-sm-10">
                            <input
                                    v-validate.initial="'required|date_format:YYYY/MM/DD'"
                                    name="edit_pdate"
                                    @click="SetDate('edit-payani-mark-date-input')"
                                    v-model="mark.date"
                                    id="edit-payani-mark-date-input"
                                    placeholder="تاریخ"
                                    class="form-control"
                                    type="text">
                            <div v-show="errors.has('edit_pdate')" class="help-block">{{ errors.first('edit_pdate') }}
                            </div>
                        </div>
                    </div>
                </form>
            </md-dialog-content>

            <md-dialog-actions>
                <md-button class="md-primary" @click.native="closeDialog('edit_payanimark_modal')">لغو</md-button>
                <md-button type="submit" form="edit_payanimark_form" class="md-primary"
                           @click.native="closeDialog('edit_payanimark_modal')">ذخیره
                </md-button>
            </md-dialog-actions>

        </md-dialog>

    </div>
</template>

<script>
    import BarChart from './BarChart';
    import LineChart from './LineChart';


    export default {
        props: ['authUser'],
        data(){
            return {
                auth: {},
                chartData: {},
                farsiMonth: ['مهر', 'آبان', 'آذر', 'دی', 'بهمن', 'اسفند', 'فروردین', 'اردیبهشت'],
                waits: {},
                wait: {},
                student: {},
                students: [],
                classes: [],
                addStudent: {
                    'username': '',
                    'email': '',
                    'password': '',
                    'fname': '',
                    'lname': '',
                    'clase': '',
                    'birthday': '',
                    'address': '',
                    'phone': '',
                    'mobile': '',
                    'photo': ''
                },
                marks: {},
                mark: {},
                marksearch: '',
                addMark: {"subject_id": '', "student_id": '', "mark": '', "status": '', "date": ''},
                userClassSubjects: {},
                monthMark: {
                    meh: [],
                    aba: [],
                    aza: [],
                    dey: [],
                    bah: [],
                    esf: [],
                    far: [],
                    ord: [],
                },
                monthWBMark: [],
                yearWBMark: [],
                monthAVGMark: [],
                subjectChartMarks: [],
                monthChartMarks: [],
                yearMarks: {},
                monthRate: {
                    meh: '',
                    aba: '',
                    aza: '',
                    dey: '',
                    bah: '',
                    esf: '',
                    far: '',
                    ord: '',
                },
                yearMinAvgMaxRank: [],
                yearAvg: {
                    mos1: '',
                    mos2: '',
                    pay1: '',
                    pay2: '',
                    nobat1: '',
                    year: '',
                    min: '',
                    max: '',
                    avg: '',
                    rate: ''
                },
                searchBox: '',
                studentErrors: {
                    username: null,
                },
                searchClase: '',
                sortType: 'asc',
                sortBy: 'lname',
                status: '',
                backStatus: '',
            }
        },
        components: {
            'bar-chart': BarChart,
            'line-chart': LineChart,
        },
        created: function () {
            this.auth = JSON.parse(this.authUser);
            if (this.auth.role != 'student') {
                this.getClase()
                this.getStudents();
                this.getWaits();
            }
            if (this.auth.role == 'student') {
                this.getStudentWbs()
            }
        },
        computed: {
            orderedStudents: function () {
                var vm = this;
                return _.filter(_.orderBy(this.students, this.sortBy, this.sortType), function (student) {
                    return ((student.fullname || '').includes(vm.searchBox) || (student.username || '').includes(vm.searchBox)) && (student.clase_id == vm.searchClase || vm.searchClase == '');
                });
            },
            orderedWaits: function () {
                var vm = this;
                return _.filter(_.orderBy(this.waits, this.sortBy, this.sortType), function (student) {
                    return student.fullname.includes(vm.searchBox) || student.username.includes(vm.searchBox);
                });
            },
            orderedCMarks: function () {
                var vm = this;
                return _.filter(_.orderBy(this.marks, this.sortBy, this.sortType), function (mark) {
                    return (mark.subject_name||'').includes(vm.searchBox);
                });
            },
            orderedPMarks: function () {
                var vm = this;
                return _.filter(_.orderBy(this.marks, this.sortBy, this.sortType), function (mark) {
                    return (mark.subject_name||'').includes(vm.searchBox);
                });
            },
        },
        methods: {
            getWaits: function () {
                var vm = this;
                axios.get('/api/student/get-waits').then(function (response) {
                    Vue.set(vm.$data, 'waits', response.data);
                });
            },
            waitOk: function (wait, index) {
                var vm = this;
                axios.post('/api/student/set-wait', wait).then(function (response) {
                    if (response.status == 200) {
                        Vue.delete(vm.waits, index);
                        vm.students.push(wait);
                        noty({
                            type: 'success',
                            layout: 'topLeft',
                            text: 'ثبت نام دانش آموز با موفقیت تائید شد.',
                            timeout: 3000,
                        });
                    }
                }).catch(function (error) {
                    noty({
                        type: 'error',
                        layout: 'topLeft',
                        text: 'مشکلی وجود دارد.',
                        textAlign: 'right',
                        animation: {
                            open: 'hvr-buzz-out'
                        },
                        timeout: 3000,
                    });
                    vm.waiting(false)
                });
            },
            getStudents: function () {
                var vm = this;
                vm.waiting(true)
                axios.get('/api/get-students').then(function (response) {
                    Vue.set(vm.$data, 'students', response.data);
                    vm.status = 'list';
                    vm.waiting(false)
                });
            },
            getStudentWbs: function () {
                var vm = this;
                vm.waiting(true)
                axios.get('/api/get-students').then(function (response) {
                    Vue.set(vm.$data, 'student', response.data);
                    vm.status = 'marks';
                    vm.waiting(false)
                });
            },
            getClase() {
                var vm = this;
                axios.get('/api/get-clases').then(function (response) {
                    Vue.set(vm.$data, 'classes', response.data);
                });
            },
            ProfileView: function (student) {
                Vue.set(this.$data, 'student', student);
            },
            StoreStudent() {
                var vm = this;
                var datas = new FormData();
                datas.append('id', vm.addStudent.id);
                datas.append('username', vm.addStudent.username);
                datas.append('email', vm.addStudent.email);
                datas.append('password', vm.addStudent.password);
                datas.append('fname', vm.addStudent.fname);
                datas.append('lname', vm.addStudent.lname);
                datas.append('clase', vm.addStudent.clase);
                datas.append('birthday', vm.addStudent.birthday);
                datas.append('address', vm.addStudent.address);
                datas.append('phone', vm.addStudent.phone);
                datas.append('mobile', vm.addStudent.mobile);


                datas.append('photo', document.getElementById('add_photo').files[0]);

                var config = {
                    onUploadProgress: function (progressEvent) {
                        var percentCompleted = Math.round((progressEvent.loaded * 100) / progressEvent.total);
                    }
                };

                this.$validator.validateAll({
                    username: vm.addStudent.username,
                    email: vm.addStudent.email,
                    password: vm.addStudent.password,
                    fname: vm.addStudent.fname,
                    lname: vm.addStudent.lname,
                    birthday: vm.addStudent.birthday,
                    address: vm.addStudent.address,
                    phone: vm.addStudent.phone,
                    mobile: vm.addStudent.mobile,
//                    photo:vm.addStudent.photo
                }).then(function (validate) {
                    if (validate) {
                        vm.waiting(true)

                        axios.post('/api/store-student', datas, config).then(function (response) {
                            vm.addStudent = {
                                'username': '',
                                'email': '',
                                'password': '',
                                'fname': '',
                                'lname': '',
                                'clase': '',
                                'birthday': '',
                                'address': '',
                                'phone': '',
                                'mobile': '',
                                'photo': '',
                            };
                            if (response.status == 200) {
                                vm.students.push(response.data);
                                Vue.nextTick(() => {
                                    vm.errors.clear()
                                })
                                vm.ValidateMsg();
                                noty({
                                    type: 'success',
                                    layout: 'topLeft',
                                    text: 'دانش آموز با موفقیت ذخیره شد.',
                                    timeout: 3000,
                                });
                                document.getElementById('noty_audio').play();
                            }
                            vm.waiting(false)

                        }).catch(function (error) {
                            noty({
                                type: 'error',
                                layout: 'topLeft',
                                text: 'مشکلی وجود دارد.',
                                textAlign: 'right',
                                animation: {
                                    open: 'hvr-buzz-out'
                                },
                                timeout: 3000,
                            });
                            vm.waiting(false)
                        });
                    }

                }).catch(function () {

                });

            },
            filterMonthMarks(subject_id, monthMark) {
                return monthMark.filter(function (mark) {
                    return mark.subject_id == subject_id;
                });
            },
            filterYearMarks(subject_id, wbs) {
                var vm = this;
                return wbs.filter(function (wb) {
                    return wb.subject_id == subject_id && wb.cuy_id == vm.student.cuy_id;
                });
            },
            getMonthMark(){
                this.monthWBMark = [];
                this.monthAVGMark = [];

                for (var subject of this.userClassSubjects) {

                    var marks = []
                    for (var month in this.monthMark) {
                        if (this.filterMonthMarks(subject.id, this.monthMark[month]).length > 0) {
                            marks.push(this.filterMonthMarks(subject.id, this.monthMark[month])[0].avg);
                        }
                        else {
                            marks.push(null);
                        }
                    }

                    this.monthWBMark.push(marks);
                }
                for (var month in this.monthMark) {
                    var mark = this.ComputMonthAvg(this.monthMark[month]);
                    this.monthAVGMark.push(mark);
                }
            },
            EditStudent: function (student) {
                Vue.set(this.$data, 'student', student);
                this.status = 'edit-student';
                Vue.nextTick(() => {
                    this.errors.clear()
                })
                this.ValidateMsg();
            },
            UpdateStudent: function () {
                var vm = this;
                var datas = new FormData();
                datas.append('id', vm.student.id);
                datas.append('username', vm.student.username);
                datas.append('email', vm.student.email);
                datas.append('password', vm.student.password);
                datas.append('fname', vm.student.fname);
                datas.append('lname', vm.student.lname);
                datas.append('clase', vm.student.clase_id);
                datas.append('birthday', vm.student.birthday);
                datas.append('address', vm.student.address);
                datas.append('phone', vm.student.phone);
                datas.append('mobile', vm.student.mobile);


                datas.append('photo', document.getElementById('edit_photo').files[0]);

                var config = {
                    onUploadProgress: function (progressEvent) {
                        var percentCompleted = Math.round((progressEvent.loaded * 100) / progressEvent.total);
                    }
                };

                this.$validator.validateAll({
                    edit_username: vm.student.username,
                    edit_email: vm.student.email,
                    edit_password: vm.student.password,
                    edit_fname: vm.student.fname,
                    edit_lname: vm.student.lname,
                    edit_clase: vm.student.clase_id,
                    edit_birthday: vm.student.birthday,
                    edit_address: vm.student.address,
                    edit_phone: vm.student.phone,
                    edit_mobile: vm.student.mobile,
//                    edit_photo:vm.student.photo
                }).then(function (validate) {
                    if (validate) {
                        vm.waiting(true)

                        axios.post('/api/update-student', datas, config).then(function (response) {
                            if (response.status == 200) {
                                noty({
                                    type: 'success',
                                    layout: 'topLeft',
                                    text: 'اطلاعات دانش آموز با موفقیت بروز شد.',
                                    timeout: 3000,
                                });
                                document.getElementById('noty_audio').play();
                            }
                            vm.waiting(false)
                        }).catch(function (error) {
                            noty({
                                type: 'error',
                                layout: 'topLeft',
                                text: 'مشکلی وجود دارد.',
                                textAlign: 'right',
                                animation: {
                                    open: 'hvr-buzz-out'
                                },
                                timeout: 3000,
                            });
                            vm.waiting(false)
                        });
                    }

                }).catch(function () {

                });
            },
            DelStudent: function (student_id, index) {
                var vm = this;
                swal({
                    title: 'حذف دانش آموز',
                    text: 'از حذف دانش آموز مطمئن هستید؟',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'بله',
                    cancelButtonText: 'خیر'
                }).then(function () {
                    vm.waiting(true)
                    axios.post('/api/delete-student', {student_id: student_id}).then(function (response) {
                        if (response.status == 200) {
                            Vue.delete(vm.students, index);
                            noty({
                                type: 'success',
                                layout: 'topLeft',
                                text: 'دانش آموز با موفقیت حذف شد.',
                                timeout: 3000,
                            });
                            document.getElementById('noty_audio').play();
                            vm.students.delete
                        }
                        vm.waiting(false)
                    }).catch(function (error) {
                        noty({
                            type: 'error',
                            layout: 'topLeft',
                            text: 'مشکلی وجود دارد.',
                            textAlign: 'right',
                            animation: {
                                open: 'hvr-buzz-out'
                            },
                            timeout: 3000,
                        });
                        vm.waiting(false)
                    });
                });
            },
            getClassMarks: function () {
                var vm = this;
                vm.waiting(true)
                axios.get('/api/get-class-marks/' + vm.student.username).then(function (response) {
                    if (response.status == 200) {
                        Vue.set(vm.$data, 'marks', response.data['marks']);
                        Vue.set(vm.$data, 'userClassSubjects', response.data['subjects']);
                        vm.errors = {};
                        vm.status = 'class-mark';
                    }
                    vm.waiting(false)
                });
            },
            CheckUsername(){
                var vm = this;
                if (vm.addStudent.username != '') {
                    axios.get('/api/students/check-username/' + vm.addStudent.username).then(function (response) {
                        if (response.status == 200) {
                            if (response.data == true) {
                                vm.studentErrors.username = true;
                            }
                            if (response.data == false) {
                                vm.studentErrors.username = false;
                            }
                        }
                    });

                } else {
                    vm.studentErrors.username = null;
                }
            },
            ValidateMsg(){
                this.$validator.updateDictionary({
                    'fa': {
                        custom: {
                            add_cmark: {
                                required: 'کادر نمره نباید خالی باشد.',
                                decimal: 'نمره باید به صورت عدد باشد.',
                                max_value: 'نمره بیشتر از 20 هست.',
                                min_value: 'نمره کمتر از 0 هست.',
                            },
                            add_csubject: {
                                required: 'درس باید انتخاب شود.',
                                numeric: 'درس معتبر نمی باشد.'
                            },
                            add_cdate: {
                                required: 'تاریخ باید انتخاب شود.',
                                date_format: 'تاریخ باید به فرم "روز/ماه/سال" باشد.'
                            },
                            edit_cmark: {
                                required: 'کادر نمره نباید خالی باشد.',
                                decimal: 'نمره باید به صورت عدد باشد.',
                                max_value: 'نمره بیشتر از 20 هست.',
                                min_value: 'نمره کمتر از 0 هست.',
                            },
                            edit_csubject: {
                                required: 'درس باید انتخاب شود.',
                                numeric: 'درس معتبر نمی باشد.'
                            },
                            edit_cdate: {
                                required: 'تاریخ باید انتخاب شود.',
                                date_format: 'تاریخ باید به فرم "روز/ماه/سال" باشد.'
                            },
                            add_pmark: {
                                required: 'کادر نمره نباید خالی باشد.',
                                decimal: 'نمره باید به صورت عدد باشد.',
                                max_value: 'نمره بیشتر از 20 هست.',
                                min_value: 'نمره کمتر از 0 هست.',
                            },
                            add_pstatus: {
                                required: 'نوع نمره را انتخاب کنید.',
                            },
                            add_psubject: {
                                required: 'درس باید انتخاب شود.',
                                numeric: 'درس معتبر نمی باشد.'
                            },
                            add_pdate: {
                                required: 'تاریخ باید انتخاب شود.',
                                date_format: 'تاریخ باید به فرم "روز/ماه/سال" باشد.'
                            },
                            edit_pmark: {
                                required: 'کادر نمره نباید خالی باشد.',
                                decimal: 'نمره باید به صورت عدد باشد.',
                                max_value: 'نمره بیشتر از 20 هست.',
                                min_value: 'نمره کمتر از 0 هست.',
                            },
                            edit_pstatus: {
                                required: 'نوع نمره را انتخاب کنید.',
                            },
                            edit_psubject: {
                                required: 'درس باید انتخاب شود.',
                                numeric: 'درس معتبر نمی باشد.'
                            },
                            edit_pdate: {
                                required: 'تاریخ باید انتخاب شود.',
                                date_format: 'تاریخ باید به فرم "روز/ماه/سال" باشد.'
                            },
                            username: {
                                required: 'نام کاربری نمی تواند خالی باشد.',
                            },
                            email: {
                                required: 'ایمیل نمی تواند خالی باشد.',
                                email: 'آدرس ایمیل معتبر نمی باشد.',
                            },
                            password: {
                                required: 'رمز عبوری انتخاب کنید.',
                                min: 'رمز عبور باید حداقل 6 حرف باشد.',
                            },
                            fname: {
                                required: 'نام دانش آموز نباید خالی باشد.',
                            },
                            lname: {
                                required: 'نام خانوادگی دانش آموز نباید خالی باشد.',
                            },
                            clase: {
                                required: 'کلاس دانش آموز را انتخاب کنید.',
                            },
                            birthday: {
                                date_format: 'تاریخ تولد باید به فرم "روز/ماه/سال" باشد.'
                            },
                            address: {
                                max: 'آدرس حداکثر می تواند 255 جرف باشد.'
                            },
                            phone: {
                                max: 'شماره تلفن حداکثر می تواند 15 رقم باشد.',
                                numeric: 'شماره تلفن باید عدد باشد.',
                            },
                            mobile: {
                                min: 'شماره همراه باید 11 رقم باشد.',
                            },
                            photo: {
                                mimes: 'فایل انتخاب شده عکس نمی باشد.',
                            },
                            edit_username: {
                                required: 'نام کاربری نمی تواند خالی باشد.',
                            },
                            edit_email: {
                                required: 'ایمیل نمی تواند خالی باشد.',
                                email: 'آدرس ایمیل معتبر نمی باشد.',
                            },
                            edit_password: {
                                required: 'رمز عبوری انتخاب کنید.',
                                min: 'رمز عبور باید حداقل 6 حرف باشد.',
                            },
                            edit_fname: {
                                required: 'نام دانش آموز نباید خالی باشد.',
                            },
                            edit_lname: {
                                required: 'نام خانوادگی دانش آموز نباید خالی باشد.',
                            },
                            edit_clase: {
                                required: 'کلاس دانش آموز را انتخاب کنید.',
                            },
                            edit_birthday: {
                                date_format: 'تاریخ تولد باید به فرم "روز/ماه/سال" باشد.'
                            },
                            edit_address: {
                                max: 'آدرس حداکثر می تواند 255 جرف باشد.'
                            },
                            edit_phone: {
                                max: 'شماره تلفن حداکثر می تواند 15 رقم باشد.',
                                numeric: 'شماره تلفن باید عدد باشد.',
                            },
                            edit_mobile: {
                                min: 'شماره همراه باید 11 رقم باشد.',
                            },
                            edit_photo: {
                                mimes: 'فایل انتخاب شده عکس نمی باشد.',
                            },
                        },

                    }
                });
                this.$validator.setLocale('fa');
            },
            AddStudent(){
                this.status = 'add-student';
                Vue.nextTick(() => {
                    this.errors.clear()
                })
                this.ValidateMsg();
            },
            AddClassMark(){
                Vue.nextTick(() => {
                    this.errors.clear()
                })
                this.ValidateMsg();
                this.status = 'add-class-mark';
            },
            EditClassMark(cmark){
                Vue.nextTick(() => {
                    this.errors.clear()
                })
                this.ValidateMsg();
                this.openDialog('edit_classmark_modal');
                this.mark = cmark;
            },
            AddPayaniMark(){
                this.status = 'add-payani-mark';
                Vue.nextTick(() => {
                    this.errors.clear()
                })
                this.ValidateMsg();
            },
            EditPayaniMark(pmark){
                Vue.nextTick(() => {
                    this.errors.clear()
                });
                this.ValidateMsg();
                this.openDialog('edit_payanimark_modal');
                this.mark = pmark;
            },
            getPayaniMarks: function () {
                var vm = this;
                vm.waiting(true)
                axios.get('/api/get-payani-marks/' + vm.student.username).then(function (response) {
                    if (response.status == 200) {
                        Vue.set(vm.$data, 'marks', response.data['marks']);
                        Vue.set(vm.$data, 'userClassSubjects', response.data['subjects']);
                        vm.status = 'payani-mark';
                        vm.waiting(false)
                    }
                });
            },
            StoreClassMark() {

                var vm = this;
                this.$validator.validateAll({
                    add_csubject: vm.addMark.subject_id,
                    add_cmark: vm.addMark.mark,
                    add_cdate: vm.addMark.date
                }).then(function (validate) {
                    if (validate) {
                        vm.waiting(true)

                        vm.addMark.student_id = vm.student.id;
                        axios.post('/api/store-class-mark', vm.addMark).then(function (response) {
                            if (response.status == 200) {
                                vm.marks.push(response.data);

                                noty({
                                    type: 'success',
                                    layout: 'topLeft',
                                    text: 'نمره دانش آموز با موفقیت ثبت شد.',
                                    timeout: 3000,
                                });
                                document.getElementById('noty_audio').play();

                            }
                            vm.waiting(false)
                        }).catch(function (error) {
                            noty({
                                type: 'error',
                                layout: 'topLeft',
                                text: 'مشکلی وجود دارد.',
                                textAlign: 'right',
                                animation: {
                                    open: 'hvr-buzz-out'
                                },
                                timeout: 3000,
                            });
                            vm.waiting(false)
                        });
                    }

                }).catch(function () {

                });
            },
            StorePayaniMark() {

                var vm = this;
                this.$validator.validateAll({
                    add_psubject: vm.addMark.subject_id,
                    add_pmark: vm.addMark.mark,
                    add_pdate: vm.addMark.date,
                    add_pstatus: vm.addMark.status
                }).then(function (validate) {
                    if (validate) {
                        vm.waiting(true)

                        vm.addMark.student_id = vm.student.id;
                        axios.post('/api/store-payani-mark', vm.addMark).then(function (response) {
                            if (response.status == 200) {
                                vm.marks.push(response.data);
                                noty({
                                    type: 'success',
                                    layout: 'topLeft',
                                    text: 'نمره دانش آموز با موفقیت ثبت شد.',
                                    timeout: 3000,
                                });
                                document.getElementById('noty_audio').play();
                            }
                            vm.waiting(false)
                        }).catch(function (error) {
                            noty({
                                type: 'error',
                                layout: 'topLeft',
                                text: 'مشکلی وجود دارد.',
                                textAlign: 'right',
                                animation: {
                                    open: 'hvr-buzz-out'
                                },
                                timeout: 3000,
                            });
                            vm.waiting(false)
                        });
                    }
                }).catch(function () {

                });
            },
            DelMark: function (mark_id, index) {
                var vm = this;
                swal({
                    title: 'حذف نمره',
                    text: 'از حذف نمره مطمئن هستید؟',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'بله',
                    cancelButtonText: 'خیر'
                }).then(function () {
                    vm.waiting(true)
                    axios.post('/api/delete-class-mark', {mark_id: mark_id}).then(function (response) {
                        if (response.status == 200) {
                            Vue.delete(vm.marks, index);
                            noty({
                                type: 'success',
                                layout: 'topLeft',
                                text: 'نمره دانش آموز با موفقیت حذف شد.',
                                timeout: 3000,
                            });
                            document.getElementById('noty_audio').play();
                        }
                        vm.waiting(false)
                    }).catch(function (error) {
                        noty({
                            type: 'error',
                            layout: 'topLeft',
                            text: 'مشکلی وجود دارد.',
                            textAlign: 'right',
                            animation: {
                                open: 'hvr-buzz-out'
                            },
                            timeout: 3000,
                        });
                        vm.waiting(false)
                    });
                });
            },
            UpdateMark: function () {
                var vm = this;
                var url = '/api/update-class-mark';
                var valid = {
                    edit_csubject: vm.addMark.subject_id,
                    edit_cmark: vm.addMark.mark,
                    edit_cdate: vm.addMark.date,
                }
                if (vm.mark.status == 'term1' || vm.mark.status == 'term2' || vm.mark.status == 'term3') {
                    url = '/api/update-payani-mark';
                    valid = {
                        edit_psubject: vm.addMark.subject_id,
                        edit_pmark: vm.addMark.mark,
                        edit_pdate: vm.addMark.date,
                        edit_pstatus: vm.addMark.status
                    }
                }

                this.$validator.validateAll().then(function (validate) {
                    if (validate) {
                        vm.waiting(true)
                        axios.post(url, vm.mark).then(function (response) {
                            if (response.status == 200) {
                                noty({
                                    type: 'success',
                                    layout: 'topLeft',
                                    text: 'نمره دانش آموز با موفقیت بروز شد.',
                                    timeout: 3000,
                                });
                            }
                            vm.waiting(false)

                        }).catch(function (error) {
                            noty({
                                type: 'error',
                                layout: 'topLeft',
                                text: 'مشکلی وجود دارد.',
                                textAlign: 'right',
                                animation: {
                                    open: 'hvr-buzz-out'
                                },
                                timeout: 3000,
                            });
                            vm.waiting(false)
                        });
                    }
                }).catch(function () {

                });

            },
            getTotalMonthWb: function () {
                var vm = this;
                vm.waiting(true)
                var username = vm.student.username;
                axios.get('/api/get-total-month-workbook/' + username).then(function (response) {
                    var marks = response.data['marks'];
                    var subjects = response.data['subjects'];
                    Vue.set(vm.$data, 'userClassSubjects', subjects);

                    vm.monthMark = {
                        meh: [],
                        aba: [],
                        aza: [],
                        dey: [],
                        bah: [],
                        esf: [],
                        far: [],
                        ord: [],
                    }
                    for (var mark of marks) {
                        switch (mark.status) {
                            case 'meh':
                                vm.monthMark.meh.push(mark);
                                break;

                            case 'aba':
                                vm.monthMark.aba.push(mark);
                                break;

                            case 'aza':
                                vm.monthMark.aza.push(mark);
                                break;

                            case 'dey':
                                vm.monthMark.dey.push(mark);
                                break;

                            case 'bah':
                                vm.monthMark.bah.push(mark);
                                break;

                            case 'esf':
                                vm.monthMark.esf.push(mark);
                                break;

                            case 'far':
                                vm.monthMark.far.push(mark);
                                break;

                            case 'ord':
                                vm.monthMark.ord.push(mark);
                                break;

                        }
                    }
                    vm.getMonthMark();

                    vm.status = 'total-month-workbook';
                    vm.waiting(false)
                });
            },
            getSubjectChart(marks, subject){
                this.resetChartData();
                this.subjectChartMarks = marks;
                this.chartData.dataCollection.datasets[0].data = this.subjectChartMarks;
                this.chartData.dataCollection.labels = this.farsiMonth;
                this.backStatus = 'total-month-workbook'
                this.status = 'bar-chart'
            },
            getMonthAvgChart(){
                this.resetChartData();
                this.chartData.dataCollection.datasets[0].data = this.monthAVGMark;
                this.chartData.dataCollection.labels = this.farsiMonth;
                this.chartData.dataCollection.datasets[0].label = ' نمودار میانگین نمرات در ماه ';
                this.backStatus = 'total-month-workbook'
                this.status = 'bar-chart'
            },
            getMonthChart(month){
                this.resetChartData();
                for (var i in this.monthWBMark) {
                    this.monthChartMarks.push(this.monthWBMark[i][month]);
                }
                var labels = [];
                for (var subject of this.userClassSubjects) {
                    labels.push(subject.subject);
                }
                this.chartData.dataCollection.datasets[0].data = this.monthChartMarks;
                this.chartData.dataCollection.datasets[0].label = ' نمودار نمرات درس در ' + this.farsiMonth[month];
                this.chartData.dataCollection.labels = labels;
                this.backStatus = 'total-month-workbook';
                this.status = 'bar-chart'
            },
            getYearChart(index){
                var vm = this;
                vm.resetChartData();
                var data = [];
                var titles = ['مستمر اول', 'پایانی اول', 'نوبت اول', 'مستمر دوم', 'پایانی دوم', 'سالانه ']
                var labels = [];
                for (var i in vm.yearWBMark) {
                    data.push(vm.yearWBMark[i][index + 3]);
                    labels.push(vm.yearWBMark[i][1]);
                }
                console.log(vm.yearWBMark)

                this.chartData.dataCollection.datasets[0].data = data;
                this.chartData.dataCollection.datasets[0].label = ' نمودار نمرات ' + titles[index];
                this.chartData.dataCollection.labels = labels;
                this.backStatus = 'year-workbook'
                this.status = 'bar-chart'
            },
            getMinAvgMaxYearChart(index){
                var vm = this;
                vm.resetChartData();
                var data = [];
                var titles = ['کمترین نمره کلاس', 'میانگین نمرات کلاس', 'بیشترین نمره کلاس ']
                var colors = ['red', 'yellow', 'green']
                var labels = [];
                console.log(vm.yearMinAvgMaxRank)
                for (var subject of this.userClassSubjects) {
                    labels.push(subject.subject);
                }
                this.chartData.dataCollection.datasets = [];
                for (var i of [0, 1, 2]) {
                    var datasets = {
                        label: '',
                        fill: '',
                        borderColor: '',
                        backgroundColor: '#248eff',
                        borderWidth: 1,
                        data: []
                    };
                    data = vm.yearMinAvgMaxRank[i];
                    datasets.fill = false;
                    datasets.borderColor = colors[i];
                    datasets.backgroundColor = colors[i];
                    datasets.data = data;
                    datasets.label = titles[i];
                    this.chartData.dataCollection.datasets.push(datasets);
                }

                this.chartData.dataCollection.labels = labels;
                this.backStatus = 'year-workbook'
                this.status = 'line-chart'
            },
            resetChartData(){
                this.chartData = {
                    dataCollection: {
                        labels: [],
                        datasets: [{
                            label: 'نمودار نمرات در ماه',
                            backgroundColor: '#248eff',
                            hoverBackgroundColor: '#f87f99',
                            borderWidth: 1,
                            data: []
                        }],
                    },
                    options: {
                        legend: {
                            labels: {
                                fontColor: 'green',
                                boxWidth: 20,
                                fontFamily: "'iransans'",
                            }
                        },
                        tooltips: {
                            display: true,
                            titleFontColor: '#248eff',
                            bodyFontColor: '#ff591a',
                            titleFontFamily: "'iransans'",
                            bodyFontFamily: "'iransans'",
                        },
                        title: {
                            fontColor: 'purple',
                            fontFamily: "'iransans'",
                        },
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true,
                                    fontColor: 'red',
                                    fontFamily: "'iransans'",
                                    min: 0,
                                    max: 20,

                                },
                            }],
                            xAxes: [{
                                barThickness: 50,
                                ticks: {
                                    fontColor: 'green',
                                    fontFamily: "'iransans'",

                                },
                            }]
                        },
                        elements: {
                            rectangle: {
                                borderWidth: 10,
                            }
                        },
                        responsive: true
                    }
                }
            },
            getYearWb: function () {

                var vm = this;
                vm.waiting(true)
                function Avg(arr) {
                    var sum = 0;
                    arr = vm.cleanArray(arr);
                    for (var i = 0; i < arr.length; i++) {
                        sum += arr[i];
                    }
                    if (arr.length != 0) {
                        return Math.round((sum / arr.length) * 100) / 100;
                    }
                    return null;
                }

                var username = vm.student.username;
                axios.get('/api/get-year-workbook/' + username).then(function (response) {
                    Vue.set(vm.$data, 'userClassSubjects', response.data['subjects']);
                    vm.yearWBMark = [];
                    var mos1 = [], mos2 = [], pay1 = [], pay2 = [], nobat1 = [], year_avg = [];
                    for (var subject of vm.userClassSubjects) {
                        var filter = vm.filterYearMarks(subject.id, response.data['workbook']);
                        var row = []


                        if (filter.length > 0) {
                            row.push(subject.code);
                            row.push(subject.subject);
                            row.push(subject.vahed);
                            row.push(vm.round(filter[0].mos1));
                            row.push(vm.round(filter[0].pay1));
                            row.push(vm.round(filter[0].nobat1_mark));
                            row.push(vm.round(filter[0].mos2));
                            row.push(vm.round(filter[0].pay2));
                            row.push(vm.round(filter[0].year_mark));

                            mos1.push(vm.round(filter[0].mos1));
                            pay1.push(vm.round(filter[0].pay1));
                            nobat1.push(vm.round(filter[0].nobat1_mark));
                            mos2.push(vm.round(filter[0].mos2));
                            pay2.push(vm.round(filter[0].pay2));
                            year_avg.push(vm.round(filter[0].year_mark));
                        }
                        else {
                            row.push(subject.code);
                            row.push(subject.subject);
                            row.push(subject.vahed);
                            row.push(null);
                            row.push(null);
                            row.push(null);
                            row.push(null);
                            row.push(null);
                            row.push(null);
                        }

                        vm.yearWBMark.push(row);

                    }


                    vm.yearAvg.mos1 = Avg(mos1);
                    vm.yearAvg.mos2 = Avg(mos2);
                    vm.yearAvg.pay1 = Avg(pay1);
                    vm.yearAvg.pay2 = Avg(pay2);
                    vm.yearAvg.nobat1 = Avg(nobat1);
                    vm.status = 'year-workbook';

                    vm.waiting(false)

                });
                // count year min avg max rank
                axios.get('/api/get-year-avg-rates/' + vm.student.username).then(function (response) {
                    var data = response.data;
                    vm.yearAvg.min = Avg(data[0]);
                    vm.yearAvg.avg = Avg(data[1]);
                    vm.yearAvg.max = Avg(data[2]);
                    vm.yearMinAvgMaxRank = data;
                });
            },
            round(num){
                if (num != null) {
                    return Math.round(num * 100) / 100;
                }
                return null;
            },
            cleanArray(actual) {
                var newArray = new Array();
                for (var i = 0; i < actual.length; i++) {
                    if (actual[i]) {
                        newArray.push(actual[i]);
                    }
                }
                return newArray;
            },
            CumputMonthRate: function () {
                var vm = this;
                vm.status = 'marks';
                axios.get('/api/get-month-mark-students/' + vm.student.username).then(function (response) {
                    var data = response.data;
                    for (var month in data) {
                        var find = vm.filterBy(response.data[month][0], vm.student.cuy_id, 'cuy_id');
                        for (var i = 0; i < data[month][0].length; i++) {
                            if (data[month][0][i] == find[0]) {
                                vm.monthRate[month] = i + 1;
                            }
                        }
                    }

                });
            },
            CumputYearRate: function () {
                var vm = this;
                vm.status = 'marks';
                axios.get('/api/get-year-rate-students/' + vm.student.username).then(function (response) {
                    var data = response.data;
                    var sortable = Object.entries(data);
                    sortable.sort(function (a, b) {
                        return a[1] - b[1];
                    });
                    var counter = sortable.length - 1;
                    var sum = 0;
                    for (var element of sortable) {
                        counter = sortable.length - counter;
                        if (element[0] == vm.student.cuy_id) {
                            vm.yearAvg.year = element[1];
                            vm.yearAvg.rate = counter;
                        }
                        sum = sum + element[1];
                    }
                    if (sortable.length > 0) {
                        vm.yearAvg.year = vm.round(sum / sortable.length);
                    }
                });
            },
            SetDate: function (param) {
                var id = '#' + param;
                var vm = this;
                $(id).val('');
                $(id).pDatepicker({
                    format: 'YYYY/MM/DD',
                    onSelect: function (date) {
                        window.formatPersian = false;
                        /* global persianDate */
                        vm.addMark.date = persianDate(date).format('YYYY/MM/DD')
                    }
                }).persianDatepicker("show");

                $(".datepicker-plot-area .btn-prev").html("<i class='fa fa-chevron-right' aria-hidden='true'></i>");
                $(".datepicker-plot-area .btn-next").html("<i class='fa fa-chevron-left' aria-hidden='true'></i>");
            },
            ComputMonthAvg: function (marks) {
                var sum = 0;
                for (var mark of marks) {
                    sum += mark.avg;
                }
                var avg = '';
                if (marks.length != 0) {
                    avg = Math.round((sum / (marks.length)) * 100) / 100;
                    return avg;
                }
                return avg;
            },
            openDialog(ref) {
                this.$refs[ref].open();
            },
            closeDialog(ref) {
                this.$refs[ref].close();
            },
            sortOrder(object) {
                this.sortBy = object.name;
                this.sortType = object.type;
            },
            waiting(status) {
                this.$parent.loading = status;
            },

        },
    }

</script>
