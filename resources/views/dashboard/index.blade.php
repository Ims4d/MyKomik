@extends('layouts.dashboard')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard Overview')

@section('content')
<div class="container-fluid">
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Total Comics</p>
                            <h3 class="mb-0">{{ $stats['total_comics'] }}</h3>
                        </div>
                        <div class="text-primary" style="font-size: 40px;">
                            <i class="fas fa-book"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Total Chapters</p>
                            <h3 class="mb-0">{{ $stats['total_chapters'] }}</h3>
                        </div>
                        <div class="text-success" style="font-size: 40px;">
                            <i class="fas fa-list"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Total Users</p>
                            <h3 class="mb-0">{{ $stats['total_users'] }}</h3>
                        </div>
                        <div class="text-warning" style="font-size: 40px;">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card danger">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Total Ratings</p>
                            <h3 class="mb-0">{{ $stats['total_ratings'] }}</h3>
                        </div>
                        <div class="text-danger" style="font-size: 40px;">
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Comics -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-book text-primary"></i> Recent Comics</h5>
                </div>
                <div class="card-body">
                    @if($recentComics->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($recentComics as $comic)
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">{{ $comic->title }}</h6>
                                            <small class="text-muted">
                                                <i class="fas fa-user"></i> {{ $comic->author ?? 'Unknown' }} | 
                                                <i class="fas fa-calendar"></i> {{ $comic->created_at }}
                                            </small>
                                        </div>
                                        <span class="badge bg-{{ $comic->status === 'ongoing' ? 'success' : ($comic->status === 'completed' ? 'primary' : 'warning') }}">
                                            {{ ucfirst($comic->status) }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted text-center mb-0">No comics yet.</p>
                    @endif
                </div>
                <div class="card-footer bg-white">
                    <a href="{{ route('dashboard.comics.index') }}" class="btn btn-sm btn-primary">
                        View All Comics <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Recent Users -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-users text-success"></i> Recent Users</h5>
                </div>
                <div class="card-body">
                    @if($recentUsers->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($recentUsers as $user)
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">{{ $user->display_name ?? $user->username }}</h6>
                                            <small class="text-muted">
                                                <i class="fas fa-user"></i> @{{ $user->username }} | 
                                                <i class="fas fa-calendar"></i> {{ $user->created_at }}
                                            </small>
                                        </div>
                                        <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : 'info' }}">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted text-center mb-0">No users yet.</p>
                    @endif
                </div>
                <div class="card-footer bg-white">
                    <a href="{{ route('dashboard.users.index') }}" class="btn btn-sm btn-success">
                        View All Users <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Rated Comics -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-star text-warning"></i> Top Rated Comics</h5>
                </div>
                <div class="card-body">
                    @if($topRatedComics->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Title</th>
                                        <th>Author</th>
                                        <th>Average Rating</th>
                                        <th>Total Ratings</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($topRatedComics as $index => $comic)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $comic->title }}</td>
                                            <td>{{ $comic->author ?? 'Unknown' }}</td>
                                            <td>
                                                <i class="fas fa-star text-warning"></i>
                                                {{ number_format($comic->average_rating, 1) }}
                                            </td>
                                            <td>{{ $comic->ratings_count }}</td>
                                            <td>
                                                <span class="badge bg-{{ $comic->status === 'ongoing' ? 'success' : ($comic->status === 'completed' ? 'primary' : 'warning') }}">
                                                    {{ ucfirst($comic->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center mb-0">No rated comics yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
