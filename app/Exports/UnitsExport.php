<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class UnitsExport implements FromQuery, WithHeadings, WithStrictNullComparison
{
  public function query()
  {
    return DB::table('unit_shadows')
      ->select(
        'customer_id',
        'name',
        'customer_name',
        'idlink',
        'area_sqm',
        'balance',
        'debt',
        'months_count',
        'months_total',
        'credit',
        'paid_months_count',
        'paid_months_total',
      )
      ->orderBy('id');
  }

  public function headings(): array
  {
    return [
      'CIF',
      'Blok',
      'Nama',
      'IdLink',
      'Luas (m2)',
      'Saldo',
      'Hutang',
      'Jml Bulan',
      'Tunggakan',
      'Iuran',
      'month_count',
      'month_total',
    ];
  }
}
