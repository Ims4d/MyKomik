@extends('layouts.dashboard')

@section('title', 'Genres Management')
@section('page-title', 'Genres Management')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-tags"></i> All Genres</h5>
            <a href="{{ route('dashboard.genres.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Add New Genre</a>
        </div>
        
        <div class="card-body">
            @if($genres->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th width="100">ID</th>
                                <th>Name</th>
                                <th>Comics Count</th>
                                <th width="150">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($genres as $genre)
                                <tr>
                                    <td>{{ $genre->genre_id }}</td>
                                    <td><strong>{{ $genre->name }}</strong></td>
                                    <td><span class="badge bg-info">{{ $genre->comics_count }} comics</span></td>
                                    <td>
                                        <a href="{{ route('dashboard.genres.edit', $genre->genre_id) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                                        <form action="{{ route('dashboard.genres.destroy', $genre->genre_id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this genre?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">{{ $genres->links() }}</div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-tags fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No genres found.</p>
                    <a href="{{ route('dashboard.genres.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Add Your First Genre</a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
