<template>
    <div>
        <!--EXAM LIST-->
        <div v-if="status == 'show-online-exams'">
            <div class="box col-xs-12">
                <button v-if="auth.role == 'admin' || auth.role == 'teacher'" @click="AddExam()"
                        class="btn btn-success pull-left">افزودن امتحان
                </button>
                <br><br>
                <md-table-card>
                    <md-toolbar>

                        <h1 class="md-title">امتحانات</h1>
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
                                        <md-table-head md-sort-by="title">عنوان امتحان</md-table-head>
                                        <md-table-head>توضیحات</md-table-head>
                                        <md-table-head md-sort-by="endDate">زمان اتمام</md-table-head>
                                        <md-table-head>
                                            <span>عملیات</span>
                                        </md-table-head>
                                    </md-table-row>
                                </md-table-header>

                                <md-table-body>
                                    <md-table-row v-for="(ex, examIndex) in orderedExams" :key="examIndex"
                                                  :md-item="ex">
                                        <md-table-cell>{{ ex.title }}</md-table-cell>
                                        <md-table-cell>{{ ex.description }}</md-table-cell>
                                        <md-table-cell>{{ ex.endDate }}</md-table-cell>
                                        <md-table-cell>
                                            <button v-if="auth.role == 'admin' || auth.role == 'teacher'"
                                                    @click="showMarks(ex)"
                                                    class="btn btn-success btn-sm">نمرات
                                            </button>

                                            <button v-if="auth.role == 'admin' || auth.role == 'teacher'"
                                                    @click="getQuestions(ex)"
                                                    class="btn btn-success btn-sm">
                                                سوالات
                                            </button>

                                            <button v-if="auth.role == 'student'" @click="getExamQuestions(ex)"
                                                    class="btn btn-success btn-sm">
                                                پاسخگویی
                                            </button>

                                            <button @click="EditExam(ex)"
                                                    v-if="auth.role == 'admin' || auth.role == 'teacher'"
                                                    class="btn btn-info btn-sm">
                                                ویرایش
                                            </button>

                                            <button @click="DelExam(ex.id,examIndex)"
                                                    v-if="auth.role == 'admin' || auth.role == 'teacher'"
                                                    class="btn btn-danger btn-sm">حذف
                                            </button>
                                        </md-table-cell>
                                    </md-table-row>
                                </md-table-body>
                            </md-table>
                        </table>

                        <div v-if="exams.length < 1" class="panel panel-default empty-table-msg">
                            <div class="panel-body">
                                <p class="text-center"><span class="text-info"> هیچ امتحانی ثبت نشده است.</span>
                                </p>
                            </div>
                        </div>

                    </div>
                </md-table-card>
            </div>
        </div>

        <!--ADD EXAM-->
        <div v-show="status == 'add-online-exam'">
            <button @click="status = 'show-online-exams'" class="btn btn-danger pull-left  btn-sm  hvr-icon-back">
                بازگشت
            </button>
            <br><br>
            <md-card class="col-sm-12">
                <md-card-header>
                    <div class="md-title">تعریف امتحان</div>
                </md-card-header>
                <div class="row">
                    <div class="col-sm-2"></div>
                    <div class="col-sm-8">
                        <form class="form-horizontal" @submit.prevent="StoreExam()">
                            <md-card-content>

                                <div class="form-group" :class="errors.has('add_class')?'has-error':''">
                                    <label for="add_class" class=" control-label">کلاس </label>
                                    <multiselect
                                            v-model="clase"
                                            :options="classes"
                                            :multiple="true"
                                            track-by="clase"
                                            :internal-search="false"
                                            data-vv-name="add_class"
                                            label="clase"
                                            placeholder=""
                                            @input="getSubjects()"
                                            v-validate.initial="'required'"
                                            select-label="برای انتخاب کلیک کنید."
                                            deselect-label="برای حذف انتخاب کلیک کنید."
                                            selected-label="انتخاب شده."
                                            :hide-selected="true"
                                            noResult="کلاسی موجود نمی باشد."

                                    ></multiselect>

                                    <div v-show="errors.has('add_class')" class="help-block">{{
                                        errors.first('add_class') }}
                                    </div>
                                </div>

                                <div class="form-group" :class="errors.has('add_subject')?'has-error':''">
                                    <label for="add_subject" class=" control-label">درس </label>
                                    <multiselect
                                            v-model="subject"
                                            :options="subjects"
                                            :multiple="true"
                                            track-by="subject"
                                            :internal-search="false"
                                            data-vv-name="add_subject"
                                            label="subject"
                                            placeholder=""
                                            v-validate.initial="'required'"
                                            select-label="برای انتخاب کلیک کنید."
                                            deselect-label="برای حذف انتخاب کلیک کنید."
                                            selected-label="انتخاب شده."
                                            :hide-selected="true"
                                            noResult="درسی موجود نمی باشد."

                                    ></multiselect>

                                    <div v-show="errors.has('add_subject')" class="help-block">{{
                                        errors.first('add_subject') }}
                                    </div>
                                </div>

                                <div class="form-group" :class="errors.has('add_title')?'has-error':''">
                                    <label for="add_title" class=" control-label">عنوان امتحان </label>
                                    <input

                                            v-model="addExam.title"
                                            name="add_title"
                                            v-validate.initial="'required|max:20'"
                                            class="form-control"
                                            placeholder=" عنوان امتحان"
                                            type="text"
                                    >
                                    <div v-show="errors.has('add_title')" class="help-block">{{
                                        errors.first('add_title') }}
                                    </div>
                                </div>

                                <div class="form-group" :class="errors.has('add_description')?'has-error':''">
                                    <label for="add_description" class=" control-label">توضیحات </label>
                                    <textarea
                                            v-model="addExam.description"
                                            name="add_description"
                                            v-validate.initial="'max:255'"
                                            class="form-control"
                                            placeholder=" توضیحات"
                                            type="text"
                                    ></textarea>
                                    <div v-show="errors.has('add_description')" class="help-block">{{
                                        errors.first('add_description') }}
                                    </div>
                                </div>

                                <div class="form-group" :class="errors.has('add_start')?'has-error':''">
                                    <label for="add_start" class=" control-label">زمان شروع </label>
                                    <input
                                            v-mask="'####/##/## ##:##'"
                                            v-model="addExam.startTime"
                                            name="add_start"
                                            v-validate.initial="'required|date_format:YYYY/MM/DD HH:mm'"
                                            class="form-control"
                                            placeholder="زمان شروع"
                                            type="text"
                                    >
                                    <div v-show="errors.has('add_start')" class="help-block">{{
                                        errors.first('add_start') }}
                                    </div>
                                </div>

                                <div class="form-group" :class="errors.has('add_end')?'has-error':''">
                                    <label for="add_end" class=" control-label">زمان پایان </label>
                                    <input
                                            v-mask="'####/##/## ##:##'"
                                            v-model="addExam.endTime"
                                            name="add_end"
                                            v-validate.initial="'required|date_format:YYYY/MM/DD HH:mm'"
                                            class="form-control"
                                            placeholder="زمان پایان"
                                            type="text"
                                    >
                                    <div v-show="errors.has('add_end')" class="help-block">{{ errors.first('add_end')
                                        }}
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="inputEmail3" class=" control-label"> نشان دادن نمره بعد از
                                        امتحان</label>
                                    <div class="">
                                        <md-switch v-model="addExam.show_mark" id="show-mark-checkbox"
                                                   name="show-mark-checkbox"
                                                   class="md-primary"></md-switch>
                                    </div>
                                </div>

                            </md-card-content>
                            <md-card-actions>
                                <md-button type="submit" class="md-raised md-primary">ذخیره</md-button>
                            </md-card-actions>
                        </form>
                    </div>
                </div>

            </md-card>
        </div>

        <!--EDIT EXAM-->
        <div v-show="status == 'edit-online-exam'">
            <a @click="status = 'show-online-exams'" class="btn btn-danger btn-sm pull-left ">بازگشت</a>
            <br><br>
            <md-card class="col-sm-12">
                <md-card-header>
                    <div class="md-title">ویرایش امتحان</div>
                </md-card-header>
                <div class="row">
                    <div class="col-sm-2"></div>
                    <div class="col-sm-8">
                        <form class="form-horizontal" @submit.prevent="UpdateExam()">
                            <md-card-content>

                                <div class="form-group" :class="errors.has('edit_class')?'has-error':''">
                                    <label for="edit_class" class=" control-label">کلاس </label>
                                    <multiselect
                                            v-model="clase"
                                            :options="classes"
                                            :multiple="true"
                                            track-by="clase"
                                            :internal-search="false"
                                            data-vv-name="edit_class"
                                            label="clase"
                                            placeholder=""
                                            @input="getSubjects()"
                                            v-validate.initial="'required'"
                                            select-label="برای انتخاب کلیک کنید."
                                            deselect-label="برای حذف انتخاب کلیک کنید."
                                            selected-label="انتخاب شده."
                                            :hide-selected="true"
                                            noResult="کلاسی موجود نمی باشد."

                                    ></multiselect>

                                    <div v-show="errors.has('edit_class')" class="help-block">{{
                                        errors.first('edit_class') }}
                                    </div>
                                </div>

                                <div class="form-group" :class="errors.has('edit_subject')?'has-error':''">
                                    <label for="edit_subject" class=" control-label">درس </label>
                                    <multiselect
                                            v-model="subject"
                                            :options="subjects"
                                            :multiple="true"
                                            track-by="subject"
                                            :internal-search="false"
                                            data-vv-name="edit_subject"
                                            label="subject"
                                            placeholder=""
                                            v-validate.initial="'required'"
                                            select-label="برای انتخاب کلیک کنید."
                                            deselect-label="برای حذف انتخاب کلیک کنید."
                                            selected-label="انتخاب شده."
                                            :hide-selected="true"
                                            noResult="درسی موجود نمی باشد."

                                    ></multiselect>

                                    <div v-show="errors.has('edit_subject')" class="help-block">{{
                                        errors.first('edit_subject') }}
                                    </div>
                                </div>

                                <div class="form-group" :class="errors.has('edit_title')?'has-error':''">
                                    <label for="edit_title" class=" control-label">عنوان امتحان </label>
                                    <input

                                            v-model="exam.title"
                                            name="edit_title"
                                            v-validate.initial="'required|max:20'"
                                            class="form-control"
                                            placeholder=" عنوان امتحان"
                                            type="text"
                                    >
                                    <div v-show="errors.has('edit_title')" class="help-block">{{
                                        errors.first('edit_title') }}
                                    </div>
                                </div>

                                <div class="form-group" :class="errors.has('edit_description')?'has-error':''">
                                    <label for="edit_description" class=" control-label">توضیحات </label>
                                    <textarea
                                            v-model="exam.description"
                                            name="edit_description"
                                            v-validate.initial="'max:255'"
                                            class="form-control"
                                            placeholder=" توضیحات"
                                            type="text"
                                    ></textarea>
                                    <div v-show="errors.has('edit_description')" class="help-block">{{
                                        errors.first('edit_description') }}
                                    </div>
                                </div>

                                <div class="form-group" :class="errors.has('edit_start')?'has-error':''">
                                    <label for="edit_start" class=" control-label">زمان شروع </label>
                                    <input
                                            v-mask="'####/##/## ##:##'"
                                            v-model="exam.date"
                                            name="edit_start"
                                            v-validate.initial="'required|date_format:YYYY/MM/DD HH:mm'"
                                            class="form-control"
                                            placeholder="زمان شروع"
                                            type="text"
                                    >
                                    <div v-show="errors.has('edit_start')" class="help-block">{{
                                        errors.first('edit_start') }}
                                    </div>
                                </div>

                                <div class="form-group" :class="errors.has('edit_end')?'has-error':''">
                                    <label for="edit_end" class=" control-label">زمان پایان </label>
                                    <input
                                            v-mask="'####/##/## ##:##'"
                                            v-model="exam.endDate"
                                            name="edit_end"
                                            v-validate.initial="'required|date_format:YYYY/MM/DD HH:mm'"
                                            class="form-control"
                                            placeholder="زمان پایان"
                                            type="text"
                                    >
                                    <div v-show="errors.has('edit_end')" class="help-block">{{ errors.first('edit_end')
                                        }}
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="inputEmail3" class=" control-label"> نشان دادن نمره بعد از
                                        امتحان</label>
                                    <div class="">
                                        <md-switch v-model="exam.showMark" id="show-mark-checkbox"
                                                   name="show-mark-checkbox"
                                                   class="md-primary"></md-switch>
                                    </div>
                                </div>

                            </md-card-content>
                            <md-card-actions>
                                <md-button type="submit" class="md-raised md-primary">ذخیره</md-button>
                            </md-card-actions>
                        </form>
                    </div>
                </div>

            </md-card>
        </div>

        <!--ADD QUESTION-->
        <div v-show="status == 'add-question'">
            <button @click="status = 'questions'" class="btn btn-danger pull-left  btn-sm  hvr-icon-back">بازگشت
            </button>
            <br><br>
            <div>
                <md-card>
                    <md-card-header>
                        <div class="md-title">افزودن سوال</div>
                    </md-card-header>
                    <form v-if="auth.role == 'admin' || 'teacher'" @submit.prevent="StoreQuestion()" novalidate>
                        <md-card-content>

                            <div class="form-group" :class="errors.has('add_question')?'has-error':''">
                                <label for="add_question" class=" control-label">سوال </label>
                                <textarea
                                        v-model="addQuestion.question"
                                        name="add_question"
                                        v-validate.initial="'required|max:255'"
                                        class="form-control"
                                        placeholder=" سوال"
                                        type="text"
                                ></textarea>
                                <div v-show="errors.has('add_question')" class="help-block">{{
                                    errors.first('add_question') }}
                                </div>
                            </div>

                            <br><br>
                            <div class="row">
                                <div class="col-sm-6">

                                    <div class="col-sm-12">
                                        <div class="form-group" :class="errors.has('add_option1')?'has-error':''">
                                            <label for="add_option1" class=" control-label">گزینه 1 </label>
                                            <input

                                                    v-model="addQuestion.ans1"
                                                    name="add_option1"
                                                    v-validate.initial="'required|max:70'"
                                                    class="form-control"
                                                    placeholder="گزینه 1"
                                                    type="text"
                                            >
                                            <div v-show="errors.has('add_option1')" class="help-block">{{
                                                errors.first('add_option1') }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-12">
                                        <div class="form-group" :class="errors.has('add_option3')?'has-error':''">
                                            <label for="add_option3" class=" control-label">گزینه3 </label>
                                            <input

                                                    v-model="addQuestion.ans3"
                                                    name="add_option3"
                                                    v-validate.initial="'required|max:70'"
                                                    class="form-control"
                                                    placeholder="گزینه3"
                                                    type="text"
                                            >
                                            <div v-show="errors.has('add_option3')" class="help-block">{{
                                                errors.first('add_option3') }}
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-sm-6">

                                    <div class="col-sm-12">
                                        <div class="form-group" :class="errors.has('add_option2')?'has-error':''">
                                            <label for="add_option2" class=" control-label">گزینه2 </label>
                                            <input

                                                    v-model="addQuestion.ans2"
                                                    name="add_option2"
                                                    v-validate.initial="'required|max:70'"
                                                    class="form-control"
                                                    placeholder="گزینه2"
                                                    type="text"
                                            >
                                            <div v-show="errors.has('add_option2')" class="help-block">{{
                                                errors.first('add_option2') }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-12">
                                        <div class="form-group" :class="errors.has('add_option4')?'has-error':''">
                                            <label for="add_option4" class=" control-label">گزینه4 </label>
                                            <input

                                                    v-model="addQuestion.ans4"
                                                    name="add_option4"
                                                    v-validate.initial="'required|max:70'"
                                                    class="form-control"
                                                    placeholder=" گزینه4"
                                                    type="text"
                                            >
                                            <div v-show="errors.has('add_option4')" class="help-block">{{
                                                errors.first('add_option4') }}
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <br><br>
                            <div class="form-group" :class="errors.has('add_trueOption')?'has-error':''">

                                <div v-show="false">
                                    <md-radio v-validate.initial="'required|in:1,2,3,4'" v-model="addQuestion.trueAns"
                                              data-vv-name="add_trueOption"
                                              md-value="0" class="md-primary"> جواب صحیح1
                                    </md-radio>
                                </div>
                                <div class="col-sm-3">
                                    <md-radio v-model="addQuestion.trueAns"
                                              data-vv-name="add_trueOption"
                                              md-value="1" class="md-primary">جواب صحیح1
                                    </md-radio>
                                </div>
                                <div class="col-sm-3">
                                    <md-radio v-model="addQuestion.trueAns"
                                              data-vv-name="add_trueOption"
                                              md-value="2" class="md-primary">جواب صحیح2
                                    </md-radio>
                                </div>
                                <div class="col-sm-3">
                                    <md-radio v-model="addQuestion.trueAns"
                                              data-vv-name="add_trueOption"
                                              md-value="3" class="md-primary">جواب صحیح3
                                    </md-radio>
                                </div>
                                <div class="col-sm-3">
                                    <md-radio v-model="addQuestion.trueAns"
                                              data-vv-name="add_trueOption"
                                              md-value="4" class="md-primary">جواب صحیح4
                                    </md-radio>
                                </div>
                                <div v-show="errors.has('add_trueOption')" class="help-block">{{
                                    errors.first('add_trueOption') }}
                                </div>
                            </div>
                            <br><br>
                        </md-card-content>

                        <md-card-actions>
                            <md-button type="submit" class="md-raised md-primary">اضافه کردن سوال</md-button>
                        </md-card-actions>
                    </form>
                </md-card>
            </div>
        </div>

        <!--EDIT QUESTION-->
        <div v-show="status == 'edit-question'">
            <button @click="status = 'questions'" class="btn btn-danger pull-left btn-sm  hvr-icon-back">بازگشت</button>
            <br><br>
            <div>
                <md-card>
                    <md-card-header>
                        <div class="md-title">ویرایش سوال</div>
                    </md-card-header>
                    <form @submit.prevent="UpdateQuestion()" novalidate>
                        <md-card-content>

                            <div class="form-group" :class="errors.has('edit_question')?'has-error':''">
                                <label for="edit_question" class=" control-label">سوال </label>
                                <textarea
                                        v-model="editQuestion.question"
                                        name="edit_question"
                                        v-validate.initial="'required|max:255'"
                                        class="form-control"
                                        placeholder=" سوال"
                                        type="text"
                                ></textarea>
                                <div v-show="errors.has('edit_question')" class="help-block">{{
                                    errors.first('edit_question') }}
                                </div>
                            </div>

                            <br><br>
                            <div class="row">
                                <div class="col-sm-6">

                                    <div class="col-sm-12">
                                        <div class="form-group" :class="errors.has('edit_option1')?'has-error':''">
                                            <label for="edit_option1" class=" control-label">گزینه 1 </label>
                                            <input

                                                    v-model="editQuestion.ans1"
                                                    name="edit_option1"
                                                    v-validate.initial="'required|max:70'"
                                                    class="form-control"
                                                    placeholder="گزینه 1"
                                                    type="text"
                                            >
                                            <div v-show="errors.has('edit_option1')" class="help-block">{{
                                                errors.first('edit_option1') }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-12">
                                        <div class="form-group" :class="errors.has('edit_option3')?'has-error':''">
                                            <label for="edit_option3" class=" control-label">گزینه3 </label>
                                            <input

                                                    v-model="editQuestion.ans3"
                                                    name="edit_option3"
                                                    v-validate.initial="'required|max:70'"
                                                    class="form-control"
                                                    placeholder="گزینه3"
                                                    type="text"
                                            >
                                            <div v-show="errors.has('edit_option3')" class="help-block">{{
                                                errors.first('edit_option3') }}
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-sm-6">

                                    <div class="col-sm-12">
                                        <div class="form-group" :class="errors.has('edit_option2')?'has-error':''">
                                            <label for="edit_option2" class=" control-label">گزینه2 </label>
                                            <input

                                                    v-model="editQuestion.ans2"
                                                    name="edit_option2"
                                                    v-validate.initial="'required|max:70'"
                                                    class="form-control"
                                                    placeholder="گزینه2"
                                                    type="text"
                                            >
                                            <div v-show="errors.has('edit_option2')" class="help-block">{{
                                                errors.first('edit_option2') }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-12">
                                        <div class="form-group" :class="errors.has('edit_option4')?'has-error':''">
                                            <label for="edit_option4" class=" control-label">گزینه4 </label>
                                            <input

                                                    v-model="editQuestion.ans4"
                                                    name="edit_option4"
                                                    v-validate.initial="'required|max:70'"
                                                    class="form-control"
                                                    placeholder=" گزینه4"
                                                    type="text"
                                            >
                                            <div v-show="errors.has('edit_option4')" class="help-block">{{
                                                errors.first('edit_option4') }}
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <br><br>
                            <div class="form-group" :class="errors.has('edit_trueOption')?'has-error':''">
                                <div v-show="false">
                                    <md-radio v-validate.initial="'required|in:1,2,3,4'" v-model="editQuestion.trueAns"
                                              data-vv-name="edit_trueOption"
                                              md-value="0" class="md-primary"> جواب صحیح1
                                    </md-radio>
                                </div>
                                <div class="col-sm-3">
                                    <md-radio v-model="editQuestion.trueAns"
                                              data-vv-name="edit_trueOption"
                                              md-value="1" class="md-primary"> جواب صحیح1
                                    </md-radio>
                                </div>
                                <div class="col-sm-3">
                                    <md-radio v-model="editQuestion.trueAns"
                                              data-vv-name="edit_trueOption"
                                              md-value="2" class="md-primary">جواب صحیح2
                                    </md-radio>
                                </div>
                                <div class="col-sm-3">
                                    <md-radio v-model="editQuestion.trueAns"
                                              data-vv-name="edit_trueOption"
                                              md-value="3" class="md-primary">جواب صحیح3
                                    </md-radio>
                                </div>
                                <div class="col-sm-3">
                                    <md-radio v-model="editQuestion.trueAns"
                                              data-vv-name="edit_trueOption"
                                              md-value="4" class="md-primary">جواب صحیح4
                                    </md-radio>
                                </div>
                                <div v-show="errors.has('edit_trueOption')" class="help-block">{{
                                    errors.first('edit_trueOption') }}
                                </div>
                            </div>
                            <br><br>
                        </md-card-content>

                        <md-card-actions>
                            <md-button type="submit" class="md-raised md-primary">اضافه کردن سوال</md-button>
                        </md-card-actions>
                    </form>
                </md-card>
            </div>
        </div>

        <!--QUESTIONS-->
        <div v-if="status == 'questions'">
            <button @click="status = 'show-online-exams'" class="btn btn-danger pull-left btn-sm  hvr-icon-back">
                بازگشت
            </button>
            <button @click="AddQuestion()" class="btn btn-success pull-right">افزودن سوال</button>
            <br><br>
            <div>
                <md-card>
                    <md-card-content>
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <p class="text-center">سوالات امتحان <span class="text-info"
                                                                           v-text="exam.title"> </span></p>
                            </div>
                        </div>
                        <p class="text-center" v-if="chunkedQuestions.length == 0"> برای این امتحان هیچ سوالی طرح نشده
                            است.با استفاده از دکمه <span class="text-success btn"
                                                         @click="AddQuestion()"> افزودن سوال </span> سوالی طرح کنید.
                        </p>
                        <div class="row" v-for="(chunk,chuind) in chunkedQuestions">
                            <div class="col-md-12">
                                <div v-for="(ques,queind) in chunk" class="col-md-4">
                                    <md-card>
                                        <md-card-header>
                                            <h3 class="box-title"> سوال {{ chuind*chunkedQuestions[0].length+queind+1
                                                }}</h3>
                                        </md-card-header>

                                        <md-card-content>
                                            <div class="form-group">
                                                <label class="col-sm-12 label-primary">سوال</label>
                                                <div class="col-sm-12 "><span class="fa fa-question"></span> {{
                                                    ques.question }}
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-12 label-info">گزینه ها</label>
                                                <div class="col-sm-12">
                                                    <div v-for="(ans,anskey) in ques.answers.split('|:br:|')">
                                                        <p>
                                                            <span class="fa fa-pencil"></span> {{ ans }}
                                                            <span v-if="anskey+1 == ques.trueAnswer"
                                                                  class="fa fa-check-square-o"
                                                                  style="color: #00a157"></span>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </md-card-content>

                                        <md-card-actions>
                                            <md-button
                                                    @click="DelQuestion(ques,chuind*chunkedQuestions[0].length+queind)">
                                                حذف
                                            </md-button>
                                            <md-button
                                                    @click="EditQuestion(ques,[chuind,queind])">
                                                ویرایش
                                            </md-button>
                                        </md-card-actions>
                                    </md-card>
                                    <br>
                                </div>
                            </div>
                            <hr v-if="chunkedQuestions.length >1">
                        </div>
                        <br>
                    </md-card-content>
                </md-card>
            </div>
        </div>

        <!--TAKE EXAM-->
        <div v-if="status == 'take-exam'">
            <md-card>

                <div v-if="questions.length>0">
                    <md-card-header>
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <p class="text-center">سوالات امتحان <span class="text-info"
                                                                           v-text="exam.title"> </span></p>
                            </div>
                        </div>
                    </md-card-header>
                    <form @submit.prevent="StoreAnswers()" class="form-horizontal">

                        <md-card-content>

                            <div v-for="(ques,index) in questions" class="row exam-question-card">
                                <md-card>
                                    <md-card-header>
                                        <div class="md-title">{{ index+1 }} - {{ ques.question }}</div>
                                    </md-card-header>

                                    <md-card-content>
                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <div v-for="(ans,anskey) in ques.answers.split('|:br:|')">

                                                    <md-theme md-name="green">
                                                        <md-radio v-model="answers[index].answer" :md-value="anskey+1"
                                                                  :id="ques.id+''+anskey" :name="'question'+ques.id"
                                                                  class="md-primary"> {{ ans }}
                                                        </md-radio>
                                                    </md-theme>
                                                </div>
                                            </div>
                                        </div>
                                    </md-card-content>
                                </md-card>
                            </div>
                        </md-card-content>

                        <md-card-actions>
                            <md-theme md-name="teal">
                                <md-button type="submit" class="md-raised md-primary">تحویل برگه</md-button>
                            </md-theme>
                        </md-card-actions>
                    </form>
                </div>
                <md-card-content v-else>
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <p class="text-center"><span
                                    class="text-danger"> برای این امتحان هیچ سوالی طرح نشده است.</span>
                            </p>
                        </div>
                    </div>
                </md-card-content>
            </md-card>
        </div>

        <!--SHOW ONLINE EXAM MARKS-->
        <div v-if="status == 'show-marks'">
            <div class=" col-xs-12">
                <a @click="status = 'show-online-exams'"
                   class="btn btn-success btn-flat pull-left btn-sm  hvr-icon-back"> بازگشت </a>
                <br><br>
                <md-table-card>
                    <md-toolbar>
                        <h1 class="md-title">نمرات امتحان</h1>
                        <div class="form-group has-success has-feedback">
                            <input placeholder="جستجو..." type="text" v-model="searchBox" class="form-control input-sm">
                            <span class="fa fa-search form-control-feedback" aria-hidden="true"></span>
                        </div>
                    </md-toolbar>
                    <div class="table-responsive">
                        <table class="table">
                            <md-table md-sort="attach.fullname" md-sort-type="asc" @sort="sortOrder">
                                <md-table-header>
                                    <md-table-row>
                                        <md-table-head md-sort-by="title">دانش آموز</md-table-head>
                                        <md-table-head md-sort-by="mark">نمره</md-table-head>
                                        <md-table-head md-sort-by="attach.exam_date">زمان امتحان</md-table-head>
                                    </md-table-row>
                                </md-table-header>

                                <md-table-body>

                                    <md-table-row v-for="(mark, markIndex) in examMarks" :key="markIndex"
                                                  :md-item="mark">
                                        <md-table-cell>
                                            <md-avatar>
                                                <img :src="'images/avatars/'+mark.attach.img" alt="Avatar">
                                            </md-avatar>
                                            {{ mark.attach.fullname }}
                                        </md-table-cell>
                                        <md-table-cell>{{ mark.mark }}</md-table-cell>
                                        <md-table-cell>{{ mark.attach.exam_date }}</md-table-cell>
                                    </md-table-row>
                                </md-table-body>
                            </md-table>

                        </table>
                        <div v-if="examMarks.length<1" class="panel panel-default">
                            <div class="text-center panel-body">
                                <p class=""><span class="fa fa-star cheshmak"></span> به این امتحان پاسخی داده نشده است.
                                </p>
                            </div>
                        </div>
                    </div>
                </md-table-card>
            </div>
        </div>

    </div>

</template>


<script>
    import Multiselect from 'vue-multiselect'

    export default {
        components: { Multiselect },
        props: ['authUser'],
        data() {
            return {
                auth: {},
                exams: [],
                exam: {},
                questions: [],
                question: {},
                classes: [],
                clase: [],
                subjects: [],
                subject: [],
                answers: [],
                examMarks: 0,
                mark: 0,
                addExam: {
                    title: '',
                    description: '',
                    startTime: '',
                    endTime: '',
                    class_ids: '',
                    subject_ids: '',
                    show_mark: false
                },
                addQuestion: {
                    exam_id: '',
                    question: '',
                    ans1: '',
                    ans2: '',
                    ans3: '',
                    ans4: '',
                    trueAns: ''
                },
                editQuestion: {
                    ques_index: '',
                    question_id: '',
                    question: '',
                    ans1: '',
                    ans2: '',
                    ans3: '',
                    ans4: '',
                    trueAns: ''
                },
                searchBox: '',
                sortType: 'asc',
                sortBy: 'title',
                status: 'show-online-exams',
            }
        },
        created: function () {
            this.auth = JSON.parse(this.authUser);
            this.getExam();
        },
        computed: {
            orderedExams() {
                var vm = this;
                return _.filter(_.orderBy(this.exams, this.sortBy, this.sortType), function (exam) {
                    return (exam.title || '').includes(vm.searchBox) || (exam.description || '').includes(vm.searchBox);
                });
            },
            chunkedQuestions() {
                return _.chunk(this.questions, 3);
            }
        },
        methods: {
            getExam() {
                var vm = this;
                vm.waiting(true)
                axios.get('/api/online-exam/get-exams').then(function (response) {
                    Vue.set(vm.$data, 'exams', response.data);
                    vm.waiting(false)
                });
            },
            AddExam() {
                var vm = this;
                Vue.nextTick(() => {
                    this.errors.clear()
                })
                this.ValidateMsg();
                axios.get('/api/online-exam/get-classes').then(function (response) {
                    Vue.set(vm.$data, 'classes', response.data);
                });
                vm.status = 'add-online-exam';
            },
            AddQuestion() {
                var vm = this;
                Vue.nextTick(() => {
                    this.errors.clear()
                })
                this.ValidateMsg();
                vm.status = 'add-question';
            },
            EditQuestion(question, index) {
                var vm = this;
                var answer = question.answers.split('|:br:|')
                vm.editQuestion = {
                    ques_index: index,
                    question_id: question.id,
                    question: question.question,
                    ans1: answer[0],
                    ans2: answer[1],
                    ans3: answer[2],
                    ans4: answer[3],
                    trueAns: question.trueAnswer
                }
                Vue.nextTick(() => {
                    this.errors.clear()
                });
                this.ValidateMsg();
                vm.status = 'edit-question';
            },
            EditExam(exam) {
                var vm = this;
                vm.exam = exam;
                if (vm.exam.showMark == 1) {
                    vm.exam.showMark = true;
                } else {
                    vm.exam.showMark = false;
                }
                Vue.nextTick(() => {
                    this.errors.clear()
                });


                this.ValidateMsg();

                axios.get('/api/online-exam/get-classes').then(function (response) {
                    Vue.set(vm.$data, 'classes', response.data);
                });

                axios.post('/api/online-exam/get-selectclass', {'clase': vm.exam.class_ids}).then(function (response) {
                    Vue.set(vm.$data, 'clase', response.data);
                    var field_ids = [];
                    vm.subjects = [];
                    for (var i in vm.clase) {
                        field_ids.push(vm.clase[i].field_id);
                    }
                    field_ids = field_ids.join(',')
                    axios.get('/api/online-exams/get-subjects/' + field_ids).then(function (response) {
                        Vue.set(vm.$data, 'subjects', response.data);
                        axios.post('/api/online-exam/get-selectsubject', {'subject': vm.exam.subject_ids}).then(function (response) {
                            Vue.set(vm.$data, 'subject', response.data);
                            vm.status = 'edit-online-exam';
                        });
                    });

                });


            },
            getSubjects() {
                var vm = this;
                var field_ids = [];
                vm.subjects = [];
                for (var i in vm.clase) {
                    field_ids.push(vm.clase[i].field_id);
                }

                field_ids = field_ids.join(',')
                axios.get('/api/online-exams/get-subjects/'+field_ids).then(function (response) {
                    Vue.set(vm.$data, 'subjects', response.data);
                });
            },
            getQuestions(exam) {
                var vm = this;
                vm.waiting(true)
                vm.exam = exam;
                if (vm.exam.showMark == 1) {
                    vm.exam.showMark = true;
                } else {
                    vm.exam.showMark = false;
                }
                axios.get('/api/online-exams/get-questions/' + exam.id).then(function (response) {
                    Vue.set(vm.$data, 'questions', response.data);
                    vm.status = 'questions';
                    vm.waiting(false)
                });
            },
            getExamQuestions(exam) {
                var vm = this;
                vm.waiting(true)
                vm.exam = exam;
                axios.get('/api/online-exams/get-questions/' + exam.id).then(function (response) {
                    Vue.set(vm.$data, 'questions', response.data);
                    for (var question of response.data) {
                        var obj = {
                            question_id: question.id,
                            answer: '',
                            trueAnswer: question.trueAnswer,
                        }
                        vm.answers.push(obj);
                    }
                    vm.status = 'take-exam';
                    vm.waiting(false)
                });
            },
            StoreExam() {
                var vm = this;
                vm.addExam.class_ids = [];
                vm.addExam.subject_ids = [];
                for (var i in vm.clase) {
                    vm.addExam.class_ids.push(vm.clase[i].id);
                }
                for (var i in vm.subject) {
                    vm.addExam.subject_ids.push(vm.subject[i].id);
                }
                this.$validator.validateAll({
                    add_class: vm.addExam.class_ids,
                    add_subject: vm.addExam.subject_ids,
                    add_title: vm.addExam.title,
                    add_description: vm.addExam.description,
                    add_start: vm.addExam.startTime,
                    add_end: vm.addExam.endTime,
                }).then(function (validate) {
                    if (validate) {
                        vm.waiting(true)

                        axios.post('/api/online-exam/store-exam', vm.addExam).then(function (response) {
                            if (response.status == 200) {
                                noty({
                                    type: 'success',
                                    layout: 'topLeft',
                                    text: 'امتحان با موفقیت ایجاد شد.',
                                    timeout: 3000,
                                });
                                document.getElementById('noty_audio').play();
                                vm.addExam = {
                                    title: '',
                                    description: '',
                                    startTime: '',
                                    endTime: '',
                                    class_ids: '',
                                    subject_ids: '',
                                    show_mark: false
                                };
                                Vue.nextTick(() => {
                                    vm.errors.clear()
                                })
                                vm.ValidateMsg();
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
            UpdateExam() {
                var vm = this;
                vm.exam.class_ids = [];
                vm.exam.subject_ids = [];
                for (var i in vm.clase) {
                    vm.exam.class_ids.push(vm.clase[i].id);
                }
                for (var i in vm.subject) {
                    vm.exam.subject_ids.push(vm.subject[i].id);
                }
                this.$validator.validateAll({
                    edit_class: vm.exam.class_ids,
                    edit_subject: vm.exam.subject_ids,
                    edit_title: vm.exam.title,
                    edit_description: vm.exam.description,
                    edit_start: vm.exam.date,
                    edit_end: vm.exam.endDate,
                }).then(function (validate) {
                    if (validate) {
                        vm.waiting(true)

                        axios.post('/api/online-exam/update-exam', vm.exam).then(function (response) {
                            if (response.status == 200) {
                                noty({
                                    type: 'success',
                                    layout: 'topLeft',
                                    text: 'امتحان با موفقیت بروز شد.',
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
            StoreQuestion() {
                var vm = this;
                vm.addQuestion.exam_id = vm.exam.id;
                this.$validator.validateAll({
                    add_question: vm.addQuestion.question,
                    add_option1: vm.addQuestion.ans1,
                    add_option2: vm.addQuestion.ans2,
                    add_option3: vm.addQuestion.ans3,
                    add_option4: vm.addQuestion.ans4,
                    add_trueOption: vm.addQuestion.trueAns
                }).then(function (validate) {
                    if (validate) {
                        vm.waiting(true)

                        axios.post('/api/online-exam/store-question', vm.addQuestion).then(function (response) {
                            if (response.status == 200) {
                                noty({
                                    type: 'success',
                                    layout: 'topLeft',
                                    text: 'سوال با موفقیت به امتحان اضافه شد.',
                                    timeout: 3000,
                                });
                                document.getElementById('noty_audio').play();
                                vm.addQuestion = {
                                    question: '',
                                    ans1: '',
                                    ans2: '',
                                    ans3: '',
                                    ans4: '',
                                    trueAns: ''
                                };
                                vm.questions.push(response.data);
                                Vue.nextTick(() => {
                                    vm.errors.clear()
                                })
                                vm.ValidateMsg();
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
            UpdateQuestion() {
                var vm = this;
                vm.addQuestion.exam_id = vm.exam.id;
                this.$validator.validateAll({
                    add_question: vm.editQuestion.question,
                    add_option1: vm.editQuestion.ans1,
                    add_option2: vm.editQuestion.ans2,
                    add_option3: vm.editQuestion.ans3,
                    add_option4: vm.editQuestion.ans4,
                    add_trueOption: vm.editQuestion.trueAns
                }).then(function (validate) {
                    if (validate) {
                        vm.waiting(true)

                        axios.post('/api/online-exam/update-question', vm.editQuestion).then(function (response) {
                            if (response.status == 200) {
                                //update in questions
                                vm.questions[vm.editQuestion.ques_index[0] * vm.chunkedQuestions[0].length + vm.editQuestion.ques_index[1]] = response.data;
                                //update in cumputed chunked questions
                                vm.chunkedQuestions[vm.editQuestion.ques_index[0]][vm.editQuestion.ques_index[1]] = response.data;
                                noty({
                                    type: 'success',
                                    layout: 'topLeft',
                                    text: 'سوال با موفقیت بروزرسانی شد.',
                                    timeout: 3000,
                                });
                                document.getElementById('noty_audio').play();
                                vm.status = 'questions';
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
            StoreAnswers() {
                var vm = this;
                vm.waiting(true)
                for (var answer of vm.answers) {
                    if (answer.answer == answer.trueAnswer) {
                        vm.mark = vm.mark + 1;
                    }
                }
                axios.post('/api/online-exam/store-answers', {
                    'answers': JSON.stringify(vm.answers),
                    'exam_id': vm.exam.id,
                    'mark': vm.mark
                }).then(function (response) {
                    if (response.status == 200) {
                        noty({
                            type: 'success',
                            layout: 'topLeft',
                            text: 'امتحان با موفقیت برگزار شد.',
                            timeout: 3000,
                        });
                        document.getElementById('noty_audio').play();
                        vm.status = 'show-online-exams';
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
            DelQuestion(question, index) {
                var vm = this;
                swal({
                    title: 'حذف سوال',
                    text: 'از حذف سوال مطمئن هستید؟',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'بله',
                    cancelButtonText: 'خیر'
                }).then(function () {
                    vm.waiting(true)

                    axios.post('/api/online-exam/delete-question', {question_id: question.id}).then(function (response) {
                        if (response.status == 200) {
                            Vue.delete(vm.questions, index);
                            noty({
                                type: 'success',
                                layout: 'topLeft',
                                text: 'سوال حذف شد.',
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
            DelExam(question, index) {
                var vm = this;
                swal({
                    title: 'حذف امتحان',
                    text: 'از حذف امتحان مطمئن هستید؟',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'بله',
                    cancelButtonText: 'خیر'
                }).then(function () {
                    vm.waiting(true)

                    axios.post('/api/online-exam/delete', {exam_id: exam.id}).then(function (response) {
                        if (response.status == 200) {
                            Vue.delete(vm.questions, index);
                            noty({
                                type: 'success',
                                layout: 'topLeft',
                                text: 'سوال حذف شد.',
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
                    'fa': {
                        custom: {
                            add_class: {
                                required: 'کلاس را انتخاب کنید.'
                            },
                            add_subject: {
                                required: 'درس را انتخاب کنید.',
                            },
                            add_title: {
                                required: 'عنوان باید انتخاب شود.',
                                max: 'عنوان حداکثر 20 حرف می تواند باشد.'
                            },
                            add_description: {
                                max: 'توضیحات حداکثر 255 حرف می تواند باشد.'
                            },
                            add_start: {
                                required: 'زمان شروع باید انتخاب شود.',
                                date_format: 'زمان شروع باید به فرم "روز/ماه/سال ساعت:دقیقه" باشد.'
                            },
                            add_end: {
                                required: 'زمان پایان باید انتخاب شود.',
                                date_format: 'زمان پایان باید به فرم "روز/ماه/سال ساعت:دقیقه" باشد.'
                            },
                            edit_class: {
                                required: 'کلاس را انتخاب کنید.'
                            },
                            edit_subject: {
                                required: 'درس را انتخاب کنید.',
                            },
                            edit_title: {
                                required: 'عنوان باید انتخاب شود.',
                                max: 'عنوان حداکثر 20 حرف می تواند باشد.'
                            },
                            edit_description: {
                                max: 'توضیحات حداکثر 255 حرف می تواند باشد.'
                            },
                            edit_start: {
                                required: 'زمان شروع باید انتخاب شود.',
                                date_format: 'زمان شروع باید به فرم "روز/ماه/سال ساعت:دقیقه" باشد.'
                            },
                            edit_end: {
                                required: 'زمان پایان باید انتخاب شود.',
                                date_format: 'زمان پایان باید به فرم "روز/ماه/سال ساعت:دقیقه" باشد.'
                            },
                            add_question: {
                                required: 'سوال را بنویسید.',
                                max: 'سوال حداکثر 255 حرف می تواند باشد.'
                            },
                            add_option1: {
                                required: 'گزینه 1 باید پر شود.',
                                max: 'گزینه 1 حداکثر 70 حرف می تواند باشد.'
                            },
                            add_option2: {
                                required: 'گزینه 2 باید پر شود.',
                                max: 'گزینه 2 حداکثر 70 حرف می تواند باشد.'
                            },
                            add_option3: {
                                required: 'گزینه 3 باید پر شود.',
                                max: 'گزینه 3 حداکثر 70 حرف می تواند باشد.'
                            },
                            add_option4: {
                                required: 'گزینه 4 باید پر شود.',
                                max: 'گزینه 4 حداکثر 70 حرف می تواند باشد.'
                            },
                            add_trueOption: {
                                required: 'گزینه صحیح را انتخاب کنید.',
                            },
                            edit_question: {
                                required: 'سوال را بنویسید.',
                                max: 'سوال حداکثر 255 حرف می تواند باشد.'
                            },
                            edit_option1: {
                                required: 'گزینه 1 باید پر شود.',
                                max: 'گزینه 1 حداکثر 70 حرف می تواند باشد.'
                            },
                            edit_option2: {
                                required: 'گزینه 2 باید پر شود.',
                                max: 'گزینه 2 حداکثر 70 حرف می تواند باشد.'
                            },
                            edit_option3: {
                                required: 'گزینه 3 باید پر شود.',
                                max: 'گزینه 3 حداکثر 70 حرف می تواند باشد.'
                            },
                            edit_option4: {
                                required: 'گزینه 4 باید پر شود.',
                                max: 'گزینه 4 حداکثر 70 حرف می تواند باشد.'
                            },
                            edit_trueOption: {
                                required: 'گزینه صحیح را انتخاب کنید.',
                            },
                        },

                    }
                });
                this.$validator.setLocale('fa');
            },
            showMarks(exam) {
                var vm = this;
                vm.waiting(true)
                vm.exam = exam;
                if (vm.exam.showMark == 1) {
                    vm.exam.showMark = true;
                } else {
                    vm.exam.showMark = false;
                }

                axios.get('/api/online-exam/get-marks/' + exam.id).then(function (response) {
                    Vue.set(vm.$data, 'examMarks', response.data);
                    vm.status = 'show-marks';
                    vm.waiting(false)
                });

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
