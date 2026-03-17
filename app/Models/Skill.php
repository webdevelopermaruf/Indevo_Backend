<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    protected $fillable = [
        'title',
        'achievement',
        'poster',
        'reward',
        'duration',
        'difficulty',
        'category',
    ];

    public function steps()
    {
        return $this->hasMany(SkillStep::class, 'skill_id', 'id');
    }

    public function toResponseArray(){
        return [
            'title' => $this->title,
            'achievement' => $this->achievement,
            'poster' => $this->poster,
            'duration' => $this->duration,
            'reward' => $this->reward,
            'difficulty' => $this->difficulty,
            'category' => $this->category,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'steps' => $this->steps
        ];
    }
}
