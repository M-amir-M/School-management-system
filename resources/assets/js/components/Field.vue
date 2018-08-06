<template>
    <div>
        <div v-if="status == 'field-list'">
            <md-button class="md-primary md-raised"  @click.native="AddField()">
                افزودن
            </md-button>
            <br><br>
            <div class="box col-xs-12">
                <md-table-card>
                    <md-toolbar>
                        <h1 class="md-title">رشته ها</h1>
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
                                        <md-table-head md-sort-by="field">رشته</md-table-head>
                                        <md-table-head md-sort-by="grade_name">پایه</md-table-head>
                                        <md-table-head>
                                            <span>عملیات</span>
                                        </md-table-head>
                                    </md-table-row>
                                </md-table-header>

                                <md-table-body>
                                    <md-table-row v-for="(field1, index) in orderedFeilds" :key="index"
                                                  :md-item="field1">
                                        <md-table-cell>{{ field1.field }}</md-table-cell>
                                        <md-table-cell>{{ field1.grade_name }}</md-table-cell>
                                        <md-table-cell>
                                            <md-theme md-name="blue">
                                                <md-button class="md-fab md-mini md-primary" id="edit_field_model"
                                                           @click.native="EditField(field1)">
                                                    <md-icon>edit</md-icon>
                                                    <md-tooltip md-direction="top">ویرایش رشته {{ field1.field }}
                                                    </md-tooltip>
                                                </md-button>
                                            </md-theme>
                                            <md-theme md-name="red">
                                                <md-button @click.native="DelField(field1.id,index)" class="md-fab md-mini  md-primary">
                                                    <md-icon md-iconset="fa fa-trash"
                                                             class="md-size-2x md-primary"></md-icon>
                                                    <md-tooltip md-direction="top">حذف رشته {{ field1.field }}
                                                    </md-tooltip>
                                                </md-button>
                                            </md-theme>

                                        </md-table-cell>
                                    </md-table-row>
                                </md-table-body>
                            </md-table>
                        </table>

                        <div v-if="cfields.length < 1" class="panel panel-default empty-table-msg">
                            <div class="panel-body">
                                <p class="text-center"><span class="text-info"> هیچ رشته ای ثبت نشده است.</span>
                                </p>
                            </div>
                        </div>

                    </div>

                </md-table-card>
            </div>

        </div>

        <!--EDIT FIELD-->
        <div v-show="status == 'edit-field'">
            <div class="col-xs-12">
                <button @click="status = 'field-list'" class="btn btn-danger pull-left  btn-sm  hvr-icon-back">بازگشت</button>
            </div>
            <br><br>
            <div class="col-xs-12">
                <md-card>
                    <md-card-header>
                        <div class="md-title">افزودن رشته</div>
                    </md-card-header>
                    <md-card-content>
                        <div class="col-sm-2"></div>
                        <div class="col-sm-8">
                            <form @submit.prevent="UpdateField()" novalidate class="form-horizontal">
                                <div class="form-group" :class="errors.has('edit_field')?'has-error':''">
                                    <label for="edit_field" class="col-sm-2 control-label">رشته </label>
                                    <div class="col-sm-10">
                                        <input
                                                v-validate.initial="'required|max:15'"
                                                name="edit_field"
                                                v-model="field.field"
                                                class="form-control"
                                                placeholder="رشته"
                                                type="text"
                                        >
                                        <div v-show="errors.has('edit_field')" class="help-block">{{ errors.first('edit_field') }}</div>
                                    </div>
                                </div>

                                <div class="form-group "  :class="errors.has('edit_grade')?'has-error':''">
                                    <label for="edit_grade" class="col-sm-2 control-label">پایه </label>
                                    <div class="col-sm-10">
                                        <select name="edit_grade"
                                                class="form-control"
                                                v-model="field.grade_id"
                                                v-validate.initial="'required'"
                                        >
                                            <option v-for="grade in grades" :value="grade.id" v-text="grade.grade"></option>

                                        </select>
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

        <!--ADD FIELD-->
        <div v-show="status == 'add-field'">
            <div class="col-xs-12">
                <button @click="status = 'field-list'" class="btn btn-danger pull-left  btn-sm  hvr-icon-back">بازگشت</button>
            </div>
            <br><br>
            <div class="col-xs-12">
                <md-card>
                    <md-card-header>
                        <div class="md-title">افزودن رشته</div>
                    </md-card-header>
                    <md-card-content>
                        <div class="col-sm-2"></div>
                        <div class="col-sm-8">
                            <form @submit.prevent="StoreField()" novalidate class="form-horizontal">
                                <div class="form-group" :class="errors.has('add_field')?'has-error':''">
                                    <label for="add_field" class="col-sm-2 control-label">رشته </label>
                                    <div class="col-sm-10">
                                        <input
                                                v-validate.initial="'required|max:15'"
                                                name="add_field"
                                                v-model="addField.field"
                                                class="form-control"
                                                placeholder="رشته"
                                                type="text"
                                        >
                                        <div v-show="errors.has('add_field')" class="help-block">{{ errors.first('add_field') }}</div>
                                    </div>
                                </div>

                                <div class="form-group "  :class="errors.has('add_grade')?'has-error':''">
                                    <label for="add_grade" class="col-sm-2 control-label">پایه </label>
                                    <div class="col-sm-10">
                                        <select name="add_grade"
                                                class="form-control"
                                                v-model="addField.grade_id"
                                                v-validate.initial="'required'"
                                        >
                                            <option v-for="grade in grades" :value="grade.id" v-text="grade.grade"></option>

                                        </select>
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

    </div>

</template>

<script>
    export default {
        data() {
            return {
                grades: [],
                cfields : [],
                field:{},
                addField:{'field':'','grade_id':''},
                searchBox: '',
                status: 'field-list',
                sortType: 'asc',
                sortBy: 'name'
            }
        },
        created: function () {
            this.getField();
            this.getGrade();
        },
        computed: {
            orderedFeilds: function () {
                var vm = this;
                return _.filter(_.orderBy(this.cfields, this.sortBy, this.sortType), function (field) {
                    return field.grade_name.includes(vm.searchBox) || field.field.includes(vm.searchBox);
                });
            },
        },
        methods: {
            getGrade() {
                var vueModel = this;
                axios.get('/api/get-grades').then(function (response) {
                    Vue.set(vueModel.$data, 'grades', response.data);
                });
            },
            getField() {
                var vm  = this;
                vm.waiting(true)
                axios.get('/api/get-fields').then(function (response) {
                    Vue.set(vm.$data,'cfields',response.data);
                    vm.waiting(false)
                });
            },
            StoreField() {
                var vm = this;
                this.$validator.validateAll({add_field:vm.addField.field,add_grade:vm.addField.grade_id}).then(function (validate) {
                    if(validate){
                        vm.waiting(true)

                        axios.post('/api/store-field',vm.addField).then(function (response) {
                            vm.addField = {'field':'','grade_id':''};
                            if (response.status == 200) {
                                vm.cfields.push(response.data);
                                Vue.nextTick(() => {
                                    vm.errors.clear()
                                })
                                vm.ValidateMsg();
                                noty({
                                    type: 'success',
                                    layout: 'topLeft',
                                    text: 'رشته با موفقیت ذخیره شد.',
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
            EditField(field) {
                Vue.set(this.$data, 'field', field);
                Vue.nextTick(() => {
                    this.errors.clear()
                });
                this.ValidateMsg();
                this.status = 'edit-field';
            },
            AddField(){
                Vue.nextTick(() => {
                    this.errors.clear()
                })
                this.ValidateMsg();
                this.status = 'add-field';
            },
            UpdateField() {
                var vm = this;
                this.$validator.validateAll({edit_field:vm.field.field,edit_grade:vm.field.grade_id}).then(function (validate) {
                    if(validate){
                        vm.waiting(true)

                        axios.post('/api/update-field', vm.field).then(function (response) {
                            if (response.status == 200) {
                                console.log(vm.field);
                                noty({
                                    type: 'success',
                                    layout: 'topLeft',
                                    text: 'اطلاعات رشته با موفقیت بروز شد.',
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
            DelField(field_id,index) {
                var vm = this;
                swal({
                    title: 'حذف رشته',
                    text: 'از حذف رشته مطمئن هستید؟',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'بله',
                    cancelButtonText: 'خیر'
                }).then(function () {

                    vm.waiting(true)
                    axios.post('/api/delete-field', {field_id: field_id}).then(function (response) {
                        if (response.status == 200) {
                            vm.cfields.pop(index);
                            noty({
                                type: 'success',
                                layout: 'topLeft',
                                text: 'رشته با موفقیت حذف شد.',
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
                            add_field:{
                                required: ' اسم رشته نباید خالی باشد.',
                                max: 'اسم رشته نباید بیشتر از 15 حرف باشد.'
                            },
                            add_grade:{
                                required: 'پایه را انتخاب کنید.'
                            },
                            edit_field:{
                                required: ' اسم رشته نباید خالی باشد.',
                                max: 'اسم رشته نباید بیشتر از 15 حرف باشد.'
                            },
                            edit_grade:{
                                required: 'پایه را انتخاب کنید.'
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
