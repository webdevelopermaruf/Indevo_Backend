<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailVerificationCode extends Model
{
    public $table = 'email_verification_codes';

    public $fillable = ['email', 'code', 'expires_at'];
}
