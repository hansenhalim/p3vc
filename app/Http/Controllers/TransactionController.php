<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Unit;
use App\Scopes\ApprovedScope;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use NumberFormatter;
use stdClass;

class TransactionController extends Controller
{
  public function __construct()
  {
    $this->payment_ids = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11];
    $this->credits = [1, 2, 3, 11];
    $this->debits = [4, 5, 6, 7, 8, 9, 10];
  }

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

  public function create()
  {
    //
  }

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

  public function show($id)
  {
    $transaction = Transaction::query()
      ->withoutGlobalScope(ApprovedScope::class)
      ->with(['unit.customer', 'payments'])
      ->findOrFail($id);

    // echo json_encode($transaction); exit();

    return view('transaction.show', compact('transaction'));
  }

  public function edit($id)
  {
    //
  }

  public function update(Request $request, $id)
  {
    //
  }

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

    $pdf = PDF::loadView('pdf.report', compact('transactions'))
      ->setPaper('a4', 'landscape');

    return $pdf->stream('report.pdf');
  }

  public function print($id)
  {
    $transaction = Transaction::findOrFail($id);
    $transaction->periodInRoman = $this->numberToRomanRepresentation($transaction->created_at->setTimezone('Asia/Jakarta')->month);
    $spellout = new NumberFormatter("id", NumberFormatter::SPELLOUT);
    $transaction->balance = $transaction->payments->firstWhere('id', 3);
    $transaction->amount = $transaction->payments->sum('pivot.amount')/2;
    $transaction->amountSpellout = $spellout->format($transaction->amount);
    unset($this->credits[2]);
    $transaction->credits = $transaction->payments->whereIn('id', $this->credits);
    $transaction->debits = $transaction->payments->whereIn('id', $this->debits);
    // echo json_encode($transaction->balance); exit;
    $pdf = PDF::loadView('pdf.invoice', compact('transaction'));

    // return view('pdf.invoice', compact('transaction'));
    return $pdf->stream('invoice.pdf');
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
        $request->session()->flash('status', Transaction::withoutGlobalScope(ApprovedScope::class)->find($id)->delete() ? 'Successfully rejected transactions. Thankyou.' : 'Transaction rejection failed. Sorry.');
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

  private function numberToRomanRepresentation($number)
  {
    $map = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
    $returnValue = '';
    while ($number > 0) {
      foreach ($map as $roman => $int) {
        if ($number >= $int) {
          $number -= $int;
          $returnValue .= $roman;
          break;
        }
      }
    }
    return $returnValue;
  }
}
