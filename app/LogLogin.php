<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LogLogin extends Model
{
    protected $fillable = ['portal_id', 'user_id', 'app_id'];
    protected $table = 'log_login';
}
