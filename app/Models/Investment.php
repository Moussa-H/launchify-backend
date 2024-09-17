<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Investment extends Model
{
    use HasFactory;

    protected $fillable = [
        'investor_id', 'startup_id', 'amount'
    ];

    public function investor()
    {
        return $this->belongsTo(Investor::class);
    }

    public function startup()
    {
        return $this->belongsTo(Startup::class);
    }
}
