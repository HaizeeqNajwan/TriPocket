<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SavedItinerary extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title', 
        'location',
        'theme',
        'days'  // This will store the JSON/array data
    ];

    protected $casts = [
        'days' => 'array'  // Automatically casts to/from JSON
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Helper method to safely get days data
    public function getItineraryDays()
    {
        return is_array($this->days) ? $this->days : [];
    }
    
}