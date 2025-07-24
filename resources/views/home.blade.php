@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">
            <i class="bi bi-speedometer2 me-2"></i> Dashboard Tugas
        </h2>
        <a href="{{ route('tasks.create') }}" class="btn btn-outline-success tambah-tugas-btn">
            <i class="bi bi-plus-circle me-1"></i> Tambah Tugas
        </a>
    </div>

    {{-- Search Bar --}}
    <form action="{{ route('tasks.index') }}" method="GET" class="mb-4">
        <div class="input-group shadow-sm">
            <input type="text" name="search" class="form-control" placeholder="Cari tugas..."
                value="{{ request('search') }}">
            <button class="btn btn-primary" type="submit">
                <i class="bi bi-search"></i> Cari
            </button>
        </div>
    </form>

    {{-- Pesan Status --}}
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Keterangan --}}
    <p class="text-muted"><i class="bi bi-check-circle-fill text-success me-1"></i> Centang jika tugas selesai</p>

    {{-- Tabel Daftar Tugas --}}
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Judul</th>
                    <th>Deskripsi</th>
                    <th>Prioritas</th>
                    <th>Tenggat Waktu</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tasks as $task)
                    <tr>
                        <td class="{{ $task->completed ? 'text-decoration-line-through text-muted' : '' }}">
                            {{ $task->title }}
                        </td>
                        <td class="{{ $task->completed ? 'text-decoration-line-through text-muted' : '' }}">
                            {{ $task->description }}
                        </td>
                        <td>
                            @php
                                $priority = strtolower($task->priority);
                            @endphp

                            @if ($priority === 'low')
                                <span class="badge bg-success">Low</span>
                            @elseif ($priority === 'medium')
                                <span class="badge bg-warning text-dark">Medium</span>
                            @elseif ($priority === 'high')
                                <span class="badge bg-danger">High</span>
                            @else
                                <span class="badge bg-secondary">N/A</span>
                            @endif
                        </td>
                        <td>{{ \Carbon\Carbon::parse($task->due_date)->format('d M Y') }}</td>
                        <td>
                            <form action="{{ route('tasks.toggleStatus', $task->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="checkbox" onchange="this.form.submit()"
                                    {{ $task->completed ? 'checked' : '' }}>
                            </form>
                        </td>
                        <td>
                            <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger"
                                    onclick="return confirm('Yakin ingin menghapus tugas ini?')">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </form>
                            @if ($tasks->isEmpty())
                    <tr>
                        <td colspan="6" class="text-center text-muted">Tidak ada tugas yang tersedia.</td>
                    </tr>
                @endif
                </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <footer class="mt-5 text-center text-muted small">
        <i class="bi bi-list-check me-1"></i> Dibuat dengan ❤️ oleh Akhlish.khai
    </footer>


    {{-- Tambahkan gaya tombol --}}
    <style>
        .tambah-tugas-btn {
            transition: all 0.3s ease;
            border: 2px solid #198754;
        }

        .tambah-tugas-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 0.5rem 1rem rgba(25, 135, 84, 0.3);
        }
    </style>
@endsection
