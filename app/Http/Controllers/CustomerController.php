<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Unit;
use App\Models\Transaction;
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
    $units = Unit::where('customer_id', $id)
      ->with(['customer:id,name', 'cluster:id,name'])
      ->paginate();

    foreach ($units as $unit) {
      $now_month = now()->firstOfMonth();
      $created_at_month = $unit->created_at->firstOfMonth();
      $diffInMonths = $created_at_month->diffInMonths($now_month);
      for ($i=0; $i < 12; $i++) {
        $months[$i] = ['period'];
      }
      $unit['months'] = $months;
    }

    echo json_encode($units[0]);exit();
    
    return view('customer.show', compact('customer', 'units'));
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
    Customer::where('id', $id)->update(['deleted_by' => Auth::id()]);
    Customer::destroy($id);

    return redirect()->route('customers.index');
  }
}
