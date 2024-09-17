<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Investor extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'investors';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'description',
        'email',
        'phone_number',
        'investment_source',
        'linkedin_url'
    ];

    /**
     * Get the user that owns the investor profile.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
   public function investments()
    {
        return $this->hasMany(Investment::class);
    }
   
}
