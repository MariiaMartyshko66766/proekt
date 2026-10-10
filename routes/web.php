<?php

use App\Http\Controllers\AttachmentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TicketCommentController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // zgłoszenia
    Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
    Route::get('/tickets/create', [TicketController::class, 'create'])
        ->middleware('role:user,admin')
        ->name('tickets.create');
    Route::post('/tickets', [TicketController::class, 'store'])
        ->middleware('role:user,admin')
        ->name('tickets.store');
    Route::get('/tickets/{ticket}', [TicketController::class, 'show'])->name('tickets.show');

    // działania na zgłoszeniu
    Route::post('/tickets/{ticket}/take', [TicketController::class, 'take'])
        ->middleware('role:agent')
        ->name('tickets.take');
    Route::post('/tickets/{ticket}/assign', [TicketController::class, 'assign'])
        ->middleware('role:admin')
        ->name('tickets.assign');
    Route::patch('/tickets/{ticket}/status', [TicketController::class, 'updateStatus'])
        ->name('tickets.status');
    Route::patch('/tickets/{ticket}/details', [TicketController::class, 'updateDetails'])
        ->middleware('role:agent,admin')
        ->name('tickets.details');

    // komentarze i załączniki
    Route::post('/tickets/{ticket}/comments', [TicketCommentController::class, 'store'])
        ->name('tickets.comments.store');
    Route::get('/attachments/{attachment}', [AttachmentController::class, 'download'])
        ->name('attachments.download');

    // profil (Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';