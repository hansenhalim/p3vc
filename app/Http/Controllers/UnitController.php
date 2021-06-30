<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Unit;
use App\Models\Cluster;
use App\Models\Customer;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Builder;

class UnitController extends Controller
{

  public function index(Request $request)
  {
    $key = $request->key;
    $sort = $request->get('sort') ?? 'id';
    $order = $request->get('order') ?? 'asc';

    $units = Unit::query()
      ->with(['customer:id,name', 'cluster:id,name'])
      ->withCount(['transactions' => function (Builder $query) {
        $query->where('content', 'like', 'code%');
      }])
      ->orderBy($sort, $order)
      ->when($key, fn ($query, $key) => $query->where('name', 'like', '%' . $key . '%'))
      ->paginate();

    echo json_encode($units);
    exit;

    return view('unit.list', compact('units'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    $clusters = Cluster::with(['prices'])->get();
    $customers = Customer::all();
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

  public function debt($id) {
    $customer = Customer::find($id);
    $units = $customer->units()
      ->with(['customer:id,name', 'cluster:id,name', 'transactions'])
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

      for ($i=0; $i < $diffInMonths; $i++) {
        $period = $unit->created_at->addMonths($i);

        if($unit->transactions->first()){
          foreach ($unit->transactions as $key => $transaction) {
            if(!$period->diffInMonths($transaction->period)){
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

    $payments = Payment::get(['id', 'name'])->only([4,5,6,7,8,9,10]); 

    // echo json_encode($units);exit();
    
    return view('unit.debt', compact('customer', 'units', 'payments'));
  }
}
