<template>
    <div>
        <div v-if="status == 'clases'">

            <div class="box col-xs-12">
                <md-table-card>
                    <md-toolbar>
                        <h1 class="md-title">کلاس ها</h1>
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
                                        <md-table-head md-sort-by="clase">کلاس</md-table-head>
                                        <md-table-head md-sort-by="field_name">رشته</md-table-head>
                                        <md-table-head>
                                            <span>عملیات</span>
                                        </md-table-head>
                                    </md-table-row>
                                </md-table-header>

                                <md-table-body>
                                    <md-table-row v-for="(clas, index) in orderedClases" :key="index"
                                                  :md-item="clas">
                                        <md-table-cell>{{ clas.clase }}</md-table-cell>
                                        <md-table-cell>{{ clas.field_name }}</md-table-cell>
                                        <md-table-cell>
                                            <md-theme md-name="green">
                                                <md-button class="md-fab md-mini md-primary" id="edit_clase_model"
                                                           @click.native="clase = clas; getScheduls(clase.id);">
                                                    <md-icon md-iconset="fa fa-home"
                                                             class="md-size-2x md-primary"></md-icon>
                                                    <md-tooltip md-direction="top">برنامه کلاسی کلاس {{ clas.clase }}
                                                    </md-tooltip>
                                                </md-button>
                                            </md-theme>
                                        </md-table-cell>
                                    </md-table-row>
                                </md-table-body>
                            </md-table>
                        </table>

                        <div v-if="clases.length < 1" class="panel panel-default empty-table-msg">
                            <div class="panel-body">
                                <p class="text-center"><span class="text-info"> هیچ کلاسی برای نمایش برنامه کلاسی ثبت نشده است.</span>
                                </p>
                            </div>
                        </div>
                    </div>


                </md-table-card>
            </div>
        </div>

        <div v-if="status == 'schedules'">
            <div class="box col-xs-12">
                <button @click="status = 'clases'" class="btn btn-danger btn-flat pull-left  btn-sm  hvr-icon-back">بازگشت</button>
                <a href="javascript:window.print()" class="btn btn-success btn-flat pull-left">چاپ</a>
            </div>
            <br><br>
            <div class="box col-xs-12">
                <div class="box-header">
                    <h3 class="box-title ng-binding"> برنامه کلاسی</h3>
                </div>
                <div class="box-body table-responsive">

                    <table class="table table-bordered table-hover">
                        <tbody>
                        <tr>
                            <th style="width: 10px">روز</th>
                            <th>برنامه کلاسی</th>
                            <th v-if="auth.role == 'admin'" style="width: 10px">افزودن</th>
                        </tr>
                        <tr v-for="day in days">
                            <td v-text="day.day"></td>
                            <td>
                                <div v-for="(schedule,index) in filterBy(schedules,day.key,'day_week')"
                                     class="btn-group">
                                    <button type="button" class="btn btn-default">{{ schedule.subject_name }} - {{
                                        schedule.start_time }} -&gt;{{ schedule.end_time }}
                                    </button>
                                    <button v-if="auth.role == 'admin'" type="button"
                                            class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                        <span class="sr-only">Toggle Dropdown</span>
                                    </button>
                                    <ul v-if="auth.role == 'admin'" class="dropdown-menu" role="menu">
                                        <li><a @click="EditSchedule(schedule)" id="add_schedule_modal">ویرایش</a>
                                        </li>
                                        <li><a @click="DelSchedule(schedule.id,index)" class="ng-binding">حذف</a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                            <td v-if="auth.role == 'admin'">
                                <md-button class="md-primary md-fab md-mini" id="add_schedule_modal"
                                           @click.native="AddSchedule(day.key) ">
                                    <i class="fa fa-fw fa-plus"></i>
                                    <md-tooltip md-direction="top">افزودن برنامه در {{ day.day }}
                                    </md-tooltip>
                                </md-button>
                            </td>
                        </tr>
                        </tbody>
                    </table>


                </div>
            </div>
        </div>


        <!--ADD SCHEDULE MODAL-->
        <md-dialog md-open-from="#add_schedule_modal" md-close-to="#add_schedule_modal" ref="add_schedule_modal">
            <md-dialog-title>افزودن برنامه</md-dialog-title>
            <md-dialog-content>
                <form @submit.prevent="StoreSchedule()" id="add_schedule_form" class="" novalidate>

                    <div class="form-group" :class="errors.has('add_subject')?'has-error':''">
                        <label for="add_subject" class=" control-label">اسم درس </label>
                        <select name="add_subject"
                                class="form-control"
                                v-model="addSchedule.subject_id"
                                v-validate.initial="'required'"
                        >
                            <option v-for="subject in subjects" :value="subject.id"
                                    v-text="subject.subject"></option>
                        </select>

                        <div v-show="errors.has('add_subject')" class="help-block">{{ errors.first('add_subject')
                            }}
                        </div>
                    </div>

                    <div class="form-group" :class="errors.has('add_start')?'has-error':''">
                        <div class="form-group" :class="errors.has('add_start')?'has-error':''">
                            <label for="add_start" class=" control-label">ساعت شروع </label>
                            <input
                                    v-mask="'##:##'"
                                    v-model="addSchedule.start_time"
                                    name="add_start"
                                    v-validate.initial="'required|date_format:HH:mm'"
                                    class="form-control"
                                    placeholder="ساعت شروع"
                                    type="text"
                            >
                            <div v-show="errors.has('add_start')" class="help-block">{{ errors.first('add_start')
                                }}
                            </div>
                        </div>

                    </div>

                    <div class="form-group" :class="errors.has('add_end')?'has-error':''">
                        <div class="form-group" :class="errors.has('add_end')?'has-error':''">
                            <label for="add_end" class=" control-label">ساعت پایان </label>
                            <input
                                    v-mask="'##:##'"
                                    v-model="addSchedule.end_time"
                                    name="add_end"
                                    v-validate.initial="'required|date_format:HH:mm'"
                                    class="form-control"
                                    placeholder="ساعت پایان"
                                    type="text"
                            >
                            <div v-show="errors.has('add_end')" class="help-block">{{ errors.first('add_end') }}
                            </div>
                        </div>
                    </div>

                </form>
            </md-dialog-content>

            <md-dialog-actions>
                <md-button class="md-primary md-raised" @click.native="closeDialog('add_schedule_modal')">لغو
                </md-button>
                <md-button type="submit" form="add_schedule_form" class="md-primary md-raised">ذخیره
                </md-button>
            </md-dialog-actions>

        </md-dialog>

        <!--EDIT SCHEDULE MODAL-->
        <md-dialog md-open-from="#edit_schedule_modal" md-close-to="#edit_schedule_modal" ref="edit_schedule_modal">
            <md-dialog-title>افزودن برنامه</md-dialog-title>
            <md-dialog-content>
                <form @submit.prevent="UpdateSchedule()" id="edit_schedule_form" class="" novalidate>

                    <div class="form-group" :class="errors.has('edit_subject')?'has-error':''">
                        <label for="edit_subject" class=" control-label">اسم درس </label>
                        <select name="edit_subject"
                                class="form-control"
                                v-model="schedule.subject_id"
                                v-validate.initial="'required'"
                        >
                            <option v-for="subject in subjects" :value="subject.id"
                                    v-text="subject.subject"></option>
                        </select>

                        <div v-show="errors.has('edit_subject')" class="help-block">{{ errors.first('edit_subject')
                            }}
                        </div>
                    </div>

                    <div class="form-group" :class="errors.has('edit_start')?'has-error':''">
                        <div class="form-group" :class="errors.has('edit_start')?'has-error':''">
                            <label for="edit_start" class=" control-label">ساعت شروع</label>
                            <input
                                    v-mask="'##:##'"
                                    v-model="schedule.start_time"
                                    name="edit_start"
                                    v-validate.initial="'required|date_format:HH:mm'"
                                    class="form-control"
                                    placeholder="ساعت شروع"
                                    type="text"
                            >
                            <div v-show="errors.has('edit_start')" class="help-block">{{ errors.first('edit_start')
                                }}
                            </div>
                        </div>

                    </div>

                    <div class="form-group" :class="errors.has('edit_end')?'has-error':''">
                        <div class="form-group" :class="errors.has('edit_end')?'has-error':''">
                            <label for="edit_end" class=" control-label">ساعت پایان</label>
                            <input
                                    v-mask="'##:##'"
                                    v-model="schedule.end_time"
                                    name="edit_end"
                                    v-validate.initial="'required|date_format:HH:mm'"
                                    class="form-control"
                                    placeholder="ساعت پایان"
                                    type="text"
                            >
                            <div v-show="errors.has('edit_end')" class="help-block">{{ errors.first('edit_end') }}
                            </div>
                        </div>
                    </div>

                </form>
            </md-dialog-content>

            <md-dialog-actions>
                <md-button class="md-primary md-raised" @click.native="closeDialog('edit_schedule_modal')">لغو
                </md-button>
                <md-button type="submit" form="edit_schedule_form" class="md-primary md-raised">ذخیره
                </md-button>
            </md-dialog-actions>

        </md-dialog>
    </div>
</template>

<script>

    export default {
        props:['authUser'],
        data() {
            return {
                auth: [],
                clases: [],
                clase: {},
                schedules: {},
                schedule: {},
                days: [
                    {key: 'sat', day: 'شنبه'},
                    {key: 'sun', day: 'یکشنبه'},
                    {key: 'mon', day: 'دوشنبه'},
                    {key: 'tue', day: 'سه شنبه'},
                    {key: 'wed', day: 'چهارشنبه'},
                    {key: 'thu', day: 'پنج شنبه'},
                    {key: 'fri', day: 'جمعه'},
                ],
                subjects: {},
                addSchedule: {'class_id': '', 'subject_id': '', 'day_week': '', 'start_time': '', 'end_time': ''},
                searchBox: '',
                sortType: 'asc',
                sortBy: 'name',
                status: 'clases'
            }
        },
        created: function () {
            this.auth = JSON.parse(this.authUser);
            this.getClasses();
        },
        computed: {
            orderedClases: function () {
                var vm = this;
                return _.filter(_.orderBy(this.clases, this.sortBy, this.sortType), function (clase) {
                    return (clase.field_name || '').includes(vm.searchBox) || (clase.clase || '').includes(vm.searchBox);
                });
            },
        },
        methods: {
            getClasses() {
                var vm = this;
                vm.waiting(true)
                axios.get('/api/get-clases').then(function (response) {
                    Vue.set(vm.$data, 'clases', response.data);
                    vm.waiting(false);
                });
            },
            getScheduls(class_id) {
                var vm = this;
                vm.waiting(true)
                axios.get('/api/get-schedules/' + class_id).then(function (response) {
                    Vue.set(vm.$data, 'schedules', response.data['schedules']);
                    Vue.set(vm.$data, 'subjects', response.data['subjects']);
                    vm.status = 'schedules';
                    vm.waiting(false);
                });
            },
            StoreSchedule () {
                var vm = this;
                vm.addSchedule.class_id = vm.clase.id;
                this.$validator.validateAll({
                    add_subject: vm.addSchedule.subject_id,
                    add_start: vm.addSchedule.start_time,
                    add_end: vm.addSchedule.end_time
                }).then(function (validate) {
                    if (validate) {
                        vm.waiting(true)

                        axios.post('/api/store-schedule', vm.addSchedule).then(function (response) {
                            if (response.status == 200) {
                                vm.schedules.push(response.data);
                                vm.closeDialog('add_schedule_modal');
                                vm.addSchedule = {
                                    'class_id': '',
                                    'subject_id': '',
                                    'day_week': '',
                                    'start_time': '',
                                    'end_time': ''
                                };
                                noty({
                                    type: 'success',
                                    layout: 'topLeft',
                                    text: 'برنامه با موفقیت ثبت شد.',
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
            EditSchedule(schedule) {
                Vue.set(this.$data, 'schedule', schedule);
                Vue.nextTick(() => {
                    this.errors.clear()
                })
                this.ValidateMsg();
                this.openDialog('edit_schedule_modal');
            },
            AddSchedule(key) {
                this.addSchedule.day_week = key
                Vue.nextTick(() => {
                    this.errors.clear()
                })
                this.ValidateMsg();
                this.openDialog('add_schedule_modal');
            },
            UpdateSchedule() {
                var vm = this;
                this.$validator.validateAll({
                    add_subject: vm.schedule.subject_id,
                    add_start: vm.schedule.start_time,
                    add_end: vm.schedule.end_time
                }).then(function (validate) {
                    if (validate) {
                        vm.waiting(true)

                        axios.post('/api/update-schedule', vm.schedule).then(function (response) {
                            if (response.status == 200) {
                                noty({
                                    type: 'success',
                                    layout: 'topLeft',
                                    text: 'تغییرات برنامه ثبت شد. <span class="fa fa-thumbs-up"></span>',
                                    timeout: 3000,
                                });
                                vm.closeDialog('edit_schedule_modal')
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
            DelSchedule(schedule_id, index) {
                var vm = this;
                swal({
                    title: 'حذف از برنامه',
                    text: 'از حذف درس از برنامه کلاسی مطمئن هستید؟',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'بله',
                    cancelButtonText: 'خیر'
                }).then(function () {

                    vm.waiting(true)
                    axios.post('/api/delete-schedule', {schedule_id: schedule_id}).then(function (response) {
                        if (response.status == 200) {
                            vm.schedules.pop(index);
                            noty({
                                type: 'success',
                                layout: 'topLeft',
                                text: 'برنامه با موفقیت حذف شد.',
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
                                required: ' درس را انتخاب کنید.'
                            },
                            add_start: {
                                required: 'زمان شروع درس را وارد کنید.',
                                date_format: 'زمان شروع باید به صورت ساعت:دقیقه باشد.',
                            },
                            add_end: {
                                required: 'زمان پایان درس را وارد کنید.',
                                date_format: 'زمان پایان باید به صورت ساعت:دقیقه باشد.',
                            },
                            edit_subject: {
                                required: ' درس را انتخاب کنید.'
                            },
                            edit_start: {
                                required: 'زمان شروع درس را وارد کنید.',
                                date_format: 'زمان شروع باید به صورت ساعت:دقیقه باشد.',
                            },
                            edit_end: {
                                required: 'زمان پایان درس را وارد کنید.',
                                date_format: 'زمان پایان باید به صورت ساعت:دقیقه باشد.',
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
