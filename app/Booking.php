<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'client_id',
        'start',
        'end',
        'notes',
    ];

    protected $dates = [
        'start',
        'end',
    ];

    public function getStartAttribute(): string
    {
        return Carbon::parse($this->attributes['start'])->translatedFormat('l d F Y, H:i');
    }
    public function getEndAttribute(): string
    {
        return Carbon::parse($this->attributes['end'])->format('H:i');
    }
}
