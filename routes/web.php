<?php

use App\Livewire\Home;
use App\Livewire\Mine\CreateMine;
use App\Livewire\Mine\ReportMine;
use App\Livewire\Mine\ValidateMine;
use App\Livewire\Mine\ViewMine;
use App\Livewire\User\UserHome;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', Home::class)->name('home');
Route::prefix('mines')->group(function(){
    Route::get('', CreateMine::class)->name('mine.create');
    Route::get('{mine}', ViewMine::class)->name('mine.view');
    Route::get('{mine}/report', ReportMine::class)->name('mine.report');
    Route::get('{mine}/validate', ValidateMine::class)->name('mine.validate');
});
Route::prefix('users')->group(function(){
    Route::get('', UserHome::class)->name('users');
});


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
