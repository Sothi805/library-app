<?php

use App\Http\Controllers\BookCategoryController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/backup', [ProfileController::class, 'backup'])->name('profile.backup');
});

Route::middleware('auth')->group(function () {

    Route::resource('books', BookController::class);
    Route::controller(\App\Http\Controllers\BorrowReturnController::class)->group(function () {
        Route::get('/borrow-return', 'index')->name('borrow-return.index');
        Route::get('/borrow-return/borrow', 'borrowSelect')->name('borrow-return.borrow-select');
        Route::get('/borrow-return/{book}/borrow', 'borrowForm')->name('borrow-return.borrow.form');
        Route::post('/borrow-return/{book}/borrow', 'borrow')->name('borrow-return.borrow');
        Route::get('/borrow-return/{book}/return/{borrow}', 'returnForm')->name('borrow-return.return.form');
        Route::post('/borrow-return/{book}/return', 'return')->name('borrow-return.return');
    });


    Route::resource('book-categories', BookCategoryController::class);

    Route::resource('members', \App\Http\Controllers\MemberController::class);
});


require __DIR__.'/auth.php';
