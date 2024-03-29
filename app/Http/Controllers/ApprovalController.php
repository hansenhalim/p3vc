<?php

namespace App\Http\Controllers;

use App\Models\Cluster;
use App\Models\Customer;
use App\Models\Unit;
use App\Scopes\ApprovedScope;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApprovalController extends Controller
{
  public function index()
  {
    $customers = Customer::query()
      ->withoutGlobalScopes([ApprovedScope::class])
      ->withTrashed()
      ->select([
        '*',
        DB::raw('"customer" AS type'),
        DB::raw('IF(deleted_at IS NOT NULL,"DEL",IF(id = previous_id,"INS","MOD")) AS operation')
      ])
      ->with('user:id,name')
      ->whereNull('approved_at')
      ->get();

    $clusters = Cluster::query()
      ->withoutGlobalScopes([ApprovedScope::class])
      ->withTrashed()
      ->select([
        '*',
        DB::raw('"cluster" AS type'),
        DB::raw('IF(deleted_at IS NOT NULL,"DEL",IF(id = previous_id,"INS","MOD")) AS operation')
      ])
      ->with('user:id,name')
      ->whereNull('approved_at')
      ->get();

    $units = Unit::query()
      ->withoutGlobalScopes([ApprovedScope::class])
      ->withTrashed()
      ->select([
        '*',
        DB::raw('"unit" AS type'),
        DB::raw('IF(deleted_at IS NOT NULL,"DEL",IF(id = previous_id,"INS","MOD")) AS operation')
      ])
      ->with('user:id,name')
      ->whereNull('approved_at')
      ->get();

    $approvals = collect();
    $approvals = $approvals
      ->merge($customers)
      ->merge($clusters)
      ->merge($units)
      ->sortDesc();

    // echo json_encode($approvals); exit;

    return view('approval.list', compact('approvals'));
  }

  public function create()
  {
    //
  }

  public function store(Request $request)
  {
    //
  }

  public function show($type, $id)
  {
    switch ($type) {
      case 'customer':
        $approval = Customer::query()
          ->withoutGlobalScopes([ApprovedScope::class])
          ->withTrashed()
          ->select([
            '*',
            DB::raw('"customer" AS type'),
            DB::raw('IF(deleted_at IS NOT NULL,"DEL",IF(id = previous_id,"INS","MOD")) AS operation')
          ])
          ->find($id);

        if ($approval->operation == 'MOD') {
          $approval->original = Customer::query()
            ->latest('id')
            ->firstWhere([
              ['previous_id', '=', $approval->previous_id],
              ['id', '<>', $approval->id]
            ]);
        }
        break;

      case 'cluster':
        $approval = Cluster::query()
          ->withoutGlobalScopes([ApprovedScope::class])
          ->withTrashed()
          ->select([
            '*',
            DB::raw('"cluster" AS type'),
            DB::raw('IF(deleted_at IS NOT NULL,"DEL",IF(id = previous_id,"INS","MOD")) AS operation')
          ])
          ->find($id);

        if ($approval->operation == 'MOD') {
          $approval->original = Cluster::query()
            ->latest('id')
            ->firstWhere([
              ['previous_id', '=', $approval->previous_id],
              ['id', '<>', $approval->id]
            ]);
        }
        break;

      default:
        $approval = Unit::query()
          ->withoutGlobalScopes([ApprovedScope::class])
          ->withTrashed()
          ->select([
            '*',
            DB::raw('"unit" AS type'),
            DB::raw('IF(deleted_at IS NOT NULL,"DEL",IF(id = previous_id,"INS","MOD")) AS operation')
          ])
          ->find($id);

        if ($approval->operation == 'MOD') {
          $approval->original = Unit::query()
            ->latest('id')
            ->firstWhere([
              ['previous_id', '=', $approval->previous_id],
              ['id', '<>', $approval->id]
            ]);
        }
        break;
    }

    // echo json_encode($approval); exit;

    return view('approval.' . $type . '.show', compact('approval'));
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

  public function approve(Request $request, $type, $id)
  {
    switch ($type) {
      case 'customer':
        $approval = new Customer;
        break;

      case 'cluster':
        $approval = new Cluster;
        break;

      default:
        $approval = new Unit;
        break;
    }

    $approval = $approval->query()
      ->select([
        '*',
        DB::raw('IF(deleted_at IS NOT NULL,"DEL",IF(id = previous_id,"INS","MOD")) AS operation')
      ])
      ->withoutGlobalScopes([ApprovedScope::class])
      ->withTrashed()
      ->find($id);

    if ($request->approval === 'true') {
      # if approved then fill only approval
      if ($approval->approved_at || $approval->approved_by) {
        $request->session()->flash('status', 'Failed to approve ' . $approval->name  . '. Sorry.');
        return redirect()->route('approvals.index');
      }

      $approval->approved_at = now();
      $approval->approved_by = $request->user()->id;
      $approval->save();

      # rebinding units to new customer
      if ($type == 'customer') {
        $approval->original = $approval->latest()
          ->firstWhere([
            ['previous_id', '=', $approval->previous_id],
            ['id', '<>', $approval->id]
          ]);

        if (isset($approval->original)) $approval->original->units()->update(['customer_id' => $id]);
      }

      # rebinding units to new cluster
      if ($type == 'cluster') {
        $approval->original = $approval->latest()
          ->firstWhere([
            ['previous_id', '=', $approval->previous_id],
            ['id', '<>', $approval->id]
          ]);

        if (isset($approval->original)) $approval->original->units()->update(['cluster_id' => $id]);
      }

      #if unit then delete except this unit
      if ($type == 'unit') {
        $approval->original = $approval->latest()
          ->firstWhere([
            ['previous_id', '=', $approval->previous_id],
            ['id', '<>', $approval->id]
          ]);

        if (isset($approval->original)) $approval->original->delete();
      }

      # if deleted then also delete all same prev_id
      if ($approval->operation == 'DEL') $approval->where('previous_id', $approval->previous_id)->delete();

      $request->session()->flash('status', 'Successfully approved ' . $approval->name  . '. Thankyou.');
    } else {
      # else rejected delete only approval and approve too
      if ($approval->approved_at || $approval->approved_by) {
        $request->session()->flash('status', 'Failed to reject ' . $approval->name  . '. Sorry.');
        return redirect()->route('approvals.index');
      }

      $approval->approved_at = now();
      $approval->approved_by = $request->user()->id;
      $approval->save();

      $approval->delete();

      $request->session()->flash('status', 'Successfully rejected ' . $approval->name  . '. Thankyou.');
    }

    return redirect()->route('approvals.index');
  }
}
