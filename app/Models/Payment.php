<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{

  public function transactions()
  {
    return $this->belongsToMany(Transaction::class)->withPivot('amount');
  }
}
