<?php

namespace App\Http\Controllers;

use App\Models\Cluster;
use Illuminate\Http\Request;

class ClusterController extends Controller
{
  public function index(Request $request)
  {
    $search = $request->search;
    $sortBy = $request->sortBy ?? 'id';
    $sortDirection = $request->sortDirection ?? 'asc';
    $perPage = $request->page == 'all' ? 2000 : 10;

    $clusters = Cluster::withCount(['units'])
      ->when($search, fn ($query) => $query->where('name', 'like', '%' . $search . '%'))
      ->orderBy($sortBy, $sortDirection)
      ->paginate($perPage);

    return view('cluster.list', compact('clusters'));
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
    return view('cluster.show', compact('cluster'));
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

    $request->session()->flash('status', 'Successfully updated ' . $cluster->name . '. Please wait for appoval.');

    return redirect()->route('clusters.index');
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
}
