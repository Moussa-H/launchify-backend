<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mentor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'full_name',
        'industry',
        'expertise',
        'description',
        'phone_number',
        'location',
        'image_url',
    ];

    /**
     * Get the user that owns the mentor.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
