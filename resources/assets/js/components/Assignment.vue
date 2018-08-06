<template>
    <div>

        <!--ASSIGNMENT LIST-->
        <div v-if="status == 'assignment-list'">

            <div class="box col-xs-12">
                <button v-if="auth.role == 'teacher' || auth.role == 'admin'"
                        class="btn btn-success btn-sm hvr-icon-float-away" @click="AddAssignment()">
                    افزودن
                </button>
                <br><br>
                <md-table-card>
                    <md-toolbar>
                        <h1 class="md-title">تکلیف ها</h1>
                        <div class="col-sm-10 form-inline">
                            <div class="pull-left">

                                <div class="row">

                                    <div v-if="auth.role == 'teacher' || auth.role == 'admin'"
                                         class="form-group has-success ">
                                        <label>کلاس : </label>
                                        <select v-model="searchClase" class="form-control">
                                            <option value="">فیلتر بر اساس کلاس</option>
                                            <option v-for="clase in classes" :value="clase.id">{{clase.clase}}</option>
                                        </select>
                                    </div>

                                    <div class="form-group has-success ">
                                        <label>درس : </label>
                                        <select v-model="searchSubject" class="form-control">
                                            <option value="">فیلتر بر اساس درس</option>
                                            <option v-for="subject in user_subjects" :value="subject.id">
                                                {{subject.subject}}
                                            </option>
                                        </select>
                                    </div>
                                    &nbsp;
                                    <div class="form-group has-success ">
                                        <label for="name">جستجو : </label>
                                        <input placeholder=" عنوان , توضیحات " type="text" v-model="searchBox"
                                               class="form-control">
                                    </div>

                                </div>
                            </div>

                        </div>
                    </md-toolbar>

                    <div class="table-responsive">
                        <table class="table">
                            <md-table md-sort="created_at" md-sort-type="asc" @sort="sortOrder">
                                <md-table-header>
                                    <md-table-row>
                                        <md-table-head style="width: 5px;"></md-table-head>
                                        <md-table-head md-sort-by="title">عنوان</md-table-head>
                                        <md-table-head md-sort-by="description">توضیحات</md-table-head>
                                        <md-table-head md-sort-by="deadtime">زمان پایان</md-table-head>
                                        <md-table-head>
                                            <span>عملیات</span>
                                        </md-table-head>
                                    </md-table-row>
                                </md-table-header>

                                <md-table-body>
                                    <md-table-row v-for="(assignment, index) in orderedAssignments" :key="index"
                                                  :md-item="assignment">
                                        <md-table-cell>
                                            <span v-if="assignment.type == 2">
                                                <span v-if="assignment.path.split('.').pop() == 'pdf'"><span
                                                        class="fa fa-file-pdf-o fa-2x"></span></span>
                                                <span v-if="assignment.path.split('.').pop() == 'jpg' || assignment.path.split('.').pop() == 'jpeg'"><span
                                                        class="fa fa-file-image-o fa-2x"></span></span>
                                            </span>
                                            <span v-if="assignment.type == 1" class="fa fa-book fa-2x"></span>
                                        </md-table-cell>
                                        <md-table-cell>{{ assignment.title }}</md-table-cell>
                                        <md-table-cell>{{ assignment.description }}</md-table-cell>
                                        <md-table-cell>{{ assignment.deadtime }}</md-table-cell>
                                        <md-table-cell>

                                            <md-theme v-if="auth.role == 'teacher' || auth.role == 'admin'"
                                                      md-name="blue">
                                                <md-button class="md-fab md-mini md-primary" id="edit_assignment_model"
                                                           @click.native="EditAssignment(assignment)">
                                                    <md-icon>edit</md-icon>
                                                    <md-tooltip md-direction="top">ویرایش تکلیف {{ assignment.title }}
                                                    </md-tooltip>
                                                </md-button>
                                            </md-theme>

                                            <md-theme v-if="assignment.type == 2" md-name="green">
                                                <a :href="'files/assignments/'+assignment.path" download>
                                                    <md-button class="md-fab md-mini md-primary">
                                                        <span class="fa fa-download"></span>
                                                        <md-tooltip md-direction="top">دانلود تکلیف {{ assignment.title
                                                            }}
                                                        </md-tooltip>
                                                    </md-button>
                                                </a>
                                            </md-theme>

                                            <md-theme v-if="auth.role == 'teacher' || auth.role == 'admin'"
                                                      md-name="red">
                                                <md-button class="md-fab md-mini  md-primary">
                                                    <md-icon md-iconset="fa fa-trash"
                                                             class="md-size-2x md-primary"></md-icon>
                                                    <md-tooltip md-direction="top">حذف تکلیف {{ assignment.title }}
                                                    </md-tooltip>
                                                </md-button>
                                            </md-theme>

                                        </md-table-cell>
                                    </md-table-row>
                                </md-table-body>
                            </md-table>
                        </table>

                        <div v-if="assignments.length < 1" class="panel panel-default empty-table-msg">
                            <div class="panel-body">
                                <p class="text-center"><span class="text-info"> هیچ تکلیفی ثبت نشده است.</span>
                                </p>
                            </div>
                        </div>

                    </div>
                </md-table-card>
            </div>

        </div>

        <!--ADD ASSIGNMENT-->
        <div v-show="status == 'add-assignment'">
            <div class="col-xs-12">
                <button @click="status = 'assignment-list'" class="btn btn-danger pull-left  btn-sm  hvr-icon-back">
                    بازگشت
                </button>
            </div>
            <br><br>
            <div class="col-xs-12">
                <md-card>
                    <md-card-header>
                        <div class="md-title">افزودن تکلیف</div>
                    </md-card-header>
                    <md-card-content>
                        <div class="col-sm-2"></div>
                        <div class="col-sm-8">
                            <form @submit.prevent="StoreAssignment()" novalidate class="form-horizontal">

                                <div class="form-group" :class="errors.has('add_title')?'has-error':''">
                                    <label for="add_title" class=" control-label">عنوان تکلیف </label>
                                    <input

                                            v-model="addAssignment.title"
                                            name="add_title"
                                            v-validate.initial="'required|max:20'"
                                            class="form-control"
                                            placeholder=" عنوان تکلیف"
                                            type="text"
                                    >
                                    <div v-show="errors.has('add_title')" class="help-block">{{
                                        errors.first('add_title') }}
                                    </div>
                                </div>

                                <div class="form-group" :class="errors.has('add_class')?'has-error':''">
                                    <label for="add_class" class=" control-label">کلاس </label>
                                    <multiselect
                                            v-model="clase"
                                            :options="classes"
                                            :multiple="true"
                                            track-by="clase"
                                            :internal-search="false"
                                            data-vv-name="add_class"
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

                                    <div v-show="errors.has('add_class')" class="help-block">{{
                                        errors.first('add_class') }}
                                    </div>
                                </div>

                                <div class="form-group" :class="errors.has('add_subject')?'has-error':''">
                                    <label for="add_subject" class=" control-label">درس </label>
                                    <multiselect
                                            v-model="subject"
                                            :options="subjects"
                                            :multiple="true"
                                            track-by="subject"
                                            :internal-search="false"
                                            data-vv-name="add_subject"
                                            label="subject"
                                            placeholder=""
                                            v-validate.initial="'required'"
                                            select-label="برای انتخاب کلیک کنید."
                                            deselect-label="برای حذف انتخاب کلیک کنید."
                                            selected-label="انتخاب شده."
                                            :hide-selected="true"
                                            noResult="درسی موجود نمی باشد."

                                    ></multiselect>

                                    <div v-show="errors.has('add_subject')" class="help-block">{{
                                        errors.first('add_subject') }}
                                    </div>
                                </div>

                                <div class="form-group" :class="errors.has('add_description')?'has-error':''">
                                    <label for="add_description" class=" control-label">توضیحات </label>
                                    <textarea
                                            v-model="addAssignment.description"
                                            name="add_description"
                                            v-validate.initial="'max:255'"
                                            class="form-control"
                                            placeholder=" توضیحات"
                                            type="text"
                                    ></textarea>
                                    <div v-show="errors.has('add_description')" class="help-block">{{
                                        errors.first('add_description') }}
                                    </div>
                                </div>

                                <div class="form-group" :class="errors.has('add_end')?'has-error':''">
                                    <label for="add_end" class=" control-label">زمان پایان </label>
                                    <input
                                            v-mask="'####/##/## ##:##'"
                                            v-model="addAssignment.endTime"
                                            name="add_end"
                                            v-validate.initial="'required|date_format:YYYY/MM/DD HH:mm'"
                                            class="form-control"
                                            placeholder="زمان پایان"
                                            type="text"
                                    >
                                    <div v-show="errors.has('add_end')" class="help-block">{{ errors.first('add_end')
                                        }}
                                    </div>
                                </div>

                                <div class="form-group" :class="errors.has('add_type')?'has-error':''">
                                    <label for="add_type" class="col-sm-2 control-label"> فایل ضمیمه </label>
                                    <div class="col-sm-10">
                                        <md-radio v-validate.initial="'required|in:1,2'" v-model="addAssignment.type"
                                                  id="with_attachment" data-vv-name="add_type" md-value="1">بدون فایل
                                        </md-radio>
                                        <md-radio v-model="addAssignment.type" id="without_attachment"
                                                  data-vv-name="add_type"
                                                  md-value="2">دارای فایل
                                        </md-radio>

                                        <div v-show="errors.has('add_type')" class="help-block">{{
                                            errors.first('add_type') }}
                                        </div>
                                    </div>
                                </div>

                                <div v-if="addAssignment.type == 2" class="form-group"
                                     :class="errors.has('add_attach')?'has-error':''">
                                    <label for="add_attach" class=" control-label">زمان پایان </label>

                                    <input
                                            name="add_attach"
                                            type="file"
                                            id="add_attach"
                                            class="form-control"
                                            v-validate.initial="'mimes:pdf,image/jpeg|size:2048'"
                                    >
                                    <div v-show="errors.has('add_attach')" class="help-block">{{
                                        errors.first('add_attach') }}
                                    </div>
                                </div>

                                <input type="submit" class="btn btn-success btn-lg pull-left hvr-float-shadow"
                                       value="ذخیره">

                            </form>
                        </div>

                    </md-card-content>

                </md-card>
            </div>
        </div>

        <!--EDIT ASSIGNMENT-->
        <div v-show="status == 'edit-assignment'">
            <div class="col-xs-12">
                <button @click="status = 'assignment-list'" class="btn btn-danger pull-left  btn-sm  hvr-icon-back">
                    بازگشت
                </button>
            </div>
            <br><br>
            <div class="col-xs-12">
                <md-card>
                    <md-card-header>
                        <div class="md-title">افزودن تکلیف</div>
                    </md-card-header>
                    <md-card-content>
                        <div class="col-sm-2"></div>
                        <div class="col-sm-8">
                            <form @submit.prevent="UpdateAssignment()" novalidate class="form-horizontal">

                                <div class="form-group" :class="errors.has('edit_title')?'has-error':''">
                                    <label for="edit_title" class=" control-label">عنوان تکلیف </label>
                                    <input

                                            v-model="assignment.title"
                                            name="edit_title"
                                            v-validate.initial="'required|max:20'"
                                            class="form-control"
                                            placeholder=" عنوان تکلیف"
                                            type="text"
                                    >
                                    <div v-show="errors.has('edit_title')" class="help-block">{{
                                        errors.first('edit_title') }}
                                    </div>
                                </div>

                                <div class="form-group" :class="errors.has('edit_class')?'has-error':''">
                                    <label for="edit_class" class=" control-label">کلاس </label>
                                    <multiselect
                                            v-model="clase"
                                            :options="classes"
                                            :multiple="true"
                                            track-by="clase"
                                            :internal-search="false"
                                            data-vv-name="edit_class"
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

                                    <div v-show="errors.has('edit_class')" class="help-block">{{
                                        errors.first('edit_class') }}
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

                                <div class="form-group" :class="errors.has('edit_description')?'has-error':''">
                                    <label for="edit_description" class=" control-label">توضیحات </label>
                                    <textarea
                                            v-model="assignment.description"
                                            name="edit_description"
                                            v-validate.initial="'max:255'"
                                            class="form-control"
                                            placeholder=" توضیحات"
                                            type="text"
                                    ></textarea>
                                    <div v-show="errors.has('edit_description')" class="help-block">{{
                                        errors.first('edit_description') }}
                                    </div>
                                </div>

                                <div class="form-group" :class="errors.has('edit_end')?'has-error':''">
                                    <label for="edit_end" class=" control-label">زمان پایان </label>
                                    <input
                                            v-mask="'####/##/## ##:##'"
                                            v-model="assignment.deadtime"
                                            name="edit_end"
                                            v-validate.initial="'required|date_format:YYYY/MM/DD HH:mm'"
                                            class="form-control"
                                            placeholder="زمان پایان"
                                            type="text"
                                    >
                                    <div v-show="errors.has('edit_end')" class="help-block">{{ errors.first('edit_end')
                                        }}
                                    </div>
                                </div>

                                <div v-if="assignment.type == 2" class="form-group"
                                     :class="errors.has('edit_attach')?'has-error':''">
                                    <label for="edit_attach" class=" control-label">زمان پایان </label>

                                    <input
                                            name="edit_attach"
                                            type="file"
                                            id="edit_attach"
                                            class="form-control"
                                            v-validate.initial="'mimes:pdf,image/jpeg|size:2048'"
                                    >
                                    <div v-show="errors.has('edit_attach')" class="help-block">{{
                                        errors.first('edit_attach') }}
                                    </div>
                                </div>

                                <input type="submit" class="btn btn-success pull-left" value="بروزرسانی">

                            </form>
                        </div>

                    </md-card-content>

                </md-card>
            </div>
        </div>

    </div>
</template>

<script>
    import Multiselect from 'vue-multiselect'

    export default {
        components: {Multiselect},
        data() {
            return {
                assignments: [],
                assignment: {},
                classes: [],
                clase: [],
                auth: [],
                subjects: [],
                user_subjects: [],
                subject: [],
                addAssignment: {
                    'title': '',
                    'description': '',
                    'class_ids': '',
                    'subject_ids': '',
                    'endTime': '',
                    'type': 1
                },
                searchBox: '',
                searchClase: '',
                searchSubject: '',
                status: 'assignment-list',
                sortType: 'asc',
                sortBy: 'name',
            }
        },
        props: ['authUser'],
        created: function () {
            this.auth = JSON.parse(this.authUser);
            this.getClase();
            this.getAssignment();
            this.getUserSubjects();
        },
        computed: {
            orderedAssignments: function () {
                var vm = this;
                return _.filter(_.orderBy(this.assignments, this.sortBy, this.sortType), function (assignment) {
                    return ((assignment.title || '').includes(vm.searchBox) || (assignment.description || '').includes(vm.searchBox))
                        && (assignment.class_ids.split('|:br:|').includes(vm.searchClase.toString()) || vm.searchClase == '')
                        && (assignment.subject_ids.split('|:br:|').includes(vm.searchSubject.toString()) || vm.searchSubject == '');
                });
            },
        },
        methods: {
            getAssignment() {
                var vm = this;
                vm.waiting(true);
                axios.get('/api/assignment/get-assignments').then(function (response) {
                    Vue.set(vm.$data, 'assignments', response.data);
                    vm.waiting(false);
                });
            },
            getClase() {
                var vm = this;
                axios.get('/api/assignment/get-classes').then(function (response) {
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
                field_ids = field_ids.join(',')
                axios.get('/api/online-exams/get-subjects/' + field_ids).then(function (response) {
                    Vue.set(vm.$data, 'subjects', response.data);
                });
            },
            getUserSubjects() {
                var vm = this;
                axios.get('/api/subject/get-user-subjects').then(function (response) {
                    Vue.set(vm.$data, 'user_subjects', response.data);
                });
            },
            EditAssignment(assignment) {
                var vm = this;
                Vue.set(this.$data, 'assignment', assignment);
                Vue.nextTick(() => {
                    this.errors.clear()
                });
                this.ValidateMsg();
                axios.get('/api/assignment/get-classes').then(function (response) {
                    Vue.set(vm.$data, 'classes', response.data);
                });

                axios.post('/api/online-exam/get-selectclass', {'clase': vm.assignment.class_ids}).then(function (response) {
                    vm.waiting(true);
                    Vue.set(vm.$data, 'clase', response.data);
                    var field_ids = [];
                    vm.subjects = [];
                    for (var i in vm.clase) {
                        field_ids.push(vm.clase[i].field_id);
                    }
                    field_ids = field_ids.join(',')
                    axios.get('/api/online-exams/get-subjects/' + field_ids).then(function (response) {
                        Vue.set(vm.$data, 'subjects', response.data);
                        axios.post('/api/online-exam/get-selectsubject', {'subject': vm.assignment.subject_ids}).then(function (response) {
                            Vue.set(vm.$data, 'subject', response.data);
                            vm.status = 'edit-assignment';
                            vm.waiting(false);
                        });
                    });

                });

            },
            AddAssignment(){
                var vm = this;
                Vue.nextTick(() => {
                    this.errors.clear()
                })
                this.ValidateMsg();
                this.getClase();
                vm.status = 'add-assignment';
            },
            StoreAssignment() {
                var vm = this;
                vm.addAssignment.class_ids = [];
                vm.addAssignment.subject_ids = [];
                for (var i in vm.clase) {
                    vm.addAssignment.class_ids.push(vm.clase[i].id);
                }
                for (var i in vm.subject) {
                    vm.addAssignment.subject_ids.push(vm.subject[i].id);
                }

                var datas = new FormData();
                datas.append('title', vm.addAssignment.title);
                datas.append('description', vm.addAssignment.description);
                datas.append('class_ids', vm.addAssignment.class_ids.join('|:br:|'));
                datas.append('subject_ids', vm.addAssignment.subject_ids.join('|:br:|'));
                datas.append('endTime', vm.addAssignment.endTime);

                if (vm.addAssignment.type == 2) {

                    datas.append('attach', document.getElementById('add_attach').files[0]);

                    var config = {
                        onUploadProgress: function (progressEvent) {
                            var percentCompleted = Math.round((progressEvent.loaded * 100) / progressEvent.total);
                        }
                    };
                }

                this.$validator.validateAll({
                    add_title: vm.addAssignment.title,
                    add_description: vm.addAssignment.description,
                    add_class: vm.addAssignment.class_ids,
                    add_subject: vm.addAssignment.subject_ids,
                    add_end: vm.addAssignment.endTime,
                }).then(function (validate) {
                    if (validate) {
                        vm.waiting(true)

                        axios.post('/api/assignment/store-assignment', datas, config).then(function (response) {
                            if (response.status == 200) {
                                vm.addAssignment = {
                                    'title': '',
                                    'description': '',
                                    'class_ids': '',
                                    'subject_ids': '',
                                    'endTime': '',
                                    'type': 1
                                };
                                Vue.nextTick(() => {
                                    vm.errors.clear()
                                })
                                vm.ValidateMsg();
                                vm.assignments.push(response.data);
                                noty({
                                    type: 'success',
                                    layout: 'topLeft',
                                    text: 'تکلیف با موفقیت ذخیره شد.',
                                    timeout: 3000,
                                });
                                document.getElementById('noty_audio').play();
                            }
                            vm.waiting(false);
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
            UpdateAssignment() {
                var vm = this;
                vm.assignment.class_ids = [];
                vm.assignment.subject_ids = [];
                for (var i in vm.clase) {
                    vm.assignment.class_ids.push(vm.clase[i].id);
                }
                for (var i in vm.subject) {
                    vm.assignment.subject_ids.push(vm.subject[i].id);
                }


                var datas = new FormData();
                datas.append('id', vm.assignment.id);
                datas.append('title', vm.assignment.title);
                datas.append('description', vm.assignment.description);
                datas.append('class_ids', vm.assignment.class_ids.join('|:br:|'));
                datas.append('subject_ids', vm.assignment.subject_ids.join('|:br:|'));
                datas.append('deadtime', vm.assignment.deadtime);

                if (vm.assignment.type == 2) {

                    datas.append('attach', document.getElementById('edit_attach').files[0]);

                    var config = {
                        onUploadProgress: function (progressEvent) {
                            var percentCompleted = Math.round((progressEvent.loaded * 100) / progressEvent.total);
                        }
                    };
                }


                this.$validator.validateAll({
                    edit_title: vm.assignment.title,
                    edit_description: vm.assignment.description,
                    edit_class: vm.assignment.class_ids,
                    edit_subject: vm.assignment.subject_ids,
                    edit_end: vm.assignment.deadtime,
                }).then(function (validate) {
                    if (validate) {
                        vm.waiting(true)

                        axios.post('/api/assignment/update-assignment', datas, config).then(function (response) {
                            if (response.status == 200) {
                                noty({
                                    type: 'success',
                                    layout: 'topLeft',
                                    text: 'تکلیف با موفقیت ذخیره شد.',
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
            DelAssignment(assignment_id, index) {
                var vm = this;
                swal({
                    title: 'حذف تکلیف',
                    text: 'از حذف تکلیف مطمئن هستید؟',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'بله',
                    cancelButtonText: 'خیر'
                }).then(function () {

                    vm.waiting(true)
                    axios.post('/api/assignment/delete-assignment', {assignment_id: assignment_id}).then(function (response) {
                        if (response.status == 200) {
                            Vue.delete(vm.assignments, index);

                            noty({
                                type: 'success',
                                layout: 'topLeft',
                                text: 'تکلیف با موفقیت حذف شد.',
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
                            add_class: {
                                required: 'کلاس را انتخاب کنید.'
                            },
                            add_subject: {
                                required: 'درس را انتخاب کنید.',
                            },
                            add_title: {
                                required: 'عنوان باید انتخاب شود.',
                                max: 'عنوان حداکثر 20 حرف می تواند باشد.'
                            },
                            add_description: {
                                max: 'توضیحات حداکثر 255 حرف می تواند باشد.'
                            },
                            add_end: {
                                required: 'زمان پایان باید انتخاب شود.',
                                date_format: 'زمان پایان باید به فرم "روز/ماه/سال ساعت:دقیقه" باشد.'
                            },
                            add_attach: {
                                mimes: 'فایل انتخاب شده باید عکس یا پی دی اف باشد.',
                                size: 'حجم فایل نباید بیشتر از 2 مگابایت باشد.'
                            },
                            edit_class: {
                                required: 'کلاس را انتخاب کنید.'
                            },
                            edit_subject: {
                                required: 'درس را انتخاب کنید.',
                            },
                            edit_title: {
                                required: 'عنوان باید انتخاب شود.',
                                max: 'عنوان حداکثر 20 حرف می تواند باشد.'
                            },
                            edit_description: {
                                max: 'توضیحات حداکثر 255 حرف می تواند باشد.'
                            },
                            edit_end: {
                                required: 'زمان پایان باید انتخاب شود.',
                                date_format: 'زمان پایان باید به فرم "روز/ماه/سال ساعت:دقیقه" باشد.'
                            },
                            edit_attach: {
                                mimes: 'فایل انتخاب شده باید عکس یا پی دی اف باشد.',
                                size: 'حجم فایل نباید بیشتر از 2 مگابایت باشد.'
                            },
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
