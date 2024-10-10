<?php

namespace App\Models;

use App\Models\Destination;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tour extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function destination(): BelongsTo
    {
        return $this->belongsTo(Destination::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function getTourImage()
    {
        if ($this->image && Storage::exists($this->image)) {
            return Storage::url($this->image);
        }

        return Storage::url('img/No_image_available.png');
    }
}
