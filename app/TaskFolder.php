<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskFolder extends Model
{
    //
    protected $fillable = ['user_id', 'task_id', 'folder'];
    public $timestamps = false;
}
