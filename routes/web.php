<?php
    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\AuthController;
    use App\Http\Controllers\DashboardController;
    use App\Http\Controllers\UserController;
    use App\Http\Controllers\PlanController;
    use App\Http\Controllers\ContactController;
    use App\Http\Controllers\MessageController;
    use App\Http\Controllers\PaymentController;
    use App\Http\Controllers\SubscriptionController;

    Route::get('/', [AuthController::class, 'index'])->name('login');
    Route::post('/check-login', [AuthController::class, 'checkLogin'])->name('check.login');
    Route::post('/pay', [PaymentController::class, 'createOrder'])->name('create.order');
    Route::post('/payment/verify', [PaymentController::class, 'verify'])->name('payment.verify');
    Route::get('/get-plan-amount/{id}', function ($id) {
        $plan = \App\Models\Plan::findOrFail($id);
        return response()->json(['amount' => $plan->amount,'whatsapp' => $plan->whatsapp,'duration' => $plan->duration]);
    });

    Route::middleware(['auth'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
        Route::get('/general-settings', [DashboardController::class, 'general_settings'])->name('admin.general-settings');
        Route::get('/download-sample', [ContactController::class, 'downloadSample'])->name('admin.download.sample');

        Route::resource('users', UserController::class);
        Route::get('/load-users', [UserController::class, 'load'])->name('admin.users.load');

        Route::resource('plans', PlanController::class);
        Route::get('/load-plans', [PlanController::class, 'load'])->name('admin.plans.load');

        Route::resource('subscriptions', SubscriptionController::class);
        Route::get('/load-subscriptions', [SubscriptionController::class, 'load'])->name('admin.subscriptions.load');
        Route::get('/my-subscriptions', [SubscriptionController::class, 'subscriptions'])->name('admin.my.subscription');
        Route::get('/load-mysubscriptions', [SubscriptionController::class, 'my_subscription'])->name('admin.mysubscriptions.load');

        Route::resource('contacts', ContactController::class);
        Route::get('/load-contacts', [ContactController::class, 'load'])->name('admin.contacts.load');
        Route::post('/import-contact', [ContactController::class, 'import'])->name('admin.contact.import');

        Route::resource('messages', MessageController::class);
        Route::get('/load-messages', [MessageController::class, 'load'])->name('admin.messages.load');
        Route::post('/message-send', [MessageController::class, 'send'])->name('admin.message.send');

        Route::get('/logout', [AuthController::class, 'logout'])->name('admin.logout');
    });
