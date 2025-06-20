<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_picture',
        'preferences' // For itinerary preferences
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'preferences' => 'array', // Fixed typo - now matches $fillable
        ];
    }

    /**
     * Get the URL for the user's profile photo.
     */
    public function getProfilePhotoUrlAttribute()
    {
        if (!$this->profile_picture) {
            return asset('images/default-profile.jpg');
        }

        if (Storage::disk('public')->exists($this->profile_picture)) {
            return asset('storage/' . $this->profile_picture);
        }

        return asset('images/default-profile.jpg');
    }

    /**
     * Relationship to saved itineraries.
     */
    public function savedItineraries(): HasMany
    {
        return $this->hasMany(SavedItinerary::class);
    }

    /**
     * Get the user's travel preferences with proper fallback.
     */
    public function getTravelPreferences(): array
    {
        // Ensure we always return an array
        return is_array($this->preferences) ? $this->preferences : [];
    }
}