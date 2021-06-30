<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Unit;
use App\Scopes\ApprovedScope;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use stdClass;

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
    $transactions = Transaction::query()
      ->withoutGlobalScope(ApprovedScope::class)
      ->with(['unit:id,name,customer_id'])
      ->withSum('payments', 'payment_transaction.amount')
      ->latest()
      ->paginate();

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
    // dd($request->all());
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
      ->withoutGlobalScope(ApprovedScope::class)
      ->where('id', $id)
      ->with(['unit.customer', 'payments'])
      ->first();

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

  public function printReport(Request $request)
  {
    $date = new stdClass;
    $date->from = Carbon::parse($request->dateFrom, 'Asia/Jakarta')->setTimezone('UTC');
    $date->to = Carbon::parse($request->dateTo, 'Asia/Jakarta')->setTimezone('UTC')->addDay()->subSecond();

    $transactions = Transaction::query()
      ->with(['payments:id', 'unit:id,name,customer_id'])
      ->whereBetween('created_at', [$date->from, $date->to])
      ->get();

    $allTransactions = Transaction::getTotals($date);

    $paymentDetails = [];
    $paymentDetailsSums = [];

    foreach ($transactions as $transaction) {
      $transaction->amount = $transaction->payments->whereIn('id', $this->credits)->sum('pivot.amount');

      foreach ($this->payment_ids as $key => $id) {
        $paymentDetails[$key] = collect($transaction->payments->firstWhere('id', $id))->whenEmpty(fn () => 0, fn ($payment) => $payment['pivot']['amount']);
        $paymentDetailsSums[$key] = (int) ($allTransactions->firstWhere('id', $id)->total ?? 0);
      }

      $transaction->paymentDetails = $paymentDetails;
    }

    $transactions->paymentDetailsSums = $paymentDetailsSums;
    $transactions->paymentDetailsSumsSum = collect($paymentDetailsSums)->sum() / 2;

    // echo json_encode($transactions); exit();

    $data = ['transactions' => $transactions];
    $pdf = PDF::loadView('pdf.report', $data)->setPaper('a4', 'landscape');
    return $pdf->stream('report.pdf');
  }

  public function print($id)
  {
    $data = ['name' => $id];
    $pdf = PDF::loadView('pdf.invoice', $data);
    return $pdf->stream('report.pdf');
  }

  public function approve(Request $request, $id)
  {
    switch ($request->approval) {
      case 'true':
        Transaction::query()
          ->withoutGlobalScope(ApprovedScope::class)
          ->where('id', $id)
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

    $date = new stdClass;
    $date->from = Carbon::parse($request->dateFrom, 'Asia/Jakarta')->setTimezone('UTC');
    $date->to = Carbon::parse($request->dateTo, 'Asia/Jakarta')->setTimezone('UTC')->addDay()->subSecond();

    $transactions = Transaction::query()
      ->with(['payments:id', 'unit:id,name,customer_id'])
      ->whereBetween('created_at', [$date->from, $date->to])
      ->paginate();

    $allTransactions = Transaction::getTotals($date);

    $paymentDetails = [];
    $paymentDetailsSums = [];

    foreach ($transactions as $transaction) {
      $transaction->amount = $transaction->payments->whereIn('id', $this->credits)->sum('pivot.amount');

      foreach ($this->payment_ids as $key => $id) {
        $paymentDetails[$key] = collect($transaction->payments->firstWhere('id', $id))->whenEmpty(fn () => 0, fn ($payment) => $payment['pivot']['amount']);
        $paymentDetailsSums[$key] = (int) ($allTransactions->firstWhere('id', $id)->total ?? 0);
      }

      $transaction->paymentDetails = $paymentDetails;
    }

    $transactions->paymentDetailsSums = $paymentDetailsSums;
    $transactions->paymentDetailsSumsSum = collect($paymentDetailsSums)->sum() / 2;

    // echo json_encode($transactions); exit();

    return view('transaction.report', compact('transactions'));
  }
}
