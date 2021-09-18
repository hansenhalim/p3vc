@extends('dashboard.base')

@section('content')
  <div class="container-fluid">
    <div class="fade-in">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <x-card-header>Transaction Report</x-card-header>
            <div class="card-body">
              <x-alert></x-alert>
              <form
                action="{{ route('transactions.report') }}"
                method="get"
              >
                <div class="row">
                  <div class="col-md-6 col-xl-4 mb-2">
                    <div
                      id="reportrange"
                      class="border-0 form-control mb-2"
                      style="cursor: pointer; background-color: rgba(0,0,21,.05)"
                    >
                      <i class="cil-calendar align-text-top"></i>&nbsp;&nbsp;<span></span>
                      <input
                        type="hidden"
                        name="dateFrom"
                      >
                      <input
                        type="hidden"
                        name="dateTo"
                      >
                    </div>
                  </div>
                  <div class="col d-flex mb-3">
                    <button
                      type="submit"
                      class="btn btn-warning flex-grow-1 flex-md-grow-0"
                    ><i class="cil-chart-line align-text-top"></i>&nbsp;Generate</button>
                    @isset($transactions)
                      <a
                        href="{{ route('transactions.report.print', request()->input()) }}"
                        class="btn btn-ghost-light ml-2"
                        style="color: black"
                      ><i class="cil-cloud-download align-text-bottom"></i></a>
                    @endisset
                  </div>
                </div>
              </form>
              @isset($transactions)
                <table class="table table-responsive table-striped table-borderless text-nowrap m-0">
                  <thead class="border-bottom">
                    <tr>
                      <th>CIF</th>
                      <th>Unit</th>
                      <th>Period</th>
                      <th>Created At</th>
                      <th>Approved At</th>
                      <th class="text-right">Amount</th>
                      <th class="text-right">TAGIHAN</th>
                      <th class="text-right">DENDA</th>
                      <th class="text-right">T.SALDO</th>
                      <th class="text-right">OTHER</th>
                      <th class="text-right">BANK</th>
                      <th class="text-right">TUNAI</th>
                      <th class="text-right">LINKAJA</th>
                      <th class="text-right">HUTANG</th>
                      <th class="text-right">DISKON</th>
                      <th class="text-right">SALDO</th>
                      <th class="text-right">B.HUTANG</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse ($transactions as $transaction)
                      <tr>
                        <th class="align-middle">#{{ $transaction->unit->customer_id }}</th>
                        <td class="align-middle">{{ $transaction->unit->name }}</td>
                        <td class="align-middle">{{ $transaction->period->formatLocalized('%B %Y') }}</td>
                        <td class="align-middle">{{ $transaction->created_at->setTimezone('Asia/Jakarta')->format('d/m/y H:i') }}</td>
                        <td class="align-middle">{{ $transaction->approved_at->setTimezone('Asia/Jakarta')->format('d/m/y H:i') }}</td>
                        <td class="align-middle text-right">{{ number_format($transaction->amount) }}</td>
                        @foreach ($transaction->paymentDetails as $paymentDetail)
                          <td class="align-middle text-right">{{ number_format($paymentDetail) }}</td>
                        @endforeach
                      </tr>
                    @empty
                      <tr>
                        <td
                          colspan="17"
                          class="text-center p-4"
                        >Oops, nothing found here :(</td>
                      </tr>
                    @endforelse
                  </tbody>
                  @if ($transactions->onFirstPage() && $transactions->first())
                    <thead class="border-top">
                      <tr>
                        <th colspan="5">Totals</th>
                        <th class="align-middle text-right">{{ number_format($transactions->paymentDetailsSumsSum) }}</th>
                        @foreach ($transactions->paymentDetailsSums as $paymentDetailsSum)
                          <th class="align-middle text-right">{{ number_format($paymentDetailsSum) }}</th>
                        @endforeach
                      </tr>
                    </thead>
                  @endif
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
              @endisset
            </div>
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
        applyButtonClasses: "btn-warning",
        alwaysShowCalendars: true,
        ranges: {
          'Today': [moment(), moment()],
          'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
          'Last 7 Days': [moment().subtract(6, 'days'), moment()],
          // 'Last 30 Days': [moment().subtract(29, 'days'), moment()],
          // 'This Month': [moment().startOf('month'), moment().endOf('month')],
          // 'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        maxSpan: {
          days: 7
        },
      }, cb)

      cb(start, end)

    })
  </script>

@endsection

@section('javascript')

@endsection
