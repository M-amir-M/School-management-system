<?php

namespace App\Http\Controllers;

use App\Clase;
use App\News;
use App\Poll;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function getNews()
    {
        if (Auth::user()->role != 'admin'){
            return News::where(['target' => Auth::user()->role.'s'])->orWhere('target','all')->orderBy('updated_at')->get();
        }
        return News::all();
    }

    public function getPoll()
    {
        return Poll::with('options')->where(['status' => 1])->whereIn('target',['all',Auth::user()->role])->get();
    }

    public function headInfo()
    {
        $students = User::where(['role' => 'student', 'activated' => 1])
            ->whereHas('cuys', function ($query) {
                $query->where(['year_id' => Auth::user()->year_id]);
            })
            ->orderBy('lname', 'ASC')
            ->orderBy('fname', 'ASC')
            ->get();
        $teachers = User::where(['role' => 'teacher', 'activated' => 1])
            ->whereHas('cuys', function ($query) {
                $query->where(['year_id' => Auth::user()->year_id]);
            })
            ->orderBy('lname', 'ASC')
            ->orderBy('fname', 'ASC')
            ->get();
        $classes = Clase::all();
        $auth_user = Auth::user();
        return [
            'classes' => count($classes),
            'teachers' => count($teachers),
            'students' => count($students),
            'auth' => $auth_user,
        ];


    }
}
