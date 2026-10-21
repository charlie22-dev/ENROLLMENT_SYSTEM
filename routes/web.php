<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Adviser;
use App\Http\Controllers\Admission;
use App\Http\Controllers\Registrar;
use App\Http\Controllers\Cashier;
use App\Http\Controllers\Student;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect()->route('dashboard'));

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

// ── Student ──────────────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:student'])
    ->prefix('student')
    ->name('student.')
    ->group(function () {
        Route::get('/dashboard', [Student\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/board',     [Student\BoardController::class, 'index'])->name('board');

        Route::prefix('pef')->name('pef.')->group(function () {
            Route::get('/create',         [Student\PefController::class, 'create'])->name('create');
            Route::post('/',              [Student\PefController::class, 'store'])->name('store');
            Route::get('/{pef}',          [Student\PefController::class, 'show'])->name('show');
            Route::get('/{pef}/edit',     [Student\PefController::class, 'edit'])->name('edit');
            Route::put('/{pef}',          [Student\PefController::class, 'update'])->name('update');
            Route::post('/{pef}/submit',  [Student\PefController::class, 'submit'])->name('submit');
            Route::delete('/{pef}',       [Student\PefController::class, 'cancel'])->name('cancel');
            Route::get('/{pef}/cor',      [Student\PefController::class, 'cor'])->name('cor');
            Route::get('/{pef}/cor/pdf',  [Student\PefController::class, 'corPdf'])->name('cor.pdf');
        });
    });

// ── Adviser ───────────────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:adviser'])
    ->prefix('adviser')
    ->name('adviser.')
    ->group(function () {
        Route::get('/queue',               [Adviser\PefReviewController::class, 'queue'])->name('queue');
        Route::get('/pef/{pef}',           [Adviser\PefReviewController::class, 'show'])->name('pef.show');
        Route::post('/pef/{pef}/approve',  [Adviser\PefReviewController::class, 'approve'])->name('pef.approve');
        Route::post('/pef/{pef}/return',   [Adviser\PefReviewController::class, 'return'])->name('pef.return');
        Route::post('/pef/{pef}/reject',   [Adviser\PefReviewController::class, 'reject'])->name('pef.reject');
    });

// ── Admission ────────────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:admission'])
    ->prefix('admission')
    ->name('admission.')
    ->group(function () {
        Route::get('/queue',              [Admission\PefReviewController::class, 'queue'])->name('queue');
        Route::get('/pef/{pef}',          [Admission\PefReviewController::class, 'show'])->name('pef.show');
        Route::post('/pef/{pef}/approve', [Admission\PefReviewController::class, 'approve'])->name('pef.approve');
        Route::post('/pef/{pef}/return',  [Admission\PefReviewController::class, 'return'])->name('pef.return');
    });

// ── Registrar ────────────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:registrar'])
    ->prefix('registrar')
    ->name('registrar.')
    ->group(function () {
        Route::get('/queue',              [Registrar\PefReviewController::class, 'queue'])->name('queue');
        Route::get('/pef/{pef}',          [Registrar\PefReviewController::class, 'show'])->name('pef.show');
        Route::post('/pef/{pef}/approve', [Registrar\PefReviewController::class, 'approve'])->name('pef.approve');
        Route::post('/pef/{pef}/return',  [Registrar\PefReviewController::class, 'return'])->name('pef.return');
    });

// ── Cashier ──────────────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:cashier'])
    ->prefix('cashier')
    ->name('cashier.')
    ->group(function () {
        Route::get('/queue',              [Cashier\PefReviewController::class, 'queue'])->name('queue');
        Route::get('/pef/{pef}',          [Cashier\PefReviewController::class, 'show'])->name('pef.show');
        Route::post('/pef/{pef}/payment', [Cashier\PefReviewController::class, 'storePayment'])->name('pef.payment');
    });

// ── Admin ────────────────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');

        Route::resource('departments', Admin\DepartmentController::class);
        Route::resource('programs',    Admin\ProgramController::class);
        Route::resource('subjects',    Admin\SubjectController::class);
        Route::resource('curriculum',  Admin\CurriculumController::class)->except('show');
        Route::resource('rooms',       Admin\RoomController::class);
        Route::resource('instructors', Admin\InstructorController::class);
        Route::resource('users',       Admin\UserController::class);

        Route::resource('terms', Admin\TermController::class);
        Route::post('terms/{term}/activate', [Admin\TermController::class, 'activate'])->name('terms.activate');

        Route::resource('sections', Admin\SectionController::class);
        Route::resource('sections.schedules', Admin\SectionScheduleController::class)
            ->shallow()
            ->except(['index', 'show']);

        Route::get('subjects/{subject}/prerequisites',                  [Admin\PrerequisiteController::class, 'index'])->name('subjects.prerequisites.index');
        Route::post('subjects/{subject}/prerequisites',                 [Admin\PrerequisiteController::class, 'store'])->name('subjects.prerequisites.store');
        Route::delete('subjects/{subject}/prerequisites/{prerequisite}', [Admin\PrerequisiteController::class, 'destroy'])->name('subjects.prerequisites.destroy');
    });

require __DIR__.'/auth.php';
