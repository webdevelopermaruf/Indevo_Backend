<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSkill extends Model
{
    protected $hidden = [
        'created_at', 'updated_at', 'user_id', 'id', 'skill_steps_id'
    ];
}
