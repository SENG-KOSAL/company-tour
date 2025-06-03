<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\Relations\HasMany;      
// use App\Models\Booking;
class Tour extends Model
{
    use HasFactory;

    protected $table = 'tours'; // Ensure it matches your database table

    protected $primaryKey = 'tour_id'; // Custom primary key

    protected $fillable = [
        'title',
        'description',
        'destination',
        'duration_days',
        'price_per_person',
        'available_slots',
        'image_url'
    ];
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'tour_id','tour_id');
    }
}
