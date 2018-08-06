<?php

namespace App\Http\Controllers;

use App\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NewsController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin', ['only' => [
            'update',
            'store',
            'delete',
        ]]);
    }

    public function show()
    {
        $auth_user = getAuth();
        return view('news.news',compact('auth_user'));
    }

    public function getNews()
    {
        if (Auth::user()->role != 'admin'){
            return News::where(['target' => Auth::user()->role.'s'])->orWhere('target','all')->orderBy('updated_at')->get();
        }
        return News::all();    }

    public function update(Request $request)
    {
        $news = News::find($request->id);
        $news->update([
            'title' => $request->title,
            'text' => $request->text,
            'target' => $request->target,
        ]);
    }

    public function store(Request $request)
    {
        $news = News::create([
            'title' => $request->title,
            'text' => $request->text,
            'target' => $request->target,
            'author' => 'امیر',
        ]);
        return $news;
    }

    public function delete(Request $request)
    {
        $news = News::find($request->news_id);
        $news->delete();
    }
}
