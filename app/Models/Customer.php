<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'phone_number'];

    public function units()
    {
        return $this->hasMany(Unit::class);
    }
}
