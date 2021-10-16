<?php

namespace App\Http\Controllers;

use App\Models\Cluster;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClusterController extends Controller
{
  public function index(Request $request)
  {
    $search = $request->search;
    $sortBy = $request->sortBy ?? 'previous_id';
    $sortDirection = $request->sortDirection ?? 'asc';
    $perPage = $request->page == 'all' ? 2000 : 10;

    $latestClusters = Cluster::query()
      ->when($search, fn ($query) => $query->where('name', 'like', '%' . $search . '%'))
      ->orderBy($sortBy, $sortDirection)
      ->select('previous_id', DB::raw('MAX(id) AS id'))
      ->groupBy('previous_id')
      ->paginate($perPage);

    $clusters = Cluster::query()
      ->withCount('units')
      ->whereIn('id', $latestClusters->pluck('id'))
      ->orderBy($sortBy, $sortDirection)
      ->get();

    // echo json_encode($latestClusters); exit;

    return view('cluster.list', compact('latestClusters', 'clusters'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    return view('cluster.create');
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request, Cluster $cluster)
  {
    $request->validate([
      'name' => 'required',
      'cost' => 'required|integer',
      'per' => 'required|in:sqm,mth'
    ], [
      'cost.integer' => 'Harga tidak valid'
    ]);

    $cluster->name = $request->name;
    $cluster->cost = $request->cost;
    $cluster->per = $request->per;
    $cluster->updated_by = $request->user()->id;

    $cluster->save();

    $cluster->previous_id = $cluster->id;

    $cluster->save();

    $request->session()->flash('status', 'Successfully created ' . $cluster->name . '. Please wait for appoval.');

    return redirect()->route('clusters.index');
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show(Cluster $cluster)
  {
    $latestUnits = $cluster->units()
      ->select('previous_id', DB::raw('MAX(id) AS id'))
      ->groupBy('previous_id')
      ->paginate(10);

    $units = Unit::query()
      ->with(['customer', 'transactions.payments', 'transactions' => function ($query) {
        $query->withoutGlobalScopes([ApprovedScope::class]);
      }])
      ->whereIn('id', $latestUnits->pluck('id'))
      ->oldest('previous_id')
      ->get();

    foreach ($units as $unit) {
      $transactions = $unit->transactions;

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
    }

    // echo json_encode($units); exit;

    return view('cluster.show', compact('latestUnits', 'cluster', 'units'));
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit(Cluster $cluster)
  {
    return view('cluster.edit', compact('cluster'));
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, Cluster $cluster)
  {
    $request->validate([
      'name' => 'required',
      'cost' => 'required|integer',
      'per' => 'required|in:sqm,mth'
    ], [
      'cost.integer' => 'Harga tidak valid'
    ]);

    $cluster->name = $request->name;
    $cluster->cost = $request->cost;
    $cluster->per = $request->per;

    if ($cluster->isClean()) return redirect()->route('clusters.index');

    $cluster->approved_at = null;
    $cluster->approved_by = null;
    $cluster->updated_by = $request->user()->id;
    $cluster->replicate()->save();

    $request->session()->flash('status', 'Successfully updated ' . $cluster->name . '. Please wait for appoval.');

    return redirect()->route('clusters.index');
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy(Request $request, Cluster $cluster)
  {
    $cluster->approved_at = null;
    $cluster->approved_by = null;
    $cluster->updated_by = $request->user()->id;
    $cluster = $cluster->replicate();
    $cluster->save();
    $cluster->delete();

    $request->session()->flash('status', 'Successfully deleted ' . $cluster->name . '. Please wait for appoval.');

    return redirect()->route('clusters.index');
  }
}
