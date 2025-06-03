<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Event;


class Booking extends Model
{
    /** @use HasFactory<\Database\Factories\BookingFactory> */
    use HasFactory;

    protected $fillable = [
        'explorer_id',
        'event_id',
        'quantity',
        'total_price',
        'status',
    ];

    public function explorer()
    {
        return $this->belongsTo(User::class, 'explorer_id');
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
