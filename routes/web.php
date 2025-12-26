<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\StudentDashboardController;
use App\Http\Controllers\Frontend\StudentRegisterController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\QuizController;
use App\Http\Controllers\Admin\Auth\AdminAuthenticatedSessionController;
use App\Http\Controllers\Frontend\StudentQuizController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\Admin\CategoryController;    

use App\Http\Controllers\Admin\AdminDashBoardController;

/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('frontend.home');

// Student Registration
Route::post('/student-register', [StudentRegisterController::class, 'store'])
    ->middleware('guest')
    ->name('student.register');

/*
|--------------------------------------------------------------------------
| Student Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::get('/course/{slug}', [App\Http\Controllers\Frontend\StudentCourseController::class, 'show'])->name('student.courses.show');

Route::middleware('auth')->group(function () {
    Route::get('/my-dashboard', [StudentDashboardController::class, 'index'])
        ->name('student.dashboard');

    // Student Course Routes
    Route::prefix('my-courses')->name('student.courses.')->group(function () {
        Route::get('/{slug}/classroom', [App\Http\Controllers\Frontend\StudentCourseController::class, 'classroom'])->name('classroom');
        Route::post('/{slug}/enroll', [App\Http\Controllers\Frontend\StudentCourseController::class, 'enroll'])->name('enroll');
        Route::post('/{slug}/finish', [App\Http\Controllers\Frontend\StudentCourseController::class, 'finish'])->name('finish');
    });

    // Student Quiz Routes
    Route::prefix('courses/{course}/quizzes')->name('student.quizzes.')->group(function () {
        Route::get('/', [StudentQuizController::class, 'index'])->name('index');
        Route::post('/{quiz}/start', [StudentQuizController::class, 'start'])->name('start');
        Route::get('/{quiz}/attempt/{attempt}', [StudentQuizController::class, 'take'])->name('take');
        Route::post('/{quiz}/attempt/{attempt}/answer', [StudentQuizController::class, 'submitAnswer'])->name('submit-answer');
        Route::post('/{quiz}/attempt/{attempt}/submit', [StudentQuizController::class, 'submit'])->name('submit');
        Route::get('/{quiz}/attempt/{attempt}/result', [StudentQuizController::class, 'result'])->name('result');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Cart Routes
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'store'])->name('cart.add');
    Route::delete('/cart/{id}', [CartController::class, 'destroy'])->name('cart.destroy');

    // Certificate Routes
    Route::get('/my-certificates', [App\Http\Controllers\Frontend\CertificateController::class, 'index'])->name('student.certificates.index');
    Route::get('/my-certificates/{id}', [App\Http\Controllers\Frontend\CertificateController::class, 'show'])->name('student.certificates.show');
    Route::get('/my-certificates/{id}/download', [App\Http\Controllers\Frontend\CertificateController::class, 'download'])->name('student.certificates.download');

    // Razorpay Checkout Routes
    Route::get('/razorpay/checkout', [App\Http\Controllers\RazorpayController::class, 'index'])->name('razorpay.index');
    Route::post('/razorpay/payment/store', [App\Http\Controllers\RazorpayController::class, 'store'])->name('razorpay.payment.store');
    Route::post('/razorpay/payment/dummy', [App\Http\Controllers\RazorpayController::class, 'storeDummy'])->name('razorpay.payment.dummy');
});

/*
|--------------------------------------------------------------------------
| Admin Redirect
|--------------------------------------------------------------------------
*/

// Direct /admin hit → Admin login
Route::get('/admin', function () {
    return redirect()->route('admin.login');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->group(function () {

    // Admin Auth Routes
    Route::get('login', [AdminAuthenticatedSessionController::class, 'create'])
        ->middleware('guest')
        ->name('login');

    Route::post('login', [AdminAuthenticatedSessionController::class, 'store'])
        ->middleware('guest')
        ->name('login.store');

    Route::post('logout', [AdminAuthenticatedSessionController::class, 'destroy'])
        ->middleware('auth')
        ->name('logout');

    // Protected Admin Panel
    Route::middleware(['admin'])->group(function () {

        Route::get('dashboard', [AdminDashBoardController::class, 'index'])->name('dashboard');

        // Role Management
        Route::get('roles/{role}/permissions', [RoleController::class, 'permissions'])
            ->name('roles.permissions');
        Route::post('roles/{role}/permissions', [RoleController::class, 'permissionsStore'])
            ->name('roles.permissions.store');
        Route::resource('roles', RoleController::class);

        // User Management
        Route::put('users/{id}/status', [UserController::class, 'updateStatus'])->name('users.updateStatus');
        Route::resource('users', UserController::class);
        // Category Management
        Route::resource('categories', CategoryController::class);
        // Course Management
        Route::resource('courses', CourseController::class);
        Route::post('courses/bulk', [CourseController::class, 'bulkAction'])
            ->name('courses.bulk');

        // Quiz Management
        Route::prefix('courses/{course}/quizzes')->name('courses.quizzes.')->group(function () {
            Route::get('/', [QuizController::class, 'index'])->name('index');
            Route::get('/create', [QuizController::class, 'create'])->name('create');
            Route::post('/', [QuizController::class, 'store'])->name('store');
            Route::get('/{quiz}/edit', [QuizController::class, 'edit'])->name('edit');
            Route::put('/{quiz}', [QuizController::class, 'update'])->name('update');
            Route::delete('/{quiz}', [QuizController::class, 'destroy'])->name('destroy');
            Route::get('/{quiz}/results', [QuizController::class, 'showResults'])->name('results');
        });

        // Payment History
        Route::get('payments', [App\Http\Controllers\Admin\AdminPaymentController::class, 'index'])->name('payments.index');
    });
});

/*
|--------------------------------------------------------------------------
| Breeze Auth Routes
|--------------------------------------------------------------------------
*/


require __DIR__ . '/auth.php';
