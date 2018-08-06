<?php


use App\User;
use Illuminate\Support\Facades\Auth;

function flash($title = NULL , $message = NULL){
    $flash = app('\App\Http\Flash');
    if (func_num_args()==0){
        return $flash;
    }
    return $flash->info($title, $message);
}

function eduYear(){
    return \App\Year::find(Auth::user()->year_id);
}

function thisYear(){
    return jDateTime::date('Y',false,false)."-".(jDateTime::date('Y',false,false)+1);
}

function years(){
    return \App\Year::all();
}

function getAuth(){
    return User::where('id',Auth::id())->select('role','username','photo')->first();
}

function eduYearMonth(){
    return [
        'meh' => 'مهر',
        'aba' => 'آبان',
        'aza' => 'آذر',
        'dey' => 'دی',
        'bah' => 'بهمن',
        'esf' => 'اسفند',
        'far' => 'فروردین',
        'ord' => 'اردیبهشت',
        'kho' => 'خرداد',
    ];
}

function monthsToAdad($month){
    switch ($month){
        case 'far':
            return 1;
            break;
        case 'ord':
            return 2;
            break;
        case 'kho':
            return 3;
            break;
        case 'tir':
            return 4;
            break;
        case 'mor':
            return 5;
            break;
        case 'sha':
            return 6;
            break;
        case 'meh':
            return 7;
            break;
        case 'aba':
            return 8;
            break;
        case 'aza':
            return 9;
            break;
        case 'dey':
            return 10;
            break;
        case 'bah':
            return 11;
            break;
        case 'esf':
            return 12;
            break;
    }
}

function adadToMonth($month){
    switch ($month){
        case 1:
            return 'far';
            break;
        case 2:
            return 'ord';
            break;
        case 3:
            return 'kho';
            break;
        case 4:
            return 'tir';
            break;
        case 5:
            return 'mor';
            break;
        case 6:
            return 'sha';
            break;
        case 7:
            return 'meh';
            break;
        case 8:
            return 'aba';
            break;
        case 9:
            return 'aza';
            break;
        case 10:
            return 'dey';
            break;
        case 11:
            return 'bah';
            break;
        case 12:
            return 'esf';
            break;
    }
}

function daysOfWeek(){
    return [
        'شنبه',
        'یکشنبه',
        'دوشنبه',
        'سه شنبه',
        'چهارشنبه',
        'پنجشنبه',
        'جمعه'
    ];
}

function houres(){
    return [
        0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23
    ];
}

function minutes(){
    return [
        0,5,10,15,20,15,20,25,30,35,40,45,50,55
    ];
}

function codeToColor($code)
{
    switch ($code){
        case '#00c0ef':
            echo "aqua";
            break;
        case '#0073b7':
            echo "blue";
            break;
        case '':
            echo "blue";
            break;
        case '#3c8dbc':
            echo "light-blue";
            break;
        case '#39cccc':
            echo "teal";
            break;
        case '#f39c12':
            echo "yellow";
            break;
        case '#ff851b':
            echo "orange";
            break;
        case '#00a65a':
            echo "green";
            break;
        case '#01ff70':
            echo "lime";
            break;
        case '#dd4b39':
            echo "red";
            break;
        case '#605ca8':
            echo "purple";
            break;
        case '#f012be':
            echo "fuchsia";
            break;
        case '#777777':
            echo "muted";
            break;
        case '#001f3f':
            echo "navy";
            break;
    }
}

function colorToCode($color)
{
    switch ($color){
        case 'aqua':
            echo "#00c0ef";
            break;
        case 'blue':
            echo "#0073b7";
            break;
        case 'light-blue':
            echo "#3c8dbc";
            break;
        case 'teal':
            echo "#39cccc";
            break;
        case 'yellow':
            echo "#f39c12";
            break;
        case 'orange':
            echo "#ff851b";
            break;
        case 'green':
            echo "#00a65a";
            break;
        case 'lime':
            echo "#01ff70";
            break;
        case 'red':
            echo "#dd4b39";
            break;
        case 'purple':
            echo "#605ca8";
            break;
        case 'fuchsia':
            echo "#f012be";
            break;
        case 'muted':
            echo "#777777";
            break;
        case 'navy':
            echo "#001f3f";
            break;
    }
}
