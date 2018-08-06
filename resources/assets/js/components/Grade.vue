<template>
    <div>
        <div v-if="status == 'grade-list'">
            <md-button class="md-primary md-raised" @click.native="AddGrade()">
                افزودن
            </md-button>
            <br><br>
            <div class="box col-xs-12">
                <md-table-card>
                    <md-toolbar>
                        <h1 class="md-title">پایه ها</h1>
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
                                        <md-table-head md-sort-by="grade">پایه</md-table-head>
                                        <md-table-head>
                                            <span>عملیات</span>
                                        </md-table-head>
                                    </md-table-row>
                                </md-table-header>
                                <md-table-body>
                                    <md-table-row v-for="(grade1, index) in orderedGrades" :key="index"
                                                  :md-item="grade1">
                                        <md-table-cell>{{ grade1.grade }}</md-table-cell>
                                        <md-table-cell>
                                            <md-theme md-name="blue">
                                                <md-button class="md-fab md-mini md-primary"
                                                           @click.native="EditGrade(grade1)">
                                                    <md-icon>edit</md-icon>
                                                    <md-tooltip md-direction="top">ویرایش کلاس {{ grade1.grade }}
                                                    </md-tooltip>
                                                </md-button>
                                            </md-theme>
                                            <md-theme md-name="red">
                                                <md-button class="md-fab md-mini  md-primary">
                                                    <md-icon md-iconset="fa fa-trash"
                                                             class="md-size-2x md-primary"></md-icon>
                                                    <md-tooltip md-direction="top">حذف کلاس {{ grade1.grade }}
                                                    </md-tooltip>
                                                </md-button>
                                            </md-theme>

                                        </md-table-cell>
                                    </md-table-row>
                                </md-table-body>
                            </md-table>
                        </table>

                        <div v-if="grades.length < 1" class="panel panel-default empty-table-msg">
                            <div class="panel-body">
                                <p class="text-center"><span class="text-info"> هیچ پایه ای ثبت نشده است.</span>
                                </p>
                            </div>
                        </div>

                    </div>

                </md-table-card>
            </div>
        </div>


        <!--ADD GRADE-->
        <div v-show="status == 'add-grade'">
            <div class="col-xs-12">
                <button @click="status = 'grade-list'" class="btn btn-danger pull-left btn-sm  hvr-icon-back">بازگشت</button>
            </div>
            <br><br>
            <div class="col-xs-12">
                <md-card>
                    <md-card-header>
                        <div class="md-title">افزودن پایه</div>
                    </md-card-header>
                    <md-card-content>
                        <div class="col-sm-2"></div>
                        <div class="col-sm-8">
                            <form @submit.prevent="StoreGrade()" novalidate class="form-horizontal">
                                <div class="form-group" :class="errors.has('add_grade')?'has-error':''">
                                    <label for="add_grade" class="col-sm-2 control-label">پایه </label>
                                    <div class="col-sm-10">
                                        <input
                                                v-validate.initial="'required|max:15'"
                                                name="add_grade"
                                                v-model="addGrade.grade"
                                                class="form-control"
                                                placeholder="پایه"
                                                type="text"
                                        >
                                        <div v-show="errors.has('add_grade')" class="help-block">{{ errors.first('add_grade') }}</div>
                                    </div>
                                </div>

                                <input type="submit" class="btn btn-success pull-left" value="ذخیره">
                            </form>

                        </div>

                    </md-card-content>

                </md-card>
            </div>
        </div>

        <!--EDIT GRADE-->
        <div v-show="status == 'edit-grade'">
            <div class="col-xs-12">
                <button @click="status = 'grade-list'" class="btn btn-danger pull-left btn-sm  hvr-icon-back">بازگشت</button>
            </div>
            <br><br>
            <div class="col-xs-12">
                <md-card>
                    <md-card-header>
                        <div class="md-title">افزودن پایه</div>
                    </md-card-header>
                    <md-card-content>
                        <div class="col-sm-2"></div>
                        <div class="col-sm-8">
                            <form @submit.prevent="UpdateGrade()" novalidate class="form-horizontal">
                                <div class="form-group" :class="errors.has('edit_grade')?'has-error':''">
                                    <label for="edit_grade" class="col-sm-2 control-label">پایه </label>
                                    <div class="col-sm-10">
                                        <input
                                                v-validate.initial="'required|max:15'"
                                                name="edit_grade"
                                                v-model="grade.grade"
                                                class="form-control"
                                                placeholder="پایه"
                                                type="text"
                                        >
                                        <div v-show="errors.has('edit_grade')" class="help-block">{{ errors.first('edit_grade') }}</div>
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
        data(){
            return {
                grades: [],
                grade: {},
                addGrade:{'grade':''},
                searchBox: '',
                status: 'grade-list',
                sortType: 'asc',
                sortBy: 'name'
            }
        },
        created: function () {
            this.getGrades();
        },
        computed: {
            orderedGrades: function () {
                var vm = this;
                return _.filter(_.orderBy(this.grades, this.sortBy, this.sortType), function (grade) {
                    return grade.grade.includes(vm.searchBox);
                });
            },
        },
        methods: {
            getGrades() {
                var vueModel = this;
                vueModel.waiting(true)
                axios.get('/api/get-grades').then(function (response) {
                    Vue.set(vueModel.$data, 'grades', response.data);
                    vueModel.waiting(false);
                });
            },
            StoreGrade() {
                var vm = this;
                this.$validator.validateAll({add_grade:vm.addGrade.grade}).then(function (validate) {
                    if(validate){
                        vm.waiting(true)

                        axios.post('/api/store-grade',vm.addGrade).then(function (response) {
                            vm.addGrade = {'grade':''};
                            if (response.status == 200) {
                                vm.grades.push(response.data)
                                Vue.nextTick(() => {
                                    vm.errors.clear()
                                })
                                vm.ValidateMsg();
                                noty({
                                    type: 'success',
                                    layout: 'topLeft',
                                    text: 'پایه با موفقیت ذخیره شد.',
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
            EditGrade(grade) {
                Vue.set(this.$data, 'grade', grade);
                Vue.nextTick(() => {
                    this.errors.clear()
                });
                this.ValidateMsg();
                this.status = 'edit-grade';
            },
            AddGrade(){
                Vue.nextTick(() => {
                    this.errors.clear()
                })
                this.ValidateMsg();
                this.status = 'add-grade';
            },
            UpdateGrade() {
                var vm = this;
                this.$validator.validateAll({edit_grade:vm.grade.grade}).then(function (validate) {
                    if(validate){
                        vm.waiting(true)

                        axios.post('/api/update-grade', vm.grade).then(function (response) {
                            if (response.status == 200) {
                                noty({
                                    type: 'success',
                                    layout: 'topLeft',
                                    text: 'اسم پایه با موفقیت تغییر شد.',
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
            DelGrade(grade_id,index) {
                var vm = this;
                swal({
                    title: 'حذف پایه',
                    text: 'از حذف پایه مطمئن هستید؟',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'بله',
                    cancelButtonText: 'خیر'
                }).then(function () {

                    vm.waiting(true)
                    axios.post('/api/delete-grade', {grade_id: grade_id}).then(function (response) {
                        if (response.status == 200) {
                            vm.grades.pop(index);
                            noty({
                                type: 'success',
                                layout: 'topLeft',
                                text: 'پایه با موفقیت حذف شد.',
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
                });
            },
            ValidateMsg(){
                this.$validator.updateDictionary({
                    'fa':{
                        custom:{
                            add_grade:{
                                required: ' اسم پایه نباید خالی باشد.',
                                max: 'اسم پایه نباید بیشتر از 15 حرف باشد.'
                            },
                            edit_grade:{
                                required: ' اسم پایه نباید خالی باشد.',
                                max: 'اسم پایه نباید بیشتر از 15 حرف باشد.'
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
