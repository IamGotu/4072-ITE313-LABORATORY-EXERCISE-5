<?php

use App\Http\Controllers\NotificationController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

// Redirect root URL to /login
Route::get('/', function () {
    return redirect('/login');
});

// Define the login route
Route::get('/login', function () {
    return view('auth.login');
});

// Dashboard route with authentication
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile/update-email', [ProfileController::class, 'updateEmail'])->name('profile.updateEmail');
    Route::patch('/profile/update-name', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Post routes
    Route::post('/posts', [PostController::class, 'store']);
    Route::get('/posts', [PostController::class, 'index']);
    Route::delete('/posts/{id}', [PostController::class, 'destroy']);
    Route::post('/posts/{post}/like', [PostController::class, 'likePost']);
    Route::post('/posts/{post}/comment', [PostController::class, 'addComment']);

    // Notification routes
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications');
    Route::post('/notifications/mark-as-read/{notificationId}', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
    Route::delete('/notifications/delete/{notificationId}', [NotificationController::class, 'destroy'])->name('notifications.delete');
    Route::delete('/notifications/delete-all-read', [NotificationController::class, 'deleteAllRead'])->name('notifications.deleteAllRead');

    // Friends routes
    Route::get('/friends', [FriendController::class, 'index'])->name('friends');
    Route::post('/friends/add/{friendId}', [FriendController::class, 'addFriend'])->name('friends.add');
    Route::post('/friends/cancel/{friendId}', [FriendController::class, 'cancelFriendRequest'])->name('friends.cancel');
    Route::post('/friends/unfriend/{friendId}', [FriendController::class, 'unfriend'])->name('friends.unfriend');
    Route::post('/friends/confirm/{requestId}', [FriendController::class, 'confirm'])->name('friends.confirm');

    
});

Route::middleware(['auth'])->group(function () {
    Route::get('messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('messages/conversation/{userId}', [MessageController::class, 'getConversation'])->name('messages.conversation');
    Route::get('/messages', [MessageController::class, 'index'])->name('messages');
    Route::post('messages/send', [MessageController::class, 'send'])->name('messages.send');
    Route::delete('/messages/{id}/delete', [MessageController::class, 'deleteMessage']);
    Route::post('/messages/conversation/{id}/read', [MessageController::class, 'markAsRead'])->name('messages.markAsRead');
    Route::get('messages/retrieve/{userId}', [MessageController::class, 'retrieve'])->name('messages.retrieve');
    Route::get('search', [MessageController::class, 'search'])->name('messages.search');
});

// Optional: API routes if needed
// You can move this part if you're handling API separately
// Route::prefix('api')->middleware('auth:api')->group(function () {
//     Route::post('/posts', [PostController::class, 'store']);
//     Route::get('/posts', [PostController::class, 'index']);
// });

require __DIR__.'/auth.php';
