@extends('dashboard.base')

@section('content')
  <div class="container-fluid">
    <div class="fade-in">
      <div class="row">
        <div class="col-xl-6 col-md-8">
          <div class="card">
            <x-card-header><code>scan_invoice</code></x-card-header>
            <div class="card-body">
              <div
                id="buttonAndInfo"
                class="d-flex flex-column align-items-center"
              >
                <img
                  src="{{ asset('/svg/scan.svg') }}"
                  class="w-50 mb-2"
                >
                <button
                  id="scanButton"
                  class="btn btn-youtube font-weight-bold font-xl mb-2"
                  onclick="scanButtonPressed();"
                >SCAN INVOICE</button>
                <small
                  id="errorMsg"
                  class="d-none"
                >QR tidak valid.</small>
                <a
                  id="invoiceLink"
                  class="btn btn-success font-weight-bold font-xl mb-2 d-none"
                  href="#"
                  target="_blank"
                  rel="noopener noreferrer"
                >PRINT INVOICE</a>
              </div>
              <div
                id="reader"
                width="600px"
              ></div>
            </div>
          </div>
        </div>
        <div class="col-xl-5 col-md-8">
          <div class="card">
            <x-card-header><code>unapprove_trx</code></x-card-header>
            <div class="card-body">
              <x-alert></x-alert>
              @if ($errors->any())
                <div class="alert alert-danger">
                  <ul>
                    @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                    @endforeach
                  </ul>
                </div>
              @endif
              <form
                action="{{ route('master.unapprove') }}"
                method="post"
              >
                @csrf
                @method('PUT')
                <label>transaction_id</label>
                <input
                  type="text"
                  name="transaction_id"
                  value="{{ request()->get('transaction_id') }}"
                >
                <input
                  type="submit"
                  value="send"
                >
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>



@endsection

@section('javascript')

  <script
    src="https://unpkg.com/html5-qrcode"
    type="text/javascript"
  ></script>

  <script>
    buttonAndInfo = document.getElementById('buttonAndInfo')
    scanButton = document.getElementById('scanButton')
    invoiceLink = document.getElementById('invoiceLink')
    errorMsg = document.getElementById('errorMsg')

    function onScanSuccess(decodedText, decodedResult) {
      // Handle on success condition with the decoded text or result.
      // console.log(`Scan result: ${decodedText}`, decodedResult)
      html5QrcodeScanner.clear()

      buttonAndInfo.classList.toggle('d-flex')
      buttonAndInfo.classList.toggle('d-none')

      raw = atob(decodedText)

      if (isJsonString(raw)) {
        invoiceLink.href = "{{ url('transactions') . '/' }}" + JSON.parse(raw) + "/print"
        scanButton.classList.toggle('d-none')
        invoiceLink.classList.toggle('d-none')
        return
      }
      errorMsg.classList.remove('d-none')
    }

    function isJsonString(str) {
      try {
        JSON.parse(str)
      } catch (e) {
        return false
      }
      return true
    }

    var html5QrcodeScanner = new Html5QrcodeScanner(
      "reader", {
        fps: 10,
        qrbox: 250
      })
    // html5QrcodeScanner.render(onScanSuccess)

    function scanButtonPressed() {
      buttonAndInfo.classList.toggle('d-flex')
      buttonAndInfo.classList.toggle('d-none')
      errorMsg.classList.add('d-none')
      html5QrcodeScanner.render(onScanSuccess)
    }
  </script>

@endsection
