@extends('layouts.app')

@section('content')
    <h1 class="mb-4">My To-Do List Indra Juliawan</h1>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- Tampilkan task deadline < 3 hari & belum selesai --}}
    @php
        use Carbon\Carbon;
        $today = Carbon::today();
        $threeDays = $today->copy()->addDays(3);
        $upcomingTasks = $tasks->where('is_completed', false)
                               ->whereBetween('deadline', [$today, $threeDays])
                               ->sortBy('deadline')
                               ->groupBy('status');
    @endphp

    @if ($upcomingTasks->isNotEmpty())
        <h3 class="mt-4">Task Mendekati Deadline (≤ 3 Hari)</h3>
        @foreach ($upcomingTasks as $status => $taskGroup)
            <h5 class="mt-3">
                @if($status == 'penting') Penting Sekali
                @elseif($status == 'menengah') Menengah / Setengah Penting
                @else Tidak Harus @endif
            </h5>
            <ul>
                @foreach ($taskGroup as $task)
                    <li>
                        <strong>{{ $task->title }}</strong> - Deadline: {{ $task->deadline }}
                    </li>
                @endforeach
            </ul>
        @endforeach
        <hr>
    @endif

    {{-- Tampilkan seluruh task dalam tabel --}}
    @if ($tasks->isEmpty())
        <p>No tasks found. <a href="{{ route('tasks.create') }}">Add one!</a></p>
    @else
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Completed</th>
                    <th>Deadline</th>
                    <th>Status</th>
                    <th>Actions</th>
                    <th>Delegasi</th>
                    <th>Penanggung Jawab</th>


                </tr>
            </thead>
            <tbody>
                @foreach ($tasks as $task)
                    <tr>
                        <td>{{ $task->title }}</td>
                        <td>{{ $task->description }}</td>
                        <td>
                            @if($task->is_completed)
                            <span class="badge bg-success">Yes</span>
                            @else
                            <span class="badge bg-secondary">No</span>
                            @endif
                        </td>
                        <td>{{ \Carbon\Carbon::parse($task->deadline)->format('d M Y') }}</td>
                        <td>
                            @if($task->status == 'penting')
                                <span class="badge bg-danger">Penting Sekali</span>
                            @elseif($task->status == 'menengah')
                                <span class="badge bg-warning text-dark">Menengah</span>
                            @else
                                <span class="badge bg-secondary">Tidak Harus</span>
                            @endif
                        </td>

                        <td>
                            <a class="btn btn-sm btn-warning" href="{{ route('tasks.edit', $task->id) }}">Edit</a>
                            <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                    Delete
                                </button>
                            </form>
                        </td>
                        <td>{{ $task->user ? $task->user->name : '-' }}</td>
                        <td>
                            {{ $task->user ? $task->user->name : 'Tidak Didelegasikan' }}
                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection
