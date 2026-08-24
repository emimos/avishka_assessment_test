<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AgentDashboardController;
use App\Http\Controllers\GuestTicketController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::post('/ticket/create', [GuestTicketController::class, 'store'])->name('ticket.create');
Route::match(['get', 'post'], '/ticket/status', [GuestTicketController::class, 'getStatus'])->name('ticket.status');
Route::post('/ticket/reply', [GuestTicketController::class, 'reply'])->name('ticket.customer-reply');


//Agent Auth Routes
Route::get('/agent/login', [AuthController::class, 'showLogin'])->name('agent.login');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/agent/login', [AuthController::class, 'login'])->name('agent.login.post');
Route::post('/agent/logout', [AuthController::class, 'logout'])->name('agent.logout');

//Agent Protected Routes
Route::middleware(['auth'])->prefix('agent')->name('agent.')->group(function () {
    Route::get('/dashboard', [AgentDashboardController::class, 'index'])->name('dashboard');
    Route::get('/tickets/{ticket}', [AgentDashboardController::class, 'show'])->name('tickets.show');
    Route::post('/tickets/{ticket}/reply', [AgentDashboardController::class, 'reply'])->name('tickets.reply');
});