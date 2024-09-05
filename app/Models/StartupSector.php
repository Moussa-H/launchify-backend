<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StartupSector extends Model
{
     use HasFactory;

    public $timestamps = false;
    protected $fillable = ['startup_id', 'sector_id'];

    public function sector()
    {
        return $this->belongsTo(Sector::class, 'sector_id');
    }
}
