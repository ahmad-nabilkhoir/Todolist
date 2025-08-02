@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold custom-title">
            <i class="bi bi-ui-checks-grid text-primary me-2"></i> Dashboard Tugas
        </h2>
        <a href="{{ route('tasks.create') }}" class="btn btn-custom tambah-tugas-btn">
            <i class="bi bi-journal-plus me-1"></i> Tambah Tugas
        </a>
    </div>

    {{-- Search Bar --}}
    <form action="{{ route('tasks.index') }}" method="GET" class="mb-4">
        <div class="input-group shadow-sm">
            <input type="text" name="search" class="form-control" placeholder="Cari tugas..."
                value="{{ request('search') }}">
            <button class="btn btn-primary" type="submit">
                <i class="bi bi-search-heart"></i> Cari
            </button>
        </div>
    </form>

    {{-- Pesan Status --}}
    @if (session('success'))
        <div class="alert alert-success">
            <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
        </div>
    @endif

    {{-- Keterangan --}}
    <p class="text-muted small">
        <i class="bi bi-check2-square text-success me-1"></i> Centang jika tugas telah selesai
    </p>

    {{-- Tabel Daftar Tugas --}}
    <div class="table-responsive">
        <table class="table-bordered table-hover table bg-white align-middle shadow-sm">
            <thead class="text-white" style="background-color: #748DAE;">
                <tr>
                    <th>Judul</th>
                    <th>Deskripsi</th>
                    <th>File</th>
                    <th>Prioritas</th>
                    <th>Tenggat Waktu</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($tasks as $task)
                    <tr>
                        <td class="{{ $task->completed ? 'text-decoration-line-through text-muted' : '' }}">
                            {{ $task->title }}
                        </td>
                        <td class="{{ $task->completed ? 'text-decoration-line-through text-muted' : '' }}">
                            {{ $task->description }}
                        </td>
                        <td>
                            {{-- Menampilkan File --}}
                            @if ($task->file && Storage::exists('public/' . $task->file))
                                <a href="{{ asset('storage/' . $task->file) }}" target="_blank">
                                    {{ basename($task->file) }}
                                </a>
                                <small class="text-muted">
                                    ({{ round(Storage::size('public/' . $task->file) / 1024, 2) }} KB)
                                </small>
                            @else
                                <span class="text-muted">Tidak ada file</span>
                            @endif

                            {{-- Menampilkan Gambar --}}
                            @if ($task->image && Storage::exists('public/' . $task->image))
                                <div class="mt-2">
                                    <img src="{{ asset('storage/' . $task->image) }}" alt="Task Image" width="100"
                                        style="border-radius: 8px;">
                                </div>
                            @endif
                        </td>

                        <td>
                            @php $priority = strtolower($task->priority); @endphp
                            @if ($priority === 'low')
                                <span class="badge rounded-pill" style="background-color: #9ECAD6;">Low</span>
                            @elseif ($priority === 'medium')
                                <span class="badge rounded-pill bg-warning text-dark">Medium</span>
                            @elseif ($priority === 'high')
                                <span class="badge rounded-pill bg-danger">High</span>
                            @else
                                <span class="badge rounded-pill bg-secondary">N/A</span>
                            @endif
                        </td>
                        <td
                            class="{{ \Carbon\Carbon::parse($task->due_date)->isPast() && !$task->completed ? 'text-danger fw-bold' : '' }}">
                            {{ \Carbon\Carbon::parse($task->due_date)->format('d M Y') }}
                        </td>
                        <td>
                            <form action="{{ route('tasks.toggleStatus', $task->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="checkbox" onchange="this.form.submit()"
                                    {{ $task->is_completed ? 'checked' : '' }} class="form-check-input">
                            </form>
                        </td>
                        <td class="d-flex gap-2">
                            <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-sm btn-warning"
                                title="Edit Tugas" aria-label="Edit Tugas">
                                <i class="bi bi-pencil-fill"></i>
                            </a>
                            <form action="{{ route('tasks.destroy', $task->id) }}" method="POST"
                                onsubmit="return confirm('Yakin ingin menghapus tugas ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-muted py-4 text-center">
                            <i class="bi bi-emoji-frown fs-4 me-2"></i>
                            Tidak ada tugas yang tersedia.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>



    {{-- Tambahan Gaya --}}
    <style>
        .tambah-tugas-btn {
            transition: all 0.3s ease;
        }

        .tambah-tugas-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 0.5rem 1rem rgba(25, 135, 84, 0.2);
        }

        .table td,
        .table th {
            vertical-align: middle;
        }

        .custom-title {
            color: #748DAE;
        }

        .btn-warning,
        .btn-danger {
            color: white;
        }

        .btn-warning:hover {
            background-color: #f0ad4e;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }
    </style>
@endsection
