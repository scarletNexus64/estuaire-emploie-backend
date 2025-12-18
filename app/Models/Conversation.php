<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $fillable = ['user_one', 'user_two', 'application_id'];
    public function messages()
    {
        return $this->hasMany(Message::class);
    }
    public function lastMessage()
    {
        return $this->hasOne(Message::class)->latestOfMany();
    }
    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function userOne()
    {
        return $this->belongsTo(User::class, 'user_one');
    }

    public function userTwo()
    {
        return $this->belongsTo(User::class, 'user_two');
    }
}