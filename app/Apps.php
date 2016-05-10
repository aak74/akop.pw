<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Apps extends Model
{
    //
    protected $fillable = ['code', 'name', 'description'];
    // public $timestamps = false;
}
