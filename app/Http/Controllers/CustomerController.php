<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
  public function index(Request $request)
  {
    $key = $request->key;
    $sort = $request->get('sort') ?? 'id';
    $order = $request->get('order') ?? 'desc';

    $query = Customer::query();

    $query->withCount(['units']);
    $query->orderBy($sort, $order);

    $query->when($key, function ($query, $key) {
      if(substr($key,0,1) === '#') return $query->where('id', substr($key,1));
      return $query->where('name', 'like', '%' . $key . '%');
    });

    $customers = $query->paginate();

    return view('customer.list', compact('customers'));
  }

  public function create()
  {
    return view('customer.create');
  }

  public function store(Request $request)
  {
    $customer = $request->validate([
      'name' => 'required',
      'phone_number' => 'required|max:16',
    ]);

    $customer = Customer::create($customer);

    $request->session()->flash('status', 'Successfully created <a href="' . route('customers.show', ['customer' => $customer->id]) . '" class="alert-link">' . $customer->name . '</a>.');

    return $request->stay ? redirect()->route('customers.create')->with('stay', true) : redirect()->route('customers.index');
  }

  public function show($id)
  {
    $customer = Customer::find($id);
    $units = $customer->units()
      ->with(['customer:id,name', 'cluster:id,name', 'transactions'])
      ->get();

    foreach ($units as $unit) {
      $startMonth = $unit->created_at->firstOfMonth();
      $endMonth = now()->firstOfMonth();
      $diffInMonths = $startMonth->diffInMonths($endMonth);
      $months = [];

      for ($i=0; $i < $diffInMonths; $i++) {
        $period = $unit->created_at->addMonths($i);

        if($unit->transactions->first()){
          if(!$period->diffInMonths($unit->transactions->first()->period)){
            $unit->transactions->shift();
            continue;
          }
        }
        
        $months[] = [
          'period' => $period,
          'credit' => $unit->cluster->prices->last()->cost * ($unit->cluster->prices->last()->per == 'sqm' ? $unit->area_sqm : 1),
          'fine' => 2000 * ($diffInMonths - $i - 1)
        ];
      }

      $unit['months'] = $months;
    }

    $payments = Payment::get(['id', 'name'])->except([1,2,3,11]);

    // echo json_encode($units);exit();
    
    return view('customer.show', compact('customer', 'units', 'payments'));
  }

  public function edit($id)
  {
    $customer = Customer::find($id);
    return view('customer.edit', compact('customer'));
  }

  public function update(Request $request, $id)
  {
    $customerNew = $request->validate([
      'name' => 'required',
      'phone_number' => 'required|max:16',
    ]);

    $customer = Customer::find($id);
    $customer->update($customerNew);

    $request->session()->flash('status', 'Successfully updated <a href="' . route('customers.show', ['customer' => $customer->id]) . '" class="alert-link">' . $customer->name . '</a>.');

    return $request->stay ? redirect()->route('customers.create')->with('stay', true) : redirect()->route('customers.index');
  }

  public function destroy($id)
  {
    Customer::where('id', $id)->update(['updated_by' => Auth::id()]);
    Customer::destroy($id);

    return redirect()->route('customers.index');
  }
}
