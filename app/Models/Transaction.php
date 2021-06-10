<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Transaction extends Model
{
  use SoftDeletes;

  protected $guarded = [];

  public function unit()
  {
    return $this->belongsTo(Unit::class);
  }

  public function payments()
  {
    return $this->belongsToMany(Payment::class)->withPivot('amount');
  }

  public static function getTotals($date)
  {
    return DB::table('payment_transaction AS pt')
      ->join('payments AS p', 'pt.payment_id', '=', 'p.id')
      ->join('transactions AS t', 't.id', '=', 'pt.transaction_id')
      ->whereBetween('t.created_at', [$date->from, $date->to])
      ->whereNull('t.deleted_at')
      ->select(DB::raw('SUM(amount) as total, p.id'))
      ->orderBy('payment_id')
      ->groupBy('p.id')
      ->get();
  }
}
