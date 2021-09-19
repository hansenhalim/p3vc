<?php

namespace App\Models;

use App\Scopes\ApprovedScope;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
  use SoftDeletes;

  protected $fillable = [];

  protected static function booted()
  {
    static::addGlobalScope(new ApprovedScope);
  }

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

  public function user()
  {
    return $this->belongsTo(User::class, 'updated_by');
  }
}
