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
    $search = $request->search;
    $sortBy = $request->sortBy ?? 'id';
    $sortDirection = $request->sortDirection ?? 'asc';
    $perPage = $request->page == 'all' ? 2000 : 10;

    $customers = Customer::withCount(['units'])
      ->when($search, fn ($query) => $query->where('id', $search)
        ->orWhere('phone_number', $search)
        ->orWhere('name', 'like', '%' . $search . '%'))
      ->orderBy($sortBy, $sortDirection)
      ->paginate($perPage);

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

      $startMonth = $unit->created_at->firstOfMonth();
      $endMonth = now()->addMonths(request('add-month', 0))->firstOfMonth();
      $diffInMonths = $startMonth->diffInMonths($endMonth);
      $diffInMonthsReal = $startMonth->diffInMonths(now()->firstOfMonth());
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
        $price = $unit->cluster->prices->last();

        $months->push([
          'period' => $period,
          'credit' => $price->cost * ($price->per == 'sqm' ? $unit->area_sqm : 1),
          'fine' => 2000 * max($diffInMonthsReal - $i - 1, 0)
        ]);
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
