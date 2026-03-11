<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Goal extends Model
{
    protected $fillable = [
        'description',
        'category',
        'deadline_date',
        'deadline_time',
        'note',
        'is_completed',
        'completion_date',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope('user_goal', function ($builder) {
            $builder->where('user_id', auth()->id());
        });
    }

    public function toResponseArray(): array
    {
        return [
            'id'             => $this->id,
            'description'    => $this->description,
            'category'       => $this->category,
            'deadline_date' => $this->deadline_date,
            'deadline_time' => $this->deadline_time,
            'completion_date'   => $this->completion_date,
            'note'          => $this->note,
            'created_at'     => $this->created_at,
        ];
    }
}
