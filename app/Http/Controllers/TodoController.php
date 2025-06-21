<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Todo;
use Illuminate\Support\Facades\Auth;

class TodoController extends Controller
{
    public function index(Request $request)
    {
        $query = Todo::where('user_id', Auth::id())
        ->orderBy('is_done')
        ->orderBy('created_at');

    if ($request->filled('filter_date')) {
        $query->whereDate('date', $request->input('filter_date'));
    }

    $todos = $query->get();

    return view('dashboard', compact('todos'));
    }

    public function store(Request $request)
    {
        $request->validate(['title' => 'required']);
        Todo::create([
            'title' => $request->title,
            'user_id' => Auth::id(),
            'date' => $request->date
        ]);
        return back();
    }
    
    public function evaluate(Request $request, $id)
    {
        $request->validate([
            'mood' => 'required',
            'notes' => 'nullable|string',
            'image' => 'nullable|image|max:2048'
        ]);

        $todo = Todo::findOrFail($id);
        $todo->mood = $request->mood;
        $todo->notes = $request->notes;
        if ($request->hasFile('image')) {
            $todo->image = $request->file('image')->store('images', 'public');
        }

        $todo->save();
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
