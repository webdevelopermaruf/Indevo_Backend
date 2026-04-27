<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScheduledNotification extends Model
{
    protected $fillable = ['user_id', 'title', 'body', 'data', 'send_at', 'sent_at'];

    protected $casts = [
        'data' => 'array',
        'send_at' => 'datetime',
        'sent_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
