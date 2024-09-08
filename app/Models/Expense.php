<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'startup_id', 
        'office_rent', 
        'marketing', 
        'legal_accounting', 
        'maintenance', 
        'software_licenses', 
        'office_supplies', 
        'miscellaneous'
    ];

    public function startup()
    {
        return $this->belongsTo(Startup::class);
    }
}
