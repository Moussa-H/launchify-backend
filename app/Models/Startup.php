<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Startup extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id','image', 'company_name', 'description', 'founder', 'industry', 
        'founding_year', 'country', 'city', 'key_challenges', 'goals',
        'business_type', 'company_stage', 'employees_count', 'phone_number',
        'email_address', 'website_url', 'currently_raising_type', 
        'currently_raising_size'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sectors()
    {
        return $this->belongsToMany(Sector::class, 'startup_sectors');
    }

    public function investmentSources()
    {
        return $this->hasMany(StartupInvestmentSource::class);
    }

    public function teamMembers()
    {
        return $this->hasMany(TeamMember::class);
    }
     public function strategies()
    {
        return $this->hasMany(Strategy::class);
    }
}
