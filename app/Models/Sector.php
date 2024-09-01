<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sector extends Model
{
    use HasFactory;

   public $timestamps = false;
     protected $fillable = ['id', 'name'];

    public function startups()
    {
        return $this->belongsToMany(Startup::class, 'startup_sectors');
    }
}
