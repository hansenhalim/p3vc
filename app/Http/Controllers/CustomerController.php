<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Payment;
use App\Scopes\ApprovedScope;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
  public function index(Request $request)
  {
    $search = $request->search;
    $sortBy = $request->sortBy ?? 'previous_id';
    $sortDirection = $request->sortDirection ?? 'asc';
    $perPage = $request->page == 'all' ? 2000 : 10;

    $latestCustomers = DB::table('customers')
      ->whereNotNull('approved_at')
      ->when($search, fn ($query) => $query->where('previous_id', $search)
        ->orWhere('phone_number', $search)
        ->orWhere('name', 'like', '%' . $search . '%'))
      ->orderBy($sortBy, $sortDirection)
      ->select('previous_id', DB::raw('MAX(id) AS id, MAX(name) AS name'))
      ->groupBy('previous_id')
      ->paginate($perPage);

    $customers = Customer::query()
      ->withCount('units')
      ->whereIn('id', $latestCustomers->pluck('id'))
      ->orderBy($sortBy, $sortDirection)
      ->get();

    return view('customer.list', compact('latestCustomers', 'customers'));
  }

  public function create()
  {
    return view('customer.create');
  }

  public function store(Request $request, Customer $customer)
  {
    $request->validate([
      'name' => 'required',
      'phone_number' => 'required',
    ]);

    $customer->name = $request->name;
    $customer->phone_number = $request->phone_number;
    $customer->updated_by = $request->user()->id;

    $customer->save();

    $customer->previous_id = $customer->id;

    $customer->save();

    $request->session()->flash('status', 'Successfully created ' . $customer->name . '. Please wait for appoval.');

    return redirect()->route('customers.index');
  }

  public function show(Customer $customer)
  {
    $units = $customer->units()
      ->with(['cluster', 'transactions.payments', 'transactions' => function ($query) {
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

        $months->push([
          'period' => $period,
          'credit' => $unit->cluster->cost * ($unit->cluster->per == 'sqm' ? $unit->area_sqm : 1),
          'fine' => 2000 * max($diffInMonthsReal - $i - 1, 0)
        ]);
      }

      $unit['months'] = $months;
    }

    $payments = Payment::get(['id', 'name'])->only([4, 5, 6, 7, 8, 9, 10]);

    // echo json_encode($units);exit();

    return view('customer.show', compact('customer', 'units', 'payments'));
  }

  public function edit(Customer $customer)
  {
    return view('customer.edit', compact('customer'));
  }

  public function update(Request $request, Customer $customer)
  {
    $request->validate([
      'name' => 'required',
      'phone_number' => 'required',
    ]);

    $customer->name = $request->name;
    $customer->phone_number = $request->phone_number;

    if ($customer->isClean()) return redirect()->route('customers.index');

    $customer->approved_at = null;
    $customer->approved_by = null;
    $customer->updated_by = $request->user()->id;
    $customer->replicate()->save();

    $request->session()->flash('status', 'Successfully updated ' . $customer->name . '. Please wait for appoval.');

    return redirect()->route('customers.index');
  }

  public function destroy(Request $request, Customer $customer)
  {
    $customer->approved_at = null;
    $customer->approved_by = null;
    $customer->updated_by = $request->user()->id;
    $customer = $customer->replicate();
    $customer->save();
    $customer->delete();

    $request->session()->flash('status', 'Successfully deleted ' . $customer->name . '. Please wait for appoval.');

    return redirect()->route('customers.index');
  }
}
