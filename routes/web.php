<?php

use App\Livewire\Home;
use App\Livewire\Institution\CreateInstitution;
use App\Livewire\Institution\EditInstitution;
use App\Livewire\Institution\HomeInstitution;
use App\Livewire\Institution\ViewInstitution;
use App\Livewire\Mine\CreateMine;
use App\Livewire\Mine\EditMine;
use App\Livewire\Mine\EvaluateMine;
use App\Livewire\Mine\ViewMine;
use App\Livewire\Report\CreateReport;
use App\Livewire\Report\ReportHome;
use App\Livewire\Report\ViewReport;
use App\Livewire\User\CreateUser;
use App\Livewire\User\EditUser;
use App\Livewire\User\HomeUser;
use App\Livewire\User\ViewUser;
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
    Route::get('create', CreateMine::class)->name('mine.create');
    Route::get('{mine}/view', ViewMine::class)->name('mine.view');
    Route::get('{mine}/report', CreateReport::class)->name('mine.report');
    Route::get('{mine}/edit', EditMine::class)->name('mine.edit');
    Route::get('{mine}/evaluate', EvaluateMine::class)->name('mine.evaluate');
});
Route::prefix('users')->group(function(){
    Route::get('', HomeUser::class)->name('users');
    Route::get('create', CreateUser::class)->name('user.create');
    Route::get('{user}/view', ViewUser::class)->name('user.view');
    Route::get('{user}/edit', EditUser::class)->name('user.edit');
});

Route::prefix('institutions')->group(function(){
    Route::get('', HomeInstitution::class)->name('institution.home');
    Route::get('create', CreateInstitution::class)->name('institution.create');
    Route::get('{institution}/view', ViewInstitution::class)->name('institution.view');
    Route::get('{institution}/edit', EditInstitution::class)->name('institution.edit');
});

Route::prefix('reports')->group(function(){
    Route::get('', ReportHome::class)->name('report.home');
    Route::get('{report}/view', ViewReport::class)->name('report.view');
});
