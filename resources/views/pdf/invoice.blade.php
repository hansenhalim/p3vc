<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Invoice</title>
  <style>
    body {
      font-family: Helvetica, sans-serif;
      font-size: 11pt;
    }

    table {
      border-collapse: collapse;
    }

    th,
    td {
      padding: 6pt;
    }

  </style>
</head>

<body>
  <table>
    <tr>
      <td colspan="4" style="text-align: center; font-size: 24pt; font-weight: bold; padding-bottom: 24pt">IURAN
        KEBERSIHAN DAN KEAMANAN (IKK)</td>
    </tr>
    <tr>
      <td style="text-align: right; font-weight: bold; white-space: nowrap; width: 0">No. Bukti Pembayaran IKK :</td>
      <td style="width: 184px">P3VC/{{ $transaction->unit->customer_id . '/' . $transaction->periodInRoman . '/' . $transaction->created_at->setTimezone('Asia/Jakarta')->year}}</td>
      <td colspan="2" rowspan="2" style="font-size: 30pt; padding: 0; vertical-align: top">P3VC</td>
    </tr>
    <tr>
      <td style="text-align: right; font-weight: bold">Tanggal Transaksi :</td>
      <td>{{ $transaction->created_at->setTimezone('Asia/Jakarta')->format('d-m-Y') }}</td>
    </tr>
    <tr>
      <td style="text-align: right; font-weight: bold">Luas Tanah :</td>
      <td>{{ $transaction->unit->area_sqm }} m<sup>2</sup></td>
      <td colspan="2" rowspan="2" style="font-size: 10pt; padding: 0; vertical-align: top">Komplek Perumahan Villa Citra
        Blok.E No.12 B, Kelurahan
        Jagabaya III, Kecamatan Way Halim, Kota Bandar Lampung, Lampung 35122</td>
    </tr>
    <tr>
      <td style="text-align: right; font-weight: bold">Blok :</td>
      <td>{{ $transaction->unit->name }}</td>
    </tr>
    <tr>
      <td style="text-align: right; font-weight: bold">Periode :</td>
      <td>{{ $transaction->period->formatLocalized('%B %Y') }}</td>
      <td colspan="2" style="font-size: 10pt; padding: 0; vertical-align: top">WA : 0821 8108 8088</td>
    </tr>
    <tr>
      <td style="text-align: right; font-weight: bold">Nama :</td>
      <td colspan="3">{{ $transaction->unit->customer->name }}</td>
    </tr>
    <tr>
      <td colspan="4">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="4" style="background: black; color: white; text-align: center; font-weight: bold">Credit</td>
    </tr>
    @foreach ($transaction->credits as $payment)
      <tr>
        <td colspan="2"></td>
        <td style="text-align: right; font-weight: bold;">{{ $payment->name }}</td>
        <td style="text-align: right">IDR {{ number_format($payment->pivot->amount) }}</td>
      </tr>
    @endforeach
    <tr>
      <td colspan="4" style="background: black; color: white; text-align: center; font-weight: bold">Debit</td>
    </tr>
    @foreach ($transaction->debits as $payment)
      <tr>
        <td colspan="2"></td>
        <td style="text-align: right; font-weight: bold;">{{ $payment->name }}</td>
        <td style="text-align: right">IDR {{ number_format($payment->pivot->amount) }}</td>
      </tr>
    @endforeach
    <tr>
      <td colspan="2"></td>
      <td style="text-align: right; font-weight: bold;">SISA</td>
      <td style="text-align: right; border-top: 1px solid black">IDR {{ number_format($transaction->balance) }}</td>
    </tr>
    <tr>
      <td colspan="4" style="text-align: center; font-weight: bold">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="4" style="background: black; color: white; text-align: center; font-weight: bold; border: 1px solid black">Terbilang</td>
    </tr>
    <tr>
      <td colspan="4" style="text-align: center; border: 1px solid black">{{ $transaction->balanceSpellout }} rupiah</td>
    </tr>
  </table>
</body>

</html>
