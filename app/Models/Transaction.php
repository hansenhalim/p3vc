<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
  use SoftDeletes;

  protected $fillable = ['period', 'approved_at', 'approved_by', 'updated_by'];

  public function unit()
  {
    return $this->belongsTo(Unit::class);
  }

  public function payments()
  {
    return $this->belongsToMany(Payment::class)->withPivot('amount');
  }
}
