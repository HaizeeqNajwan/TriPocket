<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PreferenceController extends Controller
{
    /**
     * Show the preferences selection interface.
     * If user already has preferences, it will be used to pre-check boxes.
     */
    public function select()
    {
        $user = Auth::user();
        $savedPreferences = $user->preferences ?? [];

        return view('preferences.select', [
            'savedPreferences' => $savedPreferences
        ]);
    }

    /**
     * Store or update the user's preferences.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'preferences' => 'sometimes|array|max:5', // Max 5 preferences
            'preferences.*' => 'string|max:255'
        ], [
            'preferences.max' => 'Please select no more than 5 preferences.'
        ]);

        $user = Auth::user();
        $user->preferences = $validated['preferences'] ?? [];
        $user->preferences_selected = true;
        $user->save();

        return redirect()->route('dashboard')
            ->with('success', 'Preferences updated successfully');
    }

    /**
     * Allow user to edit previously selected preferences.
     * Reuses the same view as select().
     */
    public function edit(Request $request)
{
    $savedPreferences = auth()->user()->preferences ?? [];
    $editMode = true;

    return view('preferences.select', compact('savedPreferences', 'editMode'));
}
    
}
