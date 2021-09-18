<?php

namespace App\Models;

use App\Scopes\ApprovedScope;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Cluster extends Model
{
  use SoftDeletes;

  protected $fillable = [];

  protected static function booted()
  {
    static::addGlobalScope(new ApprovedScope);
  }

  public function units()
  {
    return $this->hasMany(Unit::class);
  }

  public function user()
  {
    return $this->belongsTo(User::class, 'updated_by');
  }
}
