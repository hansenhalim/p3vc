<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Unit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $transactions = Transaction::with(['unit:id,name,customer_id', 'payments:id'])->latest()->paginate();

    foreach ($transactions as $transaction) {
      $transaction->period = Carbon::make($transaction->period);
      $transaction->approved_at = Carbon::make($transaction->approved_at);
      foreach ($transaction->payments as $payment) {
        $transaction->amount += $payment->pivot->amount;
      }
      $transaction->amount /= 2;
    }

    // echo json_encode($transactions->all()); exit();

    return view('transaction.list', compact('transactions'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    foreach ($request->units as $item) {
      $unit = Unit::find($item['unit_id']);
      if (isset($item['months'])) {
        foreach ($item['months'] as $month) {
          $transaction = $unit->transactions()->create([
            'period' => $month['period'],
            'updated_by' => Auth::id()
          ]);
          foreach ($month['payments'] as $payment) {
            $transaction->payments()->attach($payment['payment_id'], ['amount' => $payment['amount']]);
          }
        }
      }
    }

    $request->session()->flash('status', 'Successfully created transactions. Thankyou.');
    
    return back();
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    $transaction = Transaction::query()
      ->where('id', $id)
      ->with(['unit.customer', 'payments'])
      ->first();

    $transaction->period = Carbon::make($transaction->period);

    // echo json_encode($transaction); exit();

    return view('transaction.show', compact('transaction'));
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

  public function print()
  {
    return redirect()->route('transactions.index');
  }

  public function approve(Request $request, $id)
  {
    switch ($request->approval) {
      case 'true':
        Transaction::find($id)
        ->update([
          'approved_by' => Auth::id(),
          'approved_at' => now()
        ]);
        $request->session()->flash('status', 'Successfully approved transactions. Thankyou.');
        break;
      
      default:
        Transaction::destroy($id);
        $request->session()->flash('status', 'Successfully rejected transactions. Thankyou.');
        break;
    }

    // echo json_encode($request->all()); exit();

    return redirect()->route('transactions.index');
  }

  public function report(Request $request)
  {
    // $red = [1,2,3,11];
    // $green = [4,5,6,7,8,9,10];

    // $dateFrom = $request->dateFrom ?? '2021-06-05 13:16:00';
    // $dateTo = $request->dateTo ?? '2021-06-05 13:17:00';

    // $transactions = Transaction::query()
    //   ->with(['payments'])
    //   ->whereBetween('created_at', [$dateFrom, $dateTo])
    //   ->whereNotNull('approved_at')
    //   ->paginate();

    // echo json_encode($transactions); exit();

    // return view('transaction.report', compact('transactions'));

    echo json_encode($request->all()); exit();
  }
}
