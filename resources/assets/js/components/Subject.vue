<template>
    <div>
        <!--SUBJECT LIST-->
        <div v-if="status == 'subject-list'">
            <md-button class="md-primary md-raised" @click.native="AddSubject()">
                افزودن
            </md-button>
            <br><br>
            <div class="box col-xs-12">
                <md-table-card>
                    <md-toolbar>
                        <h1 class="md-title">دروس</h1>
                        <div class="form-group has-success has-feedback">
                            <input placeholder="جستجو..." type="text" v-model="searchBox" class="form-control input-sm">
                            <span class="fa fa-search form-control-feedback" aria-hidden="true"></span>
                        </div>
                    </md-toolbar>

                    <div class="table-responsive">
                        <table class="table">
                            <md-table md-sort="code" md-sort-type="asc" @sort="sortOrder">
                                <md-table-header>
                                    <md-table-row>
                                        <md-table-head md-sort-by="code">کد</md-table-head>
                                        <md-table-head md-sort-by="subject">درس</md-table-head>
                                        <md-table-head>ضریب</md-table-head>
                                        <md-table-head md-sort-by="field_name">رشته</md-table-head>
                                        <md-table-head>
                                            <span>عملیات</span>
                                        </md-table-head>
                                    </md-table-row>
                                </md-table-header>

                                <md-table-body>
                                    <md-table-row v-for="(course, index) in orderedSubjects" :key="index"
                                                  :md-item="subject">
                                        <md-table-cell>{{ course.code }}</md-table-cell>
                                        <md-table-cell>{{ course.subject }}</md-table-cell>
                                        <md-table-cell>{{ course.vahed }}</md-table-cell>
                                        <md-table-cell>{{ course.field_name }}</md-table-cell>
                                        <md-table-cell>
                                            <md-theme md-name="blue">
                                                <md-button class="md-fab md-mini md-primary"
                                                           @click.native="EditSubject(course)">
                                                    <md-icon>edit</md-icon>
                                                    <md-tooltip md-direction="top">ویرایش درس {{ course.subject }}
                                                    </md-tooltip>
                                                </md-button>
                                            </md-theme>
                                            <md-theme md-name="red">
                                                <md-button class="md-fab md-mini  md-primary">
                                                    <md-icon md-iconset="fa fa-trash"
                                                             class="md-size-2x md-primary"></md-icon>
                                                    <md-tooltip md-direction="top">حذف درس {{ course.subject }}
                                                    </md-tooltip>
                                                </md-button>
                                            </md-theme>

                                        </md-table-cell>
                                    </md-table-row>
                                </md-table-body>
                            </md-table>
                        </table>

                        <div v-if="subjects.length < 1" class="panel panel-default empty-table-msg">
                            <div class="panel-body">
                                <p class="text-center"><span class="text-info"> هیچ نمره ای ثبت نشده است.</span>
                                </p>
                            </div>
                        </div>

                    </div>
                </md-table-card>
            </div>
        </div>

        <!--ADD SUBJECT-->
        <div v-show="status == 'add-subject'">
            <div class="col-xs-12">
                <button @click="status = 'subject-list'" class="btn btn-danger pull-left btn-sm  hvr-icon-back">بازگشت</button>
            </div>
            <br><br>
            <div class="col-xs-12">
                <md-card>
                    <md-card-header>
                        <div class="md-title">افزودن درس</div>
                    </md-card-header>
                    <md-card-content>
                        <div class="col-sm-2"></div>
                        <div class="col-sm-8">
                            <form @submit.prevent="StoreSubject()" novalidate class="form-horizontal">
                                <div class="form-group" :class="errors.has('add_subject')?'has-error':''">
                                    <label for="add_subject" class="col-sm-2 control-label">اسم درس </label>
                                    <div class="col-sm-10">
                                        <input
                                                v-validate.initial="'required|max:15'"
                                                name="add_subject"
                                                v-model="addSubject.subject"
                                                class="form-control"
                                                placeholder="اسم درس"
                                                type="text"
                                        >
                                        <div v-show="errors.has('add_subject')" class="help-block">{{
                                            errors.first('add_subject') }}
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group" :class="errors.has('add_code')?'has-error':''">
                                    <label for="add_code" class="col-sm-2 control-label">کد درس </label>
                                    <div class="col-sm-10">
                                        <input
                                                v-validate.initial="'required|numeric'"
                                                name="add_code"
                                                v-model="addSubject.code"
                                                class="form-control"
                                                placeholder="کد درس"
                                                type="text"
                                        >
                                        <div v-show="errors.has('add_code')" class="help-block">{{
                                            errors.first('add_code') }}
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group" :class="errors.has('add_vahed')?'has-error':''">
                                    <label for="add_vahed" class="col-sm-2 control-label">واحد درس </label>
                                    <div class="col-sm-10">
                                        <input
                                                v-validate.initial="'required|numeric|between:1,5'"
                                                name="add_vahed"
                                                v-model="addSubject.vahed"
                                                class="form-control"
                                                placeholder="واحد درس"
                                                type="text"
                                        >
                                        <div v-show="errors.has('add_vahed')" class="help-block">{{
                                            errors.first('add_vahed') }}
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group " :class="errors.has('add_field')?'has-error':''">
                                    <label for="add_field" class="col-sm-2 control-label">رشته </label>
                                    <div class="col-sm-10">
                                        <select name="add_field"
                                                class="form-control"
                                                v-model="addSubject.field_id"
                                                v-validate.initial="'required'"
                                        >
                                            <option v-for="field in cfields" :value="field.id">{{ field.field }} {{
                                                field.grade_name }}
                                            </option>

                                        </select>
                                        <div v-show="errors.has('add_field')" class="help-block">{{
                                            errors.first('add_field') }}
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

        <!--EDIT SUBJECT-->
        <div v-show="status == 'edit-subject'">
            <div class="col-xs-12">
                <button @click="status = 'subject-list'" class="btn btn-danger pull-left btn-sm  hvr-icon-back">بازگشت</button>
            </div>
            <br><br>
            <div class="col-xs-12">
                <md-card>
                    <md-card-header>
                        <div class="md-title">ویرایش درس</div>
                    </md-card-header>
                    <md-card-content>
                        <div class="col-sm-2"></div>
                        <div class="col-sm-8">
                            <form @submit.prevent="UpdateSubject()" novalidate class="form-horizontal">

                                <div class="form-group" :class="errors.has('edit_subject')?'has-error':''">
                                    <label for="edit_subject" class="col-sm-2 control-label">اسم درس </label>
                                    <div class="col-sm-10">
                                        <input
                                                v-validate.initial="'required|max:15'"
                                                name="edit_subject"
                                                v-model="subject.subject"
                                                class="form-control"
                                                placeholder="اسم درس"
                                                type="text"
                                        >
                                        <div v-show="errors.has('edit_subject')" class="help-block">{{
                                            errors.first('edit_subject') }}
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group" :class="errors.has('edit_code')?'has-error':''">
                                    <label for="edit_code" class="col-sm-2 control-label">کد درس </label>
                                    <div class="col-sm-10">
                                        <input
                                                v-validate.initial="'required|numeric'"
                                                name="edit_code"
                                                v-model="subject.code"
                                                class="form-control"
                                                placeholder="کد درس"
                                                type="text"
                                        >
                                        <div v-show="errors.has('edit_code')" class="help-block">{{
                                            errors.first('edit_code') }}
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group" :class="errors.has('edit_vahed')?'has-error':''">
                                    <label for="edit_vahed" class="col-sm-2 control-label">واحد درس </label>
                                    <div class="col-sm-10">
                                        <input
                                                v-validate.initial="'required|numeric|between:1,5'"
                                                name="edit_vahed"
                                                v-model="subject.vahed"
                                                class="form-control"
                                                placeholder="واحد درس"
                                                type="text"
                                        >
                                        <div v-show="errors.has('edit_vahed')" class="help-block">{{
                                            errors.first('edit_vahed') }}
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group " :class="errors.has('edit_field')?'has-error':''">
                                    <label for="edit_field" class="col-sm-2 control-label">رشته </label>
                                    <div class="col-sm-10">
                                        <select name="edit_field"
                                                class="form-control"
                                                v-model="subject.field_id"
                                                v-validate.initial="'required'"
                                        >
                                            <option v-for="field in cfields" :value="field.id">{{ field.field }} {{
                                                field.grade_name }}
                                            </option>

                                        </select>
                                        <div v-show="errors.has('edit_field')" class="help-block">{{
                                            errors.first('edit_field') }}
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

    </div>
</template>

<script>
    export default {
        data() {
            return {
                cfields: {},
                subjects: {},
                subject: {},
                addSubject: {'subject': '', 'code': '', 'field_id': '', 'vahed': ''},
                searchBox: '',
                status: 'subject-list',
                sortType: 'asc',
                sortBy: 'name',
            }
        },
        computed: {
            orderedSubjects: function () {
                var vm = this;
                return _.filter(_.orderBy(this.subjects, this.sortBy, this.sortType), function (subject) {
                    return subject.field_name.includes(vm.searchBox) || subject.subject.includes(vm.searchBox);
                });
            },
        },
        created: function () {
            this.getSubject();
            this.getField();
        },
        methods: {
            getField() {
                var vueModel = this;
                axios.get('/api/get-fields').then(function (response) {
                    Vue.set(vueModel.$data, 'cfields', response.data);
                });
            },
            getSubject() {
                var vm = this;
                vm.waiting(true)
                axios.get('/api/get-subjects').then(function (response) {
                    Vue.set(vm.$data, 'subjects', response.data);
                    vm.waiting(false)
                });
            },
            AddSubject() {
                Vue.nextTick(() => {
                    this.errors.clear()
                })
                this.ValidateMsg();
                this.status = 'add-subject';
            },
            StoreSubject() {
                var vm = this;
                this.$validator.validateAll({
                    add_subject: vm.addSubject.subject,
                    add_code: vm.addSubject.code,
                    add_vahed: vm.addSubject.vahed,
                    add_field: vm.addSubject.field_id
                }).then(function (validate) {
                    if (validate) {
                        vm.waiting(true)

                        axios.post('/api/store-subject', vm.addSubject).then(function (response) {
                            if (response.status == 200) {
                                vm.addSubject = {'subject': '', 'code': '', 'field_id': '', 'vahed': ''};
                                vm.subjects.push(response.data);
                                noty({
                                    type: 'success',
                                    layout: 'topLeft',
                                    text: 'درس با موفقیت ذخیره شد.',
                                    timeout: 3000,
                                });
                                Vue.nextTick(() => {
                                    vm.errors.clear()
                                })
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
            EditSubject(subject) {
                Vue.set(this.$data, 'subject', subject);
                Vue.nextTick(() => {
                    this.errors.clear()
                })
                this.ValidateMsg();
                this.status = 'edit-subject';
            },
            UpdateSubject() {
                var vm = this;
                this.$validator.validateAll({
                    add_subject: vm.subject.subject,
                    add_code: vm.subject.code,
                    add_vahed: vm.subject.vahed,
                    add_field: vm.subject.field_id
                }).then(function (validate) {
                    if (validate) {
                        vm.waiting(true)
                        axios.post('/api/update-subject', vm.subject).then(function (response) {
                            if (response.status == 200) {
                                noty({
                                    type: 'success',
                                    layout: 'topLeft',
                                    text: 'اطلاعات درس با موفقیت بروز شد.',
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
            DelSubject(subject_id, index) {
                var vm = this;
                swal({
                    title: 'حذف درس',
                    text: 'از حذف درس مطمئن هستید؟',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'بله',
                    cancelButtonText: 'خیر'
                }).then(function () {

                    vm.waiting(true)
                    axios.post('/api/delete-subject', {subject_id: subject_id}).then(function (response) {
                        if (response.status == 200) {
                            Vue.delete(vm.subjects, index);
                            noty({
                                type: 'success',
                                layout: 'topLeft',
                                text: 'درس با موفقیت حذف شد.',
                                timeout: 3000,
                            });
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
                });
            },
            ValidateMsg(){
                this.$validator.updateDictionary({
                    'fa': {
                        custom: {
                            add_subject: {
                                required: ' اسم درس نباید خالی باشد.',
                                max: 'اسم درس نباید بیشتر از 15 حرف باشد.'
                            },
                            add_code: {
                                required: 'کد درس را وارد کنید.',
                                numeric: 'کد درس باید عدد باشد.',
                            },
                            add_vahed: {
                                required: 'تعداد واحد درس را وارد کنید.',
                                numeric: 'واحد درس باید عدد باشد.',
                                between: 'واحد درس باید بین 1 تا 5 باشد.',
                            },
                            add_field: {
                                required: 'رشته را انتخاب کنید.'
                            },
                            edit_subject: {
                                required: ' اسم درس نباید خالی باشد.',
                                max: 'اسم درس نباید بیشتر از 15 حرف باشد.'
                            },
                            edit_code: {
                                required: 'کد درس را وارد کنید.',
                                numeric: 'کد درس باید عدد باشد.',
                            },
                            edit_vahed: {
                                required: 'تعداد واحد درس را وارد کنید.',
                                numeric: 'واحد درس باید عدد باشد.',
                                between: 'واحد درس باید بین 1 تا 5 باشد.',
                            },
                            edit_field: {
                                required: 'رشته را انتخاب کنید.'
                            }
                        },

                    }
                });
                this.$validator.setLocale('fa');
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
        }
    }
</script>
