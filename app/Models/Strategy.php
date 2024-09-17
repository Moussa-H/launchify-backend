<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Strategy extends Model
{
    use HasFactory;

    // Allow mass assignment for these fields
    protected $fillable = [
        'startup_id',
        'strategy_1_name',
        'strategy_1_description',
        'strategy_1_status',
        'strategy_2_name',
        'strategy_2_description',
        'strategy_2_status',
        'strategy_3_name',
        'strategy_3_description',
        'strategy_3_status',
        'strategy_4_name',
        'strategy_4_description',
        'strategy_4_status',
        'strategy_5_name',
        'strategy_5_description',
        'strategy_5_status',
    ];

    // Define the relationship with the Startup model
    public function startup()
    {
        return $this->belongsTo(Startup::class);
    }
}
