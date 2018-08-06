<template>
    <div>
        <!--CLASS LIST-->
        <div v-if="status == 'class-list'">
            <md-button class="md-primary md-raised" id="add_clase_modal" @click.native="AddClase()">
                افزودن
            </md-button>
            <br><br>
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
                                            <md-theme md-name="blue">
                                                <md-button class="md-fab md-mini md-primary" id="edit_clase_model"
                                                           @click.native="EditClase(clas)">
                                                    <md-icon>edit</md-icon>
                                                    <md-tooltip md-direction="top">ویرایش کلاس {{ clas.clase }}
                                                    </md-tooltip>
                                                </md-button>
                                            </md-theme>
                                            <md-theme md-name="red">
                                                <md-button @click.native="DelClase(clas.id,index)" class="md-fab md-mini  md-primary">
                                                    <md-icon md-iconset="fa fa-trash"
                                                             class="md-size-2x md-primary"></md-icon>
                                                    <md-tooltip md-direction="top">حذف کلاس {{ clas.clase }}
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
                                <p class="text-center"><span class="text-info"> هیچ کلاسی ثبت نشده است.</span>
                                </p>
                            </div>
                        </div>

                    </div>


                </md-table-card>
            </div>

        </div>

        <!--EDIT CLASS-->
        <div v-show="status == 'edit-class'">
            <div class="col-xs-12">
                <button @click="status = 'class-list'" class="btn btn-danger pull-left  btn-sm  hvr-icon-back">بازگشت</button>
            </div>
            <br><br>
            <div class="col-xs-12">
                <md-card>
                    <md-card-header>
                        <div class="md-title">ویرایش کلاس</div>
                    </md-card-header>
                    <md-card-content>
                        <div class="col-sm-2"></div>
                        <div class="col-sm-8">
                            <form @submit.prevent="UpdateClase()" novalidate class="form-horizontal">
                                <div class="form-group" :class="errors.has('edit_clase')?'has-error':''">
                                    <label for="edit_clase" class="col-sm-2 control-label">کلاس </label>
                                    <div class="col-sm-10">
                                        <input
                                                v-validate.initial="'required|max:15'"
                                                name="edit_clase"
                                                v-model="clase.clase"
                                                class="form-control"
                                                placeholder="کلاس"
                                                type="text"
                                        >
                                        <div v-show="errors.has('edit_clase')" class="help-block">{{ errors.first('edit_clase') }}</div>
                                    </div>
                                </div>

                                <div class="form-group "  :class="errors.has('edit_field')?'has-error':''">
                                    <label for="edit_field" class="col-sm-2 control-label">رشته </label>
                                    <div class="col-sm-10">
                                        <select name="edit_field"
                                                class="form-control"
                                                v-model="clase.field_id"
                                                v-validate.initial="'required'"
                                        >
                                            <option v-for="field in cfields" :value="field.id">{{ field.field }} {{  field.grade_name }}</option>

                                        </select>
                                        <div v-show="errors.has('edit_field')" class="help-block">{{ errors.first('edit_field') }}</div>
                                    </div>
                                </div>

                                <input type="submit" class="btn btn-success pull-left" value="بروزرسانی">
                            </form>
                        </div>

                    </md-card-content>

                </md-card>
            </div>
        </div>

        <!--ADD CLASS-->
        <div v-show="status == 'add-class'">
            <div class="col-xs-12">
                <button @click="status = 'class-list'" class="btn btn-danger pull-left  btn-sm  hvr-icon-back">بازگشت</button>
            </div>
            <br><br>
            <div class="col-xs-12">
                <md-card>
                    <md-card-header>
                        <div class="md-title">افزودن کلاس</div>
                    </md-card-header>
                    <md-card-content>
                        <div class="col-sm-2"></div>
                        <div class="col-sm-8">
                            <form @submit.prevent="StoreClase()" novalidate class="form-horizontal">
                                <div class="form-group" :class="errors.has('add_clase')?'has-error':''">
                                    <label for="add_clase" class="col-sm-2 control-label">کلاس </label>
                                    <div class="col-sm-10">
                                        <input
                                                v-validate.initial="'required|max:15'"
                                                name="add_clase"
                                                v-model="addClase.clase"
                                                class="form-control"
                                                placeholder="کلاس"
                                                type="text"
                                        >
                                        <div v-show="errors.has('add_clase')" class="help-block">{{ errors.first('add_clase') }}</div>
                                    </div>
                                </div>

                                <div class="form-group "  :class="errors.has('add_field')?'has-error':''">
                                    <label for="add_field" class="col-sm-2 control-label">رشته </label>
                                    <div class="col-sm-10">
                                        <select name="add_field"
                                                class="form-control"
                                                v-model="addClase.field_id"
                                                v-validate.initial="'required'"
                                        >
                                            <option v-for="field in cfields" :value="field.id">{{ field.field }} {{  field.grade_name }}</option>

                                        </select>
                                        <div v-show="errors.has('add_field')" class="help-block">{{ errors.first('add_field') }}</div>
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
                cfields: [],
                clases : [],
                clase:{},
                addClase:{'clase':'','field_id':''},
                searchBox: '',
                status: 'class-list',
                sortType: 'asc',
                sortBy: 'name',
            }
        },
        created: function () {
            this.getClase();
            this.getField();
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
            getField() {
                var vueModel = this;
                axios.get('/api/get-fields').then(function (response) {
                    Vue.set(vueModel.$data, 'cfields', response.data);
                });
            },
            getClase() {
                var vm  = this;
                vm.waiting(true);
                axios.get('/api/get-clases').then(function (response) {
                    Vue.set(vm.$data,'clases',response.data);
                    vm.waiting(false);
                });
            },
            EditClase(clas) {
                Vue.set(this.$data, 'clase', clas);
                Vue.nextTick(() => {
                    this.errors.clear()
                });
                this.ValidateMsg();
                this.status = 'edit-class';
            },
            AddClase(){
                Vue.nextTick(() => {
                    this.errors.clear()
                })
                this.ValidateMsg();
                this.status = 'add-class';
            },
            StoreClase() {
                var vm = this;
                this.$validator.validateAll({add_clase:vm.addClase.clase,add_field:vm.addClase.field_id}).then(function (validate) {
                    if(validate){
                        vm.waiting(true)

                        axios.post('/api/store-clase',vm.addClase).then(function (response) {
                            if (response.status == 200) {
                                vm.addClase = {'clase':'','field_id':''};
                                Vue.nextTick(() => {
                                    vm.errors.clear()
                                })
                                vm.ValidateMsg();
                                vm.clases.push(response.data);
                                noty({
                                    type: 'success',
                                    layout: 'topLeft',
                                    text: 'کلاس با موفقیت ذخیره شد.',
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
            UpdateClase() {
                var vm = this;
                this.$validator.validateAll({edit_clase:vm.clase.clase,edit_field:vm.clase.field_id}).then(function (validate) {
                    if(validate){
                        vm.waiting(true)

                        axios.post('/api/update-clase', vm.clase).then(function (response) {
                            if (response.status == 200) {
                                noty({
                                    type: 'success',
                                    layout: 'topLeft',
                                    text: 'اطلاعات کلاس با موفقیت بروز شد.',
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
            DelClase(clase_id,index) {
                var vm = this;
                swal({
                    title: 'حذف کلاس',
                    text: 'از حذف کلاس مطمئن هستید؟',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'بله',
                    cancelButtonText: 'خیر'
                }).then(function () {
                    vm.waiting(true)
                    axios.post('/api/delete-clase', {clase_id: clase_id}).then(function (response) {
                        if (response.status == 200) {
                            Vue.delete(vm.clases, index);

                            noty({
                                type: 'success',
                                layout: 'topLeft',
                                text: 'کلاس با موفقیت حذف شد.',
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
                    'fa':{
                        custom:{
                            add_clase:{
                                required: ' اسم کلاس نباید خالی باشد.',
                                max: 'اسم کلاس نباید بیشتر از 15 حرف باشد.'
                            },
                            add_field:{
                                required: 'رشته را انتخاب کنید.'
                            },
                            edit_clase:{
                                required: ' اسم کلاس نباید خالی باشد.',
                                max: 'اسم کلاس نباید بیشتر از 15 حرف باشد.'
                            },
                            edit_field:{
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
