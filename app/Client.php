<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'address',
        'city',
        'postcode',
    ];

    protected $appends = [
        'url',
    ];

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class)->latest('start');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getBookingsCountAttribute()
    {
        return $this->bookings->count();
    }

    public function getUrlAttribute()
    {
        return "/clients/" . $this->id;
    }
}
