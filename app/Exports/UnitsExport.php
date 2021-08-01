<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UnitsExport implements FromQuery, WithHeadings
{
  public function query()
  {
    return DB::table('unit_shadows')->orderBy('id');
  }

  public function headings(): array
  {
    return [
      '#',
      'CIF',
      'Blok',
      'Nama',
      'IdLink',
      'Luas (m2)',
      'Saldo',
      'Hutang',
      'Jml Bulan',
      'Tunggakan',
      'Iuran'
    ];
  }
}
