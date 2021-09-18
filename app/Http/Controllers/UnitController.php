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
        SUM(credit) as credit
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
    $clusters = Cluster::get(['id', 'name', 'cost', 'per']);

    $latestCustomers = Customer::query()
      ->select('previous_id', DB::raw('MAX(id) AS id'))
      ->groupBy('previous_id')
      ->get();

    $customers = Customer::query()
      ->with('units:customer_id,name')
      ->whereIn('id', $latestCustomers->pluck('id'))
      ->oldest('previous_id')
      ->get(['id', 'previous_id', 'name']);

    // echo json_encode($customers); exit;

    return view('unit.create', compact('clusters', 'customers'));
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $unit = $request->validate([
      'name'          => 'required|max:255',
      'area_sqm'      => 'required|numeric|max:10',
      'customer_id'   => 'required|exists:customers,id',
      'cluster_id'    => 'required|exists:clusters,id',
    ]);

    return Unit::create($unit);
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    //
  }

  public function debt($id)
  {
    $customer = Customer::find($id);
    $units = $customer->units()
      ->with(['customer:id,name', 'cluster:id,name', 'transactions.payments', 'transactions' => function ($query) {
        $query->withoutGlobalScopes([ApprovedScope::class]);
      }])
      ->get();

    foreach ($units as $unit) {
      $debt = 0;
      foreach ($unit->transactions as $transaction) {
        foreach ($transaction->payments as $payment) {
          switch ($payment->id) {
            case 11:
              $debt -= $payment->pivot->amount;
              break;

            case 8:
              $debt += $payment->pivot->amount;
              break;
          }
        }
      }
      $unit['debt'] = $debt;
    }

    foreach ($units as $unit) {
      $balance = 0;
      foreach ($unit->transactions as $transaction) {
        foreach ($transaction->payments as $payment) {
          switch ($payment->id) {
            case 3:
              $balance += $payment->pivot->amount;
              break;

            case 10:
              $balance -= $payment->pivot->amount;
              break;
          }
        }
      }
      $unit['balance'] = $balance;
    }

    foreach ($units as $unit) {
      $startMonth = $unit->created_at->firstOfMonth();
      $endMonth = now()->firstOfMonth();
      $diffInMonths = $startMonth->diffInMonths($endMonth);
      $months = [];

      for ($i = 0; $i < $diffInMonths; $i++) {
        $period = $unit->created_at->addMonths($i);

        if ($unit->transactions->first()) {
          foreach ($unit->transactions as $key => $transaction) {
            if (!$period->diffInMonths($transaction->period)) {
              $unit->transactions->forget($key);
              continue 2;
            }
          }
        }

        $price = $unit->cluster->prices->last();

        $months[] = [
          'period' => $period,
          'credit' => $price->cost * ($price->per == 'sqm' ? $unit->area_sqm : 1),
          'fine' => 2000 * ($diffInMonths - $i - 1)
        ];
      }

      $unit['months'] = $months;
    }

    $payments = Payment::get(['id', 'name'])->only([4, 5, 6, 7, 8, 9, 10]);

    // echo json_encode($units);exit();

    return view('unit.debt', compact('customer', 'units', 'payments'));
  }

  public function sync()
  {
    Unit::query()
      ->select([
        'id',
        'customer_id',
        'cluster_id',
        'name',
        'area_sqm',
        'idlink',
        'created_at'
      ])
      ->with([
        'cluster',
        'customer:id,name',
        'transactions:id,unit_id,period',
        'transactions.payments:id'
      ])
      ->chunk(50, function ($units) {
        foreach ($units as $unit) {
          $transactions = $unit->transactions;

          $unit['customer_name'] = '';

          if ($unit->customer()->exists()) {
            $unit['customer_name'] = $unit->customer->name;
          } else {
            $unit['customer_id'] = 0;
          }

          $unit['balance'] = 0;
          $unit['debt'] = 0;

          foreach ($unit->transactions as $transaction) {
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
              'credit' => $unit->cluster->cost * ($unit->cluster->per == 'sqm' ? $unit->area_sqm : 1),
              'fine' => 2000 * ($diffInMonths - $i - 1)
            ]);
          }

          $unit['months_count'] = $months->count();
          $unit['months_total'] = $months->sum('credit') + $months->sum('fine');
          $unit['credit'] = $unit->cluster->cost * ($unit->cluster->per == 'sqm' ? $unit->area_sqm : 1);

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
            'credit'
          ]);
        }

        DB::table('configs')->upsert(['key' => 'units_last_sync', 'value' => now()], 'key');
        DB::table('unit_shadows')->upsert($unitShadows, 'id');
      });

    return redirect()->route('units.index');
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
