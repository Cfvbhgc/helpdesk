<?php

use App\Http\Livewire\AdminDashboard;
use App\Http\Livewire\KnowledgeBaseList;
use App\Http\Livewire\TicketCreate;
use App\Http\Livewire\TicketDetail;
use App\Http\Livewire\TicketList;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/knowledge-base', KnowledgeBaseList::class)->name('knowledge-base');

// Auth routes (using Laravel's built-in auth)
Route::middleware(['auth'])->group(function () {
    // Tickets
    Route::get('/tickets', TicketList::class)->name('tickets.index');
    Route::get('/tickets/create', TicketCreate::class)->name('tickets.create');
    Route::get('/tickets/{ticket}', TicketDetail::class)->name('tickets.show');

    // Admin dashboard
    Route::get('/admin/dashboard', AdminDashboard::class)
        ->name('admin.dashboard');
});

// Simple auth routes for demo purposes
Route::get('/login', function () {
    return view('auth.login');
})->name('login')->middleware('guest');

Route::post('/login', function () {
    $credentials = request()->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (auth()->attempt($credentials, request()->boolean('remember'))) {
        request()->session()->regenerate();
        return redirect()->intended('/tickets');
    }

    return back()->withErrors([
        'email' => 'The provided credentials do not match our records.',
    ])->onlyInput('email');
})->middleware('guest');

Route::post('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout')->middleware('auth');
