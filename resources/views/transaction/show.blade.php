@extends('dashboard.base')

@section('content')
  <div class="container-fluid">
    <div class="fade-in">
      <a class="btn btn-link mb-2" href="{{ route('transactions.index') }}">&lt;&lt; Return</a>
      <div class="row">
        <div class="col-xl-8">
          <div class="card">
            @csrf
            <div class="card-header">Unit List</div>
            <div class="card-body">
              <table class="table table-responsive-md text-nowrap">
                <thead class="thead-dark">
                  <tr>
                    <th class="text-center">#</th>
                    <th>Name</th>
                    <th>Customer</th>
                    <th>Cluster</th>
                    <th class="text-right">Area&nbsp;(m<sup>2</sup>)</th>
                    <th class="text-right">Balance</th>
                    <th class="text-right">Credit</th>
                  </tr>
                </thead>
                <tbody>
                  <tr class="table-light">
                    <th class="text-center">1</th>
                    <th>{{ $unit->name }}</th>
                    <th>{{ $unit->customer->name }}</th>
                    <th>{{ $unit->cluster->name }}</th>
                    <td class="text-right">{{ number_format($unit->area_sqm) }}</td>
                    <td class="text-right">{{ number_format($unit->balance) }}</td>
                    <td class="text-right">{{ number_format($unit->cluster->prices->last()->cost * ($unit->cluster->prices->last()->per == 'sqm' ? $unit->area_sqm : 1)) }}</td>
                  </tr>
                  <thead class="thead-light">
                    <tr>
                      <th colspan="3"></th>
                      <th class="text-right">#</th>
                      <th>Period</th>
                      <th class="text-right">Credit</th>
                      <th class="text-right">Fine</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr class="mth">
                      <th colspan="3"></th>
                      <th class="text-right">1</th>
                      <td>{{ $transaction->period->formatLocalized('%B %Y') }}</td>
                      <td class="text-right">{{ number_format($transaction->payments->where('id', 1)->first()->pivot->amount ?? 0) }}</td>
                      <td class="text-right">{{ number_format($transaction->payments->where('id', 2)->first()->pivot->amount ?? 0) }}</td>
                    </tr>
                    <tr class="table-secondary table-sm">
                      <th colspan="6" class="text-right">TAGIHAN</th>
                      <th class="text-right">{{ number_format(($transaction->payments->where('id', 1)->first()->pivot->amount ?? 0) + ($transaction->payments->where('id', 2)->first()->pivot->amount ?? 0)) }}</th>
                    </tr>
                    @foreach ($transaction->payments->except([1, 2]) as $payment)
                      <tr class="table-secondary table-borderless table-sm">
                        <th colspan="6" class="text-right">{{ $payment->name }}</th>
                        <th class="text-right">{{ number_format($payment->pivot->amount) }}</th>
                      </tr>
                    @endforeach
                  </tbody>
                </tbody>
              </table>
            </div>
              @if(Auth::user()->hasRole('supervisor') && !$transaction->approved_at)
                <form action="{{ route('transactions.approve', ['transaction' => $transaction->id]) }}" method="POST">
                  @csrf
                  <div class="card-footer d-flex justify-content-between">
                    <button value="false" name="approval" type="submit" class="btn btn-link text-danger"><i class="cil-thumb-down"></i>&nbsp;Reject</button>
                    @if ($transaction->payments->firstWhere('id', 10))
                      @if ($unit->balance >= $transaction->payments->firstWhere('id', 10)->pivot->amount)
                        <button value="true" name="approval" type="submit" class="btn btn-success"><i class="cil-thumb-up"></i>&nbsp;Approve</button>
                      @endif
                    @endif
                  </div>
                </form>
              @elseif($transaction->approved_at)
                <div class="card-footer d-flex justify-content-center table-success">Yes, it is already approved :></div>
              @else
                <div class="card-footer d-flex justify-content-center table-warning">Be patient, still waiting for approval :/</div>
              @endif
          </div>
        </div>
      </div>
    </div>
  </div>

@endsection

@section('javascript')

@endsection
