@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="text-primary mb-4">
            <i class="bi bi-pencil-square me-2"></i>Edit Tugas
        </h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <h5 class="mb-2"><i class="bi bi-exclamation-triangle-fill"></i> Ada kesalahan:</h5>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('tasks.update', $task->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">📝 Judul Tugas</label>
                <input type="text" name="title" class="form-control" value="{{ old('title', $task->title) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">📋 Deskripsi</label>
                <textarea name="description" class="form-control" rows="4" placeholder="Tambahkan detail...">{{ old('description', $task->description) }}</textarea>
            </div>

            <div class="mb-3">
                <label for="due_date" class="form-label">📅 Tanggal Tenggat</label>
                <input type="date" class="form-control" id="due_date" name="due_date"
                    value="{{ old('due_date', \Carbon\Carbon::parse($task->due_date)->format('Y-m-d')) }}" required>
            </div>

            <div class="mb-3">
                <label for="priority" class="form-label">🚨 Prioritas</label>
                @php
                    $priorities = ['High', 'Medium', 'Low'];
                    $selected = old('priority', $task->priority);
                    usort($priorities, function ($a, $b) use ($selected) {
                        return $a === $selected ? -1 : ($b === $selected ? 1 : 0);
                    });
                @endphp

                <select class="form-select" id="priority" name="priority">
                    <option value="">-- Pilih Prioritas --</option>
                    @foreach ($priorities as $priority)
                        <option value="{{ $priority }}" {{ $priority == $selected ? 'selected' : '' }}>
                            {{ $priority }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-check mb-4">
                <input class="form-check-input" type="checkbox" name="is_completed" id="is_completed" value="1"
                    {{ old('is_completed', $task->is_completed) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_completed">
                    ✅ Tandai sebagai selesai
                </label>
            </div>

            <div class="mb-3">
                <label for="file" class="form-label">🖼️ Ganti Gambar (Opsional)</label>
                <input type="file" name="file" class="form-control">
                @if ($task->file)
                    <small class="text-muted d-block mt-2">
                        File saat ini:
                        <br>
                        <a href="{{ asset('storage/' . $task->file) }}" target="_blank">
                            <i class="bi bi-file-earmark-image"></i> Lihat File ({{ basename($task->file) }})
                        </a>
                        <br>
                        <img src="{{ asset('storage/' . $task->file) }}" alt="Gambar tugas"
                            style="max-height: 100px; display:block; margin-top:10px;">
                    </small>
                @endif


                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-save"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('tasks.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left-circle"></i> Batal
                    </a>
                </div>
        </form>
    </div>
@endsection
