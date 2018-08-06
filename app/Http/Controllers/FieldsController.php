<?php

namespace App\Http\Controllers;

use App\Field;
use Illuminate\Http\Request;

class FieldsController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin');
    }

    public function getField()
    {
        $fields = Field::all();
        return $fields;
    }

    public function show()
    {
        return view('field.fields');
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'field' => 'required|max:15',
            'grade_id' => 'required',
        ]);
        $field = Field::find($request->id);
        $field->update([
            'field' => $request->field,
            'grade_id' => $request->grade_id
        ]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'field' => 'required|max:15',
            'grade_id' => 'required',
        ]);
        $field = Field::create([
            'field' => $request->field,
            'grade_id' => $request->grade_id

        ]);
        return response($field);
    }

    public function delete(Request $request)
    {
        $field = Field::find($request->field_id);
        $field->delete();
    }
}
