<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StartupInvestmentSource extends Model
{
    use HasFactory;


      protected $fillable = ['startup_id', 'investment_source'];

    public function startup()
    {
        return $this->belongsTo(Startup::class);
    }
}
