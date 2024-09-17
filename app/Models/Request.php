<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    use HasFactory;

    protected $fillable = ['startup_id', 'mentor_id', 'status'];

    // Define relationships
    public function startup()
    {
        return $this->belongsTo(Startup::class);
    }

    public function mentor()
    {
        return $this->belongsTo(Mentor::class);
    }
}