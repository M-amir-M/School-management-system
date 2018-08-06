<?php

namespace App\Http\Controllers;

use App\Grade;
use Illuminate\Http\Request;

class GradesController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin');
    }

    public function getGrade()
    {
        $response = Grade::all();
        return $response;
    }

    public function show()
    {
        return view('grade.grades');
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'grade' => 'required|max:15',
        ]);
        $grade = Grade::find($request->id);
        $grade->update([
            'grade' => $request->grade
        ]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'grade' => 'required|max:15',
        ]);
        $grade = Grade::create([
            'grade' => $request->grade
        ]);
        return response($grade);
    }

    public function delete(Request $request)
    {
        $grade = Grade::find($request->grade_id);
        $grade->delete();
    }
}
