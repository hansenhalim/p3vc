<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Payment;
use App\Scopes\ApprovedScope;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
  public function index(Request $request)
  {
    $key = $request->key;
    $sort = $request->sort ?? 'id';
    $order = $request->order ?? 'desc';

    $query = Customer::query();

    $query->withCount(['units']);
    $query->orderBy($sort, $order);

    $query->when($key, function ($query, $key) {
      if (substr($key, 0, 1) === '#') return $query->where('id', substr($key, 1));
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
      ->with(['cluster.prices', 'transactions.payments', 'transactions' => function ($query) {
        $query->withoutGlobalScopes([ApprovedScope::class]);
      }])
      ->get();

    foreach ($units as $unit) {
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
      $endMonth = now()->addMonths(request('add-month', 0))->firstOfMonth();
      $diffInMonths = $startMonth->diffInMonths($endMonth);
      $diffInMonthsReal = $startMonth->diffInMonths(now()->firstOfMonth());
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
          'fine' => 2000 * max($diffInMonthsReal - $i - 1, 0)
        ];
      }

      $unit['months'] = $months;
    }

    $payments = Payment::get(['id', 'name'])->only([4, 5, 6, 7, 8, 9, 10]);

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
    $customer = Customer::find($id);

    $customer->update([
      'updated_by' => Auth::id(),
      'approved_at' => null,
      'approved_by' => null,
    ]);

    $customer->delete();

    return redirect()->route('customers.index');
  }
}
