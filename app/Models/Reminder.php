<?php

namespace App\Models;

use App\Enums\ReminderCategories;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reminder extends Model
{
    protected $fillable = [
        'user_id',
        'description',
        'priority',
        'category',
        'due_time',
        'due_date',
        'recurrence',
        'place',
        'note',
        'is_completed',
        'completed_at',
    ];

    protected $hidden = [
        'user_id',
    ];

    protected $casts = [
        'category' => ReminderCategories::class,
    ];


    protected static function booted(): void
    {
        static::addGlobalScope('user_reminder', function ($builder) {
            $builder->where('user_id', auth()->id());
        });
    }

    public function goal(): belongsTo
    {
        return $this->belongsTo(Goal::class,  'goal_id', 'id');
    }

    public function toResponseArray(): array
    {
        return [
            'id'           => $this->id,
            'description'  => $this->description,
            'priority'     => $this->priority,
            'category'     => $this->category,
            'due_time'     => $this->due_time,
            'due_date'     => $this->due_date,
            'recurrence'   => $this->recurrence,
            'place'        => $this->place,
            'note'         => $this->note,
            'is_completed' => $this->is_completed,
            'completed_at' => $this->completed_at?->format('Y-m-d H:i'),
            'created_at'   => $this->created_at->format('Y-m-d H:i'),
        ];
    }
}
