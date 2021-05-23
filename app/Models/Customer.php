<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'idlink', 'phone_number'];

    public function unit()
    {
        return $this->hasOne(Unit::class);
    }
}
