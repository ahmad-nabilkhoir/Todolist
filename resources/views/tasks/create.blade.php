@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex align-items-center mb-4 gap-2">
            <h2 class="fw-bold custom-title mb-0">
                <i class="bi bi-pencil-square text-primary me-1"></i> Tambah Tugas Baru
            </h2>
        </div>

        {{-- Error Validation --}}
        @if ($errors->any())
            <div class="alert alert-danger shadow-sm">
                <i class="bi bi-exclamation-triangle-fill me-1"></i>
                <strong>Oops!</strong> Ada beberapa kesalahan:
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>🔸 {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Form Tambah Tugas --}}
        <form action="{{ route('tasks.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="title" class="form-label fw-semibold">
                    <i class="bi bi-type me-1"></i> Judul Tugas
                </label>
                <input type="text" name="title" class="form-control" placeholder="Contoh: Belajar Laravel" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label fw-semibold">
                    <i class="bi bi-card-text me-1"></i> Deskripsi
                </label>
                <textarea name="description" class="form-control" rows="4" placeholder="Tambahkan detail tugas..." required></textarea>
            </div>

            <div class="mb-3">
                <label for="priority" class="form-label fw-semibold">
                    <i class="bi bi-flag me-1"></i> Tingkat Kepentingan
                </label>
                <select class="form-select" id="priority" name="priority" required>
                    <option value="">-- Pilih Prioritas --</option>
                    <option value="Low">🟢 Low</option>
                    <option value="Medium">🟡 Medium</option>
                    <option value="High">🔴 High</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="due_date" class="form-label fw-semibold">
                    <i class="bi bi-calendar-date me-1"></i> Tanggal Tenggat
                </label>
                <input type="date" name="due_date" class="form-control" required>
                <div class="form-group">
                    <label for="file">Upload File:</label>
                    <input type="file" name="file" class="form-control">
                </div>

                <div class="d-flex justify-content-start mt-4 gap-2">
                    <button type="submit" class="btn btn-custom">
                        <i class="bi bi-save2 me-1"></i> Simpan
                    </button>
                    <a href="{{ route('tasks.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left-circle me-1"></i> Kembali
                    </a>
                </div>
        </form>
    </div>

    {{-- Style --}}
    <style>
        .custom-title {
            color: #748DAE;
        }

        .btn-custom {
            background-color: #9ECAD6;
            color: #fff;
            transition: 0.3s ease;
        }

        .btn-custom:hover {
            background-color: #748DAE;
            color: #fff;
        }

        .form-label {
            color: #444;
        }

        .form-control,
        .form-select {
            border-radius: 0.5rem;
        }

        form {
            background-color: #FFEAEA;
        }
    </style>
@endsection
