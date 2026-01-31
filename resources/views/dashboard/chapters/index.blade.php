@extends('layouts.dashboard')

@section('title', 'Chapters - ' . $comic->title)
@section('page-title', 'Manage Chapters: ' . $comic->title)

@section('content')
<div class="container-fluid">
    <div class="card mb-3">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-2">
                    @if($comic->cover_image_url)
                        <img src="{{ $comic->cover_image_url }}" alt="{{ $comic->title }}" class="img-fluid rounded">
                    @endif
                </div>
                <div class="col-md-10">
                    <h4>{{ $comic->title }}</h4>
                    <p class="text-muted mb-2">{{ $comic->synopsis }}</p>
                    <span class="badge bg-secondary">{{ $comic->totalChapters() }} chapters</span>
                    <span class="badge bg-{{ $comic->status === 'ongoing' ? 'success' : ($comic->status === 'completed' ? 'primary' : 'warning') }}">{{ ucfirst($comic->status) }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-list"></i> Chapters</h5>
            <div>
                <a href="{{ route('dashboard.chapters.create', $comic->comic_id) }}" class="btn btn-primary"><i class="fas fa-plus"></i> Add Chapter</a>
                <a href="{{ route('dashboard.comics.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back to Comics</a>
            </div>
        </div>
        
        <div class="card-body">
            @if($chapters->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Chapter #</th>
                                <th>Title</th>
                                <th>Pages</th>
                                <th>Release Date</th>
                                <th width="200">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($chapters as $chapter)
                                <tr>
                                    <td><strong>Chapter {{ $chapter->chapter_number }}</strong></td>
                                    <td>{{ $chapter->title ?? '-' }}</td>
                                    <td><span class="badge bg-info">{{ $chapter->totalPages() }} pages</span></td>
                                    <td>{{ $chapter->release_date ? $chapter->release_date->format('Y-m-d') : '-' }}</td>
                                    <td>
                                        <a href="{{ route('dashboard.chapters.edit', [$comic->comic_id, $chapter->chapter_id]) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                                        <form action="{{ route('dashboard.chapters.destroy', [$comic->comic_id, $chapter->chapter_id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this chapter and all its pages?');">
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
                <div class="mt-3">{{ $chapters->links() }}</div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-list fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No chapters yet. Add the first chapter!</p>
                    <a href="{{ route('dashboard.chapters.create', $comic->comic_id) }}" class="btn btn-primary"><i class="fas fa-plus"></i> Add First Chapter</a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
