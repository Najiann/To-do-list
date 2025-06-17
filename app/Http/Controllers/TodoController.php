<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Todo;
use Illuminate\Support\Facades\Auth;

class TodoController extends Controller
{
    public function index()
    {
        $todos =Todo::where('user_id', Auth::id())->get();
        return view('dashboard', compact('todos'));
    }

    public function store(Request $request)
    {
        $request->validate(['title' => 'required']);
        Todo::create([
            'title' => $request->title,
            'user_id' => Auth::id()
        ]);
        return back();
    }

    public function markAsDone($id)
    {
        $todo = Todo::findOrFail($id);
        $todo->is_done = true;
        $todo->save();
        return back();
    }

    public function destroy($id)
    {
        $todo = Todo::findOrFail($id);
        $todo->delete();
        return back();
    }
}
