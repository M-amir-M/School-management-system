<?php

namespace App\Http\Controllers;

use App\Clase;
use App\Cuy;
use App\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubjectsController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin',['except' =>[
            'getSubject',
            'getUserSubjects',
        ]]);
    }

    public function getSubject()
    {
        $subjects = Subject::all();
        return $subjects;
    }

    public function getUserSubjects()
    {
        $cuy = Cuy::where(['user_id' => Auth::id(),'year_id' => Auth::user()->year_id])->first();

        $field_ids = Clase::whereIn('id',explode('|:br:|',$cuy->clase))->get()->pluck('field_id');

        $subjects = Subject::whereIn('field_id',$field_ids)->get();

        return $subjects;
    }

    public function show()
    {
        return view('subject.subjects');
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'subject' => 'required|max:15',
            'code' => 'required|numeric',
            'vahed' => 'required|max:15',
            'field_id' => 'required',
        ]);
        $subject = Subject::find($request->id);
        $subject->update([
            'subject' => $request->subject,
            'code' => $request->code,
            'vahed' => $request->vahed,
            'field_id' => $request->field_id
        ]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'subject' => 'required|max:15',
            'code' => 'required|numeric',
            'vahed' => 'required|max:15',
            'field_id' => 'required',
        ]);
        $subject = Subject::create([
            'subject' => $request->subject,
            'code' => $request->code,
            'vahed' => $request->vahed,
            'field_id' => $request->field_id

        ]);
        return response($subject);
    }

    public function delete(Request $request)
    {
        $subject = Subject::find($request->subject_id);
        $subject->delete();
    }

    
}
