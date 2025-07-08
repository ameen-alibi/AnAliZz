<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Website extends Model
{
    protected $guarded = [];

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function metrics()
    {
        return $this->hasMany(Metric::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
