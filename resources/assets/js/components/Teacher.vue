<template>
    <div>

        <!--LIST TEACHER-->
        <div v-if="status == 'list'">
            <div>
                <md-button class="md-primary md-raised" id="add_teacher_modal"
                           @click.native="AddTeacher() ">
                    افزودن
                </md-button>
                <a href="javascript:window.print()" class=" btn btn-success pull-left">چاپ</a>
            </div>
            <br><br>
            <div class="box col-xs-12">
                <md-table-card>
                    <md-toolbar>

                        <h1 class="md-title">لیست دبیران</h1>
                        <div class="form-group has-success has-feedback">
                            <input placeholder="جستجو..." type="text" v-model="searchBox" class="form-control input-sm">
                            <span class="fa fa-search form-control-feedback" aria-hidden="true"></span>
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
                                    <md-table-row v-for="(teach, teachIndex) in orderedTeachers" :key="teachIndex"
                                                  :md-item="teach">
                                        <md-table-cell>
                                            <div class="profile-cursor" @click="openDialog('profile');teacher = teach;">
                                                <md-avatar>
                                                    <img :src="'images/avatars/'+teach.photo" alt="Avatar">
                                                </md-avatar>
                                                {{ teach.fullname }}
                                                <md-tooltip md-direction="top"> اطلاعات {{ teach.fullname }}
                                                </md-tooltip>
                                            </div>

                                        </md-table-cell>
                                        <md-table-cell>{{ teach.username }}</md-table-cell>
                                        <md-table-cell>{{ teach.phone }}</md-table-cell>
                                        <md-table-cell>
                                            <md-theme md-name="blue">
                                                <md-button class="md-fab md-mini md-primary"
                                                           @click.native="EditTeacher(teach)">
                                                    <md-icon>edit</md-icon>
                                                    <md-tooltip md-direction="top">ویرایش اطلاعات {{ teach.fullname }}
                                                    </md-tooltip>
                                                </md-button>
                                            </md-theme>
                                            <md-theme md-name="red">
                                                <md-button class="md-fab md-mini  md-primary"
                                                           @click.native="DelTeacher(teach.id,teachIndex)">
                                                    <md-icon md-iconset="fa fa-trash"
                                                             class="md-size-2x md-primary"></md-icon>
                                                    <md-tooltip md-direction="top">حذف {{ teach.fullname }}</md-tooltip>
                                                </md-button>
                                            </md-theme>

                                        </md-table-cell>
                                    </md-table-row>
                                </md-table-body>
                            </md-table>
                        </table>

                        <div v-if="teachers.length < 1" class="panel panel-default empty-table-msg">
                            <div class="panel-body">
                                <p class="text-center"><span class="text-info"> هیچ دبیری ثبت نشده است.</span>
                                </p>
                            </div>
                        </div>

                    </div>

                </md-table-card>
            </div>

        </div>

        <!--ADD TEACHER-->
        <div v-show="status == 'add-teacher'">
            <div class="col-xs-12">
                <button @click="status = 'list'" class="btn btn-danger pull-left btn-sm  hvr-icon-back">بازگشت</button>
            </div>
            <br><br>
            <div class="col-xs-12">
                <md-card>
                    <md-card-header>
                        <div class="md-title">افزودن دبیر</div>
                    </md-card-header>
                    <md-card-content>
                        <div class="col-sm-2"></div>
                        <div class="col-sm-8">
                            <form @submit.prevent="StoreTeacher()" novalidate>
                                <div class="form-group" :class="errors.has('username')?'has-error':''">
                                    <label for="" class="control-label">* نام کاربری</label>
                                    <input
                                            name="username"
                                            type="text"
                                            placeholder="نام کاربری"
                                            class="form-control"
                                            @blur="CheckUsername()"
                                            v-model="addTeacher.username"
                                            v-validate.initial="'required'"
                                    >
                                    <div v-show="errors.has('username')" class="help-block">{{ errors.first('username')
                                        }}
                                    </div>
                                    <span v-show="teacherErrors.username == false && addTeacher.username != ''"
                                          class="help-block text-danger">نام کاربری قبلا ثبت شده است.</span>
                                </div>
                                <div class="form-group" :class="errors.has('email')?'has-error':''">
                                    <label for="" class="control-label">* ایمیل</label>
                                    <input
                                            name="email"
                                            type="email"
                                            placeholder="آدرس ایمیل"
                                            class="form-control"
                                            v-model="addTeacher.email"
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
                                            v-model="addTeacher.password"
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
                                            v-model="addTeacher.fname"
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
                                            v-model="addTeacher.lname"
                                            v-validate.initial="'required'"
                                    >
                                    <div v-show="errors.has('lname')" class="help-block">{{ errors.first('lname') }}
                                    </div>

                                </div>
                                <div class="form-group" :class="errors.has('clase')?'has-error':''">
                                    <label for="clase" class=" control-label">کلاس </label>

                                    <multiselect
                                            v-model="clase"
                                            :options="classes"
                                            :multiple="true"
                                            track-by="clase"
                                            :internal-search="false"
                                            data-vv-name="clase"
                                            label="clase"
                                            :hide-selected="true"
                                            noResult="کلاسی موجود نمی باشد."
                                            placeholder=""
                                            @input="getSubjects()"
                                            v-validate.initial="'required'"
                                            select-label="برای انتخاب کلیک کنید."
                                            deselect-label="برای حذف انتخاب کلیک کنید."
                                            selected-label="انتخاب شده."
                                    ></multiselect>


                                    <div v-show="errors.has('clase')" class="help-block">{{
                                        errors.first('clase') }}
                                    </div>
                                </div>
                                <div class="form-group" :class="errors.has('subject')?'has-error':''">
                                    <label for="subject" class=" control-label">درس </label>
                                    <multiselect
                                            v-model="subject"
                                            :options="subjects"
                                            :multiple="true"
                                            track-by="subject"
                                            :internal-search="false"
                                            data-vv-name="subject"
                                            label="subject"
                                            placeholder=""
                                            v-validate.initial="'required'"
                                            select-label="برای انتخاب کلیک کنید."
                                            deselect-label="برای حذف انتخاب کلیک کنید."
                                            selected-label="انتخاب شده."
                                            :hide-selected="true"
                                            noResult="درسی موجود نمی باشد."
                                    ></multiselect>

                                    <div v-show="errors.has('subject')" class="help-block">{{
                                        errors.first('subject') }}
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
                                            v-model="addTeacher.birthday"
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
                                            v-model="addTeacher.address"
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
                                            v-model="addTeacher.phone"
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
                                            v-model="addTeacher.mobile"
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

        <!--EDIT TEACHER-->
        <div v-show="status == 'edit-teacher'">
            <div class="col-xs-12">
                <button @click="status = 'list'" class="btn btn-danger pull-left btn-sm  hvr-icon-back">بازگشت</button>
            </div>
            <br><br>
            <div class="col-xs-12">
                <md-card>
                    <md-card-header>
                        <div class="md-title">ویرایش دبیر</div>
                    </md-card-header>
                    <md-card-content>
                        <div class="col-sm-2"></div>
                        <div class="col-sm-8">
                            <form @submit.prevent="UpdateTeacher()" novalidate>
                                <div class="form-group" :class="errors.has('edit_username')?'has-error':''">
                                    <label for="" class="control-label">* نام کاربری</label>
                                    <input
                                            name="edit_username"
                                            type="text"
                                            placeholder="نام کاربری"
                                            class="form-control"
                                            @blur="CheckUsername()"
                                            v-model="teacher.username"
                                            v-validate.initial="'required'"
                                    >
                                    <div v-show="errors.has('edit_username')" class="help-block">{{
                                        errors.first('edit_username') }}
                                    </div>

                                    <span v-show="teacherErrors.username == false && addTeacher.username != ''"
                                          class="help-block text-danger">نام کاربری قبلا ثبت شده است.</span>

                                </div>
                                <div class="form-group" :class="errors.has('edit_email')?'has-error':''">
                                    <label for="" class="control-label">* ایمیل</label>
                                    <input
                                            name="edit_email"
                                            type="email"
                                            placeholder="آدرس ایمیل"
                                            class="form-control"
                                            v-model="teacher.email"
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
                                            v-model="teacher.password"
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
                                            v-model="teacher.fname"
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
                                            v-model="teacher.lname"
                                            v-validate.initial="'required'"
                                    >
                                    <div v-show="errors.has('edit_lname')" class="help-block">{{
                                        errors.first('edit_lname') }}
                                    </div>

                                </div>
                                <div class="form-group" :class="errors.has('edit_clase')?'has-error':''">
                                    <label for="edit_clase" class=" control-label">کلاس </label>
                                    <multiselect
                                            v-model="clase"
                                            :options="classes"
                                            :multiple="true"
                                            track-by="clase"
                                            :internal-search="false"
                                            data-vv-name="edit_clase"
                                            label="clase"
                                            placeholder=""
                                            @input="getSubjects()"
                                            v-validate.initial="'required'"
                                            select-label="برای انتخاب کلیک کنید."
                                            deselect-label="برای حذف انتخاب کلیک کنید."
                                            selected-label="انتخاب شده."
                                            :hide-selected="true"
                                            noResult="کلاسی موجود نمی باشد."

                                    ></multiselect>


                                    <div v-show="errors.has('edit_clase')" class="help-block">{{
                                        errors.first('edit_clase') }}
                                    </div>
                                </div>
                                <div class="form-group" :class="errors.has('edit_subject')?'has-error':''">
                                    <label for="edit_subject" class=" control-label">درس </label>
                                    <multiselect
                                            v-model="subject"
                                            :options="subjects"
                                            :multiple="true"
                                            track-by="subject"
                                            :internal-search="false"
                                            data-vv-name="edit_subject"
                                            label="subject"
                                            placeholder=""
                                            v-validate.initial="'required'"
                                            select-label="برای انتخاب کلیک کنید."
                                            deselect-label="برای حذف انتخاب کلیک کنید."
                                            selected-label="انتخاب شده."
                                            :hide-selected="true"
                                            noResult="درسی موجود نمی باشد."

                                    ></multiselect>

                                    <div v-show="errors.has('edit_subject')" class="help-block">{{
                                        errors.first('edit_subject') }}
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
                                            v-model="teacher.birthday"
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
                                            v-model="teacher.address"
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
                                            v-model="teacher.phone"
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
                                            v-model="teacher.mobile"
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

        <!--PROFILE-->
        <md-dialog md-open-from="#profile" md-close-to="#profile" ref="profile">
            <md-dialog-title> اطلاعات <span v-text="teacher.fullname"></span></md-dialog-title>
            <md-dialog-content>
                <div class="text-center">
                    <md-avatar class="md-large">
                        <img :src="'images/avatars/'+teacher.photo" alt="Avatar">
                    </md-avatar>
                </div>
                <table class="table table-responsive table-bordered">
                    <tbody>
                    <tr>
                        <td>نام کاربری</td>
                        <td v-text="teacher.username"></td>
                    </tr>
                    <tr>
                        <td>آدرس ایمیل</td>
                        <td v-text="teacher.email"></td>
                    </tr>
                    <tr>
                        <td>نام</td>
                        <td v-text="teacher.fname"></td>
                    </tr>
                    <tr>
                        <td>نام خانوادگی</td>
                        <td v-text="teacher.lname"></td>
                    </tr>
                    <tr>
                        <td>تاریخ تولد</td>
                        <td v-text="teacher.birthday"></td>
                    </tr>
                    <tr>
                        <td>آدرس</td>
                        <td v-text="teacher.address"></td>
                    </tr>
                    <tr>
                        <td>شماره تلفن</td>
                        <td v-text="teacher.phone"></td>
                    </tr>
                    <tr>
                        <td>شماره همراه</td>
                        <td v-text="teacher.mobile"></td>
                    </tr>
                    </tbody>
                </table>
            </md-dialog-content>
            <md-dialog-actions>
                <md-button class="md-primary md-raised " @click.native="closeDialog('profile')">بستن</md-button>
            </md-dialog-actions>

        </md-dialog>

    </div>
</template>

<script>
    import Multiselect from 'vue-multiselect'

    export default {
        components: {Multiselect},
        data(){
            return {
                teacher: {},
                teachers: [],
                classes: [],
                clase: [],
                subjects: [],
                subject: [],
                teacherErrors: {
                    username: null,
                },
                addTeacher: {
                    'username': '',
                    'email': '',
                    'password': '',
                    'fname': '',
                    'lname': '',
                    'clase': '',
                    'subject': '',
                    'birthday': '',
                    'address': '',
                    'phone': '',
                    'mobile': '',
                    'photo': ''
                },
                searchBox: '',
                sortType: 'asc',
                sortBy: 'lname',
                status: 'load',
            }
        },
        created: function () {

            this.getTeachers();
            this.getClase()
        },
        computed: {
            orderedTeachers: function () {
                var vm = this;
                return _.filter(_.orderBy(this.teachers, this.sortBy, this.sortType), function (teacher) {
                    return teacher.fullname.includes(vm.searchBox) || teacher.username.includes(vm.searchBox);
                });
            },
        },
        methods: {
            getTeachers: function () {
                var vm = this;
                vm.waiting(true);
                axios.get('/api/get-teachers').then(function (response) {
                    Vue.set(vm.$data, 'teachers', response.data);
                    vm.status = 'list';
                    vm.waiting(false);
                });
            },
            getClase() {
                var vm = this;
                axios.get('/api/get-clases').then(function (response) {
                    Vue.set(vm.$data, 'classes', response.data);
                });
            },
            getSubjects() {
                var vm = this;
                var field_ids = [];
                vm.subjects = [];
                for (var i in vm.clase) {
                    field_ids.push(vm.clase[i].field_id);
                }
                field_ids = field_ids.join(',');
                console.log(field_ids)
                axios.get('/api/online-exams/get-subjects/' + field_ids).then(function (response) {
                    Vue.set(vm.$data, 'subjects', response.data);
                });
            },
            ProfileView: function (teacher) {
                Vue.set(this.$data, 'teacher', teacher);
            },
            StoreTeacher() {
                var vm = this;
                vm.addTeacher.clase = [];
                vm.addTeacher.subject = [];
                for (var i in vm.clase) {
                    vm.addTeacher.clase.push(vm.clase[i].id);
                }
                for (var i in vm.subject) {
                    vm.addTeacher.subject.push(vm.subject[i].id);
                }

                var datas = new FormData();
                datas.append('username', vm.addTeacher.username);
                datas.append('email', vm.addTeacher.email);
                datas.append('password', vm.addTeacher.password);
                datas.append('fname', vm.addTeacher.fname);
                datas.append('lname', vm.addTeacher.lname);
                datas.append('clase', vm.addTeacher.clase.join(','));
                datas.append('subject', vm.addTeacher.subject.join(','));
                datas.append('birthday', vm.addTeacher.birthday);
                datas.append('address', vm.addTeacher.address);
                datas.append('phone', vm.addTeacher.phone);
                datas.append('mobile', vm.addTeacher.mobile);


                datas.append('photo', document.getElementById('add_photo').files[0]);

                var config = {
                    onUploadProgress: function (progressEvent) {
                        var percentCompleted = Math.round((progressEvent.loaded * 100) / progressEvent.total);
                    }
                };

                this.$validator.validateAll({
                    username: vm.addTeacher.username,
                    email: vm.addTeacher.email,
                    password: vm.addTeacher.password,
                    fname: vm.addTeacher.fname,
                    lname: vm.addTeacher.lname,
                    clase: vm.addTeacher.clase,
                    subject: vm.addTeacher.subject,
                    birthday: vm.addTeacher.birthday,
                    address: vm.addTeacher.address,
                    phone: vm.addTeacher.phone,
                    mobile: vm.addTeacher.mobile,
//                    photo:vm.addTeacher.photo
                }).then(function (validate) {
                    if (validate) {
                        vm.waiting(true)

                        axios.post('/api/store-teacher', datas, config).then(function (response) {
                            vm.addTeacher = {
                                'username': '',
                                'email': '',
                                'password': '',
                                'fname': '',
                                'lname': '',
                                'birthday': '',
                                'address': '',
                                'phone': '',
                                'mobile': '',
                                'photo': '',
                            };
                            if (response.status == 200) {
                                vm.teachers.push(response.data)
                                Vue.nextTick(() => {
                                    vm.errors.clear()
                                })
                                vm.ValidateMsg();
                                noty({
                                    type: 'success',
                                    layout: 'topLeft',
                                    text: 'دبیر با موفقیت ذخیره شد.',
                                    timeout: 3000,
                                });
                                document.getElementById('noty_audio').play();
                            }
                            vm.waiting(false)
                        });
                    }

                }).catch(function () {

                });

            },
            EditTeacher: function (teacher) {
                var vm = this;
                vm.waiting(true)
                Vue.set(this.$data, 'teacher', teacher);
                Vue.nextTick(() => {
                    this.errors.clear()
                })
                this.ValidateMsg();
                this.getClase()
                axios.post('/api/teacher/get-selectclass', {'cuy_id': vm.teacher.cuy_id}).then(function (response) {
                    Vue.set(vm.$data, 'clase', response.data);
                    var field_ids = [];
                    vm.subjects = [];


                    for (var i in vm.clase) {
                        field_ids.push(vm.clase[i].field_id);
                    }
                    field_ids = field_ids.join(',')
                    axios.get('/api/online-exams/get-subjects/' + field_ids).then(function (response) {
                        Vue.set(vm.$data, 'subjects', response.data);
                        axios.post('/api/teacher/get-selectsubject', {'cuy_id': vm.teacher.cuy_id}).then(function (response) {
                            Vue.set(vm.$data, 'subject', response.data);
                            vm.status = 'edit-teacher';
                            vm.waiting(false)
                        });
                    });

                });

            },
            UpdateTeacher: function () {
                var vm = this;
                vm.teacher.clase = [];
                vm.teacher.subject = [];

                for (var i in vm.clase) {
                    vm.teacher.clase.push(vm.clase[i].id);
                }
                for (var i in vm.subject) {
                    vm.teacher.subject.push(vm.subject[i].id);
                }

                var datas = new FormData();
                datas.append('id', vm.teacher.id);
                datas.append('username', vm.teacher.username);
                datas.append('email', vm.teacher.email);
                datas.append('password', vm.teacher.password);
                datas.append('fname', vm.teacher.fname);
                datas.append('lname', vm.teacher.lname);
                datas.append('clase', vm.teacher.clase.join(','));
                datas.append('subject', vm.teacher.subject.join(','));
                datas.append('birthday', vm.teacher.birthday);
                datas.append('address', vm.teacher.address);
                datas.append('phone', vm.teacher.phone);
                datas.append('mobile', vm.teacher.mobile);


                datas.append('photo', document.getElementById('edit_photo').files[0]);

                var config = {
                    onUploadProgress: function (progressEvent) {
                        var percentCompleted = Math.round((progressEvent.loaded * 100) / progressEvent.total);
                    }
                };

                this.$validator.validateAll({
                    edit_username: vm.teacher.username,
                    edit_email: vm.teacher.email,
                    edit_password: vm.teacher.password,
                    edit_fname: vm.teacher.fname,
                    edit_lname: vm.teacher.lname,
                    edit_clase: vm.teacher.clase,
                    edit_subject: vm.teacher.subject,
                    edit_birthday: vm.teacher.birthday,
                    edit_address: vm.teacher.address,
                    edit_phone: vm.teacher.phone,
                    edit_mobile: vm.teacher.mobile,
//                    edit_photo:vm.teacher.photo
                }).then(function (validate) {
                    if (validate) {
                        vm.waiting(true)

                        axios.post('/api/update-teacher', datas, config).then(function (response) {
                            if (response.status == 200) {
                                noty({
                                    type: 'success',
                                    layout: 'topLeft',
                                    text: 'اطلاعات دبیر با موفقیت بروز شد.',
                                    timeout: 3000,
                                });
                                document.getElementById('noty_audio').play();
                            }
                            vm.waiting(false)
                        });
                    }

                }).catch(function () {

                });
            },
            CheckUsername(){
                var vm = this;
                if (vm.addTeacher.username != '') {
                    axios.get('/api/students/check-username/' + vm.addTeacher.username).then(function (response) {
                        if (response.status == 200) {
                            if (response.data == true) {
                                vm.teacherErrors.username = true;
                            }
                            if (response.data == false) {
                                vm.teacherErrors.username = false;
                            }
                        }
                    });

                } else {
                    vm.teacherErrors.username = null;
                }
            },
            DelTeacher: function (teacher_id, index) {
                var vm = this;
                swal({
                    title: 'حذف دبیر',
                    text: 'از حذف دبیر مطمئن هستید؟',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'بله',
                    cancelButtonText: 'خیر'
                }).then(function () {
                    vm.waiting(true)
                    axios.post('/api/delete-teacher', {teacher_id: teacher_id}).then(function (response) {
                        if (response.status == 200) {
                            Vue.delete(vm.teachers, index);
                            noty({
                                type: 'success',
                                layout: 'topLeft',
                                text: 'دبیر با موفقیت حذف شد.',
                                timeout: 3000,
                            });
                            document.getElementById('noty_audio').play();
                            vm.teachers.delete
                        }
                        vm.waiting(false)
                    });
                })
            },
            ValidateMsg(){
                this.$validator.updateDictionary({
                    'fa': {
                        custom: {
                            cmark: {
                                required: 'کادر نمره نباید خالی باشد.',
                                decimal: 'نمره باید به صورت عدد باشد.',
                                max_value: 'نمره بیشتر از 20 هست.',
                                min_value: 'نمره کمتر از 0 هست.',
                            },
                            csubject: {
                                required: 'درس باید انتخاب شود.',
                                numeric: 'درس معتبر نمی باشد.'
                            },
                            cdate: {
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
                            pmark: {
                                required: 'کادر نمره نباید خالی باشد.',
                                decimal: 'نمره باید به صورت عدد باشد.',
                                max_value: 'نمره بیشتر از 20 هست.',
                                min_value: 'نمره کمتر از 0 هست.',
                            },
                            pstatus: {
                                required: 'نوع نمره را انتخاب کنید.',
                            },
                            psubject: {
                                required: 'درس باید انتخاب شود.',
                                numeric: 'درس معتبر نمی باشد.'
                            },
                            pdate: {
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
                                required: 'نام دبیر نباید خالی باشد.',
                            },
                            lname: {
                                required: 'نام خانوادگی دبیر نباید خالی باشد.',
                            },
                            clase: {
                                required: 'کلاس باید انتخاب شود.',
                            },
                            subject: {
                                required: 'درس باید انتخاب شود.',
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
                                required: 'نام دبیر نباید خالی باشد.',
                            },
                            edit_lname: {
                                required: 'نام خانوادگی دبیر نباید خالی باشد.',
                            },
                            edit_clase: {
                                required: 'کلاس باید انتخاب شود.',
                            },
                            edit_subject: {
                                required: 'درس باید انتخاب شود.',
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
            AddTeacher(){
                Vue.nextTick(() => {
                    this.errors.clear()
                })
                this.ValidateMsg();
                this.status = 'add-teacher';
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
