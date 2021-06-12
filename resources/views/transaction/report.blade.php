@extends('dashboard.base')

@section('content')
  <div class="container-fluid">
    <div class="fade-in">
      <div class="row">
        <div class="col-12">
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
                  <div class="col-md-6 col-xl-4 mb-2">
                    <div id="reportrange" class="border rounded p-2" style="cursor: pointer">
                      <i class="cil-calendar align-text-top"></i>&nbsp;<span></span>
                      <input type="hidden" name="dateFrom">
                      <input type="hidden" name="dateTo">
                    </div>
                  </div>
                  <div class="col mb-2">
                    <button type="submit" class="btn btn-primary">Generate</button>
                    @isset($transactions)
                      <a class="btn btn-outline-primary" href="{{ route('transactions.report.print', request()->input()) }}" target="_blank" rel="noopener noreferrer"><i class="cil-cloud-download align-text-bottom"></i></a>
                    @endisset
                  </div>
                </div>
              </form>
              @isset($transactions)
                <table class="table table-responsive table-striped table-bordered text-nowrap">
                  <thead class="thead-dark">
                    <tr>
                      <th>CIF</th>
                      <th>Unit</th>
                      <th>Period</th>
                      <th>Created&nbsp;At</th>
                      <th>Approved&nbsp;At</th>
                      <th>Amount</th>
                      <th>TAGIHAN</th>
                      <th>DENDA</th>
                      <th>TOP&nbsp;UP&nbsp;SALDO</th>
                      <th>OTHER</th>
                      <th>BANK&nbsp;TRANSFER</th>
                      <th>TUNAI</th>
                      <th>LINKAJA</th>
                      <th>HUTANG</th>
                      <th>DISKON</th>
                      <th>SALDO&nbsp;UNIT</th>
                      <th>BAYAR&nbsp;HUTANG</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse ($transactions as $transaction)
                      <tr>
                        <th class="align-middle">#{{ $transaction->unit->customer_id }}</th>
                        <td class="align-middle">{{ $transaction->unit->name }}</td>
                        <td class="align-middle">{{ $transaction->period->formatLocalized('%b %Y') }}</td>
                        <td class="align-middle">{{ $transaction->created_at->setTimezone('Asia/Jakarta') }}</td>
                        <td class="align-middle">{{ $transaction->approved_at->setTimezone('Asia/Jakarta') }}</td>
                        <td class="align-middle text-right">{{ number_format($transaction->amount) }}</td>
                        @foreach ($transaction->paymentDetails as $paymentDetail)
                          <td class="align-middle text-right">{{ number_format($paymentDetail) }}</td>
                        @endforeach
                      </tr>
                      @if ($loop->last)
                        <tr class="bg-dark">
                          <th class="align-middle text-right text-center" colspan="5">Total(s)</th>
                          <th class="align-middle text-right">{{ number_format($transactions->paymentDetailsSumsSum) }}</th>
                          @foreach ($transactions->paymentDetailsSums as $paymentDetailsSum)
                            <th class="align-middle text-right">{{ number_format($paymentDetailsSum) }}</th>
                          @endforeach
                        </tr>
                      @endif
                    @empty
                      <tr>
                        <td colspan="17" style="text-align: center">Oops, nothing found here :(</td>
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
    function getQueryVariable(variable) {
      var query = window.location.search.substring(1)
      var vars = query.split("&")
      for (var i = 0; i < vars.length; i++) {
        var pair = vars[i].split("=");
        if (pair[0] == variable) return pair[1]
      }
      return undefined
    }

    $(function() {

      var start = moment(getQueryVariable('dateFrom'))
      var end = moment(getQueryVariable('dateTo'))

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
