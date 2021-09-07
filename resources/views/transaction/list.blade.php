@extends('dashboard.base')

@section('content')
  <div class="container-fluid">
    <div class="fade-in">
      <div class="row">
        <div class="col-xl-7 col-lg-10">
          <div class="card">
            <div class="card-header">
              <div class="h4 m-0 my-1 text-nowrap">Transaction List</div>
            </div>
            <div class="card-body pb-2">
              @if (session('status'))
                <div class="alert alert-success alert-dismissible fade show">
                  {!! session('status') !!}
                  <button
                    type="button"
                    class="close"
                    data-dismiss="alert"
                  >
                    <span>&times;</span>
                  </button>
                </div>
              @endif

              <form
                id="filter"
                action="{{ route('transactions.index') }}"
              ></form>

              <div class="d-flex flex-column-reverse flex-md-row justify-content-between">
                <div class="input-group w-auto mb-3 rounded">
                  <input
                    type="text"
                    name="search"
                    form="filter"
                    class="form-control border-0"
                    style="background-color: rgba(0,0,21,.05);"
                    value="{{ request('search') }}"
                    placeholder="Cari blok"
                  >
                  <div class="input-group-append">
                    <button
                      type="submit"
                      class="btn btn-warning"
                      form="filter"
                    ><i class="cil-search align-text-top"></i></button>
                  </div>
                </div>
              </div>

              <table class="table table-responsive-sm table-striped table-borderless text-nowrap m-0">
                <thead class="border-bottom">
                  <tr>
                    <th>CIF</th>
                    <th>Unit</th>
                    <th>Period</th>
                    <th>Approved At</th>
                    <th class="text-right">Amount</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>
                  @forelse ($transactions as $transaction)
                    <tr>
                      <th class="align-middle">#{{ $transaction->unit->customer_id }}</th>
                      <td class="align-middle">{{ $transaction->unit->name }}</td>
                      <td class="align-middle">{{ $transaction->period->formatLocalized('%b %Y') }}</td>
                      <td class="align-middle">{!! $transaction->approved_at ? $transaction->approved_at->diffForHumans() : '<span class="badge badge-dark">None</span>' !!}</td>
                      <td class="align-middle text-right">{{ number_format($transaction->payments_sum_payment_transactionamount / 2) }}</td>
                      <td class="align-middle text-right">
                        <div class="btn-group">
                          <button
                            class="btn btn-warning btn-sm dropdown-toggle"
                            type="button"
                            data-toggle="dropdown"
                          >Action</button>
                          <div class="dropdown-menu">
                            <a
                              class="dropdown-item font-weight-bold"
                              href="{{ route('transactions.show', $transaction->id) }}"
                            ><i class="cil-description"></i>&nbsp;View</a>
                            @if ($transaction->approved_at)
                              <a
                                class="dropdown-item font-weight-bold"
                                href="{{ route('transactions.print', $transaction->id) }}"
                                target="_blank"
                                rel="noopener noreferrer"
                              ><i class="cil-print"></i>&nbsp;Print</a>
                            @endif
                          </div>
                        </div>
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td
                        colspan="6"
                        class="text-center p-4"
                      >Oops, nothing found here :(</td>
                    </tr>
                  @endforelse
                </tbody>
              </table>

              <div class="d-flex justify-content-center mt-4 mb-0">
                {{ $transactions->appends(request()->input())->links() }}
              </div>

              <small class="text-muted">
                Showing {{ $transactions->count() }} of <a
                  href="{{ substr($transactions->url(1), 0, -1) . 'all' }}"
                  class="text-muted"
                >{{ $transactions->total() }}</a>
              </small>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

@endsection

@section('javascript')

@endsection
