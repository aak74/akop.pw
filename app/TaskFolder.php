<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskFolder extends Model
{
    //
    protected $fillable = ['USER_ID', 'TASK_ID', 'FOLDER'];
    public $timestamps = false;
}
