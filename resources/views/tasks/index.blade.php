@extends('layouts.app')

@section('content')
    <div class="container">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold custom-title">
                <i class="bi bi-clipboard2-check-fill me-2"></i> Daftar Tugas
            </h2>
            <a href="{{ route('tasks.create') }}" class="btn btn-custom tambah-tugas-btn">
                <i class="bi bi-plus-circle me-1"></i> Tambah Tugas
            </a>
        </div>

        {{-- Pesan Sukses --}}
        @if (session('success'))
            <div class="alert alert-success shadow-sm">
                <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
            </div>
        @endif

        {{-- Form Pencarian --}}
        <form method="GET" action="{{ route('tasks.index') }}" class="mb-4">
            <div class="input-group shadow-sm">
                <input type="text" name="search" class="form-control" placeholder="Cari tugas..."
                    value="{{ request('search') }}">
                <button class="btn btn-primary" type="submit">
                    <i class="bi bi-search"></i> Cari
                </button>
            </div>
        </form>

        {{-- Keterangan --}}
        <p class="text-muted small mb-3">
            <i class="bi bi-check2-square text-success me-1"></i> Centang jika tugas selesai
        </p>

        {{-- Daftar Tugas --}}
        @if ($tasks->count())
            <div class="list-group">
                @foreach ($tasks as $task)
                    <div class="list-group-item mb-3 p-3 rounded shadow-sm {{ $task->completed ? 'bg-light text-muted' : 'bg-white' }}">
                        <div class="d-flex justify-content-between align-items-start flex-wrap">
                            <div class="d-flex align-items-start gap-3">
                                <form action="{{ route('tasks.toggle', $task->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="checkbox" onchange="this.form.submit()" class="form-check-input mt-1"
                                        {{ $task->completed ? 'checked' : '' }}>
                                </form>
                                <div>
                                    <h5 class="mb-1 fw-semibold {{ $task->completed ? 'text-decoration-line-through' : '' }}">
                                        {{ $task->title }}
                                    </h5>
                                    <p class="mb-1 text-muted small">{{ $task->description }}</p>
                                    <span class="badge bg-secondary me-1">
                                        <i class="bi bi-calendar-event me-1"></i>
                                        {{ \Carbon\Carbon::parse($task->due_date)->format('d M Y') }}
                                    </span>
                                    @php
                                        $priority = strtolower($task->priority);
                                        $priorityColor = match($priority) {
                                            'low' => 'bg-info',
                                            'medium' => 'bg-warning text-dark',
                                            'high' => 'bg-danger',
                                            default => 'bg-secondary',
                                        };
                                    @endphp
                                    <span class="badge {{ $priorityColor }}">
                                        <i class="bi bi-exclamation-circle me-1"></i> {{ ucfirst($task->priority) }}
                                    </span>
                                </div>
                            </div>
                            <div class="d-flex gap-2 mt-3 mt-md-0">
                                <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil-fill"></i> Edit
                                </a>
                                <form action="{{ route('tasks.destroy', $task->id) }}" method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus tugas ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash-fill"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="alert alert-info text-center mt-4">
                <i class="bi bi-emoji-smile-upside-down me-1"></i> Belum ada tugas ditemukan.
            </div>
        @endif
    </div>

    {{-- Tambahan Gaya --}}
    @push('styles')
    <style>
        body {
            background-color: #FFEAEA;
        }

        .btn-primary {
            background-color: #748DAE;
            border-color: #748DAE;
        }

        .btn-success {
            background-color: #9ECAD6;
            border-color: #9ECAD6;
        }

        .btn-outline-secondary:hover {
            background-color: #F5CBCB;
            border-color: #F5CBCB;
            color: #000;
        }

        h2.text-primary {
            color: #748DAE !important;
        }
    </style>
@endpush

@endsection
