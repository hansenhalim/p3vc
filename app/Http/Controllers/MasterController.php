<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class MasterController extends Controller
{
  public function index()
  {
    return view('master.index');
  }

  public function unapproveTrx(Request $request)
  {
    $request->validate([
      'transaction_id' => 'required|integer|exists:transactions,id'
    ]);

    Transaction::query()
      ->where('id', $request->transaction_id)
      ->update([
        'approved_at' => null,
        'approved_by' => null
      ]);

    $request->session()->flash('status', 'Successfully unapproved transactions. Thankyou.');
    return back();
  }
}
