<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class UnitsLinkajaExport implements FromQuery, WithHeadings, WithStrictNullComparison
{
  public function query()
  {
    return DB::table('unit_shadows')
      ->select(DB::raw('idlink,customer_name,DATE_FORMAT(DATE_SUB((SELECT value from configs where `key`="units_last_sync"),INTERVAL 1 MONTH),"%Y%m"),DATE_FORMAT(LAST_DAY((SELECT value from configs where `key`="units_last_sync")),"%Y%m%d"),months_total,2500,credit+2500'))
      ->orderBy('id');
  }

  public function headings(): array
  {
    return [
      'ID_PELANGGAN',
      'NAMA',
      'BULAN_TAGIHAN',
      'TANGGAL_JATUH_TEMPO',
      'NOMINAL_TAGIHAN',
      'ADMIN',
      'TOTAL'
    ];
  }
}
