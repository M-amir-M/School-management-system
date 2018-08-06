<template>
    <div>

        <!--LIST PARENT-->
        <div v-if="status == 'list'">
            <div class="box col-xs-12">
                <md-button class="md-primary md-raised" id="add_parent_modal"
                           @click.native="AddParent() ">
                    افزودن
                </md-button>
                <a href="javascript:window.print()" class=" btn btn-success pull-left">چاپ</a>
            </div>
            <br><br>
            <div class="box col-xs-12">
                <md-table-card>
                    <md-toolbar>

                        <h1 class="md-title">لیست اولیا</h1>
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
                                    <md-table-row v-for="(par, parIndex) in orderedParents" :key="parIndex"
                                                  :md-item="par">
                                        <md-table-cell>
                                            <div class="profile-cursor" @click="openDialog('profile');parent = par;">
                                                <md-avatar>
                                                    <img :src="'images/avatars/'+par.photo" alt="Avatar">
                                                </md-avatar>
                                                {{ par.fullname }}
                                                <md-tooltip md-direction="top"> اطلاعات {{ par.fullname }}
                                                </md-tooltip>
                                            </div>

                                        </md-table-cell>
                                        <md-table-cell>{{ par.username }}</md-table-cell>
                                        <md-table-cell>{{ par.phone }}</md-table-cell>
                                        <md-table-cell>
                                            <md-theme md-name="blue">
                                                <md-button class="md-fab md-mini md-primary"
                                                           @click.native="EditParent(par)">
                                                    <md-icon>edit</md-icon>
                                                    <md-tooltip md-direction="top">ویرایش اطلاعات {{ par.fullname }}
                                                    </md-tooltip>
                                                </md-button>
                                            </md-theme>
                                            <md-theme md-name="red">
                                                <md-button class="md-fab md-mini  md-primary"
                                                           @click.native="DelParent(par.id,parIndex)">
                                                    <md-icon md-iconset="fa fa-trash"
                                                             class="md-size-2x md-primary"></md-icon>
                                                    <md-tooltip md-direction="top">حذف {{ par.fullname }}</md-tooltip>
                                                </md-button>
                                            </md-theme>

                                        </md-table-cell>
                                    </md-table-row>
                                </md-table-body>
                            </md-table>
                        </table>

                        <div v-if="parents.length < 1" class="panel panel-default empty-table-msg">
                            <div class="panel-body">
                                <p class="text-center"><span class="text-info"> هیچ ولی ثبت نشده است.</span>
                                </p>
                            </div>
                        </div>

                    </div>

                </md-table-card>
            </div>

        </div>

        <!--ADD PARENT-->
        <div v-show="status == 'add-parent'">
            <div class="col-xs-12">
                <button @click="status = 'list'" class="btn btn-danger pull-left btn-sm  hvr-icon-back">بازگشت</button>
            </div>
            <br><br>
            <div class="col-xs-12">
                <md-card>
                    <md-card-header>
                        <div class="md-title">افزودن والد</div>
                    </md-card-header>
                    <md-card-content>
                        <div class="col-sm-2"></div>
                        <div class="col-sm-8">
                            <form @submit.prevent="StoreParent()" novalidate>
                                <div class="form-group" :class="errors.has('username')?'has-error':''">
                                    <label for="">* نام کاربری</label>
                                    <input
                                            name="username"
                                            type="text"
                                            placeholder="نام کاربری"
                                            class="form-control"
                                            @blur="CheckUsername()"
                                            v-model="addParent.username"
                                            v-validate.initial="'required'"
                                    >
                                    <div v-show="errors.has('username')" class="help-block">{{ errors.first('username')
                                        }}
                                    </div>
                                    <span v-show="parentErrors.username == false && addParent.username != ''"
                                          class="help-block text-danger">نام کاربری قبلا ثبت شده است.</span>


                                </div>
                                <div class="form-group" :class="errors.has('email')?'has-error':''">
                                    <label for="email">* ایمیل</label>

                                    <input
                                            name="email"
                                            type="email"
                                            placeholder="آدرس ایمیل"
                                            class="form-control"
                                            v-model="addParent.email"
                                            v-validate.initial="'required|email'"
                                    >
                                    <div v-show="errors.has('email')" class="help-block">{{ errors.first('email') }}
                                    </div>

                                </div>
                                <div class="form-group" :class="errors.has('password')?'has-error':''">
                                    <label for="">* رمز عبور</label>
                                    <input
                                            name="password"
                                            type="password"
                                            placeholder="رمز عبور"
                                            class="form-control"
                                            v-model="addParent.password"
                                            v-validate.initial="'required|min:6'"
                                    >
                                    <div v-show="errors.has('password')" class="help-block">{{ errors.first('password')
                                        }}
                                    </div>

                                </div>
                                <div class="form-group" :class="errors.has('fname')?'has-error':''">
                                    <label for="">* نام </label>

                                    <input
                                            name="fname"
                                            type="text"
                                            placeholder="نام"
                                            class="form-control"
                                            v-model="addParent.fname"
                                            v-validate.initial="'required'"
                                    >
                                    <div v-show="errors.has('fname')" class="help-block">{{ errors.first('fname') }}
                                    </div>

                                </div>
                                <div class="form-group" :class="errors.has('lname')?'has-error':''">
                                    <label for="">* نام خانوادگی</label>

                                    <input
                                            name="lname"
                                            type="text"
                                            placeholder="نام خانوادگی"
                                            class="form-control"
                                            v-model="addParent.lname"
                                            v-validate.initial="'required'"
                                    >
                                    <div v-show="errors.has('lname')" class="help-block">{{ errors.first('lname') }}
                                    </div>

                                </div>
                                <div class="form-group" :class="errors.has('add_gender')?'has-error':''">
                                    <label for="add_gender" class=" ">* رابطه </label>
                                    <div class="">
                                        <md-radio v-validate.initial="'required|in:1,2'" v-model="addParent.gender"
                                                  id="gender_father" data-vv-name="add_gender" md-value="1">پدر
                                        </md-radio>
                                        <md-radio v-model="addParent.gender" id="gender_mother"
                                                  data-vv-name="add_gender"
                                                  md-value="2">مادر
                                        </md-radio>

                                        <div v-show="errors.has('add_gender')" class="help-block">{{
                                            errors.first('add_gender') }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group" :class="errors.has('add_student')?'has-error':''">
                                    <label for="">* فرزند</label>

                                    <button
                                            type="button"
                                            class="btn btn-info btn-block"
                                            @click="openDialog('link_student')"
                                    >فرزند
                                    </button>
                                    <input type="hidden"
                                           v-validate.initial="'required'"
                                           name="add_student"
                                           v-model="addParent.student"
                                    >
                                    <span v-for="stu in student">
                                        <md-avatar>
                                                    <img :src="'images/avatars/'+stu.photo" alt="Avatar">

                                        <md-tooltip
                                                md-direction="bottom"> {{ stu.fullname }}</md-tooltip>

                                                </md-avatar>
                                    </span>
                                    <div v-show="errors.has('add_student')" class="help-block">{{
                                        errors.first('add_student') }}
                                    </div>
                                </div>
                                <div class="form-group" :class="errors.has('birthday')?'has-error':''">
                                    <label for=""> تاریخ تولد</label>

                                    <input
                                            name="birthday"
                                            v-mask="'####/##/##'"
                                            type="text"
                                            placeholder="تاریخ تولد"
                                            class="form-control"
                                            v-model="addParent.birthday"
                                            v-validate.initial="'date_format:YYYY/MM/DD'"
                                    >
                                    <div v-show="errors.has('birthday')" class="help-block">{{ errors.first('birthday')
                                        }}
                                    </div>
                                </div>
                                <div class="form-group" :class="errors.has('address')?'has-error':''">
                                    <label for="">آدرس</label>

                                    <textarea
                                            name="address"
                                            type="text"
                                            placeholder="آدرس"
                                            class="form-control"
                                            v-model="addParent.address"
                                            v-validate.initial="'max:255'"
                                    ></textarea>
                                    <div v-show="errors.has('address')" class="help-block">{{ errors.first('address')
                                        }}
                                    </div>
                                </div>
                                <div class="form-group" :class="errors.has('phone')?'has-error':''">
                                    <label for="">تلفن</label>

                                    <input
                                            name="phone"
                                            type="text"
                                            placeholder="شماره تلفن"
                                            class="form-control"
                                            v-model="addParent.phone"
                                            v-validate.initial="'numeric|max:15'"
                                    >
                                    <div v-show="errors.has('phone')" class="help-block">{{ errors.first('phone') }}
                                    </div>
                                </div>
                                <div class="form-group" :class="errors.has('mobile')?'has-error':''">
                                    <label for="">همراه</label>

                                    <input
                                            name="mobile"
                                            v-mask="'09#########'"
                                            type="text"
                                            placeholder=" شماره همراه 09123456789"
                                            class="form-control"
                                            v-model="addParent.mobile"
                                            v-validate.initial="'min:11'"
                                    >
                                    <div v-show="errors.has('mobile')" class="help-block">{{ errors.first('mobile') }}
                                    </div>
                                </div>
                                <div class="form-group" :class="errors.has('add_photo')?'has-error':''">
                                    <label for="">عکس</label>

                                    <input
                                            name="add_photo"
                                            type="file"
                                            class="form-control"
                                            id="add_photo"
                                            v-validate.initial="'mimes:image/jpeg,image/png'"
                                    >
                                    <div v-show="errors.has('add_photo')" class="help-block">{{
                                        errors.first('add_photo') }}
                                    </div>
                                </div>


                                <input type="submit" class="btn btn-success pull-left" value="ذخیره">

                            </form>
                        </div>

                    </md-card-content>

                </md-card>
            </div>
        </div>

        <!--EDIT PARENT-->
        <div v-show="status == 'edit-parent'">
            <div class="col-xs-12">
                <button @click="status = 'list'" class="btn btn-danger pull-left btn-sm  hvr-icon-back">بازگشت</button>
            </div>
            <br><br>
            <div class="col-xs-12">
                <md-card>
                    <md-card-header>
                        <div class="md-title">ویرایش والد</div>
                    </md-card-header>
                    <md-card-content>
                        <div class="col-sm-2"></div>
                        <div class="col-sm-8">
                            <form @submit.prevent="UpdateParent()" novalidate>
                                <div class="form-group" :class="errors.has('edit_username')?'has-error':''">
                                    <label for="">* نام کاربری</label>

                                    <input
                                            name="edit_username"
                                            type="text"
                                            placeholder="نام کاربری"
                                            class="form-control"
                                            @blur="CheckUsername()"
                                            v-model="parent.username"
                                            v-validate.initial="'required'"
                                    >
                                    <div v-show="errors.has('edit_username')" class="help-block">{{
                                        errors.first('edit_username') }}
                                    </div>
                                    <span v-show="parentErrors.username == false && addParent.username != ''"
                                          class="help-block text-danger">نام کاربری قبلا ثبت شده است.</span>


                                </div>
                                <div class="form-group" :class="errors.has('edit_email')?'has-error':''">
                                    <label for="email">* ایمیل</label>

                                    <input
                                            name="edit_email"
                                            type="email"
                                            placeholder="آدرس ایمیل"
                                            class="form-control"
                                            v-model="parent.email"
                                            v-validate.initial="'required|email'"
                                    >
                                    <div v-show="errors.has('email')" class="help-block">{{ errors.first('edit_email')
                                        }}
                                    </div>

                                </div>
                                <div class="form-group" :class="errors.has('edit_password')?'has-error':''">
                                    <label for="">* رمز عبور</label>

                                    <input
                                            name="edit_password"
                                            type="password"
                                            placeholder="رمز عبور"
                                            class="form-control"
                                            v-model="parent.password"
                                            v-validate.initial="'required|min:6'"
                                    >
                                    <div v-show="errors.has('edit_password')" class="help-block">{{
                                        errors.first('edit_password') }}
                                    </div>

                                </div>
                                <div class="form-group" :class="errors.has('edit_fname')?'has-error':''">
                                    <label for="">* نام </label>

                                    <input
                                            name="edit_fname"
                                            type="text"
                                            placeholder="نام"
                                            class="form-control"
                                            v-model="parent.fname"
                                            v-validate.initial="'required'"
                                    >
                                    <div v-show="errors.has('edit_fname')" class="help-block">{{
                                        errors.first('edit_fname') }}
                                    </div>

                                </div>
                                <div class="form-group" :class="errors.has('edit_lname')?'has-error':''">
                                    <label for="">* نام خانوادگی</label>

                                    <input
                                            name="edit_lname"
                                            type="text"
                                            placeholder="نام خانوادگی"
                                            class="form-control"
                                            v-model="parent.lname"
                                            v-validate.initial="'required'"
                                    >
                                    <div v-show="errors.has('edit_lname')" class="help-block">{{
                                        errors.first('edit_lname') }}
                                    </div>

                                </div>
                                <div class="form-group" :class="errors.has('edit_gender')?'has-error':''">
                                    <label for="edit_gender" class=" ">* رابطه </label>
                                    <div class="">
                                        <md-radio v-validate.initial="'required|in:1,2'" v-model="addParent.gender"
                                                  id="edit_gender_father" data-vv-name="edit_gender" md-value="1">پدر
                                        </md-radio>
                                        <md-radio v-model="addParent.gender" id="edit_gender_mother"
                                                  data-vv-name="edit_gender"
                                                  md-value="2">مادر
                                        </md-radio>

                                        <div v-show="errors.has('edit_gender')" class="help-block">{{
                                            errors.first('edit_gender') }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group" :class="errors.has('edit_student')?'has-error':''">
                                    <label for="">* فرزند</label>

                                    <button
                                            type="button"
                                            class="btn btn-info btn-block"
                                            @click="openDialog('link_student')"
                                    >فرزند
                                    </button>
                                    <input type="hidden"
                                           v-validate.initial="'required'"
                                           name="edit_student"
                                           v-model="addParent.student"
                                    >
                                    <span v-for="stu in student">
                                        <md-avatar>
                                                    <img :src="'images/avatars/'+stu.photo" alt="Avatar">
                                            <md-tooltip
                                                    md-direction="bottom"> {{ stu.fullname }}</md-tooltip>
                                                </md-avatar>
                                    </span>
                                    <div v-show="errors.has('edit_student')" class="help-block">{{
                                        errors.first('edit_student') }}
                                    </div>
                                </div>
                                <div class="form-group" :class="errors.has('edit_birthday')?'has-error':''">
                                    <label for=""> تاریخ تولد</label>

                                    <input
                                            name="edit_birthday"
                                            v-mask="'####/##/##'"
                                            type="text"
                                            placeholder="تاریخ تولد"
                                            class="form-control"
                                            v-model="parent.birthday"
                                            v-validate.initial="'date_format:YYYY/MM/DD'"
                                    >
                                    <div v-show="errors.has('edit_birthday')" class="help-block">{{
                                        errors.first('edit_birthday') }}
                                    </div>
                                </div>
                                <div class="form-group" :class="errors.has('edit_address')?'has-error':''">
                                    <label for="">آدرس</label>

                                    <textarea
                                            name="edit_address"
                                            type="text"
                                            placeholder="آدرس"
                                            class="form-control"
                                            v-model="parent.address"
                                            v-validate.initial="'max:255'"
                                    ></textarea>
                                    <div v-show="errors.has('edit_address')" class="help-block">{{
                                        errors.first('edit_address') }}
                                    </div>
                                </div>
                                <div class="form-group" :class="errors.has('edit_phone')?'has-error':''">
                                    <label for="">تلفن</label>

                                    <input
                                            name="edit_phone"
                                            type="text"
                                            placeholder="شماره تلفن"
                                            class="form-control"
                                            v-model="parent.phone"
                                            v-validate.initial="'numeric|max:15'"
                                    >
                                    <div v-show="errors.has('edit_phone')" class="help-block">{{
                                        errors.first('edit_phone') }}
                                    </div>
                                </div>
                                <div class="form-group" :class="errors.has('edit_mobile')?'has-error':''">
                                    <label for="">همراه</label>

                                    <input
                                            name="edit_mobile"
                                            v-mask="'09#########'"
                                            type="text"
                                            placeholder=" شماره همراه 09123456789"
                                            class="form-control"
                                            v-model="parent.mobile"
                                            v-validate.initial="'min:11'"
                                    >
                                    <div v-show="errors.has('edit_mobile')" class="help-block">{{
                                        errors.first('edit_mobile') }}
                                    </div>
                                </div>
                                <div class="form-group" :class="errors.has('edit_photo')?'has-error':''">
                                    <label for="">عکس</label>

                                    <input
                                            name="edit_photo"
                                            id="edit_photo"
                                            type="file"
                                            class="form-control"
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
            <md-dialog-title> اطلاعات <span v-text="parent.fullname"></span></md-dialog-title>
            <md-dialog-content>
                <div class="text-center">
                    <md-avatar class="md-large">
                        <img :src="'images/avatars/'+parent.photo" alt="Avatar">
                    </md-avatar>
                </div>
                <table class="table table-responsive table-bordered">
                    <tbody>
                    <tr>
                        <td>نام کاربری</td>
                        <td v-text="parent.username"></td>
                    </tr>
                    <tr>
                        <td>آدرس ایمیل</td>
                        <td v-text="parent.email"></td>
                    </tr>
                    <tr>
                        <td>نام</td>
                        <td v-text="parent.fname"></td>
                    </tr>
                    <tr>
                        <td>نام خانوادگی</td>
                        <td v-text="parent.lname"></td>
                    </tr>
                    <tr>
                        <td>تاریخ تولد</td>
                        <td v-text="parent.birthday"></td>
                    </tr>
                    <tr>
                        <td>آدرس</td>
                        <td v-text="parent.address"></td>
                    </tr>
                    <tr>
                        <td>شماره تلفن</td>
                        <td v-text="parent.phone"></td>
                    </tr>
                    <tr>
                        <td>شماره همراه</td>
                        <td v-text="parent.mobile"></td>
                    </tr>
                    </tbody>
                </table>
            </md-dialog-content>
            <md-dialog-actions>
                <md-button class="md-primary md-raised " @click.native="closeDialog('profile')">بستن</md-button>
            </md-dialog-actions>

        </md-dialog>

        <!--LINK STUDENT-->
        <md-dialog md-open-from="#link_student" md-close-to="#link_student" ref="link_student">
            <md-dialog-title>ایجاد ارتباط والد و فرزند</md-dialog-title>
            <md-dialog-content>
                <hr>
                <div class="row col-xs-12">
                    <div class="col-sm-9">
                        <input v-model="searchToLink" type="text" class="form-control"
                               placeholder="اسم/نام کاربری/ایمیل">
                    </div>
                    <div class="col-sm-2">
                        <button @click="linkStudentButton()" class="btn btn-info">جستجو</button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12" style="padding-top:10px;">
                        <div class="box-body table-responsive">
                            <table class="table table-bordered">
                                <tbody>
                                <tr v-for="(student,index) in students">

                                    <td>
                                        <md-avatar>
                                            <img :src="'images/avatars/'+student.photo" alt="Avatar">
                                        </md-avatar>
                                        {{ student.fullname }}
                                    </td>
                                    <td class="no-print">
                                        <button @click="linkStudentSelect(student,index)"
                                                class="btn btn-success">انتخاب
                                        </button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </md-dialog-content>
            <md-dialog-actions>
                <md-button class="md-primary md-raised " @click.native="closeDialog('link_student')">بستن</md-button>
            </md-dialog-actions>

        </md-dialog>

    </div>
</template>

<script>
    export default {
        data(){
            return {
                parent: {},
                parents: [],
                students: [],
                student: [],
                parentErrors: {
                    username: null,
                },
                addParent: {
                    'username': '',
                    'email': '',
                    'password': '',
                    'fname': '',
                    'lname': '',
                    'gender': '',
                    'birthday': null,
                    'address': null,
                    'phone': null,
                    'mobile': null,
                    'photo': '',
                    'student': [],
                },
                searchBox: '',
                searchToLink: '',
                sortType: 'asc',
                sortBy: 'lname',
                status: 'load',
            }
        },
        created: function () {
            this.getParents();
        },
        computed: {
            orderedParents: function () {
                var vm = this;
                return _.filter(_.orderBy(this.parents, this.sortBy, this.sortType), function (parent) {
                    return parent.fullname.includes(vm.searchBox) || parent.username.includes(vm.searchBox);
                });
            },
        },
        methods: {
            getParents: function () {
                var vm = this;
                vm.waiting(true)
                axios.get('/api/get-parents').then(function (response) {
                    Vue.set(vm.$data, 'parents', response.data);
                    vm.status = 'list';
                    vm.waiting(false)
                });
            },
            ProfileView: function (parent) {
                Vue.set(this.$data, 'parent', parent);
            },
            linkStudentButton(){
                var vm = this;
                vm.waiting(true)
                axios.get('/api/parent/link-to-parent/' + vm.searchToLink).then(function (response) {
                    if (response.status == 200) {
                        Vue.set(vm.$data, 'students', response.data);
                        vm.student = [];
                        vm.addParent.student = [];
                    }
                    vm.waiting(false)
                });

            },
            linkStudentSelect(student, index){
                this.student.push(student);
                this.addParent.student.push(student.id);
                Vue.delete(this.students, index);
            },
            CheckUsername(){
                var vm = this;
                if (vm.addParent.username != '') {
                    axios.get('/api/students/check-username/' + vm.addParent.username).then(function (response) {
                        if (response.status == 200) {
                            if (response.data == true) {
                                vm.parentErrors.username = true;
                            }
                            if (response.data == false) {
                                vm.parentErrors.username = false;
                            }
                        }
                    });

                }else{
                    vm.parentErrors.username = null;
                }
            },
            StoreParent() {
                var vm = this;
                var datas = new FormData();
                datas.append('username', vm.addParent.username);
                datas.append('email', vm.addParent.email);
                datas.append('password', vm.addParent.password);
                datas.append('fname', vm.addParent.fname);
                datas.append('lname', vm.addParent.lname);
                datas.append('gender', vm.addParent.gender);
                datas.append('birthday', vm.addParent.birthday);
                datas.append('address', vm.addParent.address);
                datas.append('phone', vm.addParent.phone);
                datas.append('mobile', vm.addParent.mobile);
                datas.append('student', vm.addParent.student.join(','));

                datas.append('photo', document.getElementById('add_photo').files[0]);

                var config = {
                    onUploadProgress: function (progressEvent) {
                        var percentCompleted = Math.round((progressEvent.loaded * 100) / progressEvent.total);
                    }
                };


                this.$validator.validateAll({
                    username: vm.addParent.username,
                    email: vm.addParent.email,
                    password: vm.addParent.password,
                    fname: vm.addParent.fname,
                    lname: vm.addParent.lname,
                    add_gender: vm.addParent.gender,
                    birthday: vm.addParent.birthday,
                    address: vm.addParent.address,
                    phone: vm.addParent.phone,
                    mobile: vm.addParent.mobile,
                    add_student: vm.addParent.student,
//                    photo:vm.addParent.photo
                }).then(function (validate) {
                    if (validate) {
                        vm.waiting(true)

                        axios.post('/api/store-parent', datas, config).then(function (response) {
                            vm.addParent = {
                                'username': '',
                                'email': '',
                                'password': '',
                                'fname': '',
                                'lname': '',
                                'gender': '',
                                'birthday': null,
                                'address': null,
                                'phone': null,
                                'mobile': null,
                                'photo': '',
                                'student': [],
                            };
                            if (response.status == 200) {
                                vm.parents.push(response.data)
                                Vue.nextTick(() => {
                                    vm.errors.clear()
                                })
                                vm.ValidateMsg();
                                noty({
                                    type: 'success',
                                    layout: 'topLeft',
                                    text: 'والد با موفقیت ذخیره شد.',
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
                                textAlign:'right',
                                animation:{
                                    open:'hvr-buzz-out'
                                },
                                timeout: 3000,
                            });
                            vm.waiting(false)
                        });
                    }

                }).catch(function () {

                });

            },
            EditParent(parent) {
                var vm = this;
                vm.addParent = {
                    'username': '',
                    'email': '',
                    'password': '',
                    'fname': '',
                    'lname': '',
                    'gender': '',
                    'birthday': null,
                    'address': null,
                    'phone': null,
                    'mobile': null,
                    'photo': '',
                    'student': [],
                };
                Vue.set(vm.$data, 'parent', parent);
                vm.waiting(true)
                axios.get('/api/parent/get-students-of-parents/' + vm.parent.id).then(function (response) {
                    vm.addParent.student = response.data[0];
                    Vue.set(vm.$data, 'student', response.data[1]);
                    vm.addParent.gender = response.data[2];
                    Vue.nextTick(() => {
                        vm.errors.clear()
                    })
                    vm.ValidateMsg();
                    vm.status = 'edit-parent';
                    vm.waiting(false)
                });

            },
            UpdateParent: function () {
                var vm = this;
                var datas = new FormData();
                datas.append('id', vm.parent.id);
                datas.append('username', vm.parent.username);
                datas.append('email', vm.parent.email);
                datas.append('password', vm.parent.password);
                datas.append('fname', vm.parent.fname);
                datas.append('lname', vm.parent.lname);
                datas.append('birthday', vm.parent.birthday);
                datas.append('address', vm.parent.address);
                datas.append('phone', vm.parent.phone);
                datas.append('mobile', vm.parent.mobile);
                datas.append('gender', vm.addParent.gender);
                datas.append('student', vm.addParent.student.join(','));


                datas.append('photo', document.getElementById('edit_photo').files[0]);

                var config = {
                    onUploadProgress: function (progressEvent) {
                        var percentCompleted = Math.round((progressEvent.loaded * 100) / progressEvent.total);
                    }
                };

                this.$validator.validateAll({
                    edit_username: vm.parent.username,
                    edit_email: vm.parent.email,
                    edit_password: vm.parent.password,
                    edit_fname: vm.parent.fname,
                    edit_lname: vm.parent.lname,
                    edit_birthday: vm.parent.birthday,
                    edit_address: vm.parent.address,
                    edit_phone: vm.parent.phone,
                    edit_mobile: vm.parent.mobile,
                    edit_gender: vm.addParent.gender,
                    edit_student: vm.addParent.student,
//                    edit_photo:vm.parent.photo
                }).then(function (validate) {
                    if (validate) {
                        vm.waiting(true)

                        axios.post('/api/update-parent', datas, config).then(function (response) {
                            if (response.status == 200) {
                                noty({
                                    type: 'success',
                                    layout: 'topLeft',
                                    text: 'اطلاعات والد با موفقیت بروز شد.',
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
                                textAlign:'right',
                                animation:{
                                    open:'hvr-buzz-out'
                                },
                                timeout: 3000,
                            });
                            vm.waiting(false)
                        });
                    }

                }).catch(function () {

                });
            },
            DelParent: function (parent_id, index) {
                var vm = this;
                swal({
                    title: 'حذف ولی',
                    text: 'از حذف ولی مطمئن هستید؟',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'بله',
                    cancelButtonText: 'خیر'
                }).then(function () {
                    vm.waiting(true)
                    axios.post('/api/delete-parent', {parent_id: parent_id}).then(function (response) {
                        if (response.status == 200) {
                            Vue.delete(vm.parents, index);
                            noty({
                                type: 'success',
                                layout: 'topLeft',
                                text: 'والد با موفقیت حذف شد.',
                                timeout: 3000,
                            });
                            document.getElementById('noty_audio').play();
                            vm.parents.delete
                        }
                        vm.waiting(false)
                    }).catch(function (error) {
                        noty({
                            type: 'error',
                            layout: 'topLeft',
                            text: 'مشکلی وجود دارد.',
                            textAlign:'right',
                            animation:{
                                open:'hvr-buzz-out'
                            },
                            timeout: 3000,
                        });
                        vm.waiting(false)
                    });
                })

            },
            ValidateMsg(){
                this.$validator.updateDictionary({
                    'fa': {
                        custom: {
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
                                required: 'نام والد نباید خالی باشد.',
                            },
                            lname: {
                                required: 'نام خانوادگی والد نباید خالی باشد.',
                            },
                            add_gender: {
                                required: 'جنسیت ولی را انتخاب کنید.',
                                in: 'جنسیت ولی معتبر نمی باشد.',
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
                            add_photo: {
                                mimes: 'فایل انتخاب شده عکس نمی باشد.',
                            },
                            add_student: {
                                required: 'دانش آموز باید انتخاب شود.',
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
                                required: 'نام والد نباید خالی باشد.',
                            },
                            edit_lname: {
                                required: 'نام خانوادگی والد نباید خالی باشد.',
                            },
                            edit_gender: {
                                required: 'جنسیت ولی را انتخاب کنید.',
                                in: 'جنسیت ولی معتبر نمی باشد.',
                            },
                            edit_student: {
                                required: 'دانش آموز باید انتخاب شود.',
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
            AddParent(){
                this.addParent = {
                    'username': '',
                    'email': '',
                    'password': '',
                    'fname': '',
                    'lname': '',
                    'gender': '',
                    'birthday': null,
                    'address': null,
                    'phone': null,
                    'mobile': null,
                    'photo': '',
                    'student': [],
                };
                this.status = 'add-parent';
                Vue.nextTick(() => {
                    this.errors.clear()
                })
                this.ValidateMsg();
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
