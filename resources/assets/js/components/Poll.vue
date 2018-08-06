<template>
    <div>
        <!--SHOW POLL-->
        <div v-if="status == 'show-polls'">
            <div class="box col-xs-12">
                <md-button v-if="auth.role == 'admin'" class="md-primary md-raised" @click.native="AddPoll()">
                    افزودن
                </md-button>
                <br><br>
                <md-table-card>
                    <md-toolbar>
                        <h1 class="md-title">لیست نظرسنجی ها</h1>
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
                                        <md-table-head md-sort-by="title">عنوان</md-table-head>
                                        <md-table-head>رای دهندگان</md-table-head>
                                        <md-table-head>وضعیت</md-table-head>
                                        <md-table-head>
                                            <span>عملیات</span>
                                        </md-table-head>
                                    </md-table-row>
                                </md-table-header>

                                <md-table-body>
                                    <md-table-row v-for="(poll1, index) in orderedPolls" :key="index"
                                                  :md-item="poll">
                                        <md-table-cell>{{ poll1.title }}</md-table-cell>
                                        <md-table-cell>{{ poll1.attach.target_name }}</md-table-cell>
                                        <md-table-cell>{{ poll1.attach.status_name }}</md-table-cell>
                                        <md-table-cell>

                                            <md-theme md-name="green">
                                                <md-button class="md-fab md-mini  md-primary"
                                                           @click.native="ResultPoll(poll1)"
                                                >
                                                    <md-icon md-iconset="fa fa-bar-chart"
                                                             class="md-size-2x md-primary"></md-icon>
                                                    <md-tooltip md-direction="top">نتایج نظرسنجی
                                                    </md-tooltip>
                                                </md-button>
                                            </md-theme>

                                            <md-theme v-if="auth.role != 'admin'" md-name="teal">
                                                <md-button class="md-fab md-mini  md-primary"
                                                           @click.native="GetVote(poll1)"
                                                >
                                                    <md-icon md-iconset="fa fa-hand-pointer-o"
                                                             class="md-size-2x md-primary"></md-icon>
                                                    <md-tooltip md-direction="top">رای دادن
                                                    </md-tooltip>
                                                </md-button>
                                            </md-theme>

                                            <md-theme v-if="auth.role == 'admin'" md-name="blue">
                                                <md-button class="md-fab md-mini md-primary"
                                                           @click.native="EditPoll(poll1,index)">
                                                    <md-icon>edit</md-icon>
                                                    <md-tooltip md-direction="top">ویرایش نظرسنجی
                                                    </md-tooltip>
                                                </md-button>
                                            </md-theme>

                                            <md-theme v-if="auth.role == 'admin'" md-name="red">
                                                <md-button @click.native="DelPoll(poll1.id, index)"
                                                           class="md-fab md-mini  md-primary">
                                                    <md-icon md-iconset="fa fa-trash"
                                                             class="md-size-2x md-primary"></md-icon>
                                                    <md-tooltip md-direction="top">حذف نظرسنجی
                                                    </md-tooltip>
                                                </md-button>
                                            </md-theme>

                                        </md-table-cell>
                                    </md-table-row>
                                </md-table-body>
                            </md-table>
                        </table>

                        <div v-if="polls.length < 1" class="panel panel-default empty-table-msg">
                            <div class="panel-body">
                                <p class="text-center"><span class="text-info"> هیچ نظرسنجی ثبت نشده است.</span>
                                </p>
                            </div>
                        </div>
                    </div>


                </md-table-card>
            </div>

        </div>

        <!--RESULT POLL-->
        <div v-if="status == 'show-result'">
            <div class="box col-xs-12">
                <button class="btn btn-danger btn-sm btn-sm pull-left  hvr-icon-back" @click="status = 'show-polls'">
                    برگشت
                </button>
                <br><br>

                <div class="row">
                    <div class="col-sm-3 col-xs-1"></div>
                    <div class="col-sm-6 col-xs-10">
                        <md-card>
                            <md-card-content>
                                <div>عنوان نظرسنجی : <span class="text-primary" v-text="poll.title"></span></div>
                                <div>مخاطبین نظرسنجی : <span class="text-primary"
                                                             v-text="poll.attach.target_name"></span>
                                </div>
                                <div>وضعیت نظرسنجی : <span class="text-primary" v-text="poll.attach.status_name"></span>
                                </div>
                                <div>تعداد کل آرا : <span class="text-primary" v-text="poll.count"></span></div>
                                <br>
                                <div>
                                    <div v-for="(option,index) in options">
                                        <hr>

                                        <span class="label " :class="'label-'+colors[index%colors.length]">{{option.option}}
                                                                    <span class="badge pull-left"
                                                                          :class="'bg-'+colors[index%colors.length]">
                                            {{ option.percent }} %
                                        </span>
                                                                </span>
                                    </div>

                                </div>
                            </md-card-content>
                        </md-card>
                    </div>
                </div>
            </div>
        </div>

        <!--GET VOTE-->
        <div v-show="status == 'get-vote'">
            <div class="box col-xs-12 get-vote">

                <div v-if="objectSize(poll)>0">
                    <md-button class="md-primary md-raised btn-sm  hvr-icon-back" @click.native="status = 'show-polls'">
                        برگشت
                    </md-button>
                    <br><br>

                    <div class="row">
                        <div class="col-sm-3 col-xs-1"></div>
                        <div class="col-sm-6 col-xs-10">
                            <md-card>
                                <md-card-content>
                                    <div>عنوان نظرسنجی : <span class="text-primary" v-text="poll.title"></span></div>
                                    <div>مخاطبین نظرسنجی : <span class="text-primary"
                                                                 v-text="poll.attach.target_name"></span>
                                    </div>
                                    <div>وضعیت نظرسنجی : <span class="text-primary"
                                                               v-text="poll.attach.status_name"></span>
                                    </div>
                                    <div>تعداد کل آرا : <span class="text-primary" v-text="poll.count"></span></div>
                                    <br>
                                    <form @submit.prevent="StoreVote()" novalidate>
                                        <div class="alert alert-success">
                                            <div class="form-group" :class="errors.has('vote')?'has-error':''">
                                                <div v-if="options.length>0">
                                                    <md-theme md-name="red">
                                                        <div v-for="(option,index) in options">
                                                            <md-radio v-validate.initial="'required'"
                                                                      data-vv-name="vote"
                                                                      v-model="getVote.vote"
                                                                      :md-value="option.id"
                                                                      :id="'getvote'+option.id"
                                                                      name="getvote"
                                                                      class="md-primary"> &nbsp {{ option.option }}
                                                            </md-radio>
                                                            <hr>
                                                        </div>
                                                    </md-theme>
                                                </div>

                                                <div v-show="errors.has('vote')" class="help-block">{{
                                                    errors.first('vote') }}
                                                </div>
                                            </div>
                                        </div>
                                        <button class="btn btn-info pull-left" type="submit">ثبت رای</button>
                                    </form>
                                </md-card-content>
                            </md-card>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--EDIT POLL-->
        <div v-show="status == 'edit-poll'">
            <button @click="status = 'show-polls'" class="btn btn-danger pull-left  btn-sm  hvr-icon-back">بازگشت
            </button>
            <br><br>
            <md-card class="col-sm-12">
                <md-card-header>
                    <div class="md-title">ویرایش نظرسنجی</div>
                </md-card-header>
                <div class="row">
                    <div class="col-sm-3 col-xs-1"></div>
                    <div class="col-sm-6 col-xs-10">
                        <form @submit.prevent="UpdatePoll()" id="edit_poll_form">

                            <div class="form-group" :class="errors.has('edit_title')?'has-error':''">
                                <label for="edit_title" class=" control-label">عنوان </label>
                                <div class="">
                                    <input
                                            v-validate.initial="'required|max:70'"
                                            name="edit_title"
                                            v-model="poll.title"
                                            class="form-control"
                                            placeholder="عنوان"
                                            type="text"
                                    >
                                    <div v-show="errors.has('edit_title')" class="help-block">{{
                                        errors.first('edit_title')
                                        }}
                                    </div>
                                </div>
                            </div>

                            <div class="form-group " :class="errors.has('edit_target')?'has-error':''">
                                <label for="edit_target" class=" control-label">مخاطبان </label>
                                <div class="">
                                    <select name="edit_target"
                                            class="form-control"
                                            v-model="poll.target"
                                            v-validate.initial="'required'"
                                    >
                                        <option value="all">همه</option>
                                        <option value="teacher">معلمین</option>
                                        <option value="student">دانش آموزان</option>
                                        <option value="parent">اولیا</option>
                                    </select>
                                    <div v-show="errors.has('edit_target')" class="help-block">{{
                                        errors.first('edit_target')
                                        }}
                                    </div>
                                </div>
                            </div>

                            <div class="form-group" :class="errors.has('edit_options')?'has-error':''">
                                <label for="edit_options" class=" control-label">گزینه </label>
                                <input type="hidden" v-model="options" v-validate.initial="'required'"
                                       data-vv-name="edit_options">
                                <md-card>
                                    <button type="button" @click="openDialog('add_edit_poll_option_modal');"
                                            class="btn btn-primary">افزودن گزینه
                                    </button>
                                </md-card>
                                <div v-show="errors.has('edit_options')" class="help-block">{{
                                    errors.first('edit_options')
                                    }}
                                </div>
                            </div>

                            <div v-for="(option1,index) in options">
                                <md-card>
                                    <md-card-content>
                                    <span>{{ option1.option }}
                                        <button type="button"
                                                class="btn btn-sm btn-danger pull-left"
                                                @click="DelPollOption(option1.id,index)">
                                            <span class="fa fa-trash-o"></span>
                                            <md-tooltip md-direction="top">حذف گزینه {{ option1.option }}
                                            </md-tooltip>
                                        </button>
                                        <button type="button"
                                                class="btn btn-sm btn-info pull-left"
                                                @click="EditEditPollOption(index)">
                                            <span class="fa fa-pencil"></span>
                                            <md-tooltip md-direction="top">ویرایش گزینه {{ option1.option }}
                                            </md-tooltip>
                                        </button>
                                        </span>
                                    </md-card-content>
                                </md-card>
                            </div>

                            <div class="form-group">
                                <md-checkbox md-theme="green" id="edit_activ_poll_checkbox"
                                             name="edit_activ_poll_checkbox"
                                             v-model="poll.status" class="md-primary">نظرسنجی فعال شود
                                </md-checkbox>
                            </div>

                        </form>
                        <md-button form="edit_poll_form" type="submit" class="md-primary md-raised">ذخیره</md-button>
                    </div>
                </div>


            </md-card>

        </div>

        <!--ADD POLL-->
        <div v-show="status == 'add-poll'">
            <button @click="status = 'show-polls'" class="btn btn-danger pull-left  btn-sm  hvr-icon-back">بازگشت
            </button>
            <br><br>
            <md-card class="col-sm-12">
                <md-card-header>
                    <div class="md-title">افزودن نظرسنجی</div>
                </md-card-header>
                <div class="row">
                    <div class="col-sm-3 col-xs-1"></div>
                    <div class="col-sm-6 col-xs-10">
                        <form @submit.prevent="StorePoll()" id="add_poll_form">

                            <div class="form-group" :class="errors.has('add_title')?'has-error':''">
                                <label for="add_title" class=" control-label">عنوان </label>
                                <div class="">
                                    <input
                                            v-validate.initial="'required|max:70'"
                                            name="add_title"
                                            v-model="addPoll.title"
                                            class="form-control"
                                            placeholder="عنوان"
                                            type="text"
                                    >
                                    <div v-show="errors.has('add_title')" class="help-block">{{
                                        errors.first('add_title')
                                        }}
                                    </div>
                                </div>
                            </div>

                            <div class="form-group " :class="errors.has('add_target')?'has-error':''">
                                <label for="add_target" class=" control-label">مخاطبان </label>
                                <div class="">
                                    <select name="add_target"
                                            class="form-control"
                                            v-model="addPoll.target"
                                            v-validate.initial="'required'"
                                    >
                                        <option value="all">همه</option>
                                        <option value="teacher">معلمین</option>
                                        <option value="student">دانش آموزان</option>
                                        <option value="parent">اولیا</option>
                                    </select>
                                    <div v-show="errors.has('add_target')" class="help-block">{{
                                        errors.first('add_target')
                                        }}
                                    </div>
                                </div>
                            </div>

                            <div class="form-group" :class="errors.has('add_options')?'has-error':''">
                                <label for="add_options" class=" control-label">گزینه </label>
                                <input type="hidden" v-model="addPoll.options" v-validate.initial="'required'"
                                       data-vv-name="add_options">
                                <md-card>
                                    <button type="button" @click="openDialog('add_add_poll_option_modal');"
                                            class="btn btn-primary">افزودن گزینه
                                    </button>
                                </md-card>
                                <div v-show="errors.has('add_options')" class="help-block">{{
                                    errors.first('add_options')
                                    }}
                                </div>
                            </div>

                            <div v-for="(option1,index) in addPoll.options">
                                <md-card>
                                    <md-card-content>
                                    <span>{{ addPoll.options[index] }}
                                        <button type="button"
                                                class="btn btn-sm btn-danger pull-left"
                                                @click="DelAddPollOption(index)">
                                            <span class="fa fa-trash-o"></span>
                                            <md-tooltip md-direction="top">حذف گزینه {{ option1 }}
                                            </md-tooltip>
                                        </button>
                                        <button type="button"
                                                class="btn btn-sm btn-info pull-left"
                                                @click="EditAddPollOption(index)">
                                            <span class="fa fa-pencil"></span>
                                            <md-tooltip md-direction="top">ویرایش گزینه {{ option1 }}
                                            </md-tooltip>
                                        </button>
                                        </span>
                                    </md-card-content>
                                </md-card>
                            </div>


                            <div class="form-group">
                                <md-checkbox md-theme="green" id="add_activ_poll_checkbox"
                                             name="add_activ_poll_checkbox"
                                             v-model="addPoll.status" class="md-primary">نظرسنجی فعال شود
                                </md-checkbox>
                            </div>

                        </form>
                        <md-button form="add_poll_form" type="submit" class="md-primary md-raised">ذخیره</md-button>
                    </div>
                </div>

            </md-card>

        </div>

        <!--EDIT EDIT OPTION MODAL-->
        <md-dialog md-open-from="#edit_edit_poll_option_modal" md-close-to="#edit_edit_poll_option_modal"
                   ref="edit_edit_poll_option_modal">
            <md-dialog-title>ویرایش گزینه {{ option.option }}</md-dialog-title>
            <md-dialog-content>
                <form @submit.prevent="UpdatePollOption()" id="edit_edit_poll_option_form">
                    <div class="form-group" :class="errors.has('edit_edit_option')?'has-error':''">
                        <label for="edit_edit_option" class=" control-label">گزینه </label>
                        <input
                                v-validate.initial="'required|max:20'"
                                name="edit_edit_option"
                                v-model="addOption"
                                class="form-control"
                                placeholder="گزینه"
                                type="text"
                        >
                        <div v-show="errors.has('edit_edit_option')" class="help-block">{{
                            errors.first('edit_edit_option') }}
                        </div>
                    </div>
                </form>
            </md-dialog-content>

            <md-dialog-actions>
                <md-button class="md-primary md-raised" @click.native="closeDialog('edit_edit_poll_option_modal')">لغو
                </md-button>
                <md-button type="submit" form="edit_edit_poll_option_form" class="md-primary md-raised"
                           @click.native="closeDialog('edit_edit_poll_option_modal')">بروزرسانی
                </md-button>
            </md-dialog-actions>

        </md-dialog>

        <!--ADD EDIT OPTION MODAL-->
        <md-dialog md-open-from="#add_edit_poll_option_modal" md-close-to="#add_edit_poll_option_modal"
                   ref="add_edit_poll_option_modal">
            <md-dialog-title>افزودن گزینه</md-dialog-title>
            <md-dialog-content>
                <form @submit.prevent="StorePollOption()" id="add_edit_poll_option_form">
                    <div class="form-group" :class="errors.has('edit_option')?'has-error':''">
                        <label for="edit_option" class=" control-label">گزینه </label>
                        <input
                                v-validate.initial="'required|max:20'"
                                name="edit_option"
                                v-model="addOption"
                                class="form-control"
                                placeholder="گزینه"
                                type="text"
                        >
                        <div v-show="errors.has('edit_option')" class="help-block">{{ errors.first('edit_option') }}
                        </div>
                    </div>
                </form>
            </md-dialog-content>

            <md-dialog-actions>
                <md-button class="md-primary md-raised" @click.native="closeDialog('add_edit_poll_option_modal')">لغو
                </md-button>
                <md-button type="submit" form="add_edit_poll_option_form" class="md-primary md-raised"
                           @click.native="closeDialog('add_edit_poll_option_modal')">ذخیره
                </md-button>
            </md-dialog-actions>

        </md-dialog>

        <!--ADD ADD OPTION MODAL-->
        <md-dialog md-open-from="#add_add_poll_option_modal" md-close-to="#add_add_poll_option_modal"
                   ref="add_add_poll_option_modal">
            <md-dialog-title>افزودن گزینه</md-dialog-title>
            <md-dialog-content>
                <form @submit.prevent="AddPollOption()" id="add_add_poll_option_form">

                    <div class="form-group" :class="errors.has('add_option')?'has-error':''">
                        <label for="add_option" class=" control-label">گزینه </label>
                        <input
                                v-validate.initial="'required|max:20'"
                                name="add_option"
                                v-model="addPoll.option"
                                class="form-control"
                                placeholder="گزینه"
                                type="text"
                        >
                        <div v-show="errors.has('add_option')" class="help-block">{{ errors.first('add_option') }}</div>
                    </div>
                </form>
            </md-dialog-content>

            <md-dialog-actions>
                <md-button class="md-primary md-raised" @click.native="closeDialog('add_add_poll_option_modal')">لغو
                </md-button>
                <md-button type="submit" form="add_add_poll_option_form" class="md-primary md-raised">ذخیره
                </md-button>
            </md-dialog-actions>

        </md-dialog>

        <!--EDIT ADD OPTION MODAL-->
        <md-dialog md-open-from="#edit_add_poll_option_modal" md-close-to="#edit_add_poll_option_modal"
                   ref="edit_add_poll_option_modal">
            <md-dialog-title>افزودن گزینه</md-dialog-title>
            <md-dialog-content>
                <form @submit.prevent="SaveAddPollOption()" id="edit_add_poll_option_form">

                    <div class="form-group" :class="errors.has('edit_add_option')?'has-error':''">
                        <label for="add_option" class=" control-label">گزینه </label>
                        <input
                                v-validate.initial="'required|max:20'"
                                name="edit_add_option"
                                v-model="addPoll.option"
                                class="form-control"
                                placeholder="گزینه"
                                type="text"
                        >
                        <div v-show="errors.has('edit_add_option')" class="help-block">{{
                            errors.first('edit_add_option') }}
                        </div>
                    </div>
                </form>
            </md-dialog-content>

            <md-dialog-actions>
                <md-button class="md-primary md-raised" @click.native="closeDialog('edit_add_poll_option_modal')">لغو
                </md-button>
                <md-button type="submit" form="edit_add_poll_option_form" class="md-primary md-raised">ذخیره
                </md-button>
            </md-dialog-actions>

        </md-dialog>

    </div>
</template>

<script>
    export default {
        props: ['authUser'],
        data() {
            return {
                auth: [],
                polls: [],
                poll: {},
                colors: ['success', 'primary', 'danger', 'info', 'warning'],
                getVote: {vote: '', poll_id: ''},
                options: [],
                option: {},
                addOption: '',
                addPoll: {'title': '', 'target': '', 'status': true, 'options': [], 'option': '', 'index': ''},
                status: '',
                searchBox: '',
                sortType: 'asc',
                sortBy: 'name',
            }
        },
        created: function () {
            this.auth = JSON.parse(this.authUser);
            this.getPoll();
        },
        computed: {
            orderedPolls: function () {
                var vm = this;
                return _.filter(_.orderBy(this.polls, this.sortBy, this.sortType), function (poll) {
                    return poll.title.includes(vm.searchBox);
                });
            },
        },
        methods: {
            getPoll: function () {
                var vueModel = this;
                vueModel.waiting(true)
                axios.get('/api/get-polls').then(function (response) {
                    Vue.set(vueModel.$data, 'polls', response.data);
                    vueModel.status = 'show-polls'
                    vueModel.waiting(false)
                });
            },
            getPollOption(poll) {
                var vueModel = this;
                vueModel.poll = poll;
                axios.get('/api/get-poll-options/' + vueModel.poll.id).then(function (response) {
                    Vue.set(vueModel.$data, 'options', response.data);
                });
            },
            StorePoll() {
                var vm = this;
                var options = '';
                if (vm.addPoll.options.length > 0) {
                    options = vm.addPoll.options;
                }
                this.$validator.validateAll({
                    add_title: vm.addPoll.title,
                    add_target: vm.addPoll.target,
                    add_options: options
                }).then(function (validate) {
                    if (validate) {
                        vm.waiting(true)

                        axios.post('/api/store-poll', vm.addPoll).then(function (response) {
                            if (response.status == 200) {
                                vm.addPoll = {'title': '', 'target': '', 'status': true, 'options': []};
                                Vue.nextTick(() => {
                                    vm.errors.clear()
                                })
                                vm.ValidateMsg();
                                vm.polls.push(response.data);
                                noty({
                                    type: 'success',
                                    layout: 'topLeft',
                                    text: 'نظرسنجی با موفقیت ذخیره شد.',
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
            StoreVote() {
                var vm = this;
                vm.getVote.poll_id = vm.poll.id
                this.$validator.validateAll({
                    vote: vm.getVote.vote,
                }).then(function (validate) {
                    if (validate) {
                        axios.post('/api/store-vote', vm.getVote).then(function (response) {
                            if (response.status == 200) {
                                vm.polls.push(response.data);
                                noty({
                                    type: 'success',
                                    layout: 'topLeft',
                                    text: 'رای شما با موفقیت ثبت شد.',
                                    timeout: 3000,
                                });
                                document.getElementById('noty_audio').play();
                            }
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
            StorePollOption() {
                var vm = this;
                this.$validator.validateAll({
                    edit_option: vm.addOption,
                }).then(function (validate) {
                    if (validate) {
                        axios.post('/api/store-poll-option', {
                            option: vm.addOption,
                            poll_id: vm.poll.id
                        }).then(function (response) {
                            if (response.status == 200) {
                                vm.addOption = '';
                                vm.options.push(response.data);
                            }
                        });

                        vm.addPoll.options[vm.addPoll.index] = vm.addPoll.option;
                        vm.closeDialog('edit_add_poll_option_modal');

                    }

                }).catch(function () {
                });

            },
            UpdatePollOption() {
                var vm = this;
                vm.options[vm.addPoll.index].option = vm.addOption;
                this.$validator.validateAll({
                    edit_edit_option: vm.addOption,
                }).then(function (validate) {
                    if (validate) {
                        axios.post('/api/update-poll-option', {
                            option: vm.options[vm.addPoll.index],
                        }).then(function (response) {
                            if (response.status == 200) {
                                vm.addOption = '';
                                vm.options[vm.addPoll.index] = response.data;
                            }
                        });
                        vm.closeDialog('edit_add_poll_option_modal');

                    }

                }).catch(function () {
                });

            },
            AddPollOption () {
                var vm = this;
                this.$validator.validateAll({
                    add_option: vm.addPoll.option,
                }).then(function (validate) {
                    if (validate) {
                        vm.closeDialog('add_add_poll_option_modal');
                        vm.addPoll.options.push(vm.addPoll.option);
                        vm.addPoll.option = '';
                    }

                }).catch(function () {
                });
            },
            EditAddPollOption(index) {
                var vm = this;
                vm.addPoll.option = vm.addPoll.options[index];
                vm.addPoll.index = index;
                vm.openDialog('edit_add_poll_option_modal');
            },
            EditEditPollOption(index) {
                var vm = this;
                vm.addOption = vm.options[index].option;
                vm.addPoll.index = index;
                vm.openDialog('edit_edit_poll_option_modal');
            },
            SaveAddPollOption() {
                var vm = this;
                this.$validator.validateAll({
                    edit_add_option: vm.addPoll.option,
                }).then(function (validate) {
                    if (validate) {

                        vm.addPoll.options[vm.addPoll.index] = vm.addPoll.option;
                        vm.closeDialog('edit_add_poll_option_modal');

                    }

                }).catch(function () {
                });
            },
            AddPoll() {
                Vue.nextTick(() => {
                    this.errors.clear()
                })
                this.ValidateMsg();
                this.status = 'add-poll';
            },
            EditPoll(poll, index) {
                Vue.set(this.$data, 'poll', poll);
                if (this.poll.status == 1) {
                    this.poll.status = true;
                } else {
                    this.poll.status = false;
                }
                this.addPoll.index = index
                this.getPollOption(this.poll);
                Vue.nextTick(() => {
                    this.errors.clear()
                })
                this.ValidateMsg();
                this.status = 'edit-poll';
            },
            UpdatePoll() {
                var vm = this;
                var options = '';
                if (vm.options.length > 0) {
                    options = vm.options;
                }
                this.$validator.validateAll({
                    edit_title: vm.poll.title,
                    edit_target: vm.poll.target,
                    edit_options: options
                }).then(function (validate) {
                    if (validate) {
                        vm.waiting(true)

                        axios.post('/api/update-poll', vm.poll).then(function (response) {
                            if (response.status == 200) {
                                vm.options[vm.addPoll.index] = response.data;
                                noty({
                                    type: 'success',
                                    layout: 'topLeft',
                                    text: 'اطلاعات نظرسنجی با موفقیت بروز شد.',
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
            ResultPoll(poll){
                var vm = this;
                vm.poll = poll;
                if (vm.poll.status == 1) {
                    vm.poll.status = true;
                } else {
                    vm.poll.status = false;
                }
                this.getPollOption(poll);
                this.status = 'show-result';
            },
            GetVote(poll){
                Vue.nextTick(() => {
                    this.errors.clear()
                })
                this.ValidateMsg();
                Vue.set(this.$data, 'poll', poll);
                var vm = this;
                vm.poll = poll;
                if (vm.poll.status == 1) {
                    vm.poll.status = true;
                } else {
                    vm.poll.status = false;
                }
                this.getPollOption(poll);
                this.status = 'get-vote';
            },
            DelPoll(poll_id, index) {
                var vm = this;
                swal({
                    title: 'حذف نظرسنجی',
                    text: 'از حذف نظرسنجی مطمئن هستید؟',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'بله',
                    cancelButtonText: 'خیر'
                }).then(function () {
                    vm.waiting(true)
                    axios.post('/api/delete-poll', {poll_id: poll_id}).then(function (response) {
                        if (response.status == 200) {
                            vm.polls.pop(index);
                            noty({
                                type: 'success',
                                layout: 'topLeft',
                                text: 'نظرسنجی با موفقیت حذف شد.',
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
            DelPollOption(option_id, index) {
                var vm = this;
                swal({
                    title: 'حذف گزینه',
                    text: 'از حذف گزینه مطمئن هستید؟',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'بله',
                    cancelButtonText: 'خیر'
                }).then(function () {
                    vm.waiting(true)
                    axios.post('/api/delete-poll-option', {option_id: option_id}).then(function (response) {
                        if (response.status == 200) {
                            Vue.delete(vm.options, index)

                        }
                        vm.waiting(false)
                    });
                });
            },
            DelAddPollOption(index) {
                var vm = this;
                Vue.delete(vm.addPoll.options, index);
            },
            ValidateMsg(){
                this.$validator.updateDictionary({
                    'fa': {
                        custom: {
                            add_title: {
                                required: ' عنوان نظرسنجی نباید خالی باشد.',
                                max: 'عنوان نظرسنجی نباید بیشتر از 70 حرف باشد.'
                            },
                            add_target: {
                                required: 'مخاطبان نظرسنجی را انتخاب کنید.',
                            },
                            add_options: {
                                required: 'گزینه ای را وارد کنید.',
                            },
                            add_option: {
                                required: 'گزینه نظرسنجی نمی تواند خالی باشد.',
                                max: 'گزینه نظرسنجی نمی تواند بیشتر از 20 حرف باشد.',
                            },
                            vote: {
                                required: 'یک گزینه را جهت رای دادن انتخاب کنید.',
                            },
                            edit_add_option: {
                                required: 'گزینه نظرسنجی نمی تواند خالی باشد.',
                                max: 'گزینه نظرسنجی نمی تواند بیشتر از 20 حرف باشد.',
                            },
                            edit_title: {
                                required: ' عنوان نظرسنجی نباید خالی باشد.',
                                max: 'عنوان نظرسنجی نباید بیشتر از 70 حرف باشد.'
                            },
                            edit_target: {
                                required: 'مخاطبان نظرسنجی را انتخاب کنید.',
                            },
                            edit_options: {
                                required: 'گزینه ای را وارد کنید.',
                            },
                            edit_option: {
                                required: 'گزینه نظرسنجی نمی تواند خالی باشد.',
                                max: 'گزینه نظرسنجی نمی تواند بیشتر از 20 حرف باشد.',
                            },
                            edit_edit_option: {
                                required: 'گزینه نظرسنجی نمی تواند خالی باشد.',
                                max: 'گزینه نظرسنجی نمی تواند بیشتر از 20 حرف باشد.',
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
            objectSize(obj) {
                var size = 0, key;
                for (key in obj) {
                    if (obj.hasOwnProperty(key)) size++;
                }
                return size;
            },
            waiting(status) {
                this.$parent.loading = status;
            },

        }
    }


</script>

