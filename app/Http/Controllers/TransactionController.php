<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Unit;
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
    $transactions = Transaction::query()
      ->with(['unit'])
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
    //
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
    Transaction::find($id)
      ->update([
        'approved_by' => Auth::id(),
        'approved_at' => now()
      ]);

    $request->session()->flash('status', 'Successfully approved transactions. Thankyou.');

    return back();
  }
}
