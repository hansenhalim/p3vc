<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Unit;
use App\Scopes\ApprovedScope;
use Barryvdh\DomPDF\Facade as DomPDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use NumberFormatter;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use stdClass;

class TransactionController extends Controller
{
  public function __construct()
  {
    $this->payment_ids = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11];
    $this->credits = [1, 2, 3, 11];
    $this->debits = [4, 5, 6, 7, 8, 9, 10];
  }

  public function index(Request $request)
  {
    $search = $request->search;
    $perPage = $request->page == 'all' ? 2000 : 10;

    $transactions = Transaction::withoutGlobalScope(ApprovedScope::class)
      ->when(
        $search,
        function ($query, $search) {
          return $query->where('unit_name', $search);
        },
        function ($query) {
          return $query->whereBetween('created_at', [now()->subMonth(), now()]);
        }
      )
      ->withSum('payments', 'payment_transaction.amount')
      ->latest('id')
      ->paginate($perPage);

    // echo json_encode($transactions); exit();

    return view('transaction.list', compact('transactions'));
  }

  public function store(Request $request)
  {
    foreach ($request->units as $item) {
      if (!isset($item['months'])) continue;

      $previousUnit = Unit::with(['transactions' => function ($query) {
        $query->withoutGlobalScope(ApprovedScope::class);
      }])->findOrFail($item['unit_previous_id']);

      $unit = Unit::findOrFail($item['unit_id']);

      $periods = $previousUnit->transactions->pluck('period')->toArray();

      foreach ($item['months'] as $month) {
        if (in_array(Carbon::parse($month['period']), $periods) && $month['period'] != '1970-01-01') continue;

        $balance = 0;

        foreach ($month['payments'] as $payment) {
          if (in_array($payment['payment_id'], $this->credits)) $balance -= $payment['amount'];
          else $balance += $payment['amount'];
        }

        if ($balance < 0) continue;

        $transaction = $previousUnit->transactions()->create([
          'customer_id' => $unit->customer_id,
          'unit_name' => $unit->name,
          'customer_name' => $unit->customer->name,
          'cluster_name' => $unit->cluster->name,
          'area_sqm' => $unit->area_sqm,
          'cluster_cost' => $unit->cluster->cost,
          'cluster_per' => $unit->cluster->per,
          'period' => $month['period'],
          'updated_by' => $request->user()->id
        ]);

        foreach ($month['payments'] as $payment) {
          if (!$payment['amount']) continue;
          $transaction->payments()->attach($payment['payment_id'], ['amount' => $payment['amount']]);
        }

        if ($balance > 0) $transaction->payments()->attach(3, ['amount' => $balance]);
      }
    }

    // echo json_encode($transaction); exit();

    $request->session()->flash('status', 'Successfully created transactions. Thankyou.');

    return redirect()->route('customers.index');
  }

  public function show($id)
  {
    $transaction = Transaction::query()
      ->withoutGlobalScope(ApprovedScope::class)
      ->with(['payments', 'unit.transactions.payments'])
      ->findOrFail($id);

    $unit = $transaction->unit;

    $unit['balance'] = 0;
    $unit['debt'] = 0;

    foreach ($unit->transactions as $mytransaction) {
      foreach ($mytransaction->payments as $payment) {
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

    // echo json_encode($transaction); exit();

    return view('transaction.show', compact('transaction', 'unit'));
  }

  public function printReport(Request $request)
  {
    $date = new stdClass;
    $date->from = Carbon::parse($request->dateFrom);
    $date->to = Carbon::parse($request->dateTo)->addDay()->subSecond();

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

    $pdf = DomPDF::loadView('pdf.report', compact('transactions'))
      ->setPaper('a4', 'landscape');

    $dateFrom = $date->from->setTimezone('Asia/Jakarta')->formatLocalized('%d %B %Y');
    $dateTo = $date->to->setTimezone('Asia/Jakarta')->formatLocalized('%d %B %Y');

    return $pdf->download('Report Transaksi_' . $dateFrom . '_' . $dateTo . '.pdf');
  }

  public function print($id)
  {
    $transaction = Transaction::findOrFail($id);
    $transaction->created_at->setTimezone('Asia/Jakarta');
    $payments = $transaction->payments;

    $transaction->credits = $payments->whereIn('id', [1, 2, 9, 11]);
    $transaction->debits = $payments->whereIn('id', [4, 5, 6, 7, 10]);

    $transaction->balance = $payments->firstWhere('id', 3)->pivot->amount ?? null;
    $transaction->debt = $payments->firstWhere('id', 8)->pivot->amount ?? null;
    $transaction->discount = $payments->firstWhere('id', 9)->pivot->amount ?? null;

    $transaction->credits_sum_amount = $transaction->credits->sum('pivot.amount') - $transaction->discount * 2;
    $transaction->debits_sum_amount = $transaction->debits->sum('pivot.amount');

    $spellout = new NumberFormatter('id_ID', NumberFormatter::SPELLOUT);
    $transaction->debits_sum_amount_spelled = $spellout->format($transaction->debits_sum_amount);

    $periodInRoman = $this->numberToRomanRepresentation($transaction->created_at->month);
    $transaction->invoiceNumber = config('app.name') . '/' . $transaction->unit->customer_id . '/' . $periodInRoman . '/' . $transaction->created_at->year;

    $qrcodeContent = base64_encode(json_encode(array($transaction->id)));
    $qrcodeRaw = QrCode::size(110)->margin(3)->backgroundColor(255, 255, 255)->generate($qrcodeContent);
    $qrcode = str_replace('Cww', 'C4w', base64_encode($qrcodeRaw));

    $transaction->title = $transaction->unit->name;
    $transaction->title .= ' ' . ($transaction->period->formatLocalized('%b %y') != 'Jan 70' ? $transaction->period->formatLocalized('%b %y') : '');
    $transaction->title .= ' ' . $transaction->unit->customer->name;

    $file = mb_ereg_replace("([^\w\s\d\-_~,;\[\]\(\).])", '', $transaction->title);
    $file = mb_ereg_replace("([\.]{2,})", '', $file);

    // echo json_encode($transaction); exit;
    // return view('pdf.invoice', compact('transaction', 'qrcode'));

    $pdf = DomPDF::loadView('pdf.invoice', compact('transaction', 'qrcode'));
    return $pdf->stream($file . '.pdf');
  }

  public function approve(Request $request, $id)
  {
    switch ($request->approval) {
      case 'true':
        $updateStatus = Transaction::query()
          ->withoutGlobalScope(ApprovedScope::class)
          ->where('id', $id)
          ->whereNull('approved_by')
          ->whereNull('approved_at')
          ->update([
            'approved_by' => $request->user()->id,
            'approved_at' => now()
          ]);
        $request->session()->flash('status', $updateStatus ? 'Successfully approved transactions. Thankyou.' : 'Transaction approval failed. Sorry.');
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
    $perPage = $request->page == 'all' ? 2000 : 10;

    if (!($request->dateFrom && $request->dateTo)) return view('transaction.report');

    $date = new stdClass;
    $date->from = Carbon::parse($request->dateFrom, 'Asia/Jakarta')->setTimezone('UTC');
    $date->to = Carbon::parse($request->dateTo, 'Asia/Jakarta')->setTimezone('UTC')->addDay()->subSecond();

    $transactions = Transaction::query()
      ->with(['payments:id', 'unit:id,name,customer_id'])
      ->whereBetween('created_at', [$date->from, $date->to])
      ->paginate($perPage);

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
