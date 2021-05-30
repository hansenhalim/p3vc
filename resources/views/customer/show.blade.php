@extends('dashboard.base')

@section('content')
  <div class="modal fade" id="paymentModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Add Payment</h5>
          <button type="button" class="close" data-dismiss="modal">
            <span>&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group row">
            <div class="col-md-4 mb-3 mb-md-0">
              <select class="custom-select" id="pymnt-mthd">
                <option value="4">Other</option>
                <option value="5">Bank Transfer</option>
                <option value="6">Tunai</option>
                <option value="7">Linkaja</option>
                <option value="8">Hutang</option>
                <option value="9">Diskon</option>
                <option value="10">Saldo Unit</option>
              </select>
            </div>
            <div class="col">
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text">Rp.</span>
                </div>
                <input id="pymnt-amnt" type="text" class="form-control" placeholder="Amount">
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" onclick="addToPayments()">OK&nbsp;&check;</button>
        </div>
      </div>
    </div>
  </div>
  <div class="container-fluid">
    <div class="fade-in">
      <div class="row">
        <div class="col-xl-4 col-md-6">
          <a class="btn btn-link mb-2" href="{{ route('customers.index') }}">&lt;&lt; Return</a>
          <div class="card">
            <div class="card-header">Customer Show</div>
            <div class="card-body">
              @if (session('status'))
                <div class="alert alert-success">
                  {!! session('status') !!}
                </div>
              @endif
              <div class="form-group row">
                <label class="col-md-3 col-form-label">CIF</label>
                <div class="col">
                  <input class="form-control" type="text" value="{{ $customer->id }}" disabled>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 col-form-label">Name</label>
                <div class="col">
                  <input class="form-control" type="text" value="{{ $customer->name }}" disabled>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 col-form-label">Phone</label>
                <div class="col">
                  <input class="form-control" type="text" value="{{ $customer->phone_number }}" disabled>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-xl-8">
          <div class="card">
            <form action="{{ route('customers.store') }}" method="post">
              @csrf
              <div class="card-header">Unit List</div>
              <div class="card-body">
                <table class="table table-responsive-md">
                  @foreach ($units as $unit)
                    <thead class="thead-dark">
                      <tr>
                        <th></th>
                        <th class="text-center">#</th>
                        <th>Name</th>
                        <th>Customer</th>
                        <th colspan="2">Cluster</th>
                        <th class="text-right">Area&nbsp;(m<sup>2</sup>)</th>
                        <th class="text-right">Balance</th>
                        <th class="text-right">Credit</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr class="table-light">
                        <th class="text-right"><input type="checkbox" class="unt-chck"></th>
                        <th class="text-center">{{ $loop->iteration }}</th>
                        <th><a class="text-dark" href="{{ route('units.show', ['unit' => $unit->id]) }}">{{ $unit->name }}</a></th>
                        <th><a class="text-dark" href="{{ route('customers.show', ['customer' => $unit->customer->id]) }}">{{ $unit->customer->name }}</a></th>
                        <th colspan="2"><a class="text-dark" href="{{ route('clusters.show', ['cluster' => $unit->cluster->id]) }}">{{ $unit->cluster->name }}</a></th>
                        <td class="text-right">{{ $unit->area_sqm }}</td>
                        <td class="text-right">{{ $unit->balance }}</td>
                        <td class="text-right">{{ number_format($unit->cluster->prices->last()->cost * ($unit->cluster->prices->last()->per == 'sqm' ? $unit->area_sqm : 1)) }}</td>
                      </tr>
                      <thead class="thead-light">
                        <tr>
                          <th colspan="5"></th>
                          <th class="text-center">#</th>
                          <th>Periode</th>
                          <th class="text-right">Iuran</th>
                          <th class="text-right">Denda</th>
                        </tr>
                      </thead>
                      <tbody>
                        @forelse ($unit->months as $month)
                          <tr class="mth" id="{{ $unit->id.$month['period']->format('my') }}">
                            <th colspan="4"></th>
                            <th class="text-right"><input type="checkbox" class="mth-chck"></th>
                            <th class="text-center">{{ $loop->iteration }}</th>
                            <td>{{ $month['period']->format('F Y') }}</td>
                            <td class="text-right">{{ number_format($month['credit']) }}</td>
                            <td class="text-right">{{ number_format($month['fine']) }}</td>
                          </tr>
                          <tr class="table-secondary table-sm" data-month="{{ $unit->id.$month['period']->format('my') }}">
                            <th colspan="8" class="text-right">Tagihan</th>
                            <th class="text-right">{{ number_format($month['credit'] + $month['fine']) }}</th>
                          </tr>
                          <tr class="table-secondary table-borderless table-sm" data-month="{{ $unit->id.$month['period']->format('my') }}">
                            <th colspan="8" class="text-right"><i class="cil-trash text-danger dlt-pymnt" style="cursor: pointer;"></i>&nbsp;Bank Transfer</th>
                            <th class="text-right">{{ number_format($month['credit'] + $month['fine']) }}</th>
                          </tr>
                          <tr class="table-secondary table-borderless table-sm" data-month="{{ $unit->id.$month['period']->format('my') }}">
                            <th colspan="10" class="text-right">
                              <button data-toggle="modal" data-target="#paymentModal" type="button" class="btn btn-sm btn-square btn-outline-success"><i class="cil-wallet"></i>&nbsp;Add Payments</button>
                            </th>
                          </tr>
                          <tr class="table-secondary table-borderless table-sm" data-month="{{ $unit->id.$month['period']->format('my') }}">
                            <th colspan="8" class="text-right">Sisa</th>
                            <th class="text-right text-danger">{{ number_format($month['credit'] + $month['fine']) }}</th>
                          </tr>
                        @empty
                          <tr class="table-success">
                            <td colspan="10" style="text-align: center">No tunggak-tunggak club :)</td>
                          </tr>
                        @endforelse
                        <tr style="background: white">
                          <td colspan="10"></td>
                        </tr>
                      </tbody>
                    </tbody>
                  @endforeach
                </table>
              </div>
              <div class="card-footer d-flex justify-content-between">
                <button id="dbg-btn" type="button"></i>debug-btn</button>
                <button id="sbmt-btn" type="submit" class="btn btn-primary btn-secondary" disabled></i>Submit</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script>
    submitBtn = document.getElementById('pymnt-btn')
    submitBtn = document.getElementById('sbmt-btn')
    debugBtn = document.getElementById('dbg-btn')
    unitChecks = document.getElementsByClassName('unt-chck')
    monthChecks = document.getElementsByClassName('mth-chck')
    paymentMethod = document.getElementById('pymnt-mthd')
    paymentAmount = document.getElementById('pymnt-amnt')
    deletePayments = document.getElementsByClassName('dlt-pymnt')

    function validateSubmission() {
      submitBtn.disabled = !submitBtn.disabled
      submitBtn.classList.toggle('btn-secondary')
    }

    function echo() {
      console.log('echo function executed :)')
    }

    function addToPayments() {
      method = paymentMethod.value
      amount = paymentAmount.value
      var paymentModal = document.getElementById('paymentModal')
      var modal = new coreui.Modal(paymentModal) // initialized with defaults
      if (parseInt(method) && parseInt(amount)) {
        modal.hide()
      }
    }

    function togglePayment(month, show = false) {
      paymentSections = document.querySelectorAll('[data-month="' + month.getAttribute('id') + '"]')
      for (paymentSection of paymentSections) show ? paymentSection.classList.remove('d-none') : paymentSection.classList.add('d-none')
    }
    
    debugBtn.addEventListener('click', validateSubmission)

    for (unitCheck of unitChecks) {
      unitCheck.addEventListener('change', function() {
        unitCheckWrapper  = this.parentNode
        unit              = unitCheckWrapper.parentNode.parentNode
        monthsHeader      = unit.nextElementSibling
        months            = monthsHeader.nextElementSibling
        unitMonthChecks   = months.getElementsByClassName('mth-chck')

        for (unitMonthCheck of unitMonthChecks) unitMonthCheck.checked = this.checked

        months = months.getElementsByClassName('mth')
        for (month of months) togglePayment(month, this.checked)

      })
    }

    for (monthCheck of monthChecks) {
      monthCheck.addEventListener('change', function () {
        monthCheckWrapper = this.parentNode
        month             = monthCheckWrapper.parentNode
        months            = month.parentNode
        unitMonthChecks   = months.getElementsByClassName('mth-chck')
        monthsHeader      = months.previousElementSibling
        monthUnit         = monthsHeader.previousElementSibling
        monthUnitCheck    = monthUnit.getElementsByClassName('unt-chck')[0]

        checkFlag = true
        for (unitMonthCheck of unitMonthChecks) if (checkFlag) checkFlag = unitMonthCheck.checked
        monthUnitCheck.checked = checkFlag

        togglePayment(month, this.checked)

      })
    }

    for (deletePayment of deletePayments) {
      deletePayment.addEventListener('click', function () {
        this.parentNode.parentNode.remove()
      })
    }

  </script>

@endsection

@section('javascript')

@endsection
