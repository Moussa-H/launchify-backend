<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    use HasFactory;

    protected $fillable = ['startup_id', 'fullname', 'position', 'salary'];

    // A team member belongs to a startup
    public function startup()
    {
        return $this->belongsTo(Startup::class);
    }
}
