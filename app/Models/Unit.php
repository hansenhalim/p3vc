<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
  use SoftDeletes;

  public function customer()
  {
    return $this->belongsTo(Customer::class);
  }

  public function cluster()
  {
    return $this->belongsTo(Cluster::class);
  }

  public function transactions()
  {
    return $this->hasMany(Transaction::class);
  }
}
