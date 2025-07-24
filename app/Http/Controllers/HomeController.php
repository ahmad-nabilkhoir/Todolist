<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function index()
    {
        $tasks = Task::orderBy('completed', 'asc') // belum selesai di atas
            ->orderBy('due_date', 'asc') // deadline terdekat dulu
            ->orderByRaw("FIELD(priority, 'high', 'medium', 'low')") // prioritas
            ->orderBy('id', 'asc')
            ->get();

        return view('home', compact('tasks'));
    }
}
