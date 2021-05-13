<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Unit;
use App\Models\Cluster;
use App\Models\Customer;

class UnitController extends Controller
{

    public function index(Request $request)
    {
        $key = $request->key;
        $sort = $request->get('sort') ?? 'id';
        $order = $request->get('order') ?? 'asc';

        $query = Unit::query();

        $query->with(['customer:id,name', 'cluster:id,name']);
        $query->orderBy($sort, $order);

        $query->when($key, function ($query, $key) {
            return $query->where('name', 'like', '%' . $key . '%');
        });

        $units = $query->paginate();

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
            'name' => 'required|max:255',
            'area_sqm' => 'required|numeric|max:10',
            'customer_id' => 'required|exists:customers,id',
            'cluster_id' => 'required|exists:clusters,id',
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
}
