<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Strategy extends Model
{
    use HasFactory;

    protected $fillable = ['startup_id', 'title', 'description', 'actionable_steps', 'potential_challenges'];

    public function startup()
    {
        return $this->belongsTo(Startup::class);
    }
}
