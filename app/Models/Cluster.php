<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Cluster extends Model
{
  use SoftDeletes;

  protected $fillable = [];

  public function units()
  {
    return $this->hasMany(Unit::class);
  }

  public function prices()
  {
    return $this->hasMany(Price::class);
  }

  public function user()
  {
    return $this->belongsTo(User::class);
  }
}
