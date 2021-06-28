@extends('dashboard.base')

@section('content')
  <div class="container-fluid">
    <div class="fade-in">
      <div class="row">
        <div class="col-xl-7">
          <div class="card">
            <div class="card-header">Transaction List</div>
            <div class="card-body">
              @if (session('status'))
                <div class="alert alert-success alert-dismissible fade show">
                  {!! session('status') !!}
                  <button type="button" class="close" data-dismiss="alert">
                    <span>&times;</span>
                  </button>
                </div>
              @endif
              <table class="table table-responsive-sm table-striped text-nowrap">
                <thead class="thead-dark">
                  <tr>
                    <th>CIF</th>
                    <th>Unit</th>
                    <th>Period</th>
                    <th>Approved At</th>
                    <th class="text-right">Amount</th>
                    <th>More</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse ($transactions as $transaction)
                    <tr>
                      <th class="align-middle">#{{ $transaction->unit->customer_id }}</th>
                      <td class="align-middle">{{ $transaction->unit->name }}</td>
                      <td class="align-middle">{{ $transaction->period }}</td>
                      <td class="align-middle">{!! $transaction->approved_at ? $transaction->approved_at->diffForHumans() : '<span class="badge bg-danger text-white">None</span>' !!}</td>
                      <td class="align-middle text-right">{{ number_format($transaction->payments_sum_payment_transactionamount / 2) }}</td>
                      <td class="align-middle">
                        <div class="dropdown">
                          <button class="btn btn-outline-primary dropdown-toggle" type="button" id="dropdownMenuButton"
                            data-toggle="dropdown">Action</button>
                          <div class="dropdown-menu">
                            <a class="dropdown-item"
                              href="{{ route('transactions.show', ['transaction' => $transaction->id]) }}"><i
                                class="cil-info"></i>&nbsp;Show</a>
                            @if (Auth::user()->hasRole('operator') && $transaction->approved_at)
                              <a class="dropdown-item" href="{{ route('transactions.print', ['transaction' => $transaction->id]) }}" target="_blank"
                                rel="noopener noreferrer"><i class="cil-print"></i>&nbsp;Print</a>
                            @endif
                          </div>
                        </div>
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="6" style="text-align: center">Oops, nothing found here :(</td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
              <div class="d-flex justify-content-center">
                {{ $transactions->appends(request()->input())->links() }}
              </div>
            </div>
            <div class="card-footer">
              Showing {{ $transactions->firstItem() }} to {{ $transactions->lastItem() }} of
              {{ $transactions->total() }} entries
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

@endsection

@section('javascript')

@endsection
