<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    use HasFactory;

    protected $fillable = [
        'startup_id',
        'product_sales',
        'service_revenue',
        'subscription_fees',
        'investment_income',
        'year',
        'month',
    ];

    public function startup()
    {
        return $this->belongsTo(Startup::class);
    }
}
