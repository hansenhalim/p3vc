<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'phone_number', 'approved_at', 'approved_by', 'updated_by'];

    public function units()
    {
        return $this->hasMany(Unit::class);
    }
}
