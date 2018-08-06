<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::get('/', [
    'uses' => 'HomeController@index',
    'as' => 'home'
]);

Route::group(['middleware' => 'auth'],function() {
    
    Route::post('/api/get-auth',[
        'uses' => 'HomeController@getAuth',
    ] );
    
    Route::get('/dashboard',[
        'uses' => 'HomeController@dashboard',
        'as' => 'dashboard'
    ] );
    
    Route::get('/api/get-grades',[
        'uses' => 'GradesController@getGrade',
        'as' => 'grade.get'
    ] );
    
    Route::post('/api/store-grade',[
        'uses' => 'GradesController@store',
        'as' => 'grade.store'
    ] );
    
    Route::post('/api/update-grade',[
        'uses' => 'GradesController@update',
        'as' => 'grade.update'
    ] );
    
    Route::post('/api/delete-grade',[
        'uses' => 'GradesController@delete',
        'as' => 'grade.delete'
    ] );
    
    Route::get('/grades',[
        'uses' => 'GradesController@show',
        'as' => 'grade.show'
    ] );

    /////////////////////////////////////////////////
    
    Route::get('/api/get-fields',[
        'uses' => 'FieldsController@getField',
        'as' => 'field.get'
    ] );

    Route::post('/api/store-field',[
        'uses' => 'FieldsController@store',
        'as' => 'field.store'
    ] );

    Route::post('/api/update-field',[
        'uses' => 'FieldsController@update',
        'as' => 'field.update'
    ] );

    Route::post('/api/delete-field',[
        'uses' => 'FieldsController@delete',
        'as' => 'field.delete'
    ] );

    Route::get('/fields',[
        'uses' => 'FieldsController@show',
        'as' => 'field.show'
    ] );

    /////////////////////////////////////////////////
    
    Route::get('/api/get-clases',[
        'uses' => 'ClasesController@getClase',
        'as' => 'clase.get'
    ] );



    Route::post('/api/store-clase',[
        'uses' => 'ClasesController@store',
        'as' => 'clase.store'
    ] );

    Route::post('/api/update-clase',[
        'uses' => 'ClasesController@update',
        'as' => 'clase.update'
    ] );

    Route::post('/api/delete-clase',[
        'uses' => 'ClasesController@delete',
        'as' => 'clase.delete'
    ] );

    Route::get('/clases',[
        'uses' => 'ClasesController@show',
        'as' => 'clase.show'
    ] );

    /////////////////////////////////////////////////


    Route::get('/api/dashboard/get-news',[
        'uses' => 'DashboardController@getNews',
    ] );

    Route::get('/api/dashboard/get-poll',[
        'uses' => 'DashboardController@getPoll',
    ] );


    Route::get('/api/dashboard/get-headinfo',[
        'uses' => 'DashboardController@headInfo',
    ] );





    ////////////////////////////////////////////////
    
    Route::get('/api/get-subjects',[
        'uses' => 'SubjectsController@getSubject',
        'as' => 'subject.get'
    ] );

    Route::get('/api/subject/get-user-subjects',[
        'uses' => 'SubjectsController@getUserSubjects',
    ] );

    Route::post('/api/store-subject',[
        'uses' => 'SubjectsController@store',
        'as' => 'subject.store'
    ] );

    Route::post('/api/update-subject',[
        'uses' => 'SubjectsController@update',
        'as' => 'subject.update'
    ] );

    Route::post('/api/delete-subject',[
        'uses' => 'SubjectsController@delete',
        'as' => 'subject.delete'
    ] );

    Route::get('/api/get-class-subject/{class_id}',[
        'uses' => 'SubjectsController@getClassSubject',
    ] );

    Route::get('/subjects',[
        'uses' => 'SubjectsController@show',
        'as' => 'subject.show'
    ] );


    /////////////////////////////////////////////////

    Route::get('/api/get-years',[
        'uses' => 'YearsController@getYear',
        'as' => 'year.get'
    ] );

    Route::post('/api/store-year',[
        'uses' => 'YearsController@store',
        'as' => 'year.store'
    ] );

    Route::post('/api/update-year',[
        'uses' => 'YearsController@update',
        'as' => 'year.update'
    ] );

    Route::post('/api/change-year',[
        'uses' => 'YearsController@change',
        'as' => 'year.change'
    ] );

    Route::post('/api/delete-year',[
        'uses' => 'YearsController@delete',
        'as' => 'year.delete'
    ] );

    Route::get('/years',[
        'uses' => 'YearsController@show',
        'as' => 'year.show'
    ] );


    /////////////////////////////////////////////////

    Route::get('/api/get-students',[
        'uses' => 'StudentsController@getStudent',
        'as' => 'student.get'
    ] );

    Route::post('/api/student/get-promotion-students',[
        'uses' => 'StudentsController@getPromotionStudents',
    ] );

    Route::post('/api/student/do-promote-students',[
        'uses' => 'StudentsController@doPromotionStudents',
    ] );

    Route::get('/api/student/get-waits',[
        'uses' => 'StudentsController@getWait',
        'as' => 'student.getwait'
    ] );

    Route::get('/api/students/check-username/{username}',[
        'uses' => 'StudentsController@checkUsername'
    ] );

    Route::post('/api/student/set-wait',[
        'uses' => 'StudentsController@waitOk',
        'as' => 'student.setwait'
    ] );
    
    Route::post('/api/store-student',[
        'uses' => 'StudentsController@store',
        'as' => 'student.store'
    ] );

    Route::post('/api/update-student',[
        'uses' => 'StudentsController@update',
        'as' => 'student.update'
    ] );

    Route::post('/api/delete-student',[
        'uses' => 'StudentsController@delete',
        'as' => 'student.delete'
    ] );

    Route::get('/students',[
        'uses' => 'StudentsController@show',
        'as' => 'student.show'
    ] );

    Route::get('/promotion',[
        'uses' => 'StudentsController@promotion',
        'as' => 'student.promotion'
    ] );



    /////////////////////////////////////////////////

    Route::get('/api/get-teachers',[
        'uses' => 'TeachersController@getTeacher',
        'as' => 'teacher.get'
    ] );

    Route::post('/api/store-teacher',[
        'uses' => 'TeachersController@store',
        'as' => 'teacher.store'
    ] );

    Route::post('/api/update-teacher',[
        'uses' => 'TeachersController@update',
        'as' => 'teacher.update'
    ] );

    Route::post('/api/delete-teacher',[
        'uses' => 'TeachersController@delete',
        'as' => 'teacher.delete'
    ] );

    Route::post('/api/teacher/get-selectsubject',[
        'uses' => 'TeachersController@selectSubject',
    ] );

    Route::post('/api/teacher/get-selectclass',[
        'uses' => 'TeachersController@selectClass',
    ] );

    Route::get('/teachers',[
        'uses' => 'TeachersController@show',
        'as' => 'teacher.show'
    ] );


    /////////////////////////////////////////////////



    Route::get('/api/get-parents',[
        'uses' => 'ParentsController@getParent',
        'as' => 'parent.get'
    ] );

    Route::post('/api/store-parent',[
        'uses' => 'ParentsController@store',
        'as' => 'parent.store'
    ] );

    Route::post('/api/update-parent',[
        'uses' => 'ParentsController@update',
        'as' => 'parent.update'
    ] );

    Route::post('/api/delete-parent',[
        'uses' => 'ParentsController@delete',
        'as' => 'parent.delete'
    ] );

    Route::get('/parents',[
        'uses' => 'ParentsController@show',
        'as' => 'parent.show'
    ] );

    Route::get('/api/parent/get-students-of-parents/{parent_id}',[
        'uses' => 'ParentsController@getStudentOfParent',
    ] );

    Route::get('/api/parent/link-to-parent/{search}',[
        'uses' => 'ParentsController@linkToParent'
    ] );



    /////////////////////////////////////////////////

    Route::get('/api/get-classes',[
        'uses' => 'ClassSchedulesController@getClasses',
        'as' => 'classes.get'
    ] );

    Route::get('/api/get-schedules/{class_id}',[
        'uses' => 'ClassSchedulesController@getSchedules',
        'as' => 'schedules.get'
    ] );

    Route::post('/api/store-schedule',[
        'uses' => 'ClassSchedulesController@store',
        'as' => 'schedule.store'
    ] );

    Route::post('/api/update-schedule',[
        'uses' => 'ClassSchedulesController@update',
        'as' => 'schedule.update'
    ] );

    Route::post('/api/delete-schedule',[
        'uses' => 'ClassSchedulesController@delete',
        'as' => 'schedule.delete'
    ] );

    Route::get('/class-schedules',[
        'uses' => 'ClassSchedulesController@show',
        'as' => 'classschedule.show'
    ] );



    /////////////////////////////////////////////////

    Route::get('/api/get-class-marks/{username}',[
        'uses' => 'MarksController@getClassMark',
        'as' => 'classmark.get'
    ] );

    Route::get('/api/get-payani-marks/{username}',[
        'uses' => 'MarksController@getPayaniMark',
        'as' => 'payanimark.get'
    ] );

    Route::post('/api/store-class-mark',[
        'uses' => 'MarksController@ClassMarkStore',
        'as' => 'classmark.store'
    ] );

    Route::post('/api/store-payani-mark',[
        'uses' => 'MarksController@PayaniMarkStore',
        'as' => 'payanimark.store'
    ] );

    Route::post('/api/update-class-mark',[
        'uses' => 'MarksController@ClassMarkUpdate',
        'as' => 'classmark.update'
    ] );

    Route::post('/api/update-payani-mark',[
        'uses' => 'MarksController@PayaniMarkUpdate',
        'as' => 'payanimark.update'
    ] );

    Route::post('/api/delete-class-mark',[
        'uses' => 'MarksController@ClassMarkDelete',
        'as' => 'classmark.delete'
    ] );

    ///////////////////////////////////

    Route::get('/api/get-total-month-workbook/{username}',[
        'uses' => 'MarksController@TotalMonthWorkbook',
        'as' => 'workbook.totalmonth'
    ]);
    
    Route::get('/api/get-year-workbook/{username}',[
        'uses' => 'MarksController@YearWorkbook',
        'as' => 'workbook.year'
    ]);
    
    Route::get('/api/get-month-mark-students/{username}',[
        'uses' => 'MarksController@MonthMarkStudents',
        'as' => 'workbook.monthmarkstudents'
    ]);
    
    Route::get('/api/get-year-rate-students/{username}',[
        'uses' => 'MarksController@YearRateStudents',
        'as' => 'workbook.monthmarkstudents'
    ]);
    
    Route::get('/api/get-year-avg-rates/{username}',[
        'uses' => 'MarksController@YearAvgRates',
        'as' => 'workbook.monthmarkstudents'
    ]);

    Route::get('/students/make-workbooks',[
        'uses' => 'MarksController@MakeWorkbook'
    ] );

    
    /////////////////////////////////////////////////

   
    Route::get('/api/attendance/get-classes',[
        'uses' => 'AttendancesController@getClasses',
    ] );

    Route::post('/api/attendance/get-subjects',[
        'uses' => 'AttendancesController@getSubjects',
    ] );
    
    Route::post('/api/attendance/get-students',[
        'uses' => 'AttendancesController@getStudents',
    ] );
    
    Route::post('/api/attendance/get-attendances',[
        'uses' => 'AttendancesController@getAttendances',
    ] );

    Route::post('/api/store-attendance',[
        'uses' => 'AttendancesController@store',
    ] );

    Route::get('/attendances',[
        'uses' => 'AttendancesController@show',
        'as' => 'attendance.show'
    ] );



    ///////////////////////////////////
    
    Route::get('/api/online-exam/get-exams',[
        'uses' => 'OnlineExamsController@getExams',
    ] );

    Route::get('/api/online-exam/get-classes',[
        'uses' => 'OnlineExamsController@getClasses',
    ] );

    Route::post('/api/online-exam/get-selectclass',[
        'uses' => 'OnlineExamsController@selectClass',
    ] );

    Route::post('/api/online-exam/get-selectsubject',[
        'uses' => 'OnlineExamsController@selectSubject',
    ] );

    Route::get('/api/online-exams/get-subjects/{field_ids}',[
        'uses' => 'OnlineExamsController@getSubjects'
    ] );

    Route::get('/api/online-exams/get-questions/{exam_id}',[
        'uses' => 'OnlineExamsController@getQuestions'
    ] );

    Route::post('/api/online-exam/store-exam',[
        'uses' => 'OnlineExamsController@store'
    ] );

    Route::post('/api/online-exam/update-exam',[
        'uses' => 'OnlineExamsController@update'
    ] );

    Route::post('/api/online-exam/store-question',[
        'uses' => 'OnlineExamsController@storeQuestion'
    ] );

    Route::post('/api/online-exam/update-question',[
        'uses' => 'OnlineExamsController@updateQuestion'
    ] );


    Route::post('/api/online-exam/store-answers',[
        'uses' => 'OnlineExamsController@storeAnswers'
    ] );

    Route::post('/api/online-exam/delete-question',[
        'uses' => 'OnlineExamsController@deleteQuestion'
    ] );

    Route::post('/api/online-exam/delete',[
        'uses' => 'OnlineExamsController@delete'
    ] );

    Route::get('/api/online-exam/get-marks/{exam_id}',[
        'uses' => 'OnlineExamsController@getMarks',
    ] );
    
    Route::get('/online-exams',[
        'uses' => 'OnlineExamsController@show',
        'as' => 'onlineexam.show'
    ] );
    
    ///////////////////////////////////
    
    Route::get('/api/get-polls',[
        'uses' => 'PollsController@getPolls',
        'as' => 'poll.get'
    ] );

    Route::get('/api/get-poll-options/{poll_id}',[
        'uses' => 'PollsController@getPollOptions',
        'as' => 'poll.get'
    ] );

    Route::post('/api/store-poll',[
        'uses' => 'PollsController@store',
        'as' => 'poll.store'
    ] );

    Route::post('/api/store-vote',[
        'uses' => 'PollsController@storeVote'
    ] );

    Route::post('/api/store-poll-option',[
        'uses' => 'PollsController@storeOption'
    ] );

    Route::post('/api/update-poll-option',[
        'uses' => 'PollsController@updateOption'
    ] );

    Route::post('/api/update-poll',[
        'uses' => 'PollsController@update',
        'as' => 'poll.update'
    ] );

    Route::post('/api/delete-poll',[
        'uses' => 'PollsController@delete',
        'as' => 'poll.delete'
    ] );

    Route::post('/api/delete-poll-option',[
        'uses' => 'PollsController@deleteOption',
        'as' => 'poll.delete'
    ] );

    Route::get('/polls',[
        'uses' => 'PollsController@show',
        'as' => 'poll.show'
    ] );
/////////////////////////////////////////////////////


    Route::get('/api/get-news',[
        'uses' => 'NewsController@getNews',
    ] );

    Route::post('/api/store-news',[
        'uses' => 'NewsController@store',
        'as' => 'news.store'
    ] );

    Route::post('/api/update-news',[
        'uses' => 'NewsController@update',
        'as' => 'news.update'
    ] );

    Route::post('/api/delete-news',[
        'uses' => 'NewsController@delete',
        'as' => 'news.delete'
    ] );

    Route::get('/news',[
        'uses' => 'NewsController@show',
        'as' => 'news.show'
    ] );


/////////////////////////////////////////////////////



    Route::get('/api/library/get-books',[
        'uses' => 'LibrariesController@getBooks',
    ] );

    Route::post('/api/library/store-book',[
        'uses' => 'LibrariesController@store',
        'as' => 'library.store'
    ] );

    Route::post('/api/library/update-book',[
        'uses' => 'LibrariesController@update',
        'as' => 'library.update'
    ] );

    Route::post('/api/library/delete-book',[
        'uses' => 'LibrariesController@delete',
        'as' => 'library.delete'
    ] );

    Route::get('/library',[
        'uses' => 'LibrariesController@show',
        'as' => 'library.show'
    ] );

/////////////////////////////////////////////////////

    Route::get('/api/assignment/get-assignments',[
        'uses' => 'AssignmentsController@getAssignments',
    ] );

    Route::post('/api/assignment/store-assignment',[
        'uses' => 'AssignmentsController@store',
        'as' => 'assignment.store'
    ] );

    Route::post('/api/assignment/update-assignment',[
        'uses' => 'AssignmentsController@update',
        'as' => 'assignment.update'
    ] );

    Route::post('/api/assignment/delete-assignment',[
        'uses' => 'AssignmentsController@delete',
        'as' => 'assignment.delete'
    ] );

    Route::get('/api/assignment/get-classes',[
        'uses' => 'AssignmentsController@getClasses',
        'as' => 'assignment.delete'
    ] );

    Route::get('/assignments',[
        'uses' => 'AssignmentsController@show',
        'as' => 'assignment.show'
    ] );








    ///////////////////////////////////
    Route::get('/test/{username}',[
        'uses' => 'MarksController@MakeWorkbook'
    ] );
});