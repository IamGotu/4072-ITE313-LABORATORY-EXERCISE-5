<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    // Check if the user is authenticated
    if (Auth::check()) {
        // Redirect to the dashboard if the user is logged in
        return redirect()->route('dashboard');
    } else {
        // Redirect to the login page if the user is not logged in
        return redirect()->route('login');
    }
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Route for displaying the profile page
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    
    // Route for updating profile information (name, birthdate, etc.)
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    
    // Route for updating email address
    Route::patch('/profile/email', [ProfileController::class, 'updateEmail'])->name('profile.email.update');
    
    // Route for deleting the user's account
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
