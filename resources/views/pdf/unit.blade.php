<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Surat Tunggakan {{ $unit->is_kios ? 'Maintenance' : 'IKK' }} - {{ $unit->name }}</title>
  <style>
    @page {
      margin: 1.6cm 1.8cm;
    }

    body {
      font-family: Helvetica, sans-serif;
      font-size: 11pt;
      color: #000000;
      line-height: 1.5;
    }

    table {
      border-collapse: collapse;
    }

    p {
      margin: 0 0 10px;
      text-align: justify;
    }

    .kop {
      width: 100%;
      border-bottom: 3px double #7F915A;
      padding-bottom: 14px;
    }

    .kop td {
      vertical-align: middle;
    }

    .kop .logo {
      width: 2.8cm;
      font-family: 'Comic Sans MS', 'Trebuchet MS', sans-serif;
      font-size: 28pt;
      font-weight: bold;
      color: #7F915A;
      text-align: center;
    }

    .kop .text {
      text-align: center;
    }

    .kop .org {
      font-size: 13pt;
      font-weight: bold;
      color: #7F915A;
      line-height: 1.1;
    }

    .kop .addr {
      font-size: 9.5pt;
      color: #333333;
      margin-top: 4px;
      line-height: 1.4;
    }

    .date {
      text-align: right;
      margin: 16px 0 12px;
    }

    .meta {
      margin-bottom: 12px;
    }

    .meta td {
      padding: 0;
      vertical-align: top;
    }

    .meta .label {
      width: 4.6cm;
    }

    .meta .sep {
      width: 0.4cm;
    }

    .amount {
      width: auto;
      margin: 12px 0;
    }

    .amount th,
    .amount td {
      border: 1px solid #000000;
      padding: 7px 11px;
      vertical-align: top;
    }

    .amount th {
      text-align: center;
    }

    .amount td {
      text-align: left;
    }

    .amount .nom {
      white-space: nowrap;
    }

    .amount .total-label {
      font-weight: bold;
    }

    .footer {
      margin-top: 18px;
    }

    .footer td {
      vertical-align: bottom;
    }

    .footer .qr img {
      width: 2.8cm;
    }

    .footer .banner {
      padding-left: 16px;
    }

    .footer .banner img {
      width: 4cm;
      border: 1px solid #999999;
    }
  </style>
</head>

<body>
  <table class="kop">
    <tr>
      <td class="logo">P3VC</td>
      <td class="text">
        <div class="org">PERKUMPULAN PENGELOLAAN PERUMAHAN VILLA CITRA</div>
        <div class="addr">KOMPLEK PERUMAHAN VILLA CITRA 1 BLOK E - 12B&nbsp;&nbsp;WA. 08117998088<br>BANDAR LAMPUNG</div>
      </td>
    </tr>
  </table>

  <div class="date">Bandar Lampung, {{ $unit->letter_date }}</div>

  <p style="margin-bottom: 2px;">Kepada Yth,</p>
  <table class="meta">
    <tr>
      <td class="label">Bapak/Ibu/Saudara</td>
      <td class="sep">:</td>
      <td>{{ $unit->customer_name }}</td>
    </tr>
    <tr>
      <td class="label">{{ $unit->is_kios ? 'Kios' : 'Blok' }}</td>
      <td class="sep">:</td>
      <td>{{ $unit->name }}</td>
    </tr>
  </table>

  <p>Perihal : <u>Tunggakan Iuran {{ $unit->is_kios ? 'Maintenance Taman Kuliner Villa Citra' : 'Keamanan dan Kebersihan (IKK)' }}</u></p>

  <p>Dengan hormat,</p>

  <p>Dengan ini diberitahukan bahwa sampai dengan tanggal {{ $unit->cutoff_date }}, Bapak/Ibu/Saudara belum memenuhi
    kewajiban atas pembayaran Iuran {{ $unit->is_kios ? 'Maintenance Taman Kuliner Villa Citra' : 'Keamanan dan Kebersihan (IKK)' }} sebagai berikut&nbsp;:</p>

  <table class="amount">
    <tr>
      <th>PERIODE</th>
      <th>NOMINAL</th>
    </tr>
    <tr>
      <td>{{ $unit->period_label }}</td>
      <td class="nom">Rp. {{ number_format($unit->months_total, 0, ',', '.') }}</td>
    </tr>
    <tr>
      <td class="total-label">TOTAL</td>
      <td class="nom"><strong>Rp. {{ number_format($unit->months_total, 0, ',', '.') }}</strong></td>
    </tr>
  </table>

  <p>Untuk itu kami harap Bapak/Ibu/Saudara agar segera melakukan pembayaran seluruh tunggakan
    {{ $unit->is_kios ? 'Maintenance' : 'IKK' }} Bapak/Ibu/Saudara
    melalui rekening Perkumpulan Pengelolaan Perumahan Villa Citra (P3VC), pada Bank BCA dengan nomor rekening
    294.5200.888 atas nama Perkumpulan Pengelolaan Perumahan Villa Citra (P3VC).</p>

  <p>Apabila telah melakukan pembayaran, mohon konfirmasi bukti pembayaran transfer ke nomor WA 08117998088 dengan
    menyertakan nomor {{ $unit->is_kios ? 'Kios' : 'Blok' }}.</p>

  @unless ($unit->is_kios)
    @if ($unit->months_count <= 3)
  <p>Bilamana sampai tanggal {{ $unit->deadline_date }} tunggakan diatas tidak diselesaikan, maka mohon maaf kami akan
    memasang banner yang bertuliskan
    &ldquo;<strong>RUMAH/KAVLING INI MENUNGGAK PEMBAYARAN IKK</strong>&rdquo; di depan/pagar rumah Bapak/Ibu/Saudara,
    seperti gambar di bawah ini.</p>
    @else
  <p>Mohon maaf kami telah memasang banner yang bertuliskan
    &ldquo;<strong>RUMAH/KAVLING INI MENUNGGAK PEMBAYARAN IKK</strong>&rdquo; di depan/pagar rumah Bapak/Ibu/Saudara,
    seperti gambar di bawah ini.</p>

  <p>Bilamana Bapak/Ibu/Saudara sudah melakukan pembayaran atas tunggakan tersebut, maka banner akan kami lepas/copot
    dari rumah tersebut.</p>
    @endif
  @endunless

  <p>Harap abaikan surat ini apabila Bapak/Ibu/Saudara sudah membayar tunggakan
    {{ $unit->is_kios ? 'Maintenance Taman Kuliner Villa Citra' : 'IKK' }}.</p>

  <p>Atas perhatian dan kerjasamanya kami ucapkan terima kasih.</p>

  <table class="footer">
    <tr>
      <td class="qr">
        <img src="data:image/svg+xml;base64, {{ $qrcode }}">
      </td>
      @if (!$unit->is_kios && file_exists(public_path('img/banner.jpg')))
      <td class="banner">
        <img src="data:image/jpeg;base64, {!! base64_encode(file_get_contents(public_path('img/banner.jpg'))) !!}">
      </td>
      @endif
    </tr>
  </table>
</body>

</html>