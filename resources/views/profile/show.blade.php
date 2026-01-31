@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Flash Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Profile Card -->
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="fas fa-user-circle"></i> My Profile</h4>
                    <a href="{{ route('profile.edit') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-edit"></i> Edit Profile
                    </a>
                </div>
                
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-4 text-center mb-3 mb-md-0">
                            <div class="profile-avatar mb-3">
                                <img src="{{ $user->getAvatarUrl() }}" 
                                     alt="{{ $user->username }}" 
                                     class="rounded-circle border border-3 border-primary"
                                     style="width: 150px; height: 150px; object-fit: cover;">
                            </div>
                            <h5 class="mb-1">{{ $user->display_name ?? $user->username }}</h5>
                            <p class="text-muted mb-0">{{ $user->username }}</p>
                            <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : 'info' }} mt-2">
                                {{ ucfirst($user->role) }}
                            </span>
                        </div>

                        <div class="col-md-8">
                            <h5 class="mb-3"><i class="fas fa-info-circle text-primary"></i> Account Information</h5>
                            
                            <div class="mb-3">
                                <label class="fw-bold text-muted small">USERNAME</label>
                                <p class="mb-2">{{ $user->username }}</p>
                            </div>

                            <div class="mb-3">
                                <label class="fw-bold text-muted small">DISPLAY NAME</label>
                                <p class="mb-2">{{ $user->display_name ?? '-' }}</p>
                            </div>

                            <div class="mb-3">
                                <label class="fw-bold text-muted small">ROLE</label>
                                <p class="mb-2">
                                    <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : 'info' }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </p>
                            </div>

                            <div class="mb-3">
                                <label class="fw-bold text-muted small">MEMBER SINCE</label>
                                <p class="mb-2">
                                    <i class="far fa-calendar"></i> {{ $user->created_at }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Statistics -->
                    <h5 class="mb-3"><i class="fas fa-chart-bar text-primary"></i> My Activity</h5>
                    <div class="row text-center">
                        <div class="col-md-4 mb-3 mb-md-0">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <i class="fas fa-star text-warning fa-2x mb-2"></i>
                                    <h3 class="mb-0">{{ $stats['total_ratings'] }}</h3>
                                    <p class="text-muted mb-0 small">Ratings Given</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-3 mb-md-0">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <i class="fas fa-comments text-primary fa-2x mb-2"></i>
                                    <h3 class="mb-0">{{ $stats['total_comments'] }}</h3>
                                    <p class="text-muted mb-0 small">Comments Posted</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <i class="fas fa-book-reader text-success fa-2x mb-2"></i>
                                    <h3 class="mb-0">{{ $stats['reading_progress'] }}</h3>
                                    <p class="text-muted mb-0 small">Chapters Read</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-light text-center">
                    <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Home
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        var alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            var bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
</script>
@endpush
@endsection