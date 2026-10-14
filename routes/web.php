<?php

use App\Http\Controllers\ItineraryController;
use App\Http\Controllers\PreferenceController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Landing page
Route::view('/', 'homepage')->name('homepage');

// Authenticated routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [ItineraryController::class, 'showDashboard'])->name('dashboard');
    Route::get('/generate-itinerary', [ItineraryController::class, 'generate'])->name('itinerary.generate');

    // Preferences
    Route::prefix('preferences')->group(function () {
        Route::get('/select', [PreferenceController::class, 'select'])->name('preferences.select');
        Route::post('/store', [PreferenceController::class, 'store'])->name('preferences.store');
        Route::get('/edit-form', [PreferenceController::class, 'edit'])->name('preferences.edit');
    });

    // Itineraries
    Route::post('/itinerary/save', [ItineraryController::class, 'save'])->name('itinerary.save');
    Route::post('/itinerary/customize', [ItineraryController::class, 'customize'])->name('itinerary.customize');
    Route::get('/itinerary/customize/edit', [ItineraryController::class, 'edit'])->name('itinerary.edit');
    Route::post('/itinerary/savefinal', [ItineraryController::class, 'storeFinal'])->name('itinerary.saveFinal');
    Route::get('/itineraries', [ItineraryController::class, 'index'])->name('itineraries.index');
    Route::get('/itineraries/view/{id}', [ItineraryController::class, 'show'])->name('itinerary.view');
    Route::delete('/saved-itinerary/delete/{id}', [ItineraryController::class, 'destroy'])->name('saved-itinerary.delete');
    Route::get('/saved-itinerary/pdf/{id}', [ItineraryController::class, 'downloadPdf'])->name('saved-itinerary.pdf');

    // Profile
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
});

require __DIR__.'/auth.php';
