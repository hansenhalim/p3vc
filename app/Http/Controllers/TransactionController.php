<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Unit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
  public function __construct()
  {
    $this->payment_ids = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11];
    $this->credits = [1, 2, 3, 11];
    $this->debits = [4, 5, 6, 7, 8, 9, 10];
  }
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

    // echo json_encode($transactions); exit();

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
          $balance = 0;

          foreach ($month['payments'] as $payment) {
            if (in_array($payment['payment_id'], $this->credits)) $balance -= $payment['amount'];
            else $balance += $payment['amount'];
          }

          if ($balance < 0) continue;

          $transaction = $unit->transactions()->create([
            'period' => $month['period'],
            'updated_by' => Auth::id()
          ]);

          foreach ($month['payments'] as $payment) {
            if (!$payment['amount']) continue;
            $transaction->payments()->attach($payment['payment_id'], ['amount' => $payment['amount']]);
          }

          if ($balance > 0) $transaction->payments()->attach(3, ['amount' => $balance]);
        }
      }
    }

    // echo json_encode($request->all()); exit();

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
    if (!($request->dateFrom && $request->dateTo)) return view('transaction.report');

    $date['from'] = Carbon::parse($request->dateFrom, 'Asia/Jakarta')->setTimezone('UTC');
    $date['to'] = Carbon::parse($request->dateTo, 'Asia/Jakarta')->setTimezone('UTC');

    $transactions = Transaction::with(['payments:id', 'unit:id,name'])
      ->whereBetween('created_at', [$date['from'], $date['to']->addDay()->subSecond()])
      ->whereNotNull('approved_at')
      ->paginate();

    $paymentDetailSums = [0,0,0,0,0,0,0,0,0,0,0];

    foreach ($transactions as $transaction) {
      $transaction->period = Carbon::make($transaction->period);
      $transaction->approved_at = Carbon::make($transaction->approved_at);
      $transaction->amount = $transaction->payments->whereIn('id', $this->credits)->sum('pivot.amount');

      foreach ($this->payment_ids as $key => $id) {
        $amount = collect($transaction->payments->firstWhere('id', $id))->whenEmpty(fn () => 0, fn ($payment) => $payment['pivot']['amount']);
        $paymentDetails[$key] = $amount;
        $paymentDetailSums[$key] += $amount;
      }

      $transaction->paymentDetails = $paymentDetails;
    }

    $transactions->amountSum = $transactions->sum('amount');
    $transactions->paymentDetailSums = $paymentDetailSums;

    // echo json_encode($transactions); exit();

    return view('transaction.report', compact('transactions'));
  }
}
