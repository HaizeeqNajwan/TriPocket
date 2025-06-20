<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PreferenceController extends Controller
{
    /**
     * Show the preferences selection interface.
     */
   /**
 * Show the preferences selection interface.
 */
public function select()
{
    $user = Auth::user();
    
    // Since preferences is cast to array in User model, we can use it directly
    $savedPreferences = $user->preferences ?? [];

    return view('preferences.select', [
        'savedPreferences' => $savedPreferences
    ]);
}

    /**
     * Store the user's preferences.
     */
    public function store(Request $request)
{
    $validated = $request->validate([
        'preferences' => 'sometimes|array',
        'preferences.*' => 'string|max:255'
    ]);

    $user = Auth::user();
    $user->preferences = $validated['preferences'] ?? [];
    $user->save();

    return redirect()->route('dashboard')
        ->with('success', 'Preferences updated successfully');
}
}
