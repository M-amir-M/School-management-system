<?php

namespace App\Http\Controllers;

use App\Clase;
use App\Cuy;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClasesController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin',['except' => ['getClase']]);
    }


    public function getClase()
    {
        $cuy = Cuy::where(['user_id' => Auth::id(),'year_id' => Auth::user()->year_id])->first();
        $class_ids = explode('|:br:|',$cuy->clase);
        $classes = Clase::whereIn('id',$class_ids)->get();
        return $classes;
    }

    public function show()
    {
        return view('clase.clases');
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'clase' => 'required|max:15',
            'field_id' => 'required',
        ]);

        $clase = Clase::find($request->id);
        $clase->update([
            'clase' => $request->clase,
            'field_id' => $request->field_id
        ]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'clase' => 'required|max:15',
            'field_id' => 'required',
        ]);
        $clase = Clase::create([
            'clase' => $request->clase,
            'field_id' => $request->field_id

        ]);
        if(Auth::user()->role == 'admin'){
            $cuy = Cuy::where(['user_id' =>Auth::id(),'year_id' => Auth::user()->year_id])->first();
            $c = explode('|:br:|',$cuy->clase);
            array_push($c,$clase->id);
            $cuy->update([
                'clase' => implode('|:br:|',$c)
            ]);
        }

        return response($clase);
    }

    public function delete(Request $request)
    {
        $clase = Clase::find($request->clase_id);
        $clase->delete();
    }
}
