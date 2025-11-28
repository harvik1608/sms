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
    use App\Http\Controllers\StateController;
    use App\Http\Controllers\CityController;

    Route::get('/', [AuthController::class, 'index'])->name('login');
    Route::post('/check-login', [AuthController::class, 'checkLogin'])->name('check.login');
    Route::get('/forget-password', [AuthController::class, 'forgetPassword'])->name('forget.password');
    Route::post('/submit-forget-password', [AuthController::class, 'submitForgetPassword'])->name('check.forget.password');
    Route::get('/reset-password', [AuthController::class, 'resetPassword'])->name('reset.password');
    Route::post('/submit-reset-password', [AuthController::class, 'submitResetPassword'])->name('submit.reset.password');
    Route::post('/pay', [PaymentController::class, 'createOrder'])->name('create.order');
    Route::post('/payment/verify', [PaymentController::class, 'verify'])->name('payment.verify');
    Route::get('/get-plan-amount/{id}', function ($id) {
        $plan = \App\Models\Plan::findOrFail($id);
        return response()->json(['amount' => $plan->amount,'whatsapp' => $plan->whatsapp,'duration' => $plan->duration]);
    });
    Route::get('/get-cities', function (\Illuminate\Http\Request $request) {
        $id = $request->stateId;
        $cities = \App\Models\City::select("id","name")->where("state_id",$id)->orderBy("name","asc")->get();
        return response()->json(['cities' => $cities]);
    });

    Route::middleware(['auth'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
        Route::get('/general-settings', [DashboardController::class, 'general_settings'])->name('admin.general-settings');
        Route::get('/download-sample', [ContactController::class, 'downloadSample'])->name('admin.download.sample');

        Route::resource('states', StateController::class);
        Route::get('/load-states', [StateController::class, 'load'])->name('admin.states.load');

        Route::resource('cities', CityController::class);
        Route::get('/load-cities', [CityController::class, 'load'])->name('admin.cities.load');

        Route::resource('users', UserController::class);
        Route::get('/load-users', [UserController::class, 'load'])->name('admin.users.load');
        Route::get('/approve/{id}', [UserController::class, 'approve'])->name('user.approve');

        Route::resource('plans', PlanController::class);
        Route::get('/load-plans', [PlanController::class, 'load'])->name('admin.plans.load');

        Route::resource('subscriptions', SubscriptionController::class);
        Route::get('/load-subscriptions', [SubscriptionController::class, 'load'])->name('admin.subscriptions.load');
        Route::get('/my-subscriptions', [SubscriptionController::class, 'subscriptions'])->name('admin.my.subscription');
        Route::get('/load-mysubscriptions', [SubscriptionController::class, 'my_subscription'])->name('admin.mysubscriptions.load');
        Route::get('/invoice/{id}', [SubscriptionController::class, 'download'])->name('invoice.download');

        Route::resource('contacts', ContactController::class);
        Route::get('/load-contacts', [ContactController::class, 'load'])->name('admin.contacts.load');
        Route::post('/import-contact', [ContactController::class, 'import'])->name('admin.contact.import');

        Route::resource('messages', MessageController::class);
        Route::get('/load-messages', [MessageController::class, 'load'])->name('admin.messages.load');
        Route::post('/message-send', [MessageController::class, 'send'])->name('admin.message.send');

        Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
        Route::post('/submit-profile', [AuthController::class, 'submitProfile'])->name('submit.profile');
        Route::get('/change-password', [AuthController::class, 'changePassword'])->name('change.password');
        Route::post('/submit-change-password', [AuthController::class, 'submitChangePassword'])->name('submit.change.password');
        Route::get('/logout', [AuthController::class, 'logout'])->name('admin.logout');
    });
