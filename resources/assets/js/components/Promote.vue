<template>
    <div>
        <!--PROMOTE-->
        <div v-if="status == 'promote'">
            <button @click="status = 'select-class-year'" class="btn btn-danger btn-sm pull-left btn-sm  hvr-icon-back">بازگشت</button>
            <br><br>
            <div class="box col-xs-12">
                <md-table-card>
                    <md-toolbar>
                        <h2 class="md-title">ارتقاء دانش آموزان</h2>
                    </md-toolbar>

                    <table class="table table-bordered">
                        <thead>
                        <th>دانش آموز</th>
                        <th>سال تحصیلی جدید</th>
                        <th>کلاس جدید</th>
                        </thead>
                        <tbody>
                        <tr>
                            <td>
                                <span class="label label-success">انتخاب همه</span>
                            </td>
                            <td>
                                <select
                                        class="form-control bg-success"
                                        v-model="promotion.year_id"
                                        @change="allPromote('year')"
                                >
                                    <option v-for="year in years" :value="year.id">{{ year.year }}</option>
                                </select>
                            </td>
                            <td>
                                <select
                                        class="form-control bg-success"
                                        v-model="promotion.class_id"
                                        @change="allPromote('clase')"
                                >
                                    <option v-for="clase in classes" :value="clase.id">{{ clase.clase }}</option>
                                </select>
                            </td>
                        </tr>
                        <tr v-for="(student,index) in students">
                            <td>
                                <md-avatar>
                                    <img :src="'images/avatars/'+student.photo" alt="Avatar">
                                </md-avatar>
                                <span v-text="student.fullname"></span>
                            </td>
                            <td>
                                <select
                                        class="form-control"
                                        v-model="promote[index].year_id"
                                >
                                    <option v-for="year in years" :value="year.id">{{ year.year }}</option>
                                </select>
                            </td>
                            <td>
                                <select
                                        class="form-control"
                                        v-model="promote[index].class_id"
                                >
                                    <option v-for="clase in classes" :value="clase.id">{{ clase.clase }}</option>
                                </select>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <button class="btn btn-success pull-left" @click="PromoteStudents()">ارتقاء دانش آموزان</button>
                </md-table-card>
            </div>

        </div>

        <!--SELECT CLASS YEAR-->
        <div v-show="status == 'select-class-year'">
            <div class="box col-xs-12">
                <md-card>
                    <md-card-header>
                        <div class="md-title">ارتقاء دانش آموزان</div>
                    </md-card-header>
                    <form novalidate @submit.prevent="getStudents()">
                        <div class="col-sm-2"></div>
                        <div class="col-sm-8">
                            <md-card-content>


                                <div class="form-group" :class="errors.has('check_year')?'has-error':''">
                                    <label for="check_year" class=" control-label">سال تحصیلی </label>
                                    <div>
                                        <select name="check_year"
                                                class="form-control"
                                                v-model="promotion.year_id"
                                                v-validate.initial="'required'"
                                        >
                                            <option v-for="year in years" :value="year.id">{{ year.year }}</option>
                                        </select>

                                        <div v-show="errors.has('check_year')" class="help-block">{{
                                            errors.first('check_year') }}
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group" :class="errors.has('check_class')?'has-error':''">
                                    <label for="check_class" class=" control-label">کلاس </label>
                                    <div>
                                        <select name="check_class"
                                                class="form-control"
                                                v-model="promotion.class_id"
                                                v-validate.initial="'required'"
                                        >
                                            <option v-for="clase in classes" :value="clase.id">{{ clase.clase }}
                                            </option>
                                        </select>

                                        <div v-show="errors.has('check_class')" class="help-block">{{
                                            errors.first('check_class') }}
                                        </div>
                                    </div>
                                </div>

                            </md-card-content>

                            <md-card-actions>
                                <md-button class="md-raised md-primary" type="submit">لیست دانش آموزان</md-button>
                            </md-card-actions>
                        </div>
                    </form>
                    <br><br>
                </md-card>
            </div>
        </div>

    </div>
</template>

<script>
    export default {
        data() {
            return {
                students: {},
                classes: {},
                years: {},
                promote: [],
                promotion: {year_id: '', class_id: ''},
                status: '',
            }
        },
        created: function () {
            this.getClase();
            this.getYears();
        },
        computed: {
            orderedClases: function () {
                var vm = this;
                return _.filter(_.orderBy(this.clases, this.sortBy, this.sortType), function (clase) {
                    return clase.field_name.includes(vm.searchBox) || clase.clase.includes(vm.searchBox);
                });
            },
        },
        methods: {
            getStudents() {
                var vm = this;
                this.$validator.validateAll({
                    check_class: vm.promotion.class_id,
                    check_year: vm.promotion.year_id,
                }).then(function (validate) {
                    if (validate) {
                        vm.waiting(true)

                        axios.post('/api/student/get-promotion-students', vm.promotion).then(function (response) {
                            Vue.set(vm.$data, 'students', response.data);
                            for (var student of response.data) {
                                var obj = {
                                    student_id: student.id,
                                    class_id: vm.promotion.class_id,
                                    year_id: vm.promotion.year_id
                                }
                                vm.promote.push(obj);
                            }
                            vm.status = 'promote';
                            vm.waiting(false)
                        });
                    }

                }).catch(function () {
                });
            },
            allPromote(param){
                if(param == 'clase'){
                    for (var index in this.promote) {
                        this.promote[index].class_id = this.promotion.class_id;
                    }
                }
                if (param == 'year'){
                    for (var student of this.promote) {
                        student.year_id = this.promotion.year_id;
                    }
                }
            },
            PromoteStudents(){
                var vm = this;
                vm.waiting(true)
                axios.post('/api/student/do-promote-students', {students:vm.promote}).then(function (response) {
                    if (response.status == 200) {
                        noty({
                            type: 'success',
                            layout: 'topLeft',
                            text: 'سطح دانش آموزان با موفقیت ارتقاء یافت.',
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
            getClase() {
                var vm = this;
                vm.waiting(true)
                Vue.nextTick(() => {
                    this.errors.clear()
                })
                this.ValidateMsg();
                axios.get('/api/get-clases').then(function (response) {
                    Vue.set(vm.$data, 'classes', response.data);
                    vm.status = 'select-class-year';
                    vm.waiting(false)
                });
            },
            getYears() {
                var vueModel = this;
                axios.get('/api/get-years').then(function (response) {
                    Vue.set(vueModel.$data, 'years', response.data);
                });
            },
            ValidateMsg(){
                this.$validator.updateDictionary({
                    'fa': {
                        custom: {
                            check_class: {
                                required: 'کلاس را انتخاب کنید.',
                            },
                            check_year: {
                                required: 'سال تحصیلی را انتخاب کنید.',
                            },
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
