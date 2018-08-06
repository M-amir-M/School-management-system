<template>
    <div>
        <div v-if="status == 'select-class-subject'">
            <div class="box col-xs-12">
                <md-card>
                    <md-card-header>
                        <div class="md-title">اطلاعات حضور غیاب</div>
                    </md-card-header>
                    <form novalidate @submit.prevent="getStudents()">
                        <div class="col-sm-2"></div>
                        <div class="col-sm-8">
                            <md-card-content>

                                <div class="form-group" :class="errors.has('check_class')?'has-error':''">
                                    <label for="check_class" class=" control-label">کلاس </label>
                                    <div>
                                        <select name="check_class"
                                                class="form-control"
                                                v-model="clase"
                                                @change="getSubjects()"
                                                v-validate.initial="'required'"
                                        >
                                            <option v-for="clase in classes" :value="clase">{{ clase.clase }}</option>
                                        </select>

                                        <div v-show="errors.has('check_class')" class="help-block">{{ errors.first('check_class') }}</div>
                                    </div>
                                </div>

                                <div class="form-group" :class="errors.has('check_subject')?'has-error':''">
                                    <label for="check_subject" class=" control-label">درس </label>
                                    <div>
                                        <select name="check_subject"
                                                class="form-control"
                                                v-model="subject"
                                                v-validate.initial="'required'"
                                        >
                                            <option v-for="subject in subjects" :value="subject">{{ subject.subject }}</option>
                                        </select>

                                        <div v-show="errors.has('check_subject')" class="help-block">{{ errors.first('check_subject') }}</div>
                                    </div>
                                </div>

                                <div class="form-group" :class="errors.has('check_date')?'has-error':''">
                                    <label for="check_date" class=" control-label">تاریخ </label>
                                    <div>
                                        <input
                                                v-mask="'####/##/##'"
                                                v-model="setDate.setDate"
                                                name="check_date"
                                                v-validate.initial="'required|date_format:YYYY/MM/DD'"
                                                class="form-control"
                                                placeholder="تاریخ"
                                                type="text"
                                        >
                                        <div v-show="errors.has('check_date')" class="help-block">{{ errors.first('check_date') }}</div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <md-theme md-name="green">
                                        <md-radio v-model="showDo" :md-value="'do'"
                                                  id="showDo_do" name="show-do-attendance"
                                                  class="md-primary"> ثبت حضور غیاب
                                        </md-radio>
                                        <md-radio v-model="showDo" :md-value="'show'"
                                                  id="showDo_show" name="show-do-attendance"
                                                  class="md-primary"> بررسی حضور غیاب
                                        </md-radio>
                                    </md-theme>
                                </div>

                            </md-card-content>

                            <md-card-actions>
                                <md-button class="md-raised md-primary" type="submit">کنترل حضور غیاب</md-button>
                            </md-card-actions>
                        </div>
                    </form>
                    <br><br>
                </md-card>
            </div>
        </div>

        <div v-if="status == 'do-attendance'">
            <button @click="status = 'select-class-subject'" class="btn btn-success btn-sm pull-left  btn-sm  hvr-icon-back">بازگشت</button>
            <br><br>
            <div class="box col-xs-12">
                <div class="box-header">
                    <h3 class="box-title">حضور غیاب درس {{ subject.subject }} - تاریخ : {{ setDate.setDate }}</h3>
                </div>
                <div class="box-body table-responsive">
                    <form class="form-horizontal" @submit.prevent="StoreAttendance()">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>دانش آموز</th>
                                <th>حضور غیاب</th>
                            </tr>
                            <tr>
                                <th>انتخاب همه</th>
                                <th>
                                    <input @click="SelectAttend('1')" class="btn btn-info btn-sm" value="حاضر"
                                           type="button">
                                    <input @click="SelectAttend('2')" class="btn btn-info btn-sm" value="غایب"
                                           type="button">
                                    <input @click="SelectAttend('3')" class="btn btn-info btn-sm" value="تاخیر"
                                           type="button">
                                    <input @click="SelectAttend('4')" class="btn btn-info btn-sm"
                                           value="تاخیر با عذر" type="button">
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(student,index) in students">
                                <td>
                                    <md-avatar>
                                        <img :src="'images/avatars/'+student.photo" alt="Avatar">
                                    </md-avatar>
                                    <span v-text="student.fullname"></span>
                                </td>
                                <td>
                                    <div>
                                        <md-theme md-name="green">
                                            <md-radio v-model="attendance[index].status" :md-value="1"
                                                      :id="'studentatt'+student.id+'1'" :name="'student'+student.id"
                                                      class="md-primary"> حاضر
                                            </md-radio>
                                            <md-radio v-model="attendance[index].status" :md-value="2"
                                                      :id="'studentatt'+student.id+'2'" :name="'student'+student.id"
                                                      class="md-primary"> غایب
                                            </md-radio>
                                            <md-radio v-model="attendance[index].status" :md-value="3"
                                                      :id="'studentatt'+student.id+'3'" :name="'student'+student.id"
                                                      class="md-primary"> تاخیر
                                            </md-radio>
                                            <md-radio v-model="attendance[index].status" :md-value="4"
                                                      :id="'studentatt'+student.id+'4'" :name="'student'+student.id"
                                                      class="md-primary"> تاخیر با عذر
                                            </md-radio>
                                        </md-theme>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                            <tfoot>
                            <td colspan="2">
                                <br>
                                <div class="form-group">
                                    <div class="col-sm-offset-1 col-sm-11">
                                        <button type="submit" class="btn btn-success pull-left">ثبت</button>
                                    </div>
                                </div>
                            </td>

                            </tfoot>
                        </table>

                    </form>
                </div>
            </div>
        </div>

        <div v-if="status == 'show-attendance'">
            <button @click="status = 'select-class-subject'" class="btn btn-danger btn-sm pull-left  btn-sm  hvr-icon-back">بازگشت</button>
            <br><br>
            <div class="box col-xs-12">
                <div class="box-header">
                    <h3 class="box-title">گزارش حضور غیاب درس {{ subject.subject }} - تاریخ : {{ setDate.setDate }}</h3>
                </div>
                <div class="box-body table-responsive">
                    <table class="table table-bordered">
                        <tbody>
                        <tr>
                            <th style="width: 10px">#</th>
                            <th>نام دانش آموز</th>
                            <th>وضعیت</th>
                        </tr>
                        <tr v-for="att in orderedAttendances">
                            <td></td>
                            <td>
                                <md-avatar>
                                    <img :src="att.attach.img" alt="Avatar">
                                </md-avatar>&ensp;
                                <span v-text="att.attach.fullname"></span>
                            </td>
                            <td v-text="att.attach.status_name"></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</template>

<script>

    export default {
        data() {
            return {
                classes: {},
                clase: '',
                setDate: {'setDate': ''},
                subjects: {},
                subject: '',
                students: {},
                showDo: 'do',
                attendance: [],
                getAttendances: {},
                status: 'select-class-subject'
            }
        },
        created: function () {
            this.getClasses();
        },
        computed: {
            orderedAttendances: function () {
                return _.orderBy(this.getAttendances, 'attach.fullname', 'asc');
            },
        },
        methods: {
            getClasses() {
                var vm = this;
                vm.waiting(true);
                Vue.nextTick(() => {
                    this.errors.clear()
                })
                this.ValidateMsg();
                axios.get('/api/attendance/get-classes').then(function (response) {
                    Vue.set(vm.$data, 'classes', response.data);
                    vm.waiting(false);
                });
            },
            getSubjects() {
                var vm = this;
                axios.post('/api/attendance/get-subjects', vm.clase).then(function (response) {
                    Vue.set(vm.$data, 'subjects', response.data);
                });
            },
            getStudents() {
                var vm = this;
                vm.waiting(true);
                vm.attendance = [];
                this.$validator.validateAll({
                    check_class: vm.clase,
                    check_subject: vm.subject,
                    check_date: vm.setDate.setDate
                }).then(function (validate) {
                    if (validate) {
                        axios.post('/api/attendance/get-students', vm.clase).then(function (response) {
                            Vue.set(vm.$data, 'students', response.data);
                            if (vm.showDo == 'show') {
                                axios.post('/api/attendance/get-attendances', {
                                    'class_id': vm.clase.id,
                                    'subject_id': vm.subject.id,
                                    'date': vm.setDate.setDate
                                }).then(function (res) {
                                    Vue.set(vm.$data, 'getAttendances', res.data);
                                    vm.status = 'show-attendance';
                                });
                            }
                            if (vm.showDo == 'do') {
                                for (var student of response.data) {
                                    var obj = {
                                        cuy_id: student.cuy_id,
                                        subject_id: vm.subject.id,
                                        date: vm.setDate.setDate,
                                        status: '',
                                    }
                                    vm.attendance.push(obj);
                                }
                                vm.status = 'do-attendance'
                                vm.waiting(false);
                            }

                        });
                    }

                }).catch(function () {
                });
            },
            SelectAttend(status) {
                for (var atten of this.attendance) {
                    atten.status = status;
                }
            },
            StoreAttendance() {
                var vm = this;
                vm.waiting(true);
                axios.post('/api/store-attendance', {
                    'students': vm.attendance,
                    'subject_id': vm.subject.id,
                    'date': vm.setDate.setDate
                }).then(function (response) {
                    if (response.status == 200) {
                        noty({
                            type: 'success',
                            layout: 'topLeft',
                            text: 'حضور غیاب با موفقیت انجام شد.',
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
            },
            ValidateMsg(){
                this.$validator.updateDictionary({
                    'fa': {
                        custom: {
                            check_class: {
                                required: 'کلاس را انتخاب کنید.'
                            },
                            check_subject: {
                                required: 'درس را انتخاب کنید.',
                            },
                            check_date: {
                                required: 'تاریخ باید انتخاب شود.',
                                date_format: 'تاریخ باید به فرم "روز/ماه/سال" باشد.'
                            }
                        },

                    }
                });
                this.$validator.setLocale('fa');
            },
            waiting(status) {
                this.$parent.loading = status;
            },
        }
    }
</script>
