<?php

namespace App\Http\Controllers;

use App\Attendance;
use App\Clase;
use App\Cuy;
use App\Subject;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendancesController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin|teacher', ['only' => [
            'store',
            'getStudents',
            'getAttendances',
        ]]);
    }

    public function getClasses()
    {
        $auth_cuy = Cuy::find(Auth::user()->cuy_id);
        $classes_id = explode('|:br:|',$auth_cuy->clase);
        $classes = Clase::whereIn('id',$classes_id)->get();
        return $classes;
    }

    public function getSubjects(Request $request)
    {
        $subjects = Subject::where(['field_id' => $request->field_id])->get();
        return $subjects;
    }

    public function getStudents(Request $request)
    {
        $auth_cuy = Cuy::where(['user_id' => Auth::user()->id, 'year_id' => Auth::user()->year_id])->first();
        $students = User::where(['role' => 'student', 'activated' => 1])
            ->whereHas('cuys', function ($query) use ($auth_cuy,$request) {
                $query->where(['year_id' => eduYear()->id,'clase' => $request->id]);
            })
            ->orderBy('lname', 'ASC')
            ->orderBy('fname', 'ASC')
            ->get();
        return $students;
    }

    public function getAttendances(Request $request)
    {
        $cuy_ids = Cuy::where(['clase'=> $request->class_id,'role' => 'student','year_id' => Auth::user()->year_id])->get()->pluck('id');
        $attendances = Attendance::where(['date' => $request->date, 'subject_id' => $request->subject_id])
            ->whereIn('cuy_id',$cuy_ids)
            ->get();
        return $attendances;
    }

    public function store(Request $request)
    {
        foreach ($request['students'] as $student){
            $att = Attendance::where([
                'cuy_id' => $student['cuy_id'],
                'subject_id' => $request['subject_id'],
                'date' => $request['date']
            ])->first();
            if(count($att)>0){
                $att->update([
                    'status' => $student['status']
                ]);
            }else{
                Attendance::create([
                    'cuy_id' => $student['cuy_id'],
                    'subject_id' => $request['subject_id'],
                    'date' => $request['date'],
                    'status' => (int)$student['status']
                ]);
            }
        }
        return response($request['students'][0]['status']);
    }
    
    public function show()
    {
        return view('attendance.attendances');
    }
}
