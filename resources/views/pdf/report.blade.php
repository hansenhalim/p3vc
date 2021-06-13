<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Report</title>
  <style>
    @page { margin: 20px; }

    table {
      width: 100%;
      font-size: 0.7em;
      white-space: nowrap;
      border-collapse: collapse;
      font-family: 'Courier New', monospace;
    }

    table, th, td {
      border: 1px solid black;
    }

    thead {
      background: black;
      color: white;
    }

    tr:nth-child(even) {
      background: #f2f2f2;
    }

  </style>
</head>
<body>
  @isset($transactions)
  <table>
    <thead>
      <tr>
        <th style="text-align: left">CIF</th>
        <th style="text-align: left">Unit</th>
        <th style="text-align: left">Period</th>
        <th style="text-align: left">Created At</th>
        <th style="text-align: left">Approved At</th>
        <th style="text-align: right">Amount</th>
        <th style="text-align: right">TAGIHAN</th>
        <th style="text-align: right">DENDA</th>
        <th style="text-align: right">T.SALDO</th>
        <th style="text-align: right">OTHER</th>
        <th style="text-align: right">BANK</th>
        <th style="text-align: right">TUNAI</th>
        <th style="text-align: right">LINKAJA</th>
        <th style="text-align: right">HUTANG</th>
        <th style="text-align: right">DISKON</th>
        <th style="text-align: right">SALDO</th>
        <th style="text-align: right">B.HUTANG</th>
      </tr>
    </thead>
    <tbody>
      @forelse ($transactions as $transaction)
        <tr>
          <th style="text-align: left">#{{ $transaction->unit->customer_id }}</th>
          <td style="text-align: left">{{ $transaction->unit->name }}</td>
          <td style="text-align: left">{{ $transaction->period->formatLocalized('%b %Y') }}</td>
          <td style="text-align: left">{{ $transaction->created_at->setTimezone('Asia/Jakarta')->format('d/m/y H:i') }}</td>
          <td style="text-align: left">{{ $transaction->approved_at->setTimezone('Asia/Jakarta')->format('d/m/y H:i') }}</td>
          <td style="text-align: right">{{ number_format($transaction->amount) }}</td>
          @foreach ($transaction->paymentDetails as $paymentDetail)
            <td style="text-align: right">{{ number_format($paymentDetail) }}</td>
          @endforeach
        </tr>
        @if ($loop->last)
          <tr>
            <th colspan="5">Total(s)</th>
            <th style="text-align: right">{{ number_format($transactions->paymentDetailsSumsSum) }}</th>
            @foreach ($transactions->paymentDetailsSums as $paymentDetailsSum)
              <th style="text-align: right">{{ number_format($paymentDetailsSum) }}</th>
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
  @endisset
</body>
</html>