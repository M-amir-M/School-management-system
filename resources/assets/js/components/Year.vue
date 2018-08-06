<template>
    <div>
        <md-button class="md-primary md-raised" id="add_year_modal" @click.native="AddYear()">
            افزودن
        </md-button>
        <br><br>
        <div class="box col-xs-12">
            <md-table-card>
                <md-toolbar>
                    <h1 class="md-title"> سال های تحصیلی</h1>
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
                                    <md-table-head md-sort-by="year">سال تحصیلی</md-table-head>
                                    <md-table-head>
                                        <span>عملیات</span>
                                    </md-table-head>
                                </md-table-row>
                            </md-table-header>

                            <md-table-body>
                                <md-table-row v-for="(year1, index) in orderedYears" :key="index"
                                              :md-item="year1">
                                    <md-table-cell>{{ year1.year }}</md-table-cell>
                                    <md-table-cell>

                                                                                <md-theme md-name="blue">
                                            <md-button class="md-fab md-mini md-primary" id="edit_year_model"
                                                       @click.native="EditYear(year1)">
                                                <md-icon>edit</md-icon>
                                                <md-tooltip md-direction="top">ویرایش سال تحصیلی {{ year1.year }}
                                                </md-tooltip>
                                            </md-button>
                                        </md-theme>
                                        <md-theme md-name="red">
                                            <md-button class="md-fab md-mini  md-primary" @click.native="DelYear(year1.id,index)">
                                                <md-icon md-iconset="fa fa-trash"
                                                         class="md-size-2x md-primary"></md-icon>
                                                <md-tooltip md-direction="top">حذف سال تحصیلی {{ year1.year }}
                                                </md-tooltip>
                                            </md-button>
                                        </md-theme>

                                    </md-table-cell>
                                </md-table-row>
                            </md-table-body>
                        </md-table>
                    </table>

                    <div v-if="years.length < 1" class="panel panel-default empty-table-msg">
                        <div class="panel-body">
                            <p class="text-center"><span class="text-info"> هیچ سال تحصیلی ثبت نشده است.</span>
                            </p>
                        </div>
                    </div>
                </div>

            </md-table-card>
        </div>


        <!--EDIT MODAL-->
        <md-dialog md-open-from="#edit_year_model" md-close-to="#edit_year_model" ref="edit_year_model">
            <md-dialog-title>ویرایش سال تحصیلی {{ year.year }}</md-dialog-title>
            <md-dialog-content>
                <form @submit.prevent="UpdateYear()" id="edit_year_form" novalidate>
                    <div class="form-group" :class="errors.has('edit_year')?'has-error':''">
                        <label for="edit_year" class="col-sm-2 control-label">سال تحصیلی </label>
                        <div class="col-sm-10">
                            <input
                                    v-validate.initial="'required|max:15'"
                                    name="edit_year"
                                    v-model="year.year"
                                    class="form-control"
                                    placeholder="سال تحصیلی"
                                    type="text"
                            >
                            <div v-show="errors.has('edit_year')" class="help-block">{{ errors.first('edit_year') }}</div>
                        </div>
                    </div>
                </form>
            </md-dialog-content>

            <md-dialog-actions>
                <md-button class="md-primary md-raised" @click.native="closeDialog('edit_year_model')">لغو</md-button>
                <md-button type="submit" form="edit_year_form" class="md-primary md-raised">بروزرسانی
                </md-button>
            </md-dialog-actions>

        </md-dialog>

        <!--ADD MODAL-->
        <md-dialog md-open-from="#add_year_modal" md-close-to="#add_year_modal" ref="add_year_modal">
            <md-dialog-title>افزودن سال تحصیلی</md-dialog-title>
            <md-dialog-content>
                <form @submit.prevent="StoreYear()" id="add_year_form" novalidate>
                    <div class="form-group" :class="errors.has('add_year')?'has-error':''">
                        <label for="add_year" class="col-sm-2 control-label">سال تحصیلی </label>
                        <div class="col-sm-10">
                            <input
                                    v-validate.initial="'required|max:15'"
                                    name="add_year"
                                    v-model="addYear.year"
                                    class="form-control"
                                    placeholder="سال تحصیلی"
                                    type="text"
                            >
                            <div v-show="errors.has('add_year')" class="help-block">{{ errors.first('add_year') }}</div>
                        </div>
                    </div>
                </form>
            </md-dialog-content>
            <md-dialog-actions>
                <md-button class="md-primary md-raised" @click.native="closeDialog('add_year_modal')">لغو</md-button>
                <md-button type="submit" form="add_year_form" class="md-primary md-raised">ذخیره
                </md-button>
            </md-dialog-actions>

        </md-dialog>
    </div>
</template>

<script>
    export default {
        data(){
            return {
                years: [],
                year: {},
                addYear:{'year':''},
                searchBox: '',
                sortType: 'asc',
                sortBy: 'name'
            }
        },
        created: function () {
            this.getYears();
        },
        computed: {
            orderedYears: function () {
                var vm = this;
                return _.filter(_.orderBy(this.years, this.sortBy, this.sortType), function (year) {
                    return year.year.includes(vm.searchBox);
                });
            },
        },
        methods: {
            getYears() {
                var vueModel = this;
                vueModel.waiting(true)
                axios.get('/api/get-years').then(function (response) {
                    Vue.set(vueModel.$data, 'years', response.data);
                    vueModel.waiting(false)
                });
            },
            StoreYear() {
                var vm = this;
                this.$validator.validateAll({add_year:vm.addYear.year}).then(function (validate) {
                    if(validate){
                        vm.waiting(true)

                        axios.post('/api/store-year',vm.addYear).then(function (response) {
                            vm.addYear = {'year':''};
                            if (response.status == 200) {
                                vm.years.push(response.data)
                                noty({
                                    type: 'success',
                                    layout: 'topLeft',
                                    text: 'سال تحصیلی با موفقیت ذخیره شد.',
                                    timeout: 3000,
                                });
                                vm.closeDialog('add_year_model');
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
            EditYear(year) {
                Vue.set(this.$data, 'year', year);
                Vue.nextTick(() => {
                    this.errors.clear()
                });
                this.ValidateMsg();
                this.openDialog('edit_year_model');
            },
            AddYear(){
                Vue.nextTick(() => {
                    this.errors.clear()
                })
                this.ValidateMsg();
                this.openDialog('add_year_modal')
            },
            UpdateYear() {
                var vm = this;
                this.$validator.validateAll({edit_year:vm.year.year}).then(function (validate) {
                    if(validate){
                        vm.waiting(true)

                        axios.post('/api/update-year', vm.year).then(function (response) {
                            if (response.status == 200) {
                                noty({
                                    type: 'success',
                                    layout: 'topLeft',
                                    text: 'سال تحصیلی با موفقیت تغییر شد.',
                                    timeout: 3000,
                                });
                                vm.closeDialog('edit_year_model');
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
            DelYear(year_id,index) {
                var vm = this;
                vm.waiting(true)
                axios.post('/api/delete-year', {year_id: year_id}).then(function (response) {
                    if (response.status == 200) {
                        vm.years.pop(index);
                        noty({
                            type: 'success',
                            layout: 'topLeft',
                            text: 'سال تحصیلی با موفقیت حذف شد.',
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
            ValidateMsg(){
                this.$validator.updateDictionary({
                    'fa':{
                        custom:{
                            add_year:{
                                required: ' سال تحصیلی نباید خالی باشد.',
                                max: 'سال تحصیلی نباید بیشتر از 15 حرف باشد.'
                            },
                            edit_year:{
                                required: ' سال تحصیلی نباید خالی باشد.',
                                max: 'سال تحصیلی نباید بیشتر از 15 حرف باشد.'
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
