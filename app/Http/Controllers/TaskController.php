<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Task::query();
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $tasks = $query->orderBy('completed', 'asc') // belum selesai di atas
            ->orderBy('due_date', 'asc') // deadline terdekat dulu
            ->orderByRaw("FIELD(priority, 'high', 'medium', 'low')") // prioritas
            ->orderBy('id', 'asc') // penentu terakhir yang konsisten (penting!)
            ->get(); // ini WAJIB supaya data benar-benar diambil

        return view('home', compact('tasks'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tasks.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:High,Medium,Low',
        ]);

        Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'priority' => strtolower($request->priority),
        ]);

        return redirect()->route('tasks.index')->with('success', 'Tugas berhasil ditambahkan!');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $task = Task::find($id);
        return view('tasks.edit', compact('task'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'required|date',
            'priority' => 'required|in:High,Medium,Low',
        ]);
        $validated['priority'] = strtolower($validated['priority']);
        $task->update($validated);

        $task->is_completed = $request->has('is_completed') ? 1 : 0;

        // Update status otomatis
        $task->status = $task->is_completed ? 'selesai' : 'belum';
        $task->save();

        return redirect()->route('tasks.index')->with('success', 'Tugas berhasil diperbarui!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $task = Task::find($id);

        if (!$task) {
            return redirect()->route('tasks.index')
                ->with('error', 'Tugas tidak ditemukan!');
        }

        $task->delete();

        return redirect()->route('tasks.index')
            ->with('success', 'Tugas Berhasil dihapus...');
    }
    public function toggleStatus($id)
    {
        $task = Task::findOrFail($id);
        $task->completed = !$task->completed;

        // Sinkronkan ke is_completed dan status
        $task->is_completed = $task->completed ? 1 : 0;
        $task->status = $task->completed ? 'selesai' : 'belum';

        $task->save();

        return redirect()->route('tasks.index')->with('success', 'Status tugas diperbarui.');
    }
}
