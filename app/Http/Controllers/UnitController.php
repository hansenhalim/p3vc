<?php

namespace App\Http\Controllers;

use App\Exports\UnitsExport;
use App\Exports\UnitsLinkajaExport;
use Illuminate\Http\Request;
use App\Models\Unit;
use App\Models\Cluster;
use App\Models\Customer;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class UnitController extends Controller
{

  public function index(Request $request)
  {
    $search = $request->search;
    $sortBy = $request->sortBy ?? 'id';
    $sortDirection = $request->sortDirection ?? 'asc';
    $perPage = $request->page == 'all' ? 2000 : 10;

    $units = DB::table('unit_shadows')
      ->when($search, fn ($query) => $query->where('name', $search))
      ->orderBy($sortBy, $sortDirection)
      ->paginate($perPage);

    $totals = DB::table('unit_shadows')
      ->first(DB::raw('
        SUM(balance) as balance,
        SUM(debt) as debt,
        SUM(months_count) as months_count,
        SUM(months_total) as months_total,
        SUM(credit) as credit,
        SUM(paid_months_count) as paid_months_count,
        SUM(paid_months_total) as paid_months_total
      '));

    $unitsLastSync = Carbon::parse(DB::table('configs')
      ->where('key', 'units_last_sync')
      ->pluck('value')
      ->first());

    // echo json_encode($totals); exit;

    return view('unit.list', compact('units', 'totals', 'unitsLastSync'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    $latestCustomers = Customer::query()
      ->select('previous_id', DB::raw('MAX(id) AS id'))
      ->groupBy('previous_id')
      ->get();

    $customers = Customer::query()
      ->with('units:customer_id,name')
      ->whereIn('id', $latestCustomers->pluck('id'))
      ->oldest('name')
      ->get(['id', 'previous_id', 'name']);

    $latestClusters = Cluster::query()
      ->select('previous_id', DB::raw('MAX(id) AS id'))
      ->groupBy('previous_id')
      ->get();

    $clusters = Cluster::query()
      ->whereIn('id', $latestClusters->pluck('id'))
      ->oldest('previous_id')
      ->get(['id', 'previous_id', 'name', 'cost', 'per']);

    // echo json_encode($customers); exit;

    return view('unit.create', compact('clusters', 'customers'));
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request, Unit $unit)
  {
    $request->validate([
      'name'          => 'required',
      'idlink'        => 'required',
      'area_sqm'      => 'required|numeric',
      'customer_id'   => 'required|exists:customers,id',
      'cluster_id'    => 'required|exists:clusters,id',
    ]);

    $unit->name = $request->name;
    $unit->idlink = $request->idlink;
    $unit->area_sqm = $request->area_sqm;
    $unit->customer_id = $request->customer_id;
    $unit->cluster_id = $request->cluster_id;
    $unit->updated_by = $request->user()->id;

    $unit->save();

    $unit->previous_id = $unit->id;

    $unit->save();

    $request->session()->flash('status', 'Successfully created ' . $unit->name . '. Please wait for appoval.');

    return redirect()->route('units.index');
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show(Unit $unit)
  {
    // echo json_encode($unit); exit;
    return view('unit.show', compact('unit'));
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit(Unit $unit)
  {
    $latestCustomers = Customer::query()
      ->select('previous_id', DB::raw('MAX(id) AS id'))
      ->groupBy('previous_id')
      ->get();

    $customers = Customer::query()
      ->with('units:customer_id,name')
      ->whereIn('id', $latestCustomers->pluck('id'))
      ->oldest('name')
      ->get(['id', 'previous_id', 'name']);

    $latestClusters = Cluster::query()
      ->select('previous_id', DB::raw('MAX(id) AS id'))
      ->groupBy('previous_id')
      ->get();

    $clusters = Cluster::query()
      ->whereIn('id', $latestClusters->pluck('id'))
      ->oldest('previous_id')
      ->get(['id', 'previous_id', 'name', 'cost', 'per']);

    return view('unit.edit', compact('unit', 'clusters', 'customers'));
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, Unit $unit)
  {
    $request->validate([
      'name'          => 'required',
      'idlink'        => 'required',
      'area_sqm'      => 'required|numeric',
      'customer_id'   => 'required|exists:customers,id',
      'cluster_id'    => 'required|exists:clusters,id',
    ]);

    $unit->name = $request->name;
    $unit->idlink = $request->idlink;
    $unit->area_sqm = $request->area_sqm;
    $unit->customer_id = $request->customer_id;
    $unit->cluster_id = $request->cluster_id;

    if ($unit->isClean()) return redirect()->route('units.index');

    $unit->approved_at = null;
    $unit->approved_by = null;
    $unit->updated_by = $request->user()->id;

    $created_at = $unit->created_at;

    $unit = $unit->replicate();
    $unit->created_at = $created_at;
    $unit->save();

    $request->session()->flash('status', 'Successfully updated ' . $unit->name . '. Please wait for appoval.');

    return redirect()->route('units.index');
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy(Request $request, Unit $unit)
  {
    $unit->approved_at = null;
    $unit->approved_by = null;
    $unit->updated_by = $request->user()->id;
    $unit = $unit->replicate();
    $unit->save();
    $unit->delete();

    $request->session()->flash('status', 'Successfully deleted ' . $unit->name . '. Please wait for appoval.');

    return redirect()->route('units.index');
  }

  public function debt(Unit $unit)
  {
    $unit = Unit::query()
      ->with([
        'cluster:id,name,cost,per',
        'customer:id,previous_id,name',
        'transactions.payments:id',
        'transactions' => function ($query) {
          $query->withoutGlobalScopes([ApprovedScope::class]);
        }
      ])
      ->find($unit->id);

    $transactions = $unit->transactions;

    $unit['credit'] = 0;

    if ($unit->cluster) {
      $unit['credit'] = $unit->cluster->cost * ($unit->cluster->per === 'mth' ?: $unit->area_sqm);
    }

    $unit['balance'] = 0;
    $unit['debt'] = 0;

    foreach ($transactions as $transaction) {
      foreach ($transaction->payments as $payment) {
        switch ($payment->id) {
          case 11:
            $unit['debt'] -= $payment->pivot->amount;
            break;
          case 8:
            $unit['debt'] += $payment->pivot->amount;
            break;
          case 3:
            $unit['balance'] += $payment->pivot->amount;
            break;
          case 10:
            $unit['balance'] -= $payment->pivot->amount;
            break;
        }
      }
    }

    $payments = Payment::get(['id', 'name'])->only([4, 5, 6, 7, 8, 9, 10]);

    // echo json_encode($unit); exit();

    return view('unit.debt', compact('unit', 'payments'));
  }

  public function sync()
  {
    $latestUnits = Unit::query()
      ->select('previous_id', DB::raw('MAX(id) AS id'))
      ->groupBy('previous_id')
      ->get();

    DB::table('unit_shadows')->truncate();

    Unit::query()
      ->select([
        'id',
        'previous_id',
        'customer_id',
        'cluster_id',
        'name',
        'area_sqm',
        'idlink',
        'created_at'
      ])
      ->with([
        'cluster:id,cost,per',
        'customer:id,previous_id,name',
        'transactions:id,unit_id,period',
        'transactions.payments:id'
      ])
      ->whereIn('id', $latestUnits->pluck('id'))
      ->oldest('previous_id')
      ->chunk(50, function ($units) {
        $this->calculateExtraFieldsAndCastShadow($units);
      });

    return redirect()->route('units.index');
  }

  private function calculateExtraFieldsAndCastShadow($units)
  {
    foreach ($units as $unit) {
      $transactions = $unit->transactions;

      $unit['customer_name'] = '';
      $unit['customer_id'] = 0;

      if ($unit->customer) {
        $unit['customer_name'] = $unit->customer->name;
        $unit['customer_id'] = $unit->customer->previous_id;
      }

      $unit['credit'] = 0;

      if ($unit->cluster) {
        $unit['credit'] = $unit->cluster->cost * ($unit->cluster->per === 'mth' ?: $unit->area_sqm);
      }

      $unit['balance'] = 0;
      $unit['debt'] = 0;

      foreach ($transactions as $transaction) {
        foreach ($transaction->payments as $payment) {
          switch ($payment->id) {
            case 11:
              $unit['debt'] -= $payment->pivot->amount;
              break;
            case 8:
              $unit['debt'] += $payment->pivot->amount;
              break;
            case 3:
              $unit['balance'] += $payment->pivot->amount;
              break;
            case 10:
              $unit['balance'] -= $payment->pivot->amount;
              break;
          }
        }
      }

      $startMonth = $unit->created_at->firstOfMonth();
      $endMonth = now()->firstOfMonth();
      $diffInMonths = $startMonth->diffInMonths($endMonth);

      $months = collect();

      for ($i = 0; $i < $diffInMonths; $i++) {
        $period = $unit->created_at->addMonths($i);

        if ($transactions->first()) {
          foreach ($transactions as $key => $transaction) {
            if (!$period->diffInMonths($transaction->period)) {
              $transactions->forget($key);
              continue 2;
            }
          }
        }

        // okay, this needs to be fixed in the future

        $months->push([
          'period' => $period,
          'credit' => $unit['credit'],
          'fine' => 2000 * ($diffInMonths - $i - 1)
        ]);
      }

      $unit['months_count'] = $months->count();
      $unit['months_total'] = $months->sum('credit') + $months->sum('fine');

      $unit['paid_months_count'] = 0;
      $unit['paid_months_total'] = 0;

      if ($transactions->first()) {
        foreach ($transactions as $key => $transaction) {
          $nullDate = Carbon::parse('1970-01-01');
          if ($nullDate->diffInMonths($transaction->period)) {
            $unit['paid_months_count'] += 1;
            $unit['paid_months_total'] += $unit['credit'];
          }
        }
      }

      $unitShadows[] = $unit->only([
        'id',
        'customer_id',
        'name',
        'customer_name',
        'idlink',
        'area_sqm',
        'balance',
        'debt',
        'months_count',
        'months_total',
        'paid_months_count',
        'paid_months_total',
        'credit'
      ]);
    }

    // echo json_encode($unit); exit;

    DB::table('configs')->upsert(['key' => 'units_last_sync', 'value' => now()], 'key');
    DB::table('unit_shadows')->upsert($unitShadows, 'id');
  }

  public function export($type)
  {
    $unitsLastSync = Carbon::parse(DB::table('configs')
      ->where('key', 'units_last_sync')
      ->pluck('value')
      ->first());

    switch ($type) {
      case 'linkaja':
        return Excel::download(new UnitsLinkajaExport, 'IKK Villa Citra_' . $unitsLastSync->setTimezone('Asia/Jakarta')->formatLocalized('%d %B %Y %H%M') . '.xlsx');
        break;

      case 'recapitulation':
        return Excel::download(new UnitsLinkajaExport, 'rekapitulasi.xlsx');
        break;

      default:
        return Excel::download(new UnitsExport, 'Report Unit_' . $unitsLastSync->setTimezone('Asia/Jakarta')->formatLocalized('%d %B %Y %H%M') . '.xlsx');
        break;
    }
  }
}
