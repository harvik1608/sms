<?php
    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\AuthController;
    use App\Http\Controllers\DashboardController;
    use App\Http\Controllers\UserController;
    use App\Http\Controllers\PlanController;

    Route::get('/', [AuthController::class, 'index'])->name('login');
    Route::post('/check-login', [AuthController::class, 'checkLogin'])->name('check.login');
    Route::get('/logout', [AuthController::class, 'logout'])->name('admin.logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/general-settings', [DashboardController::class, 'general_settings'])->name('admin.general-settings');

    Route::resource('users', UserController::class);
    Route::get('/load-users', [UserController::class, 'load'])->name('admin.users.load');

    Route::resource('plans', PlanController::class);
    Route::get('/load-plans', [PlanController::class, 'load'])->name('admin.plans.load');
