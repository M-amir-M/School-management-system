<?php

namespace App\Http\Controllers;

use App\Clase;
use App\Cuy;
use App\Subject;
use App\TeacherSubject;
use App\User;
use App\Year;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeachersController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin', ['only' => [
            'update',
            'store',
            'show',
            'delete',
        ]]);
    }

    public function getTeacher()
    {
        $teachers = User::where(['role' => 'teacher','activated' => 1])
        ->whereHas('cuys',function($query){
            $query->where(['year_id' => eduYear()->id]);
        })
            ->orderBy('lname','ASC')
            ->orderBy('fname','ASC')
            ->get();
        return $teachers;
    }

    public function show()
    {
        return view('teacher.teachers');
    }

    public function update(Request $request)
    {


        $teacher = User::find($request->id);
        $cuy = Cuy::where(['year_id' => Auth::user()->year_id, 'user_id' => $teacher->id])->first();
        $filename = $teacher->photo;
        if ($request->hasFile('photo')) {
            $this->validate($request, [
                'username' => 'required|unique:users,username,' . $teacher->id,
                'email' => 'required|email|unique:users,email,' . $teacher->id,
                'password' => 'required|min:6',
                'fname' => 'required',
                'lname' => 'required',
                'birthday' => 'nullable',
                'address' => 'max:255',
                'phone' => 'max:15',
                'mobile' => 'max:15',
                'photo' => 'mimes:jpg,jpeg,png|max:2048',
            ]);
            if ($filename != 'male.png' && file_exists('/images/avatars/' . $filename)){
                unlink('/images/avatars/'.$filename);
            }
            $filename = str_slug($request->username, '-') . '-' . str_random(6) . '.' . $request->photo->getClientOriginalExtension();

            $request->photo->move(public_path('/images/avatars'), $filename);

        } else {

            $this->validate($request, [
                'username' => 'required|unique:users,username,' . $teacher->id,
                'email' => 'required|email|unique:users,email,' . $teacher->id,
                'password' => 'required|min:6',
                'fname' => 'required',
                'lname' => 'required',
                'birthday' => 'nullable',
                'address' => 'max:255',
                'phone' => 'max:15',
                'mobile' => 'max:15',
            ]);
        }

        $teacher->update([
            'username' => $request->username,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'fname' => $request->fname,
            'lname' => $request->lname,
            'birthday' => $request->birthday,
            'address' => $request->address,
            'phone' => $request->phone,
            'photo' => $filename,
            'mobile' => $request->mobile,
        ]);

        $cuy->update([
            'clase' => implode('|:br:|',explode(',',$request->clase))
        ]);

        $subjects = TeacherSubject::where(['cuy_id' => $request->cuy_id])->get()->pluck('subject_id')->toArray();
        $diff1 = array_diff(explode(',',$request->subject),$subjects);
        $diff2 = array_diff($subjects,explode(',',$request->subject));
        foreach ($diff1 as $subject){
                //create
                TeacherSubject::create([
                    'cuy_id' => $cuy->id,
                    'subject_id' => $subject
                ]);
        }
        foreach ($diff2 as $subject){
                //delete
                TeacherSubject::where(['cuy_id' => $request->cuy_id,'subject_id' => $subject])->delete();
        }
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'username' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'fname' => 'required',
            'lname' => 'required',
            'clase' => 'required',
            'subject' => 'required',
            'birthday' => 'nullable|date_format:Y/m/d',
            'address' => 'nullable|max:255',
            'phone' => 'nullable|max:15',
            'mobile' => 'nullable|max:15'
        ]);

        $filename = 'male.png';
        if ($request->hasFile('photo')) {
            $filename = str_slug($request->username, '-') . '-' . str_random(6) . '.' . $request->photo->getClientOriginalExtension();

            $request->photo->move(public_path('/images/avatars'), $filename);
        }

        $teacher = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'fname' => $request->fname,
            'lname' => $request->lname,
            'role' => 'teacher',
            'activated' => 1,
            'birthday' => $request->birthday,
            'address' => $request->address,
            'phone' => $request->phone,
            'mobile' => $request->mobile,
            'photo' => $filename,
            'year_id' => Auth::user()->year_id,
        ]);

        $cuy = Cuy::create([
            'user_id' => $teacher->id,
            'year_id' => Year::first()->id,
            'clase' => implode('|:br:|',explode(',',$request->clase)),
            'role' => 'teacher',
        ]);

        foreach (explode(',',$request->subject) as $subject){
            //create
            TeacherSubject::create([
                'cuy_id' => $cuy->id,
                'subject_id' => $subject
            ]);
        }

        return response($teacher);
    }

    public function selectSubject(Request $request)
    {
        $subject_ids = TeacherSubject::where(['cuy_id' => $request->cuy_id])->get()->pluck('subject_id');
        $subjects = Subject::whereIn('id',$subject_ids)->get();

        return $subjects;
    }

    public function selectClass(Request $request)
    {
        $class_ids = explode('|:br:|',Cuy::find($request->cuy_id)->clase);
        $classes = Clase::whereIn('id',$class_ids)->get();
        return $classes;
    }

    public function delete(Request $request)
    {
        $teacher = Cuy::where(['user_id' => $request['teacher_id'],'year_id' => Auth::user()->year_id])->first();
        $teacher->delete();
    }
}
