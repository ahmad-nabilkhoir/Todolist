@extends('layouts.app')

@section('content')
    <h1 class="mb-4">Edit Task</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('tasks.update', $task->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Title</label>
            <input type="text" name="title" class="form-control" value="{{ old('title', $task->title) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control">{{ old('description', $task->description) }}</textarea>
        </div>

        <div class="mb-3">
            <label for="due_date" class="form-label">Due Date</label>
            <input type="date" class="form-control" id="due_date" name="due_date"
                value="{{ old('due_date', \Carbon\Carbon::parse($task->due_date)->format('Y-m-d')) }}">

        </div>

        <div class="mb-3">
            <label for="priority" class="form-label">Priority</label>
            @php
                $priorities = ['High', 'Medium', 'Low'];
                $selected = old('priority', $task->priority);
                usort($priorities, function ($a, $b) use ($selected) {
                    return $a === $selected ? -1 : ($b === $selected ? 1 : 0);
                });
            @endphp

            <select class="form-select" id="priority" name="priority">
                @foreach ($priorities as $priority)
                    <option value="{{ $priority }}" {{ $priority == $selected ? 'selected' : '' }}>
                        {{ $priority }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" name="is_completed" id="is_completed" value="1"
                {{ old('is_completed', $task->is_completed) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_completed">
                Tandai sebagai selesai
            </label>
        </div>

        {{-- Tombol Submit --}}
        <button type="submit" class="btn btn-primary">Save Changes</button>
        <a href="{{ route('tasks.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
@endsection
