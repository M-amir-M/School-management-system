<?php

namespace App\Http\Controllers;

use App\Clase;
use App\ClassSchedule;
use App\Cuy;
use App\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClassSchedulesController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin',['only' => ['store','update','delete']]);
    }

    public function getClasses()
    {
        $auth_cuy = Cuy::find(Auth::user()->cuy_id);
        $classes_id = explode('|::|',$auth_cuy->clase);
        $classes = Clase::whereIn('id',$classes_id)->get();
        return $classes;
    }

    public function getSchedules($class_id)
    {
        $schedules = ClassSchedule::where(['clase_id' => $class_id,'year_id' => Auth::user()->year_id])
            ->orderBy('start_time')
            ->get();
        $subjects = Subject::getClassSubject($class_id);
        return ['schedules' => $schedules,'subjects' =>$subjects];
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'subject_id' => 'required',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'day_week' => 'required',
            'class_id' => 'required',
        ]);
        $schedule = ClassSchedule::create([
            'clase_id' => $request->class_id,
            'subject_id' => $request->subject_id,
            'year_id' => Auth::user()->year_id,
            'day_week' => $request->day_week,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);
        return response($schedule);
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'subject_id' => 'required',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'day_week' => 'required',
            'class_id' => 'required',
        ]);
        $schedule = ClassSchedule::find($request->id);
        $schedule->update([
            'subject_id' => $request->subject_id,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);
    }

    public function show()
    {
        $auth_user = getAuth();
        return view('classschedules.class_schedules',compact('auth_user'));
    }

    public function delete(Request $request)
    {
        $schedule = ClassSchedule::find($request->schedule_id);
        $schedule->delete();
    }
    
}
