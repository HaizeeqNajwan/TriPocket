<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PreferenceController;
use App\Http\Controllers\ItineraryController;
use App\Http\Controllers\TripController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use App\Mail\TestEmail;





Route::post('/register', function (Request $request) {
    // 1. Validate input
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|confirmed|min:8',
    ]);

    // 2. Create and login user
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
    ]);
    Auth::login($user);

    // 3. Redirect to preferences page
    return redirect()->route('login'); // Direct URL
    // OR if you prefer named routes:
    // return redirect()->route('preferences');
})->name('register');

// Protected routes group


// Test Email Route
Route::get('/test-email', function () {
    Mail::to('abang.haizeeq@gmail.com')->send(new TestEmail());
    return 'Email sent!';
});

// Landing Page (Homepage)
Route::get('/', function () {
    return view('homepage');
})->name('homepage');

// Main Index Page


// Authenticated Routes Group
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [ItineraryController::class, 'showDashboard'])->name('dashboard');
    Route::get('/generate-itinerary', [ItineraryController::class, 'generate'])->name('itinerary.generate');

    // Preferences
    Route::prefix('preferences')->group(function () {
        Route::get('/select', [PreferenceController::class, 'select'])->name('preferences.select');
        Route::post('/store', [PreferenceController::class, 'store'])->name('preferences.store');
        Route::get('/preferences/edit-form', [PreferenceController::class, 'edit'])->name('preferences.edit');


    });

    // Trips
    Route::post('/itinerary/save', [ItineraryController::class, 'save'])->name('itinerary.save');
    Route::post('/itinerary/customize', [ItineraryController::class, 'customize'])->name('itinerary.customize');
    Route::get('/itinerary/customize/edit', [ItineraryController::class, 'edit'])->name('itinerary.edit');
    Route::post('/itinerary/savefinal', [ItineraryController::class, 'storeFinal'])->name('itinerary.saveFinal');
    Route::get('/itineraries', [ItineraryController::class, 'index'])->name('itineraries.index');
    
    // Profile
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/itineraries/view/{id}', [ItineraryController::class, 'show'])->name('itinerary.view');
    Route::delete('/saved-itinerary/delete/{id}', [ItineraryController::class, 'destroy'])->name('saved-itinerary.delete');
    Route::get('/saved-itinerary/pdf/{id}', [ItineraryController::class, 'downloadPdf'])->name('saved-itinerary.pdf');

});







// Include Laravel Auth Routes
require __DIR__.'/auth.php';
