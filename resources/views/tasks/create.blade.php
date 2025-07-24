@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="mb-4">✍️ Tambah Tugas Baru</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Oops!</strong> Ada beberapa kesalahan.<br><br>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('tasks.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="title" class="form-label">Judul Tugas</label>
                <input type="text" name="title" class="form-control" placeholder="Contoh: Belajar Laravel" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Deskripsi</label>
                <textarea name="description" class="form-control" rows="4" placeholder="Tambahkan detail tugas..." required></textarea>
            </div>

            <div class="mb-3">
                <label for="priority" class="form-label">Tingkat Kepentingan</label>
                <select class="form-select" id="priority" name="priority" required>
                    <option value="">-- Tingkat Kepentingan --</option>
                    <option value="Low">Low</option>
                    <option value="Medium">Medium</option>
                    <option value="High">High</option>
                </select>
            </div>


            <div class="mb-3">
                <label for="due_date" class="form-label">Tanggal Tenggat</label>
                <input type="date" name="due_date" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">💾 Simpan</button>
            <a href="{{ route('tasks.index') }}" class="btn btn-secondary">↩️ Kembali</a>
        </form>
    </div>
@endsection
