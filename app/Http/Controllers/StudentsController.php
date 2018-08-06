<?php

namespace App\Http\Controllers;

use App\Cuy;
use App\Grade;
use App\User;
use App\Year;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentsController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin', ['only' => [
            'update',
            'store',
            'getWait',
            'waitOk',
            'delete',
            'getPromotionStudents',
            'doPromotionStudents',
            'promotion'
        ]]);
    }

    public function getStudent()
    {
        if (getAuth()->role == 'admin') {
            $auth_cuy = Cuy::where(['user_id' => Auth::user()->id, 'year_id' => Auth::user()->year_id])->first();
            $students = User::where(['role' => 'student', 'activated' => 1])
                ->whereHas('cuys', function ($query) use ($auth_cuy) {
                    $query->where(['year_id' => Auth::user()->year_id]);
                })
                ->orderBy('lname', 'ASC')
                ->orderBy('fname', 'ASC')
                ->get();
            return $students;
        }
        if (getAuth()->role != 'student' && getAuth()->role != 'admin') {
            $auth_cuy = Cuy::where(['user_id' => Auth::user()->id, 'year_id' => Auth::user()->year_id])->first();
            $students = User::where(['role' => 'student', 'activated' => 1])
                ->whereHas('cuys', function ($query) use ($auth_cuy) {
                    $query->where(['year_id' => Auth::user()->year_id])
                        ->whereIn('clase', explode('|:br:|', $auth_cuy->clase));
                })
                ->orderBy('lname', 'ASC')
                ->orderBy('fname', 'ASC')
                ->get();
            return $students;
        }
        if (getAuth()->role == 'student') {
            $auth_cuy = Cuy::where(['user_id' => Auth::user()->id, 'year_id' => Auth::user()->year_id])->first();
            $students = User::find(Auth::id());
            return $students;
        }

    }

    public function getPromotionStudents(Request $request)
    {
        $students = User::where(['role' => 'student', 'activated' => 1])
            ->whereHas('cuys', function ($query) use ($request) {
                $query->where(['year_id' => $request->year_id, 'clase' => $request->class_id]);
            })
            ->orderBy('lname', 'ASC')
            ->orderBy('fname', 'ASC')
            ->get();
        return $students;
    }

    public function doPromotionStudents(Request $request)
    {
        foreach ($request->students as $student) {
            $cuy = Cuy::where(['user_id' => $student['student_id'], 'year_id' => $student['year_id']])->first();
            if (count($cuy) == 0) {
                Cuy::create([
                    'user_id' => $student['student_id'],
                    'clase' => $student['class_id'],
                    'year_id' => $student['year_id'],
                    'role' => 'student',
                ]);
            }
        }
    }

    public function show()
    {
        $auth_user = getAuth();
        return view('student.students', compact('auth_user'));
    }

    public function promotion()
    {
        return view('promote.promote');
    }

    public function update(Request $request)
    {
        $student = User::find($request->id);
        $cuy = Cuy::where(['year_id' => Auth::user()->year_id, 'user_id' => $student->id])->first();
        $filename = $student->photo;
        if ($request->hasFile('photo')) {
            $this->validate($request, [
                'username' => 'required|unique:users,username,' . $student->id,
                'email' => 'required|email|unique:users,email,' . $student->id,
                'password' => 'required|min:6',
                'fname' => 'required',
                'lname' => 'required',
                'clase' => 'required|numeric',
                'birthday' => 'nullable',
                'address' => 'max:255',
                'phone' => 'max:15',
                'mobile' => 'max:15',
                'photo' => 'mimes:jpg,jpeg,png|max:2048',
            ]);
            if ($filename != 'male.png' && file_exists('/images/avatars/' . $filename)) {
                unlink('/images/avatars/' . $filename);
            }
            $filename = str_slug($request->username, '-') . '-' . str_random(6) . '.' . $request->photo->getClientOriginalExtension();

            $request->photo->move(public_path('/images/avatars'), $filename);

        } else {
            $this->validate($request, [
                'username' => 'required|unique:users,username,' . $student->id,
                'email' => 'required|email|unique:users,email,' . $student->id,
                'password' => 'required|min:6',
                'fname' => 'required',
                'lname' => 'required',
                'clase' => 'required|numeric',
                'birthday' => 'nullable',
                'address' => 'max:255',
                'phone' => 'max:15',
                'mobile' => 'max:15',
            ]);
        }

        $student->update([
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
            'clase' => $request->clase
        ]);

    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'username' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'fname' => 'required',
            'lname' => 'required',
            'clase' => 'required|numeric',
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


        $student = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'fname' => $request->fname,
            'lname' => $request->lname,
            'role' => 'student',
            'activated' => 1,
            'birthday' => $request->birthday,
            'address' => $request->address,
            'phone' => $request->phone,
            'mobile' => $request->mobile,
            'photo' => $filename,
            'year_id' => Auth::user()->year_id,
        ]);

        Cuy::create([
            'user_id' => $student->id,
            'year_id' => Year::first()->id,
            'clase' => $request->clase,
            'role' => 'student',
        ]);

        return response($student);
    }

    public function getWait()
    {
        $wait = User::where(['role' => 'student', 'activated' => 0, 'year_id' => eduYear()->id])->get();
        return $wait;
    }

    public function checkUsername($username)
    {
        $check = User::where('username', '=', $username)->exists();
        if ($check)
            return 'false';
        return 'true';
    }

    public function waitOk(Request $request)
    {
        $user = User::find($request->id);
        $cuy = Cuy::where(['user_id' => $user->id, 'year_id' => eduYear()->id])->first();
        $user->update([
            'username' => $request->username,
            'email' => $request->email,
            'fname' => $request->fname,
            'lname' => $request->lname,
            'birthday' => $request->birthday,
            'activated' => 1,
            'address' => $request->address,
            'phone' => $request->phone,
            'mobile' => $request->mobile,
        ]);
        if (count($cuy) > 0) {
            $cuy->update([
                'user_id' => $user->id,
                'year_id' => eduYear()->id,
                'clase' => 0,
                'role' => 'student'
            ]);
        } else {
            Cuy::create([
                'user_id' => $user->id,
                'year_id' => eduYear()->id,
                'clase' => 0,
                'role' => 'student'
            ]);
        }

        return back()->withErrors(['ثبت نام دانش آموز تایید شد.']);
    }

    public function delete(Request $request)
    {
        $student = Cuy::where(['user_id' => $request['student_id'], 'year_id' => Auth::user()->year_id])->first();
        $student->delete();
    }
}
