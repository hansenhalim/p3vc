<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>{{ $transaction->title }}</title>
  <style>
    @page {
      margin: 0px;
    }

    body {
      font-family: Helvetica, sans-serif;
      font-size: 12pt;
    }

    table {
      border-collapse: collapse;
    }

    th,
    td {
      padding: 1px 0;
      /* border: 1px solid black; */
    }

    th {
      text-align: left;
    }


    ul {
      margin-left: 0;
      margin-top: 3px;
      padding-left: 0;
      margin-bottom: 8px;
      list-style: none;
    }

    li {
      padding-left: 1em;
      text-indent: -1em;
    }

    li:before {
      content: "•";
      padding-right: 3px;
      font-size: 7pt;
    }

    .cell-1,
    .cell-2,
    .cell-3 {
      text-align: center;
      color: white;
      height: 1.3cm;
    }

    .cell-1 {
      width: 1.5cm;
    }

    .cell-2 {
      width: 4.5cm;
    }

    .cell-3 {
      width: 6cm;
    }

    .header {
      border-bottom: 4px solid #4b81aa;
    }

    .subheader {
      padding-bottom: 10px;
      border-bottom: 1.5px solid #4b81aa;
    }

    .footer {
      padding: 13px 0;
      border-top: 4px solid #4b81aa;
    }

    .info {
      color: #c1272d;
      font-weight: bold;
      margin: 0;
    }

    .title {
      font-size: 70pt;
      font-weight: bold;
      line-height: 0.6;
      position: relative;
      left: -6px;
    }

    .subtitle {
      color: #c1272d;
      font-size: 15pt;
      font-weight: bold;
    }

    .watermark {
      position: absolute;
      z-index: -1;
      width: 15.1cm;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
    }

    .logo {
      text-align: right;
      vertical-align: top;
      padding-bottom: 25px;
    }

    .right {
      text-align: right;
    }

    .center {
      text-align: center;
    }

    .space {
      padding: 2.5px 0;
    }

    .blue {
      color: #4b81aa;
    }

    .pddt {
      padding-top: 10px;
    }

    .mrgt {
      margin-top: 5px;
      margin-bottom: 0px;
    }

    .small {
      font-size: 8pt;
    }

    .shadow {
      width: 100%;
      position: absolute;
      bottom: 0;
      transform: translateY(-100%);
    }

    .bottom {
      vertical-align: bottom;
      padding-bottom: 25px;
    }

  </style>
</head>

<body>
  <img src="data:image/svg+xml;base64, {!! base64_encode(file_get_contents('svg/P3VC_watermark.svg')) !!}" class="watermark">
  <img src="data:image/png;base64, {!! base64_encode(file_get_contents('img/blue_shadow.png')) !!}" class="shadow">
  <table>
    <tr>
      <td class="cell-1">&nbsp;</td>
      <td class="cell-3">&nbsp;</td>
      <td class="cell-1">&nbsp;</td>
      <td class="cell-2">&nbsp;</td>
      <td class="cell-3">&nbsp;</td>
      <td class="cell-1">&nbsp;</td>
    </tr>
    <tr>
      <td class="space">&nbsp;</td>
      <td colspan="3" class="header">
        <div class="title">invoice</div>
        <div class="subtitle">IURAN KEAMANAN DAN KEBERSIHAN P3VC</div>
      </td>
      <td class="logo header"><img src="data:image/svg+xml;base64, {!! base64_encode(file_get_contents('svg/P3VC.svg')) !!}" style="width: 2.6cm"></td>
      <td></td>
    </tr>

    <x-table-empty-space></x-table-empty-space>

    <tr>
      <td class="space">&nbsp;</td>
      <th>Invoice to:</th>
      <td colspan="2">{{ $transaction->customer_name }}</td>
      <td colspan="2">{{ $transaction->invoiceNumber }}</td>
    </tr>
    <tr>
      <td class="space">&nbsp;</td>
      <th></th>
      <td colspan="2">{{ $transaction->unit_name }}</td>
      <td colspan="2">{{ $transaction->created_at->setTimezone('Asia/Jakarta')->format('d-m-Y') }}</td>
    </tr>
    <tr>
      <td class="space">&nbsp;</td>
      <th></th>
      <td colspan="2">{{ $transaction->area_sqm }} m&#178;</td>
      <td colspan="2">
        {{ $transaction->period->formatLocalized('%B %Y') === 'Januari 1970' ? '' : $transaction->period->formatLocalized('%B %Y') }}
      </td>
    </tr>

    <x-table-empty-space></x-table-empty-space>

    <x-payment-detail :payments="$transaction->credits" name="Tagihan" :total="$transaction->credits_sum_amount">
    </x-payment-detail>

    <x-payment-detail :payments="$transaction->debits" name="Pembayaran" :total="$transaction->debits_sum_amount">
    </x-payment-detail>

    @isset($transaction->balance)
      <x-payment-total name="Sisa" :amount="$transaction->balance"></x-payment-total>
    @endisset

    @isset($transaction->debt)
      <x-payment-total name="Hutang" :amount="$transaction->debt"></x-payment-total>
    @endisset

  </table>
  <table style="position: absolute; bottom: 0; transform: translateY(-100%)">
    <tr>
      <td class="cell-1">&nbsp;</td>
      <td class="cell-3">&nbsp;</td>
      <td class="cell-1">&nbsp;</td>
      <td class="cell-2">&nbsp;</td>
      <td class="cell-3">&nbsp;</td>
      <td class="cell-1">&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td class="bottom">
        <img style="border: 2.5px solid black" src="data:image/svg+xml;base64, {{ $qrcode }} ">
      </td>
      <td colspan="3" class="bottom">
        <p class="info">Pembayaran dapat dilakukan melalui:</p>
        <ul class="small">
          <li>Transfer BCA 020 175 3847 / Yok Silado or Dery Jaya</li>
          <li>Transfer Bank Mandiri 114 003 388 8895 / Paguyuban Pengelola Perumahan Villa Citra</li>
          <li>Aplikasi LinkAja</li>
          <li>Gerai Indomaret</li>
        </ul>
        <p class="info">Kantor P3VC</p>
        <p class="small mrgt">Komplek Perumahan Villa Citra Blok E No. 12 B <br>Kelurahan Jagabaya III, Kecamatan Way
          Halim,
          <br>Kota Bandar Lampung, Lampung 35122 <br>WhatsApp 0821-8108-8088
        </p>
      </td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="4" class="small footer">
        <p>BUKTI PEMBAYARAN INI DIHASILKAN OLEH SISTEM DAN TIDAK MEMERLUKAN TANDATANGAN PEJABAT P3VC.<br> HARAP DISIMPAN
          BAIK-BAIK SEBAGAI BUKTI PEMBAYARAN YANG SAH.</p>
      </td>
      <td>&nbsp;</td>
    </tr>
  </table>
</body>

</html>
