<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Cluster extends Model
{
    use SoftDeletes;

    public function units()
    {
        return $this->hasMany(Unit::class);
    }

    public function prices()
    {
        return $this->hasMany(Price::class);
    }
}
