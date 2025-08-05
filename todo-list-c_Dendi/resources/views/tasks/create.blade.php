@extends('layouts.app')

@section('content')
    <h1 class="mb-4">Add New Task</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
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
            <label class="form-label">Title</label>
            <input type="text" name="title" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control"></textarea>
        </div>
        <div class="form-group">
            <label for="deadline">Jatuh Tempo (Deadline)</label>
            <input type="date" name="deadline" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="status">Status</label>
            <select name="status" class="form-control" required>
                <option value="">Pilih Status</option>
                <option value="penting">Penting Sekali</option>
                <option value="menengah">Menengah / Setengah Penting</option>
                <option value="tidak">Tidak Harus</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="user_id" class="form-label">Didelegasikan ke</label>
            <select name="user_id" class="form-select">
                <option value="">-- Tidak Didelegasikan --</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}" 
                        {{ old('user_id', $task->user_id ?? '') == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
        </div>


        <div class="mb-3">
            <label for="user_id" class="form-label">Delegasikan ke User</label>
            <select name="user_id" class="form-select">
                <option value="">-- Pilih User --</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ (old('user_id', $task->user_id ?? '') == $user->id) ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-success">Save Task</button>
        <a href="{{ route('tasks.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
    
@endsection