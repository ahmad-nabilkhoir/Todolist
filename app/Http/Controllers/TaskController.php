<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $tasks = Task::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%');
            })
            ->orderBy('is_completed', 'asc')
            ->orderBy('due_date', 'asc')
            ->orderByRaw("FIELD(priority, 'high', 'medium', 'low')")
            ->orderBy('id', 'asc')
            ->get();

        return view('home', compact('tasks'));
    }

    public function create()
    {
        return view('tasks.create');
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'priority' => 'required|in:High,Medium,Low',
        'due_date' => 'nullable|date',
        'file' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
        'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    $task = new Task();
    $task->title = $request->title;
    $task->description = $request->description;
    $task->priority = strtolower($request->priority);
    $task->due_date = $request->due_date;
    $task->is_completed = 0;
    $task->status = 'belum';

    // Upload file umum
    if ($request->hasFile('file')) {
        $path = $request->file('file')->store('public/files');
        $fileName = time() . '_' . $request->file('file')->getClientOriginalName();
        $request->file('file')->storeAs('public/uploads', $fileName);
        $task->file = 'uploads/' . $fileName;
    }

    // Upload gambar
    if ($request->hasFile('image')) {
        $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
        $request->file('image')->storeAs('public/uploads', $imageName);
        $task->image = 'uploads/' . $imageName;
    }

    $task->save();

    return redirect()->route('tasks.index')->with('success', 'Tugas berhasil ditambahkan!');
}

    public function edit($id)
    {
        $task = Task::findOrFail($id);
        return view('tasks.edit', compact('task'));
    }

    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'required|date',
            'priority' => 'required|in:High,Medium,Low',
            'file' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $task->title = $request->title;
        $task->description = $request->description;
        $task->priority = strtolower($request->priority);
        $task->due_date = $request->due_date;
        $task->is_completed = $request->has('is_completed') ? 1 : 0;
        $task->status = $task->is_completed ? 'selesai' : 'belum';

        $path = $task->file; // default pakai file lama
        if ($request->hasFile('file')) {
            // hapus file lama jika ada
            if ($task->file && \Storage::disk('public')->exists($task->file)) {
                \Storage::disk('public')->delete($task->file);
        }

        // simpan file baru
        $fileName = time() . '_' . $request->file('file')->getClientOriginalName();
        $request->file('file')->storeAs('public/uploads', $fileName);
        $task->file = 'uploads/' . $fileName;

        }

        // Update gambar jika ada
        if ($request->hasFile('image')) {
            if ($task->image && Storage::exists('public/' . $task->image)) {
                Storage::delete('public/' . $task->image);
            }
            $path = $request->file('file')->store('public/files');
            $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->storeAs('public/uploads', $imageName);
            $task->image = 'uploads/' . $imageName;
        }

        $task->save();

        return redirect()->route('tasks.index')->with('success', 'Tugas berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $task = Task::findOrFail($id);

        if ($task->file && Storage::exists('public/' . $task->file)) {
            Storage::delete('public/' . $task->file);
        }

        if ($task->image && Storage::exists('public/' . $task->image)) {
            Storage::delete('public/' . $task->image);
        }

        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Tugas berhasil dihapus!');
    }

    public function toggleStatus($id)
    {
        $task = Task::findOrFail($id);
        $task->is_completed = !$task->is_completed;
        $task->status = $task->is_completed ? 'selesai' : 'belum';
        $task->save();

        return redirect()->route('tasks.index')->with('success', 'Status tugas diperbarui.');
    }
}
