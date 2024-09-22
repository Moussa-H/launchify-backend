<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'startup_id',
        'mentor_id',
        'sender_type',
        'message',
    ];

    /**
     * Get the startup that owns the message.
     */
    public function startup()
    {
        return $this->belongsTo(Startup::class);
    }

    /**
     * Get the mentor that owns the message.
     */
    public function mentor()
    {
        return $this->belongsTo(Mentor::class);
    }
}
