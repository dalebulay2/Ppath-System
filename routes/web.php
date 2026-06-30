<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ActivityController;
use Illuminate\Support\Facades\Artisan;
Route::get('/', function () {
    return redirect('/login');
});
/*
|--------------------------------------------------------------------------
| LOGIN PAGE
|--------------------------------------------------------------------------
*/




Route::post('/activities', [ActivityController::class, 'store'])
    ->middleware('admin.auth');
Route::get('/login', function () {
    return view('auth');
})->name('login');

/*
|--------------------------------------------------------------------------
| AUTHENTICATION (LOGIN / SIGNUP)
|--------------------------------------------------------------------------
*/
Route::post('/auth', [AuthController::class, 'submit'])
    ->name('auth.submit');

/*
|--------------------------------------------------------------------------
| DASHBOARD (SECURED ROUTE)
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {

    $attendees = DB::table('attendees')
        ->orderBy('name', 'asc')
        ->get();

    $activities = DB::table('activities')
        ->orderBy('id', 'desc')
        ->get();

    return view('dashboard', compact('attendees', 'activities'));

})->middleware('admin.auth');
Route::get('/activities/delete/{id}', function ($id) {

    DB::table('activities')
        ->where('id', $id)
        ->delete();

    return redirect('/dashboard');

})->middleware('admin.auth');

/*
|--------------------------------------------------------------------------
| SUPER ADMIN: ADMIN APPROVAL PAGE (STEP 4F)
|--------------------------------------------------------------------------
*/
Route::get('/admin-requests', function () {

    $pendingAdmins = DB::table('admin')
        ->where('status', 'pending')
        ->get();

    return view('admin_requests', compact('pendingAdmins'));

})->middleware(['admin.auth', 'super.admin']);

/*
|--------------------------------------------------------------------------
| LOGOUT (SECURE SESSION DESTROY)
|--------------------------------------------------------------------------
*/
Route::get('/logout', function (Request $request) {

    // 🔴 GET USER ID BEFORE DESTROYING SESSION
    $id = session('userId');

    // 🟢 REMOVE ONLINE STATUS
    \Illuminate\Support\Facades\Cache::forget('admin-online-' . $id);

    // 🔴 CLEAR SESSION
    $request->session()->flush();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('login');

})->name('logout');

/*Route::get('/superadmin-dashboard', function () {

    if (!session()->has('userId') || session('role') !== 'super_admin') {
        return redirect('/login');
    }

    return view('superadmin_dashboard');

})->middleware(['web', \App\Http\Middleware\UpdateLastSeen::class]);

*/
Route::get('/superadmin-dashboard', function () {

    if (!session()->has('userId') || session('role') !== 'super_admin') {
        return redirect('/login');
    }

    // pending admins
    $pendingAdmins = DB::table('admin')
        ->where('status', 'pending')
        ->get();

    // approved admins
    $approvedAdmins = DB::table('admin')
        ->where('status', 'active')
        ->get();

    return view('superadmin_dashboard', compact('pendingAdmins', 'approvedAdmins'));

})->middleware('admin.auth');
Route::get('/admin-approve/{id}', function ($id) {

    DB::table('admin')
        ->where('id', $id)
        ->update(['status' => 'active']);

    return redirect()->back()->with('success', 'Admin approved.');

})->middleware('admin.auth');


Route::get('/admin-reject/{id}', function ($id) {

    DB::table('admin')
        ->where('id', $id)
        ->delete();

    return redirect()->back()->with('error', 'Admin rejected and deleted.');

})->middleware('admin.auth');
Route::get('/make-superadmin/{id}', function ($id) {

    DB::table('admin')
        ->where('id', $id)
        ->update(['role' => 'super_admin']);

    return redirect()->back()->with('success', 'User promoted to Super Admin');

})->middleware('admin.auth');


Route::get('/delete-admin/{id}', function ($id) {

    DB::table('admin')
        ->where('id', $id)
        ->delete();

    return redirect()->back();

})->middleware(['admin.auth', 'super.admin']);
Route::post('/admin/reset-password/{id}', [AuthController::class, 'resetPassword']);




Route::get('/scanner', function () {

    $activities = DB::table('activities')
                    ->latest()
                    ->get();

    return view('scanner', compact('activities'));

});



Route::post('/scanner/save', function (Request $request) {

    try {

        DB::table('attendees')->insert([

            'name' => $request->name,
            'gender' => $request->gender,
            'age' => 0,
            'category' => 'N/A',
            'activity_id' => $request->activity_id

        ]);

        return response()->json([
            'success' => true
        ]);

    } catch (\Exception $e) {

        return response()->json([

            'success' => false,
            'message' => $e->getMessage()

        ]);
Route::middleware(['auth'])->group(function () {

    Route::post('/scanner/token', function () {
        $token = \Illuminate\Support\Str::random(40);

        session(['scanner_token' => $token]);

        return response()->json([
            'token' => $token
        ]);
    });

    Route::get('/scanner', [ScannerController::class, 'index']);
    Route::post('/scanner/save', [ScannerController::class, 'save']);
});
    }

});

