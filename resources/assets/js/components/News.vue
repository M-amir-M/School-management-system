<template>
    <div>

        <!--NEWSES LISTS-->
        <div v-if="status =='show-newses'">
            <md-button  v-if="auth.role == 'admin'" class="md-primary md-raised" @click.native="AddNews()">
                افزودن
            </md-button>
            <br><br>
            <div class="box col-xs-12">
                <md-table-card>
                    <md-toolbar>
                        <h1 class="md-title">اخبار</h1>
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
                                        <md-table-head>عنوان</md-table-head>
                                        <md-table-head>مخاطب</md-table-head>
                                        <md-table-head>نویسنده</md-table-head>
                                        <md-table-head>تاریخ</md-table-head>
                                        <md-table-head v-if="auth.role == 'admin'">
                                            <span>عملیات</span>
                                        </md-table-head>
                                    </md-table-row>
                                </md-table-header>

                                <md-table-body>
                                    <md-table-row v-for="(news1, index) in orderedNewses" :key="index"
                                                  :md-item="news1">
                                        <md-table-cell><button @click="ShowNews(news1)" class="btn-link">{{ news1.title }}</button></md-table-cell>
                                        <md-table-cell>{{ news1.attach.target_name }}</md-table-cell>
                                        <md-table-cell>{{ news1.author }}</md-table-cell>
                                        <md-table-cell>{{ news1.attach.date }}</md-table-cell>
                                        <md-table-cell v-if="auth.role == 'admin'">
                                            <md-theme md-name="blue">
                                                <md-button class="md-fab md-mini md-primary" id="edit_clase_model"
                                                           @click.native="EditNews(news1)">
                                                    <md-icon>edit</md-icon>
                                                    <md-tooltip md-direction="top">ویرایش خبر {{ news1.title }}
                                                    </md-tooltip>
                                                </md-button>
                                            </md-theme>
                                            <md-theme md-name="red">
                                                <md-button class="md-fab md-mini  md-primary">
                                                    <md-icon md-iconset="fa fa-trash"
                                                             class="md-size-2x md-primary"></md-icon>
                                                    <md-tooltip md-direction="top">حذف خبر {{ news1.title }}
                                                    </md-tooltip>
                                                </md-button>
                                            </md-theme>

                                        </md-table-cell>
                                    </md-table-row>
                                </md-table-body>
                            </md-table>
                        </table>

                        <div v-if="newses.length < 1" class="panel panel-default empty-table-msg">
                            <div class="panel-body">
                                <p class="text-center"><span class="text-info"> هیچ خبری ثبت نشده است.</span>
                                </p>
                            </div>
                        </div>

                    </div>


                </md-table-card>
            </div>
        </div>

        <!--SHOW NEWSES-->
        <div v-if="status == 'show-news'">
            <div class="box col-xs-12">
                <button class="btn btn-danger btn-sm  hvr-icon-back" @click="status = 'show-newses'">
                    بازگشت
                </button>
                <br>
                <md-card>
                    <md-card-content v-html="news.text">

                    </md-card-content>
                </md-card>
            </div>
        </div>

        <!--ADD NEWS-->
        <div v-show="status =='add-news'">
            <md-button class="md-primary md-raised" id="add_clase_modal" @click.native="status = 'show-newses'">
                برگشت
            </md-button>
            <br><br>
            <md-card>
                <div class="col-xs-12">
                    <form @submit.prevent="StoreNews()">

                        <div class="form-group" :class="errors.has('add_title')?'has-error':''">
                            <label for="add_title" class=" control-label">عنوان خبر </label>
                            <div class="">
                                <input
                                        v-validate.initial="'required|max:70'"
                                        name="add_title"
                                        v-model="addNews.title"
                                        class="form-control"
                                        placeholder="عنوان خبر"
                                        type="text"
                                >
                                <div v-show="errors.has('add_title')" class="help-block">{{
                                    errors.first('add_title') }}
                                </div>
                            </div>
                        </div>

                        <div class="form-group" :class="errors.has('add_content')?'has-error':''">
                            <label for="add_content" class=" control-label">متن خبر </label>
                            <div class="">
                                <ckeditor
                                        v-validate.initial="'required'"
                                        data-vv-name="add_content"
                                        v-model="addNews.text"
                                        :config="ckconfig"
                                ></ckeditor>
                                <div v-show="errors.has('add_content')" class="help-block">{{
                                    errors.first('add_content') }}
                                </div>
                            </div>
                        </div>

                        <md-button type="submit" class="md-primary md-raised">دخیره</md-button>

                    </form>
                </div>
            </md-card>
        </div>

        <!--EDIT NEWS-->
        <div v-show="status =='edit-news'">
            <button class="btn btn-danger btn-sm  hvr-icon-back" @click="status = 'show-newses'">
                بازگشت
            </button>
            <br><br>
            <md-card>
                <div class="col-xs-12">

                    <form @submit.prevent="UpdateNews()">
                        <div class="form-group" :class="errors.has('edit_title')?'has-error':''">
                            <label for="edit_title" class=" control-label">عنوان خبر </label>
                            <div class="">
                                <input
                                        v-validate.initial="'required|max:70'"
                                        name="edit_title"
                                        v-model="news.title"
                                        class="form-control"
                                        placeholder="عنوان خبر"
                                        type="text"
                                >
                                <div v-show="errors.has('edit_title')" class="help-block">{{
                                    errors.first('edit_title') }}
                                </div>
                            </div>
                        </div>

                        <div class="form-group" :class="errors.has('edit_content')?'has-error':''">
                            <label for="edit_content" class=" control-label">متن خبر </label>
                            <div class="">
                                <ckeditor
                                        v-validate.initial="'required'"
                                        data-vv-name="edit_content"
                                        v-model="news.text"
                                        :config="ckconfig"
                                ></ckeditor>
                                <div v-show="errors.has('edit_content')" class="help-block">{{
                                    errors.first('edit_content') }}
                                </div>
                            </div>
                        </div>

                        <md-button type="submit" class="md-primary md-raised">دخیره</md-button>

                    </form>
                </div>
            </md-card>
        </div>

    </div>
</template>

<script>
    import Ckeditor from 'vue-ckeditor2';

    export default {
        components: {
            Ckeditor
        },
        props:['authUser'],
        data() {
            return {
                ckconfig: {
                    toolbar: [
                        {
                            name: 'document',
                            items: ['Source', '-', 'Save', 'NewPage', 'Preview', 'Print', '-', 'Templates']
                        },
                        {
                            name: 'clipboard',
                            items: ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo']
                        },
                        {name: 'editing', items: ['Find', 'Replace', '-', 'SelectAll', '-', 'Scayt']},
                        {
                            name: 'forms',
                            items: ['Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField']
                        },
                        '/',
                        {
                            name: 'basicstyles',
                            items: ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'CopyFormatting', 'RemoveFormat']
                        },
                        {
                            name: 'paragraph',
                            items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl', 'Language']
                        },
                        {name: 'links', items: ['Link', 'Unlink', 'Anchor']},
                        {
                            name: 'insert',
                            items: ['Image', 'Flash', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak', 'Iframe']
                        },
                        '/',
                        {name: 'styles', items: ['Styles', 'Format', 'Font', 'FontSize']},
                        {name: 'colors', items: ['TextColor', 'BGColor']},
                        {name: 'tools', items: ['Maximize', 'ShowBlocks']},
                        {name: 'about', items: ['About']}
                    ],
                    uiColor: '#9AB8F3',
                    language: 'fa'
                },
                newses: [],
                news: {},
                addNews: {'title': '', 'text': '', 'target': 'all'},
                searchBox: '',
                sortType: 'asc',
                sortBy: 'name',
                status: 'show-newses',
            }
        },
        created: function () {
            this.auth = JSON.parse(this.authUser);
            this.getNews();
        },
        computed: {
            orderedNewses: function () {
                var vm = this;
                return _.filter(_.orderBy(this.newses, this.sortBy, this.sortType), function (news) {
                    return news.title.includes(vm.searchBox) || news.text.includes(vm.searchBox);
                });
            },
        },
        methods: {
            getNews() {
                var vm = this;
                vm.waiting(true)
                axios.get('/api/get-news').then(function (response) {
                    Vue.set(vm.$data, 'newses', response.data);
                    vm.waiting(false)
                });
            },
            ShowNews(news){
                Vue.set(this.$data,'news',news);
                this.status = 'show-news';

            },
            AddNews(){
                this.addNews = {'title': '', 'text': '', 'target': 'all'};
                Vue.nextTick(() => {
                    this.errors.clear()
                })
                this.ValidateMsg();
                this.status = 'add-news';
            },
            StoreNews() {
                var vm = this;
                this.$validator.validateAll({
                    add_title: vm.addNews.title,
                    add_content: vm.addNews.text,
                }).then(function (validate) {
                    if (validate) {
                        vm.waiting(true)

                        axios.post('/api/store-news', vm.addNews).then(function (response) {
                            if (response.status == 200) {
                                vm.newses.push(response.data);
                                noty({
                                    type: 'success',
                                    layout: 'topLeft',
                                    text: 'خبر با موفقیت ذخیره شد.',
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
            EditNews(news) {
                Vue.set(this.$data, 'news', news);
                Vue.nextTick(() => {
                    this.errors.clear()
                })
                this.ValidateMsg();
                this.status = 'edit-news';
            },
            UpdateNews() {
                var vm = this;
                this.$validator.validateAll({
                    edit_title: vm.news.title,
                    edit_content: vm.news.text,
                }).then(function (validate) {
                    if (validate) {
                        vm.waiting(true)

                        axios.post('/api/update-news', vm.news).then(function (response) {
                            if (response.status == 200) {
                                noty({
                                    type: 'success',
                                    layout: 'topLeft',
                                    text: 'خبر با موفقیت بروز شد.',
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
            DelNews(news_id, index) {
                var vm = this;
                swal({
                    title: 'حذف خبر',
                    text: 'از حذف خبر مطمئن هستید؟',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'بله',
                    cancelButtonText: 'خیر'
                }).then(function () {

                    vm.waiting(true)
                    axios.post('/api/delete-news', {news_id: news_id}).then(function (response) {
                        if (response.status == 200) {
                            Vue.delete(vm.newses, index);
                            noty({
                                type: 'success',
                                layout: 'topLeft',
                                text: 'خبر با موفقیت حذف شد.',
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
                            add_title: {
                                required: 'عنوان خبر نباید خالی باشد.',
                                max: 'عنوان خبر نباید بیشتر از 70 حرف باشد.'
                            },
                            add_content: {
                                required: 'متن خبر را بنویسید.',
                            },
                            edit_title: {
                                required: 'عنوان خبر نباید خالی باشد.',
                                max: 'عنوان خبر نباید بیشتر از 70 حرف باشد.'
                            },
                            edit_content: {
                                required: 'متن خبر را بنویسید.',
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
