<?php

namespace App\Http\Controllers;

use App\ParentStudent;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParentsController extends Controller
{
    public function getParent()
    {
        $parents = User::where(['role' => 'parent', 'activated' => 1])
            ->orderBy('lname', 'ASC')
            ->orderBy('fname', 'ASC')
            ->get();
        return $parents;
    }

    public function show()
    {
        return view('parent.parents');
    }

    public function linkToParent($search)
    {
        $students = User::where(function ($query) use ($search) {
            $query->where('role', 'student')
                ->where('fname', 'like', '%' . $search . '%');
        })
            ->orWhere(function ($query) use ($search) {
                $query->where('role', 'student')
                    ->where('lname', 'like', '%' . $search . '%');
            })
            ->orWhere(function ($query) use ($search) {
                $query->where('role', 'student')
                    ->where('email', 'like', '%' . $search . '%');
            })
            ->orWhere(function ($query) use ($search) {
                $query->where('role', 'student')
                    ->where('username', 'like', '%' . $search . '%');
            })
            ->get();

        return $students;
    }

    public function update(Request $request)
    {
        $parent = User::find($request->id);
        $filename = $parent->photo;

        if ($request->hasFile('photo')) {
            $this->validate($request, [
                'username' => 'required|unique:users,username,' . $parent->id,
                'email' => 'required|email|unique:users,email,' . $parent->id,
                'password' => 'required|min:6',
                'fname' => 'required',
                'lname' => 'required',
                'birthday' => 'nullable|date_format:Y/m/d',
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
                'username' => 'required|unique:users,username,' . $parent->id,
                'email' => 'required|email|unique:users,email,' . $parent->id,
                'password' => 'required|min:6',
                'fname' => 'required',
                'lname' => 'required',
                'birthday' => 'nullable|date_format:Y/m/d',
                'address' => 'max:255',
                'phone' => 'max:15',
                'mobile' => 'max:15',
            ]);
        }


        $parent->update([
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

        //exist in db and not exist in request
        $parentStudents1 = ParentStudent::where(['parent_id' => $request->id])
            ->whereNotIn('student_id', explode(',', $request->student))
            ->pluck('id')
            ->toArray();
        if (count($parentStudents1) > 0)
            ParentStudent::destroy($parentStudents1);

        foreach (explode(',', $request->student) as $student) {
            $parentStudent = ParentStudent::where(['parent_id' => $request->id, 'student_id' => $student])
                ->first();
            if (count($parentStudent) > 0) {
                //exist in db and exist in request
                $parentStudent->update([
                    'gender' => $request->gender,
                ]);
            } else {
                //not exist in db and exist in request
                ParentStudent::create([
                    'student_id' => $student,
                    'parent_id' => $parent->id,
                    'gender' => $request->gender,
                ]);
            }
        }
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'username' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'fname' => 'required',
            'birthday' => 'nullable|date_format:Y/m/d',
            'address' => 'nullable|max:255',
            'phone' => 'nullable|max:15',
            'mobile' => 'nullable|max:15',
        ]);

        $filename = 'male.png';

        if ($request->hasFile('photo')) {
            $filename = str_slug($request->username, '-') . '-' . str_random(6) . '.' . $request->photo->getClientOriginalExtension();

            $request->photo->move(public_path('/images/avatars'), $filename);

        }

        $parent = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'fname' => $request->fname,
            'lname' => $request->lname,
            'role' => 'parent',
            'activated' => 1,
            'birthday' => $request->birthday,
            'address' => $request->address,
            'phone' => $request->phone,
            'mobile' => $request->mobile,
            'photo' => $filename,
            'year_id' => Auth::user()->year_id,
        ]);

        foreach (explode(',', $request->student) as $student) {
            ParentStudent::create([
                'student_id' => $student,
                'parent_id' => $parent->id,
                'gender' => $request->gender,
            ]);
        }


        return response($parent);
    }

    public function getStudentOfParent($parent_id)
    {
        $parentStudent = ParentStudent::where(['parent_id' => $parent_id])->get();
        $student_ids = $parentStudent->pluck('student_id')->toArray();
        if (count($parentStudent) > 0) {
            $gender = $parentStudent[0]->gender;
        }
        $students = User::whereIn('id', $student_ids)->get();
        return [$student_ids, $students, $gender];
    }

    public function delete(Request $request)
    {
        $teacher = User::find($request['parent_id']);
        $teacher->delete();
    }
}
