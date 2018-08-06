<template>
    <div>
        <!--BOOK LIST-->
        <div v-if="status == 'book-list'">
            <div v-if="auth.role == 'admin'" class="box col-xs-12">
                <md-button class="md-primary md-raised" id="add_book_modal" @click.native="AddBook()">
                    افزودن
                </md-button>
            </div>

            <br><br>
            <div class="box col-xs-12">
                <md-table-card>
                    <md-toolbar>
                        <h1 class="md-title">کتاب ها</h1>
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
                                        <md-table-head md-sort-by="bookName">اسم کتاب</md-table-head>
                                        <md-table-head md-sort-by="bookAuthor">نویسنده</md-table-head>
                                        <md-table-head>نوع</md-table-head>
                                        <md-table-head>کد/لینک</md-table-head>
                                        <md-table-head>وضعیت</md-table-head>
                                        <md-table-head>
                                            <span>عملیات</span>
                                        </md-table-head>
                                    </md-table-row>
                                </md-table-header>

                                <md-table-body>
                                    <md-table-row v-for="(book, index) in orderedBooks" :key="index"
                                                  :md-item="book">
                                        <md-table-cell>{{ book.bookName }}</md-table-cell>
                                        <md-table-cell>{{ book.bookAuthor }}</md-table-cell>
                                        <md-table-cell>{{ typeBook(book.bookType) }}</md-table-cell>
                                        <md-table-cell v-if="ValidURL(book.bookPath)"><a :href="book.bookPath" download>لینک دانلود کتاب</a></md-table-cell>
                                        <md-table-cell  v-else="!ValidURL(book.bookPath)">{{ book.bookPath }}</md-table-cell>
                                        <md-table-cell>{{ book.number }}</md-table-cell>
                                        <md-table-cell>
                                            <md-theme v-if="auth.role == 'admin'" md-name="blue">
                                                <md-button class="md-fab md-mini md-primary" id="edit_book_model"
                                                           @click.native="EditBook(book)">
                                                    <md-icon>edit</md-icon>
                                                    <md-tooltip md-direction="top">ویرایش کتاب {{ book.bookName }}
                                                    </md-tooltip>
                                                </md-button>
                                            </md-theme>
                                            <md-theme v-if="auth.role == 'admin'" md-name="red">
                                                <md-button @click.native="DelBook(book.id, index)" class="md-fab md-mini  md-primary">
                                                    <md-icon md-iconset="fa fa-trash"
                                                             class="md-size-2x md-primary"></md-icon>
                                                    <md-tooltip md-direction="top">حذف کتاب {{ book.bookName }}
                                                    </md-tooltip>
                                                </md-button>
                                            </md-theme>

                                        </md-table-cell>
                                    </md-table-row>
                                </md-table-body>
                            </md-table>
                        </table>

                        <div v-if="books.length < 1" class="panel panel-default empty-table-msg">
                            <div class="panel-body">
                                <p class="text-center"><span class="text-info"> هیچ کتابی ثبت نشده است.</span>
                                </p>
                            </div>
                        </div>
                    </div>


                </md-table-card>
            </div>

        </div>

        <!--ADD BOOK-->
        <div v-show="status == 'add-book'">
            <div class="col-xs-12">
                <button @click="status = 'book-list'" class="btn btn-danger pull-left btn-sm  hvr-icon-back">بازگشت</button>
            </div>
            <br><br>
            <div class="col-xs-12">
                <md-card>
                    <md-card-header>
                        <div class="md-title">افزودن کتاب</div>
                    </md-card-header>
                    <md-card-content>
                        <div class="col-sm-2"></div>
                        <div class="col-sm-8">

                            <form @submit.prevent="StoreBook()" novalidate class="form-horizontal">

                                <div class="form-group" :class="errors.has('add_name')?'has-error':''">
                                    <label for="add_name" class="col-sm-2 control-label">نام کتاب </label>
                                    <div class="col-sm-10">
                                        <input
                                                v-validate.initial="'required|max:20'"
                                                name="add_name"
                                                v-model="addBook.name"
                                                class="form-control"
                                                placeholder="نام کتاب"
                                                type="text"
                                        >
                                        <div v-show="errors.has('add_name')" class="help-block">{{
                                            errors.first('add_name') }}
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group" :class="errors.has('add_author')?'has-error':''">
                                    <label for="add_author" class="col-sm-2 control-label">نویسنده </label>
                                    <div class="col-sm-10">
                                        <input
                                                v-validate.initial="'required|max:20'"
                                                name="add_author"
                                                v-model="addBook.author"
                                                class="form-control"
                                                placeholder="نویسنده"
                                                type="text"
                                        >
                                        <div v-show="errors.has('add_author')" class="help-block">{{
                                            errors.first('add_author') }}
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group " :class="errors.has('add_description')?'has-error':''">
                                    <label for="add_description" class="col-sm-2 control-label">توضیحات </label>
                                    <div class="col-sm-10">
                                        <textarea name="add_description"
                                                  class="form-control"
                                                  v-model="addBook.description"
                                                  v-validate.initial="'max:75'"
                                        ></textarea>
                                        <div v-show="errors.has('add_description')" class="help-block">{{
                                            errors.first('add_description') }}
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group" :class="errors.has('add_type')?'has-error':''">
                                    <label for="add_type" class="col-sm-2 control-label">نوع کتاب </label>
                                    <div class="col-sm-10">
                                        <md-radio v-validate.initial="'required|in:1,2'" v-model="addBook.type"
                                                  id="physical_book" data-vv-name="add_type" md-value="1">کتاب
                                        </md-radio>
                                        <md-radio v-model="addBook.type" id="electronic_book" data-vv-name="add_type"
                                                  md-value="2">کتاب الکترونیکی
                                        </md-radio>

                                        <div v-show="errors.has('add_type')" class="help-block">{{
                                            errors.first('add_type') }}
                                        </div>
                                    </div>
                                </div>

                                <div v-show="addBook.type == 1" class="form-group"
                                     :class="errors.has('add_price')?'has-error':''">
                                    <label for="add_price" class="col-sm-2 control-label">قیمت </label>
                                    <div class="col-sm-10">
                                        <input
                                                v-validate.initial="'numeric'"
                                                name="add_price"
                                                v-model="addBook.price"
                                                class="form-control"
                                                placeholder="20000 تومان"
                                                type="text"
                                        >
                                        <div v-show="errors.has('add_price')" class="help-block">{{
                                            errors.first('add_price') }}
                                        </div>
                                    </div>
                                </div>

                                <div v-show="addBook.type == 1" class="form-group"
                                     :class="errors.has('add_code')?'has-error':''">
                                    <label for="add_code" class="col-sm-2 control-label">کد </label>
                                    <div class="col-sm-10">
                                        <input
                                                v-validate.initial="'required|max:150'"
                                                name="add_code"
                                                v-model="addBook.code"
                                                class="form-control"
                                                placeholder="کد"
                                                type="text"
                                        >
                                        <div v-show="errors.has('add_code')" class="help-block">{{
                                            errors.first('add_code') }}
                                        </div>
                                    </div>
                                </div>

                                <div v-show="addBook.type == 1" class="form-group"
                                     :class="errors.has('add_number')?'has-error':''">
                                    <label for="add_number" class="col-sm-2 control-label">تعداد </label>
                                    <div class="col-sm-10">
                                        <input
                                                v-validate.initial="'numeric'"
                                                name="add_number"
                                                v-model="addBook.number"
                                                class="form-control"
                                                placeholder="تعداد"
                                                type="text"
                                        >
                                        <div v-show="errors.has('add_number')" class="help-block">{{
                                            errors.first('add_number') }}
                                        </div>
                                    </div>
                                </div>

                                <div v-show="addBook.type == 2" class="form-group"
                                     :class="errors.has('add_link')?'has-error':''">
                                    <label for="add_link" class="col-sm-2 control-label">لینک دانلود </label>
                                    <div class="col-sm-10">
                                        <input
                                                v-validate.initial="'required|max:300'"
                                                name="add_link"
                                                v-model="addBook.link"
                                                class="form-control"
                                                placeholder="لینک دانلود"
                                                type="text"
                                        >
                                        <div v-show="errors.has('add_link')" class="help-block">{{
                                            errors.first('add_link') }}
                                        </div>
                                    </div>
                                </div>

                                <input type="submit" class="btn btn-success pull-left" value="ذخیره">

                            </form>

                        </div>

                    </md-card-content>

                </md-card>
            </div>
        </div>

        <!--EDIT BOOK-->
        <div v-show="status == 'edit-book'">
            <div class="col-xs-12">
                <button @click="status = 'book-list'" class="btn btn-danger pull-left btn-sm  hvr-icon-back">بازگشت</button>
            </div>
            <br><br>
            <div class="col-xs-12">
                <md-card>
                    <md-card-header>
                        <div class="md-title">افزودن کتاب</div>
                    </md-card-header>
                    <md-card-content>
                        <div class="col-sm-2"></div>
                        <div class="col-sm-8">

                            <form @submit.prevent="UpdateBook()" novalidate class="form-horizontal">

                                <div class="form-group" :class="errors.has('edit_name')?'has-error':''">
                                    <label for="edit_name" class="col-sm-2 control-label">نام کتاب </label>
                                    <div class="col-sm-10">
                                        <input
                                                v-validate.initial="'required|max:20'"
                                                name="edit_name"
                                                v-model="book.bookName"
                                                class="form-control"
                                                placeholder="نام کتاب"
                                                type="text"
                                        >
                                        <div v-show="errors.has('edit_name')" class="help-block">{{
                                            errors.first('edit_name') }}
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group" :class="errors.has('edit_author')?'has-error':''">
                                    <label for="edit_author" class="col-sm-2 control-label">نویسنده </label>
                                    <div class="col-sm-10">
                                        <input
                                                v-validate.initial="'required|max:20'"
                                                name="edit_author"
                                                v-model="book.bookAuthor"
                                                class="form-control"
                                                placeholder="نویسنده"
                                                type="text"
                                        >
                                        <div v-show="errors.has('edit_author')" class="help-block">{{
                                            errors.first('edit_author') }}
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group " :class="errors.has('edit_description')?'has-error':''">
                                    <label for="edit_description" class="col-sm-2 control-label">توضیحات </label>
                                    <div class="col-sm-10">
                                        <textarea name="edit_description"
                                                  class="form-control"
                                                  v-model="book.bookDescription"
                                                  v-validate.initial="'max:75'"
                                        ></textarea>
                                        <div v-show="errors.has('edit_description')" class="help-block">{{
                                            errors.first('edit_description') }}
                                        </div>
                                    </div>
                                </div>

                                <div v-show="book.bookType == 1" class="form-group"
                                     :class="errors.has('edit_price')?'has-error':''">
                                    <label for="edit_price" class="col-sm-2 control-label">قیمت </label>
                                    <div class="col-sm-10">
                                        <input
                                                v-validate.initial="'numeric'"
                                                name="edit_price"
                                                v-model="book.bookPrice"
                                                class="form-control"
                                                placeholder="20000 تومان"
                                                type="text"
                                        >
                                        <div v-show="errors.has('edit_price')" class="help-block">{{
                                            errors.first('edit_price') }}
                                        </div>
                                    </div>
                                </div>

                                <div v-show="book.bookType == 1" class="form-group"
                                     :class="errors.has('edit_code')?'has-error':''">
                                    <label for="edit_code" class="col-sm-2 control-label">کد </label>
                                    <div class="col-sm-10">
                                        <input
                                                v-validate.initial="'required|max:150'"
                                                name="edit_code"
                                                v-model="book.bookPath"
                                                class="form-control"
                                                placeholder="کد"
                                                type="text"
                                        >
                                        <div v-show="errors.has('edit_code')" class="help-block">{{
                                            errors.first('edit_code') }}
                                        </div>
                                    </div>
                                </div>

                                <div v-show="book.bookType == 1" class="form-group"
                                     :class="errors.has('edit_number')?'has-error':''">
                                    <label for="edit_number" class="col-sm-2 control-label">تعداد </label>
                                    <div class="col-sm-10">
                                        <input
                                                v-validate.initial="'numeric'"
                                                name="edit_number"
                                                v-model="book.number"
                                                class="form-control"
                                                placeholder="تعداد"
                                                type="text"
                                        >
                                        <div v-show="errors.has('edit_number')" class="help-block">{{
                                            errors.first('edit_number') }}
                                        </div>
                                    </div>
                                </div>

                                <div v-show="book.bookType == 2" class="form-group"
                                     :class="errors.has('edit_link')?'has-error':''">
                                    <label for="edit_link" class="col-sm-2 control-label">لینک دانلود </label>
                                    <div class="col-sm-10">
                                        <input
                                                v-validate.initial="'required|max:300'"
                                                name="edit_link"
                                                v-model="book.bookPath"
                                                class="form-control"
                                                placeholder="لینک دانلود"
                                                type="text"
                                        >
                                        <div v-show="errors.has('edit_link')" class="help-block">{{
                                            errors.first('edit_link') }}
                                        </div>
                                    </div>
                                </div>

                                <input type="submit" class="btn btn-success pull-left" value="بروز رسانی">

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
        props:['authUser'],
        data() {
            return {
                auth: [],
                books: [],
                book: {},
                addBook: {
                    'name': '',
                    'description': '',
                    'author': '',
                    'type': 1,
                    'price': '',
                    'code': '',
                    'link': '',
                    'number': ''
                },
                searchBox: '',
                status: 'book-list',
                sortType: 'asc',
                sortBy: 'name',
            }
        },
        created: function () {
            this.auth = JSON.parse(this.authUser);
            this.getBooks();
        },
        computed: {
            orderedBooks: function () {
                var vm = this;
                return _.filter(_.orderBy(this.books, this.sortBy, this.sortType), function (book) {
                    return ('' || book.bookName).includes(vm.searchBox) || ('' || book.bookAuthor).includes(vm.searchBox) || ('' || book.bookDescription).includes(vm.searchBox);
                });
            },
        },
        methods: {
            getBooks() {
                var vm = this;
                vm.waiting(true)
                axios.get('/api/library/get-books').then(function (response) {
                    Vue.set(vm.$data, 'books', response.data);
                    vm.waiting(false)
                });
            },
            EditBook(book) {
                Vue.set(this.$data, 'book', book);
                Vue.nextTick(() => {
                    this.errors.clear()
                });
                this.ValidateMsg();
                this.status = 'edit-book';
            },
            AddBook(){
                Vue.nextTick(() => {
                    this.errors.clear()
                })
                this.ValidateMsg();
                this.status = 'add-book';
            },
            StoreBook() {
                var vm = this;
                if (vm.addBook.type == 1) {
                    this.$validator.validateAll({
                        add_name: vm.addBook.name,
                        add_description: vm.addBook.description,
                        add_author: vm.addBook.author,
                        add_type: vm.addBook.type,
                        add_price: vm.addBook.price,
                        add_code: vm.addBook.code,
                        add_number: vm.addBook.number,
                    }).then(function (validate) {
                        if (validate) {
                            vm.waiting(true)

                            axios.post('/api/library/store-book', vm.addBook).then(function (response) {
                                if (response.status == 200) {
                                    vm.addBook = {
                                        'name': '',
                                        'description': '',
                                        'author': '',
                                        'type': 1,
                                        'price': '',
                                        'code': '',
                                        'link': '',
                                        'number': ''
                                    };
                                    Vue.nextTick(() => {
                                        vm.errors.clear()
                                    })
                                    vm.ValidateMsg();
                                    vm.books.push(response.data);
                                    noty({
                                        type: 'success',
                                        layout: 'topLeft',
                                        text: 'کتاب با موفقیت ذخیره شد.',
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
                }
                if (vm.addBook.type == 2) {
                    this.$validator.validateAll({
                        add_name: vm.addBook.name,
                        add_description: vm.addBook.description,
                        add_author: vm.addBook.author,
                        add_type: vm.addBook.type,
                        add_link: vm.addBook.link,
                    }).then(function (validate) {
                        if (validate) {
                            vm.waiting(true)

                            axios.post('/api/library/store-book', vm.addBook).then(function (response) {
                                if (response.status == 200) {
                                    vm.addBook = {
                                        'name': '',
                                        'description': '',
                                        'author': '',
                                        'type': 1,
                                        'price': '',
                                        'code': '',
                                        'link': '',
                                        'number': ''
                                    };
                                    Vue.nextTick(() => {
                                        vm.errors.clear()
                                    })
                                    vm.ValidateMsg();
                                    vm.books.push(response.data);
                                    noty({
                                        type: 'success',
                                        layout: 'topLeft',
                                        text: 'کتاب با موفقیت ذخیره شد.',
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
                }
            },
            UpdateBook() {
                var vm = this;
                if (vm.book.bookType == 1) {
                    this.$validator.validateAll({
                        edit_name: vm.book.bookName,
                        edit_description: vm.book.bookDescription,
                        edit_author: vm.book.bookAuthor,
                        edit_price: vm.book.bookPrice,
                        edit_code: vm.book.bookPath,
                        edit_number: vm.book.number,
                    }).then(function (validate) {
                        if (validate) {
                            vm.waiting(true)

                            axios.post('/api/library/update-book', vm.book).then(function (response) {
                                if (response.status == 200) {
                                    noty({
                                        type: 'success',
                                        layout: 'topLeft',
                                        text: 'کتاب با موفقیت بروز شد.',
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
                }
                if (vm.book.bookType == 2) {
                    this.$validator.validateAll({
                        edit_name: vm.book.bookName,
                        edit_author: vm.book.bookAuthor,
                        edit_description: vm.book.bookDescription,
                        edit_link: vm.book.bookPath,
                    }).then(function (validate) {
                        if (validate) {
                            vm.waiting(true)

                            axios.post('/api/library/update-book', vm.book).then(function (response) {
                                if (response.status == 200) {
                                    noty({
                                        type: 'success',
                                        layout: 'topLeft',
                                        text: 'کتاب با موفقیت بروز شد.',
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
                }
            },
            DelBook(book_id, index) {
                var vm = this;
                swal({
                    title: 'حذف کتاب',
                    text: 'از حذف کتاب مطمئن هستید؟',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'بله',
                    cancelButtonText: 'خیر'
                }).then(function () {
                    vm.waiting(true)
                    axios.post('/api/delete-book', {book_id: book_id}).then(function (response) {
                        if (response.status == 200) {
                            Vue.delete(vm.books, index);

                            noty({
                                type: 'success',
                                layout: 'topLeft',
                                text: 'کتاب با موفقیت حذف شد.',
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
            typeBook(id){
                if (id == 1) {
                    return 'کتاب'
                }
                if (id == 2) {
                    return 'کتاب الکترونیکی'
                }
            },
            ValidURL(str) {
                var pattern = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;
                if (!pattern.test(str)) {
                    return false;
                } else {
                    return true;
                }
            },
            ValidateMsg(){
                this.$validator.updateDictionary({
                    'fa': {
                        custom: {
                            add_name: {
                                required: ' اسم کتاب نباید خالی باشد.',
                                max: 'اسم کتاب نباید بیشتر از 20 حرف باشد.'
                            },
                            add_description: {
                                max: 'توضیحات نباید بیشتر از 75 حرف باشد.'
                            },
                            add_author: {
                                required: ' اسم نویسنده نباید خالی باشد.',
                                max: 'اسم کتاب نباید بیشتر از 20 حرف باشد.'
                            },
                            add_type: {
                                required: ' اسم نویسنده نباید خالی باشد.',
                                in: 'نوع کتاب معتبر نمی باشد.'
                            },
                            add_price: {
                                numeric: 'قیمت کتاب باید به عدد باشد.'
                            },
                            add_code: {
                                required: ' کد کتاب نباید خالی باشد.',
                                max: 'کد کتاب نباید بیشتر از 150 حرف باشد.'
                            },
                            add_number: {
                                numeric: 'تعداد کتاب باید عدد باشد.'
                            },
                            add_link: {
                                required: 'کادر لینک دانلود نباید خالی باشد.',
                                max:'لینک دانلود نباید بیشتر از 300 کاراکتر باشد.'
                            },
                            edit_name: {
                                required: ' اسم کتاب نباید خالی باشد.',
                                max: 'اسم کتاب نباید بیشتر از 20 حرف باشد.'
                            },
                            edit_description: {
                                max: 'توضیحات نباید بیشتر از 75 حرف باشد.'
                            },
                            edit_author: {
                                required: ' اسم نویسنده نباید خالی باشد.',
                                max: 'اسم کتاب نباید بیشتر از 20 حرف باشد.'
                            },
                            edit_price: {
                                numeric: 'قیمت کتاب باید به عدد باشد.'
                            },
                            edit_code: {
                                required: ' کد کتاب نباید خالی باشد.',
                                max: 'کد کتاب نباید بیشتر از 150 حرف باشد.'
                            },
                            edit_number: {
                                numeric: 'تعداد کتاب باید عدد باشد.'
                            },
                            edit_link: {
                                required: 'کادر لینک دانلود نباید خالی باشد.',
                                max:'لینک دانلود نباید بیشتر از 300 کاراکتر باشد.'
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
