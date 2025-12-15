<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPresence extends Model
{

    protected $primaryKey = 'user_id';
    protected $fillable = ['user_id', 'online', 'last_seen'];
    public $timestamps = false;
}