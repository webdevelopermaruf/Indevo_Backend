<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SkillStep extends Model
{

    public function status(){
        return $this->hasOne(UserSkill::class, 'skill_steps_id', 'id');
    }
}
