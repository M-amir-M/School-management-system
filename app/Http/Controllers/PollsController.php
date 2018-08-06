<?php

namespace App\Http\Controllers;

use App\Poll;
use App\PollOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PollsController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin',['only'=>[
            'store',
            'update',
            'delete',
            'storeOption',
            'updateOption',
            'deleteOption',
        ]]);
    }

    public function show()
    {
        $auth_user = getAuth();
        return view('poll.polls',compact('auth_user'));
    }

    public function getPolls()
    {
        $polls = Poll::where('target', Auth::user()->role)->orWhere('target','all')->get();
        if (Auth::user()->role == 'admin'){
            $polls  = Poll::all();
        }
        return $polls;
    }

    public function getPollOptions($poll_id)
    {
        $options = PollOption::where(['poll_id' => $poll_id])->get();
        return $options;
    }

    public function update(Request $request)
    {
        $poll = Poll::find($request->id);
        $poll->update([
            'title' => $request->title,
            'target' => $request->target,
            'status' => $request->status,
        ]);
        return $poll;
    }

    public function store(Request $request)
    {
        $poll = Poll::create([
            'title' => $request->title,
            'target' => $request->target,
            'status' => $request->status
        ]);
        foreach ($request->options as $option) {
            PollOption::create([
                'poll_id' => $poll->id,
                'option' => $option
            ]);
        }

        return response($poll);
    }

    public function storeOption(Request $request)
    {
        $option = PollOption::create([
            'poll_id' => $request->poll_id,
            'option' => $request->option
        ]);
        return $option;
    }

    public function storeVote(Request $request)
    {
        $poll = Poll::find($request->poll_id);
        $SEPRATOR = '|';
        $options = PollOption::where(['poll_id' => $request->poll_id])->get()->pluck('id')->toArray();
        if(in_array($request->vote,$options)){
            $option = PollOption::find($request->vote);
            if ($poll->count == 0){
                $SEPRATOR = '';
            }
            $poll->update([
                'count' => $poll->count + 1,
                'user_voted' => $SEPRATOR.Auth::user()->id
            ]);
            $option->update([
                'count' => $option->count + 1,
            ]);
        }
    }

    public function updateOption(Request $request)
    {
        $option = PollOption::find($request->option['id']);

        $option->update([
            'option' => $request->option['option']
        ]);
        return $option;
    }

    public function delete(Request $request)
    {
        $grade = Grade::find($request->grade_id);
        $grade->delete();
    }

    public function deleteOption(Request $request)
    {
        $option = PollOption::find($request->option_id);
        $option->delete();
    }
}
