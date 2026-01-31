@extends('layouts.app')

@section('title', 'Home')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="jumbotron bg-light p-5 rounded">
                <h1 class="display-4">Welcome to Comic Reader!</h1>
                <p class="lead">Discover and read your favorite comics online.</p>
                <hr class="my-4">
                <p>This is the home page for regular users. Comic listing will be displayed here.</p>
                @if(Auth::user()->role === 'admin')
                    <a class="btn btn-primary btn-lg" href="{{ route('dashboard.index') }}" role="button">
                        <i class="fas fa-shield-alt"></i> Go to Dashboard
                    </a>
                @endif
            </div>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-12">
            <h3 class="mb-4">Featured Comics</h3>
            <p class="text-muted">Coming soon... Comic listing will be implemented here.</p>
        </div>
    </div>
</div>
@endsection
