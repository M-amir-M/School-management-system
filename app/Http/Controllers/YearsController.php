<?php

namespace App\Http\Controllers;

use App\Cuy;
use App\User;
use App\Year;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class YearsController extends Controller
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

    public function getYear()
    {
        $response = Year::all();
        return $response;
    }

    public function show()
    {
        return view('year.years');
    }

    public function update(Request $request)
    {
        $year = Year::find($request->id);
        $year->update([
            'year' => $request->year
        ]);
    }

    public function store(Request $request)
    {
        $year = Year::create([
            'year' => $request->year
        ]);

        $cuy = Cuy::where(['user_id' => Auth::user()->id,'year_id' => Auth::user()->year_id])->first();

        Cuy::create([
            'user_id' => Auth::user()->id,
            'year_id' => $year->id,
            'clase' => $cuy->clase,
            'role' => 'admin'
        ]);
        return response($year);
    }

    public function change(Request $request)
    {
        User::find(Auth::user()->id)->update(['year_id'=>$request->year]);
        return redirect()->back();
    }

    public function delete(Request $request)
    {
        $year = Year::find($request->year_id);
        $year->delete();
    }
}
