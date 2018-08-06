<?php

namespace App\Http\Controllers;

use App\Clase;
use App\Cuy;
use App\Mark;
use App\Subject;
use App\TeacherSubject;
use App\User;
use App\Workbook;
use ArrayObject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MarksController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin|teacher', ['only' => [
            'ClassMarkStore',
            'PayaniMarkStore',
            'ClassMarkUpdate',
            'PayaniMarkUpdate',
            'ClassMarkDelete',
        ]]);
        $this->middleware('role:admin', ['only' => [
            'MakeWorkbook',
        ]]);
    }

    public function getClassMark($username)
    {
        $user = User::whereUsername($username)->first();
        $cuy = Cuy::where(['user_id' => $user->id, 'year_id' => Auth::user()->year_id])->first();
        $auth_cuy = Cuy::where(['user_id' => Auth::user()->id, 'year_id' => Auth::user()->year_id])->first();
        if (Auth::user()->role == 'teacher') {
            $subjects = TeacherSubject::where(['cuy_id' => $auth_cuy->id])->select('subject_id')->get()->toArray();
        }
        if (Auth::user()->role == 'admin' || Auth::user()->role == 'student') {
            $subjects = Subject::getClassSubject($cuy->clase)->pluck('id')->toArray();
        }
        $marks = Mark::where(['cuy_id' => $cuy->id])
            ->whereNotIn('status', ['term1', 'term2', 'term3'])
            ->whereIn('subject_id', $subjects)
            ->get();
        return ['marks' => $marks, 'subjects' => Subject::getClassSubject($cuy->clase)];
    }

    public function getPayaniMark($username)
    {
        $user = User::whereUsername($username)->first();
        $cuy = Cuy::where(['user_id' => $user->id, 'year_id' => Auth::user()->year_id])->first();
        $auth_cuy = Cuy::where(['user_id' => Auth::user()->id, 'year_id' => Auth::user()->year_id])->first();
        if (Auth::user()->role == 'teacher') {
            $subjects = TeacherSubject::where(['cuy_id' => $auth_cuy->id])->select('subject_id')->get()->toArray();
        }
        if (Auth::user()->role == 'admin' || Auth::user()->role == 'student') {
            $subjects = Subject::getClassSubject($cuy->clase)->pluck('id')->toArray();
        }
        $marks = Mark::where(['cuy_id' => $cuy->id])->whereIn('status', ['term1', 'term2', 'term3'])->whereIn('subject_id', $subjects)->get();
        return ['marks' => $marks, 'subjects' => Subject::getClassSubject($cuy->clase)];
    }

    public function ClassMarkStore(Request $request)
    {
        $this->validate($request, [
            'subject_id' => 'required|numeric',
            'mark' => 'required|between:0,20',
            'date' => 'required|date_format:Y/m/d',
        ]);
        $cuy = Cuy::where(['user_id' => $request->student_id, 'year_id' => Auth::user()->year_id])->first();
        $mark = Mark::create([
            'cuy_id' => $cuy->id,
            'subject_id' => $request->subject_id,
            'status' => adadToMonth(explode('/', $request->date)[1]),
            'mark' => $request->mark,
            'date' => $request->date,
        ]);
        return $mark;
    }

    public function PayaniMarkStore(Request $request)
    {
        $this->validate($request, [
            'subject_id' => 'required|numeric',
            'mark' => 'required|between:0,20',
            'status' => 'required|in:term1,term2,term3',
            'date' => 'required|date_format:Y/m/d',
        ]);
        $cuy = Cuy::where(['user_id' => $request->student_id, 'year_id' => Auth::user()->year_id])->first();
        $mark = Mark::create([
            'cuy_id' => $cuy->id,
            'subject_id' => $request->subject_id,
            'status' => $request->status,
            'mark' => $request->mark,
            'date' => $request->date,
        ]);
        return $mark;
    }

    public function ClassMarkUpdate(Request $request)
    {
        $this->validate($request, [
            'subject_id' => 'required|numeric',
            'mark' => 'required|between:0,20',
            'date' => 'required|date_format:Y/m/d',
        ]);
        $mark = Mark::find($request->id);
        if ($mark && !in_array($mark->status, ['term1', 'term2', 'term3'])) {
            $mark->update([
                'subject_id' => $request->subject_id,
                'status' => adadToMonth(explode('/', $request->date)[1]),
                'mark' => $request->mark,
                'date' => $request->date,
            ]);
        }
    }

    public function PayaniMarkUpdate(Request $request)
    {
        $this->validate($request, [
            'subject_id' => 'required|numeric',
            'mark' => 'required|between:0,20',
            'status' => 'required|in:term1,term2,term3',
            'date' => 'required|date_format:Y/m/d',
        ]);
        $mark = Mark::find($request->id);
        if ($mark && in_array($mark->status, ['term1', 'term2', 'term3'])) {
            $mark->update([
                'subject_id' => $request->subject_id,
                'status' => $request->status,
                'mark' => $request->mark,
                'date' => $request->date,
            ]);
        }
    }

    public function ClassMarkDelete(Request $request)
    {
        Mark::find($request)->delete();
    }

    public function TotalMonthWorkbook($username)
    {
        $user = User::whereUsername($username)->first();
        $cuy = Cuy::where(['user_id' => $user->id, 'year_id' => Auth::user()->year_id])->first();
        $marks = Mark::where(['cuy_id' => $cuy->id])
            ->whereNotIn('status', ['term1', 'term2', 'term3'])
            ->groupBy('status', 'subject_id')
            ->select('subject_id', 'cuy_id', 'status', DB::raw('avg(mark) as avg'))
            ->get();
        return ['marks' => $marks, 'subjects' => Subject::getClassSubject($cuy->clase)];
    }

    public function MonthMarkStudents($username)
    {
        $monthes = ['meh' => [], 'aba' => [], 'aza' => [], 'dey' => [], 'bah' => [], 'esf' => [], 'far' => [], 'ord' => []];
        $user = User::whereUsername($username)->first();
        $cuy = Cuy::where(['user_id' => $user->id, 'year_id' => Auth::user()->year_id])->first();
        $class_students = Cuy::where(['clase' => $cuy->clase, 'year_id' => Auth::user()->year_id])->get()->pluck('id');
        foreach ($monthes as $key => $month) {
            $marks = Mark::whereStatus($key)
                ->whereIn('cuy_id', $class_students)
                ->groupBy('status', 'cuy_id')
                ->select('subject_id', 'status', 'cuy_id', DB::raw('avg(mark) as avg'))
                ->orderBy('avg', 'DESC')
                ->get();
            array_push($monthes[$key], $marks);
        }
        return $monthes;
    }

    public function ComputWorkbook($username)
    {
        //محاسبه مستمر ها و پایانی ها ی دانش آموز
        $user = User::whereUsername($username)->first();
        $cuy = Cuy::where(['user_id' => $user->id, 'year_id' => Auth::user()->year_id])->first();
        $subject_ids = Subject::getClassSubject($cuy->clase)->pluck('id')->toArray();
        $subject_marks = [];
        foreach ($subject_ids as $subject_id) {
            $subject_marks[$subject_id] = [
                'mostamar1' => null,
                'payani1' => null,
                'mostamar2' => null,
                'payani2' => null,
            ];
        }

        $mostamar1 = Mark::where(['cuy_id' => $cuy->id])
            ->whereIn('status', ['meh', 'aba', 'aza', 'dey'])
            ->groupBy('subject_id')
            ->select('subject_id', DB::raw('avg(mark) as avg'))
            ->get();

        $mostamar2 = Mark::where(['cuy_id' => $cuy->id])
            ->whereIn('status', ['bah', 'esf', 'far', 'ord'])
            ->groupBy('subject_id')
            ->select('subject_id', DB::raw('avg(mark) as avg'))
            ->get();

        $payani1 = Mark::where(['cuy_id' => $cuy->id, 'status' => 'term1'])
            ->select('subject_id', 'mark')
            ->get();

        $payani2 = Mark::where(['cuy_id' => $cuy->id, 'status' => 'term2'])
            ->select('subject_id', 'mark')
            ->get();

        foreach ($mostamar1 as $mos) {
            if (array_key_exists($mos->subject_id, $subject_marks)) {
                $subject_marks[$mos->subject_id]['mostamar1'] = $mos->avg;
            }
        }

        foreach ($mostamar2 as $mos) {
            if (array_key_exists($mos->subject_id, $subject_marks)) {
                $subject_marks[$mos->subject_id]['mostamar2'] = $mos->avg;
            }
        }

        foreach ($payani1 as $pay) {
            if (array_key_exists($pay->subject_id, $subject_marks)) {
                $subject_marks[$pay->subject_id]['payani1'] = $pay->mark;
            }
        }

        foreach ($payani2 as $pay) {
            if (array_key_exists($pay->subject_id, $subject_marks)) {
                $subject_marks[$pay->subject_id]['payani2'] = $pay->mark;
            }
        }

        return $subject_marks;
    }

    public function MakeWorkbook()
    {
        $year_students = Cuy::where(['role' => 'student', 'year_id' => Auth::user()->year_id])
            ->whereNotIn('clase', [0])
            ->with('user')
            ->get();

        if (count($year_students) > 0) {
            foreach ($year_students->pluck('user') as $student) {
                if ($student != null) {
                    foreach ($this->ComputWorkbook($student->username) as $subject_id => $mark) {
                        $wb = Workbook::where(['cuy_id' => $student->cuy_id, 'subject_id' => $subject_id])->first();
                        if (count($wb) > 0) {
                            $wb->update([
                                'mos1' => $mark['mostamar1'],
                                'pay1' => $mark['payani1'],
                                'mos2' => $mark['mostamar2'],
                                'pay2' => $mark['payani2']
                            ]);
                        } else {
                            Workbook::create([
                                'cuy_id' => $student->cuy_id,
                                'subject_id' => $subject_id,
                                'mos1' => $mark['mostamar1'],
                                'pay1' => $mark['payani1'],
                                'mos2' => $mark['mostamar2'],
                                'pay2' => $mark['payani2']
                            ]);
                        }
                    }
                }

            }
        }
        return redirect()->back();
    }

    public function YearWorkbook($username)
    {
        $user = User::whereUsername($username)->first();
        $cuy = Cuy::where(['user_id' => $user->id, 'year_id' => Auth::user()->year_id])->first();
        $subjects = Subject::getClassSubject($cuy->clase);
        $workbook = Workbook::where(['cuy_id' => $user->cuy_id])
            ->select('cuy_id', 'subject_id', 'mos1', 'pay1', 'mos2', 'pay2', DB::raw('((mos1+pay1*2)/3) as nobat1_mark'), DB::raw('((mos1+mos2+pay1*2+pay2*6)/10) as year_mark'))
            ->get();
        return ['workbook' => $workbook, 'subjects' => $subjects];
    }

    public function YearAvgRates($username)
    {
        //یافتن کمترین نمره و بیشترین نمره و میانگین نمرات کلاس و رتبه دانش آموز در هر درس
        $user = User::whereUsername($username)->first();
        $user_cuy = Cuy::where(['user_id' => $user->id, 'year_id' => Auth::user()->year_id])->first();
        $cuy_ids = Cuy::where(['clase' => $user_cuy->clase, 'year_id' => Auth::user()->year_id])->get()->pluck('id')->toArray();
        $min = [];
        $avg = [];
        $max = [];
        $ranks = [];
        $workbook = Workbook::whereIn('cuy_id', $cuy_ids)
            ->where('mos1', '!=', null)
            ->where('mos2', '!=', null)
            ->where('pay1', '!=', null)
            ->where('pay2', '!=', null)
            ->select('cuy_id', 'subject_id', 'mos1', 'pay1', 'mos2', 'pay2', DB::raw('((mos1+pay1*2)/3) as nobat1_mark'), DB::raw('((mos1+mos2+pay1*2+pay2*6)/10) as year_mark'))
            ->groupBy(['cuy_id', 'subject_id'])
            ->orderBy('year_mark', 'desc')
            ->get();

        $MAMR = [];

        foreach (Subject::getClassSubject($user_cuy->clase) as $key => $subject) {
            for ($i=0 ; $i<4;$i++){
                $MAMR[$key][$i] = null;
            }
        }


        foreach (Subject::getClassSubject($user_cuy->clase) as $subject) {
            $find = $this->findFromArrayOfWbs($workbook, 'subject_id', $subject->id, $user_cuy->id);
            $objects = $find[0];
            $rank = $find[1];
            array_push($ranks, $rank);
            if (count($objects) > 0) {
                array_push($max, $objects[0]['year_mark']);
                array_push($min, $objects[count($objects) - 1]['year_mark']);
            } else {
                array_push($max, null);
                array_push($min, null);
            }
            $sum = [];
            foreach ($objects as $object) {
                array_push($sum, $object['year_mark']);
            }

            if (count($sum) > 0)
                array_push($avg, array_sum($sum) / count($sum));
            else
                array_push($avg, null);

        }

        $MAMR[0] = $min;
        $MAMR[1] = $avg;
        $MAMR[2] = $max;
        $MAMR[3] = $ranks;

        return $MAMR;
    }

    public
    function findFromArrayOfWbs($array, $key, $search, $cuy_id)
    {
        //پیدا کردن نمرات درس مشخص از کل نمرات و پیدا کردن جایگاه دانش آموز
        // key == string
        $objects = [];
        $studIndex = null;
        foreach ($array as $index => $object) {
            if ($object[$key] == $search) {
                array_push($objects, $object);
            }
        }
        foreach ($objects as $index => $object) {
            if ($object['cuy_id'] == $cuy_id) {
                $studIndex = $index + 1;
            }
        }
        return [$objects, $studIndex];
    }

    public
    function YearRateStudents($username)
    {
        //محاسبه معدل کل کارنامه و رتبه کل در کلاس
        $user = User::whereUsername($username)->first();
        $user_cuy = Cuy::where(['user_id' => $user->id, 'year_id' => Auth::user()->year_id])->first();
        $cuys = Cuy::where(['clase' => $user_cuy->clase, 'year_id' => Auth::user()->year_id, 'role' => 'student'])->get()->pluck('id')->toArray();
        $subjects = Subject::getClassSubject($user_cuy->clase);
        $avgs = [];
        foreach ($cuys as $cuy) {
            $sumation = 0;
            $vaheds = 0;
            foreach ($subjects as $subject) {
                $mark = Workbook::where('mos1', '!=', null)
                    ->where('mos2', '!=', null)
                    ->where('pay1', '!=', null)
                    ->where('pay2', '!=', null)
                    ->where(['subject_id' => $subject->id])
                    ->where('cuy_id', $cuy)
                    ->select('subject_id', 'cuy_id', 'mos1', 'pay1', 'mos2', 'pay2', DB::raw('((mos1+mos2+pay1*2+pay2*6)/10) as avg'))
                    ->first();
                if (count($mark) > 0) {
                    $sumation = $sumation + $mark->avg * $subject->vahed;
                    $vaheds = $vaheds + $subject->vahed;
                }
            }
            if ($vaheds != 0) {
                $avgs[$cuy] = $sumation / $vaheds;
            }
        }
        return $avgs;
    }

    public
    function test($username)
    {
        $user = User::whereUsername($username)->first();
        $user_cuy = Cuy::where(['user_id' => $user->id, 'year_id' => Auth::user()->year_id])->first();
        $cuys = Cuy::where(['clase' => $user_cuy->clase, 'year_id' => Auth::user()->year_id])->get()->pluck('id')->toArray();
        $subjects = Subject::getClassSubject($user_cuy->clase);
        $avgs = [];
        foreach ($subjects as $subject) {
            $marks = Workbook::where(['subject_id' => $subject->id])
                ->whereIn('cuy_id', $cuys)
                ->select('subject_id', 'cuy_id', 'mos1', 'pay1', 'mos2', 'pay2', DB::raw('((mos1+mos2+pay1*2+pay2*6)/10) as avg'))
                ->orderBy('avg', 'DESC')
                ->get();
            $avgs[$subject->id] = $marks->pluck('avg', 'cuy_id');
        }

        return $avgs;
    }
}
