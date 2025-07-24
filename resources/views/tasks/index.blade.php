@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">📋 Daftar Tugas</h2>
            <a href="{{ route('tasks.create') }}" class="btn btn-success">+ Tambah Tugas</a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form method="GET" action="{{ route('tasks.index') }}" class="mb-4">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Cari tugas..."
                    value="{{ request('search') }}">
                <button class="btn btn-outline-secondary" type="submit">🔍 Cari</button>
            </div>
        </form>

        <p class="text-muted small mb-2">Centang jika tugas selesai</p>
        @if ($tasks->count())
            <div class="list-group shadow-sm">
                @foreach ($tasks as $task)
                    <div class="row mb-3">
                        <div
                            class="col-12 list-group-item d-flex justify-content-between align-items-center flex-wrap {{ $task->completed ? 'bg-light text-muted' : '' }}">
                            <div class="mb-2 d-flex align-items-center gap-2">
                                <form action="{{ route('tasks.toggle', $task->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="checkbox" onchange="this.form.submit()"
                                        {{ $task->completed ? 'checked' : '' }}>
                                </form>
                                <div>
                                    <h5 class="mb-1 {{ $task->completed ? 'text-decoration-line-through' : '' }}">
                                        {{ $task->title }}</h5>
                                    <p class="mb-0 text-muted">{{ $task->description }}</p>
                                    <small>
                                        📅
                                        <strong>{{ \Carbon\Carbon::parse($task->due_date)->format('d M Y') }}</strong>
                                        |
                                        ⚠️ <strong>{{ ucfirst($task->priority) }}</strong>
                                    </small>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-sm btn-warning">✏️
                                    Edit</a>
                                <form action="{{ route('tasks.destroy', $task->id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"
                                        onclick="return confirm('Yakin hapus tugas ini?')">🗑️ Hapus</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="alert alert-info mt-4">Belum ada tugas ditemukan.</div>
        @endif
    </div>
@endsection
