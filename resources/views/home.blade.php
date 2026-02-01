@extends('layouts.app')

@section('title', 'Home')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="jumbotron bg-light p-5 rounded">
                <h1 class="display-4">Selamat Datang di MyKomik!</h1>
                <p class="lead">Temukan dan baca komik favoritmu secara online.</p>
                <hr class="my-4">
                <p>Ini adalah halaman beranda untuk pengguna biasa. Jelajahi koleksi komik kami di bawah ini.</p>
                @if(Auth::user()->role === 'admin')
                    <a class="btn bg-primary btn-lg" href="{{ route('dashboard.index') }}" role="button">
                        <i class="fas fa-shield-alt"></i> Pergi ke Dasbor
                    </a>
                @endif
            </div>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-12">
            <h3 class="mb-4">Komik Terbaru</h3>
        </div>
        @forelse ($comics as $comic)
            <div class="col-md-3 mb-4">
                <div class="card">
                    <a href="{{ route('comics.show', $comic) }}">
                        <img src="{{ asset($comic->cover_image_url) }}" class="card-img-top" alt="{{ $comic->title }}">
                    </a>
                    <div class="card-body">
                        <h5 class="card-title">
                            <a href="{{ route('comics.show', $comic) }}">{{ $comic->title }}</a>
                        </h5>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <p class="text-muted">Tidak ada komik ditemukan.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
