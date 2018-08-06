<?php

namespace App\Http\Controllers;

use App\Library;
use Illuminate\Http\Request;

class LibrariesController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin',['only' => ['store','update','delete']]);
    }

    public function show()
    {
        $auth_user = getAuth();
        return view('library.library',compact('auth_user'));
    }

    public function getBooks()
    {
        return Library::all();
    }

    public function store(Request $request)
    {
        if($request->type == 1){
            $book = Library::create([
                'bookName' => $request->name,
                'bookDescription' => $request->description,
                'bookAuthor' => $request->author,
                'bookType' => $request->type,
                'bookPrice' => $request->price,
                'bookPath' => $request->code,
                'number' => $request->number
            ]);
        }
        elseif ($request->type == 2){
            $book = Library::create([
                'bookName' => $request->name,
                'bookAuthor' => $request->author,
                'bookDescription' => $request->description,
                'bookType' => $request->type,
                'bookPath' => $request->link
            ]);
        }

        return $book;
    }

    public function update(Request $request)
    {
        $book = Library::find($request->id);
        if($book->bookType == 1){
            $book->update([
                'bookName' => $request->bookName,
                'bookDescription' => $request->bookDescription,
                'bookAuthor' => $request->bookAuthor,
                'bookType' => $request->bookType,
                'bookPrice' => $request->bookPrice,
                'bookPath' => $request->bookPath,
                'number' => $request->number
            ]);
        }
        elseif ($book->bookType == 2){
            $book->update([
                'bookName' => $request->bookName,
                'bookDescription' => $request->bookDescription,
                'bookAuthor' => $request->bookAuthor,
                'bookType' => $request->bookType,
                'bookPath' => $request->bookPath
            ]);
        }

    }

    public function delete(Request $request)
    {
        $book = Library::find($request->book_id);
        $book->delete();
    }

}
