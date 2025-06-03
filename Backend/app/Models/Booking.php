<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;
use App\Models\Tour;

class Booking extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'tour_id',
        'status',
        'number_of_people',
        'booking_date'
    ];

    // Relationship: A booking belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tour()
    {
        return $this->belongsTo(Tour::class, 'tour_id', 'tour_id');
    }
}
