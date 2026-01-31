<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\ComicController;
use App\Http\Controllers\Dashboard\ChapterController;
use App\Http\Controllers\Dashboard\UserController;
use App\Http\Controllers\Dashboard\GenreController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::get('/', function () {
    return redirect('/home');
});

// Home page (for regular users after login)
Route::get('/home', function () {
    return view('home');
})->middleware('auth')->name('home');

// Profile routes (protected by auth middleware)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [\App\Http\Controllers\ProfileController::class, 'changePassword'])->name('profile.password');
    Route::delete('/profile/avatar', [\App\Http\Controllers\ProfileController::class, 'deleteAvatar'])->name('profile.avatar.delete');
});

// Authentication routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Registration routes
Route::get('/register', [\App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [\App\Http\Controllers\Auth\RegisterController::class, 'register']);

// Dashboard routes (protected by admin middleware)
Route::middleware(['auth', 'admin'])->prefix('dashboard')->name('dashboard.')->group(function () {
    
    // Dashboard home
    Route::get('/', [DashboardController::class, 'index'])->name('index');
    
    // Comics management
    Route::resource('comics', ComicController::class);
    
    // Chapters management (nested under comics)
    Route::get('comics/{comic}/chapters', [ChapterController::class, 'index'])->name('chapters.index');
    Route::get('comics/{comic}/chapters/create', [ChapterController::class, 'create'])->name('chapters.create');
    Route::post('comics/{comic}/chapters', [ChapterController::class, 'store'])->name('chapters.store');
    Route::get('comics/{comic}/chapters/{chapter}/edit', [ChapterController::class, 'edit'])->name('chapters.edit');
    Route::put('comics/{comic}/chapters/{chapter}', [ChapterController::class, 'update'])->name('chapters.update');
    Route::delete('comics/{comic}/chapters/{chapter}', [ChapterController::class, 'destroy'])->name('chapters.destroy');
    Route::delete('pages/{page}', [ChapterController::class, 'deletePage'])->name('pages.destroy');
    
    // Users management
    Route::resource('users', UserController::class);
    
    // Genres management
    Route::resource('genres', GenreController::class)->except(['show']);
});