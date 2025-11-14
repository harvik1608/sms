<?php
    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\AuthController;
    use App\Http\Controllers\DashboardController;
    use App\Http\Controllers\UserController;
    use App\Http\Controllers\PlanController;
    use App\Http\Controllers\ContactController;
    use App\Http\Controllers\MessageController;

    Route::get('/', [AuthController::class, 'index'])->name('login');
    Route::post('/check-login', [AuthController::class, 'checkLogin'])->name('check.login');

    Route::middleware(['auth'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
        Route::get('/general-settings', [DashboardController::class, 'general_settings'])->name('admin.general-settings');
        Route::get('/download-sample', [ContactController::class, 'downloadSample'])->name('admin.download.sample');

        Route::resource('users', UserController::class);
        Route::get('/load-users', [UserController::class, 'load'])->name('admin.users.load');

        Route::resource('plans', PlanController::class);
        Route::get('/load-plans', [PlanController::class, 'load'])->name('admin.plans.load');

        Route::resource('contacts', ContactController::class);
        Route::get('/load-contacts', [ContactController::class, 'load'])->name('admin.contacts.load');
        Route::post('/import-contact', [ContactController::class, 'import'])->name('admin.contact.import');

        Route::resource('messages', MessageController::class);
        Route::get('/load-messages', [MessageController::class, 'load'])->name('admin.messages.load');

        Route::get('/logout', [AuthController::class, 'logout'])->name('admin.logout');
    });
