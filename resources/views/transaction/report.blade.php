@extends('dashboard.base')

@section('content')
  <div class="container-fluid">
    <div class="fade-in">
      <div class="row">
        <div class="col-xl-7">
          <div class="card">
            <div class="card-header">Transaction Report</div>
            <div class="card-body">
              @if (session('status'))
                <div class="alert alert-success alert-dismissible fade show">
                  {!! session('status') !!}
                  <button type="button" class="close" data-dismiss="alert">
                    <span>&times;</span>
                  </button>
                </div>
              @endif
              <form action="{{ route('transactions.report') }}" method="get">
                <div class="row">
                  <div class="col-6 mb-2">
                    <div id="reportrange"
                      class="border rounded p-2"
                      style="cursor: pointer">
                      <i class="cil-calendar align-text-top"></i>&nbsp;<span></span>
                      <input type="hidden" name="dateFrom">
                      <input type="hidden" name="dateTo">
                    </div>
                  </div>
                  <div class="col-6">
                    <button type="submit" class="btn btn-dark">Generate</button>
                  </div>
                </div>
              </form>
              @isset($transactions)
                <table class="table table-responsive-sm table-striped">
                  <thead>
                    <tr>
                      <th>CIF</th>
                      <th>Unit</th>
                      <th>Period</th>
                      <th>Approved At</th>
                      <th>Amount</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse ($transactions as $transaction)
                      <tr>
                        <th class="align-middle">#{{ $transaction->unit->customer_id }}</th>
                        <td class="align-middle">{{ $transaction->unit->name }}</td>
                        <td class="align-middle">{{ $transaction->period->formatLocalized('%b %Y') }}</td>
                        <td class="align-middle">{!! $transaction->approved_at ? $transaction->approved_at->diffForHumans() : '<span class="badge bg-danger text-white">None</span>' !!}</td>
                        <td class="align-middle">{{ number_format($transaction->amount) }}</td>
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
              @endisset
            </div>
            @isset($transactions)
              <div class="card-footer">
                Showing {{ $transactions->firstItem() }} to {{ $transactions->lastItem() }} of {{ $transactions->total() }} entries
              </div>
            @endisset
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    $(function() {

      var start = moment()
      var end = moment()

      function cb(start, end) {
        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
        $('#reportrange input[name="dateFrom"]').val(start.format('YYYY-MM-DD'))
        $('#reportrange input[name="dateTo"]').val(end.format('YYYY-MM-DD'))
      }

      $('#reportrange').daterangepicker({
        startDate: start,
        endDate: end,
        alwaysShowCalendars: true,
        ranges: {
          'Today': [moment(), moment()],
          'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
          'Last 7 Days': [moment().subtract(6, 'days'), moment()],
          'Last 30 Days': [moment().subtract(29, 'days'), moment()],
          'This Month': [moment().startOf('month'), moment().endOf('month')],
          'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
      }, cb)

      cb(start, end)

    })

  </script>

@endsection

@section('javascript')

@endsection
