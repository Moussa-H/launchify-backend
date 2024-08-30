<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    use HasFactory;

    protected $fillable = ['startup_id', 'fullname', 'job_title', 'salary'];

    public function startup()
    {
        return $this->belongsTo(Startup::class);
    }
}
