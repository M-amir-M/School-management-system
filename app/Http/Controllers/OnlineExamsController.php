<?php

namespace App\Http\Controllers;

use App\Clase;
use App\Cuy;
use App\OnlineExam;
use App\OnlineMarkExam;
use App\OnlineQuestionExam;
use App\Subject;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Morilog\Jalali\Facades\jDate;
use Morilog\Jalali\Facades\jDateTime;

class OnlineExamsController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin|teacher', ['only' => [
            'store',
            'update',
            'delete',
            'storeQuestion',
            'updateQuestion',
            'deleteQuestion',
            'getMarks',
        ]]);
        $this->middleware('role:student',['only' => [
            'storeAnswers'
        ]]);
    }

    public function show()
    {
        $auth_user = User::find(Auth::id());
        return view('onlineexam.onlineexams', compact('auth_user'));
    }

    public function getExams()
    {
        $auth = Auth::user();
        $cuy = Cuy::where(['user_id' => $auth->id,'year_id' => $auth->year_id])->first();
        $class_user = explode('|:br:|',$cuy->clase);
        $query = \DB::table('online_exams');
        foreach ($class_user as $item)
            $query = $query->orWhere(function ($q) use ($item) {
                $q->where('class_ids', $item)
                    ->orwhere('class_ids', 'like', $item . '|:br:|%')
                    ->orwhere('class_ids', 'like', '%|:br:|' . $item . '|:br:|%')
                    ->orwhere('class_ids', 'like', '%|:br:|' . $item);
            });
        $result = $query->get(['*']);
        return $result;
    }

    public function getClasses()
    {
        $cuy = Cuy::where(['user_id' => Auth::id(),'year_id' => Auth::user()->year_id])->first();
        $class_ids = explode('|:br:|',$cuy->clase);
        $classes = Clase::whereIn('id',$class_ids)->get();
        return $classes;
    }

    public function selectClass(Request $request)
    {
        $classes = Clase::whereIn('id',explode('|:br:|',$request->clase))->get();
        return $classes;
    }

    public function selectSubject(Request $request)
    {
        $subjects = Subject::whereIn('id',explode('|:br:|',$request->subject))->get();
        return $subjects;
    }

    public function getSubjects($field_ids)
    {
        $subjects = Subject::whereIn('field_id', explode(',',$field_ids))->get();
        return $subjects;
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:20',
            'description' => 'max:255',
            'class_ids' => 'required',
            'subject_ids' => 'required',
            'startTime' => 'required|date_format:Y/m/d H:i',
            'endTime' => 'required|date_format:Y/m/d H:i',
            'show_mark' => 'required',
        ]);
        OnlineExam::create([
            'title' => $request->title,
            'description' => $request->description,
            'class_ids' => implode('|:br:|', $request->class_ids),
            'subject_ids' => implode('|:br:|', $request->subject_ids),
            'date' => $request->startTime,
            'endDate' => $request->endTime,
            'showMark' => $request->show_mark,
        ]);
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:20',
            'description' => 'max:255',
            'class_ids' => 'required',
            'subject_ids' => 'required',
            'date' => 'required|date_format:Y/m/d H:i',
            'endDate' => 'required|date_format:Y/m/d H:i',
            'showMark' => 'required',
        ]);
        $exam = OnlineExam::find($request->id);
        $exam->update([
            'title' => $request->title,
            'description' => $request->description,
            'class_ids' => implode('|:br:|', $request->class_ids),
            'subject_ids' => implode('|:br:|', $request->subject_ids),
            'date' => $request->date,
            'endDate' => $request->endDate,
            'showMark' => $request->showMark,
        ]);
    }

    public function storeQuestion(Request $request)
    {
        $answers = $request['ans1'] . "|:br:|" . $request['ans2'] . "|:br:|" . $request['ans3'] . "|:br:|" . $request['ans4'];
        $question = OnlineQuestionExam::create([
            'exam_id' => $request['exam_id'],
            'type' => 0,
            'question' => $request['question'],
            'answers' => $answers,
            'trueAnswer' => $request['trueAns']
        ]);
        return $question;
    }

    public function updateQuestion(Request $request)
    {
        $ques = OnlineQuestionExam::find($request->question_id);
        $answers = $request['ans1'] . "|:br:|" . $request['ans2'] . "|:br:|" . $request['ans3'] . "|:br:|" . $request['ans4'];
        $ques->update([
            'type' => 0,
            'question' => $request['question'],
            'answers' => $answers,
            'trueAnswer' => $request['trueAns']
        ]);
        return OnlineQuestionExam::find($request->question_id);
    }

    public function storeAnswers(Request $request)
    {
        $exam = OnlineMarkExam::where(['exam_id' => $request->exam_id, 'cuy_id' => Auth::user()->cuy_id])->first();
        if (count($exam) > 0) {
            $exam->update([
                'answers' => $request->answers,
                'mark' => $request->mark,
                'date' => jDate::forge(time())->format('%Y/%m/%d')
            ]);
        } else {
            OnlineMarkExam::create([
                'exam_id' => $request->exam_id,
                'cuy_id' => Auth::user()->cuy_id,
                'answers' => $request->answers,
                'mark' => $request->mark,
                'date' => jDate::forge(time())->format('%Y/%m/%d')
            ]);
        }
    }

    public function getQuestions($exam_id)
    {
        $questions = OnlineQuestionExam::where('exam_id', $exam_id)->get();
        return $questions;
    }

    public function getMarks($exam_id)
    {
        $marks = OnlineMarkExam::where(['exam_id' => $exam_id])
            ->select('id','exam_id','cuy_id','mark','date','created_at')
            ->get();
        return $marks;
    }

    public function deleteQuestion(Request $request)
    {
        $question = OnlineQuestionExam::find($request->question_id);
        $question->delete();
    }

    public function delete(Request $request)
    {
        $exam = OnlineExam::find($request->exam_id);
        $exam->delete();
    }
}
