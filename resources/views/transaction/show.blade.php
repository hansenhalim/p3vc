@extends('dashboard.base')

@section('content')
  <div class="container-fluid">
    <div class="fade-in">
      <div class="row">
        <div class="col-xl-8">
          <x-return-button></x-return-button>
          <div class="card">
            <x-card-header>Transaction Detail</x-card-header>
            <div class="card-body">
              <table class="table table-responsive-md table-striped table-borderless text-nowrap">
                <thead class="border-bottom">
                  <tr>
                    <th class="text-center">CIF</th>
                    <th>Blok</th>
                    <th>Nama</th>
                    <th>Cluster</th>
                    <th class="text-right">Area&nbsp;(m<sup>2</sup>)</th>
                    <th class="text-right">Saldo</th>
                    <th class="text-right">Iuran</th>
                  </tr>
                </thead>

                <tbody>
                  <tr>
                    <th class="text-center">#{{ $transaction->customer_id }}</th>
                    <td>{{ $transaction->unit_name }}</td>
                    <td>{{ $transaction->customer_name }}</td>
                    <td>{{ $transaction->cluster_name }}</td>
                    <td class="text-right">{{ number_format($transaction->area_sqm) }}</td>
                    <td class="text-right">{{ number_format($unit->balance) }}</td>
                    <td class="text-right">
                      {{ number_format($transaction->cluster_cost * ($transaction->cluster_per == 'mth' ?: $transaction->area_sqm)) }}
                    </td>
                  </tr>
                </tbody>

                <thead class="border-bottom">
                  <tr>
                    <th colspan="3"></th>
                    <th class="text-right">#</th>
                    <th>Period</th>
                    <th class="text-right">Iuran</th>
                    <th class="text-right">Denda</th>
                  </tr>
                </thead>

                <tbody>
                  <tr class="mth">
                    <th colspan="3"></th>
                    <th class="text-right">1</th>
                    <td>{{ $transaction->period->formatLocalized('%B %Y') }}</td>
                    <td class="text-right">
                      {{ number_format($transaction->payments->where('id', 1)->first()->pivot->amount ?? 0) }}</td>
                    <td class="text-right">
                      {{ number_format($transaction->payments->where('id', 2)->first()->pivot->amount ?? 0) }}</td>
                  </tr>
                </tbody>

                <thead class="table-sm">
                  @foreach ($transaction->payments as $payment)
                    <tr>
                      <th
                        colspan="6"
                        class="text-right"
                      >{{ $payment->name }}</th>
                      <th class="text-right">{{ number_format($payment->pivot->amount) }}</th>
                    </tr>
                  @endforeach
                </thead>
              </table>
            </div>
            @if (Auth::user()->hasRole('supervisor') && !$transaction->approved_at)
              <form
                action="{{ route('transactions.approve', ['transaction' => $transaction->id]) }}"
                method="POST"
              >
                @csrf
                <div class="card-footer d-flex justify-content-between">
                  <button
                    value="false"
                    name="approval"
                    type="submit"
                    class="btn btn-link text-danger font-weight-bold"
                  ><i class="cil-thumb-down align-text-top"></i>&nbsp;Reject</button>
                  @if (!$transaction->payments->firstWhere('id', 10) || $unit->balance >= $transaction->payments->firstWhere('id', 10)->pivot->amount)
                    <button
                      value="true"
                      name="approval"
                      type="submit"
                      class="btn btn-warning font-weight-bold"
                    ><i class="cil-thumb-up align-text-top"></i>&nbsp;Approve</button>
                  @endif
                </div>
              </form>
            @elseif($transaction->approved_at)
              <div class="card-footer d-flex justify-content-center table-success">
                Yes, it's been approved.
                @if (Auth::user()->hasRole('master'))
                  Something is wrong?&nbsp;
                  <a href="{{ route('master.index', ['transaction_id' => $transaction]) }}">Unapprove</a>.
                @endif
              </div>
            @else
              <div class="card-footer d-flex justify-content-center table-warning">Be patient, still waiting for approval
                :/</div>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>

@endsection

@section('javascript')

@endsection
