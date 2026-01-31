@extends('layouts.dashboard')

@section('title', 'Comics Management')
@section('page-title', 'Comics Management')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-book"></i> All Comics</h5>
            <a href="{{ route('dashboard.comics.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add New Comic
            </a>
        </div>
        
        <div class="card-body">
            <!-- Search & Filter -->
            <form action="{{ route('dashboard.comics.index') }}" method="GET" class="row g-3 mb-4">
                <div class="col-md-6">
                    <input type="text" 
                           name="search" 
                           class="form-control" 
                           placeholder="Search by title or author..." 
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-4">
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="ongoing" {{ request('status') === 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="hiatus" {{ request('status') === 'hiatus' ? 'selected' : '' }}>Hiatus</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-secondary w-100">
                        <i class="fas fa-search"></i> Search
                    </button>
                </div>
            </form>

            <!-- Comics Table -->
            @if($comics->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th width="80">Cover</th>
                                <th>Title</th>
                                <th>Author</th>
                                <th>Genres</th>
                                <th>Chapters</th>
                                <th>Status</th>
                                <th width="200">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($comics as $comic)
                                <tr>
                                    <td>
                                        @if($comic->cover_image_url)
                                            <img src="{{ $comic->cover_image_url }}" 
                                                 alt="{{ $comic->title }}" 
                                                 class="img-thumbnail" 
                                                 style="max-width: 60px;">
                                        @else
                                            <div class="bg-secondary text-white text-center" 
                                                 style="width: 60px; height: 80px; line-height: 80px;">
                                                No Image
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $comic->title }}</strong><br>
                                        <small class="text-muted">ID: {{ $comic->comic_id }}</small>
                                    </td>
                                    <td>{{ $comic->author ?? '-' }}</td>
                                    <td>
                                        @foreach($comic->genres as $genre)
                                            <span class="badge bg-info">{{ $genre->name }}</span>
                                        @endforeach
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $comic->totalChapters() }} chapters</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $comic->status === 'ongoing' ? 'success' : ($comic->status === 'completed' ? 'primary' : 'warning') }}">
                                            {{ ucfirst($comic->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('dashboard.chapters.index', $comic->comic_id) }}" 
                                           class="btn btn-sm btn-info" 
                                           title="Manage Chapters">
                                            <i class="fas fa-list"></i>
                                        </a>
                                        <a href="{{ route('dashboard.comics.edit', $comic->comic_id) }}" 
                                           class="btn btn-sm btn-warning" 
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('dashboard.comics.destroy', $comic->comic_id) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to delete this comic? This will also delete all chapters and pages.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-3">
                    {{ $comics->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-book fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No comics found. Start by adding a new comic!</p>
                    <a href="{{ route('dashboard.comics.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Your First Comic
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
