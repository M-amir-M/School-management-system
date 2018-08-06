<?php

namespace App\Http\Controllers;

use App\Assignment;
use App\Clase;
use App\Cuy;
use App\Subject;
use App\TeacherSubject;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssignmentsController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin|teacher', ['only' => ['store', 'update', 'delete']]);
    }

    public function show()
    {
        $auth_user = getAuth();
        return view('assignment.assignments', compact('auth_user'));
    }

    public function getAssignments()
    {
        $cuy = Cuy::where(['user_id' => Auth::id(), 'year_id' => Auth::user()->year_id])->first();
        if (Auth::user()->role == 'teacher') {
            $class_ids = Clase::whereIn('id', explode('|:br:|', $cuy->clase))
                ->get()->pluck('id')->toArray();
            $subject_ids = TeacherSubject::where('cuy_id', $cuy->id)->get()->pluck('subject_id')->toArray();
            $query = \DB::table('assignments');
            foreach ($class_ids as $class_id)
                foreach ($subject_ids as $subject_id)
                    $query = $query->orWhere(function ($q) use ($subject_id, $class_id) {
                        $q->where('subject_ids', $subject_id)
                            ->orwhere('subject_ids', 'like', $subject_id . '|:br:|%')
                            ->orwhere('subject_ids', 'like', '%|:br:|' . $subject_id . '|:br:|%')
                            ->orwhere('subject_ids', 'like', '%|:br:|' . $subject_id);
                        $q->where('class_ids', $class_id)
                            ->orwhere('class_ids', 'like', $class_id . '|:br:|%')
                            ->orwhere('class_ids', 'like', '%|:br:|' . $class_id . '|:br:|%')
                            ->orwhere('class_ids', 'like', '%|:br:|' . $class_id);
                    });
            $result = $query->get(['*']);
            return $result;
        } elseif (Auth::user()->role == 'student') {
            $query = \DB::table('assignments');
            $query = $query->orWhere(function ($q) use ($cuy) {
                $q->where('class_ids', $cuy->clase)
                    ->orwhere('class_ids', 'like', $cuy->clase . '|:br:|%')
                    ->orwhere('class_ids', 'like', '%|:br:|' . $cuy->clase . '|:br:|%')
                    ->orwhere('class_ids', 'like', '%|:br:|' . $cuy->clase);
            });
            $result = $query->get(['*']);
            return $result;
        } elseif (Auth::user()->role == 'admin') {
            $assignments = Assignment::all();
            return $assignments;
        }
    }

    public function getClasses()
    {
        $cuy = Cuy::where(['user_id' => Auth::id(), 'year_id' => Auth::user()->year_id])->first();
        $class_ids = explode('|:br:|', $cuy->clase);
        $classes = Clase::whereIn('id', $class_ids)->get();
        return $classes;
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:20',
            'description' => 'max:255',
            'class_ids' => 'required',
            'subject_ids' => 'required',
            'endTime' => 'required|date_format:Y/m/d H:i',
            'attach' => 'mimes:pdf,jpg,jpeg|max:2048',
        ]);

        if ($request->hasFile('attach')) {
            $filename = str_slug($request->title, '-') . '-' . str_random(6) . '.' . $request->attach->getClientOriginalExtension();

            $request->attach->move(public_path('/files/assignments'), $filename);

            $assignment = Assignment::create([
                'class_ids' => $request['class_ids'],
                'subject_ids' => $request['subject_ids'],
                'title' => $request['title'],
                'description' => $request['description'],
                'path' => $filename,
                'deadtime' => $request['endTime'],
            ]);

        } else {
            $assignment = Assignment::create([
                'class_ids' => $request['class_ids'],
                'subject_ids' => $request['subject_ids'],
                'title' => $request['title'],
                'description' => $request['description'],
                'path' => $request['path'],
                'deadtime' => $request['endTime'],
            ]);
        }


        return response($assignment);
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:20',
            'description' => 'max:255',
            'class_ids' => 'required',
            'subject_ids' => 'required',
            'deadtime' => 'required|date_format:Y/m/d H:i',
            'attach' => 'mimes:pdf,jpg,jpeg|max:2048',
        ]);

        if ($request->hasFile('attach')) {
            $filename = str_slug($request->title, '-') . '-' . str_random(6) . '.' . $request->attach->getClientOriginalExtension();

            $request->attach->move(public_path('/files/assignments'), $filename);

            $assignment = Assignment::find($request->id);
            if ($assignment->type == 2) {
                unlink('/files/assignments/' . $assignment->path);
            }
            $assignment->update([
                'class_ids' => $request['class_ids'],
                'subject_ids' => $request['subject_ids'],
                'title' => $request['title'],
                'description' => $request['description'],
                'path' => $request['path'],
                'deadtime' => $request['deadtime'],
            ]);

        } else {
            $assignment = Assignment::find($request->id);

            $assignment->update([
                'class_ids' => $request['class_ids'],
                'subject_ids' => $request['subject_ids'],
                'title' => $request['title'],
                'description' => $request['description'],
                'path' => $request['path'],
                'deadtime' => $request['deadtime'],
            ]);
        }


    }

    public function delete()
    {

    }
}
