<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Show the form for editing the profile.
     *
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        return view('profile.edit', [
            'user' => Auth::user()
        ]);
    }

    /**
     * Update the user's profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,'.auth()->id(),
        'profile_picture' => 'nullable|image|max:2048',
        // ... other validation rules
    ]);

    $user = auth()->user();

    // Handle profile picture update separately
    if ($request->hasFile('profile_picture')) {
        // Delete old picture if exists
        if ($user->profile_picture) {
            Storage::disk('public')->delete($user->profile_picture);
        }
        
        $path = $request->file('profile_picture')->store('profile-pictures', 'public');
        $user->profile_picture = $path;
    }

    // Update other fields safely
    $user->name = $request->name;
    $user->email = $request->email;
    // ... set other fields similarly
    
    $user->save();

    return back()->with('success', 'Profile updated!');
}
}